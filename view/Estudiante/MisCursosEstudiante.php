<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$estudianteId = (int)$_SESSION['id_usuario'];
$cursos = CursoModel::obtenerCursosEstudiante($estudianteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Santa Teresita - Mis Cursos</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/fontawesome.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/owl.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/lightbox.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="../Styles/MisCursos.css">
    
</head>
<body>

<header class="main-header clearfix" role="header">
    <div class="logo">
        <a href="#"><em>Santa</em> Teresita</a>
    </div>
    <a href="#menu" class="menu-link" style="text-align:center;"><i class="fa fa-bars"></i></a>
    <nav id="menu" class="main-nav" role="navigation">
        <ul class="main-menu">
            <li><a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">🏠 Inicio</a></li>
            <?php if (isset($_SESSION['nombre'])): ?>
                <li><a href="#" style="color:white;">👤 <?= htmlspecialchars($_SESSION['nombre']); ?></a></li>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
            <?php else: ?>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" style="color:red;">
                        <i class="fas fa-sign-in-alt"></i> Iniciar sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<section class="fondo-cursos">
    <div class="card-container">
        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="card text-center">
                    <h5 class="card-title"><?= htmlspecialchars($curso['Nombre'] ?? 'Sin nombre') ?></h5>
                    <p class="card-text"><?= htmlspecialchars($curso['Descripcion'] ?? 'Sin descripción') ?></p>
                    <a class="icon-btn btn-tareas" href="/Aula-Virtual-Santa-Teresita/view/Estudiante/TareasEstudiante.php?idCurso=<?= htmlspecialchars($curso['Id_Curso']) ?>" title="Ver Tareas">
                        <i class="fa-solid fa-list"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No estás matriculado en ningún curso.</p>
        <?php endif; ?>
    </div>


    <div class="text-center mt-4">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="volver">
            <i class="fa-solid fa-house"></i> Volver al Home
        </a>
    </div>
</section>

<footer>
    <div class="container">
        <p>© 2025 Santa Teresita | Template basado en TemplateMo</p>
    </div>
</footer>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/isotope.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/owl-carousel.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/lightbox.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/custom.js"></script>

</body>
</html>
