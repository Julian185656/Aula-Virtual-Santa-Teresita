<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Calificaciones</title>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #ffffff;
            padding: 40px 20px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
        }

        .container {
            max-width: 1200px;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
            color: #ffffff;
        }

        /* Botón volver */
        .btn-volver {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            text-decoration: none;
            transition: .2s;
        }

        .btn-volver:hover {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
        }

        /* Tarjeta estilo Glass */
        .card-glass {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            padding: 20px;
            margin-bottom: 25px;
        }

        /* Filtros */
        .filter-form {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .form-select {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            color: #ffffff;
            padding: 10px 12px;
            font-weight: 600;
        }

        .form-select option[value=""] {
            font-weight: 700 !important;
        }

        /* Botones ghost */
        .btn-ghost {
            padding: 10px 22px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-ghost:hover {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
        }

        /* Tabla */
        table {
            width: 100%;
            font-size: 14px;
        }

        .table th,
        .table td {
            color: #ffffff !important;
            vertical-align: middle;
        }

        .table thead th {
            background: rgba(255,255,255,0.15);
            font-weight: 600;
        }

        .table tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.05);
        }

        .table tbody tr:hover {
            background: rgba(255,255,255,0.15);
        }

        .table tbody td:first-child {
            font-weight: 600;
        }

        /* Paginación */
        .pagination .page-link {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #ffffff;
        }

        .pagination .page-item.active .page-link {
            background: rgba(255,255,255,0.35);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Botón volver -->
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa fa-arrow-left"></i> Volver
    </a>

    <div class="container">
        <h2>Reporte de Calificaciones</h2>

        <!-- Filtros -->
        <div class="card-glass">
            <form method="GET" action="RendimientoController.php" class="filter-form">

                <select name="curso" class="form-select">
                    <option value="">Todos los cursos</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['Id_Curso'] ?>"
                                <?= ($idCurso == $c['Id_Curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-ghost">
                    <i class="fa fa-filter"></i> Filtrar
                </button>

                <a href="RendimientoController.php?export=1<?= $idCurso ? "&curso=$idCurso" : '' ?>" class="btn-ghost">
                    <i class="fa fa-download"></i> Exportar Excel
                </a>

                <a href="RendimientoController.php?verResumen=1" class="btn-ghost">
                    <i class="fa fa-chart-bar"></i> Ver Resumen
                </a>

            </form>
        </div>

        <!-- Tabla -->
        <div class="card-glass table-responsive">
            <table class="table table-borderless text-center">
                <thead>
                    <tr>
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
                            <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                            <td><?= htmlspecialchars($fila['Curso']) ?></td>
                            <td><?= htmlspecialchars($fila['Docente']) ?></td>
                            <td><?= number_format($fila['Calificacion'], 2) ?></td>
                            <td><?= htmlspecialchars($fila['Comentario']) ?></td>
                            <td><?= htmlspecialchars($fila['Fecha_Entrega']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay registros</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?= ($paginaActual == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&curso=<?= $idCurso ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
