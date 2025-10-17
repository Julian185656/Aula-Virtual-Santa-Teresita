<?php
session_start();


require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";


$model = new CursoModel($pdo);


$idCurso = intval($_GET['id_curso'] ?? 0);
if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php");
    exit();
}


$curso = $model->obtenerCursoPorId($idCurso);


if (!$curso) {
    echo "<div class='alert alert-danger text-center m-5'>⚠️ Curso no encontrado.</div>";
    exit();
}


$curso['Docente'] = $curso['Docente'] ?: 'No asignado';
$curso['Horario'] = $curso['Horario'] ?: 'No asignado';
$curso['Aula'] = $curso['Aula'] ?: 'Por definir';
$curso['Cupo'] = $curso['Cupo'] ?: '—';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Curso | <?= htmlspecialchars($curso['Nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="background-color:#f4f6f8;">
<div class="container py-5">

    
    <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php" class="btn btn-outline-primary mb-4">
        <i class="fa-solid fa-arrow-left"></i> Volver al panel
    </a>

   
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center rounded-top-4 py-3">
            <h3 class="fw-bold mb-0"><?= htmlspecialchars($curso['Nombre']) ?></h3>
            <p class="mb-0"><?= htmlspecialchars($curso['Descripcion']) ?></p>
        </div>
        <div class="card-body p-4">

            <p><i class="fa-solid fa-chalkboard-user text-secondary me-2"></i>
                <strong>Docente:</strong> <?= htmlspecialchars($curso['Docente']) ?></p>

            <p><i class="fa-regular fa-clock text-secondary me-2"></i>
                <strong>Horario:</strong> <?= htmlspecialchars($curso['Horario']) ?></p>

            <p><i class="fa-solid fa-door-open text-secondary me-2"></i>
                <strong>Aula:</strong> <?= htmlspecialchars($curso['Aula']) ?></p>

            <p><i class="fa-solid fa-users text-secondary me-2"></i>
                <strong>Cupo:</strong> <?= htmlspecialchars($curso['Cupo']) ?></p>

            <hr>

            <a href="/Aula-Virtual-Santa-Teresita/view/Tareas/dashboardTareas.php?id_curso=<?= $curso['Id_Curso'] ?>"
               class="btn btn-success w-100 mt-3">
               <i class="fa-solid fa-clipboard-list"></i> Ver tareas del curso
            </a>
        </div>
    </div>
</div>

</body>
</html>
