<?php
require __DIR__ . '/../../controller/auth_admin.php';
require_once __DIR__ . '/../../controller/AuditoriaHelper.php';

$pdo = (new CN_BD())->conectar();

/* ===============================
   DATOS DEL FORMULARIO
=============================== */
$id       = (int)($_POST['Id_Usuario'] ?? 0);
$nombre   = trim($_POST['Nombre'] ?? '');
$email    = strtolower(trim($_POST['Email'] ?? ''));
$telefono = trim($_POST['Telefono'] ?? '');
$estado   = $_POST['Estado'] ?? 'Activo';
$rolNuevo = $_POST['Rol'] ?? 'Estudiante';

/*
$contrasena = $_POST['Contrasena'] ?? '';
$contrasenaHasheada = !empty($contrasena)
    ? password_hash($contrasena, PASSWORD_BCRYPT)
    : null;
*/

/* ===============================
   VALIDACIONES BÁSICAS
=============================== */
if ($id <= 0 || $nombre === '' || $email === '') {
    $_SESSION['error_message'] = 'Datos inválidos.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* Email institucional */
if (!preg_match('/^[^@\s]+@santateresita\.ac\.cr$/i', $email)) {
    $_SESSION['error_message'] = 'Solo se permiten correos institucionales.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* Teléfono: solo 8 dígitos */
if ($telefono !== '' && !preg_match('/^\d{8}$/', $telefono)) {
    $_SESSION['error_message'] = 'El teléfono debe tener exactamente 8 números.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}

/* ===============================
   DATOS ACTUALES (ANTES DEL CAMBIO)
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
   VALIDAR EMAIL DUPLICADO
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
   ACTUALIZACIÓN
=============================== */
try {
    $stmt = $pdo->prepare("
        UPDATE aulavirtual.usuario
        SET Nombre   = ?,
            Email    = ?,
            Telefono = ?,
            Rol      = ?,
            Estado   = ?
        WHERE Id_Usuario = ?
    ");
    $stmt->execute([
        $nombre,
        $email,
        $telefono,
        $rolNuevo,
        $estado,
        $id
    ]);

    /* ===============================
       AUDITORÍA
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
    registrarAuditoria(
        'ACTUALIZAR_USUARIO',
        'Gestión de Usuarios',
        'Error al actualizar usuario',
        'Fallido'
    );

    $_SESSION['error_message'] = 'Error al actualizar el usuario.';
    header("Location: admin_usuario_edit.php?id={$id}");
    exit;
}
