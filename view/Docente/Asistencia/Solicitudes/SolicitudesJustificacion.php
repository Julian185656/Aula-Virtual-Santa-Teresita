<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Justificación</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
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
      display:inline-flex; align-items:center; gap:8px;
      padding:10px 18px; border-radius:14px;
      border:1px solid rgba(255,255,255,.30);
      background:rgba(255,255,255,.10);
      color:#fff; text-decoration:none; transition:.2s;
    }
    .btn-volver:hover{ background:rgba(255,255,255,.22); color:#fff; }
    .glass{
      background:rgba(255,255,255,0.06);
      border-radius:18px;
      border:1px solid rgba(255,255,255,0.18);
      backdrop-filter: blur(12px);
      box-shadow:0 10px 30px rgba(0,0,0,0.30);
    }
    .filters{ padding:18px; margin-bottom:18px; }
    .form-label{ color:#fff; font-weight:700; }
    .form-select, .form-control{
      background: rgba(255,255,255,0.10);
      color:#fff;
      border:1px solid rgba(255,255,255,0.22);
      border-radius:12px;
    }
    .form-select option{ background:#101733; color:#fff; }
    .btn-primary{
      background:#6a5acd; border:none; border-radius:12px; font-weight:800;
    }
    .btn-primary:hover{ background:#836fff; }

    .table-wrap{ padding:18px; }
    table.table th, table.table td{ color:#fff !important; }
    thead{ background: rgba(255,255,255,0.14); }
    tbody tr:nth-child(even){ background: rgba(255,255,255,0.04); }
    tbody tr:hover{ background: rgba(255,255,255,0.08); }
    .muted{ color: rgba(255,255,255,0.70) !important; }
    .badge{ font-weight:700; }
    .table {
            --bs-table-bg: transparent !important;
        }

  </style>
</head>

<body>
<div class="container">

  <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver mb-3">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
  </a>

  <h2><i class="fa-solid fa-inbox me-2"></i> Solicitudes de Justificación</h2>

  <?php if (!empty($mensaje)): ?>
    <div class="alert alert-<?= htmlspecialchars($tipo ?? 'info') ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($mensaje) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
  <?php endif; ?>

  <!-- FILTROS -->
  <div class="glass filters">
    <form method="GET" action="SolicitudesJustificacionController.php" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Curso</label>
        <select name="curso" class="form-select" required>
          <option value="">Seleccione un curso</option>
          <?php foreach(($cursos ?? []) as $c): 
            $idC = (int)($c['Id_Curso'] ?? 0);
            $nom = $c['Nombre'] ?? ('Curso ' . $idC);
          ?>
            <option value="<?= $idC ?>" <?= ($idC === (int)($cursoId ?? 0)) ? 'selected' : '' ?>>
              <?= htmlspecialchars($nom) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Desde</label>
        <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label">Hasta</label>
        <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label">Estado</label>
        <select name="estado" class="form-select">
          <?php
            $est = strtolower(trim((string)($estado ?? 'pendiente')));
          ?>
          <option value="pendiente" <?= $est==='pendiente'?'selected':'' ?>>Pendiente</option>
          <option value="aprobada" <?= $est==='aprobada'?'selected':'' ?>>Aprobada</option>
          <option value="denegada" <?= $est==='denegada'?'selected':'' ?>>Denegada</option>
          <option value="todos" <?= $est==='todos'?'selected':'' ?>>Todos</option>
        </select>
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
    <h5 class="mb-3 fw-bold"><i class="fa-solid fa-file-arrow-up me-2"></i> Solicitudes</h5>

    <?php if (((int)($cursoId ?? 0)) <= 0): ?>
      <p class="muted">Selecciona un curso para ver las solicitudes.</p>

    <?php elseif (empty($solicitudes)): ?>
      <p class="muted">No hay solicitudes con los filtros seleccionados.</p>

    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Fecha ausencia</th>
              <th>Estudiante</th>
              <th>Correo</th>
              <th>Estado</th>
              <th>Comprobante</th>
              <th style="width: 330px;">Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($solicitudes as $s): 
              $id = (int)($s['id'] ?? 0);
              $fecha = $s['fecha_ausencia'] ?? '';
              $estNom = $s['Estudiante'] ?? '-';
              $correo = $s['Correo'] ?? '-';
              $estadoRow = strtolower(trim((string)($s['estado'] ?? 'pendiente')));
              $comprobante = $s['comprobante'] ?? '';

              $badge = 'secondary';
              if ($estadoRow === 'pendiente') $badge = 'warning';
              if ($estadoRow === 'aprobada') $badge = 'success';
              if ($estadoRow === 'denegada') $badge = 'danger';

              // Link al comprobante (guardado como "comprobantes/archivo.ext")
              $urlArchivo = "/Aula-Virtual-Santa-Teresita/" . ltrim($comprobante, '/');
            ?>
              <tr>
                <td><?= htmlspecialchars($fecha) ?></td>
                <td><?= htmlspecialchars($estNom) ?></td>
                <td><?= htmlspecialchars($correo) ?></td>
                <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars(ucfirst($estadoRow)) ?></span></td>
                <td>
                  <?php if(!empty($comprobante)): ?>
                    <a class="btn btn-sm btn-outline-light" href="<?= htmlspecialchars($urlArchivo) ?>" target="_blank" download>
                      <i class="fa-solid fa-download me-1"></i> Ver
                    </a>
                  <?php else: ?>
                    <span class="muted small">(sin archivo)</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if($estadoRow !== 'pendiente'): ?>
                    <span class="muted small">Esta solicitud ya fue resuelta.</span>
                  <?php else: ?>
                    <form method="POST" action="SolicitudesJustificacionController.php" class="d-flex flex-column gap-2">
                      <input type="hidden" name="id" value="<?= $id ?>">
                      <input type="hidden" name="curso" value="<?= (int)($cursoId ?? 0) ?>">
                      <input type="hidden" name="desde" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
                      <input type="hidden" name="hasta" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
                      <input type="hidden" name="estado" value="<?= htmlspecialchars($estado ?? 'pendiente') ?>">
                      <input type="hidden" name="pagina" value="<?= (int)($pagina ?? 1) ?>">

                      <input type="text" name="comentario_docente" class="form-control form-control-sm"
                             placeholder="Comentario (opcional)" maxlength="255">

                      <div class="d-flex gap-2">
                        <button type="submit" name="accion" value="aprobar" class="btn btn-success btn-sm w-50">
                          <i class="fa-solid fa-check me-1"></i> Aprobar
                        </button>
                        <button type="submit" name="accion" value="denegar" class="btn btn-danger btn-sm w-50">
                          <i class="fa-solid fa-xmark me-1"></i> Denegar
                        </button>
                      </div>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if (((int)($totalPaginas ?? 1)) > 1): ?>
        <nav class="mt-3">
          <ul class="pagination justify-content-center">
            <?php for($i=1; $i <= (int)$totalPaginas; $i++):
              $params = ['curso'=>$cursoId,'desde'=>$fechaDesde,'hasta'=>$fechaHasta,'estado'=>$estado,'pagina'=>$i];
              $url = 'SolicitudesJustificacionController.php?' . http_build_query($params);
            ?>
              <li class="page-item <?= ($i === (int)($pagina ?? 1)) ? 'active' : '' ?>">
                <a class="page-link" href="<?= htmlspecialchars($url) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

      <p class="mt-2 muted small">
        Mostrando <?= count($solicitudes) ?> de <?= (int)($totalRegistros ?? 0) ?> solicitudes.
      </p>
    <?php endif; ?>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
