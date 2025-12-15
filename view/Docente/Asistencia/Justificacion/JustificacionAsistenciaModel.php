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


    public function obtenerCursosDocente(int $docenteId): array
    {
        try {
            $sql = "
                SELECT c.Id_Curso, c.Nombre
                FROM aulavirtual.curso c
                JOIN aulavirtual.curso_docente cd 
                    ON cd.Id_Curso = c.Id_Curso
                WHERE cd.Id_Docente = :docente
                ORDER BY c.Nombre
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':docente', $docenteId, PDO::PARAM_INT);
            $stmt->execute();

            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            return $cursos;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener cursos del docente: " . $e->getMessage());
        }
    }

    /**
     * Obtiene ausencias pendientes de justificación
     */
    /**
 * Obtiene ausencias pendientes de justificación
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
        if ($pagina < 1) $pagina = 1;
        if ($limite < 1) $limite = 15;

        $desde = $this->normalizarFechaNullable($fechaDesde);
        $hasta = $this->normalizarFechaNullable($fechaHasta);

        $stmt = $this->pdo->prepare("
            SELECT 
                a.Id_Asistencia,
                a.Id_Estudiante,
                u.Nombre AS Estudiante,
                u.Email AS Correo,
                c.Id_Curso,
                c.Nombre AS Curso,
                a.Fecha,
                a.ComentarioJustificacion
            FROM aulavirtual.asistencia a
            INNER JOIN aulavirtual.usuario u ON a.Id_Estudiante = u.Id_Usuario
            INNER JOIN aulavirtual.curso c ON a.Id_Curso = c.Id_Curso
            INNER JOIN aulavirtual.curso_docente cd ON c.Id_Curso = cd.Id_Curso
            WHERE cd.Id_Docente = :docente
            " . ($cursoId > 0 ? "AND c.Id_Curso = :curso" : "") . "
            " . (!empty($desde) ? "AND a.Fecha >= :desde" : "") . "
            " . (!empty($hasta) ? "AND a.Fecha <= :hasta" : "") . "
            ORDER BY a.Fecha DESC
            OFFSET :offset ROWS FETCH NEXT :limite ROWS ONLY
        ");

        $offset = ($pagina - 1) * $limite;

        $stmt->bindParam(':docente', $docenteId, PDO::PARAM_INT);
        if ($cursoId > 0) $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
        if (!empty($desde)) $stmt->bindParam(':desde', $desde, PDO::PARAM_STR);
        if (!empty($hasta)) $stmt->bindParam(':hasta', $hasta, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $stmt->closeCursor();

 
        $totalStmt = $this->pdo->prepare("
            SELECT COUNT(*) AS TotalRegistros
            FROM aulavirtual.asistencia a
            INNER JOIN aulavirtual.curso c ON a.Id_Curso = c.Id_Curso
            INNER JOIN aulavirtual.curso_docente cd ON c.Id_Curso = cd.Id_Curso
            WHERE cd.Id_Docente = :docente
            " . ($cursoId > 0 ? "AND c.Id_Curso = :curso" : "") . "
            " . (!empty($desde) ? "AND a.Fecha >= :desde" : "") . "
            " . (!empty($hasta) ? "AND a.Fecha <= :hasta" : "") . "
        ");
        $totalStmt->bindParam(':docente', $docenteId, PDO::PARAM_INT);
        if ($cursoId > 0) $totalStmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
        if (!empty($desde)) $totalStmt->bindParam(':desde', $desde, PDO::PARAM_STR);
        if (!empty($hasta)) $totalStmt->bindParam(':hasta', $hasta, PDO::PARAM_STR);
        $totalStmt->execute();
        $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $total = isset($totalRow['TotalRegistros']) ? (int)$totalRow['TotalRegistros'] : 0;

        return [
            'ausencias' => $rows,
            'total'     => $total
        ];
    } catch (\Exception $e) {
        throw new \Exception("Error al obtener ausencias pendientes: " . $e->getMessage());
    }
}

    public function marcarAusenciaJustificada(
        int $cursoId,
        int $estudianteId,
        string $fecha,
        int $docenteId,
        string $comentario
    ): bool {
        try {
            $fechaNorm = $this->normalizarFechaNullable($fecha);
            if (!$fechaNorm) throw new \Exception("La fecha de la ausencia es obligatoria.");

            $comentario = trim($comentario);
            if (strlen($comentario) > 255) $comentario = substr($comentario, 0, 255);

            $sql = "
                UPDATE aulavirtual.asistencia
                SET Justificada = 1, ComentarioJustificacion = :comentario
                WHERE Id_Curso = :curso
                  AND Id_Estudiante = :estudiante
                  AND Fecha = :fecha
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':estudiante', $estudianteId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fechaNorm, PDO::PARAM_STR);
            $stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error al marcar ausencia justificada: " . $e->getMessage());
        }
    }

  
    private function normalizarFechaNullable(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') return null;

        $fecha = trim($fecha);
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        return substr($fecha, 0, 10);
    }
}
