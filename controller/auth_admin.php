<?php
// solo Administrador
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Administrador') {
    http_response_code(403);
    exit('Acceso denegado');
}
