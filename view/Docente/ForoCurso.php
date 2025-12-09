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
body{
    font-family: Montserrat, system-ui, Segoe UI, Roboto, Helvetica, Arial;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #ffffff;
    padding: 40px 15px;
    text-align: center;
    background-color: #1e1f2a; /* fondo general oscuro */
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

/* Contenedor general */
.page {
    max-width: 1100px;
    margin: 24px auto;
}

/* Tarjetas de publicaciones */
.card {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 10px 20px rgba(0,0,0,.6);
    background: rgba(30,30,40,0.9); /* fondo oscuro para la tarjeta */
    color: #ffffff;
    text-align: center; /* títulos y contenido centrado */
}

/* Comentarios */
.bg-light {
    background: rgba(0,0,0,0.4) !important; /* fondo oscuro glass */
    color: #ffffff !important;
    text-align: left; /* comentarios alineados a la izquierda */
    padding: 15px;
    border-radius: 10px;
}

.comment {
    border-left: 3px solid rgba(255,255,255,0.3);
    padding-left: 12px;
    margin-left: 0px;
    margin-bottom: 10px;
    text-align: left;
}

/* Botones */
.btn-primary {
    background: #3b82f6;
    border: none;
    color: #fff;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: rgba(255,255,255,0.15);
    border: none;
    color: #ffffffff;
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.35);
}

/* Inputs y textarea */
textarea,
input {
    background: rgba(255,255,255,0.1);
    color: #ffffffff;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px;
}

textarea::placeholder,
input::placeholder {
    color: #ffffffff;
    opacity: 0.7;
}

/* Títulos */
.header-title {
    font-weight: 700;
    color: #ffffff;
}
</style>
</head>
<body>

<div class="page container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0 header-title"> Foro — <?= htmlspecialchars($nombre !== '' ? $nombre : ('Curso #'.$idCurso)) ?></h3>
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

                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-1"><?= htmlspecialchars($p['Titulo']) ?></h5>
                        <span class="text-muted small">#<?= (int)$p['Id_Foro'] ?></span>
                    </div>

                    <div class="text-muted small mb-2">
                        Por <?= htmlspecialchars($p['Autor']) ?> — <?= htmlspecialchars($p['Fecha_Creacion']) ?>
                    </div>

                    <p class="mb-3"><?= nl2br(htmlspecialchars($p['Contenido'])) ?></p>

                    <?php $reps = ForoModel::listarComentarios((int)$p['Id_Foro']); ?>

                    <?php if (!empty($reps)): ?>
                        <div class="bg-light p-3 rounded mb-3">
                            <div class="fw-semibold mb-2">Comentarios (<?= count($reps) ?>)</div>
                            <?php foreach ($reps as $r): ?>
                                <div class="comment mb-3">
                                    <div class="small text-muted">
                                        <?= htmlspecialchars($r['Autor']) ?> • <?= htmlspecialchars($r['Fecha_Creacion']) ?>
                                    </div>
                                    <div><?= nl2br(htmlspecialchars($r['Texto'])) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted mb-3">Sin comentarios todavía.</div>
                    <?php endif; ?>

          
                    <form method="post" class="mt-2">
                        <input type="hidden" name="idForo" value="<?= (int)$p['Id_Foro'] ?>">
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Tu respuesta</label>
                            <textarea name="texto" class="form-control" rows="2" required></textarea>
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
