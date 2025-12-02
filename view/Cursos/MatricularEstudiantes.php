<?php
require_once __DIR__ . '/../../model/CursoModel.php';

$cursos = CursoModel::obtenerCursos();
$estudiantes = CursoModel::obtenerEstudiantes();
$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Matricular
    if (isset($_POST['matricular'])) {
        CursoModel::matricularEstudiantes($_POST['idCursoMatricula'], $_POST['estudiantes'] ?? []);
        $mensaje = "Estudiantes matriculados correctamente.";
    }
    // Eliminar matrícula
    elseif (isset($_POST['eliminar'])) {
        CursoModel::eliminarMatricula($_POST['idCursoEliminar'], $_POST['idEstudianteEliminar']);
        $mensaje = "Matrícula eliminada correctamente.";
    }
}

// Curso seleccionado
$cursoSeleccionado = $_POST['idCursoMatricula'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Matricular Estudiantes</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
body { font-family: 'Montserrat', sans-serif !important; background: #1f272b; color: #fff; padding: 20px; text-align: center; }
.card-glass { max-width: 1000px; margin: 0 auto; padding: 30px; background: rgba(255,255,255,0.05); border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(12px); box-shadow: 0 8px 25px rgba(0,0,0,0.35); }
.card-glass select { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 15px; border: none; background: rgba(255,255,255,0.12); color: #fff; }
.card-glass select option { color: #000; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; color: #fff; }
th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.2); }
tr:hover { background: rgba(255,255,255,0.12); }
button, .eliminar-btn { padding: 6px 12px; border-radius: 10px; border: none; color: #fff; cursor: pointer; transition: 0.2s; }
button { width: 100%; background: rgba(255,255,255,0.15); font-weight: bold; margin-top: 20px; }
button:hover, .eliminar-btn:hover { background: rgba(255,255,255,0.35); }
.volver-btn { display: inline-block; background: rgba(255,255,255,0.15); padding: 10px 20px; border-radius: 15px; color: #fff; text-decoration: none; margin-top: 30px; transition: 0.2s; }
.volver-btn:hover { background: rgba(255,255,255,0.35); }
h1 i { font-size: 40px; margin-bottom: 10px; }
.alert-success { background: rgba(0, 255, 0, 0.2); color: #0f0; padding: 10px; margin-bottom: 20px; border-radius: 10px; }
</style>
</head>
<body>

<h1><i class="bi bi-people-fill"></i><br>Matricular Estudiantes</h1>

<div class="card-glass">

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Selección de curso para matricular -->
    <form method="POST">
        <select name="idCursoMatricula" onchange="this.form.submit()">
            <option value="">Seleccione un curso</option>
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($cursoSeleccionado == $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($cursoSeleccionado): ?>
    <form method="POST">
        <input type="hidden" name="idCursoMatricula" value="<?= $cursoSeleccionado ?>">

        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Estudiante</th>
                    <th>Cursos actuales</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $e): ?>
                    <?php
                        $cursosEst = CursoModel::obtenerCursosPorEstudiante($e['id']);
                        $cursoActual = CursoModel::obtenerCursoPorId($cursoSeleccionado);
                        $cursoNombre = $cursoActual['nombre'] ?? '';
                        $estaMatriculado = in_array($cursoNombre, $cursosEst);
                        $listaCursos = !empty($cursosEst) ? implode(", ", $cursosEst) : "Sin cursos";
                    ?>
                    <tr>
                        <td>
                            <?php if (!$estaMatriculado): ?>
                                <input type="checkbox" name="estudiantes[]" value="<?= $e['id'] ?>">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($e['nombre']) ?></td>
                        <td style="font-size: 13px; color: #ccc;"><?= htmlspecialchars($listaCursos) ?></td>
                        <td>
                            <?php foreach ($cursosEst as $cursoEliminarNombre): ?>
                                <?php 
                                    $cursoEliminar = CursoModel::obtenerCursoIdPorNombre($cursoEliminarNombre); 
                                    if ($cursoEliminar):
                                ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="idCursoEliminar" value="<?= $cursoEliminar ?>">
                                    <input type="hidden" name="idEstudianteEliminar" value="<?= $e['id'] ?>">
                                    <button type="submit" name="eliminar" class="eliminar-btn"><?= htmlspecialchars($cursoEliminarNombre) ?></button>
                                </form>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" name="matricular">Matricular Seleccionados</button>
    </form>
    <?php endif; ?>

</div>

<a href="../Home/Home.php" class="volver-btn"><i class="bi bi-arrow-left-circle-fill"></i> Volver</a>

</body>
</html>
