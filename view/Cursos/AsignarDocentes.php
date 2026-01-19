<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();
$cursos   = CursoModel::obtenerCursos();
$asignacionesActuales = CursoModel::obtenerAsignaciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarProfesores'])) {
    CursoController::asignarDocentes();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar Profesores</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
/* === MODAL IGUAL AL DE USUARIOS === */
.modal-content {
    background: rgba(29,30,40,0.95) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    border-radius: 20px !important;
    box-shadow: none !important;
}

.modal-header,
.modal-footer {
    border: none !important;
}

.modal-body {
    font-size: 1rem;
    opacity: 0.95;
}

.modal-title {
    font-weight: 600;
}

.modal-footer .btn {
    border-radius: 12px;
}


body{
    font-family: 'Poppins', sans-serif;
    background-color: #1d1e28;
    color: #fff;
    padding: 40px 20px;
    text-align: center;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
}

h1{ font-size:2.3rem;font-weight:700;margin-bottom:25px; }
.card-glass{
    max-width:1100px;margin:0 auto;padding:25px;
    background:rgba(255,255,255,0.06);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.15);
}
table{ width:100%;margin-top:15px; }
th{ background:rgba(255,255,255,0.12);padding:14px; }
td{ padding:14px;border-bottom:1px solid rgba(255,255,255,0.18); }
input[type="checkbox"]{ width:20px;height:20px;cursor:pointer; }
button{
    width:100%;padding:14px;margin-top:25px;
    border-radius:12px;border:none;
    background:rgba(255,255,255,0.2);
    color:#fff;font-weight:600;
}
.volver-btn{
    display:inline-block;margin-top:35px;
    padding:12px 25px;border-radius:12px;
    background:rgba(255,255,255,0.15);
    color:#fff;text-decoration:none;
}
</style>
</head>

<body>

<h1><i class="bi bi-person-badge-fill"></i> Asignar Profesores</h1>

<div class="card-glass">

<form method="POST" id="formAsignaciones">

<table>
<thead>
<tr>
    <th>Profesor</th>
    <?php foreach ($cursos as $c): ?>
        <th><?= htmlspecialchars($c['nombre']) ?></th>
    <?php endforeach; ?>
</tr>
</thead>

<tbody>
<?php foreach ($docentes as $d): ?>
<tr>
    <td><?= htmlspecialchars($d['nombre']) ?></td>

    <?php foreach ($cursos as $c): ?>
        <td>
            <input
                type="checkbox"
                name="asignaciones[<?= $c['id'] ?>][]"
                value="<?= $d['id'] ?>"
                <?php
                if (!empty($asignacionesActuales[$c['id']]) &&
                    in_array($d['id'], $asignacionesActuales[$c['id']]))
                    echo 'checked';
                ?>
            >
        </td>
    <?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<!-- BOTÓN QUE ABRE MODAL -->
<button type="button" onclick="confirmarGuardar()">
    Guardar Asignaciones
</button>

<input type="hidden" name="asignarProfesores" value="1">

</form>
</div>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle"></i> Volver
</a>

<!-- ================= MODAL CONFIRMACIÓN ================= -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Confirmar asignaciones</h5>
        
      </div>
      <div class="modal-body">
        ¿Seguro que deseas guardar los cambios?
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" onclick="enviarFormulario()">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
function confirmarGuardar(){
    $('#confirmModal').modal('show');
}

function enviarFormulario(){
    document.getElementById('formAsignaciones').submit();
}
</script>

</body>
</html>
