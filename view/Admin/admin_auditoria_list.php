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
    <title>Auditoría - Administración</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.7;
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
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
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
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .search-form input::placeholder {
            color: #ddd;
        }

        .search-form button,
        .search-form a {
            padding: 10px 20px;
            border-radius: 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            transition: 0.2s ease;
        }

        .search-form button:hover,
        .search-form a:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .table-container {
            overflow-x: auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        table,
        th,
        td {
            color: #fff !important;
        }

        table tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .pagination a {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            margin: 0 3px;
        }

        .pagination a:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>

    <div class="container">

        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3" style="border-radius:15px;">
            <i class="bi bi-arrow-left-circle-fill"></i> Volver
        </a>

        <h1>Auditoría del Sistema</h1>

        <form method="get" class="search-form">
            <input type="text" name="q" placeholder="Buscar evento, correo o módulo"
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Buscar</button>
        </form>

        <div class="table-container">
            <table class="table table-borderless">
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
                            <td><?= htmlspecialchars($e['Correo'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($e['Rol']) ?></td>
                            <td><?= htmlspecialchars($e['Evento']) ?></td>
                            <td><?= htmlspecialchars($e['Modulo']) ?></td>
                            <td><?= htmlspecialchars($e['Descripcion']) ?></td>
                            <td><?= htmlspecialchars($e['Resultado']) ?></td>
                            <td><?= htmlspecialchars($e['Ip_Origen']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

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