<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$estudianteId = $_SESSION['id_usuario'];
$cursos = CursoModel::obtenerCursosEstudiante($estudianteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cursos</title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }
        .btn-tareas { background-color: #0d6efd; color: white; padding: 6px 12px; border-radius: 6px; }
        .btn-material { background-color: #17a2b8; color: white; padding: 6px 12px; border-radius: 6px; }
    </style>
</head>

<body>

<h2 class="text-center mb-4">Mis Cursos</h2>

<div class="container">
    <div class="row">

        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 mb-3">

                    <div class="card">
                        <div class="card-body">

                            <h5><?= htmlspecialchars($curso['Nombre']) ?></h5>
                            <p><?= htmlspecialchars($curso['Descripcion']) ?></p>

                            <a class="btn-tareas"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/TareasEstudiante.php?idCurso=<?= $curso['Id_Curso'] ?>">
                                Ver Tareas
                            </a>

                            <a class="btn btn-outline-dark ms-2"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/ForoCurso.php?idCurso=<?= $curso['Id_Curso'] ?>&nombre=<?= urlencode($curso['Nombre']) ?>">
                                Foro
                            </a>

                            <a class="btn-material ms-2"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/Material.php?curso=<?= $curso['Id_Curso'] ?>">
                                Material
                            </a>

                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p class="text-center">No estás matriculado en ningún curso.</p>
        <?php endif; ?>

    </div>

    <div class="text-center mt-4">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">Volver al Home</a>
    </div>

</div>
</body>
</html>
