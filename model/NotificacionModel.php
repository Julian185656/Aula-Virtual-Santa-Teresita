<?php
require_once __DIR__ . '/db.php';

class NotificacionModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Crear notificación (inmediata o programada)
    public function crearNotificacion($asunto, $mensaje, $fecha_envio, $hora_envio, $destinatario)
    {
        $sql = "INSERT INTO notificaciones (Asunto, Mensaje, Fecha_Envio, Hora_Envio, Destinatario, Estado)
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

    // ✅ Obtener historial de notificaciones
    public function obtenerHistorial()
    {
        $stmt = $this->pdo->query("SELECT * FROM notificaciones ORDER BY Fecha_Envio DESC, Hora_Envio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Marcar notificación como enviada
    public function marcarComoEnviada($id)
    {
        $stmt = $this->pdo->prepare("UPDATE notificaciones SET Estado='Enviada' WHERE Id_Notificacion=:id");
        return $stmt->execute([':id' => $id]);
    }

    // ✅ Eliminar notificación
    public function eliminarNotificacion($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM notificaciones WHERE Id_Notificacion=:id");
        return $stmt->execute([':id' => $id]);
    }

    // ✅ Enviar notificación inmediata (simulada)
    public function enviarInmediato($id)
    {
        // Marcar como enviada
        $this->marcarComoEnviada($id);
        // Aquí podrías integrar PHPMailer o API de correo si querés
        return true;
    }
}
?>
