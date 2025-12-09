<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>

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
            margin: 0 auto;
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

        .icon-check {
            color: #00ff88 !important;
            font-size: 22px;
        }

        .icon-x {
            color: #ff4c4c !important;
            font-size: 22px;
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
        <h2><i class="fa-solid fa-clipboard-list me-2"></i> Reporte de Asistencia</h2>

        <!-- FILTRO -->
        <div class="glass-box">
            <form method="GET" action="AsistenciaController.php" class="filter-form">
                <select name="curso" class="form-select">
                    <option value="">Todos los cursos</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['Id_Curso'] ?>" <?= (isset($_GET['curso']) && $_GET['curso'] == $c['Id_Curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="fecha" class="form-select">
                    <option value="">Todas las fechas</option>
                    <?php foreach ($fechas as $f): ?>
                        <option value="<?= $f['Fecha'] ?>" <?= (isset($_GET['fecha']) && $_GET['fecha'] == $f['Fecha']) ? 'selected' : '' ?>>
                            <?= $f['Fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filtrar</button>

                <a href="AsistenciaController.php?exportar=1<?= isset($_GET['fecha']) ? '&fecha=' . $_GET['fecha'] : '' ?>" class="btn btn-success">
                    <i class="fa-solid fa-file-csv"></i> CSV
                </a>
            </form>
        </div>

        <!-- TABLA -->
        <div class="glass-box">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>ID Estudiante</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Fecha</th>
                            <th class="text-center">Presente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($reporte)): ?>
                            <?php foreach ($reporte as $fila): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['Id_Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($fila['Curso']) ?></td>
                                    <td><?= htmlspecialchars($fila['Fecha']) ?></td>
                                    <td class="text-center">
                                        <?php if ($fila['Presente']): ?>
                                            <i class="fa-solid fa-circle-check icon-check"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-circle-xmark icon-x"></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay registros disponibles</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINACION -->
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php
                        $paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
                        $totalPaginas = ($totalRegistros < $limite) ? 1 : ceil($totalRegistros / $limite);

                        for ($i = 1; $i <= $totalPaginas; $i++):
                    ?>
                    <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                        <a class="page-link" href="AsistenciaController.php?pagina=<?= $i ?><?= isset($_GET['fecha']) ? '&fecha=' . $_GET['fecha'] : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
