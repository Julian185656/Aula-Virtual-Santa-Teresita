<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();
$cursos   = CursoModel::obtenerCursos();


$asignacionesActuales = CursoModel::obtenerAsignaciones();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarDocentes'])) {
    CursoController::asignarDocentes();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Docentes</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
body{
    font-family: 'Montserrat', sans-serif;
    background-color: #2a2b38;
    color: #fff;
    text-align: center;
    padding: 40px 15px;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
}

.card-glass {
    max-width: 900px;
    margin: 0 auto;
    padding: 25px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    overflow-x: auto;
}

.card-glass table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.card-glass table th, .card-glass table td {
    padding: 10px;
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
}

.card-glass table th {
    background: rgba(255,255,255,0.1);
}

.card-glass button {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.card-glass button:hover {
    background: rgba(255,255,255,0.35);
}

.volver-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
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

<h1><i class="bi bi-people-fill"></i> Asignar Docentes</h1>

<div class="card-glass">

<form method="POST">

    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <?php foreach ($cursos as $c): ?>
                    <th><?= htmlspecialchars($c['nombre']) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docentes as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['nombre']) ?></td>
                <?php foreach ($cursos as $c): ?>
                    <td>
                        <input type="checkbox"
                               name="asignaciones[<?= $c['id'] ?>][]"
                               value="<?= $d['id'] ?>"
                               <?php
              
                                 if (isset($asignacionesActuales[$c['id']]) &&
                                     in_array($d['id'], $asignacionesActuales[$c['id']])) {
                                     echo 'checked';
                                 }
                               ?>>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" name="asignarDocentes">Asignar Docentes</button>
</form>

</div>

<a href="../Home/Home.php" class="volver-btn"><i class="bi bi-arrow-left-circle-fill"></i> Volver</a>

</body>
</html>
