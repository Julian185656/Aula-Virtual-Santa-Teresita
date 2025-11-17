<?php
require_once "../../../../model/db.php";

class RegistrarAsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     
     * @return array [ [Id_Curso, Curso], ... ]
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
    
     * @return array ['alumnos' => [...], 'total' => int]
     */
    public function obtenerAlumnosPaginado(int $cursoId, int $pagina = 1, int $limite = 15): array
    {
        try {
            if ($pagina < 1) $pagina = 1;
            if ($limite < 1) $limite = 15;

            $sql = "CALL sp_asist_alumnos_por_curso(:curso, :pagina, :limite, @total)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':pagina', $pagina, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();

            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            $totalRow = $this->pdo->query("SELECT @total AS Total")->fetch(PDO::FETCH_ASSOC);
            $total = isset($totalRow['Total']) ? (int)$totalRow['Total'] : 0;

            return ['alumnos' => $alumnos, 'total' => $total];
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener alumnos del curso: " . $e->getMessage());
        }
    }

    /**
     
     * @return array Mapa [Id_Estudiante => (int)Presente]
     */
    public function obtenerAsistenciaDia(int $cursoId, string $fecha): array
    {
        try {
            $fecha = $this->normalizarFecha($fecha);

            $stmt = $this->pdo->prepare("CALL sp_asist_obtener_dia(:curso, :fecha)");
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            // Convertir a mapa: [Id_Estudiante => Presente]
            $map = [];
            foreach ($rows as $r) {
                $map[(int)$r['Id_Estudiante']] = (int)$r['Presente'];
            }
            return $map;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener la asistencia del día: " . $e->getMessage());
        }
    }

    /**
    
     * @return array ['ok' => bool, 'procesados' => int]
     */
    public function guardarLoteAsistencia(int $cursoId, string $fecha, int $docenteId, array $items): array
    {
        $fecha = $this->normalizarFecha($fecha);

        try {
            $this->pdo->beginTransaction();

            $sql = "CALL sp_asist_upsert_uno(:curso, :est, :fecha, :pres, :doc)";
            $stmt = $this->pdo->prepare($sql);

            $procesados = 0;
            foreach ($items as $item) {
                // Sanitizar/normalizar entrada
                $est = (int)($item['Id_Estudiante'] ?? 0);
                $pres = isset($item['Presente']) && (int)$item['Presente'] === 1 ? 1 : 0;

                if ($est <= 0) continue; // ignora filas inválidas

                $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
                $stmt->bindParam(':est', $est, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
                $stmt->bindParam(':pres', $pres, PDO::PARAM_INT);
                $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
                $stmt->execute();
                $stmt->closeCursor(); // importante tras CALL

                $procesados++;
            }

            $this->pdo->commit();
            return ['ok' => true, 'procesados' => $procesados];
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new \Exception("Error al guardar la asistencia: " . $e->getMessage());
        }
    }

    /** Normaliza fecha a 'Y-m-d'. Si viene vacía, usa hoy. */
    private function normalizarFecha(?string $fecha): string
    {
        if (!$fecha || trim($fecha) === '') {
            return date('Y-m-d');
        }
        // Acepta formatos comunes (YYYY-mm-dd, dd/mm/YYYY)
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }
        // Confía si parece YYYY-mm-dd
        return substr($fecha, 0, 10);
    }
}
