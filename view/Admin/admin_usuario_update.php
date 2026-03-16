<?php
require __DIR__ . '/../../controller/auth_admin.php';
require_once __DIR__ . '/../../controller/AuditoriaHelper.php';

$pdo = (new CN_BD())->conectar();



/* ===============================
   DATOS DEL FORMULARIO
=============================== */
$id       = isset($_POST['Id_Usuario']) ? (int)$_POST['Id_Usuario'] : 0;
$nombre   = isset($_POST['Nombre']) ? trim($_POST['Nombre']) : '';
$email    = isset($_POST['Email']) ? strtolower(trim($_POST['Email'])) : '';
$telefono = isset($_POST['Telefono']) ? trim($_POST['Telefono']) : '';
$rolNuevo = isset($_POST['Rol']) ? trim($_POST['Rol']) : 'Estudiante';
$estado   = isset($_POST['Estado']) ? trim($_POST['Estado']) : 'Activo';

/* ===============================
   VALIDACIONES
=============================== */
if ($id <= 0 || $nombre === '' || $email === '') {
    $_SESSION['error_message'] = 'Datos inválidos.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* validar email */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = 'Correo inválido.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* validar telefono */
if ($telefono !== '' && !preg_match('/^\d{8}$/', $telefono)) {
    $_SESSION['error_message'] = 'El teléfono debe tener 8 dígitos.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* ===============================
   DATOS ACTUALES
=============================== */
$stmt = $pdo->prepare("
SELECT Email, Rol
FROM aulavirtual.usuario
WHERE Id_Usuario = ?
");
$stmt->execute([$id]);
$actual = $stmt->fetch();

if (!$actual) {
    $_SESSION['error_message'] = 'Usuario no encontrado.';
    header("Location: admin_usuarios_list.php");
    exit;
}

$emailAnterior = strtolower($actual['Email']);
$rolAnterior   = $actual['Rol'];

/* ===============================
   EMAIL DUPLICADO
=============================== */
$stmt = $pdo->prepare("
SELECT 1
FROM aulavirtual.usuario
WHERE Email = ?
AND Id_Usuario <> ?
");
$stmt->execute([$email, $id]);

if ($stmt->fetch()) {
    $_SESSION['error_message'] = 'El correo ya está registrado por otro usuario.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* ===============================
   UPDATE
=============================== */
try {

$stmt = $pdo->prepare("
UPDATE aulavirtual.usuario
SET
    Nombre = :nombre,
    Email = :email,
    Telefono = :telefono,
    Rol = :rol,
    Estado = :estado
WHERE Id_Usuario = :id
");

$stmt->execute([
    ':nombre'   => $nombre,
    ':email'    => $email,
    ':telefono' => $telefono,
    ':rol'      => $rolNuevo,
    ':estado'   => $estado,
    ':id'       => $id
]);

/* ===============================
   AUDITORIA
=============================== */

if ($emailAnterior !== $email) {
    registrarAuditoria(
        'CAMBIO_EMAIL',
        'Gestión de Usuarios',
        "Email cambiado de {$emailAnterior} a {$email}"
    );
}

if ($rolAnterior !== $rolNuevo) {
    registrarAuditoria(
        'CAMBIO_ROL',
        'Gestión de Usuarios',
        "Rol cambiado de {$rolAnterior} a {$rolNuevo}"
    );
}

$_SESSION['success_message'] = 'Usuario actualizado correctamente.';
header("Location: admin_usuarios_list.php?ok=1");
exit;

} catch (PDOException $e) {

$_SESSION['error_message'] = 'Error al actualizar usuario.';
header("Location: admin_usuario_edit.php?id={$id}");
exit;

}