<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";

/* Rol robusto */
$rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? '');
if (strtolower(trim($rol)) !== 'administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);
$notificaciones = $model->obtenerHistorial();

$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Gestión de Notificaciones</title>

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <style>
    :root{
      --bg:#2a2b38;
      --text:#fff;
      --muted:rgba(255,255,255,.75);
      --glass1:rgba(255,255,255,.10);
      --glass2:rgba(255,255,255,.06);
      --stroke:rgba(255,255,255,.20);
      --stroke2:rgba(255,255,255,.30);
      --shadow:0 14px 44px rgba(0,0,0,.42);
      --radius:18px;
    }

    body{
      font-family:'Poppins',sans-serif;
      font-size:15px;
      color:var(--text);
      padding:40px 25px;
      background:var(--bg);
      background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
      background-repeat:repeat;
      background-size:600px;
    }

    /* FIX: Quitar subrayado del rayo y cualquier enlace de tabla */
    a, a:hover, a:focus, a:active {
      text-decoration: none !important;
      outline: none !important;
    }

    .page-wrap{ max-width:1200px; margin:0 auto; }

    .page-header{
      display:flex; align-items:center; justify-content:space-between;
      gap:12px; margin-bottom:14px;
    }

    .btn-volver{
      display:inline-flex; align-items:center; gap:8px; padding:10px 18px;
      background:linear-gradient(180deg,var(--glass1),var(--glass2));
      color:var(--text) !important; border-radius:14px; border:1px solid var(--stroke);
      transition:.2s;
    }
    .btn-volver:hover{ border-color:var(--stroke2); background:rgba(255,255,255,.15); }

    .title{
      text-align:center; font-weight:700; font-size:32px;
      margin:10px 0 22px; text-shadow:0 2px 10px rgba(0,0,0,.35);
    }

    .glass-card{
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      border:1px solid var(--stroke); border-radius:var(--radius);
      box-shadow:var(--shadow); backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px); padding:22px; margin-bottom: 20px;
    }

    /* Inputs */
    label{ font-weight:600; font-size:13px; margin-bottom:8px; display:block; opacity:.9; }
    .form-control, textarea{
      background:rgba(255,255,255,0.08) !important; border:1px solid var(--stroke) !important;
      color:#fff !important; border-radius:14px !important; padding:12px 15px !important;
    }

    .btn-accion-principal{
      width:100%; height:48px; border-radius:14px; border:1px solid var(--stroke);
      background:rgba(255,255,255,0.12); color:#fff; font-weight:700;
      transition:.2s; cursor:pointer;
    }

    /* Tabla */
    .table{ color:#fff; margin-bottom:0; }
    .table thead th{ border:none; background:rgba(255,255,255,0.05); }
    .table td{ border-top:1px solid rgba(255,255,255,0.1); vertical-align:middle; }

    .badge-status{ padding:5px 12px; border-radius:10px; font-size:11px; font-weight:700; }
    .badge-ok{ background: rgba(40,199,111,0.2); color:#28c76f; border:1px solid rgba(40,199,111,0.3); }
    .badge-warn{ background: rgba(255,159,67,0.2); color:#ff9f43; border:1px solid rgba(255,159,67,0.3); }

    .btn-tabla{
      width:38px; height:38px; display:inline-flex; align-items:center; justify-content:center;
      border-radius:10px; border:1px solid var(--stroke); background:rgba(255,255,255,0.05);
      color:#fff !important; transition:.2s; margin-left:5px; cursor: pointer;
      text-decoration: none !important;
    }
    .btn-tabla:hover{ background:rgba(255,255,255,0.15); border-color:var(--stroke2); }
    .btn-danger-tabla:hover{ background:rgba(255,0,0,0.3); border-color:rgba(255,0,0,0.5); }

    /* --- MODAL IDÉNTICO AL DE ELIMINAR CURSO --- */
    .modal-content{
      background:linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.06)) !important;
      border:1px solid rgba(255,255,255,0.18) !important;
      border-radius:20px !important; box-shadow:var(--shadow) !important;
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); color:var(--text);
    }
    .modal-header, .modal-footer{ border:none !important; }
    .modal-title{ font-weight:700; }
    .close{ color:#fff !important; opacity:.95 !important; font-size:1.6rem; text-shadow: none !important; }
    
    .btn-outline-light{ 
        border-color:rgba(255,255,255,.30) !important; 
        border-radius:14px; padding:10px 16px; font-weight:700; 
    }
    .btn-danger{ 
        background:rgba(255,0,0,.55) !important; 
        border-radius:14px; padding:10px 16px; font-weight:700; 
        border:1px solid rgba(255,255,255,0.16) !important; 
    }
    .btn-danger:hover{ background:rgba(255,0,0,.70) !important; }
    /* ------------------------------------------- */

  </style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i> Volver
      </a>
    </div>

    <h1 class="title"><i class="bi bi-bell-fill"></i> Gestión de Notificaciones</h1>

    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="glass-card">
          <h5 class="mb-4 font-weight-bold">Nueva Notificación</h5>
          <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php">
            <input type="hidden" name="accion" value="crear">
            <div class="form-group">
              <label>Asunto</label>
              <input type="text" name="asunto" class="form-control" placeholder="Escriba el asunto..." required>
            </div>
            <div class="form-group">
              <label>Destinatario</label>
              <input type="email" name="destinatario" class="form-control" placeholder="correo@ejemplo.com" required>
            </div>
            <div class="form-group">
              <label>Mensaje</label>
              <textarea name="mensaje" rows="4" class="form-control" placeholder="Contenido..." required></textarea>
            </div>
            <button type="submit" class="btn-accion-principal">
              <i class="fa-solid fa-paper-plane mr-2"></i> Crear Notificación
            </button>
          </form>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="glass-card">
          <h5 class="mb-4 font-weight-bold">Historial Reciente</h5>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Asunto / Destinatario</th>
                  <th>Estado</th>
                  <th class="text-right">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($notificaciones)): ?>
                  <tr><td colspan="3" class="text-center opacity-50">No hay notificaciones</td></tr>
                <?php else: ?>
                  <?php foreach($notificaciones as $n): ?>
                    <tr>
                      <td>
                        <div class="font-weight-bold"><?= htmlspecialchars($n['Asunto']) ?></div>
                        <small class="opacity-50"><?= htmlspecialchars($n['Destinatario']) ?></small>
                      </td>
                      <td>
                        <span class="badge-status <?= ($n['Estado'] === 'Enviada') ? 'badge-ok' : 'badge-warn' ?>">
                          <?= htmlspecialchars($n['Estado']) ?>
                        </span>
                      </td>
                      <td class="text-right">
                        <?php if($n['Estado'] === 'Pendiente'): ?>
                          <a href="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php?accion=enviar_inmediato&id=<?= $n['Id_Notificacion'] ?>" class="btn-tabla" title="Enviar ahora">
                            <i class="fa-solid fa-bolt"></i>
                          </a>
                        <?php endif; ?>
                        
                        <button type="button" class="btn-tabla btn-danger-tabla" 
                                onclick="confirmarEliminacion(<?= $n['Id_Notificacion'] ?>, '<?= addslashes(htmlspecialchars($n['Asunto'])) ?>')">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar eliminación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que deseas eliminar la notificación: <br>
          <strong id="notifNombre" style="color:rgba(255,80,80,1);"></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">
            Cancelar
          </button>
          <button type="button" id="btnConfirmarFinal" class="btn btn-danger">
            Confirmar
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

  <script>
    let idAEliminar = null;

    function confirmarEliminacion(id, asunto) {
        idAEliminar = id;
        document.getElementById('notifNombre').innerText = asunto;
        $('#confirmModal').modal('show');
    }

    document.getElementById('btnConfirmarFinal').addEventListener('click', function() {
        if (idAEliminar) {
            // Se ejecuta la eliminación al confirmar
            window.location.href = `/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php?accion=eliminar&id=${idAEliminar}`;
        }
    });
  </script>
</body>
</html>