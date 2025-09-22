<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/SantaTeresita/model/CN_BD.php";

class UserModel {

    public static function registrarUsuario($nombre, $correo, $telefono, $contrasenna, $rol): bool {
        $conexion = AbrirBaseDatos();

        if (!$conexion) {
            throw new Exception("Error al conectar con la base de datos.");
        }

        $hashed = password_hash($contrasenna, PASSWORD_DEFAULT);

        $query = "CALL registroUsuario(?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);

        if (!$stmt) {
            throw new Exception("Error al preparar la sentencia: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $telefono, $hashed, $rol);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al ejecutar la sentencia: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        CerrarBaseDatos($conexion);

        return true;
    }
}
?>
