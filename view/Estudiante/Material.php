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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    body{
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            color: #c4c3ca;
            margin: 0;
            min-height: 100vh;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: no-repeat;
            background-size: 300%;
            background-position: center;
        }

    .glass {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 700;
        color: #fff;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    thead {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }

    th, td {
        padding: 12px 15px;
        vertical-align: middle;
    }

    tbody tr {
        transition: background 0.2s, transform 0.2s;
        cursor: default;
    }

    tbody tr:hover {
        background: rgba(255,255,255,0.08);
        transform: translateY(-2px);
    }

    .btn-descargar {
        background: #28c76f;
        color: #fff;
        font-weight: 500;
        border-radius: 8px;
        padding: 6px 12px;
        transition: background 0.2s, transform 0.2s;
    }

    .btn-descargar:hover {
        background: #20b85a;
        transform: translateY(-2px);
        text-decoration: none;
        color: #fff;
    }

    .btn-back {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border-radius: 8px;
        padding: 6px 14px;
        transition: background 0.2s, transform 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.1);
        transform: translateY(-2px);
        color: #fff;
        text-decoration: none;
    }

    @media (max-width:768px){
        th, td { font-size: 0.9rem; }
        h2 { font-size: 1.6rem; }
    }
</style>
</head>
<body>

<div class="container">


  <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>


    <div class="text-center mb-4">
      
        <h2>Material del Curso</h2>
    </div>

    <div class="glass">
        <table class="table table-borderless text-white mb-0">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th class="text-center">Descargar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($materiales)): ?>
                    <?php foreach ($materiales as $m): ?>
                        <tr>
                            <td><?= htmlspecialchars($m['Titulo']) ?></td>
                            <td><?= htmlspecialchars($m['Descripcion']) ?></td>
                            <td class="text-center">
                                <a class="btn-descargar"
                                   href="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php?accion=descargar&id=<?= $m['Id_Material'] ?>">
                                   <i class="fa-solid fa-download"></i> Descargar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No hay materiales disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
