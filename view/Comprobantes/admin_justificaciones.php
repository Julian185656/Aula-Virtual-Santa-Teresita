<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'EnviarCorreo.php'; // tu función que ya definiste

// Recibir datos del POST
$id = $_POST['id'] ?? null;
$accion = $_POST['accion'] ?? null; // 'aprobar' o 'rechazar'

if (!$id || !$accion) {
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
    exit;
}

// Obtener información del estudiante y su correo
$stmt = $pdo->prepare("
    SELECT j.id_estudiante, j.fecha_ausencia, j.comprobante, u.email, u.nombre 
    FROM aulavirtual.justificaciones j 
    JOIN usuarios u ON j.id_estudiante = u.id 
    WHERE j.id = ?
");
$stmt->execute([$id]);
$just = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$just) {
    echo json_encode(['ok' => false, 'error' => 'Justificación no encontrada']);
    exit;
}

// Actualizar estado
$estado = $accion === 'aprobar' ? 'aprobada' : 'rechazada';
$stmt2 = $pdo->prepare("UPDATE aulavirtual.justificaciones SET estado = ? WHERE id = ?");
$stmt2->execute([$estado, $id]);

// Preparar correos
$fechaAusencia = $just['fecha_ausencia'];
$nombreEstudiante = $just['nombre'];
$correoEstudiante = $just['email'];

// Correo al estudiante
if ($estado === 'aprobada') {
    $asunto = "Justificación Aprobada";
    $contenido = "Hola $nombreEstudiante,<br><br>Tu justificación de ausencia para el día <b>$fechaAusencia</b> ha sido <b>aprobada</b>.<br><br>Saludos,<br>Administración";
} else {
    $asunto = "Justificación Rechazada";
    $contenido = "Hola $nombreEstudiante,<br><br>Tu justificación de ausencia para el día <b>$fechaAusencia</b> ha sido <b>rechazada</b>.<br><br>Saludos,<br>Administración";
}

// Enviar correo al estudiante
$correoEnviado = EnviarCorreo($asunto, $contenido, $correoEstudiante);

// Opcional: si quieres notificar al profesor, agrega aquí su correo
// $correoProfesor = 'profesor@dominio.com';
// $asuntoProf = "Justificación de $nombreEstudiante $estado";
// $contenidoProf = "La justificación de ausencia del estudiante $nombreEstudiante para el día $fechaAusencia ha sido $estado.";
// EnviarCorreo($asuntoProf, $contenidoProf, $correoProfesor);

echo json_encode([
    'ok' => true,
    'estado' => $estado,
    'correoEstudianteEnviado' => $correoEnviado
]);
?>
