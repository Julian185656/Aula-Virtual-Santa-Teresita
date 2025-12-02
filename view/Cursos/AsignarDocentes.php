<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();
$cursos   = CursoModel::obtenerCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarDocentes'])) {
    CursoController::asignarDocentes();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Docentes</title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body {
    font-family: 'Montserrat', sans-serif !important;
    background: #1f272b;
    color: #fff;
    padding: 20px;
    text-align: center;
}

.card-glass {
    max-width: 550px;
    margin: 0 auto;
    padding: 30px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    text-align: center;
}

.card-glass select,
.card-glass button {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.12);
    color: #fff;
}

.card-glass select option {
    color: #ffffffff;
}

.card-glass button {
    background: rgba(255,255,255,0.15);
    font-weight: bold;
    transition: 0.2s ease;
}

.card-glass button:hover {
    background: rgba(255,255,255,0.35);
}

.volver-btn {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 10px 20px;
    border-radius: 15px;
    color: #fff;
    text-decoration: none;
    margin-top: 30px;
    transition: 0.2s;
}

.volver-btn:hover {
    background: rgba(255,255,255,0.35);
}

h1 i {
    font-size: 40px;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-people-fill"></i><br>
    Asignar Docentes
</h1>

<div class="card-glass">

    <form method="POST">

        <select name="idCurso" required>
            <option value="">Seleccione un curso</option>
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <select name="docentes[]" multiple size="5" required>
            <?php foreach ($docentes as $d): ?>
                <option value="<?= $d['id'] ?>"><?= $d['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="asignarDocentes">Asignar</button>
    </form>

</div>

<a href="../Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
