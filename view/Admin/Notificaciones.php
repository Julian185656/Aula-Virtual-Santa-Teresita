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
<title>Gestión de Notificaciones | Santa Teresita</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color:#f4f6f8;">

<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color:#0b1a35;">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">
      <span style="color:#ff9d00;">SANTA</span> TERESITA
    </a>
    <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" class="btn btn-danger">Cerrar sesión</a>
  </div>
</nav>

<div class="container my-5">
  <h2 class="fw-bold text-center text-primary mb-4">📨 Envío y Programación de Notificaciones</h2>

  <?php if ($msg): ?>
    <div class="alert alert-success text-center"><?= htmlspecialchars(str_replace('_',' ',ucfirst($msg))) ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- Crear / programar notificación -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="fw-bold">➕ Crear nueva notificación</h5>
      <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php">
        <input type="hidden" name="accion" value="crear">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Asunto</label>
            <input type="text" name="asunto" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Destinatario</label>
            <input type="email" name="destinatario" class="form-control" placeholder="correo@santateresita.ac.cr" required>
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
            <textarea name="mensaje" rows="4" class="form-control" required></textarea>
          </div>
        </div>
        <button class="btn btn-primary mt-3 w-100"><i class="fa fa-paper-plane"></i> Guardar Notificación</button>
      </form>
    </div>
  </div>

  <!-- Historial -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="fw-bold mb-3">📋 Historial de Notificaciones</h5>
      <?php if (empty($notificaciones)): ?>
        <div class="alert alert-warning text-center">No hay notificaciones registradas.</div>
      <?php else: ?>
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
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
                  <!-- 🚀 Enviar ahora -->
                  <?php if ($n['Estado'] === 'Pendiente'): ?>
                    <form action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" method="POST" class="d-inline">
                      <input type="hidden" name="accion" value="enviar_inmediato">
                      <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                      <button class="btn btn-outline-primary btn-sm" title="Enviar ahora">
                        <i class="fa fa-paper-plane"></i>
                      </button>
                    </form>
                  <?php endif; ?>

                  <!-- 🗑️ Eliminar -->
                  <form action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" method="POST" class="d-inline">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                    <button class="btn btn-outline-danger btn-sm" title="Eliminar">
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

<footer class="mt-5 text-center text-light py-3" style="background-color:#1c223a;">
  <p class="mb-0">© 2025 Aula Virtual Santa Teresita | Design by TemplateMo</p>
</footer>

</body>
</html>
