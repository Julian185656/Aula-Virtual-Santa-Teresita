<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Calificaciones</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">

    <link rel="stylesheet" href="styles/Reporte-Rendimiento.css?v=2">
</head>

<body>
    <div id="toast-container"></div>
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
                <li>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="section" role="main" style="padding-top: 120px;">
        <div class="container mt-5">

            <h2 class="text-center mb-4 text-primary">
                <i class="fa-solid fa-graduation-cap me-2"></i> Reporte de Calificaciones
            </h2>



            <form method="GET" action="RendimientoController.php"
                class="d-flex justify-content-center align-items-center flex-wrap mb-4 p-3 rounded bg-light shadow-sm gap-fix">

                <select name="curso" class="form-select w-auto">
                    <option value="">Todas las materias</option>
                    <?php

                    $cursosDisponibles = [];
                    if (!empty($resumen)) {
                        foreach ($resumen as $r) {
                            $cursosDisponibles[$r['Id_Curso']] = $r['Curso'];
                        }
                    } elseif (!empty($reporte)) {
                        foreach ($reporte as $r) {
                            $cursosDisponibles[$r['Id_Curso']] = $r['Curso'];
                        }
                    }

                    foreach ($cursosDisponibles as $id => $nombre):
                    ?>
                        <option value="<?= $id ?>" <?= (isset($_GET['curso']) && $_GET['curso'] == $id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary d-inline-flex align-items-center ms-2">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>

                <a href="RendimientoController.php" class="btn btn-secondary d-inline-flex align-items-center ms-2">
                    <i class="fa-solid fa-rotate-right me-1"></i> Restablecer
                </a>

                <a href="RendimientoController.php?verResumen=1" class="btn btn-success d-inline-flex align-items-center ms-2">
                    <i class="fa-solid fa-chart-line me-1"></i> Ver Resumen
                </a>
            </form>


            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Estudiante</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha de Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reporte)): ?>
                            <?php foreach ($reporte as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['IdEstudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Curso']) ?></td>
                                    <td><?= htmlspecialchars($fila['Docente']) ?></td>
                                    <td><?= htmlspecialchars(number_format($fila['Calificacion'], 2)) ?></td>
                                    <td><?= htmlspecialchars($fila['Comentario']) ?></td>
                                    <td><?= htmlspecialchars($fila['Fecha_Entrega']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay registros disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($resumen)): ?>
                <div class="mt-5">
                    <h4 class="text-center text-success mb-3">
                        <i class="fa-solid fa-chart-column me-2"></i> Promedio General por Materia
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped shadow-sm">
                            <thead class="table-success">
                                <tr>
                                    <th>ID Curso</th>
                                    <th>Curso</th>
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resumen as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['Id_Curso']) ?></td>
                                        <td><?= htmlspecialchars($fila['Curso']) ?></td>
                                        <td><?= htmlspecialchars(number_format($fila['PromedioCurso'], 2)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>


            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php
                    $paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
                    $totalPaginas = ($totalRegistros < 15) ? 1 : ceil($totalRegistros / 15);
                    for ($i = 1; $i <= $totalPaginas; $i++):
                    ?>
                        <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                            <a class="page-link"
                                href="RendimientoController.php?pagina=<?= $i ?><?= isset($_GET['curso']) ? '&curso=' . $_GET['curso'] : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        </div>
    </section>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="scripts/toasts.js"></script>
    <script>
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
            mostrarToast("<?= addslashes($mensaje) ?>", "<?= $tipo ?? 'info' ?>");
        <?php endif; ?>
    </script>
</body>

</html>