<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once __DIR__ . '/../../model/AgendaModel.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new AgendaModel($pdo);
$idDocente = $_SESSION['id_usuario'];

$msg = '';

// Crear actividad
if (isset($_POST['crearActividad'])) {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    if ($titulo && $fecha && $hora) {
        $model->crearActividad($idDocente, $titulo, $descripcion, $fecha, $hora);
        $msg = "Actividad creada correctamente.";
    }
}

// Eliminar actividad
if (isset($_POST['eliminarActividad'])) {
    $idAgenda = intval($_POST['idAgenda'] ?? 0);
    if ($idAgenda > 0) {
        $model->eliminarActividad($idAgenda);
        $msg = "Actividad eliminada correctamente.";
    }
}

// Obtener actividades
$actividades = $model->obtenerActividadesDocente($idDocente);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agenda Semanal</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
  --bg:#2a2b38;
  --glass1:rgba(255,255,255,0.10);
  --glass2:rgba(255,255,255,0.06);
  --stroke:rgba(255,255,255,0.18);
  --stroke2:rgba(255,255,255,0.28);
  --shadow:0 14px 44px rgba(0,0,0,.42);
  --radius:20px;
  --text:#fff;
}

body{
  font-family:'Poppins',sans-serif;
  font-size:15px;
  color:var(--text);
  padding:40px 20px;
  background-color:var(--bg);
  background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
  background-repeat:repeat;
  background-size:600px;
}

h2{
  text-align:center;
  font-weight:700;
  margin:10px 0 26px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

.btn-volver{
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-bottom:22px;
  padding:10px 18px;
  border-radius:14px;
  border:1px solid var(--stroke);
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  color:#fff;
  text-decoration:none;
  transition:.18s;
  box-shadow:0 10px 26px rgba(0,0,0,.22);
  backdrop-filter: blur(12px);
}
.btn-volver:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,.14);
  color:#fff;
}

.glass-box{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border-radius:var(--radius);
  border:1px solid var(--stroke);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  padding:22px;
  margin-bottom:26px;
}

/* Inputs */
.form-control{
  background:rgba(255,255,255,0.08) !important;
  border:1px solid rgba(255,255,255,0.25) !important;
  color:#fff !important;
  border-radius:14px !important;
  height:44px;
}
.form-control::placeholder{ color:rgba(255,255,255,.60); }

.btn-ghost{
  height:44px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,0.35);
  background:rgba(255,255,255,0.12);
  color:#fff;
  padding:0 16px;
  transition:.18s;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  font-weight:700;
}
.btn-ghost:hover{ background:rgba(255,255,255,0.28); }

/* Tabla: quitar blancos de Bootstrap */
.table-wrap{
  background:rgba(255,255,255,0.04);
  border:1px solid rgba(255,255,255,0.14);
  border-radius:18px;
  padding:14px;
  overflow:auto;
}

.table{
  margin:0 !important;
  color:#fff !important;
  --bs-table-bg: transparent !important;
  --bs-table-accent-bg: transparent !important;
  --bs-table-striped-bg: rgba(255,255,255,0.05) !important;
  --bs-table-hover-bg: rgba(255,255,255,0.10) !important;
}

.table thead th{
  background:rgba(255,255,255,0.12) !important;
  color:#fff !important;
  font-weight:800;
  white-space:nowrap;
}

.table th, .table td{
  background:transparent !important; /* <- ESTE ES EL FIX DEL BLOQUE BLANCO */
  color:#fff !important;
  vertical-align:middle;
  padding:12px 14px !important;
}

.table tbody tr:nth-child(even){
  background:rgba(255,255,255,0.03);
}
.table tbody tr:hover{
  background:rgba(255,255,255,0.10);
}

/* Badges */
.badge-pend{
  background:#fbbf24;
  color:#1f272b;
  font-weight:800;
  padding:7px 10px;
  border-radius:999px;
}
.badge-ok{
  background:#22c55e;
  color:#0b1a10;
  font-weight:900;
  padding:7px 10px;
  border-radius:999px;
}

/* Empty state */
.no-actividades{
  text-align:center;
  padding:18px 0;
  opacity:.85;
}

@media (max-width:520px){
  body{ padding:28px 14px; }
  .glass-box{ padding:18px; }
}
</style>
</head>

<body>
<div class="container" style="max-width: 1100px;">

  <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
    <i class="fa fa-arrow-left"></i> Volver
  </a>

  <h2><i class="fa fa-calendar-days"></i> Agenda Semanal</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <!-- CREAR ACTIVIDAD -->
  <div class="glass-box">
    <h5 class="mb-3 fw-bold">Agregar Actividad</h5>

    <form method="POST" class="row g-3 align-items-center">
      <div class="col-md-3">
        <input type="text" name="titulo" class="form-control" placeholder="Título" required>
      </div>
      <div class="col-md-3">
        <input type="date" name="fecha" class="form-control" required>
      </div>
      <div class="col-md-2">
        <input type="time" name="hora" class="form-control" required>
      </div>
      <div class="col-md-3">
        <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
      </div>
      <div class="col-md-1 d-grid">
        <button type="submit" name="crearActividad" class="btn-ghost" title="Agregar">
          <i class="fa fa-plus"></i>
        </button>
      </div>
    </form>
  </div>

  <!-- ACTIVIDADES -->
  <div class="glass-box">
    <h5 class="mb-3 fw-bold">Actividades Programadas</h5>

    <div class="table-wrap">
      <table class="table table-borderless text-center align-middle">
        <thead>
          <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($actividades)): ?>
          <?php foreach ($actividades as $a): ?>
            <tr>
              <td><?= htmlspecialchars($a['Titulo'] ?? '') ?></td>
              <td><?= htmlspecialchars($a['Descripcion'] ?? '') ?></td>
              <td><?= htmlspecialchars($a['Fecha'] ?? '') ?></td>
              <td><?= htmlspecialchars($a['Hora'] ?? '') ?></td>
              <td>
                <?php if (($a['Estado'] ?? '') === 'Pendiente'): ?>
                  <span class="badge-pend">Pendiente</span>
                <?php else: ?>
                  <span class="badge-ok">Completada</span>
                <?php endif; ?>
              </td>
              <td>
                <form method="POST" class="m-0">
                  <input type="hidden" name="idAgenda" value="<?= (int)($a['Id_Agenda'] ?? 0) ?>">
                  <button type="submit" name="eliminarActividad" class="btn-ghost" title="Eliminar">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">
              <div class="no-actividades">No hay actividades programadas.</div>
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
