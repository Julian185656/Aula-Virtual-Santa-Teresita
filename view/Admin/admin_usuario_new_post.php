<?php
require __DIR__ . '/../../controller/auth_admin.php';
require_once __DIR__ . '/../../controller/AuditoriaHelper.php';

$pdo = (new CN_BD())->conectar();

/* ===============================
   DATOS DEL FORMULARIO
=============================== */
$nombre       = trim($_POST['Nombre'] ?? '');
$email        = strtolower(trim($_POST['Email'] ?? ''));
$telefono     = trim($_POST['Telefono'] ?? '');
$rol          = $_POST['Rol'] ?? 'Estudiante';
$estado       = $_POST['Estado'] ?? 'Activo';
$grado        = $_POST['Grado'] ?? null;
$seccion      = $_POST['Seccion'] ?? null;
$especialidad = $_POST['Especialidad'] ?? null;
$contrasena   = $_POST['Contrasena'] ?? '';

/* ===============================
   VALIDACIÓN 1: CAMPOS OBLIGATORIOS
=============================== */
if ($nombre === '' || $email === '' || $contrasena === '') {
    $_SESSION['error_message'] = 'Todos los campos obligatorios deben completarse.';
    header("Location: admin_usuario_new.php");
    exit;
}

/* ===============================
   VALIDACIÓN 2: EMAIL INSTITUCIONAL
=============================== */
if (!preg_match('/^[^@\s]+@santateresita\.ac\.cr$/i', $email)) {
    $_SESSION['error_message'] = 'Solo se permiten correos institucionales @santateresita.ac.cr';
    header("Location: admin_usuario_new.php");
    exit;
}

/* ===============================
   VALIDACIÓN 3: CONTRASEÑA FUERTE
=============================== */
$regexPassword = '/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';

if (!preg_match($regexPassword, $contrasena)) {
    $_SESSION['error_message'] =
        'La contraseña debe tener mínimo 8 caracteres, una mayúscula, un número y un carácter especial.';
    header("Location: admin_usuario_new.php");
    exit;
}

/* ===============================
   VALIDACIÓN 4: TELÉFONO (si existe)
=============================== */
if ($telefono !== '' && !preg_match('/^\d{8}$/', $telefono)) {
    $_SESSION['error_message'] = 'El teléfono debe tener exactamente 8 números.';
    header("Location: admin_usuario_new.php");
    exit;
}

/* ===============================
   VALIDACIÓN 5: EMAIL DUPLICADO
=============================== */
$stmt = $pdo->prepare("
    SELECT 1
    FROM aulavirtual.usuario
    WHERE Email = ?
");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    $_SESSION['error_message'] = 'El correo ya se encuentra registrado.';
    header("Location: admin_usuario_new.php");
    exit;
}

/* ===============================
   🔐 HASH DE CONTRASEÑA
=============================== */
$hashContrasena = password_hash($contrasena, PASSWORD_BCRYPT);

/* ===============================
   CREAR USUARIO (SP)
=============================== */
try {
    $stmt = $pdo->prepare("
        EXEC aulavirtual.crearUsuarioAdmin
            ?, ?, ?, ?, ?, ?, ?, ?, ?
    ");

    $stmt->execute([
        $nombre,
        $email,
        $telefono,
        $hashContrasena,
        $rol,
        $estado,
        $grado,
        $seccion,
        $especialidad
    ]);

    $stmt->closeCursor();

    /* ===============================
       📌 AUDITORÍA: CREAR_USUARIO
    =============================== */
    registrarAuditoria(
        'CREAR_USUARIO',
        'Usuarios',
        'Se creó un nuevo usuario en el sistema'
    );

    $_SESSION['success_message'] = 'Usuario creado correctamente.';
    header("Location: admin_usuarios_list.php");
    exit;
} catch (PDOException $e) {

    registrarAuditoria(
        'CREAR_USUARIO',
        'Usuarios',
        'Error al crear un usuario',
        'Error'
    );

    $_SESSION['error_message'] = 'Error al crear el usuario.';
    header("Location: admin_usuario_new.php");
    exit;
}
