<?php

session_start();

/* 🔒 VALIDACIÓN ORIGINAL (SE CONSERVA TAL CUAL) */
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Administrador') {
    http_response_code(403);
    exit('Acceso denegado');
}

/* 🔐 VALIDACIÓN ADICIONAL CONTRA BASE DE DATOS */
require_once __DIR__ . '/../model/db.php';

$pdo = (new CN_BD())->conectar();

$stmt = $pdo->prepare("
    SELECT Estado
    FROM aulavirtual.usuario
    WHERE Id_Usuario = ?
");
$stmt->execute([$_SESSION['usuario']['id_usuario']]);
$usuario = $stmt->fetch();

/* 🚪 SI NO EXISTE O NO ESTÁ ACTIVO → CERRAR SESIÓN */
if (!$usuario || $usuario['Estado'] !== 'Activo') {
    session_unset();
    session_destroy();
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit;
}
