<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>


    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2DhZHh5s6NQ4J8R5V9VQ9b6kT81e9PYnD8R9BtQxKxT4UPOyVnB5gE1Z9I5x0GNg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../../../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../../../assets/css/owl.css">
    <link rel="stylesheet" href="../../../assets/css/lightbox.css">
    <link rel="stylesheet" href="styles/reporte-asistencia.css">
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
                <li>
                    <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <section class="section" style="padding-top: 120px;">
        <div class="container mt-5">

            <h2 class="text-center mb-4 text-primary">
                <i class="fa-solid fa-clipboard-list me-2"></i> Reporte de Asistencia
            </h2>
            <div id="toast-container"></div>
            <form method="GET" action="AsistenciaController.php"
                class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4 p-3 rounded bg-light shadow-sm">

                <select name="fecha" class="form-select w-auto">
                    <option value="">Todas las fechas</option>
                    <?php foreach ($fechas as $f): ?>
                        <option value="<?= $f['Fecha'] ?>" <?= (isset($_GET['fecha']) && $_GET['fecha'] == $f['Fecha']) ? 'selected' : '' ?>>
                            <?= $f['Fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary" id="btn-filtrar">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>

                <a href="AsistenciaController.php?exportar=1<?= isset($_GET['fecha']) ? '&fecha=' . $_GET['fecha'] : '' ?>"
                    class="btn btn-success" id="btn-descargar">
                    <i class="fa-solid fa-file-csv me-1"></i> Descargar CSV
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Estudiante</th>
                            <th>Estudiante</th>
                            <th>Grado</th>
                            <th>Sección</th>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Fecha</th>
                            <th>Presente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reporte)): ?>
                            <?php foreach ($reporte as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['Id_Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Grado']) ?></td>
                                    <td><?= htmlspecialchars($fila['Seccion']) ?></td>
                                    <td><?= htmlspecialchars($fila['Curso']) ?></td>
                                    <td><?= htmlspecialchars($fila['Docente']) ?></td>
                                    <td><?= htmlspecialchars($fila['Fecha']) ?></td>
                                    <td><?= $fila['Presente'] ? '✅' : '❌' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay registros disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php
                    $paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
                    $totalRegistros = is_array($reporte) ? count($reporte) : 0;
                    $totalPaginas = ($totalRegistros < 15) ? 1 : ceil($totalRegistros / 15);
                    for ($i = 1; $i <= $totalPaginas; $i++):
                    ?>
                        <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                            <a class="page-link"
                                href="AsistenciaController.php?pagina=<?= $i ?><?= isset($_GET['fecha']) ? '&fecha=' . $_GET['fecha'] : '' ?>">
                                <?= $i ?>
                            </a>
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

    <script src="../../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/toasts.js"></script>
    <script>
        <?php if (isset($mensaje) && !empty($mensaje)): ?>
            mostrarToast("<?= addslashes($mensaje) ?>", "<?= $tipo ?? 'info' ?>");
        <?php endif; ?>
    </script>

</body>

</html>