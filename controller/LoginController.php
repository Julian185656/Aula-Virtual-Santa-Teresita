<?php
session_start();

// Incluir el modelo correcto
require_once __DIR__ . "/../model/UserModel.php";

// =========================
// Función para validar dominio
// =========================
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

    // Bloquear dominios no permitidos
    if (!esCorreoSantateresita($correo)) {
        $_SESSION['error_message'] = "❌ Solo se permiten correos del dominio @santateresita.ac.cr";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
    }

    try {
        /** @var bool $resultado */
        $resultado = UserModel::registrarUsuario($nombre, $correo, $telefono, $contrasenna, $rol);

        if ($resultado) {
            $_SESSION['success_message'] = "¡Cuenta registrada correctamente!";
        } else {
            $_SESSION['error_message'] = "⚠️ $resultado";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

   header("Location: /Aula-Virtual-Santa-Teresita/view/Home/Home.php");
    exit();
}
?>
