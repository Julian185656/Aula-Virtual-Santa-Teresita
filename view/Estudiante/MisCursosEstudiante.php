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
<title>Mis Cursos</title>
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
/* ===== Body ===== */
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #fff;
    padding: 40px 15px;
    background-color: #1e1f2e;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

/* ===== Título ===== */
h2 {
    font-weight: 700;
    margin-bottom: 40px;
    color: #fff;
    text-align: center;
}

/* ===== Cartas de curso ===== */
.card {
    border-radius: 20px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 250px;
    aspect-ratio: 1 / 1; /* Cuadrada */
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}
.card h5 {
    font-weight: 600;
    color: #ffffffff;
    margin-bottom: 10px;
}
.card p {
    font-size: 0.95rem;
    color: #ccc;
    margin-bottom: 15px;
}

/* ===== Botones dentro de cartas ===== */
.card .botones {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 5px;
}
.card a {
    display: inline-block;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.3s;
}

/* Colores de botones */
.btn-tareas { background-color: #0d6efd; color: #fff; }
.btn-tareas:hover { background-color: #0b5ed7; }

.btn-material { background-color: #17a2b8; color: #fff; }
.btn-material:hover { background-color: #138496; }

.btn-ranking { background-color: #6f42c1; color: #fff; }
.btn-ranking:hover { background-color: #5936a2; }

.btn-foro { background-color: #ffc107; color: #212529; }
.btn-foro:hover { background-color: #e0a800; }

/* Contenedor máximo */
.container { max-width: 1200px; }

/* Botón volver al home */
.btn-home {
    background-color: #343a40;
    color: #fff;
    border-radius: 12px;
    padding: 10px 25px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}
.btn-home:hover {
    background-color: #495057;
    transform: translateY(-2px);
}

/* Scrollbar opcional */
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
</style>
</head>
<body>

<h2>Mis Cursos</h2>

<div class="container">
    <div class="row g-4 justify-content-center">
        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 d-flex justify-content-center">
                    <div class="card">
                        <h5><?= htmlspecialchars($curso['Nombre']) ?></h5>
                        <p><?= htmlspecialchars($curso['Descripcion']) ?></p>

                       <div class="botones">
    <a class="btn-tareas" href="/Aula-Virtual-Santa-Teresita/view/Estudiante/TareasEstudiante.php?idCurso=<?= $curso['Id_Curso'] ?>" title="Tareas">
        <i class="fa-solid fa-list-check"></i>
    </a>
    <a class="btn-foro" href="/Aula-Virtual-Santa-Teresita/view/Estudiante/ForoCursoEstudiante.php?idCurso=<?= $curso['Id_Curso'] ?>" title="Foro">
        <i class="fa-solid fa-comments"></i>
    </a>
    <a class="btn-material" href="/Aula-Virtual-Santa-Teresita/view/Estudiante/Material.php?curso=<?= $curso['Id_Curso'] ?>" title="Material">
        <i class="fa-solid fa-book-open"></i>
    </a>
    <a class="btn-ranking" href="/Aula-Virtual-Santa-Teresita/controller/RankingController.php?idCurso=<?= $curso['Id_Curso'] ?>" title="Ranking">
        <i class="fa-solid fa-trophy"></i>
    </a>
</div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No estás matriculado en ningún curso.</p>
        <?php endif; ?>
    </div>

    <div class="text-center mt-5">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-home btn-lg"><i class="fa-solid fa-house"></i> Volver</a>
    </div>
</div>

</body>
</html>
