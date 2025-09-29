<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$roles = ['Administrador','Docente','Estudiante','Padre'];
$estados = ['Activo','Inactivo'];
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Nuevo usuario</title></head>
<body>
<h1>Nuevo usuario</h1>
<form action="admin_usuario_new_post.php" method="post">
  <label>Nombre</label><br>
  <input name="Nombre" required><br><br>
  <label>Email (institucional)</label><br>
  <input name="Email" type="email" required pattern=".+@santateresita\.ac\.cr"><br><br>
  <label>Teléfono</label><br>
  <input name="Telefono"><br><br>
  <label>Contraseña</label><br>
  <input name="Contrasena" type="password" required><br><br>
  <label>Rol</label><br>
  <select name="Rol" id="rol">
    <?php foreach ($roles as $r): ?><option value="<?= $r ?>"><?= $r ?></option><?php endforeach; ?>
  </select><br><br>
  <label>Estado</label><br>
  <select name="Estado">
    <?php foreach ($estados as $e): ?><option value="<?= $e ?>"><?= $e ?></option><?php endforeach; ?>
  </select><br><br>
  <div id="camposDocente" style="display:none">
    <label>Especialidad (Docente)</label><br>
    <input name="Especialidad"><br><br>
  </div>
  <div id="camposEstudiante" style="display:none">
    <label>Grado (Estudiante)</label><br>
    <input name="Grado"><br><br>
    <label>Sección (Estudiante)</label><br>
    <input name="Seccion"><br><br>
  </div>
  <button type="submit">Crear</button>
  <a href="admin_usuarios_list.php">Volver</a>
</form>
<script>
const rolSel = document.getElementById('rol');
const doc = document.getElementById('camposDocente');
const est = document.getElementById('camposEstudiante');
rolSel.addEventListener('change', () => {
  doc.style.display = rolSel.value === 'Docente' ? 'block' : 'none';
  est.style.display = rolSel.value === 'Estudiante' ? 'block' : 'none';
});
</script>
</body></html>
