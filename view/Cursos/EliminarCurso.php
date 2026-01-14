<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$cursos = CursoModel::obtenerCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarCurso'])) {
    CursoController::eliminarCurso($_POST['idCursoEliminar']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eliminar Curso</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* ======== ESTILO GLOBAL ======== */
body {
    font-family: 'Poppins', sans-serif;
    font-size: 17px;
    color: #ffffff;
    padding: 40px 15px;
    text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

/* ======== TÍTULO ======== */
h1 {
    font-weight: 700;
    margin-bottom: 25px;
    font-size: 2.2rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.45);
}

h1 i {
    font-size: 42px;
    margin-bottom: 10px;
}

/* ======== CARD GLASS ======== */
.card-glass {
    max-width: 550px;
    margin: 0 auto;
    padding: 35px;
    background: rgba(255,255,255,0.06);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 28px rgba(0,0,0,0.35);
    text-align: center;
}

/* ======== SELECT ======== */
.card-glass select {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border-radius: 14px;
    border: none;
    background: rgba(255,255,255,0.14);
    color: #fff;
    font-size: 16px;
}

.card-glass select option {
    color: #000;
}

/* ======== BOTÓN ELIMINAR ======== */
.card-glass button {
    width: 100%;
    padding: 14px;
    border-radius: 14px;
    border: none;
    background: rgba(255,80,80,0.35);
    color: #fff;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

.card-glass button:hover {
    background: rgba(255,0,0,0.55);
}

/* ======== BOTÓN VOLVER ======== */
.volver-btn {
    display: inline-block;
    margin-top: 35px;
    padding: 12px 26px;
    border-radius: 14px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    transition: 0.25s;
}

.volver-btn:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-trash-fill"></i><br>
    Eliminar Curso
</h1>

<div class="card-glass">
    <form method="POST">

        <select name="idCursoEliminar" required>
            <option value="">Seleccione un curso</option>
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="eliminarCurso">
            Eliminar Curso
        </button>

    </form>
</div>

<a href="../Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
