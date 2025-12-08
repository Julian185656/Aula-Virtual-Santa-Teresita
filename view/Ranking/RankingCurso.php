<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ranking del Curso</title>
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="p-4" style="background:#f4f4f4;">

<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">
            <i class="fa-solid fa-trophy me-2"></i>Ranking del Curso
        </h3>

        <a class="btn btn-secondary"
           href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php">
            Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <?php if (!empty($ranking)): ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:80px;">#</th>
                                <th>Estudiante</th>
                                <th style="width:120px;">Grado</th>
                                <th style="width:120px;">Sección</th>
                                <th style="width:160px;">Puntos</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $pos = 1; ?>
                        <?php foreach ($ranking as $row): ?>
                            <tr>
                                <td><strong><?= $pos++ ?></strong></td>
                                <td><?= htmlspecialchars($row['nombre'] ?? 'Sin nombre') ?></td>
                                <td><?= htmlspecialchars($row['Grado'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['Seccion'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-success">
                                        <?= (int)($row['puntos_total'] ?? 0) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <small class="text-muted">
                    Los puntos se calculan sumando las evaluaciones de ranking (1 a 10) asignadas por el docente en cada tarea.
                </small>

            <?php else: ?>

                <div class="alert alert-info mb-0">
                    Aún no hay puntos registrados para este curso.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>

