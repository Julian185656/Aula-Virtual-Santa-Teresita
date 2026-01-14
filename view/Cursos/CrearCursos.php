<?php
require_once __DIR__ . '/../../controller/CursoController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearCurso'])) {
    CursoController::crearCurso();
    // Redirige a la página principal después de crear el curso
    header('Location: ../Home/Home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Curso</title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Montserrat', sans-serif;
    color: #c4c3ca;
    padding: 40px 15px;
    text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
}

.card-glass {
    max-width: 550px;
    margin: 0 auto;
    padding: 30px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    text-align: center;
}

.card-glass input,
.card-glass textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 15px;
    background: rgba(255,255,255,0.12);
    border: none;
    color: #fff;
}

.card-glass button {
    width: 100%;
    padding: 12px;
    border-radius: 15px;
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    margin-bottom: 10px;
}

.card-glass button:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-journal-plus"></i><br>
    Crear Curso
</h1>

<form method="POST">

<div class="card-glass">

    <input type="text" name="nombre" placeholder="Nombre del curso" required>
    <textarea name="descripcion" rows="3" placeholder="Descripción del curso" required></textarea>

    <!-- Botón que abre el modal -->
    <button type="button" data-toggle="modal" data-target="#confirmModal">
        Crear Curso
    </button>

    <!-- Botón de volver dentro del mismo card -->
    <a href="../Home/Home.php" class="btn" style="background: rgba(255,255,255,0.15); color: #fff; border-radius: 15px; display:block; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-0">

            <div class="modal-header border-0">
                <h5 class="modal-title">Confirmar acción</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                ¿Deseas crear este curso?
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit" name="crearCurso" class="btn btn-danger">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>

</form>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
