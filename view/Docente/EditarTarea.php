<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php"; // Conexión $pdo

// Validar sesión y rol docente
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// Validar ID de tarea
$idTarea = $_GET['id'] ?? null;
if (!$idTarea) {
    die("Tarea no especificada.");
}

// Instanciar modelo usando $pdo
$tareaModel = new TareaModel($pdo);

// Obtener datos de la tarea
$tarea = $tareaModel->obtenerTareaPorId($idTarea);
if (!$tarea) {
    die("Tarea no encontrada.");
}

// Obtener datos del curso
$curso = CursoModel::obtenerCursoPorId($tarea['Id_Curso']);
$cursoNombre = $curso['nombre'] ?? 'Curso desconocido'; // Ajuste a nombre real en db

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $fechaEntrega = $_POST['fecha_entrega'] ?? '';

    if ($titulo && $fechaEntrega) {
        if ($tareaModel->editarTarea($idTarea, $titulo, $descripcion, $fechaEntrega)) {
            $mensaje = "Tarea actualizada con éxito.";
            // Refrescar datos de la tarea
            $tarea = $tareaModel->obtenerTareaPorId($idTarea);
        } else {
            $mensaje = "Error al actualizar la tarea.";
        }
    } else {
        $mensaje = "El título y la fecha de entrega son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea - <?= htmlspecialchars($cursoNombre) ?></title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; max-width: 600px; margin: auto; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .btn-guardar { background-color: #28a745; color: white; }
        .btn-guardar:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="text-center mb-4">Editar Tarea de: <?= htmlspecialchars($cursoNombre) ?></h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Tarea</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($tarea['Titulo'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"><?= htmlspecialchars($tarea['Descripcion'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" value="<?= htmlspecialchars($tarea['Fecha_Entrega'] ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-guardar w-100">Guardar Cambios</button>
    </form>

    <div class="mt-3 text-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($tarea['Id_Curso']) ?>" class="btn btn-secondary">Volver a Tareas</a>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
