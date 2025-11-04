<?php
session_start();
require_once __DIR__ . '/../../model/CursoModel.php';

$cursos = CursoModel::obtenerCursos();
$estudiantes = CursoModel::obtenerEstudiantes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matricular Estudiantes</title>

    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            font-weight: bold;
            text-align: center;
            color: #0d6efd;
            margin-bottom: 25px;
        }
        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Matricular Estudiantes</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php">
            <input type="hidden" name="accion" value="matricularEstudiantes">

            <div class="mb-3">
                <label class="form-label">Seleccionar Curso</label>
                <select name="idCurso" class="form-select" required>
                    <option value="">-- Seleccione un curso --</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?= htmlspecialchars($curso['id']) ?>">
                            <?= htmlspecialchars($curso['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Seleccionar Estudiante</label>
                <select name="estudiantes[]" class="form-select" required>
                    <option value="">-- Seleccione un estudiante --</option>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <option value="<?= htmlspecialchars($estudiante['id']) ?>">
                            <?= htmlspecialchars($estudiante['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/HomeCurso.php" class="btn btn-secondary">← Volver</a>
                <button type="submit" class="btn btn-primary">Matricular</button>
            </div>
        </form>
    </div>

    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
