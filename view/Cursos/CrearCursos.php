<?php
// /view/Cursos/CrearCursos.php
require_once __DIR__ . '/../../controller/CursoController.php';
require_once __DIR__ . '/../../model/CursoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crearCurso'])) {
        CursoController::crearCurso();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Crear Curso</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">

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
      background-size:600px;
      background-repeat:repeat;
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

    .form-wrap{
      max-width:680px;
      margin:0 auto;
    }

    label{
      display:block;
      margin:0 0 8px;
      font-weight:600;
      color:var(--text);
    }

    input[type="text"], textarea{
      width:100%;
      padding:12px 14px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,0.08);
      color:var(--text);
      outline:none;
      margin-bottom:16px;
    }
    input[type="text"]::placeholder,
    textarea::placeholder{
      color:rgba(255,255,255,.55);
    }
    input[type="text"]:focus,
    textarea:focus{
      border-color:var(--stroke2);
    }

    textarea{
      resize:vertical;
      min-height:120px;
    }

    .btn-submit{
      width:100%;
      height:44px;
      border-radius:14px;
      border:1px solid var(--stroke);
      background:rgba(255,255,255,0.14);
      color:var(--text);
      font-weight:700;
      cursor:pointer;
      transition:.18s;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:10px;
    }
    .btn-submit:hover{
      border-color:var(--stroke2);
      background:rgba(255,255,255,0.22);
    }

    @media (max-width:520px){
      body{ padding:28px 14px; }
      .title{ font-size:26px; }
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
      <i class="fa-solid fa-circle-plus" aria-hidden="true"></i>
      Crear Curso
    </h1>

    <div class="form-wrap">
      <div class="glass-card">
        <form method="POST">
          <label for="nombre">Nombre del curso</label>
          <input id="nombre" type="text" name="nombre" placeholder="Ingrese el nombre del curso" required>

          <label for="descripcion">Descripción del curso</label>
          <textarea id="descripcion" name="descripcion" rows="4" placeholder="Detalle o información del curso" required></textarea>

          <button type="submit" name="crearCurso" class="btn-submit">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            Crear Curso
          </button>
        </form>
      </div>
    </div>

  </div>
</body>
</html>
