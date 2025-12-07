<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Grad School HTML5 Template</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">

    <style>
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .main-banner {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(31, 39, 43, 0.7);
            z-index: 1;
        }

        .header-text,
        .caption,
        .caption h6,
        .caption h2 {
            position: relative;
            z-index: 2;
        }

        .role-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 50px;
            width: 100%;
            max-width: 1200px;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.13);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            color: #fff;
            flex: 0 1 260px;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
        }

        .role-card i {
            font-size: 45px;
            margin-bottom: 15px;
            color: #fff;
        }

        .role-card h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .role-card p {
            color: #e7e7e7;
            font-size: 15px;
        }

        .role-card a {
            display: inline-block;
            margin-top: 15px;
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            text-decoration: none;
            transition: 0.2s ease;
        }

        .role-card a:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .home-overlay {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 120px;
            z-index: 10;
        }

        .home-title {
            color: white;
            font-size: 2rem;
            margin-bottom: 25px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
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

            <?php if (isset($_SESSION['nombre'])): ?>
                <?php $rolActual = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null); ?>

                <?php if ($rolActual === 'Administrador'): ?>

                <?php elseif ($rolActual === 'Docente'): ?>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Docente/ListaDocente.php">Ver Perfiles Alumnos</a></li>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php">Mis Cursos</a></li>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php">Mi Agenda</a></li>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/HomeAsistencia.php">Asistencias</a></li>
                <?php elseif ($rolActual === 'Estudiante'): ?>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php">Mis Cursos</a></li>
                    <li><a href="https://wa.me/50622222222" style="color: #25D366;">Soporte Técnico</a></li>
                <?php endif; ?>

                <li><a href="#" style="color: #ffffff;"><?php echo htmlspecialchars($_SESSION['nombre']); ?></a></li>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color: red;">Cerrar sesión</a></li>

            <?php else: ?>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" style="color: #ff0000;">Iniciar sesión</a></li>

            <?php endif; ?>

        </ul>
    </nav>
</header>



<section class="section main-banner" id="top" data-section="section1">

    <video autoplay muted loop playsinline id="bg-video">
        <source src="../assets/images/course-video.mp4" type="video/mp4">
    </video>

    <div class="home-overlay">
        <h2 class="home-title">Herramientas</h2>

        <div class="role-cards">

            <?php if ($rolActual === 'Administrador'): ?>

                <div class="role-card">
                    <i class="fa fa-users"></i>
                    <h4>Gestión de Usuarios</h4>
                    <p>Agregar, editar y eliminar perfiles.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Admin/admin_usuarios_list.php">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-chart-bar"></i>
                    <h4>Reportes</h4>
                    <p>Estadísticas y reportes generales.</p>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalReporteria">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-book"></i>
                    <h4>Gestionar Cursos</h4>
                    <p>Crear, editar y administrar cursos.</p>
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalGestionCursos">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-bell"></i>
                    <h4>Notificaciones</h4>
                    <p>Enviar y consultar avisos.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-comments"></i>
                    <h4>Foros</h4>
                    <p>Gestionar y moderar foros.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php">Ir</a>
                </div>

            <?php elseif ($rolActual === 'Docente'): ?>
                <div class="role-card">
                    <i class="fa fa-graduation-cap"></i>
                    <h4>Mis Cursos</h4>
                    <p>Administrar contenido y tareas.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php">Ir</a>
                </div>
                <div class="role-card">
                    <i class="fa fa-calendar-alt"></i>
                    <h4>Mi Agenda</h4>
                    <p>Planificar actividades.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-clipboard-list"></i>
                    <h4>Asistencias</h4>
                    <p>Control de asistencia.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/HomeAsistencia.php">Ir</a>
                </div>

            <?php elseif ($rolActual === 'Estudiante'): ?>

                <div class="role-card">
                    <i class="fa fa-book-open"></i>
                    <h4>Mis Cursos</h4>
                    <p>Accede a contenido y material.</p>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php">Ir</a>
                </div>

                <div class="role-card">
                    <i class="fa fa-headset"></i>
                    <h4>Soporte Técnico</h4>
                    <p>Contacto directo.</p>
                    <a href="https://wa.me/50622222222">Ir</a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</section>





<!-- MODAL GESTIÓN DE CURSOS -->
<div class="modal fade" id="modalGestionCursos" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content"
             style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px);
             border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">

            <div class="modal-header">
                <h3 class="modal-title">Gestión de Cursos</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row justify-content-center g-4">

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa fa-plus-circle"></i>
                            <h4>Crear Curso</h4>
                            <p>Agregar nuevos cursos.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/CrearCursos.php">Ir</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa fa-edit"></i>
                            <h4>Eliminar</h4>
                            <p>Eliminar cursos existentes.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/EliminarCurso.php">Ir</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa fa-layer-group"></i>
                            <h4>Asignar Profesores</h4>
                            <p>Asignar docentes a un curso.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/AsignarDocentes.php">Ir</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa fa-user-graduate"></i>
                            <h4>Estudiantes</h4>
                            <p>Gestionar matrícula.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/MatricularEstudiantes.php">Ir</a>
                        </div>
                    </div>

                    <!-- NUEVO: Material del Curso -->
                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa-solid fa-folder-open"></i>
                            <h4>Material del Curso</h4>
                            <p>Subir, ver y eliminar archivos del curso.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/SeleccionarCursoMaterial.php">Ir</a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>






<!-- MODAL REPORTES -->
<div class="modal fade" id="modalReporteria" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content"
             style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px);
             border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">

            <div class="modal-header">
                <h3 class="modal-title">Módulo de Reportería</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-4 justify-content-center">

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <h4>Asistencia</h4>
                            <p>Descargar reportes de asistencia.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Asistencia/AsistenciaController.php">Ir</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa-solid fa-chart-line"></i>
                            <h4>Calificaciones</h4>
                            <p>Rendimiento general por curso.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Rendimiento/RendimientoController.php">Ir</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="role-card">
                            <i class="fa-solid fa-users"></i>
                            <h4>Participación</h4>
                            <p>Resumen de actividad del estudiante.</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Participacion/ParticipacionController.php">Ir</a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>




<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/isotope.min.js"></script>
<script src="../assets/js/owl-carousel.js"></script>
<script src="../assets/js/lightbox.js"></script>
<script src="../assets/js/tabs.js"></script>
<script src="../assets/js/video.js"></script>
<script src="../assets/js/slick-slider.js"></script>
<script src="../assets/js/custom.js"></script>

</body>
</html>
