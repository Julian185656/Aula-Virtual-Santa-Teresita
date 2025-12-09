<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";


$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
if (!isset($_SESSION['id_usuario']) || !is_string($rol) || strcasecmp($rol, 'Administrador') !== 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}


$idCurso = (int)($_GET['idCurso'] ?? 0);
if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php");
    exit();
}


$flashOk = $flashErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['eliminarPublicacion'])) {
            $ok = ForoModel::eliminarPublicacion((int)$_POST['idForo']);
            $flashOk = $ok ? "Publicación eliminada correctamente." : "No se pudo eliminar la publicación.";
        } elseif (isset($_POST['eliminarComentario'])) {
            $ok = ForoModel::eliminarComentario((int)$_POST['idComentario']);
            $flashOk = $ok ? "Comentario eliminado correctamente." : "No se pudo eliminar el comentario.";
        }
    } catch (Throwable $e) {
        $flashErr = "Error: " . $e->getMessage();
    }
}


$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Moderar foro — Curso #<?= htmlspecialchars($idCurso) ?></title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>



body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
    text-align: center;
}


.page-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 30px;
}

.flash {
    max-width: 800px;
    margin: 10px auto;
    padding: 12px 20px;
    border-radius: 15px;
    text-align: center;
}

.flash-ok {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.flash-err {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}


.container-posts {
    display: flex;
    flex-direction: column;
    gap: 25px;
    max-width: 1000px;
    margin: 0 auto 30px;
}


.card-glass {
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    padding: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-glass:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
}

.card-glass h5 {
    font-size: 1.2rem;
    margin-bottom: 5px;
}

.card-glass .meta {
    font-size: 0.85rem;
    color: #ccc;
    margin-bottom: 10px;
}

.comment {
    border-left: 3px solid rgba(255,255,255,0.2);
    padding-left: 12px;
    margin-left: 6px;
    margin-bottom: 10px;
}

.btn-action {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 6px 12px;
    border-radius: 12px;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    transition: 0.2s ease;
    border: none;
}

.btn-action:hover {
    background: rgba(255,255,255,0.35);
    cursor: pointer;
}

.btn-volver {
    display: inline-block;
    margin: 20px auto 0 auto;
    background: rgba(255,255,255,0.15);
    padding: 10px 20px;
    border-radius: 20px;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
    text-align: center;
}

.btn-volver:hover {
    background: rgba(255,255,255,0.35);
}

.bg-comments {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 10px;
    margin-top: 10px;
    text-align: left;
}

.comment {
    border-left: 3px solid rgba(255,255,255,0.2);
    padding-left: 12px;
    margin-left: 6px;
    margin-bottom: 10px;
    text-align: left;
}

</style>
</head>
<body>

<h1 class="page-title"><i class="bi bi-people-fill"></i> Moderar foro — Curso #<?= htmlspecialchars($idCurso) ?></h1>

<?php if ($flashOk): ?>
    <div class="flash flash-ok"><?= htmlspecialchars($flashOk) ?></div>
<?php endif; ?>
<?php if ($flashErr): ?>
    <div class="flash flash-err"><?= htmlspecialchars($flashErr) ?></div>
<?php endif; ?>

<div class="container-posts">
<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $p): ?>
        <div class="card-glass">
            <div class="d-flex justify-content-between">
                <h5><?= htmlspecialchars($p['Titulo']) ?></h5>

            </div>
            <div class="meta">Por <?= htmlspecialchars($p['Autor'])  ?></div>
            <p><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

            <form method="post" class="mb-3">
                <input type="hidden" name="idForo" value="<?= (int)$p['Id_Foro'] ?>">
                <button class="btn-action" name="eliminarPublicacion" onclick="return confirm('¿Eliminar esta publicación?');">
                    <i class="bi bi-trash"></i> Eliminar publicación
                </button>
            </form>

            <?php
            $reps = ForoModel::listarComentarios((int)$p['Id_Foro']);
            if (!empty($reps)):
            ?>
            <div class="bg-comments">
                <div class="fw-semibold mb-2">Comentarios (<?= count($reps) ?>)</div>
                <?php foreach ($reps as $r): ?>
                    <div class="comment">
                        <div class="meta small">#<?= (int)$r['Id_Comentario'] ?> — <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?></div>
                        <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                        <form method="post" class="mt-2">
                            <input type="hidden" name="idComentario" value="<?= (int)$r['Id_Comentario'] ?>">
                            <button class="btn-action" name="eliminarComentario" onclick="return confirm('¿Eliminar este comentario?');">
                                <i class="bi bi-trash"></i> Eliminar comentario
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <div class="meta">Sin comentarios.</div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="flash flash-err">No hay publicaciones en este curso.</div>
<?php endif; ?>
</div>

<a href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php" class="btn-volver">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
