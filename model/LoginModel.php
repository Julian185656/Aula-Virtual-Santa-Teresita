<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php"; 


function recuperarContrasenna($correo) {
    global $pdo; 
    $stmt = $pdo->prepare("SELECT Id_Usuario FROM usuario WHERE Email = :correo");
    $stmt->execute([':correo' => $correo]);
    return $stmt->rowCount() > 0;
}


function cambiarContrasenna($correo, $codigoHash) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE usuario SET Contrasena = :contrasena WHERE Email = :email");
    return $stmt->execute([
        ':contrasena' => $codigoHash,
        ':email' => $correo
    ]);
}


function GenerarCodigo($longitud = 6) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    return $codigo;
}
?>
