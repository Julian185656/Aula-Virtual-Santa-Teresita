<?php
// C:\xampp\htdocs\Aula-Virtual-Santa-Teresita\model\UserModel.php
include_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CN_BD.php";

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

    public static function iniciarSesion($correo, $contrasenna) {
        $conexion = AbrirBaseDatos();
        if (!$conexion) {
            throw new Exception("Error al conectar con la base de datos.");
        }

      $query = "SELECT Id_Usuario, Nombre, Email, Contrasena, Rol, Estado 
                  FROM USUARIO 
                  WHERE Email = ?";
        $stmt = mysqli_prepare($conexion, $query);

        if (!$stmt) {
            throw new Exception("Error al preparar la sentencia: " . mysqli_error($conexion));
        }

        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $usuario = mysqli_fetch_assoc($resultado);

        mysqli_stmt_close($stmt);
        CerrarBaseDatos($conexion);

        if ($usuario && password_verify($contrasenna, $usuario['Contrasena'])) {
            return $usuario; // Devuelve el usuario autenticado
        }
        return false;
    }
}
?>
