<?php
require_once "CN_BD.php";
require_once __DIR__ . '/CN_BD.php';
$pdo = (new CN_BD())->conectar();

function recuperarContrasenna($correo)
{
    global $pdo;
    $correo = trim(strtolower($correo));

    $stmt = $pdo->prepare("SELECT Id_Usuario FROM aulavirtual.usuario WHERE LOWER(RTRIM(LTRIM(Email))) = :correo");
    $stmt->execute([':correo' => $correo]);

    $existe = $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;



    return $existe;
}

function guardarCodigoRecuperacion($correo, $codigo, $expiracion)
{
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE aulavirtual.usuario 
        SET CodigoRecuperacion = :codigo, ExpiracionRecuperacion = :expiracion
        WHERE Email = :email
    ");
    return $stmt->execute([
        ':codigo' => $codigo,
        ':expiracion' => $expiracion,
        ':email' => $correo
    ]);
}


function cambiarContrasenna($correo, $codigoHash)
{
    global $pdo;
    $correo = trim($correo);
    $stmt = $pdo->prepare("UPDATE aulavirtual.usuario SET Contrasena = :contrasena WHERE Email = :email");
    return $stmt->execute([
        ':contrasena' => $codigoHash,
        ':email' => $correo
    ]);
}

function GenerarCodigo($longitud = 6)
{
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    return $codigo;
}
