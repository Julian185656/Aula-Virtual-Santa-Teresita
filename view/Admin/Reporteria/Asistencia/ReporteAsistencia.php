<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Reporte de Asistencia</title>

  <!-- Fuente -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root{
      --bg:#2a2b38;
      --text:#fff;
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

    .page-wrap{
      max-width:1200px;
      margin:0 auto;
    }

    h1{
      text-align:center;
      font-weight:700;
      font-size:32px;
      margin:10px 0 22px;
      text-shadow:0 2px 10px rgba(0,0,0,.35);
    }

    .page-header{
      display:flex;
      justify-content:flex-start;
      align-items:center;
      margin-bottom:14px;
    }

    /* ==================== BOTÓN VOLVER (BASE) ==================== */
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

    /* ==================== GLASS CARD (BASE) ==================== */
    .glass-card{
      background:linear-gradient(180deg, var(--glass1), var(--glass2));
      border:1px solid var(--stroke);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      overflow: visible;
    }

    /* ==================== FILTROS (FIX: NO SE MONTAN / NO VACÍOS) ==================== */
    .filter-form{
      display:flex;
      align-items:center;
      justify-content:center;
      gap:12px;
      flex-wrap:wrap;
      position:relative;
      z-index:5;
    }

    .filter-form select,
    .filter-form .btn-ghost{
      height:42px;
    }

    .filter-form select{
      min-width:220px;
      padding:10px 12px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,0.35);
      background:rgba(255,255,255,0.10);
      color:#fff;
      font-weight:600;
      outline:none;
    }

    /* FIX: dropdown “en blanco” al abrir */
    .filter-form select option{
      background:#101733;
      color:#fff;
    }

    /* ==================== BOTONES (MATCH) ==================== */
    .btn-ghost{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:8px;
      padding:0 16px;
      border-radius:12px;
      border:1px solid rgba(255,255,255,0.35);
      background:rgba(255,255,255,0.10);
      color:#fff;
      font-weight:600;
      text-decoration:none;
      transition:.18s;
      white-space:nowrap;
      cursor:pointer;
    }
    .btn-ghost:hover{
      background:rgba(255,255,255,0.20);
      color:#fff;
    }

    /* ==================== TABLA ==================== */
    .table-wrap{
      overflow:auto;
      border-radius:var(--radius);
    }

    table{
      width:100%;
      border-collapse:collapse;
      font-size:14px;
      min-width:820px;
    }

    thead tr{
      background:rgba(255,255,255,0.10);
    }

    th, td{
      padding:14px 10px;
      color:#fff !important;
      text-align:center;
      vertical-align:middle;
    }

    tbody tr:nth-child(even){
      background:rgba(255,255,255,0.05);
    }

    tbody tr:hover{
      background:rgba(255,255,255,0.08);
    }

    @media (max-width:520px){
      body{ padding:28px 14px; }
      h1{ font-size:26px; }
      table{ min-width:760px; }
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
    </div>

    <h1>Reporte de Asistencia</h1>

    <!-- FILTROS -->
    <div class="glass-card mb-4 p-3">
      <form method="GET" action="AsistenciaController.php" class="filter-form">

        <select name="curso">
          <option value="">Todos los cursos</option>
          <?php foreach ($cursos as $c): ?>
            <option value="<?= $c['Id_Curso'] ?>" <?= ($cursoId == $c['Id_Curso']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['Nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <select name="fecha">
          <option value="">Todas las fechas</option>
          <?php foreach ($fechas as $f): ?>
            <option value="<?= $f['Fecha'] ?>" <?= ($fecha == $f['Fecha']) ? 'selected' : '' ?>>
              <?= $f['Fecha'] ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn-ghost">
          <i class="fa fa-filter" aria-hidden="true"></i> Filtrar
        </button>

        <a
          href="AsistenciaController.php?exportar=1<?= $fecha ? "&fecha=$fecha" : '' ?><?= $cursoId ? "&curso=$cursoId" : '' ?>"
          class="btn-ghost"
        >
          <i class="fa fa-file-csv" aria-hidden="true"></i> CSV
        </a>

      </form>
    </div>

    <!-- TABLA -->
    <div class="glass-card p-3 table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID Estudiante</th>
            <th>Nombre</th>
            <th>Curso</th>
            <th>Fecha</th>
            <th>Asistencia</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($reporte)): ?>
            <?php foreach ($reporte as $fila): ?>
              <tr>
                <td><?= $fila['Id_Estudiante'] ?></td>
                <td><?= htmlspecialchars($fila['Estudiante']) ?></td>
                <td><?= htmlspecialchars($fila['Curso']) ?></td>
                <td><?= htmlspecialchars($fila['Fecha']) ?></td>
                <td><?= $fila['Presente'] ? "Sí" : "No" ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No hay registros disponibles</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</body>
</html>
