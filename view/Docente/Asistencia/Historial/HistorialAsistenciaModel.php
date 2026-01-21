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

            // Total (para paginación)
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
            $rowTotal = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = (int)($rowTotal['total'] ?? 0);
            $stmt->closeCursor();

            $offset = ($pagina - 1) * $limite;

            // Traer historial (incluye Justificada)
            $sql = "
                SELECT Fecha, Presente, Justificada
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

            // Resumen (por página, según el dataset retornado)
            $presentes = 0;
            $ausentes = 0;
            $justificadas = 0;

            foreach ($filas as $fila) {
                $presente = (int)($fila['Presente'] ?? 0);
                $justif   = (int)($fila['Justificada'] ?? 0);

                // Regla de prioridad:
                // 1) Presente=1 -> Presente
                // 2) Presente=0 y Justificada=1 -> Justificada
                // 3) Presente=0 y Justificada=0 -> Ausente
                if ($presente === 1) {
                    $presentes++;
                } elseif ($justif === 1) {
                    $justificadas++;
                } else {
                    $ausentes++;
                }
            }

            return [
                'historial' => $filas,
                'total' => $total,
                'resumen' => [
                    'presentes' => $presentes,
                    'ausentes' => $ausentes,
                    'justificadas' => $justificadas
                ]
            ];
        } catch (\Exception $e) {
            return [
                'historial' => [],
                'total' => 0,
                'resumen' => ['presentes' => 0, 'ausentes' => 0, 'justificadas' => 0]
            ];
        }
    }

    private function normalizarFecha(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') return null;

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', $y, $m, $d);
        }

        return substr($fecha, 0, 10); // YYYY-MM-DD
    }
}
