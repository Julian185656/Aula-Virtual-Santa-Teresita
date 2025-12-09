<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Asistencia</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
}

.container { max-width: 1200px; margin: 0 auto; }
h2 { color: #fff; text-align: center; margin-bottom: 30px; text-shadow: 0 2px 6px rgba(0,0,0,0.6); }


.btn-outline-light {
    border-radius: 15px;
    padding: 8px 20px;
    transition: 0.2s ease;
}
.btn-outline-light:hover { background-color: rgba(255,255,255,0.15); }

/* Filtros */
form.row.g-3.align-items-end {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
    background: rgba(255,255,255,0.05);
    padding: 20px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
}
form select, form input {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 15px;
    border: none;
    padding: 10px 15px;
}
form select option { background-color: #000; color: #fff; }
form button {
    border-radius: 15px;
    padding: 10px 20px;
}


.card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
}


.table thead {
    background: rgba(255,255,255,0.1);
    font-weight: bold;
}
.table td, .table th {
    color: #fff;
}
.table tr:nth-child(even) { background: rgba(255,255,255,0.02); }
.table tr:hover { background: rgba(255,255,255,0.1); }
.badge { font-weight: 500; }


.btn-primary {
    background-color: #6a5acd;
    border: none;
}
.btn-primary:hover { background-color: #836fff; }
.btn-outline-primary {
    color: #fff;
    border-color: #fff;
}
.btn-outline-primary:hover { background-color: rgba(255,255,255,0.15); }


.pagination a {
    color: #fff;
    background: rgba(255,255,255,0.1);
    margin: 0 3px;
}
.pagination a:hover { background: rgba(255,255,255,0.35); }
.pagination .active a { background: #6a5acd !important; }
</style>
</head>

<body>
<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h2>
        <i class="bi bi-clock-history me-2"></i>
        Historial de asistencia individual
    </h2>

    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label">Curso</label>
            <select name="curso" class="form-select" required>
                <option value="">Seleccione un curso</option>
                <?php foreach($cursos as $c): 
                    $idC = (int)$c['Id_Curso']; 
                    $nombreC = $c['Curso'] ?? $c['Nombre'] ?? 'Curso '.$idC;
                ?>
                <option value="<?= $idC ?>" <?= ($cursoId===$idC)?'selected':'' ?>>
                    <?= htmlspecialchars($nombreC) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($fechaDesde ?? '') ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($fechaHasta ?? '') ?>">
        </div>

        <div class="col-md-2 d-flex justify-content-start align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="row">

   
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="mb-3"><i class="bi bi-people-fill me-2"></i> Estudiantes del curso</h5>
                <?php if($cursoId<=0): ?>
                    <p class="text-muted">Selecciona un curso para ver la lista de estudiantes.</p>
                <?php elseif(empty($alumnos)): ?>
                    <p class="text-muted">No hay estudiantes matriculados en este curso.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-dark">
                                <tr><th>Nombre</th><th>Correo</th><th>Historial</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($alumnos as $al): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($al['Nombre']) ?></td>
                                        <td><?= htmlspecialchars($al['Email']) ?></td>
                                        <td class="text-center">
                                            <a href="?curso=<?= $cursoId ?>&alumno=<?= $al['Id'] ?>&desde=<?= $fechaDesde ?>&hasta=<?= $fechaHasta ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-list-ul me-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100 p-3">
                <h5 class="mb-3"><i class="bi bi-card-checklist me-2"></i> Historial del alumno</h5>

                <?php if($cursoId<=0 || $usuarioId<=0): ?>
                    <p class="text-muted">Selecciona un curso y luego un estudiante para ver su historial.</p>
                <?php else: ?>
                    <div class="mb-2">
                        <strong>Alumno:</strong> <?= htmlspecialchars($alumnoNombre) ?><br>
                        <strong>Curso:</strong> <?= htmlspecialchars($cursoNombre) ?>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success me-1">Presentes: <?= $resumen['presentes'] ?></span>
                        <span class="badge bg-danger me-1">Ausentes: <?= $resumen['ausentes'] ?></span>
                        <span class="badge bg-secondary">Total: <?= $resumen['presentes']+$resumen['ausentes'] ?></span>
                    </div>

                    <?php if(empty($historial)): ?>
                        <p class="text-muted mt-3">No hay registros en el rango seleccionado.</p>
                    <?php else: ?>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-dark">
                                    <tr><th>Fecha</th><th>Estado</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($historial as $h): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($h['Fecha']) ?></td>
                                            <td>
                                                <?php if((int)$h['Presente']===1): ?>
                                                    <span class="badge bg-success">Presente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Ausente</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($totalPaginas>1): ?>
                            <nav class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <?php for($i=1;$i<=$totalPaginas;$i++): ?>
                                        <li class="page-item <?= ($pagina==$i)?'active':'' ?>">
                                            <a class="page-link" href="?curso=<?= $cursoId ?>&alumno=<?= $usuarioId ?>&desde=<?= $fechaDesde ?>&hasta=<?= $fechaHasta ?>&pagina=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
