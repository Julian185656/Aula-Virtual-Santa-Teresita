<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';


$id       = (int)($_POST['Id_Usuario'] ?? 0);
$nombre   = trim($_POST['Nombre'] ?? '');
$email    = trim($_POST['Email'] ?? '');
$telefono = trim($_POST['Telefono'] ?? '');
$estado   = $_POST['Estado'] ?? 'Activo';
$rol      = $_POST['Rol'] ?? 'Estudiante';


if ($id <= 0 || empty($nombre) || empty($email)) {
    http_response_code(400);
    exit('Datos inválidos');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Correo inválido');
}


$contrasenaHasheada = !empty($_POST['Contrasena']) 
    ? password_hash($_POST['Contrasena'], PASSWORD_BCRYPT)
    : null;

try {
    if ($contrasenaHasheada) {
        $stmt = $pdo->prepare("
            UPDATE aulavirtual.usuario 
            SET Nombre = ?, 
                Email = ?, 
                Telefono = ?, 
                Contrasena = ?, 
                Rol = ?, 
                Estado = ?
            WHERE Id_Usuario = ?
        ");
        $stmt->execute([$nombre, $email, $telefono, $contrasenaHasheada, $rol, $estado, $id]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE aulavirtual.usuario 
            SET Nombre = ?, 
                Email = ?, 
                Telefono = ?, 
                Rol = ?, 
                Estado = ?
            WHERE Id_Usuario = ?
        ");
        $stmt->execute([$nombre, $email, $telefono, $rol, $estado, $id]);
    }

    header("Location: admin_usuarios_list.php?ok=1");
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo "Error al actualizar: " . htmlspecialchars($e->getMessage());
}
?>
