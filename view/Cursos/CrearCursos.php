<?php
require_once __DIR__ . '/../../controller/CursoController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearCurso'])) {
    CursoController::crearCurso();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Curso</title>


<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>


body{
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 300;
    font-size: 15px;
    line-height: 1.7;
    color: #c4c3ca;
    padding: 40px 15px;
text-align: center;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;

    overflow-x: hidden;
}




.card-glass {
    max-width: 550px;
    margin: 0 auto;
    padding: 30px;
    background: rgba(255,255,255,0.05);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(12px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    text-align: center;
}

/* Inputs */
.card-glass input,
.card-glass textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.12);
    color: #fff;
}
.card-glass input::placeholder,
.card-glass textarea::placeholder {
    color: #ddd;
}


.card-glass button {
    width: 100%;
    padding: 12px;
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-weight: bold;
    transition: 0.2s ease;
}
.card-glass button:hover {
    background: rgba(255,255,255,0.35);
}


.volver-btn {
    display: inline-block;
    background: rgba(255,255,255,0.15);
    padding: 10px 20px;
    border-radius: 15px;
    color: #fff;
    text-decoration: none;
    margin-top: 30px;
    transition: 0.2s;
}
.volver-btn:hover {
    background: rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<h1 style="margin-bottom: 20px;">
    <i class="bi bi-journal-plus" style="font-size:40px;"></i><br>
    Crear Curso
</h1>

<div class="card-glass">

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre del curso" required>
        <textarea name="descripcion" rows="3" placeholder="Descripción del curso" required></textarea>

        <button type="submit" name="crearCurso">
            Crear Curso
        </button>
    </form>

</div>


<a href="../Home/Home.php" class="volver-btn">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

</body>
</html>
