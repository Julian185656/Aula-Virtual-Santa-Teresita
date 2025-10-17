<?php
session_start();


require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";


$model = new CursoModel($pdo);
$rol = $_SESSION['rol'] ?? '';
$idUsuario = $_SESSION['id_usuario'] ?? 0;
$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';


if ($rol !== 'Docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}


$cursos = $model->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Cursos | Santa Teresita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color:#f4f6f8;">


<nav class="navbar navbar-expand-lg navbar-dark shadow" style="background-color:#0b1a35;">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">
      <span style="color:#ff9d00;">SANTA</span> TERESITA
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menuPrincipal">
      <ul class="navbar-nav align-items-center gap-3">
        <li class="nav-item">
          <a class="nav-link text-white fw-semibold text-uppercase">
            👋 <?= htmlspecialchars($nombreUsuario) ?> (<?= htmlspecialchars($rol) ?>)
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-warning fw-semibold" href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php">
            📘 Mis Cursos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger fw-semibold" href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php">
            🔁 Cerrar Sesión
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="container my-5">
    <h3 class="fw-bold text-center mb-4 text-primary">
        📋 Lista completa de cursos disponibles
    </h3>

    <?php if (empty($cursos)): ?>
        <div class="alert alert-warning text-center">
            No hay cursos registrados en el sistema.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold">
                                <?= htmlspecialchars($curso['Nombre']) ?>
                            </h5>
                            <p class="text-muted"><?= htmlspecialchars($curso['Descripcion']) ?></p>
                            <p><i class="fa-solid fa-user text-secondary"></i>
                                <strong>Docente:</strong> <?= htmlspecialchars($curso['Docente'] ?? 'Sin asignar') ?></p>
                            <p><i class="fa-regular fa-clock text-secondary"></i>
                                <strong>Horario:</strong> <?= htmlspecialchars($curso['Horario'] ?? 'No definido') ?></p>
                            <p><i class="fa-solid fa-door-open text-secondary"></i>
                                <strong>Aula:</strong> <?= htmlspecialchars($curso['Aula'] ?? 'Por definir') ?></p>

                            
                            <?php if ($curso['Docente'] === $nombreUsuario): ?>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Tareas/dashboardTareas.php?id_curso=<?= $curso['Id_Curso'] ?>"
                                   class="btn btn-outline-success w-100 mt-2">
                                   ✏️ Gestionar tareas
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary w-100 mt-2" disabled>
                                   👀 Solo lectura
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<footer class="mt-5 text-center text-light py-4" style="background-color:#1c223a;">
    <div class="container">
        <p class="mb-1">
            <i class="fa fa-copyright"></i> 2025 Aula Virtual Santa Teresita |
            <a href="https://templatemo.com" class="text-warning" target="_blank">Design by TemplateMo</a>
        </p>
        <small class="text-secondary">Panel informativo de cursos disponibles</small>
    </div>
</footer>

</body>
</html>
