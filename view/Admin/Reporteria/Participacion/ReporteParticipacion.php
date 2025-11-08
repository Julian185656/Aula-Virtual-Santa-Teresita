<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Participación</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
    <link rel="stylesheet" href="styles/reporte-participacion.css">
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
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/HomeReportes.php">📊 Volver a Reportes</a></li>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Cursos/CrearCurso.php">📚 Cursos</a></li>
                <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="section" style="padding-top: 120px;">
        <div class="container mt-4">
            <h2 class="text-center mb-4 text-primary">
                <i class="fas fa-chart-line me-2"></i> Reporte de Participación
            </h2>

            <div id="toast-container"></div>

            <form method="GET" action="ParticipacionController.php"
                class="d-flex justify-content-center align-items-center flex-wrap mb-4 p-3 rounded bg-light shadow-sm gap-fix">
                <select name="periodo" class="form-select w-auto ">
                    <option value="">Todos los periodos</option>
                    <?php foreach ($periodos as $p): ?>
                        <option value="<?= htmlspecialchars($p['Periodo']) ?>"
                            <?= (isset($_GET['periodo']) && $_GET['periodo'] == $p['Periodo']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['Periodo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary d-inline-flex align-items-center" id="btn-filtrar">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>

                <a href="ParticipacionController.php?exportar=pdf<?= isset($_GET['periodo']) && $_GET['periodo'] !== '' ? '&periodo=' . urlencode($_GET['periodo']) : '' ?>&pagina=<?= intval($pagina ?? 1) ?>"
                    class="btn btn-danger d-inline-flex align-items-center" id="btn-pdf">
                    <i class="fa-solid fa-file-pdf me-1"></i> Descargar PDF
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Periodo</th>
                            <th>Promedio</th>
                            <th>Valoración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reporte)): ?>
                            <?php foreach ($reporte as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Curso']) ?></td>
                                    <td><?= htmlspecialchars($fila['Docente']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($fila['Periodo']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($fila['PromedioParticipacion']) ?></td>
                                    <td class="text-center">
                                        <?php
                                        $val = $fila['ValoracionCualitativa'];
                                        $badge = 'secondary';
                                        if (stripos($val, 'Alta') !== false) $badge = 'success';
                                        elseif (stripos($val, 'Media') !== false) $badge = 'warning';
                                        elseif (stripos($val, 'Baja') !== false) $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($val) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay registros disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php
                    $paginaActual = isset($pagina) ? intval($pagina) : (isset($_GET['pagina']) ? intval($_GET['pagina']) : 1);
                    $totalPaginas = $totalPaginas ?? 1;
                    for ($i = 1; $i <= $totalPaginas; $i++):
                        $qs = 'pagina=' . $i;
                        if (!empty($_GET['periodo'])) $qs .= '&periodo=' . urlencode($_GET['periodo']);
                    ?>
                        <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                            <a class="page-link" href="ParticipacionController.php?<?= $qs ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center text-white py-3 bg-dark">
                    <p>© 2025 | Módulo de Reportería - Santa Teresita</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
            alert("<?= addslashes($mensaje) ?>");
        <?php endif; ?>
    </script>

</body>

</html>