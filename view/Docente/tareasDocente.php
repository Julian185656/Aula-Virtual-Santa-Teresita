<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";


if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$idDocente = $_SESSION['id_usuario'];
$idCurso = intval($_GET['id_curso'] ?? 0);

$cursoModel = new CursoModel($pdo);
$tareaModel = new TareaModel($pdo);


$curso = $cursoModel->obtenerCursoPorId($idCurso);
if (!$curso || $curso['Id_Docente'] != $idDocente) {
    die("<div style='margin:50px; color:red; font-weight:bold;'>❌ No tienes permiso para administrar este curso.</div>");
}


$tareas = $tareaModel->obtenerTareasPorCurso($idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tareas del curso - <?= htmlspecialchars($curso['Nombre']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color:#f8f9fa;">
<div class="container mt-5">

 
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary"><?= htmlspecialchars($curso['Nombre']) ?></h2>
    <p class="text-muted">Gestión de tareas del curso</p>
    <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php" class="btn btn-outline-secondary mb-3">
      ⬅ Volver al panel de cursos
    </a>
  </div>


  <div class="text-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaTarea">
      <i class="fa fa-plus"></i> Nueva Tarea
    </button>
  </div>


  <?php if (empty($tareas)): ?>
    <div class="alert alert-warning text-center">Aún no hay tareas creadas para este curso.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Descripción</th>
          <th>Fecha de Entrega</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tareas as $index => $tarea): ?>
        <tr>
          <td class="text-center"><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($tarea['Titulo']) ?></td>
          <td><?= htmlspecialchars($tarea['Descripcion']) ?></td>
          <td class="text-center"><?= htmlspecialchars($tarea['Fecha_Entrega']) ?></td>
          <td class="text-center">
            <button class="btn btn-sm btn-warning me-2 editar-btn"
                    data-id="<?= $tarea['Id_Tarea'] ?>"
                    data-titulo="<?= htmlspecialchars($tarea['Titulo']) ?>"
                    data-descripcion="<?= htmlspecialchars($tarea['Descripcion']) ?>"
                    data-fecha="<?= htmlspecialchars($tarea['Fecha_Entrega']) ?>"
                    data-bs-toggle="modal" data-bs-target="#modalEditarTarea">
              ✏️
            </button>
            <form action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php" method="POST" style="display:inline;">
              <input type="hidden" name="accion" value="eliminar_tarea">
              <input type="hidden" name="id_tarea" value="<?= $tarea['Id_Tarea'] ?>">
              <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta tarea?')">🗑️</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>


<div class="modal fade" id="modalNuevaTarea" tabindex="-1">
  <div class="modal-dialog">
    <form action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php" method="POST" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">📝 Crear nueva tarea</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="crear_tarea">
        <input type="hidden" name="id_curso" value="<?= $idCurso ?>">
        <input type="text" name="titulo" class="form-control mb-3" placeholder="Título" required>
        <textarea name="descripcion" class="form-control mb-3" placeholder="Descripción" rows="3" required></textarea>
        <label class="form-label">📅 Fecha de entrega:</label>
        <input type="date" name="fecha_entrega" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary w-100">Guardar tarea</button>
      </div>
    </form>
  </div>
</div>


<div class="modal fade" id="modalEditarTarea" tabindex="-1">
  <div class="modal-dialog">
    <form action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php" method="POST" class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">✏️ Editar tarea</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="editar_tarea">
        <input type="hidden" id="editarIdTarea" name="id_tarea">
        <input type="hidden" name="id_curso" value="<?= $idCurso ?>">
        <input type="text" id="editarTitulo" name="titulo" class="form-control mb-3" required>
        <textarea id="editarDescripcion" name="descripcion" class="form-control mb-3" rows="3" required></textarea>
        <label class="form-label">📅 Fecha de entrega:</label>
        <input type="date" id="editarFecha" name="fecha_entrega" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning w-100">Actualizar tarea</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll(".editar-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("editarIdTarea").value = btn.dataset.id;
    document.getElementById("editarTitulo").value = btn.dataset.titulo;
    document.getElementById("editarDescripcion").value = btn.dataset.descripcion;
    document.getElementById("editarFecha").value = btn.dataset.fecha;
  });
});
</script>
</body>
</html>
