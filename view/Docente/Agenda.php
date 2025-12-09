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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agenda del Docente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #1f1f2e;
    color: #c4c3ca;
    padding: 40px;
    min-height: 100vh;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 500px;
    background-position: center top;
}



.table-container {
    overflow-x: auto;
    padding: 15px;
    border-radius: 20px;
    backdrop-filter: blur(8px);
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.1);
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    color: #fff;
}

table thead th {
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

table tbody tr:hover {
    background: rgba(255,255,255,0.05);
    cursor: default;
}

.badge-ok {
    background: #22c55e;
    color: #fff;
}
.badge-warning {
    background: #fbbf24;
    color: #000;
}
.no-actividades {
    text-align: center;
    padding: 25px;
    color: #ffffffbb;
}

/* Títulos */
h2 {
    text-align: center;
    color: #ffffff;
    margin-bottom: 30px;
    font-weight: 700;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

/* Cards translúcidas con efecto glass */
.card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.3);
}

.card-header {
    font-weight: 600;
    color: #ffffff;
    background: rgba(255,255,255,0.1) !important;
    border-bottom: none;
    border-radius: 20px 20px 0 0;
}

/* Formulario */
input, textarea {
    border-radius: 15px !important;
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: none;
    padding: 8px 12px;
}

input:focus, textarea:focus {
    background: rgba(255,255,255,0.15);
    outline: none;
    box-shadow: 0 0 10px rgba(79, 70, 229,0.5);
}

/* Botones */
.btn-primary {
    background-color: #4f46e5;
    border: none;
    border-radius: 15px;
    padding: 8px 20px;
    font-weight: 600;
    transition: all 0.2s;
}
.btn-primary:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
}

.btn-danger {
    border-radius: 15px;
    padding: 8px 15px;
    transition: all 0.2s;
}
.btn-danger:hover {
    transform: translateY(-2px);
}

/* Tabla */
.table-container {
    background: rgba(255,255,255,0.05);
    padding: 20px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    margin-bottom: 30px;
    overflow-x: auto;
}

table, th, td {
    color: #ffffff;
    vertical-align: middle;
}

th {
    background: rgba(255,255,255,0.1);
    font-weight: 600;
}

/* Badges de estado */
.badge-pend { background:#fbbf24; color:#000; }
.badge-ok   { background:#22c55e; }
.badge-no   { background:#6b7280; }

/* Mensaje "No hay actividades" */
.no-actividades {
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    padding: 30px;
    text-align: center;
    color: #ffffffbb;
    font-weight: 500;
}
.no-actividades i {
    font-size: 2rem;
    margin-bottom: 10px;
}

/* Responsivo */
@media(max-width:768px){
    .row.g-2 > div {
        margin-bottom: 10px;
    }
}
</style>
</head>
<body>



  <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

<div class="container mt-5 mb-5">

    <h2><i class="fa-solid fa-calendar-days"></i> Agenda Semanal</h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Formulario Crear Actividad -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-plus"></i> Agregar Actividad
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row g-2">
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
                        <button type="submit" name="crearActividad" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        <i class="fa-solid fa-folder-open"></i> Actividades Programadas
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table table-borderless align-middle text-center mb-0" style="background: rgba(0,0,0,0.3); border-radius:15px;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05);">
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($actividades)): ?>
                        <?php foreach ($actividades as $actividad): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <td><?= htmlspecialchars($actividad['Titulo']) ?></td>
                            <td><?= htmlspecialchars($actividad['Descripcion']) ?></td>
                            <td><?= htmlspecialchars($actividad['Fecha']) ?></td>
                            <td><?= htmlspecialchars($actividad['Hora']) ?></td>
                            <td>
                                <?php if ($actividad['Estado'] === 'Pendiente'): ?>
                                    <span class="badge badge-warning text-dark">Pendiente</span>
                                <?php else: ?>
                                    <span class="badge badge-ok">Completada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" action="" style="display:inline-block;">
                                    <input type="hidden" name="idAgenda" value="<?= $actividad['Id_Agenda'] ?>">
                                    <button type="submit" name="eliminarActividad" class="btn btn-danger btn-sm">
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
                                <i class="fa-solid fa-calendar-xmark fa-2x"></i>
                                <p>No hay actividades programadas.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

</body>
</html>
