<?php
require_once __DIR__ . '/../../controller/CursoController.php';
require_once __DIR__ . '/../../model/CursoModel.php';

$mensajeMatricula = '';

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crearCurso'])) {
        CursoController::crearCurso();
    } elseif (isset($_POST['asignarDocentes'])) {
        CursoController::asignarDocentes();
    } elseif (isset($_POST['eliminarCurso'])) {
        CursoController::eliminarCurso($_POST['idCursoEliminar']);
    } elseif (isset($_POST['matricularEstudiantes'])) {
        // Matricular estudiantes
        CursoModel::matricularEstudiantes($_POST['idCursoMatricula'], $_POST['estudiantes'] ?? []);
        $mensajeMatricula = "Estudiantes matriculados correctamente.";
    }
}

// Obtener listas
$docentes = CursoModel::obtenerDocentes();
$cursos = CursoModel::obtenerCursos();
$estudiantes = CursoModel::obtenerEstudiantes(); // Nuevo método
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestionar Cursos</title>
<link rel="stylesheet" href="../Styles/crearCurso.css">
</head>
<body>

<div class="container">

    <!-- Crear Curso -->
    <h2>Crear Curso</h2>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>
        <button type="submit" name="crearCurso">Crear Curso</button>
    </form>
    <hr>

    <!-- Asignar Docentes -->
    <h2>Asignar docentes a un curso</h2>
    <form method="POST">
        <label>Curso:</label>
        <select name="idCurso" required>
            <option value="" selected disabled>Seleccione un curso</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']) ?>">
                    <?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Docentes:</label>
        <select name="docentes[]" multiple size="5" required>
            <?php foreach ($docentes as $docente): ?>
                <option value="<?= htmlspecialchars($docente['id']) ?>">
                    <?= htmlspecialchars($docente['nombre'] ?? 'Sin nombre') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="asignarDocentes">Asignar docentes</button>
    </form>
    <hr>

    <!-- Eliminar Curso -->
    <h2>Eliminar Curso</h2>
    <form method="POST">
        <label>Seleccione curso a eliminar:</label>
        <select name="idCursoEliminar" required>
            <option value="" selected disabled>Seleccione un curso</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']) ?>">
                    <?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="eliminarCurso" class="delete-btn">Eliminar Curso</button>
    </form>
    <hr>

    <!-- Matricular Estudiantes -->
    <h2>Matricular Estudiantes a un Curso</h2>
    <?php if ($mensajeMatricula): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensajeMatricula) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Curso:</label>
        <select name="idCursoMatricula" required>
            <option value="" selected disabled>Seleccione un curso</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']) ?>">
                    <?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Estudiantes:</label>
        <select name="estudiantes[]" multiple size="5" required>
            <?php foreach ($estudiantes as $estudiante): ?>
                <option value="<?= htmlspecialchars($estudiante['id']) ?>">
                    <?= htmlspecialchars($estudiante['nombre'] ?? 'Sin nombre') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="matricularEstudiantes">Matricular Estudiantes</button>
    </form>

</div>

</body>
</html>
