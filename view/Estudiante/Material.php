<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MaterialModel.php";

$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
$rol = strtolower($rol);

if ($rol !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$model = new MaterialModel($pdo);

$cursoId = intval($_GET['curso'] ?? 0);
$materiales = $model->obtenerMaterialPorCurso($cursoId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Material del Curso</title>

    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#1f272b; font-family:Montserrat,sans-serif; color:#fff; }
        .glass { background:rgba(255,255,255,0.08); padding:25px; border-radius:15px; }
        table { width:100%; }
        th,td { padding:12px; }
        thead { background:#273036; }
        .btn-descargar { background:#28c76f; color:#fff; }
    </style>
</head>

<body>

<div class="container mt-5">

    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php"
       class="btn btn-outline-light mb-3">
       Volver
    </a>

    <h2 class="text-center mb-4">Material del Curso</h2>

    <div class="glass">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Descargar</th>
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
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($materiales)): ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay materiales disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>
