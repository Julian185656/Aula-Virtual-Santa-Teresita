<?php
require_once __DIR__ . '/../../controller/CursoController.php';
require_once __DIR__ . '/../../model/CursoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearCurso'])) {
    CursoController::crearCurso();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Curso</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
  body {
        font-family: 'Poppins', sans-serif;
        font-weight: 300;
        font-size: 15px;
        line-height: 1.7;
        color: #c4c3ca;
        padding: 40px 15px;
        background-color: #2a2b38;
        background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    }

.card-container{
    max-width:500px;
    margin:auto;
    background:rgba(255,255,255,.07);
    padding:35px;
    border-radius:20px;
}

.form-style{
    width:100%;
    padding:12px;
    background:rgba(255,255,255,.12);
    border-radius:12px;
    border:1px solid rgba(255,255,255,.25);
    color:#fff;
    margin-bottom:15px;
}

textarea.form-style{
    resize:none;
}

.btn-custom,.btn-back{
    width:100%;
    padding:12px;
    border-radius:12px;
    background:rgba(255,255,255,.12);
    color:#fff;
    border:1px solid rgba(255,255,255,.35);
}

.btn-back{
    display:block;
    text-align:center;
    margin-top:10px;
}

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

</style>
</head>

<body>

<div class="card-container">
<h1 class="text-center mb-4">
<i class="bi bi-journal-plus"></i><br>
Crear Curso
</h1>

<form id="formCrearCurso" method="POST">
    <input type="text" name="nombre" class="form-style" placeholder="Nombre del curso" required>

    <textarea name="descripcion" rows="3" class="form-style" placeholder="Descripción del curso" required></textarea>

    <!-- 🔥 BOTÓN FALSO -->
    <button type="button" class="btn-custom" data-toggle="modal" data-target="#confirmModal">
        Crear Curso
    </button>

    <a href="../Home/Home.php" class="btn-back">
        Volver
    </a>

    <input type="hidden" name="crearCurso" value="1">
</form>
</div>

<!-- 🔥 CAJA DE CONFIRMACIÓN -->
<div class="modal fade" id="confirmModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

    <div class="modal-header">
        <h5 class="modal-title">Confirmar acción</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        ¿Estás seguro de que deseas crear este curso?
    </div>

    <div class="modal-footer">
        <button class="btn btn-outline-light" data-dismiss="modal">
            Cancelar
        </button>

        <!-- ✅ SUBMIT REAL -->
        <button class="btn btn-danger" onclick="document.getElementById('formCrearCurso').submit()">
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
