<?php
require __DIR__ . '/../../controller/auth_admin.php';

$pdo = (new CN_BD())->conectar();

/* ===============================
   BÚSQUEDA Y PAGINACIÓN
================================ */
$search = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

if ($search !== '') {
    $like = "%$search%";

    $countStmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM aulavirtual.vw_usuarios_detalle
        WHERE Nombre LIKE ? OR Email LIKE ?
    ");
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
    $total = $pdo->query("SELECT COUNT(*) FROM aulavirtual.vw_usuarios_detalle")->fetchColumn();

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
<title>Usuarios</title>
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        color: #ffffff;
        padding: 40px 20px;
        background-color: #1d1e26;
        background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
        background-size: 600px;
    }

    .container {
        max-width: 1300px;
    }

    h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 25px;
        font-size: 34px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }

    /* Botón volver */
    .btn-volver {
        display: inline-block;
        margin-bottom: 25px;
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.4);
        background: rgba(255,255,255,0.1);
        color:#fff;
        text-decoration:none;
        font-weight: 600;
    }
    .btn-volver:hover {
        background: rgba(255,255,255,0.25);
    }

    /* Contenedor principal */
    .card-glass {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(12px);
        border-radius: 18px;
        border: 1px solid rgba(255,255,255,0.18);
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    }

    /* Buscador */
    .search-box {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .search-box input {
        padding: 12px 18px;
        border-radius: 12px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
        min-width: 250px;
    }

    .search-box button {
        padding: 12px 20px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.35);
        background: rgba(255,255,255,0.20);
        color: #fff;
        font-weight: 600;
    }

    .search-box button:hover {
        background: rgba(255,255,255,0.35);
    }

    /* Tabla */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        font-size: 14px;
    }

    thead th {
        background: rgba(255,255,255,0.12);
        color: #f5f5f5;
        font-weight: 600;
        padding: 12px;
        text-align: center;
        border-radius: 12px;
    }

    tbody tr {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        text-align: center;
        transition: 0.2s;
    }

    tbody tr:nth-child(even) {
        background: rgba(255,255,255,0.08);
    }

    tbody tr:hover {
        background: rgba(255,255,255,0.18);
    }

    td {
        padding: 12px;
        color: #eaeaea;
        font-weight: 400;
    }

    /* Acciones */
    .btn-action {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,0.20);
        border: 1px solid rgba(255,255,255,0.35);
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 3px;
        color: #fff !important;
    }

    .btn-action:hover {
        background: rgba(255,255,255,0.35);
    }

    .pagination .page-link {
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
    }

    .pagination .page-item.active .page-link {
        background: rgba(255,255,255,0.35);
    }
</style>
</head>

<body>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
    <i class="fa fa-arrow-left"></i> Home
</a>

<div class="container">
    <h2>Usuarios</h2>

    <div class="card-glass">

        <!-- Buscador -->
        <form method="get" class="search-box">
            <input type="text" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fa fa-search"></i> Buscar</button>
        </form>

        <!-- Tabla -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['Id_Usuario'] ?></td>
                        <td><?= htmlspecialchars($u['Nombre']) ?></td>
                        <td><?= htmlspecialchars($u['Email']) ?></td>
                        <td><?= htmlspecialchars($u['Rol']) ?></td>
                        <td>
                            <a class="btn-action" href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>">
                                <i class="fa fa-pen"></i>
                            </a>

                            <form action="admin_usuario_delete.php" method="post" style="display:inline;" 
                                  onsubmit="return confirm('¿Eliminar este usuario?');">
                                <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                <button class="btn-action" type="submit">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <!-- Paginación -->
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page-1 ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page+1 ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</div>

</body>
</html>
