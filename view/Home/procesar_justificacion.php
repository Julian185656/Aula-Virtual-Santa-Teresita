<?php
session_start();
header('Content-Type: application/json');

// Mostrar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../model/db.php'; // tu conexión $pdo
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php';


$id = $_POST['id'] ?? null;
$accion = $_POST['accion'] ?? null; // "aprobar" o "denegar"

// Validar datos
if (!$id || !in_array($accion, ['aprobar','denegar'])) {
    echo json_encode(['ok'=>false,'error'=>'Datos inválidos']);
    exit;
}

try {
    // Obtener justificación y datos del estudiante
    $stmt = $pdo->prepare("
        SELECT j.id, j.id_estudiante, j.fecha_ausencia, u.Email, u.Nombre
        FROM aulavirtual.justificaciones j
        JOIN aulavirtual.usuario u ON u.Id_Usuario = j.id_estudiante
        WHERE j.id = ?
    ");
    $stmt->execute([$id]);
    $just = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$just) {
        echo json_encode(['ok'=>false,'error'=>'Justificación no encontrada']);
        exit;
    }

    // Actualizar estado
    $nuevo_estado = $accion === 'aprobar' ? 'aprobado' : 'denegado';
    $stmt2 = $pdo->prepare("UPDATE aulavirtual.justificaciones SET estado = ? WHERE id = ?");
    $stmt2->execute([$nuevo_estado, $id]);

    // Enviar correo al estudiante
    $asunto = "Resultado de tu justificación";
    $mensaje = "Hola {$just['Nombre']},<br><br>
                Tu justificación para la fecha {$just['fecha_ausencia']} ha sido <b>{$nuevo_estado}</b>.<br><br>
                Atentamente,<br>Equipo Aula Virtual";
    
    $correo_enviado = EnviarCorreo($asunto, $mensaje, $just['Email']);

    if($correo_enviado){
        echo json_encode(['ok'=>true,'mensaje'=>"Justificación {$nuevo_estado} y correo enviado"]);
    } else {
        echo json_encode(['ok'=>true,'mensaje'=>"Justificación {$nuevo_estado}, pero no se pudo enviar el correo"]);
    }

} catch (Exception $e) {
    echo json_encode(['ok'=>false,'error'=>"Error de servidor: ".$e->getMessage()]);
}
?>
