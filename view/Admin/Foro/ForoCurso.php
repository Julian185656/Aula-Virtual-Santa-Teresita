<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";

// ✅ Solo ADMIN
$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
if (!isset($_SESSION['id_usuario']) || !is_string($rol) || strcasecmp($rol, 'Administrador') !== 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// 📌 Curso a moderar
$idCurso = (int)($_GET['idCurso'] ?? 0);
if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php");
    exit();
}

// 🗑️ Acciones: eliminar publicación o comentario
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

// 📥 Cargar publicaciones reales del curso
$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Moderar foro — Curso #<?= htmlspecialchars($idCurso) ?></title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f7f9fc;font-family:Montserrat,system-ui,Segoe UI,Roboto,Helvetica,Arial}
        .page{max-width:1100px;margin:24px auto}
        .card{border:0;border-radius:14px;box-shadow:0 10px 20px rgba(0,0,0,.06)}
        .comment{border-left:3px solid #e5e7eb;padding-left:12px;margin-left:6px}
    </style>
</head>
<body>
<div class="page container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Moderar foro — Curso #<?= htmlspecialchars($idCurso) ?></h3>
        <a class="btn btn-secondary" href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php">Volver</a>
    </div>

    <?php if ($flashOk): ?><div class="alert alert-success"><?= htmlspecialchars($flashOk) ?></div><?php endif; ?>
    <?php if ($flashErr): ?><div class="alert alert-danger"><?= htmlspecialchars($flashErr) ?></div><?php endif; ?>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $p): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-1"><?= htmlspecialchars($p['Titulo']) ?></h5>
                        <span class="text-muted small">#<?= (int)$p['Id_Foro'] ?></span>
                    </div>
                    <div class="text-muted small mb-2">
                        Por <?= htmlspecialchars($p['Autor']) ?> — <?= htmlspecialchars($p['Fecha_Creacion']) ?>
                    </div>
                    <p class="mb-3"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

                    <form method="post" class="mb-3">
                        <input type="hidden" name="idForo" value="<?= (int)$p['Id_Foro'] ?>">
                        <button class="btn btn-sm btn-danger" name="eliminarPublicacion" onclick="return confirm('¿Eliminar esta publicación?');">
                            Eliminar publicación
                        </button>
                    </form>

                    <?php
                        $reps = ForoModel::listarComentarios((int)$p['Id_Foro']);
                        if (!empty($reps)):
                    ?>
                        <div class="bg-light p-3 rounded">
                            <div class="fw-semibold mb-2">Comentarios (<?= count($reps) ?>)</div>
                            <?php foreach ($reps as $r): ?>
                                <div class="comment mb-3">
                                    <div class="small text-muted">
                                        #<?= (int)$r['Id_Comentario'] ?> — <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?>
                                    </div>
                                    <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                                    <form method="post" class="mt-2">
                                        <input type="hidden" name="idComentario" value="<?= (int)$r['Id_Comentario'] ?>">
                                        <button class="btn btn-sm btn-outline-danger" name="eliminarComentario"
                                                onclick="return confirm('¿Eliminar este comentario?');">
                                            Eliminar comentario
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">Sin comentarios.</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">No hay publicaciones en este curso.</div>
    <?php endif; ?>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
