<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/EncuestaModel.php";

// Validación de acceso
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$docenteId = $_SESSION['id_usuario'];
$cursoId = $_GET['idCurso'] ?? 0;

if (!$cursoId) {
    die("Curso no válido");
}

// ELIMINAR ENCUESTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarId'])) {
    $encuestaId = intval($_POST['eliminarId']);
    EncuestaModel::eliminarEncuesta($encuestaId);
    header("Location: EncuestasCurso.php?idCurso=$cursoId");
    exit();
}

// Crear nueva encuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
    $titulo = trim($_POST['titulo']);
    if ($titulo !== '') {
        EncuestaModel::crearEncuesta($cursoId, $titulo);
        header("Location: EncuestasCurso.php?idCurso=$cursoId");
        exit();
    }
}

// Obtener encuestas del curso
$encuestas = EncuestaModel::obtenerEncuestasCurso($cursoId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Encuestas del Curso</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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

.container {
    max-width: 900px;
}

/* Tarjeta glass */
.card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    margin-bottom: 30px;
}

.card-header {
    background: rgba(255,255,255,0.08);
    border-bottom: none;
    color: #fff;
    font-weight: 600;
    border-radius: 20px 20px 0 0;
}

.btn-custom {
    border-radius: 15px;
    padding: 8px 18px;
    background: rgba(255,255,255,0.15);
    color: white;
    border: none;
}
.btn-custom:hover {
    background: rgba(255,255,255,0.3);
}

.list-group-item {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
}
.list-group-item:hover {
    background: rgba(255,255,255,0.15);
}

.input-custom {
    background: rgba(255,255,255,0.15);
    border-radius: 15px;
    border: none;
    color: #fff;
}
.input-custom::placeholder {
    color: #ddd;
}

.btn-action {
    border-radius: 10px;
    margin-left: 5px;
    border: 1px solid rgba(255,255,255,0.3);
}
.btn-action:hover {
    background: rgba(255,255,255,0.3);
}
</style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php"
       class="btn btn-custom mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver a Mis Cursos
    </a>

    <h1 class="text-center text-white mb-4">Encuestas del Curso</h1>

   
    <div class="card">
        <div class="card-header">
            <i class="bi bi-plus-circle"></i> Crear nueva encuesta
        </div>
        <div class="card-body">
            <form method="post" class="d-flex gap-3 flex-wrap">
                <input type="text" name="titulo" class="form-control input-custom"
                       placeholder="Título de la nueva encuesta" required>

                <button type="submit" class="btn btn-custom">
                    <i class="bi bi-plus-circle-fill"></i> Crear
                </button>
            </form>
        </div>
    </div>

  
    <div class="card">
        <div class="card-header">
            <i class="bi bi-list-task"></i> Encuestas creadas
        </div>
        <div class="card-body">

            <?php if (!empty($encuestas)): ?>
                <ul class="list-group">

                <?php foreach ($encuestas as $encuesta): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <?= htmlspecialchars($encuesta['Titulo']) ?>

                        <div class="d-flex">

                            <a href="PreguntasEncuesta.php?idEncuesta=<?= $encuesta['Id_Encuesta'] ?>" 
                               class="btn btn-primary btn-sm btn-action">
                                <i class="bi bi-pencil-square"></i> Preguntas
                            </a>

                            <a href="ResultadosEncuesta.php?idEncuesta=<?= $encuesta['Id_Encuesta'] ?>" 
                               class="btn btn-info btn-sm btn-action">
                                <i class="bi bi-bar-chart-fill"></i> Resultados
                            </a>

                            <form method="post" style="display:inline;"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta encuesta?');">
                                <input type="hidden" name="eliminarId" value="<?= $encuesta['Id_Encuesta'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm btn-action">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </form>

                        </div>

                    </li>
                <?php endforeach; ?>

                </ul>

            <?php else: ?>

                <p class="text-center mt-3" style="color:#fff;">No hay encuestas creadas aún.</p>

            <?php endif; ?>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
