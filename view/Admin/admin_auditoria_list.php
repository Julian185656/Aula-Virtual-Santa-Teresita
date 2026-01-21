<?php
// /view/Admin/admin_auditoria_list.php
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
        FROM aulavirtual.auditoria_eventos
        WHERE Evento LIKE ? OR Correo LIKE ? OR Modulo LIKE ?
    ");
    $countStmt->execute([$like, $like, $like]);
    $total = $countStmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT
            FORMAT(
                Fecha AT TIME ZONE 'UTC' AT TIME ZONE 'Central America Standard Time',
                'yyyy-MM-dd HH:mm:ss'
            ) AS FechaLocal,
            Correo,
            Rol,
            Evento,
            Modulo,
            Descripcion,
            Resultado,
            Ip_Origen
        FROM aulavirtual.auditoria_eventos
        WHERE Evento LIKE ? OR Correo LIKE ? OR Modulo LIKE ?
        ORDER BY Fecha DESC
        OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
    ");
    $stmt->execute([$like, $like, $like]);
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM aulavirtual.auditoria_eventos")->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT
            FORMAT(
                Fecha AT TIME ZONE 'UTC' AT TIME ZONE 'Central America Standard Time',
                'yyyy-MM-dd HH:mm:ss'
            ) AS FechaLocal,
            Correo,
            Rol,
            Evento,
            Modulo,
            Descripcion,
            Resultado,
            Ip_Origen
        FROM aulavirtual.auditoria_eventos
        ORDER BY Fecha DESC
        OFFSET $offset ROWS FETCH NEXT $perPage ROWS ONLY
    ");
    $stmt->execute();
}

$eventos = $stmt->fetchAll();
$totalPages = (int)ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Auditoría del Sistema</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .page-wrap{
            max-width:1200px;
            margin:0 auto;
        }

        h1{
            text-align:center;
            font-weight:700;
            font-size:32px;
            margin:10px 0 22px;
            text-shadow:0 2px 10px rgba(0,0,0,.35);
        }

        /* Header: vuelve a la izquierda */
        .page-header{
            display:flex;
            justify-content:flex-start;
            align-items:center;
            margin-bottom:14px;
        }

        /* ==================== BOTÓN VOLVER (IGUAL AL BASE) ==================== */
        .btn-volver{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 18px;
            background:linear-gradient(180deg, var(--glass1), var(--glass2));
            color:var(--text);
            border-radius:14px;
            font-size:15px;
            border:1px solid var(--stroke);
            text-decoration:none;
            transition:.18s;
            box-shadow:0 10px 26px rgba(0,0,0,.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .btn-volver:hover{
            border-color:var(--stroke2);
            background:rgba(255,255,255,.14);
            color:var(--text);
        }

        /* Glass card base */
        .glass-card{
            background:linear-gradient(180deg, var(--glass1), var(--glass2));
            border:1px solid var(--stroke);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Buscador (patrón) */
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
            max-width: 820px;
            width:auto;
            padding:12px 18px;
            border-radius:14px;
            border:1px solid var(--stroke);
            outline:none;
            background:rgba(255,255,255,0.08);
            color:var(--text);
            font-size:15px;
        }
        .search-wrapper input::placeholder{ color:rgba(255,255,255,.65); }
        .search-wrapper input:focus{ border-color:var(--stroke2); }

        .search-wrapper button{
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
            white-space:nowrap;
            cursor:pointer;
        }
        .search-wrapper button:hover{
            border-color:var(--stroke2);
            background:rgba(255,255,255,0.18);
        }

        /* Tabla */
        .table-box{ padding:20px; }
        .table-responsive{
            overflow:auto;
            border-radius:var(--radius);
        }

        table{
            width:100%;
            border-collapse:collapse;
            min-width:980px;
            font-size:14px;
        }

        thead tr{ background:rgba(255,255,255,0.10); }
        th, td{
            padding:14px 10px;
            vertical-align:middle;
            color:var(--text);
            text-align:center;
        }
        tbody tr:nth-child(even){ background:rgba(255,255,255,0.05); }
        tbody tr:hover{ background:rgba(255,255,255,0.08); }

        /* ==================== PAGINACIÓN POR BLOQUES (5) ==================== */
        .pagination-wrap{
            display:flex;
            justify-content:center;
            margin-top:18px;
        }
        .pagination{
            list-style:none;
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 12px;
            margin:0;
            border-radius:999px;
            border:1px solid var(--stroke);
            background:linear-gradient(180deg, var(--glass1), var(--glass2));
            box-shadow:0 10px 26px rgba(0,0,0,.22);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .pagination .page-link{
            display:inline-block;
            border:1px solid var(--stroke);
            border-radius:999px;
            padding:8px 14px;
            color:var(--text);
            text-decoration:none;
            background:rgba(255,255,255,.06);
            transition:.18s;
            min-width:40px;
            text-align:center;
        }
        .pagination .page-link:hover{ border-color:var(--stroke2); }
        .pagination .page-item.active .page-link{
            background:rgba(255,255,255,0.22);
            border-color:var(--stroke2);
            color:var(--text);
        }

        @media (max-width:520px){
            body{ padding:28px 14px; }
            h1{ font-size:26px; }
            table{ min-width:860px; }
        }
    </style>
</head>

<body>
    <div class="page-wrap">

        <div class="page-header">
            <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
            <i class="fa-solid fa-circle-arrow-left"></i> 
            Volver
            </a>
        </div>

        <h1>Auditoría del Sistema</h1>

        <form class="search-wrapper glass-card" method="get">
            <input
                type="text"
                name="q"
                placeholder="Buscar evento, correo o módulo"
                value="<?= htmlspecialchars($search) ?>"
            >
            <button type="submit"><i class="fa fa-search"></i> Buscar</button>
        </form>

        <div class="table-box glass-card table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Evento</th>
                        <th>Módulo</th>
                        <th>Descripción</th>
                        <th>Resultado</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $e): ?>
                        <tr>
                            <td><?= $e['FechaLocal'] ?></td>
                            <td><?= $e['Correo'] ?: '—' ?></td>
                            <td><?= $e['Rol'] ?></td>
                            <td><?= $e['Evento'] ?></td>
                            <td><?= $e['Modulo'] ?></td>
                            <td><?= $e['Descripcion'] ?></td>
                            <td><?= $e['Resultado'] ?></td>
                            <td><?= $e['Ip_Origen'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <?php
                $groupSize = 5;
                $currentGroup = intdiv(($page - 1), $groupSize);
                $startPage = $currentGroup * $groupSize + 1;
                $endPage = min($startPage + $groupSize - 1, $totalPages);

                $prevGroupPage = $startPage - 1; // última del bloque anterior
                $nextGroupPage = $endPage + 1;   // primera del bloque siguiente
            ?>

            <div class="pagination-wrap">
                <nav aria-label="Paginación">
                    <ul class="pagination">

                        <?php if ($prevGroupPage >= 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $prevGroupPage ?>" aria-label="Bloque anterior">‹</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($nextGroupPage <= $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $nextGroupPage ?>" aria-label="Bloque siguiente">›</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
