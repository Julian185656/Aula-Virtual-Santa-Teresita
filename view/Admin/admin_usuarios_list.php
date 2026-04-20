<?php
// /view/Admin/admin_usuario_list.php
require __DIR__ . '/../../controller/auth_admin.php';
$pdo = (new CN_BD())->conectar();

/* ===============================
   BÚSQUEDA Y PAGINACIÓN
=============================== */
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
        SELECT * FROM aulavirtual.vw_usuarios_detalle
        ORDER BY Id_Usuario DESC
        OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
    ");
    $stmt->execute();
}

$usuarios = $stmt->fetchAll();
$totalPages = (int)ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Usuarios - Administración</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        :root{
            --bg:#2a2b38;
            --text:#fff;
            --glass1:rgba(255,255,255,.10);
            --glass2:rgba(255,255,255,.06);
            --stroke:rgba(255,255,255,.20);
            --stroke2:rgba(255,255,255,.30);
            --shadow:0 14px 44px rgba(0,0,0,.42);
            --radius:18px;
        }

        body{
            font-family:'Poppins',sans-serif;
            font-size:15px;
            color:var(--text);
            padding:40px 25px;
            background:var(--bg);
            background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat:repeat;
            background-size:600px;
        }

        .page-wrap{ max-width:1200px; margin:0 auto; }

        h1{
            text-align:center;
            font-weight:700;
            font-size:32px;
            margin:10px 0 22px;
            text-shadow:0 2px 10px rgba(0,0,0,.35);
        }

        .page-header{
            display:flex;
            justify-content:flex-start;
            align-items:center;
            margin-bottom:14px;
        }

        /* Botón Volver */
        .btn-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(180deg, var(--glass1), var(--glass2));
            color: var(--text) !important;
            border-radius: 14px;
            font-size: 15px;
            border: 1px solid var(--stroke);
            text-decoration: none !important;
            transition: .18s;
            box-shadow: 0 10px 26px rgba(0,0,0,.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .btn-volver:hover {
            border-color: var(--stroke2);
            background: rgba(255,255,255,.14);
            transform: translateY(-1px);
        }

        .glass-card{
            background:linear-gradient(180deg, var(--glass1), var(--glass2));
            border:1px solid var(--stroke);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Buscador */
        .search-wrapper{
            display:flex;
            justify-content:center;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            margin: 0 0 18px;
            padding:16px;
        }
        .search-wrapper input{
            flex: 1 1 320px;
            min-width: 240px;
            max-width: 500px;
            padding:12px 18px;
            border-radius:14px;
            border:1px solid var(--stroke);
            outline:none;
            background:rgba(255,255,255,0.08);
            color:var(--text);
            font-size:15px;
        }
        .search-wrapper button, .search-wrapper a.btn-new{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:12px 18px;
            border-radius:14px;
            background:rgba(255,255,255,0.12);
            color:var(--text);
            border:1px solid var(--stroke);
            font-size:15px;
            transition:.18s;
            text-decoration:none;
            cursor:pointer;
        }
        .search-wrapper button:hover, .search-wrapper a.btn-new:hover{
            background:rgba(255,255,255,0.18);
            border-color:var(--stroke2);
        }

        /* Tabla */
        .table-box{ padding:20px; }
        .table-responsive{ overflow:auto; border-radius:var(--radius); }
        table{ width:100%; border-collapse:collapse; min-width:900px; font-size:14px; }
        thead tr{ background:rgba(255,255,255,0.10); }
        th, td{ padding:14px 10px; vertical-align:middle; color:var(--text); text-align:center; }
        tbody tr:nth-child(even){ background:rgba(255,255,255,0.05); }
        tbody tr:hover{ background:rgba(255,255,255,0.08); }

        /* Acciones */
        .actions{ display:inline-flex; align-items:center; gap:8px; }
        .actions a, .actions button{
            width:36px; height:36px; border-radius:12px; background:rgba(255,255,255,0.14);
            color:#fff; border:1px solid var(--stroke); display:flex; align-items:center; justify-content:center;
            cursor:pointer; text-decoration:none; transition:.18s;
        }
        .actions a:hover, .actions button:hover{ background:rgba(255,255,255,0.22); border-color:var(--stroke2); }

        /* ==================== PAGINACIÓN OVALADA (ESTILO AUDITORÍA) ==================== */
.pagination-wrap {
    display: flex !important;
    justify-content: center !important;
    margin-top: 25px !important;
}

.pagination {
    display: inline-flex !important;
    gap: 12px !important;
    padding: 12px 20px !important;
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 100px !important; /* Forma de cápsula exterior */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Los botones individuales (Números) */
.pagination .page-link {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 50px !important; /* ESTO CREA EL ÓVALO PERFECTO */
    padding: 8px 22px !important;   /* Más ancho para que sea cápsula */
    min-width: 55px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
}

/* Quitar el borde azul feo de Bootstrap al hacer click */
.pagination .page-link:focus {
    box-shadow: none !important;
}

/* Estado Hover (Pasar el mouse) */
.pagination .page-link:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    border-color: rgba(255, 255, 255, 0.4) !important;
    transform: translateY(-1px);
}

/* Estado Activo (Página actual) */
.pagination .page-item.active .page-link {
    background: rgba(255, 255, 255, 0.25) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    font-weight: bold !important;
}

/* Arreglo para las flechas */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 50px !important;
    padding: 8px 15px !important;
}

/* Eliminar estilos basura de Bootstrap que se cuelan */
.pagination .page-item {
    border: none !important;
    background: none !important;
}
        /* Modal */
        .modal-content{
            background: rgba(29,30,40,0.95) !important;
            border: 1px solid var(--stroke) !important;
            border-radius: 20px !important;
        }
    </style>
</head>

<body>
    <div class="page-wrap">

        <div class="page-header">
            <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
                <i class="fa-solid fa-circle-arrow-left"></i> Volver
            </a>
        </div>

        <h1>Gestión de Usuarios</h1>

        <form class="search-wrapper glass-card" method="get">
            <input type="text" name="q" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><i class="fa fa-search"></i> Buscar</button>
            <a href="admin_usuario_new.php" class="btn-new"><i class="fa-solid fa-user-plus"></i> Nuevo usuario</a>
        </form>

        <div class="table-box glass-card table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th style="width:160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['Nombre']) ?></td>
                                <td><?= htmlspecialchars($u['Email']) ?></td>
                                <td><?= htmlspecialchars($u['Rol']) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>" title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <form method="post" action="admin_usuario_delete.php" style="margin:0;">
                                            <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                                            <button type="button" class="btnDelete" title="Eliminar">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No se encontraron usuarios.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php
                $groupSize = 5;
                $currentGroup = intdiv(($page - 1), $groupSize);
                $startPage = $currentGroup * $groupSize + 1;
                $endPage = min($startPage + $groupSize - 1, $totalPages);
                $prevGroupPage = $startPage - 1;
                $nextGroupPage = $endPage + 1;
            ?>
            <div class="pagination-wrap">
                <nav aria-label="Paginación">
                    <ul class="pagination">
                        <?php if ($prevGroupPage >= 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $prevGroupPage ?>">‹</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($nextGroupPage <= $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $nextGroupPage ?>">›</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body border-0">¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.</div>
                <div class="modal-footer border-0">
                    <button class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        let formEliminar = null;
        $(document).on("click", ".btnDelete", function(){
            formEliminar = $(this).closest("form");
            $('#deleteModal').modal('show');
        });
        $("#confirmDelete").click(function(){
            if(formEliminar) formEliminar.submit();
        });
    </script>
</body>
</html>