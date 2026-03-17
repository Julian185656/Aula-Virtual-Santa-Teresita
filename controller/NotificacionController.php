<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php";

$rol = $_SESSION['rol'] ?? '';

if (strtolower(trim($rol)) !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {

        case 'crear':
            $asunto = $_POST['asunto'];
            $mensaje = $_POST['mensaje'];
            $fecha = $_POST['fecha_envio'];
            $hora = $_POST['hora_envio'];
            $destinatario = $_POST['destinatario'];

            $model->crearNotificacion($asunto, $mensaje, $fecha, $hora, $destinatario);

            // Enviar correo inmediatamente
            $contenido = "
                <h2>Notificación - Aula Virtual</h2>
                <p><strong>Asunto:</strong> $asunto</p>
                <p>$mensaje</p>
                <p><strong>Fecha programada:</strong> $fecha $hora</p>
            ";

            EnviarCorreo($asunto, $contenido, $destinatario);

            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_creada");
            break;

        case 'eliminar':
            $model->eliminarNotificacion($_POST['id']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_eliminada");
            break;

        case 'enviar_inmediato':
            $model->enviarInmediato($_POST['id']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_enviada");
            break;
    }

    exit();
}
?>