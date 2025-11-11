<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once __DIR__ . '/../../model/AgendaModel.php';

// ✅ Verificar sesión activa
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new AgendaModel($pdo);
$idDocente = $_SESSION['id_usuario'];

// ✅ Crear actividad
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

// ✅ Eliminar actividad
if (isset($_POST['eliminarActividad'])) {
    $idAgenda = intval($_POST['idAgenda']);
    if ($idAgenda > 0) {
        $model->eliminarActividad($idAgenda);
        $msg = "Actividad eliminada correctamente.";
    }
}

// ✅ Obtener actividades del docente
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
</head>
<body style="background-color:#f8f9fa;">

<div class="container mt-5 mb-5">
    <h2 class="text-center text-primary mb-4">
        <i class="fa-solid fa-calendar-days"></i> Agenda Semanal
    </h2>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- ✅ Formulario Crear Actividad -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-dark"><i class="fa-solid fa-plus"></i> Agregar Actividad</h5>
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
                    <div class="col-md-1">
                        <button type="submit" name="crearActividad" class="btn btn-primary w-100">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ Tabla de Actividades -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-dark"><i class="fa-solid fa-folder-open"></i> Actividades Programadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-primary">
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
                        <?php if (!empty($actividades)): ?>
                            <?php foreach ($actividades as $actividad): ?>
                                <tr>
                                    <td><?= htmlspecialchars($actividad['Titulo']) ?></td>
                                    <td><?= htmlspecialchars($actividad['Descripcion']) ?></td>
                                    <td><?= htmlspecialchars($actividad['Fecha']) ?></td>
                                    <td><?= htmlspecialchars($actividad['Hora']) ?></td>
                                    <td><?= htmlspecialchars($actividad['Estado']) ?></td>
                                    <td>
                                        <form method="POST" action="">
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
                                <td colspan="6" class="text-muted text-center">No hay actividades programadas.</td>
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
