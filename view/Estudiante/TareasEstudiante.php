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
$idCurso = $_GET['idCurso'] ?? null;

$mensaje = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entregarTarea'])) {
    $idTarea = (int)($_POST['idTarea'] ?? 0);
    if ($idTarea && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
        $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/uploads/";
        if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);

        $nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
        $rutaDestino = $rutaCarpeta . $nombreArchivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
            $archivoUrl = "/Aula-Virtual-Santa-Teresita/uploads/" . $nombreArchivo;
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

$tareas = TareaModel::obtenerTareasEstudiante($idEstudiante, $idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Santa Teresita - Mis Tareas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/fontawesome.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/owl.css">
    <link rel="stylesheet" href="/Aula-Virtual-Santa-Teresita/view/assets/css/lightbox.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

 <link rel="stylesheet" href="../Styles/TareasEstudiantes.css">
</head>
<body>



<div class="container-tareas">
    <h2>Mis Tareas</h2>

    <div style="text-align:center;">
        <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="volver">
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

    <div class="row">
        <?php if (!empty($tareas)): ?>
            <?php foreach ($tareas as $tarea): ?>
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($tarea['Titulo']) ?></h5>
                            <p><strong>Curso:</strong> <?= htmlspecialchars($tarea['Curso']) ?></p>
                            <p><?= nl2br(htmlspecialchars($tarea['Descripcion'])) ?></p>
                            <p class="fecha"><strong>Fecha de entrega:</strong> <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></p>

                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="idTarea" value="<?= (int)$tarea['Id_Tarea'] ?>">
                                
                                <div class="mb-2">
                                    <input type="file" name="archivo" id="archivo<?= $tarea['Id_Tarea'] ?>" class="d-none" required>
                                    <label for="archivo<?= $tarea['Id_Tarea'] ?>" class="btn-file-select">
                                        <i class="fas fa-upload"></i> Seleccionar archivo
                                    </label>
                                    <span id="nombreArchivo<?= $tarea['Id_Tarea'] ?>" class="ms-2 text-muted small"></span>
                                </div>

                                <div>
                                    <button type="submit" name="entregarTarea" class="btn-entregar">
                                        <i class="fas fa-paperclip"></i> Entregar
                                    </button>
                                </div>
                            </form>

                            <script>
                                document.getElementById('archivo<?= $tarea['Id_Tarea'] ?>').addEventListener('change', function(e){
                                    const nombre = e.target.files[0] ? e.target.files[0].name : '';
                                    document.getElementById('nombreArchivo<?= $tarea['Id_Tarea'] ?>').textContent = nombre;
                                });
                            </script>

                            <?php
                            $info = null;
                            $q = $pdo->prepare("SELECT Calificacion, Comentario
                                                FROM entrega_tarea
                                                WHERE Id_Tarea = ? AND Id_Estudiante = ?
                                                LIMIT 1");
                            $q->execute([(int)$tarea['Id_Tarea'], $idEstudiante]);
                            $info = $q->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="status-box mt-3">
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

<footer class="text-center py-3 bg-light mt-5">
    <div class="container">
        <p>© 2025 Santa Teresita | Template basado en TemplateMo</p>
    </div>
</footer>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/isotope.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/owl-carousel.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/lightbox.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/assets/js/custom.js"></script>
</body>
</html>
