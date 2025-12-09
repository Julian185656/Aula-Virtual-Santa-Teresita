<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";


if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}


$idEncuesta = intval($_GET['idEncuesta'] ?? 0);
if (!$idEncuesta) {
    die("Encuesta no válida");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pregunta'])) {
    $pregunta = trim($_POST['pregunta']);
    if ($pregunta !== '') {
        EncuestaModel::crearPregunta($idEncuesta, $pregunta);
        header("Location: PreguntasEncuesta.php?idEncuesta=$idEncuesta");
        exit();
    }
}


$preguntas = EncuestaModel::obtenerPreguntas($idEncuesta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Preguntas de la Encuesta</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    overflow-x: hidden;
}

.container {
    max-width: 900px;
}


.card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    margin-bottom: 30px;
}

.card-header {
    background: rgba(255,255,255,0.1);
    border-bottom: none;
    color: #fff;
    font-weight: 600;
    border-radius: 20px 20px 0 0;
}

.btn-custom {
    border-radius: 15px;
    padding: 8px 18px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: none;
}
.btn-custom:hover {
    background: rgba(255,255,255,0.3);
}

.input-custom {
    background: rgba(255,255,255,0.15);
    border-radius: 15px;
    border: none;
    color: #fff;
    padding: 12px;
}
.input-custom::placeholder {
    color: #ddd;
}

.list-group-item {
    background: rgba(255,255,255,0.05);
    color: white;
    border: 1px solid rgba(255,255,255,0.15);
}
.list-group-item:hover {
    background: rgba(255,255,255,0.15);
}
</style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php"
       class="btn btn-custom mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver a Mis Cursos
    </a>

    <h1 class="text-center text-white mb-4">Preguntas de la Encuesta</h1>

  
    <div class="card">
        <div class="card-header">
            <i class="bi bi-plus-circle"></i> Agregar nueva pregunta
        </div>

        <div class="card-body">
            <form method="post" class="d-flex gap-3 flex-wrap">
                <input type="text" name="pregunta" class="form-control input-custom"
                       placeholder="Escribe la pregunta" required>

                <button type="submit" class="btn btn-custom">
                    <i class="bi bi-plus-circle-fill"></i> Agregar
                </button>
            </form>
        </div>
    </div>

 
    <div class="card">
        <div class="card-header">
            <i class="bi bi-list-task"></i> Preguntas agregadas
        </div>
        <div class="card-body">

            <?php if (!empty($preguntas)): ?>
                <ul class="list-group">

                    <?php foreach ($preguntas as $p): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($p['Pregunta']) ?>
                        </li>
                    <?php endforeach; ?>

                </ul>
            <?php else: ?>

                <p class="text-center mt-3" style="color:#fff;">
                    No hay preguntas agregadas todavía.
                </p>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
