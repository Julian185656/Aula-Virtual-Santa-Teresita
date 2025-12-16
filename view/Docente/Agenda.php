<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once __DIR__ . '/../../model/AgendaModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new AgendaModel($pdo);
$idDocente = $_SESSION['id_usuario'];

$msg = '';

// Crear actividad
if (isset($_POST['crearActividad'])) {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    if ($titulo && $fecha && $hora) {
        $model->crearActividad($idDocente, $titulo, $descripcion, $fecha, $hora);
        $msg = "Actividad creada correctamente.";
    }
}

// Eliminar actividad
if (isset($_POST['eliminarActividad'])) {
    $idAgenda = intval($_POST['idAgenda']);
    if ($idAgenda > 0) {
        $model->eliminarActividad($idAgenda);
        $msg = "Actividad eliminada correctamente.";
    }
}

// Obtener actividades
$actividades = $model->obtenerActividadesDocente($idDocente);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agenda Semanal</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    font-family:'Poppins',sans-serif;
    font-size:15px;
    color:#ffffff;
    padding:40px 20px;
    background-color:#2a2b38;
    background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat:repeat;
    background-size:600px;
}

h2{
    text-align:center;
    font-weight:700;
    margin-bottom:30px;
}

.btn-volver{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-bottom:30px;
    padding:10px 18px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.4);
    background:rgba(255,255,255,0.1);
    color:#fff;
    text-decoration:none;
    transition:.2s;
}
.btn-volver:hover{
    background:rgba(255,255,255,0.25);
}

.glass-box{
    background:rgba(255,255,255,0.06);
    backdrop-filter:blur(10px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.15);
    box-shadow:0 8px 25px rgba(0,0,0,0.25);
    padding:25px;
    margin-bottom:30px;
}

input.form-control{
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.25);
    color:#fff;
    border-radius:12px;
}
input.form-control::placeholder{
    color:#ccc;
}

.btn-ghost{
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.35);
    background:rgba(255,255,255,0.12);
    color:#fff;
    padding:10px 18px;
    transition:.2s;
}
.btn-ghost:hover{
    background:rgba(255,255,255,0.28);
}

.table-container{
    background:rgba(255,255,255,0.04);
    padding:20px;
    border-radius:20px;
    backdrop-filter:blur(10px);
    border:1px solid rgba(255,255,255,0.15);
}

table{
    width:100%;
    color:#fff;
}

thead{
    background:rgba(255,255,255,0.15);
}

th,td{
    padding:12px;
    vertical-align:middle;
}

tbody tr:nth-child(even){
    background:rgba(255,255,255,0.04);
}

tbody tr:hover{
    background:rgba(255,255,255,0.12);
}

.badge-pend{background:#fbbf24;color:#000;}
.badge-ok{background:#22c55e;}

.no-actividades{
    text-align:center;
    padding:30px;
    opacity:.85;
}
</style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa fa-arrow-left"></i> Volver
    </a>

    <h2><i class="fa fa-calendar-days"></i> Agenda Semanal</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- CREAR ACTIVIDAD -->
    <div class="glass-box">
        <h5 class="mb-3">Agregar Actividad</h5>

        <form method="POST" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="titulo" class="form-control" placeholder="Título" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="time" name="hora" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
            </div>
            <div class="col-md-1 d-grid">
                <button type="submit" name="crearActividad" class="btn-ghost">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- ACTIVIDADES -->
    <div class="glass-box">
        <h5 class="mb-3">Actividades Programadas</h5>

        <div class="table-container">
            <table class="table table-borderless text-center mb-0">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($actividades): ?>
                    <?php foreach ($actividades as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['Titulo']) ?></td>
                            <td><?= htmlspecialchars($a['Descripcion']) ?></td>
                            <td><?= htmlspecialchars($a['Fecha']) ?></td>
                            <td><?= htmlspecialchars($a['Hora']) ?></td>
                            <td>
                                <?php if ($a['Estado']==='Pendiente'): ?>
                                    <span class="badge badge-pend">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge badge-ok">Completada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="idAgenda" value="<?= $a['Id_Agenda'] ?>">
                                    <button type="submit" name="eliminarActividad" class="btn-ghost">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="no-actividades">
                                No hay actividades programadas.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
