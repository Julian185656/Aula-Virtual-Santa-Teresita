<?php
require __DIR__ . '/../../controller/auth_admin.php';

$pdo = (new CN_BD())->conectar();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    exit('ID inválido');
}

$stmt = $pdo->prepare("EXEC aulavirtual.obtenerUsuarioDetalle ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();
$stmt->closeCursor();

if (!$usuario) {
    exit('Usuario no encontrado');
}

$roles = ['Administrador', 'Docente', 'Estudiante', 'Padre'];
$estados = ['Activo', 'Inactivo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar usuario</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>



/* MODAL — MISMO ESTILO QUE ADMIN */
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



select.form-style option {
    background: #1f272b;
    color: #fff;
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
</style>
</head>

<body>

<div class="card-container">
<h1 class="text-center mb-4">Editar usuario #<?= $usuario['Id_Usuario'] ?></h1>

<form id="formEditar" action="admin_usuario_update.php" method="post">
    <input type="hidden" name="Id_Usuario" value="<?= $usuario['Id_Usuario'] ?>">

    <input name="Nombre" class="form-style" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
    <input name="Email" type="email" class="form-style" value="<?= htmlspecialchars($usuario['Email']) ?>" required>
    <input name="Telefono" class="form-style" value="<?= htmlspecialchars($usuario['Telefono'] ?? '') ?>">

    <select name="Rol" class="form-style">
        <?php foreach($roles as $r): ?>
            <option <?= $usuario['Rol']===$r?'selected':'' ?>><?= $r ?></option>
        <?php endforeach; ?>
    </select>

    <select name="Estado" class="form-style">
        <?php foreach($estados as $e): ?>
            <option <?= $usuario['Estado']===$e?'selected':'' ?>><?= $e ?></option>
        <?php endforeach; ?>
    </select>

    <!-- 🔥 BOTÓN FALSO -->
    <button type="button" class="btn-custom" data-toggle="modal" data-target="#confirmModal">
        Guardar cambios
    </button>

    <a href="admin_usuarios_list.php" class="btn-back">Volver</a>
</form>
</div>

<!-- 🔥 MODAL IDÉNTICO AL LISTADO -->
<div class="modal fade" id="confirmModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content bg-dark text-white border-0">
    <div class="modal-header border-0">
        <h5 class="modal-title">Confirmar cambios</h5>
        <button class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        ¿Deseas guardar los cambios realizados en este usuario?
    </div>

    <div class="modal-footer border-0">
        <button class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>

        <!-- ✅ SUBMIT REAL -->
        <button class="btn btn-danger" onclick="document.getElementById('formEditar').submit()">
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
