<?php

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Administrador') {
    http_response_code(403);
    exit('Acceso denegado');
}
