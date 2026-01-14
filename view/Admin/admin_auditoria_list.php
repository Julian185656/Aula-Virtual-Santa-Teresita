<?php
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
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría del Sistema</title>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #ffffff;
            padding: 40px 20px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
        }

        .container { 
            max-width: 1300px; 
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        /* Botón volver */
        .btn-volver {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.1);
            color:#fff;
            text-decoration:none;
        }
        .btn-volver:hover {
            background: rgba(255,255,255,0.25);
            color:#fff;
        }

        /* Glass Card */
        .card-glass {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.20);
            padding: 20px;
            margin-bottom: 25px;
        }

        /* Buscador */
        .search-form {
            display:flex;
            justify-content:center;
            gap:10px;
            flex-wrap:wrap;
        }
        .search-form input {
            padding:10px 15px;
            border-radius:12px;
            background:rgba(255,255,255,0.1);
            border:1px solid rgba(255,255,255,0.35);
            color:#fff;
            min-width: 280px;
        }
        .search-form button {
            padding:10px 20px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,0.35);
            background:rgba(255,255,255,0.15);
            color:#fff;
            font-weight:600;
        }
        .search-form button:hover {
            background:rgba(255,255,255,0.3);
        }

        /* Tabla */
        table { width:100%; font-size:14px; }
        table th {
            background:rgba(255,255,255,0.15);
            font-weight:600;
            color:#fff !important;
        }
        table td {
            color:#fff !important;
            vertical-align: middle;
        }
        table tr:nth-child(even) {
            background:rgba(255,255,255,0.06);
        }
        table tr:hover {
            background:rgba(255,255,255,0.12);
        }

        /* Paginación */
        .pagination .page-link {
            background:rgba(255,255,255,0.1);
            border:none;
            color:#fff;
        }
        .pagination .page-item.active .page-link {
            background:rgba(255,255,255,0.35);
            color:#fff;
        }
    </style>
</head>

<body>

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa fa-arrow-left"></i> Volver
    </a>

    <div class="container">

        <h2>Auditoría del Sistema</h2>

        <!-- Buscador -->
        <div class="card-glass">
            <form class="search-form" method="get">
                <input type="text" name="q" placeholder="Buscar evento, correo o módulo" value="<?= htmlspecialchars($search) ?>">
                <button type="submit"><i class="fa fa-search"></i> Buscar</button>
            </form>
        </div>

        <!-- Tabla -->
        <div class="card-glass table-responsive">
            <table class="table table-borderless text-center">
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

        <!-- Paginación -->
        <nav>
            <ul class="pagination justify-content-center mt-4">

                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Anterior</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Siguiente</a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>

    </div>

</body>
</html>
