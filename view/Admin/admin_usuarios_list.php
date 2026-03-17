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

<!-- BOOTSTRAP CSS (necesario para el modal) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>



/* ================= MODAL ESTILO ADMIN ================= */

.modal-content{
    background: rgba(29,30,40,0.95) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    border-radius: 20px !important;
    box-shadow: none !important;
}

.modal-header,
.modal-footer{
    border: none !important;
}

.modal-body{
    font-size: 1rem;
    opacity: 0.95;
}

.modal-title{
    font-weight: 600;
}

.modal-footer .btn{
    border-radius: 12px;
}


td:last-child{
  text-align:center;
}

th, td{
  text-align:center;
}

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
}

.page-wrap{
  max-width:1200px;
  margin:0 auto;
}

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:14px;
}

.btn-volver {
  text-decoration: none; /* Quita subrayado */
  color: var(--text);    /* Color blanco definido */
  transition: .2s;
}

.btn-volver:hover,
.btn-volver:focus {
  color: var(--text);         /* Mantiene el color blanco */
  text-decoration: none;      /* Quita subrayado */
  outline: none;              /* Quita el contorno azul del navegador */
}
    
   .btn-volver{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 18px;
  background:linear-gradient(180deg,var(--glass1),var(--glass2));
  color:var(--text);
  border-radius:14px;
  border:1px solid var(--stroke);
  text-decoration:none;
  transition:.2s;
}

.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  backdrop-filter: blur(12px);
}

.search-wrapper{
  display:flex;
  justify-content:center;
  gap:12px;
  flex-wrap:wrap;
  margin-bottom:18px;
  padding:16px;
}

.search-wrapper input{
  flex:1 1 320px;
  padding:12px 18px;
  border-radius:14px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,0.08);
  color:#fff;
}

.search-wrapper button,
.search-wrapper a{
  padding:12px 18px;
  border-radius:14px;
  background:rgba(255,255,255,0.12);
  color:#fff;
  border:1px solid var(--stroke);
  text-decoration:none;
}

.table-box{
  padding:20px;
}

table{
  width:100%;
  border-collapse:collapse;
}

thead tr{
  background:rgba(255,255,255,0.10);
}

th,td{
  padding:14px 10px;
}

tbody tr:nth-child(even){
  background:rgba(255,255,255,0.05);
}

.actions{
  display:inline-flex;
  align-items:center;
  gap:8px;
}

.actions a,
.actions button{
  width:36px;
  height:36px;
  border-radius:12px;
  background:rgba(255,255,255,0.14);
  color:#fff;
  border:1px solid var(--stroke);
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
}

.actions form{
  display:inline-flex;
  margin:0;
}

</style>
</head>

<body>

<div class="page-wrap">

<div class="page-header">
<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
<i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>
</div>

<h1>Usuarios</h1>

<form class="search-wrapper glass-card" method="get">
<input type="text" name="q" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($search) ?>">
<button type="submit"><i class="bi bi-search"></i> Buscar</button>
<a href="admin_usuario_new.php"><i class="bi bi-person-plus-fill"></i> Nuevo usuario</a>
</form>

<div class="table-box glass-card">

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

<a href="admin_usuario_edit.php?id=<?= $u['Id_Usuario'] ?>">
<i class="bi bi-pencil-fill"></i>
</a>

<form method="post" action="admin_usuario_delete.php">
<input type="hidden" name="Id_Usuario" value="<?= $u['Id_Usuario'] ?>">

<button type="button" class="btnDelete">
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

</div>


<!-- MODAL CONFIRMAR ELIMINAR -->
<div class="modal fade" id="deleteModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content bg-dark text-white border-0">

<div class="modal-header border-0">
<h5 class="modal-title">Confirmar eliminación</h5>
<button class="close text-white" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
¿Deseas eliminar este usuario?
</div>

<div class="modal-footer border-0">
<button class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
<button class="btn btn-danger" id="confirmDelete">
Eliminar
</button>
</div>

</div>
</div>
</div>


<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>

let formEliminar = null;

document.querySelectorAll(".btnDelete").forEach(btn => {

btn.addEventListener("click", function(){

formEliminar = this.closest("form");

$('#deleteModal').modal('show');

});

});

document.getElementById("confirmDelete").addEventListener("click", function(){

if(formEliminar){
formEliminar.submit();
}

});

</script>

</body>
</html>