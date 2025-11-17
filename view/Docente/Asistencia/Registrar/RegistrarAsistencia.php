<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
    <link rel="stylesheet" href="styles/Asistencia.css">
</head>

<body>
    <!-- Contenedor de toasts -->
    <div id="toast-container"></div>

    <!-- Flash message invisible (para el JS) -->
    <div id="server-flash"
        data-message="<?= isset($mensaje) ? htmlspecialchars($mensaje) : '' ?>"
        data-type="<?= isset($tipo) ? htmlspecialchars($tipo) : '' ?>"
        style="display:none;"></div>

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
                <li class="breadcrumb-item active">Registrar asistencia</li>
            </ol>
        </div>
    </nav>

    <!-- Contenido -->
    <section class="section" role="main">
        <div class="container mt-4">

            <h2 class="mb-3 text-primary">
                <i class="fa-regular fa-clipboard me-2"></i> Registrar asistencia
                <?php if (!empty($fecha)): ?>
                    <span class="badge text-bg-light border align-middle badge-hoy">
                        <i class="fa-regular fa-calendar-days me-1"></i><?= htmlspecialchars($fecha) ?>
                    </span>
                <?php endif; ?>
            </h2>

            <!-- Filtros -->
            <form method="GET" action="RegistrarAsistenciaController.php"
                class="row g-3 align-items-end bg-light p-3 rounded shadow-sm">
                <div class="col-md-5">
                    <label class="form-label">Curso</label>
                    <select name="curso" class="form-select" required>
                        <option value="">Seleccione un curso</option>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $c): ?>
                                <option value="<?= (int)$c['Id_Curso'] ?>"
                                    <?= (!empty($cursoId) && (int)$cursoId === (int)$c['Id_Curso']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['Curso']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control"
                        value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">
                </div>

                <div class="col-md-4 d-flex form-inline-gap">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter me-1"></i> Cargar lista
                    </button>
                    <a href="RegistrarAsistenciaController.php" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate me-1"></i> Restablecer
                    </a>
                </div>
            </form>

            <!-- Tabla -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-users me-2"></i>
                            Lista de estudiantes
                            <?php if (!empty($cursoId) && !empty($alumnos)): ?>
                                <small class="text-muted ms-2">
                                    (página <?= (int)($pagina ?? 1) ?> de <?= (int)($totalPaginas ?? 1) ?>)
                                </small>
                            <?php endif; ?>
                        </h5>

                        <div class="d-flex gap-2">
                            <button type="button" id="btnMarcarTodos" class="btn btn-sm btn-outline-success">
                                <i class="fa-solid fa-user-check me-1"></i> Marcar todos presentes
                            </button>
                            <button type="button" id="btnMarcarNadie" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-user-xmark me-1"></i> Marcar todos ausentes
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form id="formAsistencia" method="POST" action="RegistrarAsistenciaController.php">
                            <input type="hidden" name="curso" value="<?= (int)($cursoId ?? 0) ?>">
                            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="min-w-160">ID</th>
                                        <th>Estudiante</th>
                                        <th>Correo</th>
                                        <th>Curso</th>
                                        <th class="estado-col text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cursoId)): ?>
                                        <?php if (!empty($alumnos)): ?>
                                            <?php foreach ($alumnos as $fila):
                                                $idEst = (int)$fila['Id_Estudiante'];
                                                // Si ya hay asistencia guardada ese día, usarla; si no, por defecto presente (1)
                                                $valorActual = isset($asistenciaMap[$idEst]) ? (int)$asistenciaMap[$idEst] : 1;
                                                $isPresente  = ($valorActual === 1);
                                            ?>
                                                <tr>
                                                    <td class="align-middle">
                                                        <?= $idEst ?>
                                                        <input type="hidden" name="estudiante_id[]" value="<?= $idEst ?>">
                                                    </td>
                                                    <td class="align-middle"><?= htmlspecialchars($fila['Nombre']) ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($fila['Email']) ?></td>
                                                    <td class="align-middle"><?= htmlspecialchars($fila['Curso']) ?></td>
                                                    <td class="text-center align-middle estado-col">
                                                        <div class="estado-toggle" data-id="<?= $idEst ?>">
                                                            <button type="button"
                                                                class="btn-presente toggle-btn <?= $isPresente ? 'active' : '' ?>">
                                                                Presente
                                                            </button>
                                                            <button type="button"
                                                                class="btn-ausente toggle-btn <?= $isPresente ? '' : 'active' ?>">
                                                                Ausente
                                                            </button>

                                                            <!-- Valor real que se enviará -->
                                                            <input type="hidden"
                                                                name="estado[<?= $idEst ?>]"
                                                                id="estado-<?= $idEst ?>"
                                                                value="<?= $isPresente ? 1 : 0 ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    No hay estudiantes matriculados para este curso.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Selecciona un curso y fecha para cargar la lista.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <div class="sticky-actions d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    <?php if (!empty($alumnos)): ?>
                                        Mostrando <?= count($alumnos) ?> de <?= (int)($totalRegistros ?? 0) ?> estudiantes.
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btnGuardarAjax" class="btn btn-primary">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Guardar (AJAX)
                                    </button>
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Guardar (POST)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($cursoId) && !empty($alumnos) && ($totalPaginas ?? 1) > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php
                                $pagActual = (int)($pagina ?? 1);
                                for ($i = 1; $i <= (int)$totalPaginas; $i++):
                                    $active = ($i === $pagActual) ? 'active' : '';
                                    $q = http_build_query([
                                        'curso'  => (int)$cursoId,
                                        'fecha'  => $fecha,
                                        'pagina' => $i
                                    ]);
                                ?>
                                    <li class="page-item <?= $active ?>">
                                        <a class="page-link" href="RegistrarAsistenciaController.php?<?= $q ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
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

    <!-- JS base -->
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/js/custom.js"></script>

    <!-- Toasts locales -->
    <script src="scripts/Toasts.js"></script>

    <!-- Script del módulo -->
    <script src="scripts/Asistencia.js?v=2"></script>
</body>

</html>