<?php
session_start();
require_once __DIR__ . "/../model/UserModel.php";


function esCorreoSantateresita(string $correo): bool {
    $correo = trim($correo);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) return false;
    $partes = explode('@', $correo, 2);
    return isset($partes[1]) && strcasecmp($partes[1], 'santateresita.ac.cr') === 0;
}

if (isset($_POST["btn-registrarse"])) {
    $nombre      = trim($_POST["nombre"] ?? '');
    $correo      = trim($_POST["correo"] ?? '');
    $telefono    = trim($_POST["telefono"] ?? '');
    $contrasenna = $_POST["contra"] ?? '';
    $rol         = trim($_POST["rol"] ?? '');

    if (!esCorreoSantateresita($correo)) {
        $_SESSION['error_message'] = "❌ Solo se permiten correos del dominio @santateresita.ac.cr";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {
        $ok = UserModel::registrarUsuario($nombre, $correo, $telefono, $contrasenna, $rol);
        $_SESSION['success_message'] = $ok
            ? "✅ ¡Cuenta registrada correctamente!"
            : "❌ No se pudo registrar la cuenta.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "⚠️ Error: " . $e->getMessage();
    }

    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}


if (isset($_POST["btn-login"]) || isset($_POST["btn-ingresar"])) {
    $correo      = trim($_POST["correo"] ?? '');
    $contrasenna = $_POST["contra"] ?? '';

    if ($correo === '' || $contrasenna === '') {
        $_SESSION['error_message'] = "⚠️ Ingresa tu correo y contraseña.";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {
        $usuario = UserModel::iniciarSesion($correo, $contrasenna);

        if ($usuario && isset($usuario['Id_Usuario'])) {
            // ✅ Guardar datos en sesión
            $_SESSION['id_usuario'] = (int)$usuario['Id_Usuario'];
            $_SESSION['nombre']     = $usuario['Nombre'];
            $_SESSION['rol']        = $usuario['Rol'];
            $_SESSION['correo']     = $usuario['Email'];

            // ✅ Redirección según rol
            switch ($usuario['Rol']) {
                case 'Administrador':
                case 'Docente':
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php");
                    break;
                case 'Estudiante':
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php");
                    break;
                default:
                    $_SESSION['error_message'] = "⚠️ Rol no reconocido.";
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
                    break;
            }
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Correo o contraseña incorrectos.";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "⚠️ Error en el inicio de sesión: " . $e->getMessage();
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }
}


header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
exit();
