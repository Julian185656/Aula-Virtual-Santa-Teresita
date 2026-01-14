<?php
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Seleccione un Curso</title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* ================= BASE ================= */
body{
    font-family: 'Montserrat', sans-serif;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    color: #fff;
    padding: 40px 15px;
    text-align: center;
}

/* ================= TÍTULO ================= */
h1 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 35px;
}
h1 i {
    font-size: 42px;
    margin-bottom: 10px;
}

/* ================= GRID ================= */
.container-cursos {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
    padding: 10px;
}

/* ================= CARD ================= */
.card-glass {
    background: rgba(255,255,255,0.07);
    border-radius: 18px;
    padding: 25px 20px;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    backdrop-filter: blur(12px);
    transition: 0.25s ease;
}
.card-glass:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.45);
}

.card-glass h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 8px;
}
.card-glass p {
    font-size: 14px;
    opacity: .85;
    min-height: 35px;
}

/* ================= BOTONES ================= */
.btn-ir {
    display: inline-block;
    width: 100%;
    background: rgba(255,255,255,0.15);
    padding: 10px 0;
    border-radius: 12px;
    margin-top: 15px;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    transition: .2s;
}
.btn-ir:hover {
    background: rgba(255,255,255,0.30);
}

.btn-volver {
    display: inline-block;
    margin-top: 35px;
    padding: 10px 25px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    text-decoration:none;
    color:#fff;
    font-weight: 600;
    transition: .2s;
}
.btn-volver:hover {
    background: rgba(255,255,255,0.30);
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-journal-bookmark-fill"></i><br>
    Seleccione un Curso
</h1>

<div class="container-cursos">
    <?php if (!empty($cursos)): ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="card-glass">
                <h4><?= htmlspecialchars($curso['nombre']) ?></h4>
                <p><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>

                <a class="btn-ir"
                   href="/Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=<?= urlencode($curso['id']) ?>">
                    Administrar Material
                </a>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p>No hay cursos registrados.</p>
    <?php endif; ?>
</div>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
