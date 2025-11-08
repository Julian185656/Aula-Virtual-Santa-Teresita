<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

$registrosPorPagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $registrosPorPagina;

$search = trim($_GET['q'] ?? '');
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM vw_usuarios_detalle WHERE Nombre LIKE ? OR Email LIKE ?");
    $like = "%$search%";
    $stmt->execute([$like, $like]);
    $totalRegistros = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM vw_usuarios_detalle WHERE Nombre LIKE ? OR Email LIKE ? ORDER BY Id_Usuario DESC LIMIT $inicio, $registrosPorPagina");
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT COUNT(*) FROM vw_usuarios_detalle");
    $totalRegistros = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT * FROM vw_usuarios_detalle ORDER BY Id_Usuario DESC LIMIT $inicio, $registrosPorPagina");
}
$usuarios = $stmt->fetchAll();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios - Administración</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">


  <link rel="stylesheet" href="../Styles/adminlist.css">

</head>
<body>
<div class="card-container">
    <h1>Usuarios</h1>

    <form method="get" class="form-inline justify-content-center mb-3">
        <input type="text" name="q" class="form-control" placeholder="" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary ml-2"><i class="uil uil-search"></i></button>
        <a href="admin_usuario_new.php" class="btn btn-success ml-2"><i class="uil uil-user-plus"></i> Nuevo</a>
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary ml-2"><i class="uil uil-arrow-left"></i> Volver</a>
    </form>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger text-center"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover">
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
                            <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>" title="Editar">
                                <i class="uil uil-edit text-primary"></i>
                            </a>
                            <form action="admin_usuario_toggle.php" method="post" style="display:inline;">
                                <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                <input type="hidden" name="Estado" value="<?= $u['Estado']==='Activo'?'Inactivo':'Activo' ?>">
                                <button type="submit" title="<?= $u['Estado']==='Activo'?'Desactivar':'Activar' ?>">
                                    <i class="uil <?= $u['Estado']==='Activo'?'uil-lock text-warning':'uil-unlock text-success' ?>"></i>
                                </button>
                            </form>
                            <form action="admin_usuario_delete.php" method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar definitivamente este usuario?');">
                                <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                <button type="submit" title="Eliminar">
                                    <i class="uil uil-trash-alt text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No se encontraron usuarios</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPaginas > 1): ?>
        <nav>
            <ul class="pagination mt-4">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>&q=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
