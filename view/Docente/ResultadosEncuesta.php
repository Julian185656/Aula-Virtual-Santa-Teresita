<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$idEncuesta = intval($_GET['idEncuesta'] ?? 0);
if (!$idEncuesta) { die("Encuesta inválida"); }

$encuesta = EncuestaModel::obtenerEncuesta($idEncuesta);
$preguntas = EncuestaModel::obtenerPreguntas($idEncuesta);
$respuestas = EncuestaModel::obtenerTodasRespuestas($idEncuesta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultados de Encuesta</title>

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
    background-position: center top;
}


.container {
    max-width: 1100px;
    margin: 0 auto;
}


.titulo-card {
    background: rgba(255,255,255,0.05);
    padding: 25px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    margin-bottom: 30px;
}


.card-pregunta {
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border-left: 6px solid #0d6efd;
    padding: 0;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: 0.2s ease;
}

.card-pregunta:hover {
    transform: translateY(-2px);
}


.card-header {
    color: #fff;
    background: rgba(255,255,255,0.1);
    border: none;
    font-weight: 600;
    padding: 15px 20px;
    border-radius: 20px 20px 0 0;
}


.respuesta-item {
    background: rgba(255,255,255,0.05);
    padding: 12px 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: 0.2s;
}

.respuesta-item:hover {
    background: rgba(255,255,255,0.15);
}


.btn-back {
    border-radius: 15px;
    padding: 8px 18px;
    text-decoration: none;
}

</style>
</head>

<body>

<div class="container">


    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php"
       class="btn btn-outline-light btn-back mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

  
    <div class="titulo-card">
        <h2 class="mb-1 text-white">Resultados de la Encuesta</h2>
        <h4 class="text-primary mb-0"><strong><?= htmlspecialchars($encuesta['Titulo']) ?></strong></h4>
    </div>

    
    <?php if (empty($preguntas)): ?>
        <div class="alert alert-warning text-dark fw-bold">Esta encuesta no tiene preguntas.</div>
    <?php endif; ?>

  
    <?php foreach ($preguntas as $p): ?>
        <div class="card-pregunta">

            <div class="card-header">
                <i class="bi bi-chat-left-dots-fill"></i>
                <?= htmlspecialchars($p['Pregunta']) ?>
            </div>

            <div class="card-body">

                <?php 
                $tiene = false;
                foreach ($respuestas as $r):
                    if ($r['Id_Pregunta'] == $p['Id_Pregunta']):
                        $tiene = true;
                ?>
                    <div class="respuesta-item">
                        <strong class="text-info"><?= htmlspecialchars($r['NombreUsuario']); ?>:</strong><br>
                        <?= htmlspecialchars($r['Respuesta']); ?>
                    </div>
                <?php
                    endif;
                endforeach;

                if (!$tiene):
                ?>
                    <p class="text-white">Nadie ha respondido esta pregunta aún.</p>

                <?php endif; ?>

            </div>

        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
