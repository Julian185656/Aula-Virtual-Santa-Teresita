<?php
session_start();


require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

$model = new CursoModel($pdo);
$rol = $_SESSION['rol'] ?? '';
$idUsuario = $_SESSION['id_usuario'] ?? 0;
$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';


if (!$rol) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}


switch ($rol) {
    case 'Administrador':
        $cursos = $model->obtenerTodos();
        break;
    case 'Docente':
        $cursos = $model->obtenerCursosDocente($idUsuario);
        break;
    case 'Estudiante':
        $cursos = $model->obtenerCursosEstudiante($idUsuario);
        break;
    default:
        header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
        exit();
}


$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard de Cursos | Santa Teresita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color: #f8f9fa;">


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

        
        <?php if ($rol === 'Docente'): ?>
        <li class="nav-item">
          <a class="nav-link text-warning fw-semibold text-uppercase"
             href="/Aula-Virtual-Santa-Teresita/view/Cursos/listaCursos.php">
            🔍 Ver todos los cursos
          </a>
        </li>
        <?php endif; ?>

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
  <h3 class="fw-bold text-center mb-4 text-primary">🎓 Panel de Cursos</h3>

  
  <?php if ($rol === 'Administrador'): ?>
  <div class="d-flex justify-content-center gap-3 mb-4">
    <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/nuevoCurso.php" class="btn btn-primary fw-semibold">
      ➕ Crear nuevo curso
    </a>
    <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/asignarDocente.php" class="btn btn-warning fw-semibold">
      👨‍🏫 Asignar docentes a cursos
    </a>
  </div>
  <?php endif; ?>


  <?php if ($msg): ?>
      <div class="alert alert-success text-center"><?= htmlspecialchars(str_replace('_',' ',ucfirst($msg))) ?> correctamente.</div>
  <?php elseif ($error): ?>
      <div class="alert alert-danger text-center">⚠️ <?= htmlspecialchars($error) ?>.</div>
  <?php endif; ?>

  <?php if (empty($cursos)): ?>
      <div class="alert alert-warning text-center">No hay cursos disponibles para este rol.</div>
  <?php else: ?>
  <div class="row">
    <?php foreach ($cursos as $curso): ?>
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
          <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($curso['Nombre']) ?></h5>
          <p class="text-muted"><?= htmlspecialchars($curso['Descripcion']) ?></p>


          <?php if ($rol === 'Administrador' || $rol === 'Docente'): ?>
            <?php if (!empty($curso['Docente'])): ?>
              <p class="mb-1">
                <i class="fa-solid fa-chalkboard-user text-secondary"></i>
                <strong>Docente asignado:</strong> <?= htmlspecialchars($curso['Docente']) ?>
              </p>
            <?php else: ?>
              <p class="text-danger mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> Sin docente asignado
              </p>
            <?php endif; ?>
          <?php endif; ?>

  
          <?php if ($rol === 'Docente'): ?>
            <a href="/Aula-Virtual-Santa-Teresita/view/Docente/tareasDocente.php?id_curso=<?= $curso['Id_Curso'] ?>"
               class="btn btn-outline-primary w-100 mb-2">
               📝 Administrar Tareas
            </a>
            <button class="btn btn-outline-success w-100" data-bs-toggle="modal"
                    data-bs-target="#modalMatricula" data-id="<?= $curso['Id_Curso'] ?>">
              👩‍🎓 Matricular Estudiante
            </button>
          <?php endif; ?>


          <?php if ($rol === 'Estudiante'): ?>
            <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/accesoCurso.php?id_curso=<?= $curso['Id_Curso'] ?>"
               class="btn btn-primary w-100 mb-2">🚀 Acceso al Curso</a>
            <a href="/Aula-Virtual-Santa-Teresita/view/Tareas/dashboardTareas.php?id_curso=<?= $curso['Id_Curso'] ?>"
               class="btn btn-outline-secondary w-100">📋 Ver Tareas</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>


<div class="modal fade" id="modalMatricula" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php" class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fa fa-user-plus"></i> Matricular Estudiante</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" name="accion" value="matricular_estudiante">
        <input type="hidden" name="id_curso" id="idCursoMatricula">
        <input type="number" name="id_estudiante" class="form-control" placeholder="ID del estudiante" required>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success w-100">Matricular</button>
      </div>
    </form>
  </div>
</div>


<footer class="mt-5 text-center text-light py-4" style="background-color:#1c223a;">
  <p class="mb-1">
    <i class="fa fa-copyright"></i> 2025 Aula Virtual Santa Teresita |
    <a href="https://templatemo.com" class="text-warning" target="_blank">Design by TemplateMo</a>
  </p>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('modalMatricula')?.addEventListener('show.bs.modal', e => {
  document.getElementById('idCursoMatricula').value = e.relatedTarget.getAttribute('data-id');
});
</script>
</body>
</html>
