<?php
require_once __DIR__ . '/db.php';
$pdo = (new CN_BD())->conectar();

class NotificacionModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function crearNotificacion($asunto, $mensaje, $fecha_envio, $hora_envio, $destinatario)
    {
        $sql = "INSERT INTO aulavirtual.notificaciones (Asunto, Mensaje, Fecha_Envio, Hora_Envio, Destinatario, Estado)
                VALUES (:asunto, :mensaje, :fecha_envio, :hora_envio, :destinatario, 'Pendiente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':asunto' => $asunto,
            ':mensaje' => $mensaje,
            ':fecha_envio' => $fecha_envio,
            ':hora_envio' => $hora_envio,
            ':destinatario' => $destinatario
        ]);
    }

    public function obtenerHistorial()
    {
        $stmt = $this->pdo->query("SELECT * FROM aulavirtual.notificaciones ORDER BY Fecha_Envio DESC, Hora_Envio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoEnviada($id)
    {
        $stmt = $this->pdo->prepare("UPDATE aulavirtual.notificaciones SET Estado='Enviada' WHERE Id_Notificacion=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function eliminarNotificacion($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM aulavirtual.notificaciones WHERE Id_Notificacion=:id");
        return $stmt->execute([':id' => $id]);
    }

    public function enviarInmediato($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM aulavirtual.notificaciones WHERE Id_Notificacion = :id");
        $stmt->execute([':id' => $id]);
        $noti = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$noti) return false;

        require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php";

        $contenido = "
            <h2>Notificación - Aula Virtual</h2>
            <p><strong>Asunto:</strong> {$noti['Asunto']}</p>
            <p>{$noti['Mensaje']}</p>
            <p><strong>Fecha:</strong> {$noti['Fecha_Envio']} {$noti['Hora_Envio']}</p>
        ";

        EnviarCorreo($noti['Asunto'], $contenido, $noti['Destinatario']);

        $this->marcarComoEnviada($id);

        return true;
    }
}
?>