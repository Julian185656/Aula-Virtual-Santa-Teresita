<?php
require_once __DIR__ . "/../../../../model/CN_BD.php";

class AsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new CN_BD())->conectar();
    }

    // Obtener lista de cursos
    public function obtenerCursos(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT Id_Curso, Nombre FROM aulavirtual.curso ORDER BY Nombre");
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener cursos: " . $e->getMessage());
        }
    }

    // Obtener fechas únicas
    public function obtenerFechas(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT DISTINCT Fecha FROM aulavirtual.asistencia ORDER BY Fecha DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener fechas: " . $e->getMessage());
        }
    }

    // Obtener reporte de asistencia
    public function obtenerReporte(?string $fecha = null, ?int $cursoId = null, int $offset = 0, int $limite = 15): array
    {
        try {
            $sql = "SELECT a.Id_Asistencia, a.Fecha, a.Presente,
                           u.Id_Usuario AS Id_Estudiante, u.Nombre AS Estudiante,
                           c.Nombre AS Curso
                    FROM aulavirtual.asistencia a
                    INNER JOIN aulavirtual.usuario u ON u.Id_Usuario = a.Id_Estudiante
                    INNER JOIN aulavirtual.curso c ON c.Id_Curso = a.Id_Curso
                    WHERE 1=1";

            if ($fecha) $sql .= " AND a.Fecha = :fecha";
            if ($cursoId) $sql .= " AND c.Id_Curso = :cursoId";

            $sql .= " ORDER BY a.Fecha DESC OFFSET :offset ROWS FETCH NEXT :limite ROWS ONLY";

            $stmt = $this->pdo->prepare($sql);

            if ($fecha) $stmt->bindParam(':fecha', $fecha);
            if ($cursoId) $stmt->bindParam(':cursoId', $cursoId, PDO::PARAM_INT);

            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener reporte: " . $e->getMessage());
        }
    }

    // Contar total de registros para paginación
    public function contarRegistros(?string $fecha = null, ?int $cursoId = null): int
    {
        try {
            $sql = "SELECT COUNT(*) AS total
                    FROM aulavirtual.asistencia a
                    INNER JOIN aulavirtual.usuario u ON u.Id_Usuario = a.Id_Estudiante
                    INNER JOIN aulavirtual.curso c ON c.Id_Curso = a.Id_Curso
                    WHERE 1=1";

            if ($fecha) $sql .= " AND a.Fecha = :fecha";
            if ($cursoId) $sql .= " AND c.Id_Curso = :cursoId";

            $stmt = $this->pdo->prepare($sql);

            if ($fecha) $stmt->bindParam(':fecha', $fecha);
            if ($cursoId) $stmt->bindParam(':cursoId', $cursoId, PDO::PARAM_INT);

            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (Exception $e) {
            throw new Exception("Error al contar registros: " . $e->getMessage());
        }
    }
}
