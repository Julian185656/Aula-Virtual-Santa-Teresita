<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/AlumnoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MedicoModel.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/index.php?error=NoAutorizado");
    exit();
}

$model = new AlumnoModel($pdo);
$medModel = new MedicoModel($pdo);
$docenteId = $_SESSION['id_usuario'];

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$search = $_GET['search'] ?? '';
$offset = ($page - 1) * $limit;


$alumnos = $model->obtenerAlumnosDeDocentePaginado($docenteId, $limit, $offset, $search);
$totalAlumnos = $model->contarAlumnos($docenteId, $search);
$totalPaginas = ceil($totalAlumnos / $limit);
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
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.btn-volver {
    border-radius: 15px;
    padding: 8px 18px;
    text-decoration: none;
    color: #fff;
    background: rgba(255,255,255,0.15);
    transition: 0.2s;
}
.btn-volver:hover {
    background: rgba(255,255,255,0.35);
}

.search-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 25px;
}
.search-form input[type="text"] {
    padding: 10px 15px;
    border-radius: 15px;
    border: none;
    min-width: 250px;
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.search-form input[type="text"]::placeholder {
    color: #ddd;
}
.search-form button {
    padding: 10px 20px;
    border-radius: 15px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
    background: rgba(255,255,255,0.15);
    transition: 0.2s ease;
}
.search-form button:hover {
    background: rgba(255,255,255,0.35);
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
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
table thead {
    background: rgba(255, 255, 255, 0.1);
    text-align: left;
    font-weight: bold;
}
table th, table td {
    padding: 12px 15px;
    vertical-align: middle;
}
table tr:nth-child(even) {
    background: rgba(255,255,255,0.02);
}
table tr:hover {
    background: rgba(255,255,255,0.1);
}

.btn-info {
    border-radius: 50%;
    width: 38px;
    height: 38px;
    padding: 0;
}

.pagination a {
    color: #fff;
    background: rgba(255,255,255,0.1);
    border: none;
    margin: 0 3px;
}
.pagination a:hover {
    background: rgba(255,255,255,0.3);
}
.pagination .active .page-link {
    background: rgba(255,255,255,0.25);
}

.modal-header.bg-primary {
    background-color: #0d6efd !important;
}
.modal-header.bg-success {
    background-color: #198754 !important;
}
.modal-content {
    background: rgba(255,255,255,0.05);
    color: #fff;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
}
.modal-body input, .modal-body textarea {
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
}
.modal-body input::placeholder, .modal-body textarea::placeholder {
    color: #ddd;
}
</style>

</head>
<body>

<div class="container">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h1>Alumnos Asignados</h1>


    <form method="get" class="mb-3">
        <input type="text" name="search" placeholder="Buscar por nombre" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
    </form>

    <?php if (empty($alumnos)): ?>
        <div class="alert alert-warning text-center">No hay alumnos que coincidan.</div>
    <?php else: ?>
    <div class="table-container">
        <table class="table table-borderless">
            <thead>
                <tr>
                  
                    <th>Nombre</th>
                    <th>Correo</th>
               
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    
                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                    <td><?= htmlspecialchars($alumno['correo']) ?></td>
                 
                    <td>
                   

                        <button class="btn btn-sm btn-info"
                            data-bs-toggle="modal"
                            data-bs-target="#medicoModal"
                            data-id="<?= htmlspecialchars($alumno['id']) ?>"
                            data-nombre="<?= htmlspecialchars($alumno['nombre']) ?>"
                        ><i class="fas fa-notes-medical"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <nav>
        <ul class="pagination justify-content-center mt-3">
            <?php for($i=1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <?php endif; ?>
</div>


<div class="modal fade" id="perfilModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Perfil del Estudiante</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
        
          <li class="list-group-item"><strong>Nombre:</strong> <span id="modal-nombre"></span></li>
          <li class="list-group-item"><strong>Correo:</strong> <span id="modal-correo"></span></li>
        
        </ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="medicoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Información Médica</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="form-medico">
        <div class="modal-body">
          <input type="hidden" id="medico-id" name="idEstudiante">
          <div class="mb-3"><label>Numero de contacto</label><input type="text" class="form-control" id="alergias" name="alergias"></div>
          <div class="mb-3"><label>Medicamentos</label><input type="text" class="form-control" id="medicamentos" name="medicamentos"></div>
          <div class="mb-3"><label>Enfermedades Crónicas</label><input type="text" class="form-control" id="enfermedades" name="enfermedades"></div>
          <div class="mb-3"><label>Observaciones</label><textarea class="form-control" id="observaciones" name="observaciones"></textarea></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const perfilModal = document.getElementById('perfilModal');
perfilModal.addEventListener('show.bs.modal', event => {
    const b = event.relatedTarget;
    
    document.getElementById('modal-nombre').textContent = b.getAttribute('data-nombre');
    document.getElementById('modal-correo').textContent = b.getAttribute('data-correo');
    document.getElementById('modal-curso').textContent = b.getAttribute('data-curso');
});

const medicoModal = document.getElementById('medicoModal');
medicoModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    document.getElementById('medico-id').value = id;

    fetch(`/Aula-Virtual-Santa-Teresita/controller/MedicoController.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('alergias').value = data?.Alergias || '';
            document.getElementById('medicamentos').value = data?.Medicamentos || '';
            document.getElementById('enfermedades').value = data?.EnfermedadesCronicas || '';
            document.getElementById('observaciones').value = data?.Observaciones || '';
        });
});

document.getElementById('form-medico').addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch('/Aula-Virtual-Santa-Teresita/controller/MedicoController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(resp => {
        if(resp.success){
            alert('Información médica guardada.');
            medicoModal.querySelector('.btn-close').click();
        } else {
            alert('Error al guardar: ' + (resp.msg || ''));
        }
    });
});
</script>

</body>
</html>
