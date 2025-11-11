<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// Validar sesión y rol estudiante
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// Obtener ID del estudiante
$estudianteId = (int)$_SESSION['id_usuario'];

// Obtener cursos del estudiante
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
        h2 { text-align: center; margin-bottom: 25px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-body h5 { font-weight: 600; }
        .btn-tareas {
            background-color: #0d6efd; color: #fff; border-radius: 6px; padding: 6px 12px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-tareas:hover { background-color: #0b5ed7; color: #fff; }
    </style>
</head>
<body>

<h2>Mis Cursos</h2>

<div class="container">
    <div class="row">
        <?php if (!empty($cursos)): ?>
            <?php foreach ($cursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($curso['Nombre'] ?? 'Sin nombre') ?></h5>
                            <p class="card-text"><?= htmlspecialchars($curso['Descripcion'] ?? 'Sin descripción') ?></p>

                            <!-- Botones de acciones -->
                            <a class="btn-tareas"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/TareasEstudiante.php?idCurso=<?= htmlspecialchars($curso['Id_Curso']) ?>">
                                <i class="fa-solid fa-list"></i> Ver Tareas
                            </a>

                            <!-- botón Foro (estudiante) -->
                            <a class="btn btn-outline-dark ms-2"
                               href="/Aula-Virtual-Santa-Teresita/view/Estudiante/ForoCurso.php?idCurso=<?= urlencode($curso['Id_Curso']) ?>&nombre=<?= urlencode($curso['Nombre'] ?? '') ?>">
                                <i class="fa-solid fa-comments"></i> Foro
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No estás matriculado en ningún curso.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- ÚNICO botón inferior -->
    <div class="text-center mt-4">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">
            <i class="fa-solid fa-house"></i> Volver al Home
        </a>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
