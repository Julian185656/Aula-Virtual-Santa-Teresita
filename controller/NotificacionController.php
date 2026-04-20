<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php";

$rol = $_SESSION['rol'] ?? ($_SESSION['usuario']['Rol'] ?? '');

if (strtolower(trim($rol)) !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);

// CAPTURAR ACCION E ID YA SEA POR POST O POR GET
$accion = $_REQUEST['accion'] ?? ''; 
$id = $_REQUEST['id'] ?? null;

switch ($accion) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $asunto = $_POST['asunto'];
            $mensaje = $_POST['mensaje'];
            $fecha = $_POST['fecha_envio'] ?? date('Y-m-d');
            $hora = $_POST['hora_envio'] ?? date('H:i');
            $destinatario = $_POST['destinatario'];

            $model->crearNotificacion($asunto, $mensaje, $fecha, $hora, $destinatario);

            $contenido = "
                <h2>Notificación - Aula Virtual</h2>
                <p><strong>Asunto:</strong> $asunto</p>
                <p>$mensaje</p>
            ";
            EnviarCorreo($asunto, $contenido, $destinatario);

            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_creada");
        }
        break;

    case 'eliminar':
        if ($id) {
            $model->eliminarNotificacion($id);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_eliminada");
        }
        break;

    case 'enviar_inmediato':
        if ($id) {
            $model->enviarInmediato($id);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_enviada");
        }
        break;
        
    default:
        // Si no hay acción válida, regresar a la lista
        header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php");
        break;
}
exit();