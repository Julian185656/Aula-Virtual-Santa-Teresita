<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
body{
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

        h2, label, th, td, p, a, span {
            color: #ffffff !important;
        }

        .fa, .fas, .fa-solid, i {
            color: #ffffff !important;
        }

        .titulo-section {
            font-weight: 700;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 25px;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(6px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0, 0, 0, .25);
        }

        thead {
            background: #273036 !important;
        }

        tbody tr:nth-child(odd) {
            background: rgba(255, 255, 255, .05);
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, .10);
        }

        .page-link {
            background: #273036 !important;
            color: #ffffff !important;
            border: none;
        }

        .page-item.active .page-link {
            background: #ffffff !important;
            color: #1f272b !important;
        }

        .btn-primary {
            background-color: #ff9f43 !important;
            border: none;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-success {
            background-color: #28c76f !important;
            border: none;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        select.form-select,
        option {
            color: black !important;
            background: #eaeaea !important;
            font-weight: 600;
        }

        .icon-check {
            color: #00ff88 !important;
            font-size: 22px;
        }

        .icon-x {
            color: #ff4c4c !important;
            font-size: 22px;
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
    <section class="section" style="padding-top: 120px;">
        <div class="container">

            <h2 class="titulo-section">
                <i class="fa-solid fa-clipboard-list me-2"></i> Reporte de Asistencia
            </h2>

            <div class="glass-box mb-4">
                <form method="GET" action="AsistenciaController.php"
                    class="d-flex justify-content-center align-items-center flex-wrap gap-3">

                    <select name="fecha" class="form-select w-auto">
                        <option value="">Todas las fechas</option>
                        <?php foreach ($fechas as $f): ?>
                            <option value="<?= $f['Fecha'] ?>"
                                <?= (isset($_GET['fecha']) && $_GET['fecha'] == $f['Fecha']) ? 'selected' : '' ?>>
                                <?= $f['Fecha'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Filtrar
                    </button>

                    <a href="AsistenciaController.php?exportar=1<?= isset($_GET['fecha']) ? '&fecha=' . $_GET['fecha'] : '' ?>"
                        class="btn btn-success">
                        <i class="fa-solid fa-file-csv"></i> CSV
                    </a>
                </form>
            </div>

            <div class="glass-box">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
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
                                    <td colspan="8" class="text-center">No hay registros disponibles</td>
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
        </div>
    </section>

    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
