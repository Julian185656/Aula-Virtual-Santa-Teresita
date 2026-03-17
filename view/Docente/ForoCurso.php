<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/ForoModel.php";

$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
if (!isset($_SESSION['id_usuario']) || !is_string($rol) || strcasecmp($rol, 'Docente') !== 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idCurso  = (int)($_GET['idCurso'] ?? 0);
$nombre   = trim((string)($_GET['nombre'] ?? ''));
if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php");
    exit();
}

$flashOk = $flashErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['responder'])) {
    $idForo = (int)($_POST['idForo'] ?? 0);
    $texto  = trim((string)($_POST['texto'] ?? ''));
    if ($idForo <= 0 || strlen($texto) < 2) {
        $flashErr = "Escribe una respuesta válida.";
    } else {
        try {
            $ok = ForoModel::responder($idForo, (int)$_SESSION['id_usuario'], $texto);
            $flashOk = $ok ? "Respuesta publicada." : "No se pudo publicar la respuesta.";
        } catch (Throwable $e) {
            $flashErr = "Error: " . $e->getMessage();
        }
    }
}

$posts = ForoModel::listarPublicacionesPorCurso($idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro — <?= htmlspecialchars($nombre !== '' ? $nombre : ('Curso #'.$idCurso)) ?></title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.7;
            color: #ffffff;
            background-color: #1e1f2a;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            background-position: center top;
            padding: 40px 15px;
        }

        .page {
            max-width: 1100px;
            margin: auto;
        }

        .header-title {
            font-weight: 700;
            color: #ffffff;
            font-size: 1.8rem;
        }

        .volver-btn {
            border-radius: 12px;
            padding: 8px 18px;
            text-decoration: none;
        }

        .alert {
            border-radius: 12px;
        }

        /* Tarjetas modernas */
        .card {
            background: rgba(30, 30, 40, 0.85);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .card small {
            color: #aaa;
        }

        .comment {
            border-left: 3px solid rgba(255,255,255,0.3);
            padding-left: 12px;
            margin-bottom: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 8px;
            padding: 10px 12px;
        }

        .comment .small {
            font-size: 0.75rem;
            color: #bbb;
        }

        .bg-light-custom {
            background: rgba(255,255,255,0.08);
            color: #000000;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        textarea, input {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 12px;
            padding: 10px;
        }

        /* Cambiado color del placeholder para que no sea blanco intenso */
        textarea::placeholder,
        input::placeholder {
            color: #cccccc;  /* gris suave */
            opacity: 0.8;
        }

        button.btn-primary {
            background: #3b82f6;
            border: none;
            border-radius: 12px;
            padding: 8px 18px;
        }

        button.btn-primary:hover {
            background: #2563eb;
        }

        button.btn-secondary {
            background: rgba(255,255,255,0.15);
            border: none;
            border-radius: 12px;
            padding: 8px 18px;
            color: #fff;
        }

        button.btn-secondary:hover {
            background: rgba(255,255,255,0.35);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="page container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="header-title">Foro — <?= htmlspecialchars($nombre !== '' ? $nombre : ('Curso #'.$idCurso)) ?></h3>
        <a class="btn btn-secondary volver-btn" href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php">⬅ Volver</a>
    </div>

    <?php if ($flashOk): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashOk) ?></div>
    <?php endif; ?>
    <?php if ($flashErr): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashErr) ?></div>
    <?php endif; ?>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $p): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0"><?= htmlspecialchars($p['Titulo']) ?></h5>
                        <small>#<?= (int)$p['Id_Foro'] ?></small>
                    </div>
                    <div class="text-muted small mb-3">
                        Por <?= htmlspecialchars($p['Autor']) ?> • <?= htmlspecialchars($p['Fecha_Creacion']) ?>
                    </div>
                    <p><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

                    <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>
                    <?php if (!empty($reps)): ?>
                        <div class="bg-light-custom">
                            <div class="fw-semibold mb-2">Comentarios (<?= count($reps) ?>)</div>
                            <?php foreach ($reps as $r): ?>
                                <div class="comment">
                                    <div class="small"><?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?></div>
                                    <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted mb-3">Sin comentarios todavía.</div>
                    <?php endif; ?>

                    <form method="post" class="mt-3">
                        <input type="hidden" name="idForo" value="<?= (int)$p['Id_Foro'] ?>">
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Tu respuesta</label>
                            <textarea name="texto" class="form-control" rows="2" placeholder="Escribe tu respuesta..." required></textarea>
                        </div>
                        <button class="btn btn-primary" name="responder">Responder</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Aún no hay publicaciones.</div>
    <?php endif; ?>

</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>