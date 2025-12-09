<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idEncuesta = intval($_GET['idEncuesta'] ?? 0);
if (!$idEncuesta) die("Encuesta no válida");

$preguntas = EncuestaModel::obtenerPreguntas($idEncuesta);
$respuestas = EncuestaModel::obtenerRespuestas($idEncuesta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultados de la Encuesta</title>
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Resultados de la Encuesta</h2>

    <?php foreach ($preguntas as $p): ?>
        <h4><?= htmlspecialchars($p['Pregunta']) ?></h4>
        <ul>
        <?php foreach ($respuestas as $r): ?>
            <?php if ($r['Id_Pregunta'] == $p['Id_Pregunta']): ?>
                <li><?= htmlspecialchars($r['Respuesta']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
