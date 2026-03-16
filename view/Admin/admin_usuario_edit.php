<?php
require __DIR__ . '/../../controller/auth_admin.php';

$pdo = (new CN_BD())->conectar();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    exit('ID inválido');
}

/* CONSULTA DIRECTA A LA TABLA */
$stmt = $pdo->prepare("
SELECT 
    Id_Usuario,
    Nombre,
    Email,
    Telefono,
    Rol,
    Estado
FROM aulavirtual.usuario
WHERE Id_Usuario = ?
");

$stmt->execute([$id]);
$usuario = $stmt->fetch();

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

/* MODAL */
.modal-content {
    background: rgba(29,30,40,0.95) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    border-radius: 20px !important;
}

.modal-header,
.modal-footer {
    border: none !important;
}

body{
    font-family:'Poppins',sans-serif;
    font-weight:300;
    font-size:15px;
    line-height:1.7;
    color:#c4c3ca;
    padding:40px 15px;
    background-color:#2a2b38;
    background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
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

select.form-style option{
    background:#1f272b;
    color:#fff;
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

<h1 class="text-center mb-4">
Editar usuario #<?= $usuario['Id_Usuario'] ?>
</h1>

<form id="formEditar" action="admin_usuario_update.php" method="post">

<input type="hidden" name="Id_Usuario" value="<?= $usuario['Id_Usuario'] ?>">

<input 
name="Nombre"
class="form-style"
value="<?= htmlspecialchars($usuario['Nombre']) ?>"
required
>

<input 
name="Email"
type="email"
class="form-style"
value="<?= htmlspecialchars($usuario['Email']) ?>"
required
>

<input 
name="Telefono"
class="form-style"
value="<?= htmlspecialchars($usuario['Telefono'] ?? '') ?>"
>

<select name="Rol" class="form-style">
<?php foreach($roles as $r): ?>
<option <?= $usuario['Rol']===$r?'selected':'' ?>>
<?= $r ?>
</option>
<?php endforeach; ?>
</select>

<select name="Estado" class="form-style">
<?php foreach($estados as $e): ?>
<option <?= $usuario['Estado']===$e?'selected':'' ?>>
<?= $e ?>
</option>
<?php endforeach; ?>
</select>

<!-- BOTÓN QUE ABRE MODAL -->
<button
type="button"
class="btn-custom"
data-toggle="modal"
data-target="#confirmModal"
>
Guardar cambios
</button>

<a href="admin_usuarios_list.php" class="btn-back">
Volver
</a>

</form>
</div>

<!-- MODAL CONFIRMAR -->
<div class="modal fade" id="confirmModal">
<div class="modal-dialog modal-dialog-centered">

<div class="modal-content bg-dark text-white">

<div class="modal-header">
<h5 class="modal-title">
Confirmar cambios
</h5>

<button class="close text-white" data-dismiss="modal">
&times;
</button>
</div>

<div class="modal-body">
¿Deseas guardar los cambios realizados en este usuario?
</div>

<div class="modal-footer">

<button class="btn btn-outline-light" data-dismiss="modal">
Cancelar
</button>

<button type="submit" form="formEditar" class="btn btn-danger">
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