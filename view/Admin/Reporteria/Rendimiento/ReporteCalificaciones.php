<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Calificaciones</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .container {
            max-width: 1200px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            text-shadow: 0 2px 8px rgba(0,0,0,.5);
        }

        .btn-volver {
            border-radius: 15px;
            padding: 8px 18px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        /* Evitar que los botones se tornen blancos */
        .btn,
        .btn:focus,
        .btn:active {
            background-color: inherit !important;
            box-shadow: none !important;
            color: white !important;
        }

        /* Quitar efecto blanco al hacer hover */
        .btn:hover {
            filter: brightness(0.9);
            color: white !important;
        }

        .btn-primary { background-color: #ff9f43 !important; }
        .btn-secondary { background-color: #6c757d !important; }
        .btn-success { background-color: #28c76f !important; }

        /* Glass boxes */
        .glass-box {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            margin-bottom: 25px;
        }

        select.form-select {
            background: #ffffff !important;
            color: #000 !important;
        }

        option { color: #000 !important; }

        /* Tabla */
        .table-container {
            overflow-x: auto;
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        table, table thead th, table tbody td {
            color: #ffffff !important;
            background: transparent !important;
        }

        .table thead tr {
            background-color: #2e353b !important;
        }

        .table thead th {
            font-weight: 600;
            padding: 12px 15px;
            border-bottom: 2px solid #3a4148 !important;
        }

        .table tbody tr:nth-child(odd) { background-color: #242c31 !important; }
        .table tbody tr:nth-child(even) { background-color: #2b3338 !important; }
        .table tbody tr:hover { background-color: #3a4148 !important; }

        /* Paginación */
        .pagination .page-link {
            background-color: #273036;
            color: white;
            border: none;
            border-radius: 10px;
            margin: 0 4px;
        }
        .pagination .page-item.active .page-link {
            background-color: #ff9f43 !important;
            color: black !important;
        }
        .pagination .page-link:hover {
            background-color: #ffb65c;
            color: black;
        }
    </style>
</head>

<body>




<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" 
       class="btn btn-outline-light btn-volver">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>

    <h2>
        <i class="fa-solid fa-graduation-cap me-2"></i>
        Reporte de Calificaciones
    </h2>

    <!-- FILTROS -->
    <div class="glass-box">

        <form method="GET" action="RendimientoController.php"
            class="d-flex justify-content-center align-items-center flex-wrap gap-3">

            <select name="curso" class="form-select w-auto">
                <option value="">Todas las materias</option>

                <?php
                $cursosDisponibles = [];
                if (!empty($resumen)) {
                    foreach ($resumen as $r) $cursosDisponibles[$r['Id_Curso']] = $r['Curso'];
                } elseif (!empty($reporte)) {
                    foreach ($reporte as $r) $cursosDisponibles[$r['Id_Curso']] = $r['Curso'];
                }

                foreach ($cursosDisponibles as $id => $nombre): ?>
                    <option value="<?= $id ?>" <?= (isset($_GET['curso']) && $_GET['curso'] == $id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($nombre) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter me-1"></i> Filtrar
            </button>

            <a href="RendimientoController.php" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right me-1"></i> Restablecer
            </a>

            <a href="RendimientoController.php?verResumen=1" class="btn btn-success">
                <i class="fa-solid fa-chart-line me-1"></i> Ver Resumen
            </a>
        </form>

    </div>

    <!-- TABLA -->
    <div class="table-container">
        <table class="table table-borderless">
            <thead>
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
                    <tr><td colspan="7" class="text-center">No hay registros disponibles</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <?php if (isset($totalPaginas) && $totalPaginas > 1): ?>
        <nav class="d-flex justify-content-center mt-3">
            <ul class="pagination">

                <!-- Anterior -->
                <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>&curso=<?= $_GET['curso'] ?? '' ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>

                <!-- Números -->
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $paginaActual == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>&curso=<?= $_GET['curso'] ?? '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Siguiente -->
                <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>&curso=<?= $_GET['curso'] ?? '' ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>

            </ul>
        </nav>
    <?php endif; ?>

</div>

</body>
</html>
