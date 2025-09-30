<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$roles = ['Administrador','Docente','Estudiante','Padre'];
$estados = ['Activo','Inactivo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo usuario</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
 <link rel="stylesheet" href="../Styles/New.css">
</head>
<body>

<div class="card-container">
    <h1>Nuevo usuario</h1>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-custom"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-custom"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <form action="admin_usuario_new_post.php" method="post">
        <div class="form-group">
            <input name="Nombre" class="form-style" placeholder="Nombre completo" required>
        </div>
        <div class="form-group">
            <input name="Email" type="email" class="form-style" placeholder="Email institucional" required pattern=".+@santateresita\.ac\.cr">
        </div>
        <div class="form-group">
            <input name="Telefono" class="form-style" placeholder="Teléfono">
        </div>
        <div class="form-group">
            <input name="Contrasena" type="password" class="form-style" placeholder="Contraseña" required>
        </div>
        <div class="form-group">
            <select name="Rol" id="rol" class="form-style">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r ?>"><?= $r ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <select name="Estado" class="form-style">
                <?php foreach ($estados as $e): ?>
                    <option value="<?= $e ?>"><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="camposDocente" style="display:none">
            <input name="Especialidad" class="form-style" placeholder="Especialidad (Docente)">
        </div>
        <div id="camposEstudiante" style="display:none">
            <input name="Grado" class="form-style" placeholder="Grado (Estudiante)">
            <input name="Seccion" class="form-style" placeholder="Sección (Estudiante)">
        </div>
        <button type="submit" class="btn-custom">Crear</button>
        <a href="admin_usuarios_list.php" class="btn-back">Volver</a>
    </form>
</div>

<script>
const rolSel = document.getElementById('rol');
const doc = document.getElementById('camposDocente');
const est = document.getElementById('camposEstudiante');

rolSel.addEventListener('change', () => {
    doc.style.display = rolSel.value === 'Docente' ? 'block' : 'none';
    est.style.display = rolSel.value === 'Estudiante' ? 'block' : 'none';
});
</script>
</body>
</html>
