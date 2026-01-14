<?php
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Curso</title>

<!-- Iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Fuente -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">

<style>
/* =====================================================
   ESTILO GLOBAL
===================================================== */

body {
    font-family: 'Poppins', sans-serif;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-size: 600px;
    color: #fff;
    padding: 40px 20px;
}

/* Título */
.page-title {
    text-align: center;
    color: #fff;
    font-size: 2.3rem;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 2px 12px rgba(0,0,0,0.35);
}

.page-title i {
    display: block;
    font-size: 45px;
    margin-bottom: 8px;
    opacity: 0.9;
}

/* =====================================================
   CARD (MISMO ESTILO QUE LOS DEMÁS MÓDULOS)
===================================================== */

.card-wrapper {
    max-width: 650px;
    margin: 0 auto;
    background: rgba(255,255,255,0.08);
    border-radius: 20px;
    padding: 35px;
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 35px rgba(0,0,0,0.4);
}

.card-wrapper label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #fff;
}

.card-wrapper input,
.card-wrapper textarea {
    width: 100%;
    padding: 12px 15px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.25);
    background: rgba(255,255,255,0.10);
    color: #fff;
    margin-bottom: 18px;
}

.card-wrapper textarea {
    resize: vertical;
}

/* Inputs más claros */
.form-input,
textarea,
input[type="text"],
input[type="email"],
input[type="password"] {
    color: #ffffff !important;  /* Texto mucho más claro */
    font-weight: 500;
}

/* Placeholder más visible */
.form-input::placeholder,
textarea::placeholder,
input::placeholder {
    color: rgba(255, 255, 255, 0.65) !important;
    font-weight: 400;
}

/* Bordes más definidos para ver mejor el campo */
.form-input,
textarea,
input[type="text"],
input[type="email"],
input[type="password"] {
    border: 1px solid rgba(255,255,255,0.25) !important;
}

/* Hover para claridad */
.form-input:hover,
textarea:hover,
input[type="text"]:hover {
    border-color: rgba(255,255,255,0.45) !important;
}

/* Botón principal */
.btn-submit {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    background: rgba(255,255,255,0.20);
    border: 1px solid rgba(255,255,255,0.32);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

.btn-submit:hover {
    background: rgba(255,255,255,0.32);
}

/* Botón volver */
.volver-btn {
    display: block;
    text-align: center;
    width: fit-content;
    margin: 30px auto 0;
    padding: 10px 22px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    text-decoration: none;
    transition: 0.25s;
}

.volver-btn:hover {
    background: rgba(255,255,255,0.32);
}

/* Aumentar tamaño general del texto de inputs y textarea */
.form-input,
textarea,
input[type="text"],
input[type="email"],
input[type="password"] {
    font-size: 18px !important;   
    color: #ffffff !important;    
    font-weight: 500;
    padding: 14px 18px !important; 
}

/* Placeholder más grande y visible */
.form-input::placeholder,
textarea::placeholder,
input::placeholder {
    font-size: 17px !important;
    color: rgba(255, 255, 255, 0.70) !important;
}

/* Etiquetas arriba de los campos */
label {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    margin-bottom: 6px;
}

/* Botón más grande */
button[type="submit"],
.btn-submit {
    font-size: 18px !important;
    padding: 14px !important;
}
/* TAMAÑO Y ESTILO DEL TEXTO */
input,
textarea,
select {
    font-size: 17px !important;
    color: #ffffff !important;
    font-weight: 500;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 12px !important;
    padding: 12px 16px !important;
    width: 100%;
    box-sizing: border-box;
}

/* PLACEHOLDER CLARO Y GRANDE */
input::placeholder,
textarea::placeholder {
    font-size: 16px !important;
    color: rgba(255,255,255,0.65) !important;
}

/* CONTROLAR LA ALTURA DEL TEXTAREA PARA QUE NO SE ROMPA */
textarea {
    min-height: 120px !important;
    max-height: 200px;
    resize: vertical;
}

/* LABELS MÁS GRANDES Y LEGIBLES */
label {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    margin-bottom: 6px;
    display: block;
}

/* BOTÓN */
button[type="submit"],
.btn-submit {
    font-size: 18px !important;
    padding: 14px !important;
    border-radius: 12px !important;
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.3);
}

button:hover {
    background: rgba(255,255,255,0.32);
}

/* CONTENEDOR PRINCIPAL */
.form-card {
    padding: 40px !important;
    border-radius: 18px !important;
}

</style>
</head>

<body>

<!-- ================================
     TÍTULO 
================================ -->
<h2 class="page-title">
    <i class="fa fa-plus-circle"></i>
    Crear Curso
</h2>

<!-- ================================
     CARD FORMULARIO
================================ -->
<div class="card-wrapper">
    <form method="POST">

        <label>Nombre del curso</label>
        <input type="text" name="nombre" placeholder="Ingrese el nombre del curso" required>

        <label>Descripción del curso</label>
        <textarea name="descripcion" rows="4" placeholder="Detalle o información del curso" required></textarea>

        <button type="submit" name="crearCurso" class="btn-submit">
            Crear Curso
        </button>
    </form>
</div>

<!-- ================================
     BOTÓN VOLVER
================================ -->
<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="volver-btn">
    <i class="fa fa-arrow-left"></i> Volver
</a>

</body>
</html>
