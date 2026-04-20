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
  margin-bottom:20px;
}

.btn-volver {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  background: linear-gradient(180deg, var(--glass1), var(--glass2));
  color: var(--text) !important;
  border-radius: 14px;
  font-size: 15px;
  border: 1px solid var(--stroke);
  text-decoration: none !important;
  transition: .18s;
  box-shadow: 0 10px 26px rgba(0,0,0,.22);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}
.btn-volver:hover {
  border-color: var(--stroke2);
  background: rgba(255,255,255,.14);
  transform: translateY(-1px);
}

/* ESTILO DEL BUSCADOR */
.search-container {
  max-width: 500px;
  margin: 0 auto 30px;
  position: relative;
}
.search-input {
  width: 100%;
  padding: 14px 20px 14px 45px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid var(--stroke);
  border-radius: 15px;
  color: #fff;
  font-family: 'Poppins', sans-serif;
  backdrop-filter: blur(10px);
  outline: none;
  transition: 0.3s;
}
.search-input:focus {
  background: rgba(255, 255, 255, 0.12);
  border-color: var(--stroke2);
  box-shadow: 0 0 15px rgba(255,255,255,0.05);
}
.search-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--muted);
  font-size: 18px;
}

h1{
  text-align:center;
  font-weight:800;
  font-size:32px;
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

/* GRID */
.grid{
  display:grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap:20px;
}

/* CARD */
.course-card{
  padding:18px;
  border-radius:var(--radius);
  border:1px solid rgba(255,255,255,0.18);
  background:rgba(255,255,255,0.06);
  backdrop-filter: blur(12px);
  box-shadow:0 10px 28px rgba(0,0,0,0.30);
  transition:.2s;
  display:flex;
  flex-direction:column;
  min-height: 170px;
}
.course-card:hover{
  transform: translateY(-5px);
  background:rgba(255,255,255,0.10);
}

.course-title{
  font-size:18px;
  font-weight:800;
  margin:0 0 6px;
}

.course-desc{
  margin:0;
  font-size:13px;
  color:rgba(255,255,255,.78);
  line-height:1.4;
  margin-bottom: 15px;
}

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
  font-weight:700;
  transition:.18s;
}
.btn-ir:hover{
  background:rgba(255,255,255,0.22);
}

#no-results {
  display: none;
  text-align: center;
  padding: 40px;
  font-size: 18px;
  opacity: 0.7;
}

@media (max-width:520px){
  h1{ font-size:26px; }
}
</style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
      </a>
    </div>

    <h1><i class="bi bi-journal-bookmark-fill"></i> Seleccione un Curso</h1>

    <div class="search-container">
      <i class="bi bi-search search-icon"></i>
      <input type="text" id="searchInput" class="search-input" placeholder="Buscar curso por nombre o descripción...">
    </div>

    <?php if (!empty($cursos)): ?>
      <div class="grid" id="courseGrid">
        <?php foreach ($cursos as $curso): ?>
          <div class="course-card" data-nombre="<?= strtolower(esc($curso['nombre'])) ?>" data-desc="<?= strtolower(esc($curso['descripcion'] ?? '')) ?>">
            <div class="course-title"><?= esc($curso['nombre'] ?? '—') ?></div>
            <p class="course-desc"><?= esc($curso['descripcion'] ?? 'Sin descripción') ?></p>

            <a class="btn-ir" href="/Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=<?= urlencode((string)($curso['id'] ?? '')) ?>">
              <i class="bi bi-folder2-open"></i> Administrar Material
            </a>
          </div>
        <?php endforeach; ?>
      </div>
      
      <div id="no-results" class="glass-card">
        <i class="bi bi-exclamation-circle"></i> No se encontraron cursos que coincidan con tu búsqueda.
      </div>

    <?php else: ?>
      <div class="glass-card" style="text-align:center; padding:20px;">
        No hay cursos registrados.
      </div>
    <?php endif; ?>

  </div>

  <script>
    // LÓGICA DE FILTRADO EN TIEMPO REAL
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const term = e.target.value.toLowerCase().trim();
      const cards = document.querySelectorAll('.course-card');
      const grid = document.getElementById('courseGrid');
      const noResults = document.getElementById('no-results');
      let hasResults = false;

      cards.forEach(card => {
        const nombre = card.getAttribute('data-nombre');
        const desc = card.getAttribute('data-desc');

        if (nombre.includes(term) || desc.includes(term)) {
          card.style.display = 'flex';
          hasResults = true;
        } else {
          card.style.display = 'none';
        }
      });

      // Mostrar/Ocultar mensaje de "no hay resultados"
      if (hasResults) {
        grid.style.display = 'grid';
        noResults.style.display = 'none';
      } else {
        grid.style.display = 'none';
        noResults.style.display = 'block';
      }
    });
  </script>
</body>
</html>