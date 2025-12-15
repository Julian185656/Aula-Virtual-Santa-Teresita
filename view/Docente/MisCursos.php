<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
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

<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

h1 {
    text-align: center;
    margin-bottom: 40px;
    color: #fff;
    font-weight: 700;
}

.card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    color: #fff;
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

.card-body h5 {
    font-weight: 600;
    margin-bottom: 10px;
    color: #fff;
}

.card-body p {
    color: #e9e9e9;
    margin-bottom: 15px;
}

.icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    margin: 0 5px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    font-size: 18px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.icon-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

/* Botones */
.btn-tarea { background-color: #007BFF; }
.btn-tareas-asignadas { background-color: #FFC107; color: #212529; }
.btn-foro { background-color: #6f42c1; }

=======
.btn-encuesta { background-color: #ff5722; } 
.btn-rendimiento { background-color: #9c27b0; } /* NUEVO */
>>>>>>> d9b17f0d433ab52b9ae367912430f78b6ae44f6b
.btn-container {
    text-align: center;
    margin-top: 10px;
}

.btn-volver {
    display: inline-block;
    margin-bottom: 30px;
    padding: 10px 20px;
    background: #2a2b38;
    color: #fff;
    border-radius: 12px;
    text-decoration: none;
    transition: background 0.3s;
}
.btn-volver:hover {
    background: #1f202a;
}
</style>

</head>
<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <h1>Mis Cursos</h1>

    <div class="row">
        <?php if (!empty($misCursos)): ?>
            <?php foreach ($misCursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center">

                            <h5 class="card-title"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></h5>
                            <p class="card-text"><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>

                            <div class="btn-container">

                                <a class="icon-btn btn-tarea"
                                   href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= htmlspecialchars($curso['id']) ?>"
                                   title="Añadir Tarea">
                                    <i class="fa-solid fa-plus"></i>
                                </a>

                                <!-- Ver tareas -->
                                <a class="icon-btn btn-tareas-asignadas"
                                   href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($curso['id']) ?>"
                                   title="Ver Tareas">
                                    <i class="fa-solid fa-list"></i>
                                </a>

                                <!-- Foro -->
                                <a class="icon-btn btn-foro"
                                   href="/Aula-Virtual-Santa-Teresita/view/Docente/ForoCurso.php?idCurso=<?= urlencode($curso['id']) ?>&nombre=<?= urlencode($curso['nombre'] ?? '') ?>"
                                   title="Foro del Curso">
                                    <i class="fa-solid fa-comments"></i>
                                </a>

                                <a class="icon-btn btn-encuesta"
                                   href="/Aula-Virtual-Santa-Teresita/view/Docente/EncuestasCurso.php?idCurso=<?= htmlspecialchars($curso['id']) ?>"
                                   title="Encuestas del Curso">
                                    <i class="fa-solid fa-poll"></i>
                                </a>
                                
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p><strong>No tienes cursos asignados.</strong></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/jquery/jquery.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
