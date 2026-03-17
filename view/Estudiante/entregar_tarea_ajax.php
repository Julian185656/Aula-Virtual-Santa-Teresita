<?php
session_start();
require_once __DIR__ . "/../../model/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    echo json_encode(['success'=>false,'error'=>'No autorizado']);
    exit();
}

$idUsuario = (int)$_SESSION['id_usuario'];
$idTarea = (int)($_POST['idTarea'] ?? 0);

if($idTarea <= 0 || empty($_FILES['archivo'])){
    echo json_encode(['success'=>false,'error'=>'Archivo no válido']);
    exit();
}

$rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/uploads/";
if(!is_dir($rutaCarpeta)) mkdir($rutaCarpeta,0777,true);

$nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
$rutaDestino = $rutaCarpeta . $nombreArchivo;

if(move_uploaded_file($_FILES['archivo']['tmp_name'],$rutaDestino)){
    $archivoUrl = "/Aula-Virtual-Santa-Teresita/uploads/" . $nombreArchivo;

    global $pdo;
    $sel = $pdo->prepare("SELECT Id_Entrega FROM aulavirtual.entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
    $sel->execute([$idTarea,$idUsuario]);
    $ex = $sel->fetch(PDO::FETCH_ASSOC);

    if($ex){
        $upd = $pdo->prepare("UPDATE aulavirtual.entrega_tarea SET Archivo_URL=?, Fecha_Entrega=GETDATE() WHERE Id_Entrega=?");
        $upd->execute([$archivoUrl,$ex['Id_Entrega']]);
    } else {
        $ins = $pdo->prepare("INSERT INTO aulavirtual.entrega_tarea (Id_Tarea, Id_Estudiante, Archivo_URL, Fecha_Entrega) VALUES (?,?,?,GETDATE())");
        $ins->execute([$idTarea,$idUsuario,$archivoUrl]);
    }

    echo json_encode(['success'=>true,'url'=>$archivoUrl,'nombre'=>$nombreArchivo]);
} else {
    echo json_encode(['success'=>false,'error'=>'Error al subir archivo']);
}