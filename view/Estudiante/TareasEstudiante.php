<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";

// Validar sesión y rol estudiante
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idEstudiante = $_SESSION['id_usuario'];

// Filtrar por curso
$idCurso = $_GET['idCurso'] ?? null;

$mensaje = '';
$errores = [];

// Manejar adjuntar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entregarTarea'])) {
    $idTarea = $_POST['idTarea'] ?? null;
    if ($idTarea && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/uploads/";
        if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);
        
        $nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
        $rutaDestino = $rutaCarpeta . $nombreArchivo;
        
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
            $mensaje = "Archivo entregado correctamente.";
            // Aquí podrías guardar en BD la ruta del archivo y el idEstudiante/idTarea
        } else {
            $errores[] = "Error al subir el archivo.";
        }
    } else {
        $errores[] = "Seleccione un archivo válido.";
    }
}

// Obtener tareas del estudiante filtradas por curso si se proporciona
$tareas = TareaModel::obtenerTareasEstudiante($idEstudiante, $idCurso);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Tareas</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-body h5 { font-weight: 600; }
        .card-body p { margin-bottom: 10px; }
        .btn-entregar {
            background-color: #007BFF; color: white; border-radius: 6px;
            padding: 6px 12px; display: inline-flex; align-items: center;
        }
        .btn-entregar:hover { background-color: #0069d9; color: white; }
        .fecha { font-size: 0.9em; color: #555; }
        .alert { max-width: 600px; margin: 15px auto; }
        .btn-container { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Mis Tareas</h2>

<?php if ($mensaje): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <?php if (!empty($tareas)): ?>
            <?php foreach ($tareas as $tarea): ?>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($tarea['Titulo']) ?></h5>
                            <p><strong>Curso:</strong> <?= htmlspecialchars($tarea['Curso']) ?></p>
                            <p><?= nl2br(htmlspecialchars($tarea['Descripcion'])) ?></p>
                            <p class="fecha"><strong>Fecha de entrega:</strong> <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></p>
                            <div class="btn-container">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="idTarea" value="<?= htmlspecialchars($tarea['Id_Tarea']) ?>">
                                    <input type="file" name="archivo" required>
                                    <button type="submit" name="entregarTarea" class="btn btn-entregar mt-2">📎 Entregar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p><strong>No tienes tareas asignadas para este curso.</strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
