<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/ForoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']??'') !== 'administrador') {
  header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado"); exit();
}

$mensaje = $error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['del_pub'])) {
        if (ForoModel::eliminarPublicacion((int)$_POST['idForo'])) $mensaje="Publicación eliminada.";
        else $error="No se pudo eliminar la publicación.";
    } elseif (isset($_POST['del_com'])) {
        if (ForoModel::eliminarComentario((int)$_POST['idComentario'])) $mensaje="Comentario eliminado.";
        else $error="No se pudo eliminar el comentario.";
    }
}

$filtro = $_GET['curso'] ?? '';
$rows = ForoModel::adminListar($filtro);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Moderación de Foro</title>
  <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="m-0">Moderación de foro (Admin)</h3>
      <a class="btn btn-outline-secondary" href="/Aula-Virtual-Santa-Teresita/view/Admin/Home.php">⬅️ Volver</a>
    </div>

    <form class="row g-2 mb-3">
      <div class="col-auto">
        <input name="curso" class="form-control" value="<?=htmlspecialchars($filtro)?>" placeholder="Filtrar por nombre de curso">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-primary">Buscar</button>
      </div>
    </form>

    <?php if($mensaje):?><div class="alert alert-success"><?=$mensaje?></div><?php endif;?>
    <?php if($error):?><div class="alert alert-danger"><?=$error?></div><?php endif;?>

    <?php foreach($rows as $r): ?>
      <div class="card mb-3">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <div class="badge bg-secondary"><?=$r['Curso']?> (ID <?=$r['Id_Curso']?>)</div>
              <div class="small text-muted">Autor: <?=htmlspecialchars($r['Autor'])?> • <?=$r['Fecha_Creacion']?> • Estado: <?=$r['Estado']?></div>
              <h5 class="mt-2 mb-1"><?=htmlspecialchars($r['Titulo'])?></h5>
              <p class="mb-2"><?=nl2br(htmlspecialchars($r['Resumen']))?><?=strlen($r['Resumen'])>=200?'…':''?></p>
            </div>
            <form method="post" class="ms-2">
              <input type="hidden" name="idForo" value="<?=$r['Id_Foro']?>">
              <button class="btn btn-sm btn-danger" name="del_pub" onclick="return confirm('¿Eliminar publicación?')">Eliminar</button>
            </form>
          </div>

          <?php $coms = ForoModel::listarComentarios((int)$r['Id_Foro']); ?>
          <?php if(!empty($coms)): ?>
            <div class="mt-2 border rounded p-2 bg-white">
              <div class="small fw-bold mb-1">Comentarios (<?=$r['Id_Foro']?>):</div>
              <?php foreach($coms as $c): ?>
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <div class="small text-muted"><?=htmlspecialchars($c['Autor'])?> • <?=$c['Fecha_Creacion']?></div>
                    <div><?=nl2br(htmlspecialchars($c['Texto']))?></div>
                  </div>
                  <form method="post" class="ms-2">
                    <input type="hidden" name="idComentario" value="<?=$c['Id_Comentario']?>">
                    <button class="btn btn-sm btn-outline-danger" name="del_com" onclick="return confirm('¿Eliminar comentario?')">Eliminar</button>
                  </form>
                </div>
                <hr class="my-1">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if(empty($rows)): ?>
      <div class="alert alert-info">No hay publicaciones para mostrar.</div>
    <?php endif; ?>
  </div>
  <script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
