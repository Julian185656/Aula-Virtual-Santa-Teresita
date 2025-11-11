<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

/* --- Guardas y validaciones básicas --- */
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
  exit();
}

$estudianteId = (int) $_SESSION['id_usuario'];
$idCurso      = (int) ($_GET['idCurso'] ?? 0);
$nombreCurso  = trim($_GET['nombre'] ?? '');

/* Si no viene idCurso o no está matriculado => redirigir */
if ($idCurso <= 0) {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php");
  exit();
}

/* (opcional) Verificar pertenencia del estudiante al curso */
try {
  $cursosEst = CursoModel::obtenerCursosEstudiante($estudianteId);
  $idsPermitidos = array_column($cursosEst, 'Id_Curso');
  if (!in_array($idCurso, $idsPermitidos, true)) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php?e=CursoNoPermitido");
    exit();
  }
} catch (\Throwable $th) {
  // si el modelo no está listo, continuamos sin bloquear
}

/* --- Mensajes por PRG (Post/Redirect/Get) --- */
$flash_ok = $_GET['ok'] ?? '';
$flash_er = $_GET['er'] ?? '';

/* --- CSRF simple para evitar doble envío por refresh --- */
if (empty($_SESSION['csrf_for_est_forum'])) {
  $_SESSION['csrf_for_est_forum'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_for_est_forum'];

/* --- Crear publicación --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
  if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
    header("Location: ?idCurso={$idCurso}&er=Token%20inv%C3%A1lido");
    exit();
  }

  $titulo    = trim($_POST['titulo'] ?? '');
  $contenido = trim($_POST['contenido'] ?? '');

  if (mb_strlen($titulo) < 3 || mb_strlen($contenido) < 3) {
    header("Location: ?idCurso={$idCurso}&er=Completa%20t%C3%ADtulo%20y%20contenido%20(m%C3%ADnimo%203%20caracteres)");
    exit();
  }
  if (mb_strlen($titulo) > 120)   $titulo    = mb_substr($titulo, 0, 120);
  if (mb_strlen($contenido) > 4000) $contenido = mb_substr($contenido, 0, 4000);

  $ok = ForoModel::crearPublicacion($idCurso, $estudianteId, $titulo, $contenido);
  if ($ok) {
    header("Location: ?idCurso={$idCurso}&ok=Publicaci%C3%B3n%20creada");
  } else {
    header("Location: ?idCurso={$idCurso}&er=No%20se%20pudo%20crear%20la%20publicaci%C3%B3n");
  }
  exit();
}

/* --- Listado de publicaciones del curso --- */
$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Foro del Curso</title>
  <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background:#f7f7f7; padding:24px; font-family: 'Montserrat', sans-serif; }
    .card { border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,.06); }
    .post-meta { font-size:.9rem; color:#6c757d; }
  </style>
</head>
<body>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h3 class="m-0">
          🗣️ Foro del curso
          <?php if ($nombreCurso !== ''): ?>
            <small class="text-muted">— <?= htmlspecialchars($nombreCurso) ?></small>
          <?php endif; ?>
        </h3>
        <div class="post-meta">ID Curso: <?= (int)$idCurso ?></div>
      </div>
      <a class="btn btn-outline-secondary" href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php">
        <i class="fa-solid fa-arrow-left"></i> Volver a Mis Cursos
      </a>
    </div>

    <?php if ($flash_ok): ?>
      <div class="alert alert-success"><?= htmlspecialchars($flash_ok) ?></div>
    <?php endif; ?>
    <?php if ($flash_er): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($flash_er) ?></div>
    <?php endif; ?>

    <!-- Nueva publicación -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3"><i class="fa-regular fa-square-plus"></i> Crear nueva publicación</h5>
        <form method="post" autocomplete="off" novalidate>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
          <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" maxlength="120" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="contenido" class="form-control" rows="4" maxlength="4000" required></textarea>
          </div>
          <button class="btn btn-primary" name="crear">
            <i class="fa-solid fa-paper-plane"></i> Publicar
          </button>
        </form>
      </div>
    </div>

    <!-- Lista de publicaciones -->
    <?php foreach ($posts as $p): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title mb-1"><?= htmlspecialchars($p['Titulo']) ?></h5>
          <div class="post-meta">por <?= htmlspecialchars($p['Autor']) ?> • <?= htmlspecialchars($p['Fecha_Creacion']) ?></div>
          <p class="mt-3 mb-3"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

          <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>
          <?php if (!empty($reps)): ?>
            <div class="border rounded p-2 bg-light">
              <?php foreach ($reps as $r): ?>
                <div class="mb-2">
                  <div class="small text-muted">
                    <i class="fa-regular fa-comment-dots"></i>
                    <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?>
                  </div>
                  <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                </div>
                <hr class="my-1">
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-muted">Sin respuestas aún.</div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($posts)): ?>
      <div class="alert alert-info">No hay publicaciones. ¡Sé el primero en escribir!</div>
    <?php endif; ?>
  </div>

  <script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
