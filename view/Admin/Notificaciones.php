<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";

/* ✅ Rol (más robusto) */
$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? '');
if (strtolower(trim($rol)) !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);
$notificaciones = $model->obtenerHistorial();

$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Notificaciones</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap 4 (igual que el resto del proyecto) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

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

  --warn:rgba(255, 193, 7, .18);
  --ok:#28c76f;
  --pending:#6c757d;
  --danger:#ff4c4c;
  --amber:#ff9f43;
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
  overflow-x:hidden;
}

.page-wrap{
  max-width:1100px;
  margin:0 auto;
}

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}

h1{
  text-align:center;
  font-weight:700;
  font-size:32px;
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

/* ==================== BOTÓN VOLVER (IGUAL AL SISTEMA) ==================== */
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
  line-height:1;
}
.btn-volver:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,.14);
  color:var(--text);
}
.btn-volver i{
  font-size:16px;
  line-height:1;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  transform: translateY(1px);
}

/* ==================== GLASS CARD ==================== */
.glass-card{
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  overflow:hidden;
}

.card-pad{ padding:18px; }

.section-title{
  font-weight:800;
  margin:0 0 12px;
}

/* ==================== FORM ==================== */
label{
  font-weight:700;
  font-size:13px;
  opacity:.9;
}

.form-control, textarea{
  border-radius:14px !important;
  padding:12px 14px !important;
  background:rgba(255,255,255,0.08) !important;
  border:1px solid var(--stroke) !important;
  color:var(--text) !important;
  outline:none !important;
}
.form-control::placeholder{ color:rgba(255,255,255,.60) !important; }
.form-control:focus, textarea:focus{
  border-color:var(--stroke2) !important;
  box-shadow:none !important;
}

/* Botón principal (blanco) */
.btn-save{
  width:100%;
  padding:12px 14px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.20);
  background:#fff;
  color:#1f272b;
  font-weight:900;
  cursor:pointer;
  transition:.18s;
}
.btn-save:hover{ background:#e9e9e9; }

/* ==================== TABLE ==================== */
.table-wrap{
  max-height:420px;
  overflow:auto;
  border-radius:16px;
}

.table{
  margin:0;
  color:#fff;
}

.table thead th{
  position:sticky;
  top:0;
  z-index:2;
  background:rgba(255,255,255,0.12);
  border:0 !important;
  font-weight:900;
  white-space:nowrap;
}

.table td{
  border-top:1px solid rgba(255,255,255,0.10) !important;
  vertical-align:middle !important;
}

.table tbody tr:nth-child(even){
  background:rgba(255,255,255,0.04);
}
.table tbody tr:hover{
  background:rgba(255,255,255,0.10);
}

.row-pendiente{
  background: var(--warn) !important;
}

/* Badges */
.badge-pill{
  padding:6px 10px;
  border-radius:999px;
  font-weight:900;
}
.badge-ok{ background: var(--ok); }
.badge-pending{ background: var(--pending); }

/* Botones acciones */
.btn-icon{
  width:38px;
  height:38px;
  border-radius:12px;
  border:1px solid rgba(255,255,255,0.20);
  background:rgba(255,255,255,0.10);
  color:#fff;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  transition:.18s;
}
.btn-icon:hover{
  border-color:rgba(255,255,255,0.35);
  background:rgba(255,255,255,0.18);
}

.btn-icon-send{
  border-color:rgba(255,159,67,.55);
}
.btn-icon-send:hover{
  background:rgba(255,159,67,.18);
}

.btn-icon-del{
  border-color:rgba(255,76,76,.55);
}
.btn-icon-del:hover{
  background:rgba(255,76,76,.18);
}

.alert{
  border-radius:14px;
  border:1px solid rgba(255,255,255,0.18);
  background:rgba(255,255,255,0.10);
  color:#fff;
}
.alert-success{ background:rgba(40,200,90,.18); }
.alert-danger{ background:rgba(255,76,76,.18); }
.alert-warning{ background:rgba(255,193,7,.18); }

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
        <i class="fa-solid fa-circle-arrow-left" aria-hidden="true"></i>
        Volver
      </a>
      <div></div>
    </div>

    <h1>Gestión de Notificaciones</h1>

    <?php if ($msg): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- CREAR NOTIFICACIÓN -->
    <div class="glass-card card-pad mb-4">
      <h5 class="section-title">Crear nueva notificación</h5>

      <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php">
        <input type="hidden" name="accion" value="crear">

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Asunto</label>
            <input type="text" name="asunto" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
            <label>Destinatario</label>
            <input type="email" name="destinatario" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
            <label>Fecha de envío</label>
            <input type="date" name="fecha_envio" class="form-control" required>
          </div>

          <div class="form-group col-md-6">
            <label>Hora de envío</label>
            <input type="time" name="hora_envio" class="form-control" required>
          </div>

          <div class="form-group col-12">
            <label>Mensaje</label>
            <textarea name="mensaje" rows="4" class="form-control" required></textarea>
          </div>
        </div>

        <button type="submit" class="btn-save">
          <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
          Guardar Notificación
        </button>
      </form>
    </div>

    <!-- HISTORIAL -->
    <div class="glass-card card-pad">
      <h5 class="section-title">Historial de Notificaciones</h5>

      <?php if (empty($notificaciones)): ?>
        <div class="alert alert-warning text-center mb-0">No hay notificaciones registradas.</div>
      <?php else: ?>

        <div class="table-wrap mt-3">
          <table class="table table-borderless text-center">
            <thead>
              <tr>
                <th>Asunto</th>
                <th>Destinatario</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($notificaciones as $n): ?>
                <tr class="<?= ($n['Estado'] ?? '') === 'Pendiente' ? 'row-pendiente' : '' ?>">
                  <td><?= htmlspecialchars($n['Asunto'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($n['Destinatario'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($n['Fecha_Envio'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($n['Hora_Envio'] ?? '—') ?></td>

                  <td>
                    <?php if (($n['Estado'] ?? '') === 'Enviada'): ?>
                      <span class="badge-pill badge-ok">Enviada</span>
                    <?php else: ?>
                      <span class="badge-pill badge-pending">Pendiente</span>
                    <?php endif; ?>
                  </td>

                  <td style="white-space:nowrap;">
                    <?php if (($n['Estado'] ?? '') === 'Pendiente'): ?>
                      <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                        <input type="hidden" name="accion" value="enviar_inmediato">
                        <input type="hidden" name="id" value="<?= (int)($n['Id_Notificacion'] ?? 0) ?>">
                        <button type="submit" class="btn-icon btn-icon-send" aria-label="Enviar ahora">
                          <i class="fa-solid fa-paper-plane"></i>
                        </button>
                      </form>
                    <?php endif; ?>

                    <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                      <input type="hidden" name="accion" value="eliminar">
                      <input type="hidden" name="id" value="<?= (int)($n['Id_Notificacion'] ?? 0) ?>">
                      <button type="submit" class="btn-icon btn-icon-del" aria-label="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>

          </table>
        </div>

      <?php endif; ?>
    </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
