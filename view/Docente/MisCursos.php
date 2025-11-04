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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Santa Teresita - Mis Cursos</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">

    <style>
        body {
            background-color: #fafafa;
        }

        .fondo-cursos {
            min-height: 92.5vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 0;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            width: 100%;
            max-width: 1200px;
        }

        .card {
            width: 260px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out, box-shadow 0.3s ease;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 14px;
            color: #6c757d;
            min-height: 70px;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            margin: 5px;
        }

        .btn-tarea {
            background-color: #007bff;
            color: white;
        }

        .btn-tareas {
            background-color: #ffc107;
            color: #212529;
        }

        footer {
            background-color: #111;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>

<body>

    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="#"><em>Santa</em> Teresita</a>
        </div>
        <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
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
            <?php if (!empty($misCursos)): ?>
                <?php foreach ($misCursos as $curso): ?>
                    <div class="card">
                        <h5 class="card-title"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></h5>
                        <p class="card-text"><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= htmlspecialchars($curso['id']) ?>" class="btn btn-tarea" title="Añadir Tarea">
                            <i class="uil uil-plus"></i> 
                        </a>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($curso['id']) ?>" class="btn btn-tareas" title="Ver Tareas">
                            <i class="uil uil-list-ul"></i> 
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted text-center">No tienes cursos asignados.</p>
            <?php endif; ?>
        </div>
    </section>


    <footer>
        <div class="container">
            <p>© 2025 Santa Teresita | Template basado en TemplateMo</p>
        </div>
    </footer>

    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/isotope.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/lightbox.js"></script>
    <script src="../assets/js/custom.js"></script>

</body>
</html>
