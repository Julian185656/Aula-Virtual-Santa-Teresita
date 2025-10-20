<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// Validar sesión y rol docente
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

// Obtener ID del docente
$docenteId = $_SESSION['id_usuario'];

// Obtener cursos del docente
$misCursos = CursoModel::obtenerCursosDocente($docenteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700" rel="stylesheet">
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f4f4; font-family: 'Montserrat', sans-serif; padding: 20px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .card { border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .card-body h5 { font-weight: 600; }
        .card-body p { margin-bottom: 10px; }

        /* Botones circulares */
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            margin: 0 5px;
            border-radius: 50%;
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .icon-btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        .btn-ver { background-color: #4CAF50; }
        .btn-tarea { background-color: #007BFF; }
        .btn-tareas-asignadas { background-color: #FFC107; color: #212529; }

        .btn-container { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Mis Cursos</h2>

<div class="container">
    <div class="row">
        <?php if (!empty($misCursos)): ?>
            <?php foreach ($misCursos as $curso): ?>
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></h5>
                            <p class="card-text"><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></p>
                            <div class="btn-container">
                                <a class="icon-btn btn-ver" href="/Aula-Virtual-Santa-Teresita/view/Docente/VerCurso.php?id=<?= htmlspecialchars($curso['id']) ?>" title="Ver Curso">
                                    <i class="fa-solid fa-book"></i>
                                </a>
                                <a class="icon-btn btn-tarea" href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= htmlspecialchars($curso['id']) ?>" title="Añadir Tarea">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                                <a class="icon-btn btn-tareas-asignadas" href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($curso['id']) ?>" title="Ver Tareas">
                                    <i class="fa-solid fa-list"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p><strong>No tienes cursos asignados.</strong></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <!-- Cambiado para llevar al Home -->
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/jquery/jquery.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
