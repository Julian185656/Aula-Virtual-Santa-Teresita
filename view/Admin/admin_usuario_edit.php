<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); exit('ID inválido'); }

$stmt = $pdo->prepare("CALL obtenerUsuarioDetalle(?)");
$stmt->execute([$id]);
$usuario = $stmt->fetch();
$stmt->closeCursor();
if (!$usuario) { exit('Usuario no encontrado'); }

$roles = ['Administrador','Docente','Estudiante','Padre'];
$estados = ['Activo','Inactivo'];
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Editar usuario</title></head>
<body>
<h1>Editar usuario #<?= $usuario['Id_Usuario'] ?></h1>
<form action="admin_usuario_update.php" method="post">
  <input type="hidden" name="Id_Usuario" value="<?= $usuario['Id_Usuario'] ?>">

  <label>Nombre</label><br>
  <input name="Nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required><br><br>

  <label>Email (institucional)</label><br>
  <input name="Email" type="email" value="<?= htmlspecialchars($usuario['Email']) ?>" required pattern=".+@santateresita\.ac\.cr"><br><br>

  <label>Teléfono</label><br>
  <input name="Telefono" value="<?= htmlspecialchars($usuario['Telefono'] ?? '') ?>"><br><br>

  <label>Nueva contraseña (opcional)</label><br>
  <input name="Contrasena" type="password" placeholder="Dejar vacío para no cambiar"><br><br>

  <label>Rol</label><br>
  <select name="Rol" id="rol">
    <?php foreach ($roles as $r): ?>
      <option value="<?= $r ?>" <?= $usuario['Rol']===$r?'selected':''?>><?= $r ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <label>Estado</label><br>
  <select name="Estado">
    <?php foreach ($estados as $e): ?>
      <option value="<?= $e ?>" <?= $usuario['Estado']===$e?'selected':''?>><?= $e ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <div id="camposDocente" style="display: <?= $usuario['Rol']==='Docente'?'block':'none' ?>">
    <label>Especialidad (Docente)</label><br>
    <input name="Especialidad" value="<?= htmlspecialchars($usuario['Especialidad'] ?? '') ?>"><br><br>
  </div>

  <div id="camposEstudiante" style="display: <?= $usuario['Rol']==='Estudiante'?'block':'none' ?>">
    <label>Grado (Estudiante)</label><br>
    <input name="Grado" value="<?= htmlspecialchars($usuario['Grado'] ?? '') ?>"><br><br>
    <label>Sección (Estudiante)</label><br>
    <input name="Seccion" value="<?= htmlspecialchars($usuario['Seccion'] ?? '') ?>"><br><br>
  </div>

  <button type="submit">Guardar cambios</button>
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
