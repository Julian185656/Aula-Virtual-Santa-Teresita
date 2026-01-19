<?php
require __DIR__ . '/../../controller/auth_admin.php';

$pdo = (new CN_BD())->conectar();

$search = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = (int)(($page - 1) * $perPage);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Administración</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- TU CSS ORIGINAL, SIN TOCAR -->
<style>



/* MODAL — MISMO ESTILO QUE ADMIN */
/* === MODAL IGUAL AL DE USUARIOS === */
.modal-content {
    background: rgba(29,30,40,0.95) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    border-radius: 20px !important;
    box-shadow: none !important;
}

.modal-header,
.modal-footer {
    border: none !important;
}

.modal-body {
    font-size: 1rem;
    opacity: 0.95;
}

.modal-title {
    font-weight: 600;
}

.modal-footer .btn {
    border-radius: 12px;
}

.pagination .page-link {
    border-radius: 10px;
    margin: 0 3px;
    background: rgba(255,255,255,.10);
}

.pagination .active .page-link {
    background: rgba(255,255,255,.30);
    font-weight: bold;
}

.pagination .disabled .page-link {
    opacity: .4;
    pointer-events: none;
}


    body {
        font-family: 'Poppins', sans-serif;
        font-weight: 300;
        font-size: 15px;
        line-height: 1.7;
        color: #c4c3ca;
        padding: 40px 15px;
        background-color: #2a2b38;
        background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    }

    .container {
        max-width: 1200px;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #fff;
    }

    /* ---------- BUSCADOR ---------- */
    .search-form {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
    }

    .search-form input {
        padding: 10px 15px;
        border-radius: 15px;
        background: rgba(255,255,255,.1);
        color: #fff;
        border: none;
        outline: none;
    }

    .search-form button,
    .search-form a {
        padding: 10px 20px;
        border-radius: 15px;
        background: rgba(255,255,255,.15);
        color: #fff !important;
        border: none;
        cursor: pointer;
        transition: all .25s ease;
        text-decoration: none;
    }

    .search-form button:hover,
    .search-form a:hover {
        background: rgba(255,255,255,.25);
        color: #fff !important;
        text-decoration: none;
    }

    /* ---------- TABLA ---------- */
    .table-container {
        background: rgba(255,255,255,.05);
        padding: 20px;
        border-radius: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        color: #fff !important;
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    /* ---------- BOTONES DE ACCIONES ---------- */
    .actions a,
    .actions button {
        background: rgba(255,255,255,.10);
        color: #fff !important;
        border-radius: 10px;
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        transition: all .25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .actions a:hover,
    .actions button:hover {
        background: rgba(255,255,255,.25);
        color: #fff !important;
        text-decoration: none;
    }

    /* ---------- ICONOS ---------- */
    .actions i,
    .search-form i {
        color: #fff;
        transition: color .25s ease;
    }

    .actions a:hover i,
    .actions button:hover i,
    .search-form button:hover i,
    .search-form a:hover i {
        color: #fff;
    }

    /* ---------- QUITA ESTILOS DEFAULT (BOOTSTRAP) ---------- */
    button:hover,
    a:hover {
        filter: none;
    }
</style>

</head>

<body>
<div class="container">

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php"
   class="btn btn-outline-light mb-3">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

<h1>Usuarios</h1>

<form method="get" class="search-form">
    <input type="text" name="q" placeholder="Buscar por nombre o email" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Buscar</button>
    <a href="admin_usuario_new.php">+ Nuevo usuario</a>
</form>

<div class="table-container">
<table class="table table-borderless">
<thead>
<tr>
    <th>Nombre</th>
    <th>Email</th>
    <th>Rol</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>
</thead>
<tbody>

<?php foreach ($usuarios as $u): ?>
<tr>
<td><?= htmlspecialchars($u['Nombre']) ?></td>
<td><?= htmlspecialchars($u['Email']) ?></td>
<td><?= htmlspecialchars($u['Rol']) ?></td>
<td><?= htmlspecialchars($u['Estado']) ?></td>

<td class="actions">

<!-- EDITAR (SIN CONFIRMACIÓN) -->
<a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>"
   class="btn btn-outline-light btn-sm">
   <i class="bi bi-pencil-fill"></i>
</a>


<!-- ACTIVAR / DESACTIVAR (MISMO BOTÓN) -->
<form action="admin_usuario_toggle.php" method="post" style="display:inline;">
<input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
<input type="hidden" name="Estado" value="<?= $u['Estado']==='Activo'?'Inactivo':'Activo' ?>">
<button type="submit"
    class="btn btn-outline-light btn-sm"
    onclick="return confirmarForm(event,this)"
    data-title="<?= $u['Estado']==='Activo'?'Desactivar':'Activar' ?> usuario"
    data-message="¿Confirmas esta acción?">
    <?= $u['Estado']==='Activo'
        ? '<i class="bi bi-x-circle-fill"></i>'
        : '<i class="bi bi-check-circle-fill"></i>' ?>
</button>
</form>

<!-- ELIMINAR (MISMO BOTÓN) -->
<form action="admin_usuario_delete.php" method="post" style="display:inline;">
<input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
<button type="submit"
    class="btn btn-outline-light btn-sm"
    onclick="return confirmarForm(event,this)"
    data-title="Eliminar usuario"
    data-message="⚠ ¿Seguro que deseas eliminar este usuario?">
    <i class="bi bi-trash-fill"></i>
</button>
</form>

</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>
</div>

<!-- MODAL (NO CAMBIA BOTONES) -->
<div class="modal fade" id="confirmModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content bg-dark text-white">
<div class="modal-header border-0">
<h5 id="confirmTitle"></h5>
<button class="close text-white" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body" id="confirmMessage"></div>
<div class="modal-footer border-0">
<button class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
<button class="btn btn-danger" id="confirmBtn">Confirmar</button>
</div>
</div>
</div>
</div>

<?php if ($totalPages > 1): ?>
<nav class="mt-4">
<ul class="pagination justify-content-center">

<?php
$qParam = $search !== '' ? '&q=' . urlencode($search) : '';
?>

<!-- Anterior -->
<li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
<a class="page-link bg-dark text-white border-0"
   href="?page=<?= $page - 1 . $qParam ?>">
   &laquo;
</a>
</li>

<!-- Números -->
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<li class="page-item <?= $i == $page ? 'active' : '' ?>">
<a class="page-link bg-dark text-white border-0"
   href="?page=<?= $i . $qParam ?>">
   <?= $i ?>
</a>
</li>
<?php endfor; ?>

<!-- Siguiente -->
<li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
<a class="page-link bg-dark text-white border-0"
   href="?page=<?= $page + 1 . $qParam ?>">
   &raquo;
</a>
</li>

</ul>
</nav>
<?php endif; ?>



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
let accion = null;

function confirmarLink(e, url, titulo, mensaje) {
    e.preventDefault();
    $('#confirmTitle').text(titulo);
    $('#confirmMessage').text(mensaje);
    accion = () => window.location.href = url;
    $('#confirmModal').modal('show');
    return false;
}

function confirmarForm(e, btn) {
    e.preventDefault();
    const form = btn.closest('form');
    $('#confirmTitle').text(btn.dataset.title);
    $('#confirmMessage').text(btn.dataset.message);
    accion = () => form.submit();
    $('#confirmModal').modal('show');
    return false;
}

$('#confirmBtn').on('click', function () {
    if (accion) accion();
});
</script>

</body>
</html>
