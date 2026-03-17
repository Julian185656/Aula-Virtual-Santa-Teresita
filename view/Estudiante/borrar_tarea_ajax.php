<?php
session_start();
require_once __DIR__ . "/../../model/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    echo json_encode(['success'=>false,'error'=>'No autorizado']);
    exit();
}

$idUsuario = (int)$_SESSION['id_usuario'];
$input = json_decode(file_get_contents('php://input'), true);
$idTarea = (int)($input['idTarea'] ?? 0);

if($idTarea <= 0){
    echo json_encode(['success'=>false,'error'=>'ID de tarea no válido']);
    exit();
}

global $pdo;
$sel = $pdo->prepare("SELECT Archivo_URL, Id_Entrega FROM aulavirtual.entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
$sel->execute([$idTarea,$idUsuario]);
$ex = $sel->fetch(PDO::FETCH_ASSOC);

if($ex){
    if(file_exists($_SERVER['DOCUMENT_ROOT'].$ex['Archivo_URL'])){
        unlink($_SERVER['DOCUMENT_ROOT'].$ex['Archivo_URL']);
    }
    $del = $pdo->prepare("DELETE FROM aulavirtual.entrega_tarea WHERE Id_Entrega=?");
    $del->execute([$ex['Id_Entrega']]);
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false,'error'=>'No hay entrega para borrar']);
}