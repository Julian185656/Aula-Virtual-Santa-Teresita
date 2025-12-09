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
    ?>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            color: #c4c3ca;
            margin:0;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            padding: 40px 15px;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card-glass {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 18px;
            padding: 26px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            max-width: 500px;
            text-align:center;
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            font-size: 32px;
        }

        .btn-volver {
            margin-top: 20px;
            padding: 10px 18px;
            border-radius: 12px;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.22);
            color: white;
            text-decoration: none;
        }

        .btn-volver:hover {
            background: rgba(255,255,255,0.30);
        }
    </style>

    <div class="card-glass">
        <div class="icon-circle">✔</div>
        <h2>Encuesta ya respondida</h2>
        <p>Ya has completado esta encuesta.</p>
        <a href='/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php' class='btn-volver'>
            Volver a mis cursos
        </a>
    </div>
    <?php
    exit();
}


$preguntas = PreguntaModel::obtenerPreguntasPorEncuesta($idEncuesta);

if (!is_array($preguntas)) {
    $preguntas = [];
}

$success = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    foreach ($preguntas as $p) {
        $idPregunta = $p["Id_Pregunta"];
        $campo = "pregunta_$idPregunta";
        $respuesta = trim($_POST[$campo] ?? "");

        if ($respuesta !== "") {
            try {
                EncuestaModel::guardarRespuesta($idPregunta, $idUsuario, $respuesta);
            } catch (Throwable $e) {
                $errors[] = "No se pudo guardar la respuesta para la pregunta {$idPregunta}.";
            }
        }
    }

    if (empty($errors)) {
        $success = true;
    }
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
            --accent: #0d6efd;
        }

        html,body{
            height:100%;
        }

        body{
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            color: #c4c3ca;
            margin:0;
            background-color: var(--bg);
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            padding: 40px 15px;
        }

        .page {
            max-width: 760px;
            margin: 0 auto;
        }

        .back-btn {
            border-radius: 14px;
            padding: 8px 16px;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
            text-decoration: none;
            display: inline-flex;
            gap:8px;
            align-items:center;
        }
        .back-btn:hover { background: rgba(255,255,255,0.12); }

        .card-glass {
            margin-top:20px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 26px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
        }

        h2.title {
            margin: 0 0 12px 0;
            color: #fff;
            font-weight: 600;
            font-size: 22px;
            text-align: center;
        }

        .subtitulo {
            text-align: center;
            color: #dbe3ff;
            margin-bottom: 18px;
        }

        .form-label {
            color: #ffffffd1;
            font-weight: 500;
            margin-bottom:6px;
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            border-radius: 12px;
            padding: 10px 12px;
        }
        .form-control::placeholder { color: #e9e9f0a8; }
        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 6px rgba(13,110,253,0.06);
            border-color: var(--accent);
            background: rgba(255,255,255,0.14);
        }

        .btn-enviar {
            background: rgba(255,255,255,0.16);
            color: #fff;
            border-radius: 12px;
            padding: 10px 14px;
            border: 1px solid rgba(255,255,255,0.12);
            font-weight:600;
            transition: transform .15s, background .15s;
        }
        .btn-enviar:hover {
            background: rgba(255,255,255,0.28);
            transform: translateY(-2px);
        }

        .alert-success-custom {
            background: rgba(34,197,94,0.12);
            border: 1px solid rgba(34,197,94,0.22);
            color: #dfffe6;
        }

        .alert-error-custom {
            background: rgba(220,53,69,0.10);
            border: 1px solid rgba(220,53,69,0.18);
            color: #ffdfe0;
        }

        @media (max-width:576px){
            .card-glass { padding:18px; }
        }

        input.form-control,
        textarea.form-control {
            color: white !important;
            background-color: #2c2c2c !important;
            border: 1px solid #555;
        }

        input.form-control::placeholder,
        textarea.form-control::placeholder {
            color: #cccccc !important;
        }
    </style>
</head>
<body>

<div class="page">

    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="back-btn mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 1-.708 0L5.5 9.207V11.5a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H5.5l2.146 2.146a.5.5 0 0 1 0 .708z"/></svg>
        Volver
    </a>

    <div class="card-glass">

        <h2 class="title"><?= htmlspecialchars($encuesta["Titulo"]) ?></h2>
        <p class="subtitulo">Responde las preguntas para ayudar a mejorar el curso</p>

   <?php if ($success): ?>
    <script>
        window.location.href = "/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php";
    </script>
<?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-error-custom p-3 mb-3">
                <strong>Error:</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (count($preguntas) == 0): ?>
            <div class="alert alert-warning text-dark p-3">No hay preguntas en esta encuesta.</div>

        <?php else: ?>
            <form method="POST" novalidate>
                <?php foreach ($preguntas as $p): 
                    $idP = $p["Id_Pregunta"];
                    $campo = "pregunta_{$idP}";
                ?>
                    <div class="mb-3">
                        <label class="form-label"><?= htmlspecialchars($p["Pregunta"]) ?></label>
                        <input type="text" name="<?= $campo ?>" id="<?= $campo ?>"
                               class="form-control" placeholder="Escribe tu respuesta..." required>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn-enviar w-100">Enviar respuestas</button>
            </form>
        <?php endif; ?>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
