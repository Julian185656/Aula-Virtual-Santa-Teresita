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

body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #ffffffff; 
    padding: 40px 15px;
    text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
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
    color: #fff; 
}


.card-glass select {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 15px;
    border: none;
    background: rgba(255, 255, 255, 0.12);
    color: black; 
}

.card-glass select option {
    color: black; 
}


.card-glass button {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.15);
    color: #fff; 
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


.card-glass h1, 
.card-glass label {
    color: #fff; 
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
