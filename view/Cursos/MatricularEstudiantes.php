<?php
// /view/Cursos/MatricularEstudiantes.php
require_once __DIR__ . '/../../model/CursoModel.php';

$nombreFiltro = $_GET['nombreEstudiante'] ?? '';
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$cursos = CursoModel::obtenerCursos();
$cursoSeleccionado = $_POST['idCursoMatricula'] ?? $_GET['idCursoMatricula'] ?? null;

$estudiantes = CursoModel::obtenerEstudiantes($nombreFiltro, $porPagina, $offset);
$totalEstudiantes = CursoModel::contarEstudiantes($nombreFiltro);
$totalPaginas = (int)ceil($totalEstudiantes / $porPagina);

foreach ($estudiantes as &$est) {
    if (!isset($est['Id_Usuario'])) $est['Id_Usuario'] = $est['id'] ?? null;
    if (!isset($est['Nombre'])) $est['Nombre'] = $est['nombre'] ?? '';
}
unset($est);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['matricular'])) {
        CursoModel::matricularEstudiantes(
            $_POST['idCursoMatricula'],
            $_POST['estudiantes'] ?? []
        );
    }

    if (isset($_POST['eliminar'])) {
        CursoModel::eliminarMatricula(
            $_POST['idCursoEliminar'],
            $_POST['idEstudianteEliminar']
        );
    }

    header("Location: ?idCursoMatricula=" . ($_POST['idCursoMatricula'] ?? '') .
           "&nombreEstudiante=" . urlencode($nombreFiltro) .
           "&pagina=" . $pagina);
    exit;
}

/**
 * Paginación: ventana de páginas con ellipsis.
 */
function build_pagination(int $page, int $total, int $window = 5): array {
    if ($total <= 1) return [];

    $half = intdiv($window, 2);
    $start = max(1, $page - $half);
    $end = min($total, $start + $window - 1);
    $start = max(1, $end - $window + 1);

    $items = [];

    if ($start > 1) {
        $items[] = 1;
        if ($start > 2) $items[] = '...';
    }

    for ($i = $start; $i <= $end; $i++) $items[] = $i;

    if ($end < $total) {
        if ($end < $total - 1) $items[] = '...';
        $items[] = $total;
    }

    return $items;
}

$paginationItems = build_pagination($pagina, $totalPaginas, 5);

function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Matricular Estudiantes</title>

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root{
      --bg:#2a2b38;
      --text:#fff;
      --muted:rgba(255,255,255,.75);
      --glass1:rgba(255,255,255,.10);
      --glass2:rgba(255,255,255,.06);
      --stroke:rgba(255,255,255,.20);
      --stroke2:rgba(255,255,255,.30);
      --shadow:0 14px 44px rgba(0,0,0,.42);
      --radius:18px;

      --danger:rgba(255,80,80,.55);
      --dangerHover:rgba(255,40,40,.75);
      --ok:rgba(40,200,90,.38);
      --okHover:rgba(40,200,90,.52);

      /* ✅ chips tamaño uniforme */
      --chipW: 170px;
      --chipH: 34px;
    }

    body{
      font-family:'Poppins',sans-serif;
      background:var(--bg);
      background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
      background-repeat:repeat;
      background-size:600px;
      color:var(--text);
      padding:40px 25px;
    }

    .page-wrap{
      max-width:1200px;
      margin:0 auto;
    }

    .page-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .btn-volver{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 18px;
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      color:var(--text);
      border-radius:14px;
      font-size:15px;
      border:1px solid var(--stroke);
      text-decoration:none;
      transition:.18s;
      box-shadow:0 10px 26px rgba(0,0,0,.22);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      line-height:1;
    }
    .btn-volver:hover{
      border-color:var(--stroke2);
      background:rgba(255,255,255,.14);
      color:var(--text);
    }
    .btn-volver i{
      font-size:16px;
      line-height:1;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      transform: translateY(1px);
    }

    h1{
      text-align:center;
      font-weight:700;
      font-size:32px;
      margin:10px 0 22px;
      text-shadow:0 2px 10px rgba(0,0,0,.35);
    }

    .glass-card{
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      border:1px solid var(--stroke);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      overflow:hidden;
    }

    .card-pad{ padding:18px; }

    /* Barra filtros */
    .filter-bar{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:center;
    }

    .filter-bar form{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:center;
      margin:0;
    }

    select, input[type="text"]{
      height:44px;
      padding:10px 14px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,.10);
      color:var(--text);
      outline:none;
      min-width: 260px;
    }
    input[type="text"]::placeholder{ color:rgba(255,255,255,.55); }
    select:focus, input[type="text"]:focus{ border-color:var(--stroke2); }

    /* FIX dropdown blanco/vacío */
    select option{
      background:#101733;
      color:#fff;
    }

    .btn-ghost{
      height:44px;
      padding:0 16px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,.14);
      color:var(--text);
      font-weight:800;
      cursor:pointer;
      transition:.18s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      white-space:nowrap;
    }
    .btn-ghost:hover{
      border-color:var(--stroke2);
      background:rgba(255,255,255,.22);
    }

    /* Tabla */
    .table-wrap{
      overflow:auto;
      max-height: 520px;
      border-radius:var(--radius);
      margin-top:14px;
    }

    table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      font-size:14px;
      min-width: 1050px;
    }

    thead th{
      position: sticky;
      top: 0;
      z-index: 3;
      background:rgba(255,255,255,0.10);
      padding:14px 12px;
      font-weight:800;
      text-align:center;
      border-bottom:1px solid rgba(255,255,255,0.18);
      white-space:nowrap;
    }

    tbody td{
      padding:12px 12px;
      text-align:center;
      border-bottom:1px solid rgba(255,255,255,0.14);
    }

    tbody tr:nth-child(even) td{ background:rgba(255,255,255,0.05); }
    tbody tr:hover td{ background:rgba(255,255,255,0.08); }

    /* Columna sticky: checkbox + estudiante */
    .sticky-col{
      position: sticky;
      left: 0;
      z-index: 4;
      background:rgba(255,255,255,0.08);
      border-right:1px solid rgba(255,255,255,0.14);
    }
    thead .sticky-col{ z-index: 6; background:rgba(255,255,255,0.12); }

    .sticky-col-2{
      position: sticky;
      left: 52px;
      z-index: 4;
      background:rgba(255,255,255,0.08);
      border-right:1px solid rgba(255,255,255,0.14);
      text-align:left;
    }
    thead .sticky-col-2{ z-index: 6; background:rgba(255,255,255,0.12); }

    .chk{
      width:18px;
      height:18px;
      cursor:pointer;
      accent-color:#4da3ff;
      transform: translateY(1px);
    }

    .student-name{
      display:inline-block;
      max-width:260px;
      overflow:hidden;
      text-overflow:ellipsis;
      white-space:nowrap;
      vertical-align:middle;
      font-weight:700;
    }

    /* Chips */
    .chips{
      display:flex;
      flex-wrap:wrap;
      gap:8px;
      justify-content:center;
    }

    /* ✅ UNIFORME PARA GRIS */
    .chip{
      width: var(--chipW) !important;
      height: var(--chipH) !important;
      display:inline-flex !important;
      align-items:center !important;
      justify-content:center !important;

      padding: 0 12px !important;
      border-radius:999px !important;
      border:1px solid rgba(255,255,255,0.18);
      background:rgba(255,255,255,.18);
      font-size:13px;
      font-weight:800;
      color:#fff;
      line-height:1;

      white-space:nowrap !important;
      overflow:hidden !important;
      text-overflow:ellipsis !important;
    }

    .chip-danger{
      background:var(--danger) !important;
      border-color:rgba(255,255,255,0.14) !important;
    }

    /* ✅ UNIFORME PARA ROJA */
    .btn-chip-danger{
      width: var(--chipW) !important;
      height: var(--chipH) !important;
      display:inline-flex !important;
      align-items:center !important;
      justify-content:center !important;

      padding: 0 12px !important;
      border-radius:999px !important;
      border:1px solid rgba(255,255,255,0.14);
      background:var(--danger);
      color:#fff;

      font-size:13px;
      font-weight:800;
      cursor:pointer;
      transition:.18s;

      white-space:nowrap !important;
      overflow:hidden !important;
      text-overflow:ellipsis !important;
      line-height:1;
    }
    .btn-chip-danger:hover{ background:var(--dangerHover); }

    .actions-bar{
      padding:14px 18px 18px;
      border-top:1px solid rgba(255,255,255,0.12);
      display:flex;
      justify-content:center;
    }

    .btn-ok{
      height:44px;
      padding:0 18px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,0.18);
      background:var(--ok);
      color:var(--text);
      font-weight:900;
      cursor:pointer;
      transition:.18s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      min-width: 280px;
      white-space:nowrap;
    }
    .btn-ok:hover{ background:var(--okHover); }

    /* Paginación (glass pill) */
    .pagination-wrap{
      display:flex;
      justify-content:center;
      margin-top:16px;
    }

    .pagination-pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 12px;
      border-radius:999px;
      border:1px solid var(--stroke);
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      box-shadow:0 10px 26px rgba(0,0,0,.22);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }

    .pagination-pill a,
    .pagination-pill span{
      border:1px solid var(--stroke);
      border-radius:999px;
      padding:8px 14px;
      color:var(--text);
      text-decoration:none;
      background:rgba(255,255,255,.06);
      transition:.18s;
      min-width:40px;
      text-align:center;
      font-weight:800;
      line-height:1;
    }

    .pagination-pill a:hover{ border-color:var(--stroke2); }
    .pagination-pill a.active{
      background:rgba(255,255,255,0.22);
      border-color:var(--stroke2);
    }
    .pagination-pill span{
      opacity:.75;
      cursor:default;
    }
    .pagination-pill a.disabled{
      opacity:.45;
      pointer-events:none;
    }

    /* Modal glass */
    .modal-content{
      background:linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.06)) !important;
      border:1px solid rgba(255,255,255,0.18) !important;
      border-radius:20px !important;
      box-shadow:var(--shadow) !important;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      color:var(--text);
    }
    .modal-header, .modal-footer{ border:none !important; }
    .modal-title{ font-weight:900; }
    .modal-footer .btn{ border-radius:14px; font-weight:900; }
    .btn-outline-light{ border-color:rgba(255,255,255,.30) !important; }
    .btn-danger{
      background:rgba(255,0,0,.55) !important;
      border-color:rgba(255,255,255,.16) !important;
    }
    .btn-danger:hover{ background:rgba(255,0,0,.70) !important; }

    @media (max-width:520px){
      body{ padding:28px 14px; }
      h1{ font-size:26px; }
      select, input[type="text"]{ min-width:220px; }
      .table-wrap{ max-height: 460px; }
    }
  </style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left" aria-hidden="true"></i>
        Volver
      </a>
      <div></div>
    </div>

    <h1><i class="bi bi-people-fill"></i> Matricular Estudiantes</h1>

    <div class="glass-card card-pad">

      <!-- FILTROS -->
      <div class="filter-bar">
        <form method="GET">
          <select name="idCursoMatricula" onchange="this.form.submit()">
            <option value="">Seleccione un curso</option>
            <?php foreach ($cursos as $c): ?>
              <option value="<?= esc($c['id']) ?>" <?= ((string)$cursoSeleccionado === (string)$c['id']) ? 'selected' : '' ?>>
                <?= esc($c['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" name="nombreEstudiante" value="<?= esc($nombreFiltro) ?>">
          <input type="hidden" name="pagina" value="1">
        </form>

        <form method="GET">
          <input type="text" name="nombreEstudiante" placeholder="Buscar estudiante..." value="<?= esc($nombreFiltro) ?>">
          <input type="hidden" name="idCursoMatricula" value="<?= esc($cursoSeleccionado) ?>">
          <input type="hidden" name="pagina" value="1">
          <button class="btn-ghost" type="submit">
            <i class="bi bi-search" aria-hidden="true"></i> Buscar
          </button>
        </form>
      </div>

      <form method="POST">
        <input type="hidden" name="idCursoMatricula" value="<?= esc($cursoSeleccionado) ?>">

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th class="sticky-col" style="width:52px;">✔</th>
                <th class="sticky-col-2">Estudiante</th>
                <th>Cursos actuales</th>
                <th>Eliminar</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($estudiantes as $e): ?>
                <?php
                  $idEst = $e['Id_Usuario'];
                  $cursosEst = CursoModel::obtenerCursosPorEstudiante($idEst);
                ?>
                <tr>
                  <td class="sticky-col">
                    <input class="chk" type="checkbox" name="estudiantes[]" value="<?= esc($idEst) ?>">
                  </td>

                  <td class="sticky-col-2">
                    <span class="student-name"><?= esc($e['Nombre']) ?></span>
                  </td>

                  <td>
                    <div class="chips">
                      <?php foreach ($cursosEst as $cNom): ?>
                        <span class="chip" title="<?= esc($cNom) ?>"><?= esc($cNom) ?></span>
                      <?php endforeach; ?>
                      <?php if (empty($cursosEst)): ?>
                        <span class="chip" style="opacity:.70;">—</span>
                      <?php endif; ?>
                    </div>
                  </td>

                  <td>
                    <div class="chips">
                      <?php foreach ($cursosEst as $cursoNom): ?>
                        <?php $idCurso = CursoModel::obtenerCursoIdPorNombre($cursoNom); ?>
                        <button
                          type="button"
                          class="btn-chip-danger"
                          onclick="confirmarEliminar(<?= (int)$idCurso ?>, <?= (int)$idEst ?>)"
                          aria-label="Eliminar <?= esc($cursoNom) ?>"
                          title="<?= esc($cursoNom) ?>"
                        >
                          <?= esc($cursoNom) ?>
                        </button>
                      <?php endforeach; ?>
                      <?php if (empty($cursosEst)): ?>
                        <span class="chip chip-danger" style="opacity:.55;">—</span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if ($cursoSeleccionado): ?>
          <div class="actions-bar">
            <button type="submit" name="matricular" class="btn-ok">
              <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
              Matricular seleccionados
            </button>
          </div>
        <?php endif; ?>
      </form>

      <?php if ($totalPaginas > 1): ?>
        <div class="pagination-wrap">
          <div class="pagination-pill">
            <?php
              $base = "?idCursoMatricula=" . urlencode((string)$cursoSeleccionado)
                    . "&nombreEstudiante=" . urlencode((string)$nombreFiltro)
                    . "&pagina=";
            ?>
            <a class="<?= $pagina <= 1 ? 'disabled' : '' ?>" href="<?= $base . max(1, $pagina - 1) ?>" aria-label="Anterior">
              «
            </a>

            <?php foreach ($paginationItems as $it): ?>
              <?php if ($it === '...'): ?>
                <span>…</span>
              <?php else: ?>
                <a class="<?= ((int)$it === (int)$pagina) ? 'active' : '' ?>" href="<?= $base . (int)$it ?>">
                  <?= (int)$it ?>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>

            <a class="<?= $pagina >= $totalPaginas ? 'disabled' : '' ?>" href="<?= $base . min($totalPaginas, $pagina + 1) ?>" aria-label="Siguiente">
              »
            </a>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!-- MODAL CONFIRMACIÓN -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar eliminación</h5>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas eliminar este curso al estudiante?
        </div>
        <div class="modal-footer justify-content-end">
          <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger btn-sm" onclick="enviarEliminar()">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

  <form method="POST" id="formEliminar">
    <input type="hidden" name="idCursoEliminar" id="idCursoEliminar">
    <input type="hidden" name="idEstudianteEliminar" id="idEstudianteEliminar">
    <input type="hidden" name="eliminar" value="1">
    <input type="hidden" name="idCursoMatricula" value="<?= esc($cursoSeleccionado) ?>">
  </form>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

  <script>
    function confirmarEliminar(idCurso,idEst){
      document.getElementById('idCursoEliminar').value=idCurso;
      document.getElementById('idEstudianteEliminar').value=idEst;
      $('#confirmModal').modal('show');
    }
    function enviarEliminar(){
      document.getElementById('formEliminar').submit();
    }
  </script>
</body>
</html>
