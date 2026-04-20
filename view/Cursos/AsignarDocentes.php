<?php
// /view/Cursos/AsignarDocentes.php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();
$cursos   = CursoModel::obtenerCursos();
$asignacionesActuales = CursoModel::obtenerAsignaciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarProfesores'])) {
    CursoController::asignarDocentes();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asignar Profesores</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>


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
  background:var(--bg);
  background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
  background-size:600px;
  background-repeat:repeat;
  color:var(--text);
  padding:40px 25px;
}

.page-header a {
  text-decoration: none;
  color: var(--text);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 0px;
  padding:10px 18px;
  background:linear-gradient(180deg,var(--glass1),var(--glass2));
  border-radius:14px;
  border:1px solid var(--stroke);
  transition:.2s;
}
.page-header a:hover { color:#4da3ff; }

h1 { text-align:center; margin-bottom:20px; }

.search-bar {
  display:flex;
  justify-content:center;
  margin-bottom:20px;
}
.search-bar input {
  width: 300px;
  padding:10px 15px;
  border-radius:10px;
  border:1px solid #444;
  background:#2a2b38;
  color:#fff;
  font-size:16px;
}
.search-bar input::placeholder { color:#bbb; }

.container { display:flex; flex-wrap:wrap; gap:20px; justify-content:center; }

.card {
  background: #2a2b38;
  border-radius: 15px;
  padding: 20px;
  width: 300px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  transition: transform .2s;
}
.card:hover { transform: translateY(-5px); }
.card h3 { margin-top:0; margin-bottom:15px; font-size:20px; }

.docente-list { list-style:none; padding:0; margin:0; }
.docente-list li {
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:10px;
  padding:5px 10px;
  background: rgba(255,255,255,0.05);
  border-radius:8px;
}
.docente-list li input[type=checkbox] { transform: scale(1.2); accent-color:#4da3ff; }

.btn-guardar {
  margin-top:20px;
  display:inline-flex;
  padding:12px 25px;
  font-size:16px;
  background:#555;
  color:#fff;
  border:none;
  border-radius:10px;
  cursor:pointer;
  font-weight:600;
  transition:.2s;
  text-align:center;
}
.btn-guardar:hover { background:#777; }

.toast {
  position:fixed;
  bottom:20px;
  right:20px;
  background:#333;
  padding:15px 25px;
  border-radius:8px;
  display:none;
}
</style>
</head>
<body>

<div class="page-header">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
</div>

<h1>Asignar Profesores</h1>

<div class="search-bar">
    <input type="text" id="searchInput" placeholder="Buscar curso...">
</div>

<form method="POST" id="formAsignaciones">
<div class="container" id="cardsContainer">
    <?php foreach($cursos as $c): ?>
        <div class="card" data-name="<?= strtolower($c['nombre']) ?>">
            <h3><?= htmlspecialchars($c['nombre']) ?></h3>
            <ul class="docente-list">
                <?php foreach($docentes as $d): ?>
                    <li>
                        <?= htmlspecialchars($d['nombre']) ?>
                        <input type="checkbox" name="asignaciones[<?= $c['id'] ?>][]" value="<?= $d['id'] ?>"
                        <?php if(!empty($asignacionesActuales[$c['id']]) && in_array($d['id'],$asignacionesActuales[$c['id']])) echo 'checked'; ?>>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<div style="text-align:center; margin-top:20px;">
    <button type="submit" class="btn-guardar">Guardar Asignaciones</button>
</div>
<input type="hidden" name="asignarProfesores" value="1">
</form>

<div class="toast" id="toast">Asignaciones guardadas correctamente</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#searchInput').on('input', function(){
    const query = $(this).val().toLowerCase();
    $('#cardsContainer .card').each(function(){
        const name = $(this).data('name');
        $(this).toggle(name.includes(query));
    });
});
</script>

</body>
</html>