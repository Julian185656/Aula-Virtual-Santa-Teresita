<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MaterialModel.php";

// Validar acceso (solo docente)
$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
$rol = strtolower($rol);

if ($rol !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$model = new MaterialModel($pdo);

$cursoId = intval($_GET['curso'] ?? 0);
$materiales = $model->obtenerMaterialPorCurso($cursoId);
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Material del Curso</title>

    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background: #1f272b; font-family: 'Montserrat', sans-serif; color: #fff; }
        .glass { background: rgba(255,255,255,0.07); padding: 25px; border-radius: 15px; }
        table { width: 100%; }
        thead { background: #273036; }
        td, th { padding: 12px; }
        .btn-subir { background: #ff9f43; color: #fff; }
        .btn-descargar { background: #28c76f; color: #fff; }
        .btn-eliminar { background: #ff4c4c; color: #fff; }
    </style>
</head>
<body>

<div class="container mt-5">

    <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn btn-outline-light mb-3">
        <i class="fa-solid fa-arrow-left"></i> Volver
    </a>

    <h2 class="text-center mb-4"><i class="fa-solid fa-folder-open"></i> Material del Curso</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info fw-bold text-center"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Subir material -->
    <div class="glass mb-4">
        <form action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="accion" value="subir">
            <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">

            <label>Título</label>
            <input class="form-control mb-3" name="Titulo" required>

            <label>Descripción</label>
            <input class="form-control mb-3" name="Descripcion">

            <label>Archivo</label>
            <input class="form-control mb-3" type="file" name="archivo" required>

            <button class="btn btn-subir"><i class="fa-solid fa-upload"></i> Subir archivo</button>

        </form>
    </div>

    <!-- Tabla de material -->
    <div class="glass">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Descargar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($materiales as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['Titulo']) ?></td>
                        <td><?= htmlspecialchars($m['Descripcion']) ?></td>

                        <td>
                            <a class="btn btn-descargar"
                               href="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php?accion=descargar&id=<?= $m['Id_Material'] ?>">
                               <i class="fa-solid fa-download"></i>
                            </a>
                        </td>

                        <td>
                            <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="idMaterial" value="<?= $m['Id_Material'] ?>">
                                <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">
                                <button class="btn btn-eliminar"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($materiales)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay materiales subidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>
</body>
</html>
