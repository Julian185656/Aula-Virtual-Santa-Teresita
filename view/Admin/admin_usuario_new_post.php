<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$pdo = (new CN_BD())->conectar();


$nombre       = trim($_POST['Nombre'] ?? '');
$email        = trim($_POST['Email'] ?? '');
$telefono     = trim($_POST['Telefono'] ?? '');
$rol          = $_POST['Rol'] ?? 'Estudiante';
$estado       = $_POST['Estado'] ?? 'Activo';
$grado        = $_POST['Grado'] ?? null;
$seccion      = $_POST['Seccion'] ?? null;
$especialidad = $_POST['Especialidad'] ?? null;
$contrasena   = $_POST['Contrasena'] ?? '';


if (empty($contrasena)) {
    http_response_code(400);
    exit('Contraseña requerida');
}

try {
   
    $stmt = $pdo->prepare("EXEC aulavirtual.crearUsuarioAdmin ?, ?, ?, ?, ?, ?, ?, ?, ?");
    $stmt->execute([
        $nombre,
        $email,
        $telefono,
        $contrasena, 
        $rol,
        $estado,
        $grado,
        $seccion,
        $especialidad
    ]);

    $stmt->closeCursor();

  
    header("Location: admin_usuarios_list.php?created=1");
    exit;
} catch (PDOException $e) {
    http_response_code(400);
    echo "Error al crear usuario: " . htmlspecialchars($e->getMessage());
}
