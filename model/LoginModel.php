<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php"; 

// ✅ Verifica si un usuario existe por correo
function recuperarContrasenna($email) {
    global $pdo; 
    $stmt = $pdo->prepare("SELECT Id_Usuario FROM usuario WHERE Email = :email");
    $stmt->execute([':email' => $email]);
    return $stmt->rowCount() > 0;
}

// ✅ Actualiza la contraseña de un usuario
function cambiarContrasenna($email, $codigoHash) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE usuario SET Contrasena = :contrasena WHERE Email = :email");
    return $stmt->execute([
        ':contrasena' => $codigoHash,
        ':email' => $email
    ]);
}

// ✅ Genera un código aleatorio (para recuperación)
function GenerarCodigo($longitud = 6) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    return $codigo;
}
?>
