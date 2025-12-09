<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/MedicoModel.php";


$cn = new CN_BD();
$pdo = $cn->conectar();

$model = new MedicoModel($pdo);

$model = new MedicoModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $model->obtenerInfoMedica($id);
    echo json_encode($data ?: []);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idEstudiante'];
    $alergias = $_POST['alergias'] ?? '';
    $medicamentos = $_POST['medicamentos'] ?? '';
    $enfermedades = $_POST['enfermedades'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';

    $success = $model->guardarInfoMedica($id, $alergias, $medicamentos, $enfermedades, $observaciones);
    echo json_encode(['success' => $success]);
}
?>
