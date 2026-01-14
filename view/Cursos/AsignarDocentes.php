<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();   // NO SE CAMBIA para no romper backend
$cursos   = CursoModel::obtenerCursos();
$asignacionesActuales = CursoModel::obtenerAsignaciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarProfesores'])) {
    CursoController::asignarDocentes(); // Se mantiene igual en el backend
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Profesores</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    background-color: #1d1e28;
    color: #fff;
    padding: 40px 20px;
    text-align: center;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
}

h1{
    font-size: 2.3rem;
    font-weight: 700;
    margin-bottom: 25px;
}

h1 i{
    font-size: 42px;
    margin-right: 8px;
}

.card-glass{
    max-width: 1100px;
    margin: 0 auto;
    padding: 25px;
    background: rgba(255,255,255,0.06);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    overflow-x: auto;
}

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th{
    background: rgba(255,255,255,0.12);
    padding: 14px;
    font-size: 15px;
    font-weight: 600;
}

td{
    padding: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.18);
    font-size: 15px;
}

tr:hover{
    background: rgba(255,255,255,0.1);
}

input[type="checkbox"]{
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #6cc0ff;
}

button{
    width: 100%;
    padding: 14px;
    margin-top: 25px;
    border-radius: 12px;
    border: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: 0.25s;
}

button:hover{
    background: rgba(255,255,255,0.35);
}

.volver-btn{
    display: inline-block;
    margin-top: 35px;
    padding: 12px 25px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    text-decoration: none;
    transition: 0.25s;
}

.volver-btn:hover{
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1><i class="bi bi-person-badge-fill"></i> Asignar Profesores</h1>

<div class="card-glass">

<form method="POST">

    <table>
        <thead>
            <tr>
                <th>Profesor</th>
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
                        <input 
                            type="checkbox"
                            name="asignaciones[<?= $c['id'] ?>][]"
                            value="<?= $d['id'] ?>"
                            <?php if (!empty($asignacionesActuales[$c['id']]) && in_array($d['id'], $asignacionesActuales[$c['id']])) echo 'checked'; ?>
                        >
                    </td>
                <?php endforeach; ?>

            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

    <button type="submit" name="asignarProfesores">
        Guardar Asignaciones
    </button>

</form>

</div>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle"></i> Volver
</a>

</body>
</html>
