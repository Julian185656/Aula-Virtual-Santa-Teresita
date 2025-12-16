<?php
session_start();

/* Seguridad: solo ADMINISTRADOR puede acceder */
if (
    !isset($_SESSION['id_usuario']) || 
    strtolower($_SESSION['rol']) !== 'administrador'
) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rendimiento Académico </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            color: #ffffff;
            padding: 30px;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }
        .contenedor {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }
        iframe {
            width: 100%;
            height: 650px;
            border: none;
        }
        .btn-volver {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #1f202a;
            color: #fff;
            border-radius: 12px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-volver:hover {
            background: #000;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
    ⬅ Volver
</a>

<div class="contenedor">
    <h2>📊 Rendimiento Académico </h2>

    <!-- POWER BI IFRAME -->
    <iframe
        title="RendimientoGeneral"
        src="https://app.powerbi.com/reportEmbed?reportId=363866da-95b1-439b-be91-7b372baa6f8a&autoAuth=true&ctid=dde2fb8f-d8e0-445e-b851-e69c198c1e59"
        allowfullscreen="true">
    </iframe>
</div>

</body>
</html>
<iframe title="Rendimiento General" width="1140" height="541.25" src="https://app.powerbi.com/reportEmbed?reportId=363866da-95b1-439b-be91-7b372baa6f8a&autoAuth=true&ctid=dde2fb8f-d8e0-445e-b851-e69c198c1e59" frameborder="0" allowFullScreen="true"></iframe>