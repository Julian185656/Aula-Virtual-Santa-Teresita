<?php
session_start();

$rol = $_SESSION['usuario']['rol'] ?? ($_SESSION['rol'] ?? null);
$rol = strtolower($rol);

if ($rol !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MaterialModel.php";

$model = new MaterialModel($pdo);

$cursoId = intval($_GET['curso'] ?? 0);
$materiales = $model->obtenerMaterialPorCurso($cursoId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Material - Administrador</title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
    text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

h1 {
    font-size: 36px;
    margin-bottom: 30px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.card-glass {
    max-width: 800px;
    margin: 20px auto;
    padding: 25px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    text-align: center;
}

.card-glass h4 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #fff;
}

.card-glass input[type="text"] {
    width: 100%;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
    background: rgba(255,255,255,0.08);
    color: #fff;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.card-glass input[type="text"]:focus {
    outline: none;
    background: rgba(255,255,255,0.15);
    border-color: rgba(255,255,255,0.4);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
}

/* Selector de archivos personalizado */
.file-input-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-input-wrapper input[type="file"] {
    display: none;
}

.file-input-label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 15px 20px;
    border-radius: 15px;
    border: 2px dashed rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.08);
    color: #c4c3ca;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    gap: 12px;
    text-align: center;
}

.file-input-label:hover {
    border-color: rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.12);
    color: #fff;
}

.file-input-label.has-file {
    border-style: solid;
    border-color: rgba(0,151,178,0.6);
    background: rgba(0,151,178,0.1);
    color: #fff;
}

.file-input-label i {
    font-size: 20px;
}

.file-name {
    font-size: 12px;
    margin-top: 8px;
    opacity: 0.8;
}

.card-glass button {
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-weight: bold;
    padding: 12px 30px;
    border-radius: 15px;
    border: none;
    cursor: pointer;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.card-glass button:hover {
    background: rgba(255,255,255,0.35);
}

.table-container {
    max-height: 400px;
    overflow-y: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    text-align: center;
}

thead {
    background: rgba(255,255,255,0.1);
    font-weight: bold;
}

tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.02);
}

tbody tr:hover {
    background: rgba(255,255,255,0.1);
}

.btn-volver {
    display:inline-block;
    background: #ff9f43;
    padding: 10px 20px;
    border-radius: 15px;
    color: #fff;
    text-decoration:none;
    margin-top: 20px;
    transition:0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-volver:hover { background: #e88f32; }

.btn-accion {
    padding: 6px 12px;
    border-radius: 10px;
    border: none;
    color: #fff;
    cursor: pointer;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.btn-subir { background: #0097b2; }
.btn-eliminar { background: #ff4c4c; }
.btn-descargar { background: #28c76f; }

.btn-subir:hover { background: rgba(0,151,178,0.7); }
.btn-eliminar:hover { background: rgba(255,76,76,0.7); }
.btn-descargar:hover { background: rgba(40,199,111,0.7); }
</style>
</head>
<body>

<h1><i class="fas fa-folder-open"></i><br>Administración de Material</h1>

<div class="card-glass">
    <form action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php"
          method="POST" enctype="multipart/form-data" id="uploadForm">
        
        <input type="hidden" name="accion" value="subir">
        <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">

        <input type="text" name="Titulo" placeholder="Título" required>

        <div class="file-input-wrapper">
            <input type="file" name="archivo" id="fileInput" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar,.jpg,.png,.mp4" required>
            <label for="fileInput" class="file-input-label" id="fileLabel">
                <i class="fas fa-cloud-upload-alt"></i>
                <div>
                    <div>Selecciona un archivo o arrastralo aquí</div>
                    <div class="file-name" id="fileName" style="display: none;"></div>
                </div>
            </label>
        </div>

        <button class="btn-subir" type="submit">
            <i class="fas fa-upload"></i> Subir archivo
        </button>
    </form>
</div>

<div class="card-glass table-container">
    <h4><i class="fas fa-list"></i> Archivos del Curso</h4>
    <table>
        <thead>
            <tr>
                <th><i class="fas fa-tag"></i> Título</th>
                <th><i class="fas fa-download"></i> Descargar</th>
                <th><i class="fas fa-trash-alt"></i> Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($materiales)): ?>
                <?php foreach ($materiales as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['Titulo']) ?></td>
                        <td>
                            <a class="btn-descargar btn-accion" 
                               href="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php?accion=descargar&id=<?= $m['Id_Material'] ?>">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/MaterialController.php" style="display: inline;">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="idMaterial" value="<?= $m['Id_Material'] ?>">
                                <input type="hidden" name="Id_Curso" value="<?= $cursoId ?>">
                                <button type="submit" class="btn-eliminar btn-accion">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No hay material en este curso.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="/Aula-Virtual-Santa-Teresita/view/Admin/SeleccionarCursoMaterial.php" class="btn-volver">
    <i class="fas fa-arrow-left"></i> Volver
</a>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileLabel = document.getElementById('fileLabel');
    const fileName = document.getElementById('fileName');
    const file = e.target.files[0];
    
    if (file) {
        fileName.textContent = file.name;
        fileName.style.display = 'block';
        fileLabel.classList.add('has-file');
    } else {
        fileName.style.display = 'none';
        fileLabel.classList.remove('has-file');
    }
});
</script>

</body>
</html>