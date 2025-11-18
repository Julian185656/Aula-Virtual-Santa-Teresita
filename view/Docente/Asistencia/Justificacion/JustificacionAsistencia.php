<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Justificación de Ausencias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
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
                <li class="breadcrumb-item active">Justificación de ausencias</li>
            </ol>
        </div>
    </nav>

    <section class="section" role="main">
        <div class="container mt-4">

            <h2 class="mb-3 text-primary">
                <i class="fa-solid fa-file-circle-check me-2"></i>
                Marcar ausencia justificada
            </h2>

            <!-- Alertas de mensaje -->
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?= htmlspecialchars($tipo ?? 'info') ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <!-- Filtros -->
            <form method="GET"
                action="JustificarAusenciaController.php"
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
                                <option value="<?= $idC ?>" <?= ($idC === ($cursoId ?? 0)) ? 'selected' : '' ?>>
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

                <div class="col-md-2 d-flex justify-content-start align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filtrar
                    </button>
                </div>
            </form>

            <!-- Tabla de ausencias pendientes -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="fa-solid fa-user-clock me-2"></i>
                        Ausencias pendientes de justificación
                    </h5>

                    <?php if (($cursoId ?? 0) <= 0): ?>
                        <p class="text-muted">
                            Selecciona un curso y, opcionalmente, un rango de fechas para ver las ausencias.
                        </p>
                    <?php elseif (empty($ausencias)): ?>
                        <p class="text-muted">
                            No hay ausencias pendientes de justificación con los filtros seleccionados.
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estudiante</th>
                                        <th>Correo</th>
                                        <th>Curso</th>
                                        <th>Comentario</th>
                                        <th style="width: 170px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ausencias as $a): ?>
                                        <?php
                                        $idCurso  = (int)$a['Id_Curso'];
                                        $idEst    = (int)$a['Id_Estudiante'];
                                        $fecha    = $a['Fecha'];
                                        $comentDB = $a['ComentarioJustificacion'] ?? '';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($fecha) ?></td>
                                            <td><?= htmlspecialchars($a['Estudiante']) ?></td>
                                            <td><?= htmlspecialchars($a['Correo']) ?></td>
                                            <td><?= htmlspecialchars($a['Curso']) ?></td>
                                            <td>
                                                <?php if (!empty($comentDB)): ?>
                                                    <span class="text-muted small">
                                                        <?= htmlspecialchars($comentDB) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted small">
                                                        (sin comentario)
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <!-- Formulario inline para justificar esta ausencia -->
                                                <form method="POST"
                                                    action="JustificarAusenciaController.php"
                                                    class="d-flex flex-column gap-1">

                                                    <!-- Datos clave de la ausencia -->
                                                    <input type="hidden" name="curso" value="<?= $idCurso ?>">
                                                    <input type="hidden" name="estudiante" value="<?= $idEst ?>">
                                                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">

                                                    <!-- Mantener filtros y página actual -->
                                                    <input type="hidden" name="desde" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                                                    <input type="hidden" name="hasta" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                                                    <input type="hidden" name="pagina" value="<?= (int)($pagina ?? 1) ?>">

                                                    <input type="text"
                                                        name="comentario"
                                                        class="form-control form-control-sm"
                                                        placeholder="Motivo de la justificación"
                                                        maxlength="255"
                                                        required>

                                                    <button type="submit"
                                                        class="btn btn-sm btn-success mt-1">
                                                        <i class="fa-solid fa-check me-1"></i>
                                                        Justificar
                                                    </button>
                                                </form>
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
                                            'curso' => $cursoId,
                                            'desde' => $fechaDesde,
                                            'hasta' => $fechaHasta,
                                            'pagina' => $i
                                        ];
                                        $urlPag = 'JustificarAusenciaController.php?' . http_build_query($paramsPag);
                                        ?>
                                        <li class="page-item <?= ($i === ($pagina ?? 1)) ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= $urlPag ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>

                        <p class="mt-2 text-muted small">
                            Mostrando <?= count($ausencias) ?> de <?= (int)($totalRegistros ?? 0) ?> ausencias pendientes.
                        </p>
                    <?php endif; ?>
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