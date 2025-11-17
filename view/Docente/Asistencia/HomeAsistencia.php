<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Santa Teresita - Módulo de Asistencias</title>


    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../assets/css/owl.css">
    <link rel="stylesheet" href="../../assets/css/lightbox.css">
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
                    <?php $rolActual = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null); ?>

                    <li><a href="#" class="text-white">👤 <?= htmlspecialchars($_SESSION['nombre']); ?></a></li>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" class="text-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" class="text-danger">
                            <i class="fas fa-sign-in-alt"></i> Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="section main-banner" id="top" data-section="section1">
        <video autoplay muted loop id="bg-video">
            <source src="../../assets/images/course-video" type="video/mp4" />
        </video>
        <div class="video-overlay header-text">
            <div class="caption text-center">
                <h6>Institución Educativa Santa Teresita</h6>
                <h2><em>Sistema de</em> Asistencias</h2>
            </div>
        </div>
    </section>

    <nav class="bg-light py-2 border-bottom">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">Inicio</a></li>
                <li class="breadcrumb-item active">Sistema de Asistencias</li>
            </ol>
        </div>
    </nav>

    <section class="py-5 text-center bg-light" id="reportes">
        <div class="container">
            <h2 class="fw-bold mb-3 text-primary">📊 Módulo de Asistencia</h2>
            <p class="text-muted mb-5">Selecciona el tipo de reporte que deseas visualizar o descargar:</p>

            <div class="row g-4 justify-content-center">
                <!-- Registro de Asistencia -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">📋 Registro de Asistencias</h5>
                            <p class="card-text flex-grow-1 text-muted">
                                Registra la asistencia de los estudiantes.
                            </p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Registrar/RegistrarAsistenciaController.php"
                                class="btn btn-primary mt-auto">Ver Asistencia</a>
                        </div>
                    </div>
                </div>

                <!-- Historial de asistencia -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-success">🧾 Historial de asistencia registrada</h5>
                            <p class="card-text flex-grow-1 text-muted">
                                Consulta el historial de asistencia individual de los estudiantes.
                            </p>
                            <a href=""
                                class="btn btn-success mt-auto">Ver Historial</a>
                        </div>
                    </div>
                </div>

                <!-- Justificacion de Ausencias -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-warning">📚 Justificacion de Ausencias regitradas</h5>
                            <p class="card-text flex-grow-1 text-muted">
                                Registra como justificada la ausencias de los estudiantes, con justificacion Valida
                            </p>
                            <a href=""
                                class="btn btn-warning text-white mt-auto">Justificar ausencia</a>
                        </div>
                    </div>
                </div>

                <!-- Reporte de Asistencia del curso -->
                <div class="col-md-6 col-lg-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-danger">👥 Reporte de Asistencia por curso</h5>
                            <p class="card-text flex-grow-1 text-muted">
                                Visualiza un reporte de asistencia de los estudiantes por curso.
                            </p>
                            <a href=""
                                class="btn btn-danger mt-auto">Ver reporte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">
                &copy; 2025 Santa Teresita
            </p>
        </div>
    </footer>

    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/isotope.min.js"></script>
    <script src="../../assets/js/owl-carousel.js"></script>
    <script src="../../assets/js/lightbox.js"></script>
    <script src="../../assets/js/tabs.js"></script>
    <script src="../../assets/js/video.js"></script>
    <script src="../../assets/js/slick-slider.js"></script>
    <script src="../../assets/js/custom.js"></script>

</body>

</html>