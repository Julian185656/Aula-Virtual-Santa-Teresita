<?php
require_once "../../../../model/db.php";

class HistorialAsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Cursos del docente (mismo SP que usas en RegistrarAsistencia)
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
     * Lista de alumnos de un curso (simple, sin paginación).
     */
    public function obtenerAlumnosCurso(int $cursoId): array
    {
        try {
            $sql = "
                SELECT DISTINCT
                    e.Id_Estudiante,
                    u.Nombre,
                    u.Email
                FROM estudiante e
                INNER JOIN usuario   u ON e.Id_Estudiante = u.Id_Usuario
                INNER JOIN matricula m ON e.Id_Estudiante = m.Id_Estudiante
                WHERE m.Id_Curso = :curso
                ORDER BY u.Nombre ASC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->execute();
            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();
            return $alumnos;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener alumnos del curso: " . $e->getMessage());
        }
    }

    /**
     * Historial de asistencia de un alumno en un curso, con rango de fechas + paginación.
     *
     * Retorna:
     * [
     *   'historial' => [...filas...],
     *   'total'     => int (Total registros para paginación),
     *   'resumen'   => ['presentes' => X, 'ausentes' => Y]
     * ]
     */
    public function obtenerHistorialAlumno(
        int $cursoId,
        int $estudianteId,
        ?string $fechaDesde,
        ?string $fechaHasta,
        int $pagina = 1,
        int $limite = 15
    ): array {
        try {
            if ($pagina < 1) $pagina = 1;
            if ($limite < 1) $limite = 15;

            // Normalizar fechas (pero si vienen vacías, se envían NULL)
            $desde = $this->normalizarFechaNullable($fechaDesde);
            $hasta = $this->normalizarFechaNullable($fechaHasta);

            $sql = "CALL sp_asist_historial_alumno(
                        :curso,
                        :est,
                        :desde,
                        :hasta,
                        :pagina,
                        :limite,
                        @total
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso',  $cursoId,      PDO::PARAM_INT);
            $stmt->bindParam(':est',    $estudianteId, PDO::PARAM_INT);
            $stmt->bindParam(':desde',  $desde,        PDO::PARAM_STR);
            $stmt->bindParam(':hasta',  $hasta,        PDO::PARAM_STR);
            $stmt->bindParam(':pagina', $pagina,       PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite,       PDO::PARAM_INT);
            $stmt->execute();

            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            // Obtener total de registros para la paginación
            $totalRow = $this->pdo
                ->query("SELECT @total AS TotalRegistros")
                ->fetch(PDO::FETCH_ASSOC);

            $total = isset($totalRow['TotalRegistros'])
                ? (int)$totalRow['TotalRegistros']
                : 0;

            // Resumen de presentes / ausentes (vienen en cada fila desde el SP)
            $presentes = 0;
            $ausentes  = 0;
            if (!empty($filas)) {
                $presentes = (int)$filas[0]['TotalPresentes'];
                $ausentes  = (int)$filas[0]['TotalAusentes'];
            }

            return [
                'historial' => $filas,
                'total'     => $total,
                'resumen'   => [
                    'presentes' => $presentes,
                    'ausentes'  => $ausentes
                ]
            ];
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener historial del alumno: " . $e->getMessage());
        }
    }

    /**
     * Normaliza fecha a 'Y-m-d', pero si está vacía devuelve NULL (para que el SP use su default).
     */
    private function normalizarFechaNullable(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') {
            return null;
        }

        // dd/mm/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        // si viene como YYYY-mm-dd, cortamos a 10
        return substr($fecha, 0, 10);
    }
}
