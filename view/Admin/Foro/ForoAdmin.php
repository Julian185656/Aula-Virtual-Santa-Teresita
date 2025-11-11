<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

/* ✅ Solo ADMIN (acepta 'Administrador' en mayúsc/minúsc) */
$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
if (!isset($_SESSION['id_usuario']) || !is_string($rol) || strcasecmp($rol, 'Administrador') !== 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

/* 📚 Traer TODOS los cursos — intenta varios nombres de método por compatibilidad */
$cursos = [];
if (class_exists('CursoModel')) {
    if (method_exists('CursoModel', 'obtenerCursosAdmin')) {
        $cursos = CursoModel::obtenerCursosAdmin();
    } elseif (method_exists('CursoModel', 'obtenerCursos')) {
        $cursos = CursoModel::obtenerCursos();
    } elseif (method_exists('CursoModel', 'listarCursos')) {
        $cursos = CursoModel::listarCursos();
    } elseif (method_exists('CursoModel', 'getAll')) {
        $cursos = CursoModel::getAll();
    }
}

/* Helper seguro para lower */
function _toLower($s) {
    return function_exists('mb_strtolower') ? mb_strtolower((string)$s, 'UTF-8') : strtolower((string)$s);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Moderación de Foro</title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body{background:#f7f9fc;font-family:Montserrat,system-ui,Segoe UI,Roboto,Helvetica,Arial}
        .page-title{font-weight:700;margin:20px 0}
        .card{border:0;border-radius:14px;box-shadow:0 10px 20px rgba(0,0,0,.07)}
        .badge-id{font-size:.75rem;background:#eef2ff;color:#3b82f6}
        .btn-foro{background:#0d6efd;color:#fff}
        .btn-foro:hover{background:#0b5ed7;color:#fff}
        .search{max-width:520px}
    </style>
</head>
<body class="container py-4">

    <div class="d-flex justify-content-between align-items-center">
        <h3 class="page-title"><i class="fa-solid fa-comments"></i> Moderación de Foro</h3>
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">
            <i class="fa-solid fa-house"></i> Volver al Home
        </a>
    </div>

    <div class="alert alert-info">
        Aquí el administrador puede ver <strong>todos los cursos</strong> y entrar al foro de cada uno para
        <strong>eliminar comentarios</strong> si es necesario.
    </div>

    <!-- 🔎 Buscador -->
    <div class="mb-3">
        <input id="filtro" type="text" class="form-control search" placeholder="Filtrar por nombre de curso...">
    </div>

    <div class="row" id="gridCursos">
        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $c):
                /* Compatibilidad con claves distintas */
                $id   = $c['Id_Curso']     ?? $c['id']          ?? $c['ID'] ?? null;
                $nom  = $c['Nombre']       ?? $c['nombre']      ?? $c['titulo'] ?? 'Sin nombre';
                $desc = $c['Descripcion']  ?? $c['descripcion'] ?? $c['detalle'] ?? 'Sin descripción';
            ?>
            <div class="col-lg-4 col-md-6 col-12 mb-4 item-curso" data-nombre="<?= htmlspecialchars(_toLower($nom)) ?>">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($nom) ?></h5>
                            <span class="badge badge-id rounded-pill px-3 py-2">ID: <?= htmlspecialchars($id) ?></span>
                        </div>
                        <p class="text-muted flex-grow-1"><?= htmlspecialchars($desc) ?></p>
                        <div class="d-grid gap-2">
                            <!-- 🔗 Moderar foro de este curso -->
                            <a class="btn btn-foro"
                               href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoCurso.php?idCurso=<?= urlencode((string)$id) ?>&modo=admin">
                                <i class="fa-solid fa-gavel"></i> Moderar foro de este curso
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning mb-0">No hay cursos registrados.</div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // 🔎 Filtro rápido por nombre
        const filtro = document.getElementById('filtro');
        const items  = document.querySelectorAll('.item-curso');
        filtro.addEventListener('input', e => {
            const q = (e.target.value || '').trim().toLowerCase();
            items.forEach(it => {
                it.style.display = it.dataset.nombre.includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
