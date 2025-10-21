<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/AlumnoModel.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/index.php?error=NoAutorizado");
    exit();
}

$model = new AlumnoModel($pdo);
$docenteId = $_SESSION['id_usuario'];
$alumnos = $model->obtenerAlumnosDeDocente($docenteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .table-hover tbody tr:hover { background-color: #e9ecef; }

    .btn-perfil {
        background-color: #4f83dd;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        font-size: 18px;
        transition: 0.2s;
    }

    .btn-perfil:hover {
        background-color: #3662b5;
        transform: scale(1.1);
    }

    td.text-center { vertical-align: middle; }
</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Alumnos Asignados</h2>

    <?php if (empty($alumnos)): ?>
        <div class="alert alert-warning text-center">No tiene alumnos asignados.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Curso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno['id']) ?></td>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td><?= htmlspecialchars($alumno['correo']) ?></td>
                        <td><?= htmlspecialchars($alumno['id_curso']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-perfil" data-bs-toggle="modal" data-bs-target="#perfilModal"
        data-nombre="<?= htmlspecialchars($alumno['nombre']) ?>"
        data-correo="<?= htmlspecialchars($alumno['correo']) ?>"
        data-curso="<?= htmlspecialchars($alumno['id_curso']) ?>"
        data-id="<?= htmlspecialchars($alumno['id']) ?>"
        title="Ver perfil del alumno">
        <i class="fas fa-user-circle"></i>
    </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="text-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary mt-3">Volver al Panel</a>
    </div>
</div>


<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="perfilModalLabel">Perfil del Estudiante</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><strong>ID:</strong> <span id="modal-id"></span></li>
          <li class="list-group-item"><strong>Nombre:</strong> <span id="modal-nombre"></span></li>
          <li class="list-group-item"><strong>Correo:</strong> <span id="modal-correo"></span></li>
          <li class="list-group-item"><strong>Curso:</strong> <span id="modal-curso"></span></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const perfilModal = document.getElementById('perfilModal');
    perfilModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        document.getElementById('modal-id').textContent = button.getAttribute('data-id');
        document.getElementById('modal-nombre').textContent = button.getAttribute('data-nombre');
        document.getElementById('modal-correo').textContent = button.getAttribute('data-correo');
        document.getElementById('modal-curso').textContent = button.getAttribute('data-curso');
    });
</script>
</body>
</html>
