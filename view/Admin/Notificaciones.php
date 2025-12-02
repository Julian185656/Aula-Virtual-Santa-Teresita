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


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body {
    font-family: 'Montserrat', sans-serif;
    background: #1f272b; 
    color: #fff;
}


.navbar {
    background: #0b1a35;
    padding: 15px 0;
}
.navbar-brand {
    font-weight: 700;
    font-size: 1.4rem;
}
.navbar-brand span {
    color: #ffffffff;
}
.navbar .btn-danger {
    background-color: #e63946;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    font-weight: bold;
}


h2 {
    font-weight: 800;
    color: #ffffffff !important;
}


.card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(6px);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.08);
}
.card-body {
    padding: 30px;
}
.form-control:focus,
textarea:focus {
    outline: none; 
    box-shadow: none;   
    background-color: rgba(255,255,255,0.1); 
}

.form-control, textarea {
    border-radius: 12px !important;
    padding: 12px !important;
    background: rgba(255,255,255,0.1);
    border: 1px solid #3b4752;
    color: #fff;
}
.form-label {
    font-weight: 600;
    color: #ffffffff;
}
.form-control::placeholder {
    color: #bbbbbb;
}


.btn-primary {
    background: #ffffffff;
    color: #1f272b;
    border: none;
    font-weight: 700;
    padding: 12px;
    border-radius: 12px;
}
.btn-primary:hover {
    background: #ffffffff;
    color: #000;
}


.table {
    color: white;
}
.table thead {
    background: #0b1a35;
}
.table tbody tr {
    background: rgba(255,255,255,0.05);
}
.table-bordered, .table th, .table td {
    border-color: rgba(255,255,255,0.12) !important;
}


.btn-outline-primary {
    color: #ffffffff;
    border-color: #ffffffff;
}
.btn-outline-primary:hover {
    background: #ffffffff;
    color: #1f272b;
}

.btn-outline-danger:hover {
    background: #e63946;
    color: #fff;
}


footer {
    background: #0b1a35;
    padding: 15px;
    text-align: center;
    color: #fff;
    margin-top: 50px;
}

.card {
    color: #fff !important;
}
</style>
</head>

<body>


<div class="container my-5">

  <h2 class="text-center mb-4"> Gestión de Notificaciones</h2>

  <?php if ($msg): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars(str_replace('_',' ',ucfirst($msg))) ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="fw-bold mb-3"> Crear nueva notificación</h5>

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


  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="fw-bold mb-3"> Historial de Notificaciones</h5>

      <?php if (empty($notificaciones)): ?>
        <div class="alert alert-warning text-center">No hay notificaciones registradas.</div>
      <?php else: ?>

      <table class="table table-bordered table-hover text-center">
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
          <tr>
            <td><?= htmlspecialchars($n['Asunto']) ?></td>
            <td><?= htmlspecialchars($n['Destinatario']) ?></td>
            <td><?= htmlspecialchars($n['Fecha_Envio']) ?></td>
            <td><?= htmlspecialchars($n['Hora_Envio']) ?></td>
            <td>
              <?php if ($n['Estado'] === 'Enviada'): ?>
                <span class="badge bg-success">Enviada</span>
              <?php else: ?>
                <span class="badge bg-secondary">Pendiente</span>
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
      <?php endif; ?>
    </div>
  </div>
</div>


</body>
</html>
