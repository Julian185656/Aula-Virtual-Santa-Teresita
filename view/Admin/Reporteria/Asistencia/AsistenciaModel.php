<?php
require_once "../../../../model/db.php";

class AsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerReporte($fecha = null, $offset = 0, $limite = 15)
    {
        try {
            $sql = "CALL sp_ReporteAsistencia(:fecha, :offset, :limite)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener reporte: " . $e->getMessage());
        }
    }

    public function obtenerFechas()
    {
        try {
            $stmt = $this->pdo->query("CALL sp_FechaAsistencia()");
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            throw new Exception("Error al obtener fechas: " . $e->getMessage());
        }
    }
}
