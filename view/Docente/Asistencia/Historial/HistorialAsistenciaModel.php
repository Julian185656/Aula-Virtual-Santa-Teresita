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

    // Obtener cursos del docente
     public function obtenerCursosDocente(int $docenteId): array
    {
        try {
            $stmt = $this->pdo->prepare("EXEC aulavirtual.sp_asist_cursos_por_docente :doc");
            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->execute();
            $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();
            return $cursos;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Obtener alumnos de un curso
    public function obtenerAlumnosCurso(int $cursoId): array
    {
        try {
            $sql = "
                SELECT u.Id_Usuario AS Id, u.Nombre, u.Email
                FROM aulavirtual.usuario u
                INNER JOIN aulavirtual.matricula m ON u.Id_Usuario = m.Id_Estudiante
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
            return [];
        }
    }

    // Obtener historial de un alumno con paginación
    public function obtenerHistorialAlumno(
        int $cursoId,
        int $usuarioId,
        ?string $fechaDesde,
        ?string $fechaHasta,
        int $pagina = 1,
        int $limite = 15
    ): array {
        try {
            $desde = $this->normalizarFecha($fechaDesde) ?? '1900-01-01';
            $hasta = $this->normalizarFecha($fechaHasta) ?? date('Y-m-d');

            // Contar total registros
            $sqlTotal = "
                SELECT COUNT(*) AS total
                FROM aulavirtual.asistencia
                WHERE Id_Curso = :curso
                  AND Id_Estudiante = :usuario
                  AND Fecha BETWEEN :desde AND :hasta
            ";
            $stmt = $this->pdo->prepare($sqlTotal);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':desde', $desde);
            $stmt->bindParam(':hasta', $hasta);
            $stmt->execute();
            $total = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            $stmt->closeCursor();

            // Calcular offset para paginación
            $offset = ($pagina - 1) * $limite;

            // Consultar historial
            $sql = "
                SELECT Fecha, Presente
                FROM aulavirtual.asistencia
                WHERE Id_Curso = :curso
                  AND Id_Estudiante = :usuario
                  AND Fecha BETWEEN :desde AND :hasta
                ORDER BY Fecha DESC
                OFFSET :offset ROWS
                FETCH NEXT :limite ROWS ONLY
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':desde', $desde);
            $stmt->bindParam(':hasta', $hasta);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            $filas = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            // Contar presentes y ausentes
            $presentes = 0;
            $ausentes = 0;
            foreach ($filas as $fila) {
                if ((int)($fila['Presente'] ?? 0) === 1) $presentes++;
                else $ausentes++;
            }

            return [
                'historial' => $filas,
                'total' => $total,
                'resumen' => [
                    'presentes' => $presentes,
                    'ausentes' => $ausentes
                ]
            ];
        } catch (\Exception $e) {
            return [
                'historial' => [],
                'total' => 0,
                'resumen' => ['presentes'=>0,'ausentes'=>0]
            ];
        }
    }

    private function normalizarFecha(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') return null;
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d,$m,$y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', $y,$m,$d);
        }
        return substr($fecha, 0, 10); // YYYY-MM-DD
    }
}
