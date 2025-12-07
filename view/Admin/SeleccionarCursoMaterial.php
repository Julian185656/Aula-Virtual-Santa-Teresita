<?php
session_start();

$rol = null;

// si existe el arreglo usuario → tomar ese rol
if (isset($_SESSION['usuario']['rol'])) {
    $rol = strtolower($_SESSION['usuario']['rol']);
}
// si existe rol suelto → tomarlo también
elseif (isset($_SESSION['rol'])) {
    $rol = strtolower($_SESSION['rol']);
}

if ($rol !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

$cursos = CursoModel::obtenerCursos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccione un Curso</title>

    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f4f6;
            font-family: 'Montserrat', sans-serif;
        }

        .curso-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: .3s;
            margin-bottom: 25px;
        }

        .curso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .btn-ir {
            background-color: #0097b2;
            color: #fff;
            border-radius: 8px;
            padding: 8px 15px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-ir:hover {
            background-color: #007f96;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center mb-4 fw-bold">Seleccione un Curso</h2>

    <div class="row">

        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12">

                    <div class="curso-card">

                        <h4 class="fw-bold">
                            <?= htmlspecialchars($curso['nombre']) ?>
                        </h4>

                        <p class="text-muted">
                            <?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?>
                        </p>

                        <a class="btn-ir"
                           href="/Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=<?= urlencode($curso['id']) ?>">
                            Administrar Material
                        </a>

                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>

            <div class="col-12 text-center">
                <p>No hay cursos registrados.</p>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
