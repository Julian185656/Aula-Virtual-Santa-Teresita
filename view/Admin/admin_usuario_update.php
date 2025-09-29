<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$id          = (int)($_POST['Id_Usuario'] ?? 0);
$nombre      = trim($_POST['Nombre'] ?? '');
$email       = trim($_POST['Email'] ?? '');
$telefono    = trim($_POST['Telefono'] ?? '');
$estado      = $_POST['Estado'] ?? 'Activo';
$rolNuevo    = $_POST['Rol'] ?? 'Estudiante';

$grado        = $_POST['Grado'] ?? null;
$seccion      = $_POST['Seccion'] ?? null;
$especialidad = $_POST['Especialidad'] ?? null;

// Si viene una nueva contraseña, la hasheamos; si no, pasamos NULL
$contrasenaHasheada = null;
if (!empty($_POST['Contrasena'])) {
    $contrasenaHasheada = password_hash($_POST['Contrasena'], PASSWORD_BCRYPT);
}

try {
    $stmt = $pdo->prepare("CALL actualizarUsuarioAdmin(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $id, $nombre, $email, $telefono, $contrasenaHasheada,
        $rolNuevo, $estado, $grado, $seccion, $especialidad
    ]);

    
    while ($stmt->nextRowset()) { /* no-op */ }
    $stmt->closeCursor();

    header("Location: admin_usuarios_list.php?ok=1");
    exit;
} catch (PDOException $e) {
    http_response_code(400);
    echo "Error al actualizar: " . htmlspecialchars($e->getMessage());
}
