<?php

require_once __DIR__ . '/CN_BD.php';
/*$pdo = (new CN_BD())->conectar();
class CN_BD
{
    public function conectar()
    {
        $server   = "tcp:serverab.database.windows.net,1433";
        $database = "SantaTereS";
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
}*/

class UserModel
{
    /* ===============================
       CONEXIÓN
    =============================== */
    private static function conn(): PDO
    {
        return (new CN_BD())->conectar();
    }

    private static function cleanEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    /* ===============================
       LOGIN
    =============================== */
    public static function iniciarSesion(string $correo, string $contrasenna): ?array
    {
        $correo = self::cleanEmail($correo);
        $pdo = self::conn();

        $sql = "
            SELECT TOP 1
                Id_Usuario,
                Nombre,
                Email,
                Contrasena,
                Rol,
                Estado,
                Telefono
            FROM aulavirtual.usuario
            WHERE Email = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        /* Usuario inactivo */
        if ($row['Estado'] !== 'Activo') {
            throw new Exception('La cuenta está inactiva.');
        }

        /*  Verificación segura de contraseña */
        if (!password_verify($contrasenna, $row['Contrasena'])) {
            return null;
        }

        /* Nunca devolver la contraseña */
        unset($row['Contrasena']);
        return $row;
    }

    /* ===============================
       REGISTRO / CREACIÓN DE USUARIO
    =============================== */
    public static function registrarUsuario(
        string $nombre,
        string $correo,
        string $telefono,
        string $contrasenna,
        string $rol,
        string $estado = 'Activo'
    ): bool {

        $correo = self::cleanEmail($correo);
        $pdo = self::conn();

        /* 🔐 HASH DE CONTRASEÑA */
        $hash = password_hash($contrasenna, PASSWORD_BCRYPT);

        $sql = "
            INSERT INTO aulavirtual.usuario
                (Nombre, Email, Telefono, Contrasena, Rol, Estado)
            VALUES
                (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $nombre,
            $correo,
            $telefono,
            $hash,
            $rol,
            $estado
        ]);
    }

    /* ===============================
       CAMBIO / RECUPERACIÓN DE CONTRASEÑA
    =============================== */
    public static function cambiarContrasenna(string $correo, string $nuevaContrasenna): bool
    {
        $correo = self::cleanEmail($correo);
        $pdo = self::conn();

        $hash = password_hash($nuevaContrasenna, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            UPDATE aulavirtual.usuario
            SET Contrasena = ?, CodigoRecuperacion = NULL, ExpiracionRecuperacion = NULL
            WHERE Email = ?
        ");

        return $stmt->execute([$hash, $correo]);
    }
}
