<?php
session_start();

require_once __DIR__ . '/../../controller/AuditoriaHelper.php';

/* Registrar auditoría antes de destruir la sesión */
registrarAuditoria(
    'LOGOUT',
    'Autenticación',
    'Cierre de sesión'
);

/* Limpiar sesión */
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/* Redirección */
header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?cerrado=1");
exit();
