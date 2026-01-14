<?php
session_start();
require_once __DIR__ . "/../../model/TareaModel.php";
require_once __DIR__ . "/../../model/db.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol'] ?? '') !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idUsuario = (int)$_SESSION['id_usuario'];
$idCurso = $_GET['idCurso'] ?? null;

$mensaje = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entregarTarea'])) {
    $idTarea = (int)($_POST['idTarea'] ?? 0);

    if ($idTarea && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {

        $rutaCarpeta = $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/uploads/";
        if (!is_dir($rutaCarpeta)) mkdir($rutaCarpeta, 0777, true);

        $nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
        $rutaDestino = $rutaCarpeta . $nombreArchivo;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {

            $archivoUrl = "/Aula-Virtual-Santa-Teresita/uploads/" . $nombreArchivo;

            $sel = $pdo->prepare("SELECT Id_Entrega FROM aulavirtual.entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
            $sel->execute([$idTarea, $idUsuario]);
            $ex = $sel->fetch(PDO::FETCH_ASSOC);

            if ($ex) {
                $upd = $pdo->prepare("UPDATE aulavirtual.entrega_tarea SET Archivo_URL=?, Fecha_Entrega=GETDATE() WHERE Id_Entrega=?");
                $upd->execute([$archivoUrl, $ex['Id_Entrega']]);
            } else {
                $ins = $pdo->prepare("INSERT INTO aulavirtual.entrega_tarea (Id_Tarea, Id_Estudiante, Archivo_URL, Fecha_Entrega) VALUES (?,?,?,GETDATE())");
                $ins->execute([$idTarea, $idUsuario, $archivoUrl]);
            }

            $mensaje = "Archivo entregado correctamente.";
        } else {
            $errores[] = "Error al subir el archivo.";
        }
    } else {
        $errores[] = "Seleccione un archivo válido.";
    }
}

$tareas = TareaModel::obtenerTareasEstudiante($idUsuario, $idCurso);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Tareas</title>

<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#2a2b38 url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg') repeat;
    padding:40px 15px;
    color:#c4c3ca;
}
h2{color:#fff;text-align:center;margin-bottom:30px;}

.card{
    background:rgba(255,255,255,.05);
    backdrop-filter:blur(10px);
    border-radius:18px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 8px 25px rgba(0,0,0,.35);
}

.btn-entregar,.btn-file{
    background:rgba(255,255,255,.15);
    color:#fff;
    border:none;
    border-radius:10px;
    padding:8px 16px;
}
.btn-entregar:hover,.btn-file:hover{
    background:rgba(255,255,255,.30);
}

input[type=file]{display:none;}

.archivo-nombre{
    font-size:.9rem;
    color:#4dd0e1;
    margin-top:6px;
}

/* MODAL OSCURO IGUAL AL DE USUARIOS */
.modal-content{
    background-color:#343a40;
    color:#fff;
    border-radius:8px;
}
.modal-header,
.modal-footer{
    border-color:#495057;
}
.btn-close{
    filter:invert(1);
}
</style>
</head>

<body>

<a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php"
class="btn btn-outline-light mb-3" style="border-radius:15px;">
<i class="fa fa-arrow-left"></i> Volver
</a>

<h2>Mis Tareas</h2>

<?php if ($mensaje): ?>
<div class="alert alert-success text-center"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="container">
<div class="row">

<?php foreach ($tareas as $tarea): ?>
<div class="col-lg-6">
<div class="card">

<h5><?= htmlspecialchars($tarea['Titulo']) ?></h5>
<p><?= htmlspecialchars($tarea['Descripcion']) ?></p>
<p><strong>Entrega:</strong> <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></p>

<form method="POST" enctype="multipart/form-data" class="form-entrega">
<input type="hidden" name="idTarea" value="<?= (int)$tarea['Id_Tarea'] ?>">
<input type="hidden" name="entregarTarea" value="1">

<label for="archivo-<?= $tarea['Id_Tarea'] ?>" class="btn-file">
Seleccionar archivo
</label>
<input type="file" name="archivo" id="archivo-<?= $tarea['Id_Tarea'] ?>" required>

<div id="archivo-nombre-<?= $tarea['Id_Tarea'] ?>" class="archivo-nombre">
Ningún archivo seleccionado
</div>

<button type="button" class="btn-entregar mt-3 btn-confirmar">
Entregar
</button>
</form>

</div>
</div>
<?php endforeach; ?>

</div>
</div>

<!-- MODAL CONFIRMACIÓN BOOTSTRAP -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Confirmar entrega</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ¿Deseas entregar este archivo?
        <div id="archivoConfirmado" class="archivo-nombre mt-2"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-light" data-bs-dismiss="modal">
          Cancelar
        </button>
        <button class="btn btn-danger" id="btnConfirmarEnvio">
          Confirmar
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
let formActual=null;
const modal = new bootstrap.Modal(document.getElementById('confirmModal'));

document.querySelectorAll('.btn-confirmar').forEach(btn=>{
    btn.onclick=()=>{
        formActual = btn.closest('form');
        const file = formActual.querySelector('input[type=file]');
        document.getElementById('archivoConfirmado').innerText =
            file.files.length ? file.files[0].name : 'Sin archivo';
        modal.show();
    };
});

document.getElementById('btnConfirmarEnvio').onclick=()=>{
    if(formActual) formActual.submit();
};

document.querySelectorAll('input[type=file]').forEach(input=>{
    input.onchange=()=>{
        const id=input.id.split('-')[1];
        document.getElementById('archivo-nombre-'+id).innerText =
            input.files.length ? input.files[0].name : 'Ningún archivo seleccionado';
    };
});
</script>

</body>
</html>
