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

// Obtener datos del curso usando tu modelo
$curso = CursoModel::obtenerCursoPorId($cursoId);
if (!$curso) {
    die("Curso no encontrado.");
}

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $fechaEntrega = $_POST['fecha_entrega'] ?? '';

    if ($titulo && $fechaEntrega) {
        $tareaModel = new TareaModel($pdo); // usar el global $pdo de db.php
        if ($tareaModel->crearTarea($cursoId, $titulo, $descripcion, $fechaEntrega)) {
            $mensaje = "Tarea creada con éxito.";
        } else {
            $mensaje = "Error al crear la tarea.";
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
    <title>Asignar Tarea - <?= htmlspecialchars($curso['nombre'] ?? 'Curso desconocido') ?></title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; max-width: 600px; margin: auto; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .btn-crear { background-color: #007BFF; color: white; }
        .btn-crear:hover { background-color: #0069d9; }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="text-center mb-4">Asignar Tarea a: <?= htmlspecialchars($curso['nombre'] ?? 'Curso desconocido') ?></h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Tarea</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-crear w-100">Crear Tarea</button>
    </form>

    <div class="mt-3 text-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn btn-secondary">Volver a Mis Cursos</a>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
