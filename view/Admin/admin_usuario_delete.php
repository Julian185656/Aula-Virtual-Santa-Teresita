<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';
$pdo = (new CN_BD())->conectar();
$id = (int)($_POST['Id_Usuario'] ?? 0);
if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("DELETE FROM aulavirtual.estudiante WHERE Id_Estudiante = ?");
    $stmt->execute([$id]);


    $stmt = $pdo->prepare("DELETE FROM aulavirtual.usuario WHERE Id_Usuario = ?");
    $stmt->execute([$id]);

    $pdo->commit();

    header("Location: admin_usuarios_list.php?deleted=1");
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo "No se pudo eliminar: " . htmlspecialchars($e->getMessage()) .
         "<br><a href='admin_usuarios_list.php'>Volver</a>";
}
