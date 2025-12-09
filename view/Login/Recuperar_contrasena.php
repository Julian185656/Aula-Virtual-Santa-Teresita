<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/model/LoginModel.php';


$mensaje = '';
$tipoMensaje = '';
$mostrarFormularioCodigo = false;


if (!empty($_POST['correo']) && empty($_POST['codigo'])) {
    $correo = trim($_POST['correo']);
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
        $tipoMensaje = "danger";
    } elseif (recuperarContrasenna($correo)) {
        $codigo = GenerarCodigo();
        $expiracion = date('Y-m-d H:i:s', strtotime('+10 minutes'));

       
        guardarCodigoRecuperacion($correo, $codigo, $expiracion);

        $contenido = "
        <html>
        <body>
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
        <p>Tu código de recuperación es: <strong>$codigo</strong></p>
        <p>Este código expirará en 10 minutos.</p>
        </body>
        </html>
        ";

        if (EnviarCorreo("Recuperar Contraseña", $contenido, $correo)) {
            $mensaje = "Revisa tu correo para obtener el código de recuperación.";
            $tipoMensaje = "success";
            $mostrarFormularioCodigo = true;
        } else {
            $mensaje = "No se pudo enviar el correo. Intenta más tarde.";
            $tipoMensaje = "danger";
        }
    } else {
        $mensaje = "Si el correo existe, se enviaron las instrucciones.";
        $tipoMensaje = "success";
    }
}


if (!empty($_POST['correo']) && !empty($_POST['codigo']) && !empty($_POST['nuevaContrasena'])) {
    $correo = trim($_POST['correo']);
    $codigo = trim($_POST['codigo']);
    $nuevaContrasena = trim($_POST['nuevaContrasena']);

    global $pdo;
    $stmt = $pdo->prepare("SELECT CodigoRecuperacion, ExpiracionRecuperacion FROM aulavirtual.usuario WHERE Email = :email");
    $stmt->execute([':email' => $correo]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        if ($usuario['CodigoRecuperacion'] === $codigo) {
            if (new DateTime() < new DateTime($usuario['ExpiracionRecuperacion'])) {
                cambiarContrasenna($correo, $nuevaContrasena);
                $mensaje = "Contraseña cambiada con éxito. Ahora puedes iniciar sesión con tu nueva contraseña.";
                $tipoMensaje = "success";
            } else {
                $mensaje = "El código ha expirado. Solicita uno nuevo.";
                $tipoMensaje = "danger";
            }
        } else {
            $mensaje = "Código incorrecto.";
            $tipoMensaje = "danger";
        }
    } else {
        $mensaje = "Correo no registrado.";
        $tipoMensaje = "danger";
    }
    $mostrarFormularioCodigo = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="../Styles/Logins.css">
  <style>
    .alert-custom {
      font-weight: bold;
      border-radius: 8px;
      padding: 10px 15px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Recuperar Contraseña</h4>

                                        <?php if($mensaje): ?>
                                            <div class="alert alert-<?= $tipoMensaje ?> alert-custom"><?= htmlspecialchars($mensaje) ?></div>
                                        <?php endif; ?>

                                        <?php if($mostrarFormularioCodigo): ?>
    <form method="POST" action="">
        <input type="hidden" name="correo" value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        <div class="form-group mt-2">
            <input type="text" name="codigo" class="form-style" placeholder="Ingresa el código recibido" required>
            <i class="input-icon uil uil-key-skeleton"></i>
        </div>
        <div class="form-group mt-2">
            <input type="password" name="nuevaContrasena" class="form-style" placeholder="Nueva contraseña" required>
            <i class="input-icon uil uil-lock-alt"></i>
        </div>
        <button type="submit" class="btn mt-4">Cambiar contraseña</button>
        <a href="Login.php" class="btn btn-secondary mt-2" style="width:63%;">Volver</a>
    </form>
<?php else: ?>
    <form method="POST" action="">
        <div class="form-group mt-2">
            <input type="email" name="correo" class="form-style" placeholder="Ingresa tu correo" required>
            <i class="input-icon uil uil-at"></i>
        </div>
        <button type="submit" class="btn mt-4">Enviar código</button>
        <a href="Login.php" class="btn btn-secondary mt-2" style="width:52%;">Volver</a>
    </form>
<?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
