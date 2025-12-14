<?php
session_start();

/* Seguridad: solo docentes pueden acceder */
if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'docente') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

/* Capturamos el curso (para uso futuro, no rompe nada ahora) */
$idCurso = isset($_GET['idCurso']) ? intval($_GET['idCurso']) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi rendimiento académico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap (opcional, por coherencia visual) -->
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

<a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php" class="btn-volver">
    ⬅ Volver a Mis Cursos
</a>

<div class="contenedor">
    <h2>📊 Mi rendimiento académico</h2>

    <!-- IFRAME POWER BI -->
    <iframe
        title="RendimientoDocente"
        src="https://app.powerbi.com/reportEmbed?reportId=0437c0b8-2ee6-4ca0-8ddd-d8dbe604c62c&autoAuth=true&ctid=dde2fb8f-d8e0-445e-b851-e69c198c1e59"
        allowFullScreen="true">
    </iframe>
</div>

</body>
</html>
