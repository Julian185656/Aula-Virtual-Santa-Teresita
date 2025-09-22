<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/LoginModel.php";

if (isset($_POST["btn-registrarse"])) {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $telefono = $_POST["telefono"];
    $contrasenna = $_POST["contra"];
    $rol = $_POST["rol"];

    try {
        $resultado = UserModel::registrarUsuario($nombre, $correo, $telefono, $contrasenna, $rol);

        if ($resultado) {
            $_SESSION['success_message'] = "¡Cuenta registrada correctamente!";
        } else {
            $_SESSION['error_message'] = "¡Error al registrar la cuenta!";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }

    header("Location: /SantaTeresita/view/Home/Home.php");
    exit();
}
?>
