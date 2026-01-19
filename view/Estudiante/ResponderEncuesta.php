<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];
$idCurso = $_GET['idCurso'] ?? null;

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/PreguntaModel.php";

/* ===== FLAGS ===== */
$sinEncuesta = false;
$mostrarMensajeRespondido = false;
$yaRespondioAntes = false;

if (!$idCurso) {
    $sinEncuesta = true;
} else {
    $encuesta = EncuestaModel::obtenerEncuestaPorCurso($idCurso);

    if (!$encuesta) {
        $sinEncuesta = true;
    } else {
        $idEncuesta = $encuesta["Id_Encuesta"];
        $yaRespondioAntes = EncuestaModel::usuarioYaRespondio($idEncuesta, $idUsuario);
        $preguntas = PreguntaModel::obtenerPreguntasPorEncuesta($idEncuesta);
    }
}

/* ===== GUARDAR RESPUESTAS ===== */
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$sinEncuesta && !$yaRespondioAntes) {
    foreach ($preguntas as $p) {
        $campo = "pregunta_" . $p["Id_Pregunta"];
        $respuesta = trim($_POST[$campo] ?? "");

        if ($respuesta !== "") {
            EncuestaModel::guardarRespuesta(
                $p["Id_Pregunta"],
                $idUsuario,
                $respuesta
            );
        }
    }
    $mostrarMensajeRespondido = true;
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
:root{
    --bg:#2a2b38;
    --card-bg: rgba(255,255,255,0.06);
    --card-border: rgba(255,255,255,0.18);
    --input-bg: rgba(255,255,255,0.10);
}
body{
    font-family:'Poppins',sans-serif;
    color:#c4c3ca;
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
h2{color:#fff;text-align:center;font-weight:600;}
p{text-align:center;}
.form-control{
    background:var(--input-bg);
    border-radius:12px;
    color:#fff;
}
.btn-main{
    background:rgba(255,255,255,0.18);
    border-radius:12px;
    padding:10px;
    color:#fff;
    border:none;
    text-decoration:none;
}
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
}
</style>
</head>

<body>
<div class="page">
<div class="card-glass">

<?php if ($sinEncuesta): ?>

    <h2>No hay encuesta disponible</h2>
    <p class="mt-3">Este curso no tiene una encuesta activa.</p>
    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php"
       class="btn-main w-100 text-center d-block mt-4">
        Volver a mis cursos
    </a>

<?php elseif ($yaRespondioAntes || $mostrarMensajeRespondido): ?>

    <h2>¡Encuesta respondida!</h2>
    <p class="mt-3">
        Ya has respondido esta encuesta.<br>
        ¡Gracias por tu participación!
    </p>
    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php"
       class="btn-main w-100 text-center d-block mt-4">
        Volver a mis cursos
    </a>

<?php else: ?>

    <h2><?= htmlspecialchars($encuesta["Titulo"]) ?></h2>
    <p>Responde las preguntas para mejorar el curso</p>

    <form method="POST" id="formEncuesta">
        <?php foreach ($preguntas as $p): ?>
            <div class="mb-3">
                <label class="form-label text-white">
                    <?= htmlspecialchars($p["Pregunta"]) ?>
                </label>
                <input type="text"
                       name="pregunta_<?= $p["Id_Pregunta"] ?>"
                       class="form-control"
                       required>
            </div>
        <?php endforeach; ?>

        <button type="button" class="btn-main w-100 mt-3" onclick="abrirConfirmacion()">
            Enviar respuestas
        </button>
    </form>

<?php endif; ?>

</div>
</div>

<!-- CONFIRMACIÓN -->
<div id="confirmOverlay" class="confirm-overlay">
<div class="confirm-box">
    <h5>Confirmar envío</h5>
    <p>¿Deseas enviar la encuesta?</p>
    <button class="btn btn-secondary w-100 mb-2" onclick="cerrarConfirmacion()">Cancelar</button>
    <button class="btn btn-success w-100" onclick="confirmarEnvio()">Enviar</button>
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
