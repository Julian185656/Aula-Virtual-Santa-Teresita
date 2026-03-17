<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
  exit();
}

$estudianteId = (int) $_SESSION['id_usuario'];
$idCurso      = (int) ($_GET['idCurso'] ?? 0);
$nombreCurso  = trim($_GET['nombre'] ?? '');

if ($idCurso <= 0) {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php");
  exit();
}

$flash_ok = $_GET['ok'] ?? '';
$flash_er = $_GET['er'] ?? '';

if (empty($_SESSION['csrf_for_est_forum'])) {
  $_SESSION['csrf_for_est_forum'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_for_est_forum'];

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

$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Foro del Curso</title>
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>




body{
    font-family:'Poppins',sans-serif;
    background:#2a2b38 url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg') repeat;
    color:#fff;
    padding:40px 15px;
}
h3{text-align:center;margin-bottom:30px;font-weight:700;}
.card{
    border-radius:20px;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    margin-bottom:20px;
    transition: 0.3s;
}
.card:hover{
    transform: translateY(-5px);
    box-shadow: 0 14px 40px rgba(0,0,0,0.4);
}
.card-body h5{
    font-weight:600;
    color:#fff;
}
.post-meta{
    font-size:0.85rem;
    color:rgba(255,255,255,0.6);
}
.comment-box{
    background: rgba(255,255,255,0.05);
    border-radius:12px;
    padding:10px 12px;
    margin-top:10px;
    font-size:0.9rem;
}
.comment-meta{
    font-size:0.8rem;
    color: rgba(255,255,255,0.5);
}
textarea, input[type=text]{
    background: rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.2);
    border-radius:8px;
    color:#fff;
}
textarea::placeholder, input::placeholder{
    color: rgba(255,255,255,0.5);
}
.btn-primary{
    background:#0d6efd;
    border:none;
    border-radius:12px;
    transition: 0.2s;
}
.btn-primary:hover{
    filter:brightness(1.1);
    transform: translateY(-2px);
}
.alert-dark-custom{
    background: rgba(255,255,255,0.08);
    color: #fff;
    border:1px solid rgba(255,255,255,0.2);
    border-radius:12px;
    padding:15px;
    text-align:center;
}
</style>
</head>
<body>

<a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" 
   class="btn btn-outline-light mb-3" style="border-radius:15px; padding: 8px 18px;">
   <i class="fa-solid fa-arrow-left"></i> Volver
</a>

<div class="container">
    <h3>Foro del curso</h3>

    <?php if ($flash_ok): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flash_ok) ?></div>
    <?php endif; ?>
    <?php if ($flash_er): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flash_er) ?></div>
    <?php endif; ?>

    <!-- Crear publicación -->
    <div class="card mb-4">
        <div class="card-body">
            <h5><i class="fa-regular fa-square-plus"></i> Crear nueva publicación</h5>
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
                <button class="btn btn-primary" name="crear"><i class="fa-solid fa-paper-plane"></i> Publicar</button>
            </form>
        </div>
    </div>

    <!-- Listar publicaciones -->
    <?php if(!empty($posts)): ?>
        <?php foreach($posts as $p): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($p['Titulo']) ?></h5>
                    <div class="post-meta">por <?= htmlspecialchars($p['Autor']) ?> • <?= htmlspecialchars($p['Fecha_Creacion']) ?></div>
                    <p class="mt-2"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

                    <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>
                    <?php if(!empty($reps)): ?>
                        <div class="comment-box">
                            <?php foreach($reps as $r): ?>
                                <div class="mb-2">
                                    <div class="comment-meta"><i class="fa-regular fa-comment-dots"></i> <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?></div>
                                    <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                                </div>
                                <hr style="border-color: rgba(255,255,255,0.1); margin:5px 0;">
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">Sin respuestas aún.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert-dark-custom mt-3">No hay publicaciones. ¡Sé el primero en escribir!</div>
    <?php endif; ?>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>