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
$estado    = $_POST['Estado'] ?? 'Inactivo';

/* ===============================
   VALIDACIÓN BÁSICA
=============================== */
if ($idUsuario <= 0 || !in_array($estado, ['Activo', 'Inactivo'])) {

    registrarAuditoria(
        'CAMBIO_ESTADO',
        'Gestión de Usuarios',
        'Datos inválidos para cambiar estado de usuario',
        'Fallido'
    );

    http_response_code(400);
    exit('Datos inválidos');
}

/* ===============================
   CAMBIO DE ESTADO
=============================== */
try {
    $stmt = $pdo->prepare("EXEC aulavirtual.desactivarUsuario ?, ?");
    $stmt->execute([$idUsuario, $estado]);
    $stmt->closeCursor();

    registrarAuditoria(
        'CAMBIO_ESTADO',
        'Gestión de Usuarios',
        "Usuario ID {$idUsuario} cambiado a estado {$estado}",
        'Éxito'
    );

    header("Location: admin_usuarios_list.php?toggled=1");
    exit;
} catch (PDOException $e) {

    registrarAuditoria(
        'DESACTIVAR_USUARIO',
        'Gestión de Usuarios',
        "Error al cambiar estado del usuario ID {$idUsuario}",
        'Fallido'
    );

    http_response_code(400);
    echo "No se pudo cambiar el estado.";
    exit;
}
