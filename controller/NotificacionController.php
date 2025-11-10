<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";

$rol = $_SESSION['rol'] ?? '';

if ($rol !== 'Administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        // Crear / programar
        case 'crear':
            $model->crearNotificacion(
                $_POST['asunto'],
                $_POST['mensaje'],
                $_POST['fecha_envio'],
                $_POST['hora_envio'],
                $_POST['destinatario']
            );
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_creada");
            break;

        // Eliminar
        case 'eliminar':
            $model->eliminarNotificacion($_POST['id']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_eliminada");
            break;

        // Enviar inmediata
        case 'enviar_inmediato':
            $model->enviarInmediato($_POST['id']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php?msg=notificacion_enviada");
            break;
    }

    exit();
}
?>
