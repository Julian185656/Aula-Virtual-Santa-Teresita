<?php
require_once __DIR__ . '/db.php';

class AgendaModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function obtenerActividadesDocente($idDocente)
    {
        $sql = "SELECT Id_Agenda, Titulo, Descripcion, Fecha, Hora, Estado 
                FROM agenda 
                WHERE Id_Docente = :idDocente
                ORDER BY Fecha ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idDocente' => $idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function crearActividad($idDocente, $titulo, $descripcion, $fecha, $hora)
    {
        $sql = "INSERT INTO agenda (Id_Docente, Titulo, Descripcion, Fecha, Hora, Estado)
                VALUES (:idDocente, :titulo, :descripcion, :fecha, :hora, 'Pendiente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idDocente' => $idDocente,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':hora' => $hora
        ]);
    }


    public function editarActividad($idAgenda, $titulo, $descripcion, $fecha, $hora)
    {
        $sql = "UPDATE agenda
                SET Titulo = :titulo, Descripcion = :descripcion, Fecha = :fecha, Hora = :hora
                WHERE Id_Agenda = :idAgenda";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':idAgenda' => $idAgenda
        ]);
    }


    public function eliminarActividad($idAgenda)
    {
        $stmt = $this->pdo->prepare("DELETE FROM agenda WHERE Id_Agenda = ?");
        return $stmt->execute([$idAgenda]);
    }
}
?>
