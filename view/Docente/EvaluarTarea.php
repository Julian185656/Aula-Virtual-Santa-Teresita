<?php
session_start();


require_once __DIR__ . "/controller_guard_docente.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/model/db.php";

$idTarea = (int)($_GET['idTarea'] ?? 0);
$idCurso = (int)($_GET['idCurso'] ?? 0);
if ($idTarea <= 0 || $idCurso <= 0) { http_response_code(400); exit("Parámetros inválidos"); }


$sqlT = "SELECT t.Id_Tarea, t.Titulo, t.Fecha_Entrega, c.Id_Curso, c.Nombre AS Curso
         FROM tarea t
         JOIN curso c ON c.Id_Curso = t.Id_Curso
         WHERE t.Id_Tarea = ? AND c.Id_Curso = ?";
$stmt = $pdo->prepare($sqlT);
$stmt->execute([$idTarea, $idCurso]);
$tarea = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tarea) { http_response_code(404); exit("Tarea/curso no encontrados"); }


$sql = "SELECT
          u.Id_Usuario     AS Id_Estudiante,
          u.Nombre         AS Estudiante,
          e.Grado, e.Seccion,
          et.Id_Entrega,
          et.Archivo_URL,
          et.Fecha_Entrega,
          et.Calificacion,
          et.Comentario
        FROM matricula m
        JOIN usuario u         ON u.Id_Usuario = m.Id_Estudiante
        LEFT JOIN estudiante e ON e.Id_Estudiante = m.Id_Estudiante
        LEFT JOIN entrega_tarea et
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
    .badge-pend { background:#ffc107; }
    .badge-ok   { background:#198754; }
    .badge-no   { background:#6c757d; }
    .card-eval  { border-left:6px solid #0d6efd; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-3">Evaluar: <strong><?= htmlspecialchars($tarea['Titulo']) ?></strong></h3>
  <p class="text-muted">Curso: <?= htmlspecialchars($tarea['Curso']) ?> · Entrega límite: <?= htmlspecialchars($tarea['Fecha_Entrega']) ?></p>

  <div class="card card-eval mb-4">
    <div class="card-body p-3">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Estudiantes</h5>
        <a class="btn btn-outline-secondary" href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= $idCurso ?>">⬅️ Volver</a>
      </div>
    </div>
  </div>

  <?php foreach ($filas as $row):
    $entregada = !empty($row['Id_Entrega']);
    $evaluada  = $entregada && $row['Calificacion'] !== null;
  ?>
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h5 class="mb-1">
            <?= htmlspecialchars($row['Estudiante']) ?>
            <?php if ($entregada): ?>
              <span class="badge badge-ok ms-2">📤 Entregó</span>
            <?php else: ?>
              <span class="badge badge-no ms-2">⏳ Sin entrega</span>
            <?php endif; ?>
            <?php if ($evaluada): ?>
              <span class="badge bg-primary ms-2">✅ Evaluada</span>
            <?php endif; ?>
          </h5>
          <div class="text-muted small">
            <?= htmlspecialchars($row['Grado'] ?: '-') ?> <?= htmlspecialchars($row['Seccion'] ?: '') ?>
            <?php if ($row['Fecha_Entrega']): ?>
              · Recibida: <?= htmlspecialchars($row['Fecha_Entrega']) ?>
            <?php endif; ?>
          </div>

          <?php if ($entregada && $row['Archivo_URL']): ?>
            <div class="mt-2">
               <a href="<?= htmlspecialchars($row['Archivo_URL']) ?>" target="_blank" rel="noopener">Ver archivo</a>
            </div>
          <?php endif; ?>
        </div>

        <?php if ($entregada): ?>
          <form class="ms-3" method="post" action="/Aula-Virtual-Santa-Teresita/view/Docente/GuardarEvaluacion.php" style="min-width:320px;max-width:420px">
            <input type="hidden" name="Id_Tarea" value="<?= $idTarea ?>">
            <input type="hidden" name="Id_Curso" value="<?= $idCurso ?>">
            <input type="hidden" name="Id_Estudiante" value="<?= (int)$row['Id_Estudiante'] ?>">

            <div class="mb-2">
              <label class="form-label mb-1">Calificación ⭐ (0–100)</label>
              <input type="number" class="form-control" name="Calificacion" min="0" max="100"
                     value="<?= $row['Calificacion'] !== null ? (float)$row['Calificacion'] : '' ?>" required>
            </div>

            <div class="mb-2">
              <label class="form-label mb-1">Comentarios 📝</label>
              <textarea class="form-control" name="Comentario" rows="2" placeholder="Buen trabajo, recuerda..."><?= htmlspecialchars((string)$row['Comentario']) ?></textarea>
            </div>

            <div class="d-flex gap-2">
              <button class="btn btn-primary">💾 Guardar</button>
              <?php if ($evaluada): ?>
                <a class="btn btn-outline-secondary" href="#"
                   onclick="event.preventDefault();const f=this.closest('form');f.Calificacion.value='';f.Comentario.value='';">🧽 Limpiar</a>
              <?php endif; ?>
            </div>
          </form>
        <?php else: ?>
          <div class="ms-3 text-muted">
            No puedes evaluar porque no hay entrega.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div>
</body>
</html>
