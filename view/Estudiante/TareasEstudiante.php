<?php
session_start();
require_once __DIR__ . "/../../model/TareaModel.php";
require_once __DIR__ . "/../../model/db.php";

// Validar sesión y rol estudiante
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idUsuario = (int)$_SESSION['id_usuario']; // ID real del estudiante
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
            $archivoUrl = "/Aula-Virtual-Santa-Teresita/uploads/" . $nombreArchivo;

            // Verificar si ya existe entrega
            $sel = $pdo->prepare("SELECT Id_Entrega FROM aulavirtual.entrega_tarea WHERE Id_Tarea = ? AND Id_Estudiante = ?");
            $sel->execute([$idTarea, $idUsuario]);
            $ex = $sel->fetch(PDO::FETCH_ASSOC);

            if ($ex) {
                // Actualizar entrega existente
                $upd = $pdo->prepare("UPDATE aulavirtual.entrega_tarea SET Archivo_URL = ?, Fecha_Entrega = GETDATE() WHERE Id_Entrega = ?");
                $upd->execute([$archivoUrl, $ex['Id_Entrega']]);
            } else {
                // Insertar nueva entrega
                $ins = $pdo->prepare("INSERT INTO aulavirtual.entrega_tarea (Id_Tarea, Id_Estudiante, Archivo_URL, Fecha_Entrega) VALUES (?,?,?,GETDATE())");
                $ins->execute([$idTarea, $idUsuario, $archivoUrl]);
            }

            $mensaje = "Archivo entregado correctamente.";
        } else {
            $errores[] = "Error al subir el archivo.";
        }
    } else {
        $errores[] = "Seleccione un archivo válido.";
    }
}

// Obtener tareas del estudiante
$tareas = TareaModel::obtenerTareasEstudiante($idUsuario, $idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Tareas</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

h2 { 
    text-align: center; 
    margin-bottom: 30px; 
    font-weight: 700;
    color: #fff;
}

.card { 
    border-radius: 15px; 
    box-shadow: 0 8px 25px rgba(0,0,0,0.2); 
    margin-bottom: 20px; 
    background: rgba(255,255,255,0.05); 
    backdrop-filter: blur(8px);
    padding: 20px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
}

.card-body h5 { 
    font-weight: 600; 
    color: #fff;
    margin-bottom: 10px;
}

.card-body p { 
    font-size: 0.95rem; 
    color: #c4c3ca;
    margin-bottom: 10px;
}

.fecha { 
    font-size: 0.85rem; 
    color: #a0a0a0; 
}

.btn-entregar, .btn-file-select {
    border-radius: 8px; 
    padding: 8px 16px; 
    display: inline-flex; 
    align-items: center; 
    gap: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-entregar {
    background-color: #ffffffff; 
    color: #030303ff;
    border: none;
}

.btn-entregar:hover {
    background-color: #009acd;
    transform: translateY(-2px);
}

.btn-file-select {
    background-color: #ffffffff;
    color: #000000ff;
    border: none;
}

.btn-file-select:hover {
    background-color: #138496;
    transform: translateY(-2px);
}

.status-box { 
    background: rgba(255,255,255,0.1); 
    border: 1px solid rgba(255,255,255,0.2); 
    border-radius: 10px; 
    padding: 12px; 
    margin-top: 10px; 
    font-size: 0.9rem;
    color: #fff;
}

.status-box div { 
    margin-bottom: 4px; 
}

.container { max-width: 1200px; }

input[type="file"] {
    display: none; /* ocultamos el input original */
}
</style>
</head>
<body>


  <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>




<h2>Mis Tareas</h2>


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
                            <h5>
                                <?= htmlspecialchars($tarea['Titulo']) ?> 
                                <i class="fa-solid fa-file-lines"></i>
                            </h5>
                            <p><strong>Curso:</strong> <?= htmlspecialchars($tarea['Curso']) ?></p>
                            <p><?= nl2br(htmlspecialchars($tarea['Descripcion'])) ?></p>
                            <p class="fecha"><strong>Fecha de entrega:</strong> <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></p>

                            <!-- Formulario moderno de entrega -->
                            <form method="POST" enctype="multipart/form-data" class="mb-3">
                                <input type="hidden" name="idTarea" value="<?= (int)$tarea['Id_Tarea'] ?>">

                                <!-- Input oculto -->
                                <input type="file" name="archivo" id="archivo-<?= $tarea['Id_Tarea'] ?>" required>

                                <!-- Botón para seleccionar archivo -->
                                <label for="archivo-<?= $tarea['Id_Tarea'] ?>" class="btn-file-select">
                                     Seleccionar Archivo
                                </label>

                                <!-- Botón de enviar -->
                                <button type="submit" name="entregarTarea" class="btn-entregar mt-2" title="Entregar">
                                    <i class="fa-solid fa-paper-plane"></i> Entregar
                                </button>
                            </form>

                            <!-- Estado de evaluación / comentarios -->
                            <?php
                            $info = null;
                            $q = $pdo->prepare("SELECT TOP 1 Calificacion, Comentario
                                                FROM aulavirtual.entrega_tarea
                                                WHERE Id_Tarea = ? AND Id_Estudiante = ?");
                            $q->execute([(int)$tarea['Id_Tarea'], $idUsuario]);
                            $info = $q->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <div class="status-box">
                                <?php if ($info && $info['Calificacion'] !== null): ?>
                                    <div> <strong>Evaluado</strong></div>
                                    <div> <strong>Nota:</strong> <?= (float)$info['Calificacion'] ?>/100</div>
                                    <?php if (!empty($info['Comentario'])): ?>
                                        <div> <strong>Comentario:</strong> <?= nl2br(htmlspecialchars($info['Comentario'])) ?></div>
                                    <?php else: ?>
                                        <div> Sin comentarios adicionales.</div>
                                    <?php endif; ?>
                                <?php elseif ($info): ?>
                                    <div> <strong>Entregado</strong> — Aún no evaluado.</div>
                                <?php else: ?>
                                    <div> <strong>No enviado</strong> — Aún no has enviado esta tarea.</div>
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

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
