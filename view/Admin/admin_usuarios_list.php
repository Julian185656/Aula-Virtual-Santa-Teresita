<?php
// /view/Admin/admin_usuario_list.php
require __DIR__ . '/../../controller/auth_admin.php';
$pdo = (new CN_BD())->conectar();

$search = trim($_GET['q'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

/* --- CONSULTA (NO CAMBIADA) --- */
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
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Usuarios - Administración</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
:root{
  --bg:#2a2b38;
  --text:#fff;
  --muted:rgba(255,255,255,.75);
  --glass1:rgba(255,255,255,.10);
  --glass2:rgba(255,255,255,.06);
  --stroke:rgba(255,255,255,.20);
  --stroke2:rgba(255,255,255,.30);
  --shadow:0 14px 44px rgba(0,0,0,.42);
  --radius:18px;
}

/* ==================== GENERAL ==================== */
body{
  font-family:'Poppins',sans-serif;
  background:var(--bg);
  background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
  background-size:600px;
  background-repeat:repeat;
  color:var(--text);
  padding:40px 25px;
}

h1{
  text-align:center;
  font-weight:600;
  font-size:32px;
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

/* ==================== LAYOUT WRAPPER (MISMO ANCHO) ==================== */
.page-wrap{
  max-width:1200px;
  margin:0 auto;
}

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}

/* ==================== BOTÓN VOLVER ==================== */
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
.btn-volver:hover{ border-color:var(--stroke2); background:rgba(255,255,255,.14); }

/* ==================== GLASS CARD ==================== */
.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* ==================== BUSCADOR ==================== */
.search-wrapper{
  display:flex;
  justify-content:center;
  align-items:center;
  gap:12px;
  flex-wrap:wrap;             /* FIX: no se montan */
  margin: 0 0 18px;
  padding:16px;
}

.search-wrapper input{
  flex: 1 1 320px;            /* FIX: fluido */
  min-width: 240px;
  max-width: 720px;
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

.search-wrapper button,
.search-wrapper a{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:12px 18px;
  border-radius:14px;
  background:rgba(255,255,255,0.12);
  color:var(--text);
  border:1px solid var(--stroke);
  text-decoration:none;
  font-size:15px;
  transition:.18s;
  white-space:nowrap;
}
.search-wrapper button:hover,
.search-wrapper a:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,0.18);
}

/* ==================== TABLA ==================== */
.table-box{
  padding:20px;
}

.table-responsive{
  overflow:auto;
  border-radius:var(--radius);
}

table{ width:100%; border-collapse:collapse; min-width:740px; }

thead tr{
  background:rgba(255,255,255,0.10);
}

th,td{
  padding:14px 10px;
  font-size:15px;
}

tbody tr:nth-child(even){
  background:rgba(255,255,255,0.05);
}

tbody tr:hover{
  background:rgba(255,255,255,0.08);
}

/* ==================== ACCIONES ==================== */
.actions{
  display:inline-flex;        /* FIX: alinea form + links */
  align-items:center;
  gap:8px;
}
.actions a,
.actions button{
  padding:8px 12px;
  border-radius:12px;
  background:rgba(255,255,255,0.14);
  color:var(--text) !important;
  border:1px solid var(--stroke);
  display:inline-flex;
  align-items:center;
  gap:6px;
  text-decoration:none;
  transition:.18s;
  cursor:pointer;
}
.actions a:hover,
.actions button:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,0.22);
}
.actions form{ display:inline-flex; margin:0; }

/* ==================== PAGINACIÓN (GLASS + ACTIVE FIX) ==================== */
.pagination-wrap{
  display:flex;
  justify-content:center;
  margin-top:18px;
}

.pagination{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 12px;
  border-radius:999px;
  border:1px solid var(--stroke);
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  box-shadow:0 10px 26px rgba(0,0,0,.22);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

.pagination a{
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
.pagination a:hover{ border-color:var(--stroke2); }
.pagination a.active{                     /* FIX: coincide con tu HTML */
  background:rgba(255,255,255,0.22);
  border-color:var(--stroke2);
}

/* Responsive: evita cortes feos */
@media (max-width:520px){
  body{ padding:28px 14px; }
  h1{ font-size:26px; }
}
</style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
<i class="bi bi-arrow-left-circle-fill"></i> Volver



      </a>
      <div></div>
    </div>

    <h1>Usuarios</h1>

    <form class="search-wrapper glass-card" method="get">
      <input type="text" name="q" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($search) ?>">
      <button type="submit"><i class="bi bi-search"></i> Buscar</button>
      <a href="admin_usuario_new.php"><i class="bi bi-person-plus-fill"></i> Nuevo usuario</a>
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
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['Nombre']) ?></td>
              <td><?= htmlspecialchars($u['Email']) ?></td>
              <td><?= htmlspecialchars($u['Rol']) ?></td>
              <td>
                <div class="actions">
                  <a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>" aria-label="Editar">
                    <i class="bi bi-pencil-fill"></i>
                  </a>

                  <form method="post" action="admin_usuario_delete.php">
                    <input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">
                    <button type="submit" onclick="return confirm('¿Eliminar usuario?')" aria-label="Eliminar">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="pagination-wrap">
        <div class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a
              href="?page=<?= $i ?>&q=<?= urlencode($search) ?>"
              class="<?= $i == $page ? 'active' : '' ?>"
              aria-label="Página <?= $i ?>"
            >
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</body>
</html>
