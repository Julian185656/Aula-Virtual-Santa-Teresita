<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/AgendaModel.php";

$rol = $_SESSION['rol'] ?? '';
$idDocente = $_SESSION['id_usuario'] ?? 0;

if ($rol !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new AgendaModel($pdo);
$actividades = $model->obtenerActividadesDocente($idDocente);
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agenda Semanal | Docente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color:#f9f9fb;">

<nav class="navbar navbar-dark shadow" style="background-color:#0b1a35;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php">
      <span style="color:#ff9d00;">SANTA</span> TERESITA
    </a>
    <a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" class="btn btn-danger">Cerrar sesión</a>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="fw-bold text-center text-primary mb-4">📅 Agenda Semanal</h2>

  <?php if ($msg): ?>
      <div class="alert alert-success text-center">
        <?= htmlspecialchars(str_replace('_',' ',ucfirst($msg))) ?> correctamente.
      </div>
  <?php endif; ?>

  <!-- Formulario Nueva Actividad -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="fw-bold">➕ Agregar Actividad</h5>
      <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/AgendaController.php">
        <input type="hidden" name="accion" value="crear">
        <div class="row g-3">
          <div class="col-md-4"><input type="text" name="titulo" class="form-control" placeholder="Título" required></div>
          <div class="col-md-3"><input type="date" name="fecha" class="form-control" required></div>
          <div class="col-md-2"><input type="time" name="hora" class="form-control" required></div>
          <div class="col-md-3"><input type="text" name="descripcion" class="form-control" placeholder="Descripción"></div>
        </div>
        <button class="btn btn-primary mt-3"><i class="fa fa-plus"></i> Crear</button>
      </form>
    </div>
  </div>

  <!-- Lista de actividades -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="fw-bold mb-3">🗂 Actividades Programadas</h5>
      <?php if (empty($actividades)): ?>
        <div class="alert alert-warning text-center">No hay actividades programadas.</div>
      <?php else: ?>
        <table class="table table-hover align-middle text-center">
          <thead class="table-primary">
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
            <?php foreach ($actividades as $a): ?>
            <tr>
              <td><?= htmlspecialchars($a['Titulo']) ?></td>
              <td><?= htmlspecialchars($a['Descripcion']) ?></td>
              <td><?= htmlspecialchars($a['Fecha']) ?></td>
              <td><?= htmlspecialchars($a['Hora']) ?></td>
              <td><?= htmlspecialchars($a['Estado']) ?></td>
              <td>
                <!-- Editar -->
                <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/AgendaController.php" class="d-inline">
                  <input type="hidden" name="accion" value="eliminar">
                  <input type="hidden" name="id" value="<?= $a['Id_Actividad'] ?>">
                  <button class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
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
