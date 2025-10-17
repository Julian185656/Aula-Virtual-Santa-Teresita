<?php
session_start();

// ✅ Rutas seguras y absolutas
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// ✅ Inicializar modelo
$model = new CursoModel($pdo);

// ✅ Verificar si se pasó el ID del curso
$idCurso = intval($_GET['id_curso'] ?? 0);
if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php");
    exit();
}

// ✅ Obtener datos del curso
$curso = $model->obtenerCursoPorId($idCurso);
if (!$curso) {
    echo "<div class='alert alert-danger text-center m-5'>⚠️ Curso no encontrado.</div>";
    exit();
}

// ✅ Obtener tareas del curso
$stmt = $pdo->prepare("SELECT Id_Tarea, Titulo, Descripcion, Fecha_Entrega 
                       FROM tarea WHERE Id_Curso = ? ORDER BY Fecha_Entrega ASC");
$stmt->execute([$idCurso]);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- 🔙 Botón de volver -->
    <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php" class="btn btn-outline-primary mb-4">
        <i class="fa-solid fa-arrow-left"></i> Volver al panel
    </a>

    

    <!-- 📝 Tabla de tareas -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-success text-white text-center rounded-top-4 py-3">
            <h4 class="fw-bold mb-0"><i class="fa-solid fa-clipboard-list"></i> Tareas del curso</h4>
        </div>
        <div class="card-body p-4">
            <?php if (empty($tareas)): ?>
                <div class="alert alert-warning text-center">
                    ⚠️ No hay tareas registradas para este curso.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Fecha de entrega</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareas as $i => $t): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($t['Titulo']) ?></td>
                                    <td><?= htmlspecialchars($t['Descripcion']) ?></td>
                                    <td>
                                        <?= $t['Fecha_Entrega'] 
                                            ? date("d/m/Y", strtotime($t['Fecha_Entrega'])) 
                                            : '<em>No definida</em>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>
