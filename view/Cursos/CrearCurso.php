<?php
require_once __DIR__ . '/../../controller/CursoController.php';
require_once __DIR__ . '/../../model/CursoModel.php';

$mensajeMatricula = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crearCurso'])) CursoController::crearCurso();
    elseif (isset($_POST['asignarDocentes'])) CursoController::asignarDocentes();
    elseif (isset($_POST['eliminarCurso'])) CursoController::eliminarCurso($_POST['idCursoEliminar']);
    elseif (isset($_POST['matricularEstudiantes'])) {
        CursoModel::matricularEstudiantes($_POST['idCursoMatricula'], $_POST['estudiantes'] ?? []);
        $mensajeMatricula = "✅ Estudiantes matriculados correctamente.";
    }
}

$docentes = CursoModel::obtenerDocentes();
$cursos = CursoModel::obtenerCursos();
$estudiantes = CursoModel::obtenerEstudiantes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestionar Cursos</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    overflow-x: hidden;
}

h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 2.2rem;
}

.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    margin-bottom: 50px;
}

.card {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 30px 20px;
    flex: 0 1 300px;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
}

.card i {
    font-size: 50px;
    margin-bottom: 15px;
    color: #fff;
}

.card h3 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    font-weight: 700;
}

.card form label {
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
    font-weight: 600;
    text-align: left;
}

.card form input,
.card form textarea,
.card form select,
.card form button {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 10px;
    border-radius: 10px;
    border: none;
    outline: none;
    font-size: 1rem;
}

.card form textarea {
    resize: vertical;
}

.card form button {
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s ease;
}

.card form button:hover {
    background: rgba(255,255,255,0.35);
}

.alert {
    background: rgba(40,167,69,0.3);
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 10px;
    color: #fff;
}

.volver-btn {
    display: inline-block;
    margin-top: 30px;
    padding: 12px 25px;
    border-radius: 10px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s ease;
}

.volver-btn:hover {
    background: rgba(255,255,255,0.35);
}

/* Tabla de estudiantes */
.table-container {
    background: rgba(255,255,255,0.08);
    padding: 20px;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    max-width: 1000px;
    margin: 0 auto 50px auto;
}

.table-container h3 {
    text-align: center;
    margin-bottom: 20px;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}

.table-container th, .table-container td {
    padding: 12px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    text-align: left;
}

.table-container th {
    background: rgba(255,255,255,0.15);
    font-weight: 600;
}

.table-container tr:hover {
    background: rgba(255,255,255,0.25);
}

.table-container input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

@media(max-width: 768px){
    .card {
        flex: 0 1 90%;
    }
}
</style>
</head>
<body>

<h2> Gestión de Cursos</h2>

<div class="card-container">

    <div class="card">
        <i class="fa fa-plus-circle"></i>
        <h3>Crear Curso</h3>
        <form method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
            <label>Descripción:</label>
            <textarea name="descripcion" rows="3" required></textarea>
            <button type="submit" name="crearCurso">Crear</button>
        </form>
    </div>

    <div class="card">
        <i class="fa fa-chalkboard-teacher"></i>
        <h3>Asignar Docentes</h3>
        <form method="POST">
            <label>Curso:</label>
            <select name="idCurso" required>
                <option value="" disabled selected>Seleccione curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id']) ?>"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></option>
                <?php endforeach; ?>
            </select>
            <label>Docentes:</label>
            <select name="docentes[]" multiple size="5" required>
                <?php foreach ($docentes as $docente): ?>
                    <option value="<?= htmlspecialchars($docente['id']) ?>"><?= htmlspecialchars($docente['nombre'] ?? 'Sin nombre') ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="asignarDocentes">Asignar</button>
        </form>
    </div>

    <div class="card">
        <i class="fa fa-trash-alt"></i>
        <h3>Eliminar Curso</h3>
        <form method="POST">
            <label>Seleccione curso:</label>
            <select name="idCursoEliminar" required>
                <option value="" disabled selected>Seleccione curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id']) ?>"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="eliminarCurso">Eliminar</button>
        </form>
    </div>

</div>

<div class="table-container">
    <h3> Matricular Estudiante</h3>
    <?php if ($mensajeMatricula): ?>
        <div class="alert"><?= htmlspecialchars($mensajeMatricula) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Curso:</label>
        <select name="idCursoMatricula" required>
            <option value="" disabled selected>Seleccione curso</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']) ?>"><?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?></option>
            <?php endforeach; ?>
        </select>

        <table>
            <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre del Estudiante</th>
            
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><input type="checkbox" name="estudiantes[]" value="<?= htmlspecialchars($estudiante['id']) ?>"></td>
                        <td><?= htmlspecialchars($estudiante['nombre'] ?? 'Sin nombre') ?></td>
                    
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="matricularEstudiantes">Matricular Seleccionados</button>
    </form>
</div>

<div style="text-align:center;">
    <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="volver-btn"><i class="fa fa-arrow-left"></i> Volver</a>
</div>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
