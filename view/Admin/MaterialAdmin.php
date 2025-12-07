<?php
session_start();

$rol = $_SESSION['usuario']['rol'] ?? ($_SESSION['rol'] ?? null);
$rol = strtolower($rol);

if ($rol !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MaterialModel.php";

$model = new MaterialModel($pdo);

$cursoId = intval($_GET['curso'] ?? 0);
$materiales = $model->obtenerMaterialPorCurso($cursoId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Material - Administrador</title>

    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; font-family:Montserrat,sans-serif; }
        .card { padding:25px; border-radius:12px; background:white; }
        .btn-subir { background:#0097b2; color:white; }
        .btn-eliminar { background:#ff4c4c; color:white; }
        .btn-descargar { background:#28c76f; color:white; }
    </style>
</head>

<body>

<div class="container mt-4">

    <a href="/Aula-Virtual-Santa-Teresita/view/Admin/SeleccionarCursoMaterial.php"
       class="btn btn-secondary mb-3">Volver</a>

    <h2 class="mb-4 fw-bold">Administración de Material</h2>

    <!-- SUBIR MATERIAL -->
    <div class="card mb-4">
        <form action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php"
              method="POST" enctype="multipart/form-data">

            <input type="hidden" name="accion" value="subir">
            <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">

            <label>Título</label>
            <input type="text" name="Titulo" class="form-control mb-3" required>

            <label>Descripción</label>
            <input type="text" name="Descripcion" class="form-control mb-3">

            <label>Archivo</label>
            <input type="file" name="archivo" class="form-control mb-3" required>

            <button class="btn btn-subir">Subir archivo</button>
        </form>
    </div>

    <!-- LISTA DE MATERIALES -->
    <div class="card">
        <h4 class="mb-3 fw-bold">Archivos del Curso</h4>

        <table class="table">
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
                               Descargar
                            </a>
                        </td>

                        <td>
                            <form action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php"
                                  method="POST">

                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="idMaterial" value="<?= $m['Id_Material'] ?>">
                                <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">

                                <button class="btn btn-eliminar">Eliminar</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>

                <?php if (empty($materiales)): ?>
                    <tr><td colspan="4" class="text-center">No hay material en este curso.</td></tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>
