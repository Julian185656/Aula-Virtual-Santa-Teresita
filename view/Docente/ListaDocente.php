<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/AlumnoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MedicoModel.php";

if (!isset($_SESSION['id_usuario']) || ($_SESSION['rol'] ?? '') !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/index.php?error=NoAutorizado");
    exit();
}

$model = new AlumnoModel($pdo);
$medModel = new MedicoModel($pdo);

$docenteId = $_SESSION['id_usuario'];

$limit  = 10;
$page   = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$search = trim($_GET['search'] ?? '');
$offset = ($page - 1) * $limit;

$alumnos       = $model->obtenerAlumnosDeDocentePaginado($docenteId, $limit, $offset, $search);
$totalAlumnos  = $model->contarAlumnos($docenteId, $search);
$totalPaginas  = (int)ceil($totalAlumnos / $limit);

function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

/**
 * Paginación corta con ellipsis
 */
function build_pagination(int $page, int $total, int $window = 5): array {
    if ($total <= 1) return [];

    $half  = intdiv($window, 2);
    $start = max(1, $page - $half);
    $end   = min($total, $start + $window - 1);
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

$paginationItems = build_pagination($page, $totalPaginas, 5);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Alumnos Asignados</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

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
}

body{
  font-family:'Poppins',sans-serif;
  font-size:15px;
  color:var(--text);
  padding:40px 25px;
  background:var(--bg);
  background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
  background-repeat:repeat;
  background-size:600px;
  overflow-x:hidden;
}

.page-wrap{ max-width:1200px; margin:0 auto; }

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}

/* ==================== BOTÓN VOLVER (ESTÁNDAR) ==================== */
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
.btn-volver:hover{ border-color:var(--stroke2); background:rgba(255,255,255,.14); color:var(--text); }
.btn-volver i{ transform:translateY(1px); }

h1{
  text-align:center;
  font-weight:700;
  font-size:32px;
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

/* ==================== GLASS CARD ==================== */
.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* Buscador */
.search-wrap{
  padding:16px;
  margin-bottom:16px;
}
.search-form{
  display:flex;
  justify-content:center;
  align-items:center;
  gap:12px;
  flex-wrap:wrap;
  margin:0;
}
.search-form input{
  flex:1 1 320px;
  min-width:240px;
  max-width:720px;
  padding:12px 16px;
  border-radius:14px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,.10);
  color:var(--text);
  outline:none;
}
.search-form input::placeholder{ color:rgba(255,255,255,.60); }
.search-form input:focus{ border-color:var(--stroke2); }
.search-form button{
  height:46px;
  padding:0 18px;
  border-radius:14px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,.14);
  color:var(--text);
  font-weight:800;
  transition:.18s;
  display:inline-flex;
  gap:10px;
  align-items:center;
}
.search-form button:hover{ border-color:var(--stroke2); background:rgba(255,255,255,.22); }

/* Tabla */
.table-card{ padding:18px; }
.table-responsive{ border-radius:var(--radius); overflow:auto; }

table{
  width:100%;
  border-collapse:collapse;
  min-width:820px;
  color:var(--text);
}
thead tr{ background:rgba(255,255,255,.10); }
th,td{ padding:14px 12px; text-align:center; vertical-align:middle; }
tbody tr:nth-child(even){ background:rgba(255,255,255,.05); }
tbody tr:hover{ background:rgba(255,255,255,.08); }
td.left, th.left{ text-align:left; }

/* Botón info (más “glass”) */
.btn-info-circle{
  width:42px; height:42px;
  border-radius:999px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border:1px solid var(--stroke);
  background:rgba(110,200,255,.22);
  color:#fff;
  transition:.18s;
}
.btn-info-circle:hover{
  border-color:var(--stroke2);
  background:rgba(110,200,255,.35);
}

/* Paginación pill */
.pagination-wrap{
  display:flex;
  justify-content:center;
  margin-top:14px;
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
.pagination-pill span{ opacity:.75; }
.pagination-pill a.disabled{ opacity:.45; pointer-events:none; }

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

.form-control, textarea{
  border-radius:14px !important;
  background:rgba(255,255,255,.10) !important;
  border:1px solid rgba(255,255,255,.22) !important;
  color:#fff !important;
}
.form-control:focus, textarea:focus{
  border-color:var(--stroke2) !important;
  box-shadow:none !important;
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

    <h1><i class="fa-solid fa-users"></i> Alumnos Asignados</h1>

    <!-- BUSCADOR -->
    <div class="glass-card search-wrap">
      <form class="search-form" method="get">
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Buscar estudiante...">
        <input type="hidden" name="page" value="1">
        <button type="submit"><i class="bi bi-search"></i> Buscar</button>
      </form>
    </div>

    <!-- TABLA -->
    <div class="glass-card table-card">
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th class="left">Nombre</th>
              <th class="left">Correo</th>
              <th style="width:120px;">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($alumnos)): ?>
              <?php foreach ($alumnos as $alumno): ?>
                <tr>
                  <td class="left"><?= esc($alumno['nombre'] ?? '—') ?></td>
                  <td class="left"><?= esc($alumno['correo'] ?? '—') ?></td>
                  <td>
                    <button
                      class="btn-info-circle"
                      type="button"
                      data-bs-toggle="modal"
                      data-bs-target="#medicoModal"
                      data-id="<?= (int)($alumno['id'] ?? 0) ?>"
                      aria-label="Ver información médica"
                    >
                      <i class="fas fa-notes-medical"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" style="opacity:.75;">No hay alumnos para mostrar.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- PAGINACIÓN -->
      <?php if ($totalPaginas > 1): ?>
        <div class="pagination-wrap">
          <div class="pagination-pill">
            <?php
              $base = "?search=" . urlencode($search) . "&page=";
            ?>
            <a class="<?= $page <= 1 ? 'disabled' : '' ?>" href="<?= $base . max(1, $page - 1) ?>" aria-label="Anterior">«</a>

            <?php foreach ($paginationItems as $it): ?>
              <?php if ($it === '...'): ?>
                <span>…</span>
              <?php else: ?>
                <a class="<?= ((int)$it === (int)$page) ? 'active' : '' ?>" href="<?= $base . (int)$it ?>">
                  <?= (int)$it ?>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>

            <a class="<?= $page >= $totalPaginas ? 'disabled' : '' ?>" href="<?= $base . min($totalPaginas, $page + 1) ?>" aria-label="Siguiente">»</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- MODAL INFORMACIÓN MÉDICA -->
  <div class="modal fade" id="medicoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Información Médica</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <form id="form-medico">
          <div class="modal-body">
            <input type="hidden" id="medico-id" name="idEstudiante">

            <label class="mb-1">Contacto</label>
            <input class="form-control mb-2" id="alergias" name="alergias">

            <label class="mb-1">Medicamentos</label>
            <input class="form-control mb-2" id="medicamentos" name="medicamentos">

            <label class="mb-1">Enfermedades Crónicas</label>
            <input class="form-control mb-2" id="enfermedades" name="enfermedades">

            <label class="mb-1">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </form>

      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const medicoModal = document.getElementById('medicoModal');

medicoModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  const id = button.getAttribute('data-id');
  document.getElementById('medico-id').value = id;

  fetch(`/Aula-Virtual-Santa-Teresita/controller/MedicoController.php?id=${id}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('alergias').value = data?.Alergias || '';
      document.getElementById('medicamentos').value = data?.Medicamentos || '';
      document.getElementById('enfermedades').value = data?.EnfermedadesCronicas || '';
      document.getElementById('observaciones').value = data?.Observaciones || '';
    })
    .catch(() => {
      document.getElementById('alergias').value = '';
      document.getElementById('medicamentos').value = '';
      document.getElementById('enfermedades').value = '';
      document.getElementById('observaciones').value = '';
    });
});

document.getElementById('form-medico').addEventListener('submit', e => {
  e.preventDefault();
  const formData = new FormData(e.target);

  fetch('/Aula-Virtual-Santa-Teresita/controller/MedicoController.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(resp => {
    alert(resp.success ? "Información guardada." : "Error al guardar.");
  })
  .catch(() => alert("Error al guardar."));
});
</script>

</body>
</html>
