<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
if (!isset($_SESSION['id_usuario']) || !is_string($rol) || strcasecmp($rol, 'Administrador') !== 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$cursos = [];
if (class_exists('CursoModel')) {
    if (method_exists('CursoModel', 'obtenerCursosAdmin')) {
        $cursos = CursoModel::obtenerCursosAdmin();
    } elseif (method_exists('CursoModel', 'obtenerCursos')) {
        $cursos = CursoModel::obtenerCursos();
    }
}

function _toLower($s) {
    return function_exists('mb_strtolower') ? mb_strtolower((string)$s, 'UTF-8') : strtolower((string)$s);
}
function esc($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Moderación de Foro</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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
.btn-volver:hover{ border-color:var(--stroke2); background:rgba(255,255,255,.14); color:var(--text); }
.btn-volver i{ font-size:16px; transform: translateY(1px); }

h1{
  text-align:center;
  font-weight:700;
  font-size:32px;
  margin:10px 0 18px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

.search-wrap{
  padding:16px;
  margin:0 auto 18px;
}

#filtro{
  width:100%;
  max-width:720px;
  padding:12px 16px;
  border-radius:14px;
  border:1px solid var(--stroke);
  outline:none;
  background:rgba(255,255,255,0.08);
  color:var(--text);
  font-size:15px;
}
#filtro::placeholder{ color:rgba(255,255,255,.65); }
#filtro:focus{ border-color:var(--stroke2); }

.grid{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap:18px;
}

.card-curso{
  padding:18px;
  border-radius:18px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,0.06);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow:0 10px 30px rgba(0,0,0,0.30);
  transition:.22s;
  min-height:180px;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}
.card-curso:hover{
  transform: translateY(-6px);
  box-shadow:0 15px 40px rgba(0,0,0,0.45);
  border-color:var(--stroke2);
}

.card-top{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:10px;
  margin-bottom:10px;
}
.card-curso h5{
  margin:0;
  font-weight:700;
  font-size:18px;
  line-height:1.2;
}
.badge-id{
  font-size:12px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,0.10);
  color:var(--text);
  white-space:nowrap;
}

.card-curso p{
  margin:0 0 14px;
  color:rgba(255,255,255,.75);
  font-size:13px;
  line-height:1.35;
  overflow:hidden;
  display:-webkit-box;
  -webkit-line-clamp:3;
  -webkit-box-orient:vertical;
}

.btn-foro{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:10px 14px;
  border-radius:12px;
  background:#ffffff;
  color:#1f272b;
  font-weight:800;
  font-size:14px;
  text-decoration:none;
  border:1px solid rgba(255,255,255,0.25);
  transition:.18s;
}
.btn-foro:hover{
  background:#f0f0f0;
  color:#1f272b;
}

.empty{
  padding:18px;
  text-align:center;
  color:rgba(255,255,255,.75);
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
        <i class="fa-solid fa-circle-arrow-left" aria-hidden="true"></i>
        Volver
      </a>
      <div></div>
    </div>

    <h1><i class="fa-solid fa-comments"></i> Moderación de Foro</h1>

    <div class="glass-card search-wrap" style="display:flex; justify-content:center;">
      <input type="text" id="filtro" placeholder="Buscar curso por nombre...">
    </div>

    <?php if (!empty($cursos)): ?>
      <div class="grid">
        <?php foreach ($cursos as $c):
          $id   = $c['Id_Curso']     ?? $c['id']          ?? $c['ID'] ?? null;
          $nom  = $c['Nombre']       ?? $c['nombre']      ?? $c['titulo'] ?? 'Sin nombre';
          $desc = $c['Descripcion']  ?? $c['descripcion'] ?? $c['detalle'] ?? 'Sin descripción';
        ?>
          <div class="item-curso" data-nombre="<?= esc(_toLower($nom)) ?>">
            <div class="card-curso">
              <div>
                <div class="card-top">
                  <h5><?= esc($nom) ?></h5>
                  <span class="badge-id">ID: <?= esc($id) ?></span>
                </div>
                <p><?= esc($desc) ?></p>
              </div>

              <a class="btn-foro"
                 href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoCurso.php?idCurso=<?= urlencode((string)$id) ?>&modo=admin">
                <i class="fa-solid fa-gavel"></i>
                Moderar foro
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="glass-card empty">No hay cursos registrados.</div>
    <?php endif; ?>

  </div>

<script>
const filtro = document.getElementById('filtro');
const items  = document.querySelectorAll('.item-curso');
filtro.addEventListener('input', (e) => {
  const q = (e.target.value || '').trim().toLowerCase();
  items.forEach(it => {
    it.style.display = it.dataset.nombre.includes(q) ? '' : 'none';
  });
});
</script>
</body>
</html>
