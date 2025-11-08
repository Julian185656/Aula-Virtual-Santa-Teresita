<?php
session_start();
require_once __DIR__ . "/../../model/TareaModel.php";
require_once __DIR__ . "/../../model/db.php";

// Validar sesión y rol estudiante
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idEstudiante = (int)$_SESSION['id_usuario'];

// Filtrar por curso (opcional)
$idCurso = $_GET['idCurso'] ?? null;

$mensaje = '';
$errores = [];

// Manejar adjuntar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entregarTarea'])) {
    $idTarea = (int)($_POST['idTarea'] ?? 0);
    if ($idTarea && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/uploads/";
        if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);

        $nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
        $rutaDestino = $rutaCarpeta . $nombreArchivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
            // Guarda la entrega en BD (si ya existe, actualiza archivo y fecha)
            $archivoUrl = "/Aula-Virtual-Santa-Teresita/uploads/" . $nombreArchivo;

            // upsert sencillo de entrega_tarea
            $sel = $pdo->prepare("SELECT Id_Entrega FROM entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
            $sel->execute([$idTarea, $idEstudiante]);
            $ex = $sel->fetch(PDO::FETCH_ASSOC);

            if ($ex) {
                $upd = $pdo->prepare("UPDATE entrega_tarea SET Archivo_URL=?, Fecha_Entrega=CURDATE() WHERE Id_Entrega=?");
                $upd->execute([$archivoUrl, $ex['Id_Entrega']]);
            } else {
                $ins = $pdo->prepare("INSERT INTO entrega_tarea (Id_Tarea, Id_Estudiante, Archivo_URL, Fecha_Entrega) VALUES (?,?,?,CURDATE())");
                $ins->execute([$idTarea, $idEstudiante, $archivoUrl]);
            }

            $mensaje = "Archivo entregado correctamente.";
        } else {
            $errores[] = "Error al subir el archivo.";
        }
    } else {
        $errores[] = "Seleccione un archivo válido.";
    }
}

// Obtener tareas del estudiante (con filtro de curso si viene)
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
        h2 { text-align: center; margin-bottom: 20px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .card-body h5 { font-weight: 600; }
        .btn-entregar {
            background-color: #0d6efd; color: #fff; border-radius: 6px;
            padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-entregar:hover { background-color: #0b5ed7; color: #fff; }
        .fecha { font-size: 0.9em; color: #555; }
        .alert { max-width: 800px; margin: 15px auto; }
        .status-box { background:#f8f9fa; border:1px dashed #dee2e6; border-radius: 8px; padding:12px; }
    </style>
</head>
<body>

<h2>Mis Tareas</h2>

<!-- 🔙 Botón para volver a la lista de cursos del estudiante (archivo correcto) -->
<div class="container mb-3" style="text-align:center;">
    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="btn btn-outline-secondary">
        ⬅️ Volver a Mis Cursos
    </a>
</div>

<?php if ($mensaje): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
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

                            <!-- Form de entrega -->
                            <form method="POST" enctype="multipart/form-data" class="mb-3">
                                <input type="hidden" name="idTarea" value="<?= (int)$tarea['Id_Tarea'] ?>">
                                <input type="file" name="archivo" required>
                                <button type="submit" name="entregarTarea" class="btn btn-entregar mt-2">
                                    📎 Entregar
                                </button>
                            </form>

                            <!-- Estado de evaluación / comentarios -->
                            <?php
                            $info = null;
                            $q = $pdo->prepare("SELECT Calificacion, Comentario
                                                FROM entrega_tarea
                                                WHERE Id_Tarea = ? AND Id_Estudiante = ?
                                                LIMIT 1");
                            $q->execute([(int)$tarea['Id_Tarea'], $idEstudiante]);
                            $info = $q->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="status-box">
                                <?php if ($info && $info['Calificacion'] !== null): ?>
                                    <div><strong>✅ Evaluado</strong> — ¡bien! 🎉</div>
                                    <div>⭐ <strong>Nota:</strong> <?= (float)$info['Calificacion'] ?>/100</div>
                                    <?php if (!empty($info['Comentario'])): ?>
                                        <div>📝 <strong>Comentarios del profe:</strong> <?= nl2br(htmlspecialchars($info['Comentario'])) ?></div>
                                    <?php else: ?>
                                        <div>📝 Sin comentarios adicionales.</div>
                                    <?php endif; ?>
                                <?php elseif ($info): ?>
                                    <div><strong>⏳ Entregado</strong> — El profe aún no lo evalúa.</div>
                                <?php else: ?>
                                    <div><strong>📤 Aún no has enviado esta tarea.</strong></div>
                                <?php endif; ?>
                            </div>
                            <!-- / -->
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
