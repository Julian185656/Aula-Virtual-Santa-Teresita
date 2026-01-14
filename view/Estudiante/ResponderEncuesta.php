<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];
$idCurso = $_GET['idCurso'] ?? null;

if (!$idCurso) {
    die("<h3>Error: No se recibió el curso.</h3>");
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/PreguntaModel.php";

$encuesta = EncuestaModel::obtenerEncuestaPorCurso($idCurso);
if (!$encuesta) {
    die("<h3>No hay encuesta disponible para este curso.</h3>");
}

$idEncuesta = $encuesta["Id_Encuesta"];
$yaRespondio = EncuestaModel::usuarioYaRespondio($idEncuesta, $idUsuario);

if ($yaRespondio) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php");
    exit();
}

$preguntas = PreguntaModel::obtenerPreguntasPorEncuesta($idEncuesta);
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($preguntas as $p) {
        $idPregunta = $p["Id_Pregunta"];
        $campo = "pregunta_$idPregunta";
        $respuesta = trim($_POST[$campo] ?? "");

        if ($respuesta !== "") {
            EncuestaModel::guardarRespuesta($idPregunta, $idUsuario, $respuesta);
        }
    }
    header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Responder Encuesta</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<style>
/* ===== TU CSS ORIGINAL (NO TOCADO) ===== */
:root{
    --bg:#2a2b38;
    --card-bg: rgba(255,255,255,0.06);
    --card-border: rgba(255,255,255,0.18);
    --input-bg: rgba(255,255,255,0.10);
    --accent: #0d6efd;
}

body{
    font-family:'Poppins',sans-serif;
    font-weight:300;
    font-size:15px;
    color:#c4c3ca;
    margin:0;
    background-color:var(--bg);
    background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    padding:40px 15px;
}

.page{max-width:760px;margin:auto;}

.card-glass{
    background:var(--card-bg);
    border:1px solid var(--card-border);
    border-radius:18px;
    padding:26px;
    backdrop-filter:blur(10px);
    box-shadow:0 10px 30px rgba(0,0,0,0.35);
}

h2.title{
    color:#fff;
    text-align:center;
    font-weight:600;
}

.subtitulo{text-align:center;color:#dbe3ff;margin-bottom:18px;}

.form-label{color:#ffffffd1;font-weight:500;}
.form-control{
    background:var(--input-bg);
    border:1px solid rgba(255,255,255,0.12);
    color:#fff;
    border-radius:12px;
}

.btn-enviar{
    background:rgba(255,255,255,0.16);
    color:#fff;
    border-radius:12px;
    padding:10px;
    border:1px solid rgba(255,255,255,0.12);
    font-weight:600;
}

/* ===== SOLO CONFIRMACIÓN ===== */
.confirm-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.55);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}
.confirm-box{
    background:rgba(30,30,45,.95);
    border-radius:18px;
    padding:24px;
    text-align:center;
    color:#fff;
    max-width:420px;
    width:100%;
}
.confirm-actions{
    display:flex;
    gap:10px;
    margin-top:20px;
}
.btn-cancelar{
    flex:1;
    background:#6c757d;
    border:none;
    border-radius:10px;
    padding:10px;
    color:#fff;
}
.btn-confirmar{
    flex:1;
    background:#28a745;
    border:none;
    border-radius:10px;
    padding:10px;
    color:#fff;
}
</style>
</head>

<body>

<div class="page">
<div class="card-glass">

<h2 class="title"><?= htmlspecialchars($encuesta["Titulo"]) ?></h2>
<p class="subtitulo">Responde las preguntas para ayudar a mejorar el curso</p>

<form method="POST" id="formEncuesta">
<?php foreach ($preguntas as $p): ?>
<div class="mb-3">
    <label class="form-label"><?= htmlspecialchars($p["Pregunta"]) ?></label>
    <input type="text"
           name="pregunta_<?= $p["Id_Pregunta"] ?>"
           class="form-control"
           required>
</div>
<?php endforeach; ?>

<!-- SOLO CAMBIO: type=button -->
<button type="button" class="btn-enviar w-100" onclick="abrirConfirmacion()">
    Enviar respuestas
</button>
</form>

</div>
</div>

<!-- CONFIRMACIÓN -->
<div id="confirmOverlay" class="confirm-overlay">
<div class="confirm-box">
    <h5>Confirmar envío</h5>
    <p>¿Deseas enviar la encuesta?</p>

    <div class="confirm-actions">
        <button class="btn-cancelar" onclick="cerrarConfirmacion()">Cancelar</button>
        <button class="btn-confirmar" onclick="confirmarEnvio()">Enviar</button>
    </div>
</div>
</div>

<script>
function abrirConfirmacion(){
    document.getElementById('confirmOverlay').style.display='flex';
}
function cerrarConfirmacion(){
    document.getElementById('confirmOverlay').style.display='none';
}
function confirmarEnvio(){
    document.getElementById('formEncuesta').submit();
}
</script>

</body>
</html>
