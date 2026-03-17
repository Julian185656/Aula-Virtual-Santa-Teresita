<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrativo</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg:#2a2b38;
            --text:#fff;
            --muted:rgba(255,255,255,.75);
            --glass1:rgba(255,255,255,.10);
            --glass2:rgba(255,255,255,.06);
            --stroke:rgba(255,255,255,.20);
            --shadow:0 14px 44px rgba(0,0,0,.42);
            --radius:18px;
        }

        body {
            font-family:'Poppins',sans-serif;
            font-size:15px;
            color:var(--text);
            padding:40px 25px;
            background:var(--bg);
            background-image:url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat:repeat;
            background-size:600px;
        }

        h1 {
            text-align:center;
            margin-bottom:30px;
            font-weight:700;
            font-size:2.2rem;
            letter-spacing:1px;
            color:#fff;
        }

        /* BOTÓN VOLVER */
        .btn-volver {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 18px;
            background:linear-gradient(180deg, var(--glass1), var(--glass2));
            color:var(--text);
            border-radius:14px;
            border:1px solid var(--stroke);
            text-decoration:none;
            margin-bottom:30px;
        }

        /* 🔹 Cards */
        .cards-container {
            display:flex;
            gap:25px;
            flex-wrap:wrap;
            justify-content:center;
            margin-bottom:50px;
        }

        .card {
            flex:1 1 220px;
            background:var(--glass1);
            backdrop-filter: blur(15px);
            border-radius:var(--radius);
            padding:25px;
            text-align:center;
            box-shadow:var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow:0 15px 35px rgba(0,0,0,0.5);
        }

        .card h3 {
            margin-bottom:15px;
            font-size:18px;
            font-weight:600;
            color:var(--muted);
        }

        .card span {
            font-size:36px;
            display:block;
            font-weight:700;
            color:#fff;
        }

        .card.estudiantes{ background:#4bc0c0; }
        .card.docentes{ background:#ff6384; }
        .card.tareas{ background:#ffce56; color:#222; }
        .card.ausencias{ background:#36a2eb; }

        /* 🔹 Charts */
        .charts-container {
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            gap:30px;
        }

        .chart-container {
            flex:1 1 400px;
            max-width:500px;
            height:350px;
            background:var(--glass2);
            padding:25px;
            border-radius:var(--radius);
            box-shadow:var(--shadow);
        }

        .chart-container canvas {
            width:100% !important;
            height:100% !important;
        }

        @media(max-width:1000px){
            .chart-container{
                width:90%;
                margin:20px auto;
                display:block;
            }
        }

    </style>
</head>
<body>

<!-- BOTÓN VOLVER -->
<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
<i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

<h1>Dashboard Administrativo</h1>

<!-- 🔹 Cards -->
<div class="cards-container">
    <div class="card estudiantes">
        <h3>Estudiantes</h3>
        <span id="totalEstudiantes">0</span>
    </div>
    <div class="card docentes">
        <h3>Docentes</h3>
        <span id="totalDocentes">0</span>
    </div>
    <div class="card tareas">
        <h3>Tareas Pendientes</h3>
        <span id="tareasPendientes">0</span>
    </div>
    <div class="card ausencias">
        <h3>Ausencias sin justificar</h3>
        <span id="ausenciasSinJustificar">0</span>
    </div>
</div>

<!-- 🔹 Gráficas -->
<div class="charts-container">
    <div class="chart-container"><canvas id="rolesChart"></canvas></div>
    <div class="chart-container"><canvas id="estadoChart"></canvas></div>
    <div class="chart-container"><canvas id="asistenciaChart"></canvas></div>
    <div class="chart-container"><canvas id="entregasChart"></canvas></div>
    <div class="chart-container"><canvas id="justificacionesChart"></canvas></div>
    <div class="chart-container" style="width:90%;"><canvas id="scatterChart"></canvas></div>
</div>

<script>
window.addEventListener('DOMContentLoaded', ()=>{
    const chartOptions = { maintainAspectRatio:false };

    // 1️⃣ Usuarios
    fetch('/Aula-Virtual-Santa-Teresita/view/Admin/GraficasUsuarios.php')
    .then(r=>r.json()).then(d=>{
        new Chart(document.getElementById('rolesChart').getContext('2d'),{
            type:'pie', 
            data:{labels:d.rolesLabels,datasets:[{data:d.rolesData, backgroundColor:['#4bc0c0','#ff6384','#ffce56']}]},
            options:{...chartOptions, plugins:{title:{display:true,text:'Usuarios por Rol',color:'#fff'},legend:{position:'bottom',labels:{color:'#fff'}}}}
        });
        new Chart(document.getElementById('estadoChart').getContext('2d'),{
            type:'bar', 
            data:{labels:d.estadoLabels,datasets:[{data:d.estadoData,label:'Usuarios por Estado', backgroundColor:'#36a2eb'}]},
            options:{...chartOptions, plugins:{title:{display:true,text:'Usuarios por Estado',color:'#fff'},legend:{display:false}},scales:{x:{ticks:{color:'#fff'}},y:{beginAtZero:true,stepSize:1,ticks:{color:'#fff'}}}}
        });
    });

    // 2️⃣ Asistencia
    fetch('/Aula-Virtual-Santa-Teresita/view/Admin/GraficasAsistencia.php')
    .then(r=>r.json()).then(d=>{
        new Chart(document.getElementById('asistenciaChart').getContext('2d'),{
            type:'bar', 
            data:{labels:d.labels,datasets:[{data:d.data,label:'Asistencia %', backgroundColor:'#ff9f40'}]},
            options:{...chartOptions, plugins:{title:{display:true,text:'Asistencia por Curso',color:'#fff'}},scales:{x:{ticks:{color:'#fff'}},y:{beginAtZero:true,max:100,ticks:{color:'#fff'}}}}
        });
    });

    // 3️⃣ Entregas
    fetch('/Aula-Virtual-Santa-Teresita/view/Admin/GraficasEntregas.php')
    .then(r=>r.json()).then(d=>{
        new Chart(document.getElementById('entregasChart').getContext('2d'),{
            type:'bar', 
            data:{labels:d.labels,datasets:[{data:d.data,label:'Promedio Calificación', backgroundColor:'#9966ff'}]},
            options:{...chartOptions, plugins:{title:{display:true,text:'Promedio de Calificaciones por Curso',color:'#fff'}},scales:{x:{ticks:{color:'#fff'}},y:{beginAtZero:true,max:100,ticks:{color:'#fff'}}}}
        });
    });

    // 4️⃣ Justificaciones
    fetch('/Aula-Virtual-Santa-Teresita/view/Admin/GraficasJustificaciones.php')
    .then(r=>r.json()).then(d=>{
        new Chart(document.getElementById('justificacionesChart').getContext('2d'),{
            type:'pie', 
            data:{labels:d.labels,datasets:[{data:d.data, backgroundColor:['#ff6384','#36a2eb','#ffce56']}]},
            options:{...chartOptions, plugins:{title:{display:true,text:'Justificaciones por Estado',color:'#fff'},legend:{position:'bottom',labels:{color:'#fff'}}}}
        });
    });

    // 5️⃣ Extras: Totales y Scatter
    fetch('/Aula-Virtual-Santa-Teresita/view/Admin/DashboardExtras.php')
    .then(r=>r.json()).then(d=>{
        document.getElementById('totalEstudiantes').textContent = d.totalEstudiantes;
        document.getElementById('totalDocentes').textContent = d.totalDocentes;
        document.getElementById('tareasPendientes').textContent = d.tareasPendientes;
        document.getElementById('ausenciasSinJustificar').textContent = d.ausenciasSinJustificar;

        new Chart(document.getElementById('scatterChart').getContext('2d'),{
            type:'scatter',
            data:{datasets:[{label:'Asistencia vs Calificación', data:d.scatterData, backgroundColor:'#9966ff'}]},
            options:{
                ...chartOptions,
                plugins:{title:{display:true,text:'Asistencia vs Calificación',color:'#fff'}},
                scales:{
                    x:{title:{display:true,text:'% Asistencia',color:'#fff'}, min:0, max:100, ticks:{color:'#fff'}},
                    y:{title:{display:true,text:'Promedio Calificación',color:'#fff'}, min:0, max:100, ticks:{color:'#fff'}}
                }
            }
        });
    });
});
</script>

</body>
</html>