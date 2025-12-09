<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ranking del Curso</title>
<link href="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
body{
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #c4c3ca;
    padding: 40px 15px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;       
    background-size: 600px;         
    background-position: center top;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0,0,0,0.5);
}

.card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    margin-bottom: 30px;
}

.card-header {
    font-weight: 600;
    color: #ffffff;
    background: rgba(255,255,255,0.1);
    border-bottom: none;
    border-radius: 20px 20px 0 0;
}

.table-container {
    overflow-x: auto;
    border-radius: 20px;
}

table, table thead th, table tbody td {
    color: #fff;
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
table thead {
    background: rgba(255, 255, 255, 0.1);
    font-weight: bold;
}
table th, table td {
    padding: 12px 15px;
    vertical-align: middle;
    text-align: center;
}
table tr:nth-child(even) {
    background: rgba(255,255,255,0.02);
}
table tr:hover {
    background: rgba(255,255,255,0.1);
}

.badge-points {
    background:#22c55e;
    color:#fff;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 12px;
}

.btn-back {
    background: #ffffffff;
    color: #000000ff;
    border-radius: 8px;
    padding: 6px 16px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s, transform 0.2s;
    text-decoration: none;
}
.btn-back:hover {
    background: #574fd6;
    transform: translateY(-2px);
    color: #fff;
    text-decoration: none;
}

.alert-info {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border: none;
    text-align: center;
}

@media(max-width:768px){
    table th, table td { font-size: 0.85rem; }
    h1 { font-size: 1.5rem; }
}
</style>
</head>
<body>

<div class="container">

    <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php" class="btn-back mb-3">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>

    <h1><i class="fa-solid fa-trophy me-2"></i>Ranking del Curso</h1>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-people-fill"></i> Estudiantes y Puntos
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <?php if (!empty($ranking)): ?>
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:80px;">#</th>
                            <th>Estudiante</th>
                         
                            <th style="width:160px;">Puntos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $pos = 1; ?>
                        <?php foreach ($ranking as $row): ?>
                        <tr>
                            <td><strong><?= $pos++ ?></strong></td>
                            <td><?= htmlspecialchars($row['nombre'] ?? 'Sin nombre') ?></td>
                 
                            <td><span class="badge-points"><?= (int)($row['puntos_total'] ?? 0) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <small class="text-muted d-block text-center mt-3">
                    Los puntos se calculan sumando las evaluaciones de ranking (1 a 10) asignadas por el docente en cada tarea.
                </small>
                <?php else: ?>
                <div class="alert alert-info mb-0">
                    Aún no hay puntos registrados para este curso.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<script src="/Aula-Virtual-Santa-Teresita/view/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
