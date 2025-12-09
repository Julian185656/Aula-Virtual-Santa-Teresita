
<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/PHPMailer.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/SMTP.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/PHPMailer/src/Exception.php';


function EnviarCorreo($asunto, $contenido, $destinatario)
{
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

        $mail->setFrom($correoSalida, 'Importadora Mora');
        $mail->Subject = $asunto;
        $mail->MsgHTML($contenido);
        $mail->addAddress($destinatario);

        return $mail->send();

    } catch (Exception $e) {
        echo "Error al enviar el correo: " . $mail->ErrorInfo;
        return false;
    }
}
?>