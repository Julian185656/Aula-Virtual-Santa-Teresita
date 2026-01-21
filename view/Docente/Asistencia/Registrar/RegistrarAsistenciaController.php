<?php
// ✅ RegistrarAsistencia.php (VIEW)
// Variables esperadas desde el Controller:
// $fecha, $cursos, $cursoId, $alumnos, $asistenciaMap, $pagina, $totalPaginas
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Asistencia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 4 + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

  <style>
    body{
      font-family:'Poppins',sans-serif;
      font-size:15px;
      color:#fff;
      padding:40px 15px;
      background-color:#2a2b38;
      background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
      background-repeat:repeat;
      background-size:600px;
      overflow-x:hidden;
    }

    /* ✅ Wrapper centrado para que el botón "Volver" no quede pegado a la izquierda */
    .page-wrap{
      max-width: 1100px;
      margin: 0 auto;
    }

    .topbar{
      display:flex;
      justify-content:flex-start;
      margin-bottom:18px;
    }

    /* (Opcional) que se quede visible al hacer scroll */
    .topbar.sticky{
      position: sticky;
      top: 18px;
      z-index: 50;
    }

    .btn-volver{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 18px;
      border-radius:12px;
      text-decoration:none;
      color:#fff;
      border:1px solid rgba(255,255,255,.28);
      background:rgba(255,255,255,.10);
      backdrop-filter: blur(10px);
      transition:.2s;
    }
    .btn-volver:hover{
      background:rgba(255,255,255,.22);
      color:#fff;
      text-decoration:none;
    }

    h2{
      text-align:center;
      margin-bottom:18px;
      color:#fff;
      font-weight:700;
      text-shadow:0 2px 8px rgba(0,0,0,0.5);
    }

    .badge-fecha{
      display:inline-block;
      margin-left:10px;
      padding:6px 10px;
      border-radius:999px;
      background:rgba(255,255,255,.18);
      border:1px solid rgba(255,255,255,.22);
      font-size:12px;
      font-weight:600;
    }

    /* Contenedor filtros */
    .filtro-box{
      display:flex;
      flex-wrap:wrap;
      justify-content:center;
      gap:12px;
      margin: 0 auto 18px auto;
      background:rgba(255,255,255,0.05);
      padding:18px;
      border-radius:18px;
      backdrop-filter: blur(10px);
      border:1px solid rgba(255,255,255,0.20);
    }

    .filtro-box input,
    .filtro-box select{
      min-width: 180px;
      padding: 10px 14px;
      border-radius: 14px;
      background: rgba(255,255,255,0.10);
      color:#fff;
      border:1px solid rgba(255,255,255,0.18);
      outline:none;
    }
    .filtro-box select option{ color:#000; }

    .filtro-box button,
    .filtro-box a{
      padding:10px 16px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,0.18);
      background:rgba(255,255,255,0.12);
      color:#fff;
      text-decoration:none;
      transition:.2s;
      display:inline-flex;
      align-items:center;
      gap:8px;
      cursor:pointer;
    }
    .filtro-box button:hover,
    .filtro-box a:hover{
      background:rgba(255,255,255,0.22);
      color:#fff;
      text-decoration:none;
    }

    /* Card */
    .card-glass{
      background:rgba(255,255,255,0.05);
      padding:20px;
      border-radius:18px;
      backdrop-filter: blur(10px);
      border:1px solid rgba(255,255,255,0.20);
    }

    /* Tabla */
    .table,
    .table th,
    .table td,
    .table thead th,
    .table tbody td{ color:#fff !important; }

    .table thead{
      background:rgba(255,255,255,0.14);
    }

    .table tbody tr:nth-child(even){
      background:rgba(255,255,255,0.06);
    }
    .table tbody tr:hover td{
      background:rgba(255,255,255,0.12);
      color:#fff !important;
    }

    .text-muted{
      color:rgba(255,255,255,0.70) !important;
    }

    /* Estado toggle */
    .estado-toggle{
      display:flex;
      gap:8px;
      justify-content:center;
      flex-wrap:wrap;
    }

    .toggle-btn{
      padding:6px 14px;
      border-radius:10px;
      font-weight:600;
      cursor:pointer;
      border:1px solid rgba(255,255,255,0.18);
      background:rgba(255,255,255,0.08);
      color:#fff;
      transition:.15s;
    }

    .btn-presente{
      border-color: rgba(34,197,94,0.55);
      color:#22c55e;
      background: rgba(34,197,94,0.12);
    }
    .btn-ausente{
      border-color: rgba(239,68,68,0.55);
      color:#ef4444;
      background: rgba(239,68,68,0.12);
    }
    .btn-presente.active{
      background:#22c55e;
      color:#fff;
      border-color:#22c55e;
    }
    .btn-ausente.active{
      background:#ef4444;
      color:#fff;
      border-color:#ef4444;
    }

    /* Acciones sticky */
    .sticky-actions{
      margin-top: 14px;
      padding: 12px 14px;
      border-radius: 14px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.18);
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:12px;
      flex-wrap:wrap;
    }

    .btn-guardar{
      background:#6a5acd;
      border:none;
      border-radius:12px;
      padding:10px 16px;
      color:#fff;
      font-weight:700;
    }
    .btn-guardar:hover{ background:#836fff; }

    /* Botones "Todos presentes/ausentes" */
    .mini-actions{
      display:flex;
      gap:8px;
      flex-wrap:wrap;
      justify-content:flex-end;
    }
    .btn-mini{
      padding:6px 10px;
      border-radius:10px;
      border:1px solid rgba(255,255,255,0.18);
      background:rgba(255,255,255,0.10);
      color:#fff;
      cursor:pointer;
      transition:.2s;
      font-size:13px;
      font-weight:600;
    }
    .btn-mini:hover{ background:rgba(255,255,255,0.20); }

    .pagination .page-link{
      background:rgba(255,255,255,0.10);
      color:#fff;
      border:none;
      margin:0 3px;
      border-radius:10px;
    }
    .pagination .page-item.active .page-link{
      background:#6a5acd;
      color:#fff;
    }
  </style>
</head>

<body>

  <div class="page-wrap">

    <!-- ✅ BOTÓN VOLVER centrado al mismo ancho del contenido -->
    <div class="topbar sticky">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
      </a>
    </div>

    <h2>
      <i class="bi bi-clipboard2"></i> Registrar Asistencia
      <?php if (!empty($fecha)): ?>
        <span class="badge-fecha"><?= htmlspecialchars($fecha) ?></span>
      <?php endif; ?>
    </h2>

    <!-- FILTROS -->
    <form method="GET" class="filtro-box">
      <input type="date" name="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

      <select name="curso" required>
        <option value="">Seleccione un curso</option>
        <?php foreach(($cursos ?? []) as $c): ?>
          <option value="<?= (int)$c['Id_Curso'] ?>"
            <?= (!empty($cursoId) && (int)$cursoId === (int)$c['Id_Curso']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['Curso']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit">
        <i class="bi bi-filter"></i> Cargar lista
      </button>

      <a href="RegistrarAsistenciaController.php">
        <i class="bi bi-arrow-clockwise"></i> Restablecer
      </a>

      <?php if (!empty($cursoId)): ?>
        <input type="hidden" name="pagina" value="<?= (int)($pagina ?? 1) ?>">
      <?php endif; ?>
    </form>

    <div class="card-glass">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:12px;">
        <h5 class="m-0 font-weight-bold">Lista de estudiantes</h5>

        <div class="mini-actions">
          <button type="button" class="btn-mini" id="btnMarcarTodos">
            <i class="bi bi-check2-circle"></i> Todos presentes
          </button>
          <button type="button" class="btn-mini" id="btnMarcarNadie">
            <i class="bi bi-x-circle"></i> Todos ausentes
          </button>
        </div>
      </div>

      <form method="POST" id="formAsistencia">
        <input type="hidden" name="curso" value="<?= (int)($cursoId ?? 0) ?>">
        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

        <div class="table-responsive">
          <table class="table table-borderless text-center mb-0">
            <thead>
              <tr>
                <th style="width:90px;">ID</th>
                <th>Estudiante</th>
                <th>Correo</th>
                <th>Curso</th>
                <th style="width:260px;">Estado</th>
              </tr>
            </thead>

            <tbody>
            <?php if (!empty($alumnos)): ?>
              <?php foreach ($alumnos as $al):
                $idEst = (int)$al['Id_Estudiante'];
                $valor = isset($asistenciaMap[$idEst]) ? (int)$asistenciaMap[$idEst] : 1; // default presente
              ?>
                <tr>
                  <td>
                    <?= $idEst ?>
                    <input type="hidden" name="estudiante_id[]" value="<?= $idEst ?>">
                  </td>
                  <td><?= htmlspecialchars($al['Nombre']) ?></td>
                  <td><?= htmlspecialchars($al['Email']) ?></td>
                  <td><?= htmlspecialchars($al['Curso']) ?></td>
                  <td>
                    <div class="estado-toggle" data-id="<?= $idEst ?>">
                      <button type="button"
                        class="toggle-btn btn-presente <?= $valor ? 'active' : '' ?>">
                        Presente
                      </button>
                      <button type="button"
                        class="toggle-btn btn-ausente <?= !$valor ? 'active' : '' ?>">
                        Ausente
                      </button>
                      <input type="hidden" name="estado[<?= $idEst ?>]" id="estado-<?= $idEst ?>" value="<?= $valor ?>">
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-muted py-4">
                  Selecciona un curso y fecha para cargar la lista.
                </td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="sticky-actions">
          <div class="text-muted">
            <?php if(!empty($alumnos)): ?>
              Mostrando <?= count($alumnos) ?> estudiantes
            <?php else: ?>
              —
            <?php endif; ?>
          </div>

          <button type="submit" class="btn-guardar">
            <i class="bi bi-save"></i> Guardar
          </button>
        </div>
      </form>

      <!-- PAGINACIÓN (si el controller manda $totalPaginas) -->
      <?php if (!empty($cursoId) && (int)($totalPaginas ?? 1) > 1): ?>
        <nav class="mt-3">
          <ul class="pagination justify-content-center mb-0">
            <?php
              $pActual = (int)($pagina ?? 1);
              $f = $fecha ?? date('Y-m-d');
              for ($i = 1; $i <= (int)$totalPaginas; $i++):
                $qs = http_build_query(['curso'=>$cursoId,'fecha'=>$f,'pagina'=>$i]);
            ?>
              <li class="page-item <?= $i === $pActual ? 'active' : '' ?>">
                <a class="page-link" href="RegistrarAsistenciaController.php?<?= $qs ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>

      <?php if (!empty($_GET['ok'])): ?>
        <div class="alert alert-success mt-3 text-center" style="border-radius:14px;background:rgba(34,197,94,0.15);border:1px solid #22c55e;color:#22c55e;">
          <i class="bi bi-check-circle-fill"></i>
          <?= $_GET['ok'] === 'editado'
              ? '✏️ Asistencia actualizada correctamente'
              : '✅ Asistencia guardada correctamente'
          ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger mt-3 text-center" style="border-radius:14px;background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#ef4444;">
          <i class="bi bi-x-circle-fill"></i> ❌ Error al guardar la asistencia
        </div>
      <?php endif; ?>

    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Toggle presente/ausente por fila
    $('.estado-toggle .toggle-btn').click(function(){
      const box = $(this).closest('.estado-toggle');
      box.find('.toggle-btn').removeClass('active');
      $(this).addClass('active');
      const id = box.data('id');
      $('#estado-'+id).val($(this).hasClass('btn-presente') ? 1 : 0);
    });

    // Marcar todos presentes
    $('#btnMarcarTodos').click(function(){
      $('.estado-toggle').each(function(){
        const id = $(this).data('id');
        $(this).find('.btn-presente').addClass('active');
        $(this).find('.btn-ausente').removeClass('active');
        $('#estado-'+id).val(1);
      });
    });

    // Marcar todos ausentes
    $('#btnMarcarNadie').click(function(){
      $('.estado-toggle').each(function(){
        const id = $(this).data('id');
        $(this).find('.btn-ausente').addClass('active');
        $(this).find('.btn-presente').removeClass('active');
        $('#estado-'+id).val(0);
      });
    });
  </script>

</body>
</html>
