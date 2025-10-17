<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión - Santa Teresita</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link rel="stylesheet" href="../Styles/Logins.css">

  <style>
    body { background: #f8f9fa; }
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
          <div class="section pb-5 pt-5 text-center">

            <?php if (isset($_SESSION['error_message'])): ?>
              <div class="alert alert-danger alert-custom">
                <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
              </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
              <div class="alert alert-success alert-custom">
                <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
              </div>
            <?php endif; ?>

            <h6 class="mb-0 pb-3"><span>Inicia sesión </span><span>¿Quiénes somos?</span></h6>
            <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
            <label for="reg-log"></label>

            <div class="card-3d-wrap mx-auto">
              <div class="card-3d-wrapper">

               
                <div class="card-front">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">Inicia sesión</h4>
                      <form method="POST" action="../../controller/LoginController.php">
                        <div class="form-group">
                          <input type="email" name="correo" class="form-style" placeholder="Correo institucional" autocomplete="off" required>
                          <i class="input-icon uil uil-at"></i>
                        </div>  
                        <div class="form-group mt-2">
                          <input type="password" name="contra" class="form-style" placeholder="Contraseña" autocomplete="off" required>
                          <i class="input-icon uil uil-lock-alt"></i>
                        </div>
                        <button type="submit" name="btn-login" class="btn mt-4">Ingresar</button>
                      </form>
                      <p class="mb-0 mt-4 text-center"><a href="Recuperar_contrasena.php" class="link">¿Olvidaste tu contraseña?</a></p>
                    </div>
                  </div>
                </div>

             
                <div class="card-back">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">¿Quiénes somos?</h4>
                      <p>
                        La Escuela Santa Teresita, fundada en 1963, es una institución educativa que promueve la excelencia académica y el desarrollo integral de sus estudiantes. 
                        Fomentamos valores como el respeto, la responsabilidad y la solidaridad a través de una enseñanza inclusiva e innovadora.
                      </p>
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
</body>
</html>
