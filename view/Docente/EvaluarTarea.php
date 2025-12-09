<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
require_once __DIR__ . "/controller_guard_docente.php";

$pdo = (new CN_BD())->conectar(); 
$modelTarea = new TareaModel($pdo);

$idTarea = (int)($_GET['idTarea'] ?? 0);
$idCurso = (int)($_GET['idCurso'] ?? 0);
if ($idTarea <= 0 || $idCurso <= 0) { http_response_code(400); exit("Parámetros inválidos"); }

$sqlT = "SELECT t.Id_Tarea, t.Titulo, t.Fecha_Entrega, c.Id_Curso, c.Nombre AS Curso
        FROM aulavirtual.tarea t
        JOIN aulavirtual.curso c ON c.Id_Curso = t.Id_Curso
        WHERE t.Id_Tarea = ? AND c.Id_Curso = ?";
$stmt = $pdo->prepare($sqlT);
$stmt->execute([$idTarea, $idCurso]);
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tarea) { http_response_code(404); exit("Tarea/curso no encontrados"); }

$sql = "SELECT
          u.Id_Usuario AS Id_Estudiante,
          u.Nombre AS Estudiante,
          e.Grado, e.Seccion,
          et.Id_Entrega,
          et.Archivo_URL,
          et.Fecha_Entrega,
          et.Calificacion,
          et.Comentario,
          et.Puntos_Ranking
        FROM aulavirtual.matricula m
        JOIN aulavirtual.usuario u ON u.Id_Usuario = m.Id_Estudiante
        LEFT JOIN aulavirtual.estudiante e ON e.Id_Estudiante = m.Id_Estudiante
        LEFT JOIN aulavirtual.entrega_tarea et
          ON et.Id_Estudiante = m.Id_Estudiante AND et.Id_Tarea = ?
        WHERE m.Id_Curso = ?
        ORDER BY u.Nombre ASC";
$st = $pdo->prepare($sql);
$st->execute([$idTarea, $idCurso]);
$filas = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Evaluar Tarea</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

h3.title-main {
    text-align: center;
    margin-bottom: 15px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

p {
    text-align: center;
    margin-bottom: 30px;
    color: #fff;
}

.card-eval, .card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    margin-bottom: 20px;
    color: #fff;
}

.card-body {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
}

.left-side {
    flex: 1 1 auto;
    text-align: left;
}

.right-side {
    flex: 0 0 360px;
}

.btn-outline-secondary.back-btn {
    border-radius: 15px;
    padding: 8px 18px;
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
    background: rgba(255, 255, 255, 0.1);
    transition: 0.2s;
    text-decoration: none;
}
.btn-outline-secondary.back-btn:hover {
    background: rgba(255,255,255,0.35);
}

.badge {
    border-radius: 12px;
    padding: 0.25em 0.6em;
    font-size: 0.8em;
}
.badge-ok { background: #22c55e; color: #fff; }
.badge-no { background: #6b7280; color: #fff; }
.badge-pend { background: #fbbf24; color: #fff; }
.badge.bg-primary { background: #4f46e5; }

input, textarea {
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    padding: 8px 12px;
}
input::placeholder, textarea::placeholder {
    color: #ddd;
}
button.btn-primary {
    border-radius: 15px;
    background: #4f46e5;
    border: none;
    padding: 8px 18px;
    transition: 0.2s;
}
button.btn-primary:hover {
    background: #4338ca;
}
button.btn-outline-secondary {
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 18px;
    color: #fff;
    background: rgba(255,255,255,0.1);
}
button.btn-outline-secondary:hover {
    background: rgba(255,255,255,0.35);
}

form {
    margin-top: 10px;
    background: rgba(0,0,0,0.3);
    padding: 15px;
    border-radius: 15px;
    backdrop-filter: blur(8px);
}

.d-flex.gap-2 {
    gap: 10px;
}

@media (max-width: 768px) {
    .card-body {
        flex-direction: column;
        gap: 15px;
    }
    .right-side {
        flex: 1 1 100%;
    }
    form {
        width: 100% !important;
        max-width: none !important;
    }
}
  </style>
</head>

<body>

<div class="container">

  <h3 class="mb-3 title-main">
    Evaluar: <strong><?= htmlspecialchars($tarea['Titulo']) ?></strong>
  </h3>

  <p style="color: #ffffff; font-weight: 500;">
    Curso: <?= htmlspecialchars($tarea['Curso']) ?> · Entrega límite:
    <strong><?= htmlspecialchars($tarea['Fecha_Entrega']) ?></strong>
  </p>

  <div class="card card-eval mb-4">
    <div class="card-body p-3 d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Estudiantes</h5>
      <a class="btn btn-outline-secondary back-btn" href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= $idCurso ?>">Volver</a>
    </div>
  </div>

  <?php foreach ($filas as $row):
    $entregada = !empty($row['Id_Entrega']);
    $evaluada  = $entregada && $row['Calificacion'] !== null;
  ?>
  <div class="card shadow-sm mb-3">
    <div class="card-body">

      <div class="left-side">
          <h5 class="mb-1">
            <?= htmlspecialchars($row['Estudiante']) ?>
            <?php if ($entregada): ?>
              <span class="badge badge-ok ms-2"> Entregó</span>
            <?php else: ?>
              <span class="badge badge-no ms-2"> Sin entrega</span>
            <?php endif; ?>
            <?php if ($evaluada): ?>
              <span class="badge bg-primary ms-2"> Evaluada</span>
            <?php endif; ?>
          </h5>

          <div class="text-muted small">
            <?= htmlspecialchars($row['Grado'] ?: '-') ?>
            <?= htmlspecialchars($row['Seccion'] ?: '') ?>
            <?php if ($row['Fecha_Entrega']): ?>
              <span class="text-white">· Recibida: <?= htmlspecialchars($row['Fecha_Entrega']) ?></span>
            <?php endif; ?>
          </div>

          <?php if ($entregada && $row['Archivo_URL']): ?>
            <div class="mt-2">
              <a href="<?= htmlspecialchars($row['Archivo_URL']) ?>" target="_blank">Ver archivo</a>
            </div>
          <?php endif; ?>
      </div>

      <?php if ($entregada): ?>
      <div class="right-side">
        <form method="post" action="/Aula-Virtual-Santa-Teresita/view/Docente/GuardarEvaluacion.php">
          <input type="hidden" name="Id_Tarea" value="<?= $idTarea ?>">
          <input type="hidden" name="Id_Curso" value="<?= $idCurso ?>">
          <input type="hidden" name="Id_Estudiante" value="<?= (int)$row['Id_Estudiante'] ?>">

          <div class="mb-2">
            <label class="form-label mb-1">Calificación (0–100)</label>
            <input type="number" class="form-control" name="Calificacion" min="0" max="100"
                   value="<?= $row['Calificacion'] !== null ? (float)$row['Calificacion'] : '' ?>" required>
          </div>

          <div class="mb-2">
            <label class="form-label mb-1">Puntos Ranking (1–10)</label>
            <input type="number" class="form-control" name="Puntos_Ranking" min="1" max="10"
                   value="<?= $row['Puntos_Ranking'] !== null ? (int)$row['Puntos_Ranking'] : '' ?>" required>
            <small class="text-white">
              Estos puntos se suman al ranking que verá el estudiante.
            </small>
          </div>

          <div class="mb-2">
            <label class="form-label mb-1">Comentarios</label>
            <textarea class="form-control" name="Comentario" rows="2"><?= htmlspecialchars((string)$row['Comentario']) ?></textarea>
          </div>

          <div class="d-flex gap-2 justify-content-end">
            <button class="btn btn-primary"> Guardar</button>
            <?php if ($evaluada): ?>
              <a class="btn btn-outline-secondary"
                 onclick="event.preventDefault(); const f=this.closest('form'); 
                          f.Calificacion.value=''; 
                          f.Puntos_Ranking.value=''; 
                          f.Comentario.value='';">
                 Borrar
              </a>
            <?php endif; ?>
          </div>

        </form>
      </div>
      <?php else: ?>
        <div class="text-white mt-2">No puedes evaluar porque no hay entrega.</div>
      <?php endif; ?>

    </div>
  </div>
  <?php endforeach; ?>

</div>

</body>
</html>
