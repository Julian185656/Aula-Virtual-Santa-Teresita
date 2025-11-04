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

 
    public function obtenerReporte($idCurso = null, $pagina = 1, $limite = 15)
    {
        try {
            $sql = "CALL sp_ObtenerReporteCalificaciones(:idCurso, :pagina, :limite, @total)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
            $stmt->bindParam(':pagina', $pagina, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();

            $reporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $totalResult = $this->pdo->query("SELECT @total AS TotalRegistros")->fetch(PDO::FETCH_ASSOC);
            $totalRegistros = $totalResult['TotalRegistros'] ?? 0;

            return [
                'reporte' => $reporte ?: [],
                'total' => (int)$totalRegistros
            ];
        } catch (Exception $e) {
            throw new Exception("Error al obtener el reporte de calificaciones: " . $e->getMessage());
        }
    }

    
    public function obtenerResumen()
    {
        try {
            $stmt = $this->pdo->query("CALL sp_VerResumenCalificaciones()");
            $resumen = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $resumen ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener el resumen de rendimiento: " . $e->getMessage());
        }
    }

   
    public function obtenerPromedioPorEstudiante($idCurso)
    {
        try {
            $sql = "CALL sp_VerPromedioPorEstudiante(:idCurso)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $result ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener promedio por estudiante: " . $e->getMessage());
        }
    }
}
