<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";


if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}


$stmt = $pdo->query("SELECT u.Id_Usuario, u.Nombre 
                     FROM usuario u 
                     WHERE u.Rol = 'Docente' AND u.Estado = 'Activo'");
$docentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo Curso | Santa Teresita</title>
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
      <h4 class="mb-0"><i class="fa-solid fa-plus"></i> Crear Nuevo Curso</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/CursoController.php">
        <input type="hidden" name="accion" value="crear_curso">

        <div class="mb-3">
          <label class="form-label fw-bold">📘 Nombre del curso</label>
          <input type="text" class="form-control" name="nombre" placeholder="Ej: Matemáticas 3er Grado" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">📝 Descripción</label>
          <textarea name="descripcion" class="form-control" rows="3" placeholder="Breve descripción del curso" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">👨‍🏫 Docente responsable</label>
          <select name="id_docente" class="form-select" required>
            <option value="">Seleccionar docente...</option>
            <?php foreach ($docentes as $d): ?>
              <option value="<?= $d['Id_Usuario'] ?>"><?= htmlspecialchars($d['Nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary fw-bold">💾 Crear curso</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="text-center text-light py-3 mt-5" style="background-color:#1c223a;">
  <small>© 2025 Aula Virtual Santa Teresita</small>
</footer>

</body>
</html>
