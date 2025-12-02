<?php
require_once __DIR__ . '/../../model/CursoModel.php';

// --- Parámetros de filtro y paginación ---
$nombreFiltro = $_GET['nombreEstudiante'] ?? '';
$pagina = max(1, (int)($_GET['pagina'] ?? 1)); // página actual
$porPagina = 10;                               // registros por página
$offset = ($pagina - 1) * $porPagina;         // inicio para LIMIT

// --- Obtener cursos ---
$cursos = CursoModel::obtenerCursos();
$cursoSeleccionado = $_POST['idCursoMatricula'] ?? $_GET['idCursoMatricula'] ?? null;

// --- Obtener estudiantes y total ---
$estudiantes = CursoModel::obtenerEstudiantes($nombreFiltro, $porPagina, $offset);
$totalEstudiantes = CursoModel::contarEstudiantes($nombreFiltro);
$totalPaginas = ceil($totalEstudiantes / $porPagina);

// --- Corregir índices para evitar errores ---
foreach ($estudiantes as &$est) {
    if (!isset($est['Id_Usuario'])) $est['Id_Usuario'] = $est['id'] ?? null;
    if (!isset($est['Nombre'])) $est['Nombre'] = $est['nombre'] ?? '';
}
unset($est);

$mensaje = "";

// --- Procesar formularios ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['matricular'])) {
        CursoModel::matricularEstudiantes($_POST['idCursoMatricula'], $_POST['estudiantes'] ?? []);
        $mensaje = "Estudiantes matriculados correctamente.";
    } elseif (isset($_POST['eliminar'])) {
        CursoModel::eliminarMatricula($_POST['idCursoEliminar'], $_POST['idEstudianteEliminar']);
        $mensaje = "Matrícula eliminada correctamente.";
    }

    header("Location: ?idCursoMatricula=" . ($_POST['idCursoMatricula'] ?? '') . "&nombreEstudiante=" . urlencode($nombreFiltro) . "&pagina=" . $pagina);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Matricular Estudiantes</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
body { font-family: 'Montserrat', sans-serif; background: #1f272b; color: #fff; padding: 20px; text-align: center; }
.card-glass { max-width: 1000px; margin: 0 auto; padding: 30px; background: rgba(255,255,255,0.05); border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(12px); box-shadow: 0 8px 25px rgba(0,0,0,0.35); }
.card-glass select, .card-glass input[type="text"] { width: 100%; padding: 12px; border-radius: 15px; border: none; background: rgba(255,255,255,0.12); color: #fff; }
.card-glass select option { color: #000; }

table { width: 100%; border-collapse: collapse; margin-top: 15px; color: #fff; }
th, td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.2); }
tr:hover { background: rgba(255,255,255,0.12); }
th { text-align: center; }
td { vertical-align: middle; }
td.eliminar { text-align: center; }

button, .eliminar-btn { padding: 6px 12px; border-radius: 10px; border: none; color: #fff; cursor: pointer; transition: 0.2s; }
button { background: rgba(255,255,255,0.15); font-weight: bold; margin-top: 20px; }
button:hover, .eliminar-btn:hover { background: rgba(255,255,255,0.35); }

.volver-btn { display:inline-block; background: rgba(255,255,255,0.15); padding: 10px 20px; border-radius: 15px; color: #fff; text-decoration:none; margin-top: 30px; transition:0.2s; }
.volver-btn:hover { background: rgba(255,255,255,0.35); }

h1 i { font-size: 40px; margin-bottom: 10px; }
.alert-success { background: rgba(0,255,0,0.2); color:#0f0; padding:10px; margin-bottom:20px; border-radius:10px; }

/* Filtro */
.form-filtro { display:flex; justify-content:center; align-items:center; gap:10px; margin-bottom:20px; max-width:600px; margin-left:auto; margin-right:auto; }
.form-filtro form { flex:1; display:flex; gap:10px; align-items:center; }
.form-filtro input[type="text"], .form-filtro select { flex:1; }
.form-filtro button { flex:0 0 auto; }
</style>
</head>
<body>

<h1><i class="bi bi-people-fill"></i><br>Matricular Estudiantes</h1>

<div class="card-glass">

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Filtro y selección de curso -->
    <div class="form-filtro">
        <!-- Buscador -->


<form method="GET">
            <select name="idCursoMatricula" onchange="this.form.submit()">
                <option value="">Seleccione un curso</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($cursoSeleccionado == $c['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="nombreEstudiante" value="<?= htmlspecialchars($nombreFiltro) ?>">
        </form>


        <form method="GET">
            <input type="text" name="nombreEstudiante" placeholder="Buscar estudiante..." value="<?= htmlspecialchars($nombreFiltro) ?>">
            <input type="hidden" name="idCursoMatricula" value="<?= htmlspecialchars($cursoSeleccionado) ?>">
            <button type="submit"><i class="bi bi-search"></i> Buscar</button>
        </form>

        <!-- Selección de curso -->
        
    </div>

    <!-- Tabla de estudiantes -->
    <form method="POST">
        <input type="hidden" name="idCursoMatricula" value="<?= htmlspecialchars($cursoSeleccionado) ?>">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Estudiante</th>
                    <th>Cursos actuales</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $e): 
                    $idEstudiante = $e['Id_Usuario'];  
                    $nombreEstudiante = $e['Nombre'];
                    $cursosEst = CursoModel::obtenerCursosPorEstudiante($idEstudiante);
                    $cursoNombre = $cursoSeleccionado ? (CursoModel::obtenerCursoPorId($cursoSeleccionado)['nombre'] ?? '') : '';
                    $estaMatriculado = $cursoSeleccionado ? in_array($cursoNombre, $cursosEst) : false;
                    $listaCursos = !empty($cursosEst) ? implode(", ", $cursosEst) : "Sin cursos";
                ?>
                <tr>
                    <td>
                        <?php if (!$estaMatriculado && $cursoSeleccionado): ?>
                            <input type="checkbox" name="estudiantes[]" value="<?= $idEstudiante ?>">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($nombreEstudiante) ?></td>
                    <td style="font-size:13px; color:#ccc;"><?= htmlspecialchars($listaCursos) ?></td>
                    <td class="eliminar">
                        <?php foreach ($cursosEst as $cursoEliminarNombre):
                            $cursoEliminar = CursoModel::obtenerCursoIdPorNombre($cursoEliminarNombre);
                            if ($cursoEliminar): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="idCursoEliminar" value="<?= $cursoEliminar ?>">
                                <input type="hidden" name="idEstudianteEliminar" value="<?= $idEstudiante ?>">
                                <button type="submit" name="eliminar" class="eliminar-btn"><?= htmlspecialchars($cursoEliminarNombre) ?></button>
                            </form>
                        <?php endif; endforeach; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($cursoSeleccionado): ?>
        <button type="submit" name="matricular">Matricular Seleccionados</button>
        <?php endif; ?>
    </form>

    <!-- Paginación -->
    <div style="margin-top:20px;">
        <?php for ($p=1; $p<=$totalPaginas; $p++): ?>
            <a href="?nombreEstudiante=<?= urlencode($nombreFiltro) ?>&idCursoMatricula=<?= $cursoSeleccionado ?>&pagina=<?= $p ?>" style="margin:2px; padding:5px 10px; background:#333; color:#fff; border-radius:5px; text-decoration:none; <?= ($p==$pagina)?'font-weight:bold;background:#555;':'' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>

</div>

<a href="../Home/Home.php" class="volver-btn"><i class="bi bi-arrow-left-circle-fill"></i> Volver</a>

</body>
</html>
