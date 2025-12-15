<?php
require __DIR__ . '/../../controller/auth_admin.php';
require_once __DIR__ . '/../../controller/AuditoriaHelper.php';

/* ===============================
   CONEXIÓN
=============================== */
$pdo = (new CN_BD())->conectar();

/* ===============================
   DATOS
=============================== */
$idUsuario = (int)($_POST['Id_Usuario'] ?? 0);

/* ===============================
   VALIDACIÓN BÁSICA
=============================== */
if ($idUsuario <= 0) {

    registrarAuditoria(
        'ELIMINAR_USUARIO',
        'Gestión de Usuarios',
        'ID de usuario inválido al intentar eliminar',
        'Fallido'
    );

    http_response_code(400);
    exit('ID inválido');
}

/* ===============================
   ELIMINACIÓN CON TRANSACCIÓN
=============================== */
try {
    $pdo->beginTransaction();

    /* Eliminar registros relacionados (ej. estudiante) */
    $stmt = $pdo->prepare("
        DELETE FROM aulavirtual.estudiante
        WHERE Id_Estudiante = ?
    ");
    $stmt->execute([$idUsuario]);

    /* Eliminar usuario */
    $stmt = $pdo->prepare("
        DELETE FROM aulavirtual.usuario
        WHERE Id_Usuario = ?
    ");
    $stmt->execute([$idUsuario]);

    $pdo->commit();

    registrarAuditoria(
        'ELIMINAR_USUARIO',
        'Gestión de Usuarios',
        "Usuario ID {$idUsuario} eliminado definitivamente",
        'Éxito'
    );

    header("Location: admin_usuarios_list.php?deleted=1");
    exit;
} catch (PDOException $e) {

    $pdo->rollBack();

    registrarAuditoria(
        'ELIMINAR_USUARIO',
        'Gestión de Usuarios',
        "Error al eliminar usuario ID {$idUsuario}",
        'Fallido'
    );

    http_response_code(400);
    echo "No se pudo eliminar el usuario.<br>
          <a href='admin_usuarios_list.php'>Volver</a>";
    exit;
}
