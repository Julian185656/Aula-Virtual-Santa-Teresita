<?php
session_start();

// Incluir el modelo correcto
require_once __DIR__ . "/../model/UserModel.php";

/* =========================
   Función para validar dominio
   ========================= */
function esCorreoSantateresita(string $correo): bool {
    $correo = trim($correo);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) return false;
    $partes = explode('@', $correo, 2);
    return isset($partes[1]) && strcasecmp($partes[1], 'santateresita.ac.cr') === 0;
}

/* =========================
   REGISTRO
   ========================= */
if (isset($_POST["btn-registrarse"])) {
    $nombre      = trim($_POST["nombre"] ?? '');
    $correo      = trim($_POST["correo"] ?? '');
    $telefono    = trim($_POST["telefono"] ?? '');
    $contrasenna = $_POST["contra"] ?? '';
    $rol         = trim($_POST["rol"] ?? '');

    // Solo permitir correos institucionales al registrarse
    if (!esCorreoSantateresita($correo)) {
        $_SESSION['error_message'] = "❌ Solo se permiten correos del dominio @santateresita.ac.cr";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {
        $ok = UserModel::registrarUsuario($nombre, $correo, $telefono, $contrasenna, $rol);
        if ($ok) {
            $_SESSION['success_message'] = "¡Cuenta registrada correctamente!";
        } else {
            $_SESSION['error_message'] = "No se pudo registrar la cuenta.";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
    exit();
}

/* =========================
   INICIAR SESIÓN
   ========================= */
if (isset($_POST["btn-login"]) || isset($_POST["btn-ingresar"])) {
    $correo      = trim($_POST["correo"] ?? '');
    $contrasenna = $_POST["contra"] ?? '';

    if ($correo === '' || $contrasenna === '') {
        $_SESSION['error_message'] = "Ingresa tu correo y contraseña.";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {
        // Debe devolver los campos de la tabla `usuario`
        $usuario = UserModel::iniciarSesion($correo, $contrasenna);

        if ($usuario && isset($usuario['Id_Usuario'])) {
            // Estructura estándar para todo el sitio
            $_SESSION['usuario'] = [
                'Id_Usuario' => (int)$usuario['Id_Usuario'],
                'Nombre'     => $usuario['Nombre'],
                'Email'      => $usuario['Email'],
                'Rol'        => $usuario['Rol'],
            ];
            // Compatibilidad con Home.php
            $_SESSION['nombre'] = $usuario['Nombre'];
            $_SESSION['rol']    = $usuario['Rol'];

            header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }
}

/* =========================
   ========================= */
header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
exit();
