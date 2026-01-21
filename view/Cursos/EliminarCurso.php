<?php
// /view/Cursos/EliminarCurso.php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$cursos = CursoModel::obtenerCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarCurso'])) {
    CursoController::eliminarCurso($_POST['idCursoEliminar']);
    header('Location: ../Home/Home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Eliminar Curso</title>

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

      --danger1:rgba(255,80,80,.32);
      --danger2:rgba(255,0,0,.50);
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

    .page-wrap{
      max-width:1200px;
      margin:0 auto;
    }

    .page-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .btn-volver{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 18px;
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      color:var(--text);
      border-radius:14px;
      font-size:15px;
      border:1px solid var(--stroke);
      text-decoration:none;
      transition:.18s;
      box-shadow:0 10px 26px rgba(0,0,0,.22);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      line-height:1;
    }
    .btn-volver:hover{
      border-color:var(--stroke2);
      background:rgba(255,255,255,.14);
      color:var(--text);
    }
    .btn-volver i{
      font-size:16px;
      line-height:1;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      transform: translateY(1px);
    }

    .title{
      text-align:center;
      font-weight:700;
      font-size:32px;
      margin:10px 0 22px;
      text-shadow:0 2px 10px rgba(0,0,0,.35);
    }
    .title i{
      display:block;
      font-size:44px;
      margin-bottom:10px;
      opacity:.95;
    }

    .glass-card{
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      border:1px solid var(--stroke);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      padding:22px;
    }

    .card-center{
      max-width:680px;
      margin:0 auto;
    }

    .form-row{
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:center;
    }

    select{
      flex:1 1 360px;
      min-width:260px;
      height:44px;
      padding:10px 12px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,0.10);
      color:var(--text);
      font-weight:600;
      outline:none;
    }
    select:focus{ border-color:var(--stroke2); }

    /* FIX dropdown “blanco/vacío” */
    select option{
      background:#101733;
      color:#fff;
    }

    .btn-eliminar{
      height:44px;
      padding:0 18px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,0.18);
      background:linear-gradient(180deg, var(--danger1), rgba(255,80,80,.22));
      color:var(--text);
      font-weight:800;
      cursor:pointer;
      transition:.18s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      white-space:nowrap;
      min-width:160px;
    }
    .btn-eliminar:hover{
      background:linear-gradient(180deg, rgba(255,0,0,.45), rgba(255,0,0,.30));
      border-color:rgba(255,255,255,0.26);
    }

    /* Modal glass */
    .modal-content{
      background:linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.06)) !important;
      border:1px solid rgba(255,255,255,0.18) !important;
      border-radius:20px !important;
      box-shadow:var(--shadow) !important;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      color:var(--text);
    }
    .modal-header, .modal-footer{
      border:none !important;
    }
    .modal-title{
      font-weight:700;
    }
    .close{
      color:#fff !important;
      opacity:.95 !important;
      text-shadow:none !important;
      font-size:1.6rem;
    }
    .close:hover{ opacity:1 !important; }

    .modal-footer .btn{
      border-radius:14px;
      padding:10px 16px;
      font-weight:700;
    }

    .btn-outline-light{
      border-color:rgba(255,255,255,.30) !important;
    }

    .btn-danger{
      background:rgba(255,0,0,.55) !important;
      border-color:rgba(255,255,255,.16) !important;
    }
    .btn-danger:hover{
      background:rgba(255,0,0,.70) !important;
    }

    @media (max-width:520px){
      body{ padding:28px 14px; }
      .title{ font-size:26px; }
      select{ min-width:220px; }
    }
  </style>
</head>

<body>
  <div class="page-wrap">

    <div class="page-header">
      <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left" aria-hidden="true"></i>
        Volver
      </a>
      <div></div>
    </div>

    <h1 class="title">
      <i class="bi bi-trash-fill" aria-hidden="true"></i>
      Eliminar Curso
    </h1>

    <div class="card-center">
      <div class="glass-card">
        <form id="formEliminarCurso" method="POST">
          <div class="form-row">
            <select name="idCursoEliminar" required>
              <option value="">Seleccione un curso</option>
              <?php foreach ($cursos as $c): ?>
                <option value="<?= htmlspecialchars((string)$c['id'], ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars((string)$c['nombre'], ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button type="button" class="btn-eliminar" data-toggle="modal" data-target="#confirmModal">
              <i class="fa-solid fa-trash" aria-hidden="true"></i>
              Eliminar
            </button>
          </div>

          <input type="hidden" name="eliminarCurso" value="1">
        </form>
      </div>
    </div>

  </div>

  <!-- Modal confirmación -->
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
          ¿Estás seguro de que deseas eliminar este curso?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">
            Cancelar
          </button>

          <button type="button" class="btn btn-danger"
            onclick="document.getElementById('formEliminarCurso').submit()">
            Confirmar
          </button>
        </div>

      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
