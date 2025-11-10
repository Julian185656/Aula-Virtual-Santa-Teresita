<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/AgendaModel.php";

$rol = $_SESSION['rol'] ?? '';
$idDocente = $_SESSION['id_usuario'] ?? 0;

if ($rol !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new AgendaModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'crear':
            $model->crearActividad($idDocente, $_POST['titulo'], $_POST['descripcion'], $_POST['fecha'], $_POST['hora']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php?msg=actividad_creada");
            break;

        case 'editar':
            $model->editarActividad($_POST['id'], $_POST['titulo'], $_POST['descripcion'], $_POST['fecha'], $_POST['hora']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php?msg=actividad_editada");
            break;

        case 'eliminar':
            $model->eliminarActividad($_POST['id']);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php?msg=actividad_eliminada");
            break;
    }
    exit();
}
?>
