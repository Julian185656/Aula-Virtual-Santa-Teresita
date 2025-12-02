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
<meta charset="UTF-8" />
<title>Moderación de Foro</title>
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg, #1f272b 0%, #2a353c 100%);
    color: #fff;
    margin: 0;
    padding: 20px;
}

.page-title {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 30px;
    color: #ffffffff;
}

/* FILTRO */
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

#filtro::placeholder {
    color: #ddd;
}

#filtro:focus {
    background: rgba(255,255,255,0.2);
    box-shadow: 0 0 10px rgba(255,255,255,0.2);
}

/* GRID DE CURSOS */
#gridCursos {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    padding-bottom: 40px;
}

/* TARJETA */
.item-curso .card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.item-curso .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}

.item-curso .card h5 {
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.item-curso .card p {
    font-size: 0.88rem;
    color: #ccc;
    flex-grow: 1;
    margin-bottom: 15px;
}

/* BOTONES */
.item-curso .btn-foro {
    padding: 8px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ffffffff, #ffffffff);
    color: #1f272b;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: 0.3s ease;
}

.item-curso .btn-foro:hover {
    background: linear-gradient(135deg, #ffffffff, #ffffffff);
    color: #1f272b;
}

/* BADGE */
.badge-id {
    font-size: 0.7rem;
    background: rgba(255,255,255,0.15);
    color: #fff;
    padding: 4px 8px;
    border-radius: 50px;
}

/* BOTÓN VOLVER */
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
    background: rgba(255,255,255,0.3);
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
        <div class="card h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0"><?= htmlspecialchars($nom) ?></h5>
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

<div style="display:flex; justify-content:center; align-items:center; margin-top:20px;">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">
        <i class="fa-solid fa-house"></i> Volver al Home
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
