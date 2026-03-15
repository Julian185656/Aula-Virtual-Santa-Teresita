<?php
require_once __DIR__ . '/CN_BD.php';
$pdo = (new CN_BD())->conectar();

function recuperarContrasenna($correo)
{
    global $pdo;

    $correo = trim(strtolower($correo));

    $stmt = $pdo->prepare("
        SELECT Id_Usuario 
        FROM aulavirtual.usuario 
        WHERE LOWER(TRIM(Email)) = :correo
    ");

    $stmt->execute([':correo' => $correo]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}

function guardarCodigoRecuperacion($correo, $codigo, $expiracion)
{
    global $pdo;

    $stmt = $pdo->prepare("
        UPDATE aulavirtual.usuario 
        SET CodigoRecuperacion = :codigo,
            ExpiracionRecuperacion = :expiracion
        WHERE Email = :email
    ");

    return $stmt->execute([
        ':codigo' => $codigo,
        ':expiracion' => $expiracion,
        ':email' => $correo
    ]);
}

function obtenerCodigoUsuario($correo)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT CodigoRecuperacion, ExpiracionRecuperacion
        FROM aulavirtual.usuario
        WHERE Email = :email
    ");

    $stmt->execute([':email' => $correo]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function cambiarContrasenna($correo, $passwordHash)
{
    global $pdo;

    $stmt = $pdo->prepare("
        UPDATE aulavirtual.usuario 
        SET Contrasena = :contrasena,
            CodigoRecuperacion = NULL,
            ExpiracionRecuperacion = NULL
        WHERE Email = :email
    ");

    return $stmt->execute([
        ':contrasena' => $passwordHash,
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