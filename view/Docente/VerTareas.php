<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// Validar sesión y rol docente
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// Validar ID del curso
$cursoId = $_GET['id'] ?? null;
if (!$cursoId) {
    die("Curso no especificado.");
}

// Obtener curso
$curso = CursoModel::obtenerCursoPorId($cursoId);
if (!$curso) {
    die("Curso no encontrado.");
}

$tareaModel = new TareaModel($pdo);
$tareas = $tareaModel->obtenerTareasPorCurso($cursoId);

// Eliminar tarea si se recibe acción
if (isset($_GET['eliminar'])) {
    $idTarea = (int)$_GET['eliminar'];
    $tareaModel->eliminarTarea($idTarea);
    header("Location: VerTareas.php?id=" . $cursoId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tareas - <?= htmlspecialchars($curso['nombre'] ?? 'Curso') ?></title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-body { display: flex; justify-content: space-between; align-items: center; }
        .tarea-info { flex-grow: 1; }
        .btn-icon { text-decoration: none; font-size: 18px; margin-left: 10px; padding: 6px 10px; border-radius: 6px; color: white; display: inline-block; }
        .btn-edit { background-color: #007BFF; }
        .btn-edit:hover { background-color: #0069d9; }
        .btn-delete { background-color: #DC3545; }
        .btn-delete:hover { background-color: #c82333; }
        h2 { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>

<h2>Tareas del Curso: <?= htmlspecialchars($curso['nombre'] ?? 'Curso') ?></h2>

<div class="container">
    <?php if (!empty($tareas)): ?>
        <?php foreach ($tareas as $tarea): ?>
            <div class="card">
                <div class="card-body">
                    <div class="tarea-info">
                        <h5><?= htmlspecialchars($tarea['Titulo']) ?></h5>
                        <p><?= htmlspecialchars($tarea['Descripcion']) ?></p>
                        <small>Entrega: <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></small>
                    </div>
                    <div class="tarea-acciones">
                        <a class="btn-icon btn-edit" href="EditarTarea.php?id=<?= $tarea['Id_Tarea'] ?>"><i class="fas fa-edit"></i></a>
                        <a class="btn-icon btn-delete" href="VerTareas.php?id=<?= $cursoId ?>&eliminar=<?= $tarea['Id_Tarea'] ?>" onclick="return confirm('¿Eliminar esta tarea?')"><i class="fas fa-trash"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center">
            <p>No hay tareas asignadas a este curso.</p>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn btn-secondary">Volver a Mis Cursos</a>
        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= $cursoId ?>" class="btn btn-primary">Añadir Nueva Tarea</a>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
