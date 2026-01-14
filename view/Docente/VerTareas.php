<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$cursoId = $_GET['id'] ?? null;
if (!$cursoId) die("Curso no especificado.");

$curso = CursoModel::obtenerCursoPorId($cursoId);
if (!$curso) die("Curso no encontrado.");

$cursoNombre = $curso['Nombre'];

$tareaModel = new TareaModel($pdo);
$tareas = $tareaModel->obtenerTareasPorCurso($cursoId);

// Proceso de eliminación
if (isset($_GET['eliminar'])) {
    $idTarea = (int)$_GET['eliminar'];
    $tareaModel->eliminarTarea($idTarea);
    // Redirigir para evitar reenvío de GET
    header("Location: VerTareas.php?id=" . $cursoId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Tareas de <?= htmlspecialchars($cursoNombre) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

h2{
    font-weight: 600;
    text-align: center;
    margin: 35px 0;
    color: white;
}

.container{
    padding: 0;
}

.card-glass{
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    padding: 20px;
    margin: 15px 0;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    color: #fff;
}

.card-glass h5{ font-weight: 600; }
.card-glass small{ color: #dcdcdc; }

.btn-icon{
    font-size: 18px;
    padding: 8px 12px;
    border-radius: 10px;
    color: white;
    margin-left: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-edit{ background-color: #4a6cf7; }
.btn-edit:hover{ background-color: #3956d9; }

.btn-delete{ background-color: #dc3545; }
.btn-delete:hover{ background-color: #b02a37; }

.btn-eval{ background-color: #6c757d; }
.btn-eval:hover{ background-color: #5c636a; }

.volver-btn{
    border-radius: 12px;
    padding: 12px;
    margin-top: 10px;
}
</style>
</head>
<body>

<h2>Tareas del Curso: <?= htmlspecialchars($cursoNombre) ?></h2>

<div class="container">

<?php if (!empty($tareas)): ?>
    <?php foreach ($tareas as $tarea): ?>
        <div class="card-glass">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5><?= htmlspecialchars($tarea['Titulo']) ?></h5>
                    <p><?= htmlspecialchars($tarea['Descripcion']) ?></p>
                    <small>Entrega: <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></small>
                </div>

                <div>
                    <a class="btn-icon btn-edit"
                       href="EditarTarea.php?id=<?= (int)$tarea['Id_Tarea'] ?>">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- Botón eliminar que abre modal -->
                    <button class="btn-icon btn-delete" 
                            data-bs-toggle="modal" 
                            data-bs-target="#confirmModal" 
                            data-id="<?= (int)$tarea['Id_Tarea'] ?>">
                        <i class="fas fa-trash"></i>
                    </button>

                    <a class="btn-icon btn-eval"
                       href="/Aula-Virtual-Santa-Teresita/view/Docente/EvaluarTarea.php?idTarea=<?= (int)$tarea['Id_Tarea'] ?>&idCurso=<?= (int)$cursoId ?>">
                        <i class="fas fa-clipboard-check"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card-glass text-center">
        <p>No hay tareas asignadas a este curso.</p>
    </div>
<?php endif; ?>

<div class="text-center">
    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn btn-secondary volver-btn">
        Volver a Mis Cursos
    </a>
</div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que deseas eliminar esta tarea?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="#" class="btn btn-danger" id="modalConfirmDelete">Eliminar</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Configurar modal para eliminar tarea
var confirmModal = document.getElementById('confirmModal');
confirmModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var tareaId = button.getAttribute('data-id');
    var deleteBtn = document.getElementById('modalConfirmDelete');
    deleteBtn.href = 'VerTareas.php?id=<?= (int)$cursoId ?>&eliminar=' + tareaId;
});
</script>

</body>
</html>
