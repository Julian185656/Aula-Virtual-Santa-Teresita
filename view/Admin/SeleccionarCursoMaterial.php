<?php
// /view/Admin/SeleccionarCursoMaterial.php
session_start();

$rol = null;
if (isset($_SESSION['usuario']['rol'])) {
    $rol = strtolower($_SESSION['usuario']['rol']);
} elseif (isset($_SESSION['rol'])) {
    $rol = strtolower($_SESSION['rol']);
}

if ($rol !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";
$cursos = CursoModel::obtenerCursos();

function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Seleccione un Curso</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
  font-family:'Poppins', sans-serif;
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

/* ✅ MISMO VOLVER QUE TU BASE */
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
.btn-volver i{ transform: translateY(1px); }

h1{
  text-align:center;
  font-weight:800;
  font-size:32px;
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}
h1 i{ margin-right:8px; opacity:.95; }

.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* GRID */
.grid{
  display:grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap:18px;
}

/* CARD */
.course-card{
  padding:18px;
  border-radius:var(--radius);
  border:1px solid rgba(255,255,255,0.18);
  background:rgba(255,255,255,0.06);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  box-shadow:0 10px 28px rgba(0,0,0,0.30);
  transition:.18s;
  display:flex;
  flex-direction:column;
  min-height: 170px;
}
.course-card:hover{
  transform: translateY(-4px);
  box-shadow:0 14px 34px rgba(0,0,0,0.40);
}

.course-title{
  font-size:18px;
  font-weight:800;
  margin:0 0 6px;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
}

.course-desc{
  margin:0;
  font-size:13px;
  color:rgba(255,255,255,.78);
  line-height:1.35;
  min-height: 38px;
  overflow:hidden;
  display:-webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* BOTÓN (misma línea que ghost) */
.btn-ir{
  margin-top:auto;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  height:44px;
  width:100%;
  border-radius:14px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,0.14);
  color:var(--text);
  text-decoration:none;
  font-weight:800;
  transition:.18s;
  white-space:nowrap;
}
.btn-ir:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,0.22);
}

.empty{
  text-align:center;
  padding:18px;
  color:rgba(255,255,255,.80);
}

@media (max-width:520px){
  body{ padding:28px 14px; }
  h1{ font-size:26px; }
}
</style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="bi bi-arrow-left-circle-fill" aria-hidden="true"></i>
        Volver
      </a>
      <div></div>
    </div>

    <h1><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i>Seleccione un Curso</h1>

    <?php if (!empty($cursos)): ?>
      <div class="grid">
        <?php foreach ($cursos as $curso): ?>
          <div class="course-card">
            <div class="course-title" title="<?= esc($curso['nombre'] ?? '') ?>">
              <?= esc($curso['nombre'] ?? '—') ?>
            </div>
            <p class="course-desc" title="<?= esc($curso['descripcion'] ?? '') ?>">
              <?= esc($curso['descripcion'] ?? 'Sin descripción') ?>
            </p>

            <a class="btn-ir"
               href="/Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=<?= urlencode((string)($curso['id'] ?? '')) ?>">
              <i class="bi bi-folder2-open" aria-hidden="true"></i>
              Administrar Material
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="glass-card empty">
        No hay cursos registrados.
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
