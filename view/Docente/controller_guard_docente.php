<?php
if (!isset($_SESSION)) { session_start(); }

if (!isset($_SESSION['id_usuario'])) {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
  exit();
}

$rol = strtolower($_SESSION['rol'] ?? '');
if (!in_array($rol, ['docente', 'administrador'], true)) {
  http_response_code(403);
  exit('Acceso solo para docentes');
}
