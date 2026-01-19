<?php
require_once __DIR__ . '/../../model/CursoModel.php';

$nombreFiltro = $_GET['nombreEstudiante'] ?? '';
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$cursos = CursoModel::obtenerCursos();
$cursoSeleccionado = $_POST['idCursoMatricula'] ?? $_GET['idCursoMatricula'] ?? null;

$estudiantes = CursoModel::obtenerEstudiantes($nombreFiltro, $porPagina, $offset);
$totalEstudiantes = CursoModel::contarEstudiantes($nombreFiltro);
$totalPaginas = ceil($totalEstudiantes / $porPagina);

foreach ($estudiantes as &$est) {
    if (!isset($est['Id_Usuario'])) $est['Id_Usuario'] = $est['id'] ?? null;
    if (!isset($est['Nombre'])) $est['Nombre'] = $est['nombre'] ?? '';
}
unset($est);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['matricular'])) {
        CursoModel::matricularEstudiantes(
            $_POST['idCursoMatricula'],
            $_POST['estudiantes'] ?? []
        );
    }

    if (isset($_POST['eliminar'])) {
        CursoModel::eliminarMatricula(
            $_POST['idCursoEliminar'],
            $_POST['idEstudianteEliminar']
        );
    }

    header("Location: ?idCursoMatricula=" . ($_POST['idCursoMatricula'] ?? '') .
           "&nombreEstudiante=" . urlencode($nombreFiltro) .
           "&pagina=" . $pagina);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Matricular Estudiantes</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>


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
    font-family:'Poppins',sans-serif;
    background:#2a2b38 url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    color:#fff;padding:40px 15px;text-align:center;
}
.card-glass{
    max-width:1100px;margin:auto;padding:30px;
    background:rgba(255,255,255,.06);
    border-radius:20px;
}
.filtro-bar{
    display:flex;gap:15px;justify-content:center;
    margin-bottom:25px;flex-wrap:wrap;
}
select,input[type=text]{
    padding:12px 15px;border-radius:12px;border:none;
    min-width:260px;background:rgba(255,255,255,.12);color:#fff;
}
select option{color:#000;}
.buscar-btn{
    padding:12px 20px;background:rgba(255,255,255,.2);
    border-radius:12px;border:none;color:#fff;
}
table{width:100%;margin-top:15px;}
th{background:rgba(255,255,255,.12);padding:14px;}
td{padding:12px;border-bottom:1px solid rgba(255,255,255,.15);}
.chip,.eliminar-btn{
    width:120px;height:36px;border-radius:50px;
    display:inline-flex;align-items:center;justify-content:center;
    margin:4px;font-size:14px;border:none;
}
.chip{background:rgba(255,255,255,.22);}
.eliminar-btn{background:rgba(255,80,80,.55);color:#fff;}
.eliminar-btn:hover{background:rgba(255,40,40,.75);}
.matricular-btn{
    margin-top:20px;padding:12px 25px;border-radius:12px;
    background:rgba(40,200,90,.4);border:none;color:#fff;
}
.volver-btn{
    margin-top:25px;display:inline-block;padding:12px 25px;
    background:rgba(255,255,255,.15);border-radius:12px;
    color:#fff;text-decoration:none;
}
</style>
</head>

<body>

<h1><i class="bi bi-people-fill"></i><br>Matricular Estudiantes</h1>

<div class="card-glass">

<!-- FILTROS -->
<div class="filtro-bar">
<form method="GET">
<select name="idCursoMatricula" onchange="this.form.submit()">
<option value="">Seleccione un curso</option>
<?php foreach ($cursos as $c): ?>
<option value="<?= $c['id'] ?>" <?= $cursoSeleccionado == $c['id'] ? 'selected' : '' ?>>
<?= htmlspecialchars($c['nombre']) ?>
</option>
<?php endforeach; ?>
</select>
<input type="hidden" name="nombreEstudiante" value="<?= htmlspecialchars($nombreFiltro) ?>">
</form>

<form method="GET">
<input type="text" name="nombreEstudiante" placeholder="Buscar estudiante..."
value="<?= htmlspecialchars($nombreFiltro) ?>">
<input type="hidden" name="idCursoMatricula" value="<?= htmlspecialchars($cursoSeleccionado) ?>">
<button class="buscar-btn"><i class="bi bi-search"></i> Buscar</button>
</form>
</div>

<form method="POST">
<input type="hidden" name="idCursoMatricula" value="<?= htmlspecialchars($cursoSeleccionado) ?>">

<table>
<thead>
<tr>
<th>✔</th>
<th>Estudiante</th>
<th>Cursos actuales</th>
<th>Eliminar</th>
</tr>
</thead>
<tbody>

<?php foreach ($estudiantes as $e):
$idEst = $e['Id_Usuario'];
$cursosEst = CursoModel::obtenerCursosPorEstudiante($idEst);
?>
<tr>
<td>
<input type="checkbox" name="estudiantes[]" value="<?= $idEst ?>">
</td>
<td><?= htmlspecialchars($e['Nombre']) ?></td>
<td>
<?php foreach ($cursosEst as $c): ?>
<span class="chip"><?= htmlspecialchars($c) ?></span>
<?php endforeach; ?>
</td>
<td>
<?php foreach ($cursosEst as $cursoNom):
$idCurso = CursoModel::obtenerCursoIdPorNombre($cursoNom); ?>
<button type="button" class="eliminar-btn"
onclick="confirmarEliminar(<?= $idCurso ?>, <?= $idEst ?>)">
<?= htmlspecialchars($cursoNom) ?>
</button>
<?php endforeach; ?>
</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<?php if ($cursoSeleccionado): ?>
<button type="submit" name="matricular" class="matricular-btn">
<i class="bi bi-check-circle-fill"></i> Matricular seleccionados
</button>
<?php endif; ?>

</form>
</div>

<a href="../Home/Home.php" class="volver-btn">
<i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

<!-- MODAL CONFIRMACIÓN -->
<div class="modal fade" id="confirmModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content bg-dark text-white">
<div class="modal-header border-0">
<h5 class="modal-title">Confirmar eliminación</h5>
</div>
<div class="modal-body">
¿Seguro que deseas eliminar este curso al estudiante?
</div>
<div class="modal-footer border-0 justify-content-end">
<button class="btn btn-outline-light btn-sm" data-dismiss="modal">Cancelar</button>
<button class="btn btn-danger btn-sm" onclick="enviarEliminar()">Eliminar</button>
</div>
</div>
</div>
</div>

<form method="POST" id="formEliminar">
<input type="hidden" name="idCursoEliminar" id="idCursoEliminar">
<input type="hidden" name="idEstudianteEliminar" id="idEstudianteEliminar">
<input type="hidden" name="eliminar" value="1">
</form>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
function confirmarEliminar(idCurso,idEst){
    document.getElementById('idCursoEliminar').value=idCurso;
    document.getElementById('idEstudianteEliminar').value=idEst;
    $('#confirmModal').modal('show');
}
function enviarEliminar(){
    document.getElementById('formEliminar').submit();
}
</script>

</body>
</html>
