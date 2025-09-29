<?php
// model/UserModel.php
require_once __DIR__ . '/CN_BD.php';

class UserModel
{
    private static function conn(): mysqli
    {
        if (class_exists('CN_BD')) {
            $cn = new CN_BD();
            if (method_exists($cn, 'conectar')) {
                $cx = $cn->conectar();
                if ($cx instanceof mysqli) return $cx;
            }
        }
        $cx = @new mysqli('127.0.0.1', 'root', '', 'aulavirtual', 3307);
        if ($cx->connect_errno) {
            $cx = new mysqli('127.0.0.1', 'root', '', 'aulavirtual'); // intenta 3306
        }
        return $cx;
    }

    /** Normaliza email a minúsculas y sin espacios */
    private static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /** LOGIN: devuelve array con datos del usuario o null si falla */
    public static function iniciarSesion(string $correo, string $contrasenna): ?array
    {
        $correo = self::cleanEmail($correo);
        $cx = self::conn();
        if ($cx->connect_errno) {
            throw new Exception('Error de conexión a BD: ' . $cx->connect_error);
        }

        $sql = "SELECT Id_Usuario, Nombre, Email, Contrasena, Rol, Estado, Telefono
                FROM usuario
                WHERE Email = ?
                LIMIT 1";
        $stmt = $cx->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error preparando consulta: ' . $cx->error);
        }
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        $cx->close();

        if (!$row) {
            return null; // no existe
        }
        if (!password_verify($contrasenna, $row['Contrasena'])) {
            return null; // clave incorrecta
        }
        if (isset($row['Estado']) && $row['Estado'] === 'Inactivo') {
            throw new Exception('La cuenta está inactiva.');
        }

        // No regresamos el hash
        unset($row['Contrasena']);
        return $row;
    }

    /**
     * REGISTRO: usa el procedimiento almacenado `registroUsuario`
     * Retorna true si se insertó; lanza Exception si falla
     */
    public static function registrarUsuario(
        string $nombre,
        string $correo,
        string $telefono,
        string $contrasenna,
        string $rol
    ): bool {
        $correo = self::cleanEmail($correo);
        $hash = password_hash($contrasenna, PASSWORD_BCRYPT);

        $cx = self::conn();
        if ($cx->connect_errno) {
            throw new Exception('Error de conexión a BD: ' . $cx->connect_error);
        }

        // Llama tu SP que valida @santateresita.ac.cr y estado = 'Activo'
        $stmt = $cx->prepare("CALL registroUsuario(?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception('Error preparando SP: ' . $cx->error);
        }
        $stmt->bind_param('sssss', $nombre, $correo, $telefono, $hash, $rol);

        $ok = $stmt->execute();
        if (!$ok) {
            $msg = $cx->error ?: $stmt->error;
            $stmt->close();
            $cx->close();
            throw new Exception($msg);
        }

        
        while ($stmt->more_results() && $stmt->next_result()) { /* nada */ }

        $stmt->close();
        $cx->close();
        return true;
    }
}
