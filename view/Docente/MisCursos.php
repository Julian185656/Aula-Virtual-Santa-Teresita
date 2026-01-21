<?php 
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$docenteId = $_SESSION['id_usuario'];
$misCursos = CursoModel::obtenerCursosDocente($docenteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mis Cursos</title>

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

.page-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}

/* Botón volver (mismo estilo glass) */
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
  margin:10px 0 22px;
  text-shadow:0 2px 10px rgba(0,0,0,.35);
}

/* CONTENEDOR */
.cards-container{
  display:flex;
  flex-direction:column;
  gap:18px;
  align-items:center;
}

/* ✅ CARD UNIFORME */
.course-card{
  width:100%;
  max-width:1100px;              /* <- uniforme */
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  border:1px solid var(--stroke);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  padding:22px;

  display:flex;                  /* <- evita “vacíos” */
  flex-direction:column;
  justify-content:space-between;
  gap:16px;
}

/* Header del curso */
.course-head{
  display:flex;
  flex-direction:column;
  align-items:flex-start;
  gap:6px;
  text-align:left;
}

.course-title{
  font-size:18px;
  font-weight:800;
  margin:0;
}

.course-desc{
  font-size:13px;
  opacity:.80;
  margin:0;
}

/* ✅ Botones siempre alineados */
.course-actions{
  display:grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap:12px;
}

.action-btn{
  height:44px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;

  border-radius:14px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,0.12);
  color:var(--text);
  text-decoration:none;
  transition:.18s;
  font-size:14px;
  font-weight:800;
  white-space:nowrap;
}
.action-btn:hover{
  border-color:var(--stroke2);
  background:rgba(255,255,255,0.22);
  color:var(--text);
}

.no-cursos{
  text-align:center;
  opacity:0.85;
  padding:18px;
  width:100%;
  max-width:1100px;
  border-radius:var(--radius);
  border:1px solid var(--stroke);
  background:linear-gradient(180deg, var(--glass1), var(--glass2));
  box-shadow:var(--shadow);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

/* Responsive */
@media (max-width: 920px){
  .course-actions{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width:520px){
  body{ padding:28px 14px; }
  .course-actions{ grid-template-columns: 1fr; }
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

    <h1><i class="fa-solid fa-display"></i> Mis Cursos</h1>

    <div class="cards-container">

      <?php if (!empty($misCursos)): ?>
        <?php foreach ($misCursos as $curso): ?>
          <div class="course-card">

            <div class="course-head">
              <p class="course-title">
                <?= htmlspecialchars($curso['nombre'] ?? 'Sin nombre') ?>
              </p>
              <?php if (!empty($curso['descripcion'])): ?>
                <p class="course-desc"><?= htmlspecialchars($curso['descripcion']) ?></p>
              <?php else: ?>
                <p class="course-desc">—</p>
              <?php endif; ?>
            </div>

            <div class="course-actions">
              <a class="action-btn"
                 href="/Aula-Virtual-Santa-Teresita/view/Docente/AsignarTarea.php?id=<?= htmlspecialchars($curso['id']) ?>">
                <i class="fa fa-plus"></i> Tarea
              </a>

              <a class="action-btn"
                 href="/Aula-Virtual-Santa-Teresita/view/Docente/VerTareas.php?id=<?= htmlspecialchars($curso['id']) ?>">
                <i class="fa fa-list"></i> Tareas
              </a>

              <a class="action-btn"
                 href="/Aula-Virtual-Santa-Teresita/view/Docente/ForoCurso.php?idCurso=<?= urlencode($curso['id']) ?>">
                <i class="fa fa-comments"></i> Foro
              </a>

              <a class="action-btn"
                 href="/Aula-Virtual-Santa-Teresita/view/Docente/EncuestasCurso.php?idCurso=<?= htmlspecialchars($curso['id']) ?>">
                <i class="fa fa-poll"></i> Encuestas
              </a>
            </div>

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-cursos">
          <strong>No tienes cursos asignados.</strong>
        </div>
      <?php endif; ?>

    </div>
  </div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/jquery/jquery.min.js"></script>
<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
