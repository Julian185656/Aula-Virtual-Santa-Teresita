<?php
require_once __DIR__ . '/../../model/CursoModel.php';

$nombreFiltro = $_GET['nombreEstudiante'] ?? '';
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$cursos = CursoModel::obtenerCursos();
$cursoSeleccionado = $_POST['idCursoMatricula'] ?? $_GET['idCursoMatricula'] ?? null;

$estudiantes = CursoModel::obtenerEstudiantes($nombreFiltro, $porPagina, $offset);
$totalEstudiantes = CursoModel::contarEstudiantes($nombreFiltro);
$totalPaginas = ceil($totalEstudiantes / $porPagina);

foreach ($estudiantes as &$est) {
    if (!isset($est['Id_Usuario'])) $est['Id_Usuario'] = $est['id'] ?? null;
    if (!isset($est['Nombre'])) $est['Nombre'] = $est['nombre'] ?? '';
}
unset($est);

$mensaje = "";

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

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
body{
    font-family: 'Poppins', sans-serif;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    color: #fff;
    padding: 40px 15px;
    text-align: center;
}

/* ENCABEZADO */
h1{
    font-size: 2.3rem;
    font-weight: 700;
    margin-bottom: 25px;
}
h1 i { font-size: 40px; }

/* BLOQUE GLASS */
.card-glass {
    max-width: 1100px;
    margin: 0 auto;
    padding: 30px;
    background: rgba(255,255,255,0.06);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    box-shadow: 0px 8px 25px rgba(0,0,0,0.4);
}

/* SELECT y SEARCH */
.filtro-bar {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

select, input[type="text"]{
    padding: 12px 15px;
    border-radius: 12px;
    border: none;
    min-width: 260px;
    background: rgba(255,255,255,0.12);
    color: #fff;
    font-size: 15px;
}
select option { color: #000; }

.buscar-btn {
    padding: 12px 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    border: none;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
}
.buscar-btn:hover {
    background: rgba(255,255,255,0.35);
}

/* TABLA */
table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 15px;
}
th{
    background: rgba(255,255,255,0.12);
    padding: 14px;
    font-weight: 600;
}
td{
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
}

/* CHIPS DE CURSOS */
.chip{
    display: inline-block;
    padding: 5px 10px;
    margin: 3px;
    background: rgba(255,255,255,0.2);
    border-radius: 50px;
    font-size: 13px;
}
.eliminar-btn{
    display: inline-block;
    padding: 5px 10px;
    margin: 3px;
    border-radius: 50px;
    background: rgba(255,70,70,0.4);
    border: none;
    color: #fff;
    font-size: 13px;
    cursor:pointer;
}
.eliminar-btn:hover{
    background: rgba(255,50,50,0.6);
}

/* BOTÓN MATRICULAR */
.matricular-btn{
    margin-top: 20px;
    padding: 12px 25px;
    border-radius: 12px;
    background: rgba(40,200,90,0.4);
    color: #fff;
    font-weight: 600;
    border:none;
    cursor:pointer;
}
.matricular-btn:hover{
    background: rgba(40,200,90,0.6);
}

/* PAGINACIÓN */
.paginacion a{
    padding: 6px 12px;
    border-radius: 8px;
    margin: 2px;
    background: rgba(255,255,255,0.12);
    color: #fff;
    text-decoration: none;
}
.paginacion a.activa{
    background: rgba(255,255,255,0.3);
    font-weight: 600;
}

/* BOTÓN VOLVER */
.volver-btn{
    display:inline-block;
    margin-top: 25px;
    padding: 12px 25px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    color: #fff;
    text-decoration: none;
}
.volver-btn:hover{
    background: rgba(255,255,255,0.35);
}

.chip, .eliminar-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    width: 120px;     /* 🔥 MISMO ANCHO PARA TODOS */
    height: 36px;     /* 🔥 MISMA ALTURA PARA TODOS */

    margin: 4px;
    border-radius: 50px;

    font-size: 14px;
    font-weight: 500;

    padding: 0 10px;     
    border: none;
    cursor: pointer;
    transition: 0.2s;
}

/* Chips grises */
.chip {
    background: rgba(255,255,255,0.22);
    color: #fff;
}

/* Botones rojos */
.eliminar-btn {
    background: rgba(255,80,80,0.55);
    color: #fff;
}

.eliminar-btn:hover {
    background: rgba(255,40,40,0.75);
}

</style>

</head>
<body>

<h1><i class="bi bi-people-fill"></i><br>Matricular Estudiantes</h1>

<div class="card-glass">

<?php if ($mensaje): ?>
    <div style="background:rgba(0,255,0,0.25); padding:10px; border-radius:12px; margin-bottom:20px;">
        <?= htmlspecialchars($mensaje) ?>
    </div>
<?php endif; ?>

<!-- FILTROS -->
<div class="filtro-bar">

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
        <button class="buscar-btn" type="submit"><i class="bi bi-search"></i> Buscar</button>
    </form>

</div>

<!-- TABLA -->
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
            $nombre = $e['Nombre'];
            $cursosEst = CursoModel::obtenerCursosPorEstudiante($idEstudiante);
            $listaCursos = !empty($cursosEst) ? $cursosEst : [];
            $cursoNombre = $cursoSeleccionado ? (CursoModel::obtenerCursoPorId($cursoSeleccionado)['nombre'] ?? '') : '';
            $estaMatriculado = $cursoSeleccionado ? in_array($cursoNombre, $listaCursos) : false;
        ?>
        <tr>
            <td>
                <?php if (!$estaMatriculado && $cursoSeleccionado): ?>
                    <input type="checkbox" name="estudiantes[]" value="<?= $idEstudiante ?>">
                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($nombre) ?></td>

            <td>
                <?php if (empty($listaCursos)) echo "<span class='chip'>Sin cursos</span>"; ?>
                <?php foreach ($listaCursos as $c): ?>
                    <span class="chip"><?= htmlspecialchars($c) ?></span>
                <?php endforeach; ?>
            </td>

            <td>
                <?php foreach ($listaCursos as $cursoEliminarNombre):
                    $cursoEliminar = CursoModel::obtenerCursoIdPorNombre($cursoEliminarNombre);
                ?>
                <?php if ($cursoEliminar): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="idCursoEliminar" value="<?= $cursoEliminar ?>">
                        <input type="hidden" name="idEstudianteEliminar" value="<?= $idEstudiante ?>">
                        <button type="submit" name="eliminar" class="eliminar-btn">
                            <?= htmlspecialchars($cursoEliminarNombre) ?>
                        </button>
                    </form>
                <?php endif; endforeach; ?>
            </td>
        </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <?php if ($cursoSeleccionado): ?>
    <button class="matricular-btn" type="submit" name="matricular">
        Matricular Seleccionados
    </button>
    <?php endif; ?>
</form>

<!-- PAGINACIÓN -->
<div class="paginacion" style="margin-top:20px;">
    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
        <a class="<?= ($p == $pagina) ? 'activa' : '' ?>"
           href="?nombreEstudiante=<?= urlencode($nombreFiltro) ?>&idCursoMatricula=<?= $cursoSeleccionado ?>&pagina=<?= $p ?>">
           <?= $p ?>
        </a>
    <?php endfor; ?>
</div>

</div>

<a href="../Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
