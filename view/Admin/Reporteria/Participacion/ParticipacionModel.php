<?php
require_once __DIR__ . '/../../../../model/db.php';

class ParticipacionModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }


    public function obtenerReporteParticipacion($periodo, $offset, $limite)
    {
        try {
            $sql = "CALL sp_ReporteParticipacion(:periodo, :offset, :limite)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':periodo', $periodo);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger text-center mt-3'>
                    <strong>Error:</strong> {$e->getMessage()}
                  </div>";
            return [];
        }
    }

    public function obtenerPeriodos()
    {
        try {
            $sql = "SELECT DISTINCT Periodo FROM participacion ORDER BY Periodo DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
