<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/model/LoginModel.php';

if (!empty($_POST['correo'])) {
    $correo = trim($_POST['correo']);
    
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Correo inválido";
        header("Location: Recuperar_contrasena.php");
        exit;
    }

    if (recuperarContrasenna($correo)) {
        $codigo = GenerarCodigo();
        $codigoHash = password_hash($codigo, PASSWORD_DEFAULT);

        if (cambiarContrasenna($correo, $codigoHash)) {
            $contenido = '
            <html>
            <head>
                <style>
                .correo-container {font-family: Arial; padding:20px; background:#f4f6f8; border-radius:10px; max-width:500px; margin:auto;}
                .codigo {font-size:24px; font-weight:bold; color:rgb(79,131,221); text-align:center; margin:20px 0;}
                </style>
            </head>
            <body>
                <div class="correo-container">
                    <p>Hola,</p>
                    <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                    <p>Usa este código para continuar:</p>
                    <div class="codigo">'.$codigo.'</div>
                    <p>Si no solicitaste esto, ignora este mensaje.</p>
                    <p>Atentamente,<br><b>Grupo Mora S.A</b></p>
                </div>
            </body>
            </html>';

            if (EnviarCorreo("Recuperar Contraseña", $contenido, $correo)) {
                $_SESSION['success_message'] = "Revisa tu correo para recuperar tu contraseña.";
            } else {
                $_SESSION['error_message'] = "No se pudo enviar el correo.";
            }
        } else {
            $_SESSION['error_message'] = "No se pudo actualizar la contraseña.";
        }
    } else {
       
        $_SESSION['success_message'] = "Si el correo existe, se enviaron las instrucciones.";
    }

    header("Location: Recuperar_contrasena.php");
    exit;
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
  <a href="https://front.codes/" class="logo" target="_blank">
    <img src="" alt="Logo">
  </a>

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
                      <form method="POST" action="Recuperar_contrasena.php">
                        <div class="form-group mt-2">
                          <input type="email" name="correo" class="form-style" placeholder="Ingresa tu correo" autocomplete="off" required>
                          <i class="input-icon uil uil-at"></i>
                        </div>
                        <button type="submit" class="btn mt-4">Enviar código</button>
                      </form>

                      <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-custom mt-3"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                      <?php endif; ?>

                      <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-custom mt-3"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
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

  <!-- JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
