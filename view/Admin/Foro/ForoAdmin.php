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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Moderación de Foro</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    font-family: 'Montserrat', sans-serif !important;
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
    text-align: center;
}



.page-title {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 25px;
}


#filtro {
    display: block;
    margin: 0 auto 25px auto;
    max-width: 400px;
    width: 100%;
    padding: 10px 15px;
    border-radius: 12px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 0.95rem;
    outline: none;
    transition: 0.3s ease;
}
#filtro::placeholder { color: #ddd; }
#filtro:focus { background: rgba(255,255,255,0.2); box-shadow: 0 0 10px rgba(255,255,255,0.2); }


#gridCursos {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    padding-bottom: 40px;
}


.item-curso .card {
    background: rgba(255,255,255,0.08);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 180px;
}

.item-curso .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
}

.item-curso .card h5 {
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.2rem;
}

.item-curso .card p {
    font-size: 0.9rem;
    color: #ccc;
    flex-grow: 1;
    margin-bottom: 15px;
}


.item-curso .btn-foro {
    padding: 8px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ffffff, #ffffff);
    color: #1f272b;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: 0.3s ease;
}

.item-curso .btn-foro:hover {
    background: linear-gradient(135deg, #ffffff, #ffffff);
    color: #1f272b;
}


.badge-id {
    font-size: 0.75rem;
    background: rgba(255,255,255,0.15);
    color: #fff;
    padding: 4px 8px;
    border-radius: 50px;
}


.btn-secondary {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    font-weight: 500;
    padding: 10px 22px;
    border-radius: 12px;
    transition: 0.3s ease;
}
.btn-secondary:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>
<body>

<h3 class="page-title"><i class="fa-solid fa-comments"></i> Moderación de Foro</h3>

<input type="text" id="filtro" placeholder="Buscar curso por nombre...">

<div id="gridCursos">
<?php if (!empty($cursos)): ?>
    <?php foreach ($cursos as $c):
        $id   = $c['Id_Curso']     ?? $c['id']          ?? $c['ID'] ?? null;
        $nom  = $c['Nombre']       ?? $c['nombre']      ?? $c['titulo'] ?? 'Sin nombre';
        $desc = $c['Descripcion']  ?? $c['descripcion'] ?? $c['detalle'] ?? 'Sin descripción';
    ?>
    <div class="item-curso" data-nombre="<?= htmlspecialchars(_toLower($nom)) ?>">
        <div class="card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5><?= htmlspecialchars($nom) ?></h5>
                <span class="badge badge-id">ID: <?= htmlspecialchars($id) ?></span>
            </div>
            <p><?= htmlspecialchars($desc) ?></p>
            <a class="btn-foro" href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoCurso.php?idCurso=<?= urlencode((string)$id) ?>&modo=admin">
                <i class="fa-solid fa-gavel"></i> Moderar foro
            </a>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info text-center w-100">No hay cursos registrados.</div>
<?php endif; ?>
</div>

<div style="display:flex; justify-content:center; margin-top:20px;">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">
        <i class="fa-solid fa-house"></i> Volver
    </a>
</div>

<script>
const filtro = document.getElementById('filtro');
const items  = document.querySelectorAll('.item-curso');
filtro.addEventListener('input', e => {
    const q = (e.target.value || '').trim().toLowerCase();
    items.forEach(it => {
        it.style.display = it.dataset.nombre.includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
