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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #fff;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

/* ==== TÍTULO ==== */
h1 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    color: #fff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

/* ==== VOLVER ==== */
.btn-volver {
    border-radius: 15px;
    padding: 10px 22px;
    text-decoration: none;
    color: #fff;
    background: rgba(255,255,255,0.15);
    transition: 0.2s;
    font-weight: 500;
}
.btn-volver:hover { background: rgba(255,255,255,0.35); }

/* ==== BUSCADOR ==== */
.search-wrapper {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 25px;
}

.search-wrapper input {
    background: rgba(255,255,255,0.12);
    border: none;
    padding: 13px;
    width: 300px;
    color: #fff;
    border-radius: 12px;
}

.search-wrapper input::placeholder { color: #ccc; }

.search-wrapper button {
    background: rgba(255,255,255,0.20);
    border: none;
    padding: 12px 18px;
    border-radius: 12px;
    color: #fff;
    transition: .2s;
}

.search-wrapper button:hover { background: rgba(255,255,255,0.35); }

/* ==== TABLA ==== */
.table-container {
    max-width: 1100px;
    margin: auto;
    padding: 25px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
    background: transparent !important;
}

table thead {
    background: rgba(255,255,255,0.10);
}

table thead th {
    padding: 15px;
    font-size: 16px;
    font-weight: 600;
}

table tbody tr {
    background: rgba(255,255,255,0.03);
    transition: .2s;
}

table tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.06);
}

table tbody tr:hover {
    background: rgba(255,255,255,0.12);
}

table td {
    padding: 15px;
}

/* ==== BOTÓN ACCIONES ==== */
.btn-info {
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #6ec8ff;
    border: none;
    color: white;
}

.btn-info:hover { background: #3ab4ff; }

/* ==== PAGINACIÓN ==== */
.pagination-container {
    text-align: center;
    margin-top: 15px;
}

.pagination a, .pagination span {
    display: inline-block;
    padding: 8px 14px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    border-radius: 10px;
    margin: 3px;
    text-decoration: none;
}

.pagination .active {
    background: rgba(255,255,255,0.45);
    font-weight: bold;
}
</style>
</head>

<body>

<div class="container text-center mb-4">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>
</div>

<h1><i class="fa-solid fa-users"></i> Alumnos Asignados</h1>

<!-- BUSCADOR -->
<div class="search-wrapper">
    <form method="get">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar estudiante...">
        <button type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<!-- TABLA -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th style="text-align:center;">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                <td><?= htmlspecialchars($alumno['correo']) ?></td>
                <td style="text-align:center;">
                    <button class="btn btn-info"
                        data-bs-toggle="modal"
                        data-bs-target="#medicoModal"
                        data-id="<?= $alumno['id'] ?>">
                        <i class="fas fa-notes-medical"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <div class="pagination-container">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a class="<?= $i == $page ? 'active' : '' ?>" 
               href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>


<!-- MODAL INFORMACIÓN MÉDICA -->
<div class="modal fade" id="medicoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="color:#fff;">
      <div class="modal-header bg-success">
        <h5 class="modal-title">Información Médica</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="form-medico">
        <div class="modal-body">
          <input type="hidden" id="medico-id" name="idEstudiante">

          <label>Contacto</label>
          <input class="form-control mb-2" id="alergias" name="alergias">

          <label>Medicamentos</label>
          <input class="form-control mb-2" id="medicamentos" name="medicamentos">

          <label>Enfermedades Crónicas</label>
          <input class="form-control mb-2" id="enfermedades" name="enfermedades">

          <label>Observaciones</label>
          <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
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
// Cargar datos médicos en modal
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

// Guardar Info Médica
document.getElementById('form-medico').addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);

    fetch('/Aula-Virtual-Santa-Teresita/controller/MedicoController.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(resp => {
        alert(resp.success ? "Información guardada." : "Error al guardar.");
    });
});
</script>

</body>
</html>
