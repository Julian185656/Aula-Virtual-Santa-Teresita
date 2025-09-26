<?php
// DEBUG TEMPORAL: mostrar TODOS los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// evitar problemas si hay salida previa
ob_start();
?>

<?php
    header('location: view/Login/login.php');      
?>