<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$id     = (int)($_POST['Id_Usuario'] ?? 0);
$estado = $_POST['Estado'] ?? 'Inactivo';
if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

try {
    $stmt = $pdo->prepare("CALL desactivarUsuario(?, ?)");
    $stmt->execute([$id, $estado]);
    $stmt->closeCursor();
    header("Location: admin_usuarios_list.php?toggled=1");
} catch (PDOException $e) {
    http_response_code(400);
    echo "No se pudo cambiar el estado: " . htmlspecialchars($e->getMessage());
}
