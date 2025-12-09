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

<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
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

h1 { margin-bottom: 30px; font-size: 36px; }

.card-glass {
    display: inline-block;
    width: 300px;
    margin: 15px;
    padding: 20px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    text-align: center;
    vertical-align: top;
}

.card-glass h4 {
    font-size: 20px;
    margin-bottom: 10px;
}

.card-glass p {
    font-size: 14px;
    color: #c4c3ca;
    min-height: 40px;
}

.btn-ir, .btn-volver {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 8px 15px;
    border-radius: 15px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    margin-top: 10px;
    transition: 0.2s;
}

.btn-ir:hover, .btn-volver:hover {
    background: rgba(255,255,255,0.35);
}

.container-cursos {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.btn-volver {
    margin-bottom: 30px;
}
</style>
</head>
<body>

<h1><i class="bi bi-journal-bookmark-fill"></i><br>Seleccione un Curso</h1>



<div class="container-cursos">
    <?php if (!empty($cursos)): ?>
        <?php foreach ($cursos as $curso): ?>
            <div class="card-glass">
                <h4><?= htmlspecialchars($curso['nombre']) ?></h4>
                <p><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>
                <a class="btn-ir" href="/Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=<?= urlencode($curso['id']) ?>">
                    Administrar Material
                </a>
            </div>
        <?php endforeach; ?>




    <?php else: ?>
        <p>No hay cursos registrados.</p>
    <?php endif; ?>
</div>


<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver"><i class="bi bi-arrow-left-circle-fill"></i> Volver</a>


</body>
</html>
