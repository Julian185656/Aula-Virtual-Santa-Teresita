<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['usuario']) || strtolower($_SESSION['usuario']['rol']) !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$estudianteId = $_SESSION['usuario']['id_usuario'];
$cursos = CursoModel::obtenerCursosEstudiante($estudianteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Cursos</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    color: #ffffff;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

h1{
    text-align: center;
    margin-bottom: 50px;
    font-weight: 700;
}

.card{
    width: 280px;
    height: 280px;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    padding: 25px 20px;
    text-align: center;
    color: #fff;
    transition: 0.3s;
}

.card:hover{
    transform: translateY(-10px);
    box-shadow: 0 16px 45px rgba(0,0,0,0.4);
}

.card h5{
    font-weight: 600;
    margin-bottom: 6px;
}

.card p{
    font-size: 0.95rem;
    color: rgba(255,255,255,0.85);
    margin-bottom: 18px;
}

/* Contenedor de iconos */
.icon-group{
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

/* Botones circulares */
.icon-btn{
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    text-decoration: none;
    transition: 0.2s ease;
}

.icon-btn:hover{
    transform: translateY(-3px) scale(1.05);
    filter: brightness(1.15);
}

/* Colores de acciones */
.btn-tareas   { background:#0d6efd; }
.btn-foro     { background:#22c55e; }
.btn-material { background:#0dcaf0; }
.btn-ranking  { background:#facc15; color:#000; }
.btn-encuesta { background:#7c3aed; }

/* Botón volver */
.btn-volver{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 26px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    border-radius: 14px;
    text-decoration: none;
    transition: 0.2s;
}
.btn-volver:hover{
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1>Mis Cursos</h1>

<div class="container">
    <div class="row justify-content-center g-5">

        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 d-flex justify-content-center">
                    <div class="card">

                        <h5><?= htmlspecialchars($curso['Nombre']) ?></h5>
                        <p><?= htmlspecialchars($curso['Descripcion']) ?></p>

                        <div class="icon-group">

                            <a class="icon-btn btn-tareas"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/TareasEstudiante.php?idCurso=<?= $curso['Id_Curso'] ?>"
                               title="Tareas">
                                <i class="fa-solid fa-list-check"></i>
                            </a>

                            <a class="icon-btn btn-foro"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/ForoCursoEstudiante.php?idCurso=<?= $curso['Id_Curso'] ?>"
                               title="Foro">
                                <i class="fa-solid fa-comments"></i>
                            </a>

                            <a class="icon-btn btn-material"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/Material.php?curso=<?= $curso['Id_Curso'] ?>"
                               title="Material">
                                <i class="fa-solid fa-book-open"></i>
                            </a>

                            <a class="icon-btn btn-ranking"
                               href="/Aula-Virtual-Santa-Teresita/controller/RankingController.php?idCurso=<?= $curso['Id_Curso'] ?>"
                               title="Ranking">
                                <i class="fa-solid fa-trophy"></i>
                            </a>

                            <a class="icon-btn btn-encuesta"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/ResponderEncuesta.php?idCurso=<?= $curso['Id_Curso'] ?>"
                               title="Encuestas">
                                <i class="fa-solid fa-square-poll-horizontal"></i>
                            </a>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No estás matriculado en ningún curso.</p>
            </div>
        <?php endif; ?>

    </div>

    <div class="text-center mt-5">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
            <i class="fa-solid fa-house"></i> Volver
        </a>
    </div>
</div>

</body>
</html>
