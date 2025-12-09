<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Calificaciones</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.7;
            color: #c4c3ca;
            padding: 40px 15px;

            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            background-position: center top;
            overflow-x: hidden;
        }

        h1, h2, label, th, td, p, a, span {
            color: #fff !important;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 2rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .filter-form select.form-select {
            padding: 10px 15px;
            border-radius: 15px;
            border: none;
            min-width: 200px;
            font-weight: 600;
        }

        .filter-form button, .filter-form a {
            padding: 10px 20px;
            border-radius: 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s ease;
        }

        .filter-form button.btn-primary {
            background-color: #ff9f43;
        }

        .filter-form button.btn-primary:hover {
            background-color: #e88f32;
        }

        .filter-form a.btn-success {
            background-color: #28c76f;
        }

        .filter-form a.btn-success:hover {
            background-color: #20b85a;
        }

        .filter-form a.btn-secondary {
            background-color: #6c757d;
        }

        .filter-form a.btn-secondary:hover {
            background-color: #5a6268;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.25);
        }

        table, table thead th, table tbody td {
            color: #fff !important;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background: rgba(255, 255, 255, 0.1);
            font-weight: bold;
        }

        table th, table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.02);
        }

        tbody tr:hover {
            background: rgba(255,255,255,0.1);
        }

        .pagination a {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border: none;
            margin: 0 3px;
        }

        .pagination a:hover {
            background: rgba(255,255,255,0.3);
        }

        .pagination .active .page-link {
            background: #fff !important;
            color: #1f272b !important;
        }

        .btn-back {
            position: absolute;
            top: 30px;
            left: 30px;
            background: #ff9f43;
            border: none;
            border-radius: 50%;
            width: 55px;
            height: 55px;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            cursor: pointer;
            transition: .2s;
        }

        .btn-back:hover {
            transform: scale(1.05);
            opacity: .85;
        }

    </style>
</head>
<body>

     <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3"
       style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>


    <div class="container">
        <h2><i class="fa-solid fa-graduation-cap me-2"></i> Reporte de Calificaciones</h2>

        <!-- FILTRO -->
        <div class="glass-box">
            <form method="GET" class="filter-form">
                <select name="curso" class="form-select">
                    <option value="">Todos los cursos</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['Id_Curso'] ?>" <?= ($idCurso == $c['Id_Curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>
                <a href="RendimientoController.php?export=1<?= $idCurso ? "&curso=$idCurso" : '' ?>" class="btn btn-success">
                    <i class="fa-solid fa-download"></i> Exportar Excel
                </a>
                <a href="RendimientoController.php?verResumen=1" class="btn btn-secondary">
                    <i class="fa-solid fa-chart-pie"></i> Ver Resumen
                </a>
            </form>
        </div>

        <!-- TABLA -->
        <div class="glass-box table-responsive">
            <table class="table table-borderless">
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
                                <td><?= htmlspecialchars(number_format($fila['Calificacion'], 2)) ?></td>
                                <td><?= htmlspecialchars($fila['Comentario']) ?></td>
                                <td><?= htmlspecialchars($fila['Fecha_Entrega']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No hay registros</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINACION -->
        <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>&curso=<?= $idCurso ?>">«</a>
                    </li>
                    <?php for ($i=1; $i<=$totalPaginas; $i++): ?>
                        <li class="page-item <?= $paginaActual==$i?'active':'' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&curso=<?= $idCurso ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>&curso=<?= $idCurso ?>">»</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
