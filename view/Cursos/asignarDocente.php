<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";


if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}


$stmtCursos = $pdo->query("SELECT c.Id_Curso, c.Nombre, u.Nombre AS Docente
                           FROM curso c
                           LEFT JOIN usuario u ON c.Id_Docente = u.Id_Usuario
                           ORDER BY c.Nombre ASC");
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);


$stmtDocentes = $pdo->query("SELECT Id_Usuario, Nombre 
                             FROM usuario 
                             WHERE Rol = 'Docente' AND Estado = 'Activo'
                             ORDER BY Nombre ASC");
$docentes = $stmtDocentes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignar Docentes | Santa Teresita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color:#f5f6fa;">
<nav class="navbar navbar-dark shadow" style="background-color:#0b1a35;">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-uppercase" href="/Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php">
      ⬅️ Volver al Panel de Cursos
    </a>
  </div>
</nav>

<div class="container my-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fa-solid fa-users"></i> Asignar o cambiar docente</h4>
    </div>
    <div class="card-body">

      <?php if (empty($cursos)): ?>
        <div class="alert alert-warning text-center">No hay cursos registrados.</div>
      <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Curso</th>
              <th>Docente actual</th>
              <th>Asignar nuevo docente</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cursos as $i => $curso): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><strong><?= htmlspecialchars($curso['Nombre']) ?></strong></td>
              <td><?= htmlspecialchars($curso['Docente'] ?? 'Sin asignar') ?></td>
              <td>
                <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php" class="d-flex gap-2 justify-content-center">
                  <input type="hidden" name="accion" value="editar_curso">
                  <input type="hidden" name="id_curso" value="<?= $curso['Id_Curso'] ?>">
                  <input type="hidden" name="nombre" value="<?= htmlspecialchars($curso['Nombre']) ?>">
                  <input type="hidden" name="descripcion" value="<?= htmlspecialchars($curso['Descripcion'] ?? '') ?>">
                  <select name="id_docente" class="form-select form-select-sm" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($docentes as $d): ?>
                      <option value="<?= $d['Id_Usuario'] ?>"><?= htmlspecialchars($d['Nombre']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button class="btn btn-success btn-sm">💾 Guardar</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<footer class="text-center text-light py-3 mt-5" style="background-color:#1c223a;">
  <small>© 2025 Aula Virtual Santa Teresita</small>
</footer>

</body>
</html>
