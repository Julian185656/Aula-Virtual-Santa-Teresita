<?php
// /view/Cursos/AsignarDocentes.php
require_once __DIR__ . '/../../model/CursoModel.php';
require_once __DIR__ . '/../../controller/CursoController.php';

$docentes = CursoModel::obtenerDocentes();
$cursos   = CursoModel::obtenerCursos();
$asignacionesActuales = CursoModel::obtenerAsignaciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignarProfesores'])) {
    CursoController::asignarDocentes();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Asignar Profesores</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
      background:var(--bg);
      background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
      background-repeat:repeat;
      background-size:600px;
      color:var(--text);
      padding:40px 25px;
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

    h1{
      text-align:center;
      font-weight:700;
      font-size:32px;
      margin:10px 0 22px;
      text-shadow:0 2px 10px rgba(0,0,0,.35);
    }

    .glass-card{
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      border:1px solid var(--stroke);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      overflow:hidden;
    }

    .card-pad{ padding:18px; }

    /* Tabla scroll */
    .table-wrap{
      overflow:auto;
      max-height: 520px;
      border-radius:var(--radius);
    }

    table{
      width:100%;
      border-collapse:separate;
      border-spacing:0;
      font-size:14px;
      min-width: 980px; /* asegura scroll horizontal */
    }

    thead th{
      position: sticky;
      top: 0;
      z-index: 3;
      background:rgba(255,255,255,0.10);
      padding:14px 12px;
      font-weight:700;
      text-align:center;
      border-bottom:1px solid rgba(255,255,255,0.18);
      white-space:nowrap;
    }

    tbody td{
      padding:14px 12px;
      text-align:center;
      border-bottom:1px solid rgba(255,255,255,0.14);
      background:transparent;
    }

    tbody tr:nth-child(even) td{
      background:rgba(255,255,255,0.05);
    }
    tbody tr:hover td{
      background:rgba(255,255,255,0.08);
    }

    /* Primera columna sticky (Profesor) */
    .sticky-col{
      position: sticky;
      left: 0;
      z-index: 4;
      text-align:left !important;
      white-space:nowrap;
      background:rgba(255,255,255,0.08);
      border-right:1px solid rgba(255,255,255,0.14);
    }
    thead .sticky-col{
      z-index: 6;
      background:rgba(255,255,255,0.12);
    }

    .prof-name{
      display:inline-block;
      max-width:220px;
      overflow:hidden;
      text-overflow:ellipsis;
      vertical-align:middle;
    }

    /* Checkbox */
    .chk{
      width:18px;
      height:18px;
      cursor:pointer;
      accent-color:#4da3ff;
      transform: translateY(1px);
    }

    /* Botón guardar */
    .actions{
      padding:14px 18px 18px;
      border-top:1px solid rgba(255,255,255,0.12);
      display:flex;
      justify-content:center;
    }

    .btn-guardar{
      height:44px;
      min-width: 280px;
      padding:0 18px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,0.14);
      color:var(--text);
      font-weight:800;
      cursor:pointer;
      transition:.18s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
    }
    .btn-guardar:hover{
      border-color:var(--stroke2);
      background:rgba(255,255,255,0.22);
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
    .modal-header, .modal-footer{ border:none !important; }
    .modal-title{ font-weight:800; }
    .modal-footer .btn{ border-radius:14px; font-weight:800; }
    .btn-outline-light{ border-color:rgba(255,255,255,.30) !important; }
    .btn-danger{
      background:rgba(255,0,0,.55) !important;
      border-color:rgba(255,255,255,.16) !important;
    }
    .btn-danger:hover{ background:rgba(255,0,0,.70) !important; }

    @media (max-width:520px){
      body{ padding:28px 14px; }
      h1{ font-size:26px; }
      .table-wrap{ max-height: 460px; }
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

    <h1><i class="bi bi-person-badge-fill"></i> Asignar Profesores</h1>

    <div class="glass-card">
      <form method="POST" id="formAsignaciones">

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th class="sticky-col">Profesor</th>
                <?php foreach ($cursos as $c): ?>
                  <th><?= htmlspecialchars($c['nombre']) ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($docentes as $d): ?>
                <tr>
                  <td class="sticky-col">
                    <span class="prof-name"><?= htmlspecialchars($d['nombre']) ?></span>
                  </td>

                  <?php foreach ($cursos as $c): ?>
                    <td>
                      <input
                        class="chk"
                        type="checkbox"
                        name="asignaciones[<?= $c['id'] ?>][]"
                        value="<?= $d['id'] ?>"
                        <?php
                          if (!empty($asignacionesActuales[$c['id']]) &&
                              in_array($d['id'], $asignacionesActuales[$c['id']])) {
                              echo 'checked';
                          }
                        ?>
                      >
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="actions">
          <button type="button" class="btn-guardar" onclick="confirmarGuardar()">
            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
            Guardar Asignaciones
          </button>
        </div>

        <input type="hidden" name="asignarProfesores" value="1">
      </form>
    </div>

  </div>

  <!-- Modal confirmación -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar asignaciones</h5>
        </div>
        <div class="modal-body">
          ¿Seguro que deseas guardar los cambios?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger" onclick="enviarFormulario()">Confirmar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

  <script>
    function confirmarGuardar(){
      $('#confirmModal').modal('show');
    }
    function enviarFormulario(){
      document.getElementById('formAsignaciones').submit();
    }
  </script>
</body>
</html>
