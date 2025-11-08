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

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios - Administración</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">


 <link rel="stylesheet" href="../Styles/List.css">

</head>
<body>

<div class="card-container">
    <h1>Usuarios</h1>

    <form method="get" class="form-inline">
        <input type="text" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Buscar</button>
        <a href="admin_usuario_new.php">+ Nuevo usuario</a>
    </form>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-custom"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-custom"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['Id_Usuario'] ?></td>
                    <td><?= htmlspecialchars($u['Nombre']) ?></td>
                    <td><?= htmlspecialchars($u['Email']) ?></td>
                    <td><?= htmlspecialchars($u['Rol']) ?></td>
                    <td><?= htmlspecialchars($u['Estado']) ?></td>
                    <td class="actions">
                        <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <form action="admin_usuario_toggle.php" method="post" style="display:inline;">
                            <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                            <input type="hidden" name="Estado" value="<?= $u['Estado']==='Activo'?'Inactivo':'Activo' ?>">
                            <button type="submit" class="btn btn-sm btn-warning"><?= $u['Estado']==='Activo'?'Desactivar':'Activar' ?></button>
                        </form>
                        <form action="admin_usuario_delete.php" method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
                            <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
