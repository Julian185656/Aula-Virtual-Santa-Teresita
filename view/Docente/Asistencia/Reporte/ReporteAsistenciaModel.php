<?php
require_once "../../../../model/db.php";

class ReporteAsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Cursos asignados al docente (reutiliza el SP de asistencia).
     * @return array [ [Id_Curso, Curso], ... ]
     */
    public function obtenerCursosDocente(int $docenteId): array
    {
        try {
            $stmt = $this->pdo->prepare("CALL sp_asist_cursos_por_docente(:doc)");
            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();
            return $rows;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener cursos del docente: " . $e->getMessage());
        }
    }

    /**
     * Lista de estudiantes de un curso (sin paginación, solo para mostrar botones).
     * @return array [ [Id_Estudiante, Nombre, Email], ... ]
     */
    public function obtenerEstudiantesCurso(int $cursoId): array
    {
        try {
            $sql = "
                SELECT DISTINCT
                    e.Id_Estudiante,
                    u.Nombre,
                    u.Email
                FROM matricula m
                INNER JOIN estudiante e ON e.Id_Estudiante = m.Id_Estudiante
                INNER JOIN usuario   u ON u.Id_Usuario    = e.Id_Estudiante
                WHERE m.Id_Curso = :curso
                ORDER BY u.Nombre
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();
            return $rows;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener estudiantes del curso: " . $e->getMessage());
        }
    }

    /**
     * Datos crudos del reporte para CSV.
     */
    public function obtenerReporteCurso(
        int $cursoId,
        ?string $desde,
        ?string $hasta,
        ?int $estudianteId = null
    ): array {
        try {
            // Normalizar fechas (mismo criterio que el SP)
            $desde = $this->normalizarFecha($desde, '1900-01-01');
            $hasta = $this->normalizarFecha($hasta, '2999-12-31');

            $stmt = $this->pdo->prepare("CALL sp_asist_reporte_curso(:curso, :desde, :hasta, :est)");
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':desde', $desde, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $hasta, PDO::PARAM_STR);

            if ($estudianteId === null) {
                $cero = 0;
                $stmt->bindParam(':est', $cero, PDO::PARAM_INT);
            } else {
                $stmt->bindParam(':est', $estudianteId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            return $rows;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener datos del reporte: " . $e->getMessage());
        }
    }

    private function normalizarFecha(?string $fecha, string $default): string
    {
        if (!$fecha || trim($fecha) === '') {
            return $default;
        }
        return substr($fecha, 0, 10);
    }
}
