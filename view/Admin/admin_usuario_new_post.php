<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';
$pdo = (new CN_BD())->conectar();
$nombre      = trim($_POST['Nombre'] ?? '');
$email       = trim($_POST['Email'] ?? '');
$telefono    = trim($_POST['Telefono'] ?? '');
$rol         = $_POST['Rol'] ?? 'Estudiante';
$estado      = $_POST['Estado'] ?? 'Activo';
$grado       = $_POST['Grado'] ?? null;
$seccion     = $_POST['Seccion'] ?? null;
$especialidad= $_POST['Especialidad'] ?? null;

if (empty($_POST['Contrasena'])) {
    http_response_code(400);
    exit('Contraseña requerida');
}
$contrasenaHasheada = password_hash($_POST['Contrasena'], PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("EXEC aulavirtual.crearUsuarioAdmin ?, ?, ?, ?, ?, ?, ?, ?, ?");
    $stmt->execute([
        $nombre, $email, $telefono, $contrasenaHasheada, $rol, $estado, $grado, $seccion, $especialidad
    ]);
    $stmt->closeCursor();
    header("Location: admin_usuarios_list.php?created=1");
} catch (PDOException $e) {
    http_response_code(400);
    echo "Error al crear usuario: " . htmlspecialchars($e->getMessage());
}
