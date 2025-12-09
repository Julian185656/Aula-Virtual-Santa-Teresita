<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Participación</title>

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
   padding: 20px 15px;

   

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
            margin-bottom: 30px;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.07);
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

        /* Paginación */
        .page-link {
            background: #273036 !important;
            color: #ffffff !important;
            border: none;
        }

        .page-item.active .page-link {
            background: #ffffff !important;
            color: #1f272b !important;
        }

        /* Botones */
        .btn-primary {
            background-color: #ff9f43 !important;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger {
            background-color: #ea5455 !important;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Select */
        select.form-select {
            color: black !important;
            background: #eaeaea !important;
            padding: 10px 12px;
            border-radius: 10px;
            font-weight: 600;
        }

     .btn-volver {
            border-radius: 15px;
            padding: 8px 18px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .btn-volver:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        /* Contenedor de filtros */
        .filtros-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>

 
<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" 
       class="btn btn-outline-light btn-volver">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>

    <section class="section" style="padding-top: 40px;">
        

    
    <div class="container">



            <h2 class="titulo-section">
                <i class="fa-solid fa-chart-line me-2"></i> Reporte de Participación
            </h2>

            <!-- FILTROS -->
            <div class="glass-box mb-4">

                <form method="GET" action="ParticipacionController.php" class="filtros-box">

                    <select name="periodo" class="form-select w-auto">
                        <option value="">Todos los periodos</option>
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['Periodo'] ?>"
                                <?= (isset($_GET['periodo']) && $_GET['periodo'] == $p['Periodo']) ? 'selected' : '' ?>>
                                <?= $p['Periodo'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Filtrar
                    </button>

                    <a href="ParticipacionController.php?exportar=pdf<?= isset($_GET['periodo']) ? '&periodo=' . $_GET['periodo'] : '' ?>"
                        class="btn btn-danger">
                        <i class="fa-solid fa-file-pdf"></i> PDF
                    </a>

                </form>
            </div>

            <!-- TABLA -->
            <div class="glass-box">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
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
                                        <td><?= $fila['Estudiante'] ?></td>
                                        <td><?= $fila['Curso'] ?></td>
                                        <td><?= $fila['Docente'] ?></td>
                                        <td class="text-center"><?= $fila['Periodo'] ?></td>
                                        <td class="text-center"><?= $fila['PromedioParticipacion'] ?></td>

                                        <td class="text-center">
                                            <?php
                                            $val = $fila['ValoracionCualitativa'];
                                            $badge = "secondary";

                                            if (stripos($val, 'Alta') !== false) $badge = "success";
                                            elseif (stripos($val, 'Media') !== false) $badge = "warning";
                                            elseif (stripos($val, 'Baja') !== false) $badge = "danger";
                                            ?>
                                            <span class="badge bg-<?= $badge ?>">
                                                <?= $val ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No hay registros</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php
                        $paginaActual = $pagina ?? 1;
                        for ($i = 1; $i <= $totalPaginas; $i++):
                        ?>
                            <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="ParticipacionController.php?pagina=<?= $i ?><?= isset($_GET['periodo']) ? '&periodo=' . $_GET['periodo'] : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

            </div>

        </div>
    </section>

</body>
</html>
