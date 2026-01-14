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

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    color: #ffffff;
    padding: 40px 20px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
}

h1 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 40px;
}

.btn-volver {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
    padding: 10px 18px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.1);
    color: #ffffff;
    text-decoration: none;
    transition: .2s;
}

.btn-volver:hover {
    background: rgba(255,255,255,0.25);
    color: #ffffff;
}

.cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    justify-content: center;
}

.course-card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    padding: 35px;
    width: 750px;
    min-height: 250px;   /* REDUCIDO PARA QUITAR ESPACIO VACÍO */
    
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    gap: 30px; /* SEPARACIÓN REAL ENTRE TÍTULO Y BOTONES */
}

.course-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 0px;
}

.course-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

.action-btn {
    flex: 1;
    text-align: center;
    padding: 14px;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.12);
    color: #ffffff;
    text-decoration: none;
    transition: .2s;
    font-size: 15px;
}

.action-btn:hover {
    background: rgba(255,255,255,0.25);
    color: #ffffff;
}

.no-cursos {
    text-align: center;
    opacity: 0.85;
}
</style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa fa-arrow-left"></i> Volver
    </a>

    <h1>Mis Cursos</h1>

    <div class="cards-container">

        <?php if (!empty($misCursos)): ?>
            <?php foreach ($misCursos as $curso): ?>
                <div class="course-card">

                    <div class="course-title">
                        <?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?>
                    </div>

                    <div class="course-actions">
                        <a class="action-btn"
                           href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= htmlspecialchars($curso['id']) ?>">
                            <i class="fa fa-plus"></i> Tarea
                        </a>

                        <a class="action-btn"
                           href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($curso['id']) ?>">
                            <i class="fa fa-list"></i> Tareas
                        </a>

                        <a class="action-btn"
                           href="/Aula-Virtual-Santa-Teresita/view/Docente/ForoCurso.php?idCurso=<?= urlencode($curso['id']) ?>">
                            <i class="fa fa-comments"></i> Foro
                        </a>

                        <a class="action-btn"
                           href="/Aula-Virtual-Santa-Teresita/view/Docente/EncuestasCurso.php?idCurso=<?= htmlspecialchars($curso['id']) ?>">
                            <i class="fa fa-poll"></i> Encuestas
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-cursos">
                <strong>No tienes cursos asignados.</strong>
            </div>
        <?php endif; ?>

    </div>

</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/jquery/jquery.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
