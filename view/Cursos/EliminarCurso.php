<?php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$cursos = CursoModel::obtenerCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarCurso'])) {
    CursoController::eliminarCurso($_POST['idCursoEliminar']);
    // Redirigir a la página principal después de eliminar
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

<style>

body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
    text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}
.card-glass {
    max-width: 550px;
    margin: 0 auto;
    padding: 35px;
    background: rgba(255,255,255,0.06);
    border-radius: 22px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 28px rgba(0,0,0,0.35);
    text-align: center;
}

.card-glass select,
.card-glass button {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border-radius: 14px;
    border: none;
    background: rgba(255,255,255,0.14);
    color: #fff;
}

.card-glass select option {
    color: #000;
}

/* ======== BOTÓN ELIMINAR ======== */
.card-glass button {
    width: 100%;
    padding: 14px;
    border-radius: 14px;
    border: none;
    background: rgba(255,80,80,0.35);
    color: #fff;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

.card-glass button:hover {
    background: rgba(255,0,0,0.55);
}

.volver-btn {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 10px 20px;
    border-radius: 15px;
    color: #fff;
    text-decoration: none;
    margin-top: 30px;
    transition: 0.2s;
}

.volver-btn:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1>
    <i class="bi bi-trash-fill"></i><br>
    Eliminar Curso
</h1>

<form method="POST">
<div class="card-glass">

    <form method="POST">

        <select name="idCursoEliminar" required>
            <option value="">Seleccione un curso</option>
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="eliminarCurso">
            Eliminar
        </button>
    </form>

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
                ¿Deseas eliminar este curso?
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit" name="eliminarCurso" class="btn btn-danger">
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
