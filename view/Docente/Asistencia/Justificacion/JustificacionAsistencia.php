<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Justificación de Ausencias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fuentes y CSS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
    <link href="../../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
     body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}
/* Texto de la tabla en blanco */
table.table, 
table.table th, 
table.table td, 
table.table tbody td input,
table.table tbody td span {
    color: #fff !important;
}

/* Inputs dentro de la tabla (comentario de justificación) */
table.table tbody td input.form-control {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: none;
}

/* Botones dentro de la tabla */
table.table tbody td .btn {
    color: #fff;
}

/* Comentarios en pequeño */
table.table tbody td span.text-muted {
    color: rgba(255,255,255,0.6) !important;
}

/* Hover de la fila */
table.table tbody tr:hover {
    background: rgba(255,255,255,0.08);
}

        h2 {
            color: #ffffffff;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            color: #fff;
            font-weight: 500;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Formulario filtros */
        form.row.g-3.align-items-end {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-select, .form-control {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            border: none;
            padding: 10px 12px;
        }

        .form-select option {
            background: #2a2b38;
            color: #fff;
        }

        .btn-primary {
            background-color: #6a5acd;
            border: none;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background-color: #836fff;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            transition: 0.2s;
        }

        .btn-success:hover {
            background-color: #3cd05c;
        }

        .text-muted {
            color: rgba(255,255,255,0.6) !important;
        }

        /* Tabla estilizada */
        table.table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 15px;
            overflow: hidden;
            width: 100%;
        }

        table.table th, table.table td {
            vertical-align: middle;
            border-top: none;
        }

        table.table thead {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 600;
        }

        table.table thead th {
            border-bottom: 2px solid rgba(255,255,255,0.2);
        }

        table.table tbody tr {
            background: rgba(255, 255, 255, 0.02);
            transition: 0.3s;
        }

        table.table tbody tr:hover {
            background: rgba(253, 253, 253, 0.08);
            transform: translateY(-1px);
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Paginación */
        .pagination a {
            color: #fff;
            background: rgba(255,255,255,0.1);
            margin:0 3px;
            border-radius: 5px;
        }

        .pagination a:hover {
            background: rgba(255,255,255,0.3);
        }

        .pagination .active a {
            background: #6a5acd !important;
            color: #fff;
        }

        /* Alertas */
        .alert {
            border-radius: 12px;
        }
    </style>
</head>

<body>




 <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>


    <div class="container">

        <h2>
            <i class="fa-solid fa-file-circle-check me-2"></i>
            Marcar ausencia justificada
        </h2>

      
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?= htmlspecialchars($tipo ?? 'info') ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

 
        <form method="GET" action="JustificarAusenciaController.php" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Curso</label>
                <select name="curso" class="form-select" required>
                    <option value="">Seleccione un curso</option>
                    <?php if (!empty($cursos)): ?>
                        <?php foreach ($cursos as $c): 
                            $idC = (int)$c['Id_Curso'];
                            $nombreC = $c['Curso'] ?? ($c['Nombre'] ?? ('Curso ' . $idC));
                        ?>
                        <option value="<?= $idC ?>" <?= ($idC === ($cursoId ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nombreC) ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
            </div>
            <div class="col-md-2 d-flex justify-content-start align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>

  
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">
                    <i class="fa-solid fa-user-clock me-2"></i>
                    Ausencias pendientes de justificación
                </h5>

                <?php if (($cursoId ?? 0) <= 0): ?>
                    <p class="text-muted">
                        Selecciona un curso y, opcionalmente, un rango de fechas para ver las ausencias.
                    </p>
                <?php elseif (empty($ausencias)): ?>
                    <p class="text-muted">
                        No hay ausencias pendientes de justificación con los filtros seleccionados.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Estudiante</th>
                                    <th>Correo</th>
                                    <th>Curso</th>
                                    <th>Comentario</th>
                                    <th style="width: 170px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ausencias as $a): 
                                    $idCurso  = (int)$a['Id_Curso'];
                                    $idEst    = (int)$a['Id_Estudiante'];
                                    $fecha    = $a['Fecha'];
                                    $comentDB = $a['ComentarioJustificacion'] ?? '';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($fecha) ?></td>
                                    <td><?= htmlspecialchars($a['Estudiante']) ?></td>
                                    <td><?= htmlspecialchars($a['Correo']) ?></td>
                                    <td><?= htmlspecialchars($a['Curso']) ?></td>
                                    <td><span class="text-white-50 small"><?= !empty($comentDB) ? htmlspecialchars($comentDB) : '(sin comentario)' ?></span></td>
                                    <td>
                                        <form method="POST" action="JustificarAusenciaController.php" class="d-flex flex-column gap-1">
                                            <input type="hidden" name="curso" value="<?= $idCurso ?>">
                                            <input type="hidden" name="estudiante" value="<?= $idEst ?>">
                                            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                                            <input type="hidden" name="desde" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                                            <input type="hidden" name="hasta" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                                            <input type="hidden" name="pagina" value="<?= (int)($pagina ?? 1) ?>">

                                            <input type="text" name="comentario" class="form-control form-control-sm" placeholder="Motivo de la justificación" maxlength="255" required>
                                            <button type="submit" class="btn btn-success btn-sm mt-1">
                                                <i class="fa-solid fa-check me-1"></i> Justificar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <?php if (($totalPaginas ?? 1) > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPaginas; $i++): 
                                    $paramsPag = ['curso'=>$cursoId,'desde'=>$fechaDesde,'hasta'=>$fechaHasta,'pagina'=>$i];
                                    $urlPag = 'JustificarAusenciaController.php?' . http_build_query($paramsPag);
                                ?>
                                <li class="page-item <?= ($i === ($pagina ?? 1)) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $urlPag ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>

                    <p class="mt-2 text-muted small">
                        Mostrando <?= count($ausencias) ?> de <?= (int)($totalRegistros ?? 0) ?> ausencias pendientes.
                    </p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
