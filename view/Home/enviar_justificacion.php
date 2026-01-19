<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../model/db.php'; // tu conexión $pdo
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/PHPMailer.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/SMTP.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/Exception.php';



// Función para enviar correo
function EnviarCorreo($asunto, $contenido, $destinatario) {
    $correoSalida = "grupomoras.a19@gmail.com";
    $contrasennaSalida = "njdmhpkjalyjsosv";

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->SMTPAuth   = true;
        $mail->Username   = $correoSalida;
        $mail->Password   = $contrasennaSalida;

        $mail->setFrom($correoSalida, 'Aula Virtual');
        $mail->Subject = $asunto;
        $mail->MsgHTML($contenido);
        $mail->addAddress($destinatario);

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// 1️⃣ Validar datos del POST
$id_estudiante = $_POST['id_estudiante'] ?? null;
$fecha_ausencia = $_POST['fecha_ausencia'] ?? null;
$id_curso = $_POST['id_curso'] ?? null;

if (!$id_estudiante || !$fecha_ausencia || !$id_curso) {
    echo json_encode(['ok'=>false, 'error'=>'Por favor, selecciona el curso, la fecha y adjunta tu comprobante.']);
    exit;
}

/* Permitir HOY y fechas ANTERIORES (bloquear FUTURO) */
date_default_timezone_set('America/Costa_Rica');
$hoy = date('Y-m-d');

if ($fecha_ausencia > $hoy) {
    echo json_encode([
        'ok' => false,
        'error' => 'No puedes seleccionar una fecha futura.'
    ]);
    exit;
}


// 2️⃣ Carpeta de comprobantes
$directorio_comprobantes = __DIR__ . '/view/Home/comprobantes/';
if (!is_dir($directorio_comprobantes)) {
    mkdir($directorio_comprobantes, 0777, true);
}

// 3️⃣ Subida del archivo
if (!isset($_FILES['comprobante']) || $_FILES['comprobante']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok'=>false, 'error'=>'Debe adjuntar un archivo válido']);
    exit;
}

$nombreOriginal = pathinfo($_FILES['comprobante']['name'], PATHINFO_FILENAME);
$extension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);
$nombreArchivo = $nombreOriginal . '_' . $id_estudiante . '_' . time() . '.' . $extension;
$rutaArchivo = $directorio_comprobantes . $nombreArchivo;

if (!move_uploaded_file($_FILES['comprobante']['tmp_name'], $rutaArchivo)) {
    echo json_encode(['ok'=>false, 'error'=>'Error al guardar el archivo']);
    exit;
}

try {
    // 4️⃣ Guardar en la base de datos
    $sql = "INSERT INTO aulavirtual.justificaciones 
        (id_estudiante, id_curso, fecha_ausencia, comprobante, estado, fecha_solicitud) 
        VALUES (?, ?, ?, ?, 'pendiente', GETDATE())";
    $stmt = $pdo->prepare($sql);
    $ok = $stmt->execute([$id_estudiante, $id_curso, $fecha_ausencia, 'comprobantes/' . $nombreArchivo]);

    if ($ok) {
        // 5️⃣ Obtener correo del docente del curso
        $sqlDocente = "SELECT u.email
                       FROM aulavirtual.curso_docente cd
                       JOIN aulavirtual.usuario u ON cd.Id_Docente = u.Id_Usuario
                       WHERE cd.Id_Curso = ?";
        $stmtDocente = $pdo->prepare($sqlDocente);
        $stmtDocente->execute([$id_curso]);
        $correoDocente = $stmtDocente->fetchColumn();

        if ($correoDocente) {
            $asunto = "Nueva justificación de ausencia";
            $contenido = "<p>El estudiante ha enviado una justificación de ausencia para tu curso.</p>
                          <p>Fecha de ausencia: $fecha_ausencia</p>
                          <p>Comprobante: <a href='https://tusitio.com/Aula-Virtual-Santa-Teresita/comprobantes/$nombreArchivo' target='_blank'>Ver archivo</a></p>";
            EnviarCorreo($asunto, $contenido, $correoDocente);
        }

        // 6️⃣ Obtener correo del estudiante y enviar confirmación
        $sqlEstudiante = "SELECT email FROM aulavirtual.usuario WHERE Id_Usuario = ?";
        $stmtEstudiante = $pdo->prepare($sqlEstudiante);
        $stmtEstudiante->execute([$id_estudiante]);
        $correoEstudiante = $stmtEstudiante->fetchColumn();

        if ($correoEstudiante) {
            $asuntoEst = "Justificación enviada correctamente";
            $contenidoEst = "<p>Hola, tu justificación de ausencia ha sido enviada correctamente. Se encuentra en estado pendiente.</p>
                             <p>Fecha de ausencia: $fecha_ausencia</p>
                             <p>Comprobante: <a href='https://tusitio.com/Aula-Virtual-Santa-Teresita/comprobantes/$nombreArchivo' target='_blank'>Ver archivo</a></p>";
            EnviarCorreo($asuntoEst, $contenidoEst, $correoEstudiante);
        }

        echo json_encode(['ok'=>true, 'mensaje'=>'Justificación enviada correctamente y correos enviados']);
    } else {
        echo json_encode(['ok'=>false, 'error'=>'Error al guardar en la base de datos']);
    }

} catch (Exception $e) {
    echo json_encode(['ok'=>false, 'error'=>'Error de servidor: ' . $e->getMessage()]);
}

exit;
?>
