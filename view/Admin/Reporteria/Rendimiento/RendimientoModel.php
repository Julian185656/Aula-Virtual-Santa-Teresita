<?php
require_once "../../../../model/db.php";

class RendimientoModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Obtener reporte de calificaciones con paginación
    public function obtenerReporte($idCurso = null, $pagina = 1, $limite = 15)
    {
        try {
            $offset = ($pagina - 1) * $limite;

            $sql = "SELECT 
                        e.Id_Entrega,
                        u.Nombre AS Estudiante,
                        c.Nombre AS Curso,
                        d.Nombre AS Docente,
                        e.Calificacion,
                        e.Comentario,
                        e.Fecha_Entrega
                    FROM aulavirtual.entrega_tarea e
                    INNER JOIN aulavirtual.usuario u ON u.Id_Usuario = e.Id_Estudiante
                    INNER JOIN aulavirtual.tarea t ON t.Id_Tarea = e.Id_Tarea
                    INNER JOIN aulavirtual.curso c ON c.Id_Curso = t.Id_Curso
                    INNER JOIN aulavirtual.curso_docente cd ON cd.Id_Curso = c.Id_Curso
                    INNER JOIN aulavirtual.usuario d ON d.Id_Usuario = cd.Id_Docente
                    " . ($idCurso ? "WHERE c.Id_Curso = :idCurso" : "") . "
                    ORDER BY e.Fecha_Entrega DESC
                    OFFSET :offset ROWS FETCH NEXT :limite ROWS ONLY";

            $stmt = $this->pdo->prepare($sql);

            if ($idCurso) {
                $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
            }
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

            $stmt->execute();
            $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Total de registros
            $countSql = "SELECT COUNT(*) AS total
                         FROM aulavirtual.entrega_tarea e
                         INNER JOIN aulavirtual.tarea t ON t.Id_Tarea = e.Id_Tarea
                         INNER JOIN aulavirtual.curso c ON c.Id_Curso = t.Id_Curso
                         " . ($idCurso ? "WHERE c.Id_Curso = :idCurso" : "");
            $countStmt = $this->pdo->prepare($countSql);
            if ($idCurso) {
                $countStmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
            }
            $countStmt->execute();
            $totalRegistros = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'reporte' => $reporte ?: [],
                'total' => (int)$totalRegistros
            ];

        } catch (Exception $e) {
            throw new Exception("Error al obtener el reporte de calificaciones: " . $e->getMessage());
        }
    }

    // Obtener resumen de calificaciones
    public function obtenerResumen()
    {
        try {
            $sql = "SELECT c.Nombre AS Curso, AVG(e.Calificacion) AS Promedio
                    FROM aulavirtual.entrega_tarea e
                    INNER JOIN aulavirtual.tarea t ON t.Id_Tarea = e.Id_Tarea
                    INNER JOIN aulavirtual.curso c ON c.Id_Curso = t.Id_Curso
                    INNER JOIN aulavirtual.curso_docente cd ON cd.Id_Curso = c.Id_Curso
                    INNER JOIN aulavirtual.usuario d ON d.Id_Usuario = cd.Id_Docente
                    GROUP BY c.Nombre";
            $stmt = $this->pdo->query($sql);
            $resumen = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resumen ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener el resumen de rendimiento: " . $e->getMessage());
        }
    }

    // Obtener cursos disponibles
    public function obtenerCursos()
    {
        $stmt = $this->pdo->query("
            SELECT DISTINCT c.Id_Curso, c.Nombre 
            FROM aulavirtual.curso c
            INNER JOIN aulavirtual.curso_docente cd ON cd.Id_Curso = c.Id_Curso
            ORDER BY c.Nombre
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
