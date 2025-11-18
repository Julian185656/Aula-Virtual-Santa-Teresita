<?php
require_once "../../../../model/db.php";

class JustificacionAsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Obtiene los cursos asignados al docente (igual que en Registrar / Historial).
     *
     * @param int $docenteId
     * @return array
     */
    public function obtenerCursosDocente(int $docenteId): array
    {
        try {
            $stmt = $this->pdo->prepare("CALL sp_asist_cursos_por_docente(:doc)");
            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->execute();

            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            return $cursos;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener cursos del docente: " . $e->getMessage());
        }
    }

    /**
     * Lista de ausencias pendientes de justificar para un docente,
     * con filtro opcional por curso y rango de fechas + paginación.
     *
     * Usa: sp_asist_ausencias_pendientes
     *
     * @param int         $docenteId
     * @param int         $cursoId      0 = todos los cursos del docente
     * @param string|null $fechaDesde   (formato yyyy-mm-dd o dd/mm/yyyy o null)
     * @param string|null $fechaHasta
     * @param int         $pagina
     * @param int         $limite
     *
     * @return array ['ausencias' => [...], 'total' => int]
     */
    public function obtenerAusenciasPendientes(
        int $docenteId,
        int $cursoId = 0,
        ?string $fechaDesde = null,
        ?string $fechaHasta = null,
        int $pagina = 1,
        int $limite = 15
    ): array {
        try {
            if ($pagina < 1)  $pagina = 1;
            if ($limite < 1)  $limite = 15;

            // Normalizamos, pero si vienen vacías las mandamos como NULL para que el SP use sus defaults.
            $desde = $this->normalizarFechaNullable($fechaDesde);
            $hasta = $this->normalizarFechaNullable($fechaHasta);

            $sql = "CALL sp_asist_ausencias_pendientes(
                        :docente,
                        :curso,
                        :desde,
                        :hasta,
                        :pagina,
                        :limite,
                        @total
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':docente', $docenteId, PDO::PARAM_INT);
            $stmt->bindParam(':curso',   $cursoId,   PDO::PARAM_INT);
            $stmt->bindParam(':desde',   $desde,     PDO::PARAM_STR);
            $stmt->bindParam(':hasta',   $hasta,     PDO::PARAM_STR);
            $stmt->bindParam(':pagina',  $pagina,    PDO::PARAM_INT);
            $stmt->bindParam(':limite',  $limite,    PDO::PARAM_INT);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            // Leer el total de registros para paginación
            $totalRow = $this->pdo
                ->query("SELECT @total AS TotalRegistros")
                ->fetch(PDO::FETCH_ASSOC);

            $total = isset($totalRow['TotalRegistros'])
                ? (int)$totalRow['TotalRegistros']
                : 0;

            return [
                'ausencias' => $rows,
                'total'     => $total
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener ausencias pendientes: " . $e->getMessage());
        }
    }

    /**
     * Marca una ausencia como justificada, guardando el comentario.
     *
     * Usa: sp_asist_marcar_justificada
     *
     * @param int    $cursoId
     * @param int    $estudianteId
     * @param string $fecha         (yyyy-mm-dd o dd/mm/yyyy)
     * @param int    $docenteId
     * @param string $comentario
     *
     * @return bool  true si se actualizó al menos 1 registro
     */
    public function marcarAusenciaJustificada(
        int $cursoId,
        int $estudianteId,
        string $fecha,
        int $docenteId,
        string $comentario
    ): bool {
        try {
            $fechaNorm = $this->normalizarFechaNullable($fecha);

            if (!$fechaNorm) {
                throw new \Exception("La fecha de la ausencia es obligatoria.");
            }

            // Acotamos el comentario a 255 caracteres por el tipo de columna
            $comentario = trim($comentario);
            if (strlen($comentario) > 255) {
                $comentario = substr($comentario, 0, 255);
            }

            $sql = "CALL sp_asist_marcar_justificada(
                        :curso,
                        :est,
                        :fecha,
                        :doc,
                        :comentario
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso',      $cursoId,      PDO::PARAM_INT);
            $stmt->bindParam(':est',        $estudianteId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha',      $fechaNorm,    PDO::PARAM_STR);
            $stmt->bindParam(':doc',        $docenteId,    PDO::PARAM_INT);
            $stmt->bindParam(':comentario', $comentario,   PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();

            // Opcionalmente podríamos verificar filas afectadas si el SP hiciera SELECT ROW_COUNT()
            // Pero con CALL no es directo, así que devolvemos true si no lanzó excepción.
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al marcar ausencia justificada: " . $e->getMessage());
        }
    }

    /**
     * Normaliza una fecha a 'Y-m-d', pero si está vacía/null devuelve NULL.
     */
    private function normalizarFechaNullable(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') {
            return null;
        }

        $fecha = trim($fecha);

        // Formato dd/mm/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        // Si viene como YYYY-mm-dd, recortamos a 10
        return substr($fecha, 0, 10);
    }
}
