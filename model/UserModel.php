<?php

require_once __DIR__ . '/CN_BD.php';
$pdo = (new CN_BD())->conectar();
class CN_BD {
    public function conectar() {
        $server   = "tcp:serverab.database.windows.net,1433";
        $database = "aulavirtual";
        $user     = "Julianab@serverab";
        $pass     = "tuguis2004A@";

        try {
            $pdo = new PDO(
                "sqlsrv:server=$server;Database=$database",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            die("ERROR CONECTANDO AZURE SQL: " . $e->getMessage());
        }
    }
}

class UserModel
{
    private static function conn(): PDO
    {
        return (new CN_BD())->conectar();
    }

    private static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }


    public static function iniciarSesion(string $correo, string $contrasenna): ?array
    {
        $correo = self::cleanEmail($correo);
        $pdo = self::conn();

       
        $sql = "SELECT TOP 1 Id_Usuario, Nombre, Email, Contrasena, Rol, Estado, Telefono
FROM aulavirtual.usuario
WHERE Email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        $row = $stmt->fetch();

        if (!$row) return null;

 
        if ($contrasenna !== $row['Contrasena']) {
            return null;
        }

        if ($row['Estado'] === 'Inactivo') {
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
        $pdo = self::conn();


        $sql = "INSERT INTO aulavirtual.usuario (Nombre, Email, Telefono, Contrasena, Rol, Estado)
                VALUES (?, ?, ?, ?, ?, 'Activo')";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([$nombre, $correo, $telefono, $contrasenna, $rol]);
    }
}
