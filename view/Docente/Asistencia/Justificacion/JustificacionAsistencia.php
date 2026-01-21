<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Justificación de Ausencias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- Bootstrap 5 + icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body{
            font-family:'Poppins',sans-serif;
            font-weight:300;
            font-size:15px;
            color:#fff;
            padding:40px 15px;
            background-color:#2a2b38;
            background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat:repeat;
            background-size:600px;
            background-position:center top;
            overflow-x:hidden;
        }

        h2{
            color:#fff;
            text-align:center;
            margin-bottom:30px;
            font-weight:800;
            text-shadow:0 2px 10px rgba(0,0,0,.35);
        }

        .btn-volver{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 18px;
            border-radius:14px;
            border:1px solid rgba(255,255,255,.30);
            background:rgba(255,255,255,.10);
            color:#fff;
            text-decoration:none;
            transition:.2s;
        }
        .btn-volver:hover{ background:rgba(255,255,255,.22); color:#fff; }

        .glass{
            background:rgba(255,255,255,0.06);
            border-radius:18px;
            border:1px solid rgba(255,255,255,0.18);
            backdrop-filter: blur(12px);
            box-shadow:0 10px 30px rgba(0,0,0,0.30);
        }

        /* filtros */
        .filters{
            padding:18px;
            margin-bottom:18px;
        }

        .form-label{ color:#fff; font-weight:700; }

        .form-select, .form-control{
            background: rgba(255,255,255,0.10);
            color:#fff;
            border:1px solid rgba(255,255,255,0.22);
            border-radius:12px;
        }
        .form-select option{ background:#101733; color:#fff; }

        .btn-primary{
            background:#6a5acd;
            border:none;
            border-radius:12px;
            font-weight:800;
        }
        .btn-primary:hover{ background:#836fff; }

        /* tabla */
        .table-wrap{
            padding:18px;
        }

        table.table th,
        table.table td{ color:#fff !important; }

        thead{
            background: rgba(255,255,255,0.14);
        }

        tbody tr:nth-child(even){
            background: rgba(255,255,255,0.04);
        }

        tbody tr:hover{
            background: rgba(255,255,255,0.08);
        }

        .muted{ color: rgba(255,255,255,0.70) !important; }

        .btn-success{
            border-radius:12px;
            font-weight:800;
            border:none;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h2><i class="fa-solid fa-file-circle-check me-2"></i> Marcar ausencia justificada</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= htmlspecialchars($tipo ?? 'info') ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- FILTROS -->
    <div class="glass filters">
        <form method="GET" action="JustificarAusenciaController.php" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Curso</label>
                <select name="curso" class="form-select" required>
                    <option value="">Seleccione un curso</option>
                    <?php foreach(($cursos ?? []) as $c): 
                        $idC = (int)($c['Id_Curso'] ?? $c['id'] ?? 0);
                        $nombreC = $c['Curso'] ?? $c['Nombre'] ?? $c['nombre'] ?? ('Curso ' . $idC);
                    ?>
                        <option value="<?= $idC ?>" <?= ($idC === (int)($cursoId ?? 0)) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nombreC) ?>
                        </option>
                    <?php endforeach; ?>
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

            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- LISTA -->
    <div class="glass table-wrap">
        <h5 class="mb-3 fw-bold">
            <i class="fa-solid fa-user-clock me-2"></i> Ausencias pendientes de justificación
        </h5>

        <?php if (((int)($cursoId ?? 0)) <= 0): ?>
            <p class="muted">Selecciona un curso y, opcionalmente, un rango de fechas para ver las ausencias.</p>

        <?php elseif (empty($ausencias)): ?>
            <p class="muted">No hay ausencias pendientes de justificación con los filtros seleccionados.</p>

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
                            <th style="width: 190px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ausencias as $a): 
                            $idCurso  = (int)($a['Id_Curso'] ?? 0);
                            $idEst    = (int)($a['Id_Estudiante'] ?? $a['Id_Usuario'] ?? 0);
                            $fecha    = $a['Fecha'] ?? '';
                            $comentDB = $a['ComentarioJustificacion'] ?? '';

                            $estNom = $a['Estudiante'] ?? $a['Nombre'] ?? '-';
                            $correo = $a['Correo'] ?? $a['Email'] ?? '-';
                            $cursoN = $a['Curso'] ?? $a['NombreCurso'] ?? '-';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fecha) ?></td>
                            <td><?= htmlspecialchars($estNom) ?></td>
                            <td><?= htmlspecialchars($correo) ?></td>
                            <td><?= htmlspecialchars($cursoN) ?></td>
                            <td><span class="muted small"><?= $comentDB !== '' ? htmlspecialchars($comentDB) : '(sin comentario)' ?></span></td>
                            <td>
                                <form method="POST" action="JustificarAusenciaController.php" class="d-flex flex-column gap-2">
                                    <input type="hidden" name="curso" value="<?= $idCurso ?>">
                                    <input type="hidden" name="estudiante" value="<?= $idEst ?>">
                                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                                    <input type="hidden" name="desde" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                                    <input type="hidden" name="hasta" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                                    <input type="hidden" name="pagina" value="<?= (int)($pagina ?? 1) ?>">

                                    <input type="text" name="comentario" class="form-control form-control-sm"
                                           placeholder="Motivo de la justificación" maxlength="255" required>

                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-check me-1"></i> Justificar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (((int)($totalPaginas ?? 1)) > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= (int)$totalPaginas; $i++): 
                            $paramsPag = ['curso'=>$cursoId,'desde'=>$fechaDesde,'hasta'=>$fechaHasta,'pagina'=>$i];
                            $urlPag = 'JustificarAusenciaController.php?' . http_build_query($paramsPag);
                        ?>
                        <li class="page-item <?= ($i === (int)($pagina ?? 1)) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $urlPag ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <p class="mt-2 muted small">
                Mostrando <?= count($ausencias) ?> de <?= (int)($totalRegistros ?? 0) ?> ausencias pendientes.
            </p>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
