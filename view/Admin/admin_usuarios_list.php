<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM vw_usuarios_detalle WHERE Nombre LIKE ? OR Email LIKE ? ORDER BY Id_Usuario DESC");
    $like = "%$search%";
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM vw_usuarios_detalle ORDER BY Id_Usuario DESC");
}
$usuarios = $stmt->fetchAll();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Usuarios - Administración</title>
<style>
table{border-collapse:collapse;width:100%}th,td{border:1px solid #ccc;padding:8px;text-align:left}th{background:#f4f4f4}
.actions a,.actions form{display:inline-block;margin-right:6px}
</style></head>
<body>
<h1>Usuarios</h1>
<form method="get">
  <input type="text" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($search) ?>">
  <button type="submit">Buscar</button>
  <a href="admin_usuario_new.php">+ Nuevo usuario</a>
</form>
<table>
<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
<tbody>
<?php foreach ($usuarios as $u): ?>
<tr>
  <td><?= $u['Id_Usuario'] ?></td>
  <td><?= htmlspecialchars($u['Nombre']) ?></td>
  <td><?= htmlspecialchars($u['Email']) ?></td>
  <td><?= htmlspecialchars($u['Rol']) ?></td>
  <td><?= htmlspecialchars($u['Estado']) ?></td>
  <td class="actions">
    <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>">Editar</a>
    <form action="admin_usuario_toggle.php" method="post">
      <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
      <input type="hidden" name="Estado" value="<?= $u['Estado']==='Activo'?'Inactivo':'Activo' ?>">
      <button type="submit"><?= $u['Estado']==='Activo'?'Desactivar':'Activar' ?></button>
    </form>
    <form action="admin_usuario_delete.php" method="post" onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
      <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
      <button type="submit">Eliminar</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</tbody></table>
</body></html>
