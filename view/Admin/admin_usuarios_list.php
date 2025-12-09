<?php
require __DIR__ . '/../../controller/auth_admin.php';
require __DIR__ . '/../../model/db.php';

// Conexión a SQL Server
$pdo = (new CN_BD())->conectar();

// Parámetros de búsqueda y paginación
$search = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Conteo total de registros
if ($search !== '') {
    $like = "%$search%";
    $countStmt = $pdo->prepare("SELECT COUNT(*) AS total FROM aulavirtual.vw_usuarios_detalle WHERE Nombre LIKE ? OR Email LIKE ?");
    $countStmt->execute([$like, $like]);
    $total = $countStmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT *
        FROM aulavirtual.vw_usuarios_detalle
        WHERE Nombre LIKE ? OR Email LIKE ?
        ORDER BY Id_Usuario DESC
        OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
    ");
    $stmt->execute([$like, $like]);
} else {
    $total = $pdo->query("SELECT COUNT(*) AS total FROM aulavirtual.vw_usuarios_detalle")->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT *
        FROM aulavirtual.vw_usuarios_detalle
        ORDER BY Id_Usuario DESC
        OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
    ");
    $stmt->execute();
}

$usuarios = $stmt->fetchAll();
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios - Administración</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.search-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 25px;
}
.search-form input[type="text"] {
    padding: 10px 15px;
    border-radius: 15px;
    border: none;
    min-width: 250px;
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.search-form input[type="text"]::placeholder {
    color: #ddd;
}
.search-form button, .search-form a {
    padding: 10px 20px;
    border-radius: 15px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    color: #fff;
    background: rgba(255,255,255,0.15);
    transition: 0.2s ease;
}
.search-form button:hover, .search-form a:hover {
    background: rgba(255,255,255,0.35);
}

/* Card de tabla */
.card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    margin-bottom: 30px;
}

.card-header {
    font-weight: 600;
    color: #ffffff;
    background: rgba(255,255,255,0.1);
    border-bottom: none;
    border-radius: 20px 20px 0 0;
}

.table-container {
    overflow-x: auto;
    border-radius: 20px;
}

table, table thead th, table tbody td {
    color: #fff;
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
table thead {
    background: rgba(255, 255, 255, 0.1);
    font-weight: bold;
}
table th, table td {
    padding: 12px 15px;
    vertical-align: middle;
}
table tr:nth-child(even) {
    background: rgba(255,255,255,0.02);
}
table tr:hover {
    background: rgba(255,255,255,0.1);
}

.actions a, .actions button {
    margin: 2px;
    font-size: 1rem;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 6px 10px;
    background: rgba(255,255,255,0.10);
    color: #fff !important;
}
.actions a:hover, .actions button:hover {
    background: rgba(255,255,255,0.25);
}

.pagination a {
    color: #fff;
    background: rgba(255,255,255,0.1);
    border: none;
    margin: 0 3px;
}
.pagination a:hover {
    background: rgba(255,255,255,0.3);
}

.badge-ok { background:#22c55e; color:#fff; }
.badge-no { background:#6b7280; color:#fff; }

.no-actividades {
    text-align: center;
    padding: 30px;
    color: #ffffffbb;
}
.no-actividades p {
    margin-top: 10px;
    font-weight: 500;
}
</style>
</head>
<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h1>Usuarios</h1>

    <form method="get" class="search-form">
        <input type="text" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Buscar</button>
        <a href="admin_usuario_new.php">+ Nuevo usuario</a>
    </form>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-people-fill"></i> Usuarios Registrados
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($usuarios)): ?>
                            <?php foreach($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['Id_Usuario'] ?></td>
                                <td><?= htmlspecialchars($u['Nombre']) ?></td>
                                <td><?= htmlspecialchars($u['Email']) ?></td>
                                <td><?= htmlspecialchars($u['Rol']) ?></td>
                                <td>
                                    <?php if($u['Estado'] === 'Activo'): ?>
                                        <span class="badge badge-ok">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-no">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>" class="btn btn-primary btn-sm" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="admin_usuario_toggle.php" method="post" style="display:inline;">
                                        <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                        <input type="hidden" name="Estado" value="<?= $u['Estado']==='Activo'?'Inactivo':'Activo' ?>">
                                        <button type="submit" class="btn btn-warning btn-sm" title="<?= $u['Estado']==='Activo'?'Desactivar':'Activar' ?>">
                                            <?php if($u['Estado']==='Activo'): ?>
                                                <i class="bi bi-x-circle-fill"></i>
                                            <?php else: ?>
                                                <i class="bi bi-check-circle-fill"></i>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <form action="admin_usuario_delete.php" method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?');">
                                        <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="no-actividades">
                                        <i class="bi bi-person-x-fill fa-2x"></i>
                                        <p>No hay usuarios registrados.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav>
        <ul class="pagination justify-content-center mt-4">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Anterior</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Siguiente</a></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
