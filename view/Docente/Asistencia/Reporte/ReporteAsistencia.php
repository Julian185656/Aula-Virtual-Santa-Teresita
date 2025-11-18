<?php
// Se asume session_start() ya se hizo en el controller
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia por Curso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
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
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/HomeAsistencia.php">↩️ Volver a Asistencias</a></li>
                <li>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" class="text-danger">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <nav class="bg-light border-bottom" style="margin-top: 85px;">
        <div class="container">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item"><a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/HomeAsistencia.php">Asistencias</a></li>
                <li class="breadcrumb-item active">Reporte de asistencia por curso</li>
            </ol>
        </div>
    </nav>

    <section class="section" role="main">
        <div class="container mt-4">

            <h2 class="mb-3 text-primary">
                <i class="fa-solid fa-file-export me-2"></i> Reporte de asistencia por curso
            </h2>

            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?= $tipo ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <!-- Filtros -->
            <form method="GET"
                action="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Reporte/ReporteAsistenciaController.php"
                class="row g-3 align-items-end bg-light p-3 rounded shadow-sm mb-4">

                <div class="col-md-4">
                    <label class="form-label">Curso</label>
                    <select name="curso" class="form-select" required>
                        <option value="">Seleccione un curso</option>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= (int)$c['Id_Curso'] ?>"
                                    <?= (!empty($cursoSeleccionado) && (int)$cursoSeleccionado === (int)$c['Id_Curso']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['Curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="desde" class="form-control"
                        value="<?= htmlspecialchars($desdeSel ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="hasta" class="form-control"
                        value="<?= htmlspecialchars($hastaSel ?? '') ?>">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <?php if (!empty($cursoSeleccionado) && !empty($alumnos)): ?>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-users me-2"></i>
                        Estudiantes del curso
                    </h5>

                    <!-- Exportar curso completo -->
                    <a class="btn btn-success btn-sm"
                        href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Reporte/ReporteAsistenciaController.php?<?= http_build_query([
                                                                                                                                'curso' => (int)$cursoSeleccionado,
                                                                                                                                'desde' => $desdeSel,
                                                                                                                                'hasta' => $hastaSel,
                                                                                                                                'export' => 1
                                                                                                                            ]) ?>">
                        <i class="fa-solid fa-file-csv me-1"></i> Exportar curso completo
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Estudiante</th>
                                <th>Email</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $al): ?>
                                <tr>
                                    <td><?= (int)$al['Id_Estudiante'] ?></td>
                                    <td><?= htmlspecialchars($al['Nombre']) ?></td>
                                    <td><?= htmlspecialchars($al['Email']) ?></td>
                                    <td class="text-center">
                                        <a class="btn btn-outline-primary btn-sm"
                                            href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Reporte/ReporteAsistenciaController.php?<?= http_build_query([
                                                                                                                                                    'curso'      => (int)$cursoSeleccionado,
                                                                                                                                                    'desde'      => $desdeSel,
                                                                                                                                                    'hasta'      => $hastaSel,
                                                                                                                                                    'estudiante' => (int)$al['Id_Estudiante'],
                                                                                                                                                    'export'     => 1
                                                                                                                                                ]) ?>">
                                            <i class="fa-solid fa-file-csv me-1"></i> Reporte
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif (!empty($cursoSeleccionado)): ?>
                <div class="alert alert-info">
                    No hay estudiantes matriculados en este curso.
                </div>
            <?php endif; ?>

        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p class="mb-0">© 2025 | Módulo de Asistencia - Santa Teresita</p>
        </div>
    </footer>

    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/custom.js"></script>
</body>

</html>