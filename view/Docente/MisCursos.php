<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$docenteId = $_SESSION['id_usuario'];
$misCursos = CursoModel::obtenerCursosDocente($docenteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Cursos</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;

    margin: 0;      
    padding: 40px 15px;  
    min-height: 100vh;    

    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');

    background-repeat: no-repeat;
    background-size: 200%; 
    background-position: center;
    overflow-x: hidden;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
}
h1 {
    text-align: center;
    margin-bottom: 30px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

/* TARJETAS DE CURSOS */
.card-curso {
    background: rgba(255, 255, 255, 0.08);
    padding: 20px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.15);
    transition: 0.3s;
}
.card-curso:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.15);
}

/* Título del curso */
.card-curso h5 {
    font-weight: 600;
    color: #fff;
}

/* Botones redondos */
.icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    margin: 5px;
    border-radius: 50%;
    font-size: 20px;
    color: white;
    border: 1px solid rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.1);
    transition: 0.2s ease;
}
.icon-btn:hover {
    background: rgba(255,255,255,0.35);
}

/* Colores por acción */
.btn-tarea { background-color: #007BFF44; }
.btn-tareas-asignadas { background-color: #FFC10755; color: #fff !important; }
.btn-foro { background-color: #6f42c177; }

/* Botón volver */
.btn-volver {
    border-radius: 15px;
    padding: 8px 18px;
    text-decoration:none;
    border: 1px solid rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.btn-volver:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>
<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h1>Mis Cursos</h1>

    <div class="row">
        <?php if (!empty($misCursos)): ?>
            <?php foreach ($misCursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card-curso text-center">

                        <h5><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></h5>
                        <p><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>

                        <div class="mt-3">

                            <!-- Añadir tarea -->
                            <a class="icon-btn btn-tarea"
                               href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= $curso['id'] ?>"
                               title="Añadir Tarea">
                                <i class="bi bi-plus-circle-fill"></i>
                            </a>

                            <!-- Ver tareas -->
                            <a class="icon-btn btn-tareas-asignadas"
                               href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= $curso['id'] ?>"
                               title="Ver Tareas">
                                <i class="bi bi-list-task"></i>
                            </a>

                            <!-- Foro -->
                            <a class="icon-btn btn-foro"
                               href="/Aula-Virtual-Santa-Teresita/view/Docente/ForoCurso.php?idCurso=<?= urlencode($curso['id']) ?>&nombre=<?= urlencode($curso['nombre']) ?>"
                               title="Foro del Curso">
                                <i class="bi bi-chat-dots-fill"></i>
                            </a>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center"><strong>No tienes cursos asignados.</strong></div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
