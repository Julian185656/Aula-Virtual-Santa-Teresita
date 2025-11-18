<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
    <!-- Si luego quieres estilos propios:
    <link rel="stylesheet" href="styles/Historial.css">
    -->
</head>

<body>
    <!-- Header -->
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

    <!-- Breadcrumb -->
    <nav class="bg-light border-bottom" style="margin-top: 85px;">
        <div class="container">
            <ol class="breadcrumb mb-0 py-2">
                <li class="breadcrumb-item"><a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">Inicio</a></li>
                <li class="breadcrumb-item"><a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/HomeAsistencia.php">Asistencias</a></li>
                <li class="breadcrumb-item active">Historial de asistencia</li>
            </ol>
        </div>
    </nav>

    <!-- Contenido principal -->
    <section class="section" role="main">
        <div class="container mt-4">

            <h2 class="mb-3 text-primary">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                Historial de asistencia individual
            </h2>

            <!-- Filtros: curso + rango fechas -->
            <form method="GET" action="HistorialAsistenciaController.php"
                class="row g-3 align-items-end bg-light p-3 rounded shadow-sm mb-4">

                <div class="col-md-4">
                    <label class="form-label">Curso</label>
                    <select name="curso" class="form-select" required>
                        <option value="">Seleccione un curso</option>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $c): ?>
                                <?php
                                $idC = (int)$c['Id_Curso'];
                                $nombreC = $c['Curso'] ?? ($c['Nombre'] ?? ('Curso ' . $idC));
                                ?>
                                <option value="<?= $idC ?>" <?= ($cursoId === $idC) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($nombreC) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="desde" class="form-control"
                        value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="hasta" class="form-control"
                        value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                </div>

                <!-- Mantener alumno seleccionado si ya estaba -->
                <input type="hidden" name="alumno" value="<?= (int)($alumnoId ?? 0) ?>">

                <div class="col-md-2 d-flex justify-content-start align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <div class="row">
                <!-- Columna izquierda: lista de alumnos -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-users me-2"></i> Estudiantes del curso
                            </h5>

                            <?php if ($cursoId <= 0): ?>
                                <p class="text-muted">
                                    Selecciona un curso para ver la lista de estudiantes.
                                </p>
                            <?php elseif (empty($alumnos)): ?>
                                <p class="text-muted">
                                    No hay estudiantes matriculados en este curso.
                                </p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Correo</th>
                                                <th>Historial</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($alumnos as $al): ?>
                                                <?php
                                                $idEst = (int)$al['Id_Estudiante'];
                                                $params = [
                                                    'curso'  => $cursoId,
                                                    'alumno' => $idEst,
                                                    'desde'  => $fechaDesde,
                                                    'hasta'  => $fechaHasta,
                                                    'pagina' => 1
                                                ];
                                                $urlHist = 'HistorialAsistenciaController.php?' . http_build_query($params);
                                                ?>
                                                <tr>
                                                    <td class="align-middle"><?= $idEst ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($al['Nombre']) ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($al['Email']) ?></td>
                                                    <td class="align-middle text-center">
                                                        <a href="<?= $urlHist ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-list-ul me-1"></i> Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: historial del alumno seleccionado -->
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-clipboard-list me-2"></i>
                                Historial del alumno
                            </h5>

                            <?php if ($cursoId <= 0 || $alumnoId <= 0): ?>
                                <p class="text-muted">
                                    Selecciona un curso y luego un estudiante para ver su historial de asistencia.
                                </p>
                            <?php else: ?>
                                <div class="mb-2">
                                    <strong>Alumno:</strong>
                                    <?= htmlspecialchars($alumnoNombre ?? ('ID ' . $alumnoId)) ?><br>
                                    <strong>Curso:</strong>
                                    <?= htmlspecialchars($cursoNombre ?? ('ID ' . $cursoId)) ?><br>
                                </div>

                                <div class="mb-2">
                                    <?php
                                    $pres = $resumen['presentes'] ?? 0;
                                    $aus  = $resumen['ausentes'] ?? 0;
                                    $tot  = $pres + $aus;
                                    ?>
                                    <span class="badge bg-success me-1">
                                        Presentes: <?= $pres ?>
                                    </span>
                                    <span class="badge bg-danger me-1">
                                        Ausentes: <?= $aus ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        Total en rango: <?= $tot ?>
                                    </span>
                                </div>

                                <?php if (empty($historial)): ?>
                                    <p class="text-muted mt-3">
                                        No hay registros de asistencia para este alumno en el rango seleccionado.
                                    </p>
                                <?php else: ?>
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($historial as $h): ?>
                                                    <?php
                                                    $esPresente = (int)$h['Presente'] === 1;
                                                    ?>
                                                    <tr>
                                                        <td class="align-middle">
                                                            <?= htmlspecialchars($h['Fecha']) ?>
                                                        </td>
                                                        <td class="align-middle">
                                                            <?php if ($esPresente): ?>
                                                                <span class="badge bg-success">Presente</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Ausente</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php if (($totalPaginas ?? 1) > 1): ?>
                                        <nav class="mt-3">
                                            <ul class="pagination justify-content-center">
                                                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                                    <?php
                                                    $paramsPag = [
                                                        'curso'  => $cursoId,
                                                        'alumno' => $alumnoId,
                                                        'desde'  => $fechaDesde,
                                                        'hasta'  => $fechaHasta,
                                                        'pagina' => $i
                                                    ];
                                                    $urlPag = 'HistorialAsistenciaController.php?' . http_build_query($paramsPag);
                                                    ?>
                                                    <li class="page-item <?= ($i === $pagina) ? 'active' : '' ?>">
                                                        <a class="page-link" href="<?= $urlPag ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

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