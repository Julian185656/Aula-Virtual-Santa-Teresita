<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";

// Verificación de Rol (Administrador)
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
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #c4c3ca;
            padding: 40px 15px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-size: 600px;
        }

        .page-wrap { max-width: 1000px; margin: auto; }
        .title { text-align: center; font-weight: 700; color: #fff; margin-bottom: 30px; }

        /* Tarjetas Estilo Glass */
        .card-glass {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 25px;
            margin-bottom: 25px;
            text-align: left;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .meta { font-size: 0.85rem; color: #aaa; margin-bottom: 10px; }
        .post-content { color: #fff; font-size: 1.05rem; }

        /* Comentarios */
        .bg-comments {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 15px;
            margin-top: 20px;
        }
        .comment-item {
            border-left: 3px solid rgba(255,255,255,0.2);
            padding-left: 15px;
            margin-bottom: 15px;
        }

        /* Botones de acción */
        .btn-delete {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff8080;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 0.85rem;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-delete:hover { background: #dc3545; color: #fff; }

        .btn-back {
            display: inline-block;
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding: 10px 25px;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.2);
            text-decoration: none !important;
            margin-top: 20px;
        }
        .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

        /* Modal Minimalista */
        .modal-content {
            background: rgba(29, 30, 40, 0.98) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 20px !important;
            color: #fff;
        }
        .modal-header, .modal-footer { border: none !important; }
        .modal-title { font-weight: 700; width: 100%; text-align: center; }
        .close { color: #fff !important; text-shadow: none; opacity: 0.8; }
        .btn-round { border-radius: 12px; padding: 10px 25px; font-weight: 600; }
    </style>
</head>
<body>

<div class="page-wrap">
    <h1 class="title"><i class="bi bi-shield-lock"></i> Moderación de Foro</h1>
    <h5 class="text-center mb-4 text-white-50">Curso #<?= htmlspecialchars($idCurso) ?></h5>

    <?php if ($flashOk): ?> 
        <div class="alert alert-success border-0 text-center mb-4" style="border-radius: 15px; background: rgba(40, 167, 69, 0.2); color: #2ecc71;">
            <?= htmlspecialchars($flashOk) ?>
        </div> 
    <?php endif; ?>
    
    <?php if ($flashErr): ?> 
        <div class="alert alert-danger border-0 text-center mb-4" style="border-radius: 15px; background: rgba(220, 53, 69, 0.2); color: #ff7675;">
            <?= htmlspecialchars($flashErr) ?>
        </div> 
    <?php endif; ?>

    <div class="container-posts">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $p): ?>
                <div class="card-glass">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="m-0 font-weight-bold text-white"><?= htmlspecialchars($p['Titulo']) ?></h5>
                        <button class="btn-delete" onclick="confirmarEliminar(<?= (int)$p['Id_Foro'] ?>, 'publicacion')">
                            <i class="bi bi-trash3"></i> Eliminar
                        </button>
                    </div>
                    
                    <div class="meta">Publicado por: <strong><?= htmlspecialchars($p['Autor']) ?></strong></div>
                    <p class="post-content"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

                    <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>
                    <div class="bg-comments">
                        <div class="small font-weight-bold mb-3 text-uppercase opacity-50">
                            Comentarios (<?= count($reps) ?>)
                        </div>
                        
                        <?php if (!empty($reps)): ?>
                            <?php foreach ($reps as $r): ?>
                                <div class="comment-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="meta mb-1"><strong><?= htmlspecialchars($r['Autor']) ?></strong> • <?= htmlspecialchars($r['Fecha_Creacion']) ?></div>
                                            <div class="text-white-50"><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                                        </div>
                                        <button class="btn-delete px-2 py-1" onclick="confirmarEliminar(<?= (int)$r['Id_Comentario'] ?>, 'comentario')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-muted small italic">Sin comentarios actualmente.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card-glass text-center py-5">
                <p class="m-0 opacity-50">No hay publicaciones para moderar en este curso.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php" class="btn-back">
            <i class="bi bi-arrow-left-short"></i> Volver al foro general
        </a>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="m-0">¿Estás seguro de que deseas eliminar este elemento del foro?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <form id="formEliminarFinal" method="post">
                    <input type="hidden" name="idForo" id="inputPostId">
                    <input type="hidden" name="idComentario" id="inputCommentId">
                    
                    <button type="button" class="btn btn-outline-light btn-round px-4 mr-2" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnConfirmSubmit" class="btn btn-danger btn-round px-4">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
function confirmarEliminar(id, tipo) {
    const modal = $('#confirmDeleteModal');
    const inputPost = document.getElementById('inputPostId');
    const inputComment = document.getElementById('inputCommentId');
    const btnSubmit = document.getElementById('btnConfirmSubmit');

    // Reset de valores previos
    inputPost.value = "";
    inputComment.value = "";
    inputPost.name = "";
    inputComment.name = "";

    if (tipo === 'publicacion') {
        inputPost.value = id;
        inputPost.name = "idForo";
        btnSubmit.name = "eliminarPublicacion";
    } else {
        inputComment.value = id;
        inputComment.name = "idComentario";
        btnSubmit.name = "eliminarComentario";
    }

    modal.modal('show');
}
</script>

</body>
</html>