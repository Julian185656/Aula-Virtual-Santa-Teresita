<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/ForoModel.php";

if (!isset($_SESSION['id_usuario']) || (($_SESSION['usuario']['Rol'] ?? $_SESSION['rol'] ?? '') !== 'Administrador')) {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado"); exit();
}

$idCurso = (int)($_GET['idCurso'] ?? 0);
if ($idCurso <= 0) { header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php"); exit(); }

$mensaje = $error = '';

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['eliminar']) && isset($_POST['id_foro'])) {
    $idForo = (int)$_POST['id_foro'];
    if (ForoModel::eliminarPublicacion($idForo)) {
        $mensaje = "Publicación eliminada.";
    } else {
        $error = "No se pudo eliminar la publicación.";
    }
}

$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Moderar foro — Curso #<?= $idCurso ?></title>
  <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Moderar foro — Curso #<?= $idCurso ?></h3>
    <a class="btn btn-secondary" href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php">Volver</a>
  </div>

  <?php if($mensaje): ?><div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <?php if(empty($posts)): ?>
    <div class="alert alert-info">No hay publicaciones en este curso.</div>
  <?php endif; ?>

  <?php foreach ($posts as $p): ?>
    <div class="card mb-3 shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-1"><?= htmlspecialchars($p['Titulo']) ?></h5>
        <div class="text-muted small mb-2">Por <?= htmlspecialchars($p['Autor']) ?> · <?= htmlspecialchars($p['Fecha_Creacion']) ?></div>
        <p class="mb-3"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

        <?php $reps = ForoModel::listarComentariosPorPublicacion((int)$p['Id_Foro']); ?>
        <?php if(!empty($reps)): ?>
          <div class="bg-light border rounded p-2 mb-2">
            <?php foreach($reps as $r): ?>
              <div class="mb-2">
                <div class="small text-muted"><?= htmlspecialchars($r['Autor']) ?> · <?= htmlspecialchars($r['Fecha_Creacion']) ?></div>
                <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
              </div>
              <hr class="my-1">
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" onsubmit="return confirm('¿Eliminar esta publicación y sus comentarios?');">
          <input type="hidden" name="id_foro" value="<?= (int)$p['Id_Foro'] ?>">
          <button class="btn btn-sm btn-danger" name="eliminar">Eliminar</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
