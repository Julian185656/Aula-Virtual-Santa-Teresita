<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$id = (int)($_POST['Id_Usuario'] ?? 0);
if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

try {
    $stmt = $pdo->prepare("CALL eliminarUsuarioFuerte(?)");
    $stmt->execute([$id]);
    $stmt->closeCursor();
    header("Location: admin_usuarios_list.php?deleted=1");
} catch (PDOException $e) {
    http_response_code(400);
    echo "No se pudo eliminar: " . htmlspecialchars($e->getMessage()) .
         "<br><a href='admin_usuarios_list.php'>Volver</a>";
}
