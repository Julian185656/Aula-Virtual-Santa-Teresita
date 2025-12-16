<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

$id_estudiante = $_POST['id_estudiante'];
$fecha_ausencia = $_POST['fecha_ausencia'];

// Comprobante
if(isset($_FILES['comprobante'])) {
    $nombre = time() . '_' . $_FILES['comprobante']['name'];
    $ruta = 'comprobantes/' . $nombre;
    move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta);
} else {
    echo json_encode(['ok'=>false, 'error'=>'No se envió comprobante']);
    exit;
}

// Guardar en DB
$sql = "INSERT INTO aulavirtual.justificaciones (id_estudiante, fecha_ausencia, comprobante, estado, fecha_solicitud) VALUES (?, ?, ?, 'pendiente', NOW())";
$stmt = $pdo->prepare($sql);
$ok = $stmt->execute([$id_estudiante, $fecha_ausencia, $ruta]);

if($ok) {
    echo json_encode(['ok'=>true]);
} else {
    echo json_encode(['ok'=>false, 'error'=>'Error al guardar en la base de datos']);
}
?>
