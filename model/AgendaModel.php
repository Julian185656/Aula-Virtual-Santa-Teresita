<?php
require_once __DIR__ . '/db.php';

class AgendaModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Obtener actividades semanales del docente
    public function obtenerActividadesDocente($idDocente)
    {
        $sql = "SELECT Id_Actividad, Titulo, Descripcion, Fecha, Hora, Estado 
                FROM agenda 
                WHERE Id_Docente = :idDocente
                ORDER BY Fecha ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idDocente' => $idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Crear nueva actividad
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

    // ✅ Editar actividad
    public function editarActividad($idActividad, $titulo, $descripcion, $fecha, $hora)
    {
        $sql = "UPDATE agenda
                SET Titulo = :titulo, Descripcion = :descripcion, Fecha = :fecha, Hora = :hora
                WHERE Id_Actividad = :idActividad";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':idActividad' => $idActividad
        ]);
    }

    // ✅ Eliminar actividad
    public function eliminarActividad($idActividad)
    {
        $stmt = $this->pdo->prepare("DELETE FROM agenda WHERE Id_Actividad = ?");
        return $stmt->execute([$idActividad]);
    }
}
?>
