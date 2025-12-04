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
<title>Alumnos Asignados</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
	font-family: 'Poppins', sans-serif;
	font-weight: 300;
	font-size: 15px;
	line-height: 1.7;
	color: #c4c3ca;
	padding: 40px 15px;

	background-color: #2a2b38;
	background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
	background-position: bottom center;
	background-repeat: no-repeat;
	background-size: 300%;
	overflow-x: hidden;
}

.container {
    max-width: 1100px;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: white;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.table-container {
    overflow-x: auto;
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
}

table, table thead th, table tbody td {
    color: #fff !important;
}

table thead {
    background: rgba(255, 255, 255, 0.1);
    text-align: left;
}

table tr:nth-child(even) {
    background: rgba(255,255,255,0.03);
}

table tr:hover {
    background: rgba(255,255,255,0.1);
}

.btn-perfil {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff !important;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    transition: .2s;
}

.btn-perfil:hover {
    background: rgba(255,255,255,0.4);
    transform: scale(1.1);
}

.btn-volver {
    border-radius: 15px;
    padding: 10px 20px;
    border: 1px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.15);
    color: white;
    text-decoration: none;
}

.btn-volver:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php"
       class="btn-volver mb-3">
       <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h1>Alumnos Asignados</h1>

    <?php if (empty($alumnos)): ?>
        <div class="alert alert-warning text-center">No tiene alumnos asignados.</div>
    <?php else: ?>

    <div class="table-container">
        <table class="table table-borderless">
            <thead>
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
                    <td><?= $alumno['id'] ?></td>
                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                    <td><?= htmlspecialchars($alumno['correo']) ?></td>
                    <td><?= htmlspecialchars($alumno['id_curso']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-perfil"
                            data-bs-toggle="modal"
                            data-bs-target="#perfilModal"
                            data-nombre="<?= htmlspecialchars($alumno['nombre']) ?>"
                            data-correo="<?= htmlspecialchars($alumno['correo']) ?>"
                            data-curso="<?= htmlspecialchars($alumno['id_curso']) ?>"
                            data-id="<?= htmlspecialchars($alumno['id']) ?>"
                            title="Ver perfil del alumno"
                        >
                            <i class="fas fa-user-circle"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
    <?php endif; ?>

</div>


<!-- MODAL PERFIL -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Perfil del Estudiante</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const perfilModal = document.getElementById('perfilModal');
perfilModal.addEventListener('show.bs.modal', event => {
    const b = event.relatedTarget;
    document.getElementById('modal-id').textContent = b.getAttribute('data-id');
    document.getElementById('modal-nombre').textContent = b.getAttribute('data-nombre');
    document.getElementById('modal-correo').textContent = b.getAttribute('data-correo');
    document.getElementById('modal-curso').textContent = b.getAttribute('data-curso');
});
</script>

</body>
</html>
