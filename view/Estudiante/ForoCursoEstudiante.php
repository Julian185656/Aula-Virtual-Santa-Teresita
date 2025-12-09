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
  body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #fff;
    padding: 40px 15px;
    background-color: #1e1f2e;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

    h3 {
      font-weight: 700;
      color: #fff;
    }

    .card {
      border-radius: 15px;
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(6px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.2);
      margin-bottom: 20px;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.35);
    }

    .card-body h5 {
      color: #fff;
      font-weight: 600;
    }

    .post-meta {
      font-size: 0.85rem;
      color: #a0a0a0;
    }

    .btn-primary, .btn-outline-secondary {
      border-radius: 8px;
      transition: all 0.2s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
    }

    .comment-box {
      background: rgba(255,255,255,0.1);
      border-radius: 8px;
      padding: 10px 12px;
      margin-top: 10px;
      font-size: 0.9rem;
    }

    .comment-meta {
      font-size: 0.8rem;
      color: #ccc;
    }

    textarea, input {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 6px;
      color: #fff;
    }

    ::placeholder {
      color: #ccc;
    }
  </style>
</head>
<body>



  <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

<div class="container text-center mb-4">
  <h3>
      Foro del curso
    
  </h3>

 
</div>

      
    </div>

    <?php if ($flash_ok): ?>
      <div class="alert alert-success"><?= htmlspecialchars($flash_ok) ?></div>
    <?php endif; ?>
    <?php if ($flash_er): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($flash_er) ?></div>
    <?php endif; ?>

    <!-- Crear nueva publicación -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="mb-3"><i class="fa-regular fa-square-plus"></i> Crear nueva publicación</h5>
        <form method="post" autocomplete="off" novalidate>
          <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
          <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" maxlength="120" placeholder="Escribe un título..." required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="contenido" class="form-control" rows="4" maxlength="4000" placeholder="Escribe tu publicación..." required></textarea>
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
          <h5 class="card-title"><?= htmlspecialchars($p['Titulo']) ?></h5>
          <div class="post-meta">por <?= htmlspecialchars($p['Autor']) ?> • <?= htmlspecialchars($p['Fecha_Creacion']) ?></div>
          <p class="mt-3"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

          <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>
          <?php if (!empty($reps)): ?>
            <div class="comment-box">
              <?php foreach ($reps as $r): ?>
                <div class="mb-2">
                  <div class="comment-meta">
                    <i class="fa-regular fa-comment-dots"></i> <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?>
                  </div>
                  <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                </div>
                <hr class="my-1" style="border-color: rgba(255,255,255,0.1);">
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
