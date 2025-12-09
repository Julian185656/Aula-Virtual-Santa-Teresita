<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";

$rol = $_SESSION['rol'] ?? '';
if ($rol !== 'Administrador') {
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: 'Montserrat', sans-serif;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    color: #fff;
    padding: 40px 15px;
    text-align: center;
}

h2 {
    font-weight: 800;
    margin-bottom: 30px;
}

.card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(6px);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.08);
    color: #fff;
    margin-bottom: 25px;
}

.card-body {
    padding: 30px;
}

.form-control, textarea {
    border-radius: 12px;
    padding: 12px;
    background: rgba(255,255,255,0.1);
    border: 1px solid #3b4752;
    color: #fff;
}

.form-control::placeholder {
    color: #bbbbbb;
}

.btn-primary {
    background: #fff;
    color: #1f272b;
    border: none;
    font-weight: 700;
    padding: 12px;
    border-radius: 12px;
}

.btn-primary:hover {
    background: #fff;
    color: #000;
}

.card-glass {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 25px;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    color: #fff;
}

.table-container {
    max-height: 400px;
    overflow-y: auto;
    border-radius: 15px;
    margin-top: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}

thead {
    background: rgba(255,255,255,0.1);
    font-weight: bold;
    position: sticky;
    top: 0;
    z-index: 1;
}

tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.03);
}

tbody tr:hover {
    background: rgba(255,255,255,0.12);
    transition: 0.2s;
}

.fila-pendiente {
    background: rgba(255,159,67,0.2) !important;
}

.badge {
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 12px;
}

.badge-success { background-color: #28c76f; }
.badge-secondary { background-color: #6c757d; }

.btn-outline-primary {
    color: #ff9f43;
    border: 1px solid #ff9f43;
    transition: 0.2s;
}

.btn-outline-primary:hover {
    background: #ff9f43;
    color: #fff;
}

.btn-outline-danger {
    color: #ff4c4c;
    border: 1px solid #ff4c4c;
    transition: 0.2s;
}

.btn-outline-danger:hover {
    background: #ff4c4c;
    color: #fff;
}
</style>
</head>
<body>




<div class="text-start mb-3">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" 
       class="btn btn-outline-light rounded-pill px-4">
        <i class="fa fa-arrow-left"></i> Volver
    </a>
</div>

<div class="container my-5">


  <h2>Gestión de Notificaciones</h2>

  <?php if ($msg): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars(str_replace('_',' ',ucfirst($msg))) ?></div>
  <?php elseif ($error): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- Crear nueva notificación -->
  <div class="card shadow-sm">
      <div class="card-body">
          <h5 class="fw-bold mb-3">Crear nueva notificación</h5>
          <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php">
              <input type="hidden" name="accion" value="crear">
              <div class="row g-3">
                  <div class="col-md-6">
                      <label class="form-label">Asunto</label>
                      <input type="text" name="asunto" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Destinatario</label>
                      <input type="email" name="destinatario" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Fecha de envío</label>
                      <input type="date" name="fecha_envio" class="form-control" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Hora de envío</label>
                      <input type="time" name="hora_envio" class="form-control" required>
                  </div>
                  <div class="col-12">
                      <label class="form-label">Mensaje</label>
                      <textarea name="mensaje" class="form-control" rows="4" required></textarea>
                  </div>
              </div>
              <button class="btn btn-primary mt-3 w-100">
                  <i class="fa fa-paper-plane"></i> Guardar Notificación
              </button>
          </form>
      </div>
  </div>

  <!-- Historial de notificaciones -->
  <div class="card-glass">
      <h5 class="fw-bold mb-3">Historial de Notificaciones</h5>
      <?php if (empty($notificaciones)): ?>
          <div class="alert alert-warning text-center">No hay notificaciones registradas.</div>
      <?php else: ?>
          <div class="table-container">
              <table class="table text-center">
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
                      <tr class="<?= $n['Estado'] === 'Pendiente' ? 'fila-pendiente' : '' ?>">
                          <td><?= htmlspecialchars($n['Asunto']) ?></td>
                          <td><?= htmlspecialchars($n['Destinatario']) ?></td>
                          <td><?= htmlspecialchars($n['Fecha_Envio']) ?></td>
                          <td><?= htmlspecialchars($n['Hora_Envio']) ?></td>
                          <td>
                              <?php if ($n['Estado'] === 'Enviada'): ?>
                                  <span class="badge badge-success">Enviada</span>
                              <?php else: ?>
                                  <span class="badge badge-secondary">Pendiente</span>
                              <?php endif; ?>
                          </td>
                          <td>
                              <?php if ($n['Estado'] === 'Pendiente'): ?>
                                  <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                                      <input type="hidden" name="accion" value="enviar_inmediato">
                                      <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                                      <button class="btn btn-outline-primary btn-sm">
                                          <i class="fa fa-paper-plane"></i>
                                      </button>
                                  </form>
                              <?php endif; ?>
                              <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                                  <input type="hidden" name="accion" value="eliminar">
                                  <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                                  <button class="btn btn-outline-danger btn-sm">
                                      <i class="fa fa-trash"></i>
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
</body>
</html>
