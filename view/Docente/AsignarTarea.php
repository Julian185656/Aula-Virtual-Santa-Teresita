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
    font-family:'Poppins',sans-serif;
    font-size:15px;
    color:#c4c3ca;
    min-height:100vh;
    background:#2a2b38 url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
}



#confirmModal .modal-header,
#confirmModal .modal-footer {
    border: none;
}



.form-box{
    max-width:600px;
    margin:70px auto;
    padding:35px;
    border-radius:25px;
    background:rgba(255,255,255,.05);
    backdrop-filter:blur(10px);
    box-shadow:0 8px 30px rgba(0,0,0,.3);
    color:#fff;
    text-align:center;
}

h3{font-weight:600;margin-bottom:20px;}

label{color:#e0e0e0;font-weight:500;}

.form-control{
    background:rgba(255,255,255,.08);
    border:none;
    color:#fff;
}
.form-control:focus{
    background:rgba(255,255,255,.12);
    box-shadow:none;
    color:#fff;
}

.btn-crear{
    background:#d5deff;
    border:none;
    padding:12px;
    border-radius:10px;
    font-weight:600;
}
.btn-crear:hover{
    background:#59ac44;
}

.volver-btn{
    margin-top:15px;
    border-radius:10px;
}


.modal-backdrop.show{
    opacity:.6;
}

.modal-dark{
    background:#343a40;
    color:#fff;
    border-radius:8px;
}

.modal-dark .modal-header{
    border-bottom:1px solid #495057;
}

.modal-dark .modal-footer{
    border-top:1px solid #495057;
}

.modal-dark .modal-body{
    color:#e0e0e0;
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
            <label>Título de la Tarea</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3 text-start">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3 text-start">
            <label>Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" class="form-control" required>
        </div>

        <button type="button"
                class="btn btn-crear w-100"
                onclick="abrirConfirmacion()">
            Crear Tarea
        </button>
    </form>

    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php"
       class="btn btn-secondary volver-btn w-100">
        Volver a Mis Cursos
    </a>
</div>

<!-- MODAL CONFIRMACIÓN (MISMO QUE USUARIOS) -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-dark">

      <div class="modal-header">
        <h5 class="modal-title">Confirmar acción</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ¿Estás seguro que deseas crear esta tarea?
      </div>

      <div class="modal-footer">
        <button type="button"
                class="btn btn-outline-light"
                data-bs-dismiss="modal">
            Cancelar
        </button>
        <button type="button"
                class="btn btn-danger"
                onclick="confirmarEnvio()">
            Confirmar
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let modalConfirm;

function abrirConfirmacion(){
    modalConfirm = new bootstrap.Modal(document.getElementById('confirmModal'));
    modalConfirm.show();
}

function confirmarEnvio(){
    document.getElementById('crearTareaForm').submit();
}
</script>

</body>
</html>
