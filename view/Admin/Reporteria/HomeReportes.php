<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Santa Teresita - Módulo de Reportería</title>

    <!-- Bootstrap y FontAwesome -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f5f5f5;
            font-family: 'Montserrat', sans-serif;
            padding: 50px 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #007bff;
            font-weight: 700;
        }

        .report-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .report-card h5 {
            font-weight: 600;
        }

        .report-card p {
            color: #6c757d;
        }

        .btn-primary, .btn-success, .btn-warning, .btn-danger {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📊 Módulo de Reportería</h2>

        <div class="row g-4 justify-content-center">
            <!-- Reporte Asistencia -->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">📋 Reporte de Asistencia</h5>
                        <p class="card-text flex-grow-1">Consulta y descarga la asistencia de los estudiantes por fecha y curso.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Asistencia/AsistenciaController.php"
                            class="btn btn-primary mt-auto">Ver reporte</a>
                    </div>
                </div>
            </div>

            <!-- Reporte Calificaciones -->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-success">🧾 Reporte de Calificaciones</h5>
                        <p class="card-text flex-grow-1">Consulta las notas registradas de los estudiantes en los distintos cursos.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Rendimiento/RendimientoController.php"
                            class="btn btn-success mt-auto">Ver reporte</a>
                    </div>
                </div>
            </div>

            <!-- Reporte Cursos -->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-warning">📚 Reporte de Cursos</h5>
                        <p class="card-text flex-grow-1">Visualiza información general de los cursos activos y docentes asignados.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Tareas/ReporteCursos.php"
                            class="btn btn-warning text-white mt-auto">Ver reporte</a>
                    </div>
                </div>
            </div>

            <!-- Reporte Participación -->
            <div class="col-md-6 col-lg-3">
                <div class="card report-card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-danger">👥 Reporte de Participación</h5>
                        <p class="card-text flex-grow-1">Analiza la participación de los estudiantes en foros, tareas y actividades.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Participacion/ParticipacionController.php"
                            class="btn btn-danger mt-auto">Ver reporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
