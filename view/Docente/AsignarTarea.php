<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// Validación de sesión
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// Validar ID del curso
$cursoId = $_GET['id'] ?? null;
if (!$cursoId) {
    die("Curso no especificado.");
}

// Obtener curso
$curso = CursoModel::obtenerCursoPorId($cursoId);
if (!$curso) {
    die("Curso no encontrado.");
}

$cursoNombre = $curso["Nombre"];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fechaEntrega = $_POST['fecha_entrega'] ?? '';

    if ($titulo !== '' && $fechaEntrega !== '') {
        $tareaModel = new TareaModel($pdo);
        $ok = $tareaModel->crearTarea($cursoId, $titulo, $descripcion, $fechaEntrega);
        $mensaje = $ok ? "Tarea creada con éxito." : "Error al crear la tarea.";
    } else {
        $mensaje = "El título y la fecha de entrega son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Tarea</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    margin: 0;
    min-height: 100vh;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: no-repeat;
    background-size: 300%;
    background-position: center;
}

.form-box {
    max-width: 600px;
    margin: 70px auto;
    padding: 35px;
    border-radius: 25px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    text-align: center;
    color: #fff;
}

h3 { font-weight: 600; margin-bottom: 20px; }
label { color: #e0e0e0; font-weight: 500; }
.form-control {
    background: rgba(255,255,255,0.08);
    border: none;
    color: white;
}
.form-control:focus {
    background: rgba(255,255,255,0.12);
    box-shadow: 0 0 10px #6a5acd;
    color: white;
}
.btn-crear {
    background-color: #d5deffff;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
}
.btn-crear:hover {
    background-color: #59ac44ff;
}
.volver-btn { margin-top: 15px; border-radius: 10px; }

/* Modal estilo Usuario Lista */
.modal-backdrop.show {
    opacity: 0.5 !important; /* igual que Usuario Lista */
}

.modal-content {
    background: rgba(30,30,40,0.95);
    color: white;
    border-radius: 20px;
    padding: 20px;
}

.modal-header {
    border-bottom: none;
    font-weight: 600;
}

.modal-footer {
    border-top: none;
}

.btn-confirm {
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 600;
}
.btn-confirm.btn-cancel {
    background: #6c757d;
    color: white;
    border: none;
}
.btn-confirm.btn-confirmar {
    background: #28a745;
    color: white;
    border: none;
}
.btn-confirm.btn-cancel:hover {
    background: #5c636a;
}
.btn-confirm.btn-confirmar:hover {
    background: #218838;
}
</style>
</head>
<body>

<div class="form-box">
    <h3>Asignar Tarea a:<br><strong><?= htmlspecialchars($cursoNombre) ?></strong></h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-info mt-2"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form id="crearTareaForm" method="POST">
        <div class="mb-3 text-start">
            <label for="titulo" class="form-label">Título de la Tarea</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3 text-start">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control" required>
        </div>

        <button type="button" class="btn btn-crear w-100" data-bs-toggle="modal" data-bs-target="#confirmModal">
            Crear Tarea
        </button>
    </form>

    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn btn-secondary volver-btn w-100">
        Volver a Mis Cursos
    </a>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar acción</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que quieres crear esta tarea?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-confirm btn-cancel" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-confirm btn-confirmar" id="confirmCreate">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('confirmCreate').addEventListener('click', function() {
    document.getElementById('crearTareaForm').submit();
});
</script>

</body>
</html>
