<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';


$pdo = (new CN_BD())->conectar();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

$stmt = $pdo->prepare("EXEC aulavirtual.obtenerUsuarioDetalle ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();
$stmt->closeCursor();
if (!$usuario) { exit('Usuario no encontrado'); }

$roles = ['Administrador','Docente','Estudiante','Padre'];
$estados = ['Activo','Inactivo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar usuario</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

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
	background-position: bottom center;
	background-repeat: no-repeat;
	background-size: 300%;

	overflow-x: hidden;
}

.card-container {
    max-width: 500px;
    margin: 0 auto;
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    padding: 35px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 4px 18px rgba(0,0,0,0.4);
}

h1 {
    text-align: center;
    font-size: 1.7rem;
    margin-bottom: 25px;
    color: #fff;
    text-shadow: 0 2px 6px rgba(0,0,0,0.5);
}

.form-group {
    margin-bottom: 18px;
}

.form-style {
    width: 100%;
    padding: 12px 15px;
    border-radius: 12px;
    border: none;
    outline: none;
    font-size: 0.95rem;
    background: rgba(255,255,255,0.12);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.25);
}

.form-style::placeholder {
    color: #ddd;
}

select.form-style option {
    background: #1f272b;
    color: #fff;
}

.btn-custom {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 12px;
    color: #fff;
    cursor: pointer;
    transition: 0.25s ease;
    font-size: 1rem;
}

.btn-custom:hover {
    background: rgba(255,255,255,0.35);
}

.btn-back {
    display: block;
    text-align: center;
    margin-top: 12px;
    padding: 10px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 12px;
    color: #fff;
    text-decoration: none;
    transition: 0.25s;
}

.btn-back:hover {
    background: rgba(255,255,255,0.35);
}
</style>

</head>
<body>

<div class="card-container">

    <h1>Editar usuario #<?= $usuario['Id_Usuario'] ?></h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-custom"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-custom"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <form action="admin_usuario_update.php" method="post">

        <input type="hidden" name="Id_Usuario" value="<?= $usuario['Id_Usuario'] ?>">

        <div class="form-group">
            <input name="Nombre" class="form-style" placeholder="Nombre completo"
                   value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>
        </div>

        <div class="form-group">
            <input name="Email" type="email" class="form-style"
                   placeholder="Email institucional"
                   value="<?= htmlspecialchars($usuario['Email']) ?>"
                   required pattern=".+@santateresita\.ac\.cr">
        </div>

        <div class="form-group">
            <input name="Telefono" class="form-style" placeholder="Teléfono"
                   value="<?= htmlspecialchars($usuario['Telefono'] ?? '') ?>">
        </div>

        <div class="form-group">
            <input name="Contrasena" type="password" class="form-style"
                   placeholder="Nueva contraseña">
        </div>

        <div class="form-group">
            <select name="Rol" id="rol" class="form-style">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r ?>" <?= $usuario['Rol']===$r?'selected':'' ?>><?= $r ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <select name="Estado" class="form-style">
                <?php foreach ($estados as $e): ?>
                    <option value="<?= $e ?>" <?= $usuario['Estado']===$e?'selected':'' ?>><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-custom">
            <i class=""></i> Guardar cambios
        </button>

        <a href="admin_usuarios_list.php" class="btn-back">
            <i class=""></i> Volver
        </a>

    </form>

</div>

</body>
</html>
