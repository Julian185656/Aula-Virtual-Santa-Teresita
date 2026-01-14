<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$cursos = CursoModel::obtenerCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarCurso'])) {
    CursoController::eliminarCurso($_POST['idCursoEliminar']);
    header('Location: ../Home/Home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eliminar Curso</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
body{
    font-family:'Montserrat',sans-serif;
    font-weight:300;
    font-size:15px;
    color:#c4c3ca;
    padding:40px 15px;
    text-align:center;
    background-color:#2a2b38;
    background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat:repeat;
    background-size:600px;
}

.card-glass{
    max-width:550px;
    margin:0 auto;
    padding:35px;
    background:rgba(255,255,255,0.06);
    border-radius:22px;
    border:1px solid rgba(255,255,255,0.25);
    backdrop-filter:blur(12px);
    box-shadow:0 10px 28px rgba(0,0,0,0.35);
}

.card-glass select{
    width:100%;
    padding:14px;
    margin-bottom:18px;
    border-radius:14px;
    border:none;
    background:rgba(255,255,255,0.14);
    color:#fff;
}

.card-glass select option{
    color:#000;
}

.btn-eliminar{
    width:100%;
    padding:14px;
    border-radius:14px;
    border:none;
    background:rgba(255,80,80,0.35);
    color:#fff;
    font-size:17px;
    font-weight:600;
    transition:.25s;
}

.btn-eliminar:hover{
    background:rgba(255,0,0,0.55);
}

/* MODAL */
.modal-content{
    background:#343a40;
    color:#fff;
    border-radius:18px;
}
.modal-header,
.modal-footer{
    border:none;
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-trash-fill"></i><br>
    Eliminar Curso
</h1>

<div class="card-glass">

<form id="formEliminarCurso" method="POST">

    <select name="idCursoEliminar" required>
        <option value="">Seleccione un curso</option>
        <?php foreach ($cursos as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
        <?php endforeach; ?>
    </select>

    <!-- 🔥 BOTÓN FALSO -->
    <button type="button" class="btn-eliminar" data-toggle="modal" data-target="#confirmModal">
        Eliminar
    </button>

    <input type="hidden" name="eliminarCurso" value="1">
</form>

</div>

<!-- 🔥 MODAL DE CONFIRMACIÓN -->
<div class="modal fade" id="confirmModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">Confirmar eliminación</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este curso?<br>
 
    </div>

    <div class="modal-footer">
        <button class="btn btn-outline-light" data-dismiss="modal">
            Cancelar
        </button>

        <!-- ✅ SUBMIT REAL -->
        <button class="btn btn-danger" onclick="document.getElementById('formEliminarCurso').submit()">
            Confirmar
        </button>
    </div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
