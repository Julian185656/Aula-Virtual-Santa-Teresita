<?php

require_once __DIR__ . '/CN_BD.php';


if (!class_exists('CN_BD')) {

    class CN_BD {
        public function conectar() {
            return new mysqli('127.0.0.1', 'root', '', 'aulavirtual', 3307);
        }
    }
}

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

    private static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }


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
            return null;
        }
        if (!password_verify($contrasenna, $row['Contrasena'])) {
            return null;
        }
        if (isset($row['Estado']) && $row['Estado'] === 'Inactivo') {
            throw new Exception('La cuenta está inactiva.');
        }


        unset($row['Contrasena']);
        return $row;
    }


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

        
        while ($stmt->more_results() && $stmt->next_result()) {  }

        $stmt->close();
        $cx->close();
        return true;
    }
}
