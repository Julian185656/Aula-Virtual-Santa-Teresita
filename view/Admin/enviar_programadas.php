<?php
require_once __DIR__ . '/../model/db.php';
require_once __DIR__ . '/../model/NotificacionModel.php';
require_once __DIR__ . '/PHPMailerHelper.php'; // tu función EnviarCorreo()

$pdo = (new CN_BD())->conectar();
$model = new NotificacionModel($pdo);

// SQL Server: seleccionar notificaciones pendientes cuya fecha y hora ya pasaron
$sql = "
    SELECT TOP 1000 *
    FROM aulavirtual.notificaciones
    WHERE Estado = 'Pendiente'
      AND CAST(Fecha_Envio AS DATETIME) + CAST(Hora_Envio AS DATETIME) <= GETDATE()
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pendientes as $n) {
    $asunto = $n['Asunto'];
    $mensaje = $n['Mensaje'];
    $destinatario = $n['Destinatario'];

    // Intentar enviar correo
    if (EnviarCorreo($asunto, $mensaje, $destinatario)) {
        // Marcar como enviada
        $model->marcarComoEnviada($n['Id_Notificacion']);
        echo "[" . date('Y-m-d H:i:s') . "] Notificación enviada a {$destinatario}\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] Error al enviar a {$destinatario}\n";
    }
}
?>