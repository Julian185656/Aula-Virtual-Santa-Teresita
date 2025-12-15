<?php
session_start();

require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . '/AuditoriaHelper.php';

function esCorreoSantateresita(string $correo): bool
{
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
        $_SESSION['error_message'] = " Solo se permiten correos del dominio @santateresita.ac.cr";
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

if (isset($_POST["btn-login"]) || isset($_POST["btn-ingresar"])) {
    $correo      = trim($_POST["correo"] ?? '');
    $contrasenna = $_POST["contra"] ?? '';

    if ($correo === '' || $contrasenna === '') {
        $_SESSION['error_message'] = "Ingresa tu correo y contraseña.";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {

        $usuario = UserModel::iniciarSesion($correo, $contrasenna);

        if ($usuario && isset($usuario['Id_Usuario'])) {

            $_SESSION['usuario'] = [
                'id_usuario' => (int)$usuario['Id_Usuario'],
                'nombre'     => $usuario['Nombre'],
                'correo'     => $usuario['Email'],
                'rol'        => $usuario['Rol'],
            ];


            $_SESSION['id_usuario'] = (int)$usuario['Id_Usuario'];
            $_SESSION['nombre']     = $usuario['Nombre'];
            $_SESSION['rol']        = $usuario['Rol'];

            // ✅ AUDITORÍA LOGIN EXITOSO
            registrarAuditoria(
                'LOGIN_EXITOSO',
                'Autenticación',
                'Inicio de sesión correcto'
            );

            if ($usuario['Rol'] === 'Docente') {
                header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
            } else if ($usuario['Rol'] === 'Estudiante') {
                header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
            } else {
                header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
            }
            exit();
        } else {

            // ❌ AUDITORÍA LOGIN FALLIDO
            registrarAuditoria(
                'LOGIN_FALLIDO',
                'Autenticación',
                'Correo o contraseña incorrectos',
                'Fallido'
            );
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

registrarAuditoria(
    'LOGIN_FALLIDO',
    'Autenticación',
    'Credenciales incorrectas',
    'Fallido'
);

header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
exit();
