<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>


  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

 
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


  <link rel="stylesheet" href="../Styles/Logins.css">
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
            <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
            <input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
            <label for="reg-log"></label>
            <div class="card-3d-wrap mx-auto">
              <div class="card-3d-wrapper">

             
                <div class="card-front">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">Log In</h4>
                      
                      <form method="POST" action="../../controller/LoginController.php">
                        <div class="form-group">
                          <input type="email" name="correo" class="form-style" placeholder="Your Email" autocomplete="off" required>
                          <i class="input-icon uil uil-at"></i>
                        </div>  
                        <div class="form-group mt-2">
                          <input type="password" name="contra" class="form-style" placeholder="Your Password" autocomplete="off" required>
                          <i class="input-icon uil uil-lock-alt"></i>
                        </div>
                        <button type="submit" name="btn-login" class="btn mt-4">Ingresar</button>
                      </form>
                      <p class="mb-0 mt-4 text-center"><a href="#0" class="link">¿Olvidaste tu contraseña?</a></p>
                    </div>
                  </div>
                </div>

         
                <div class="card-back">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">Sign Up</h4>
                      <form method="POST" action="../../controller/LoginController.php">
                        
                        <div class="form-group">
                          <input type="text" name="nombre" class="form-style" placeholder="Your Full Name" autocomplete="off" required>
                          <i class="input-icon uil uil-user"></i>
                        </div>  

                        <div class="form-group mt-2">
                          <input type="email" name="correo" class="form-style" placeholder="Your Email" autocomplete="off" required>
                          <i class="input-icon uil uil-at"></i>
                        </div>  

                        <div class="form-group mt-2">
                          <input type="text" name="telefono" class="form-style" placeholder="Your Phone" autocomplete="off">
                          <i class="input-icon uil uil-phone"></i>
                        </div>  

                        <div class="form-group mt-2">
                          <input type="password" name="contra" class="form-style" placeholder="Your Password" autocomplete="off" required>
                          <i class="input-icon uil uil-lock-alt"></i>
                        </div>

                        <div class="form-group mt-2">
                          <select name="rol" class="form-style" required>
                            <option value="" disabled selected>Seleccione un rol</option>
                            <option value="Estudiante">Estudiante</option>
                            <option value="Docente">Docente</option>
                            <option value="Padre">Padre</option>
                            <option value="Administrador">Administrador</option>
                          </select>
                        </div>

                        <button type="submit" name="btn-registrarse" class="btn mt-4">Registrarse</button>
                      </form>
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
