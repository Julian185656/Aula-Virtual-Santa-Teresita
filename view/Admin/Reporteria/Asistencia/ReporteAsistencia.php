<?php
/** * ReporteAsistencia.php 
 * Versión final con Feedback de envío de correo y persistencia de filtros.
 */

// 1. INICIALIZACIÓN DE SEGURIDAD
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if (!isset($totalPages)) { $totalPages = 1; }
if (!isset($cursoId)) { $cursoId = ''; }
if (!isset($fecha)) { $fecha = ''; }

// Obtener los datos de ausentismo crítico para la tabla superior
$criticos = $this->model->obtenerAusentismoCritico(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Reporte de Asistencia - Escuela Santa Teresita</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        :root {
            --bg: #2a2b38;
            --text: #fff;
            --glass1: rgba(255, 255, 255, .10);
            --glass2: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .20);
            --stroke2: rgba(255, 255, 255, .30);
            --shadow: 0 14px 44px rgba(0, 0, 0, .42);
            --radius: 18px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: var(--text);
            padding: 40px 25px;
            background: var(--bg);
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
        }

        .page-wrap { max-width: 1200px; margin: 0 auto; }

        h1 { text-align: center; font-weight: 700; font-size: 32px; margin: 10px 0 22px; text-shadow: 0 2px 10px rgba(0,0,0,.35); }

        .btn-volver {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px;
            background: linear-gradient(180deg, var(--glass1), var(--glass2));
            color: var(--text) !important; border-radius: 14px; border: 1px solid var(--stroke);
            text-decoration: none !important; transition: .18s; backdrop-filter: blur(12px);
        }
        .btn-volver:hover { transform: translateY(-1px); border-color: var(--stroke2); background: rgba(255,255,255,.14); }

        .glass-card {
            background: linear-gradient(180deg, var(--glass1), var(--glass2));
            border: 1px solid var(--stroke); border-radius: var(--radius);
            box-shadow: var(--shadow); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        }

        .filter-form { display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap; }
        .filter-form select, .btn-ghost { height: 42px; border-radius: 12px; transition: .18s; }
        .filter-form select {
            min-width: 200px; padding: 0 12px; background: rgba(255,255,255,0.10);
            border: 1px solid var(--stroke); color: #fff; outline: none;
        }
        .filter-form select option { background: #2a2b38; color: #fff; }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 8px; padding: 0 16px;
            background: rgba(255,255,255,0.10); border: 1px solid var(--stroke);
            color: #fff; font-weight: 600; cursor: pointer; text-decoration: none !important;
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.20); color: #fff; }

        .table-wrap { overflow: auto; border-radius: var(--radius); }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 14px; text-align: center; vertical-align: middle; color: #fff !important; }
        thead tr { background: rgba(255,255,255,0.10); }
        tbody tr:nth-child(even) { background: rgba(255,255,255,0.05); }
        
        .pagination-wrap { display: flex; justify-content: center; margin-top: 25px; }
        .pagination { display: inline-flex; gap: 8px; padding: 10px; border-radius: 100px; background: rgba(255,255,255,0.05); border: 1px solid var(--stroke); }
        .page-link { 
            border-radius: 50px !important; background: rgba(255,255,255,0.1) !important; 
            color: #fff !important; border: 1px solid var(--stroke) !important; padding: 6px 18px !important;
        }
        .page-item.active .page-link { background: rgba(255,255,255,0.3) !important; border-color: #fff !important; }

        /* Estilo para las alertas de éxito/error */
        .custom-alert {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            min-width: 300px; border-radius: 12px; backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

<div class="page-wrap">
    
    <?php if (isset($_GET['alerta_enviada'])): ?>
        <div class="alert alert-dismissible fade show glass-card mb-4 <?= $_GET['alerta_enviada'] == '1' ? 'border-success' : 'border-danger' ?>" role="alert" style="color: #fff;">
            <?php if ($_GET['alerta_enviada'] == '1'): ?>
                <i class="fa-solid fa-circle-check text-success mr-2"></i> 
                <strong>¡Correo enviado!</strong> La notificación de ausentismo se envió correctamente al estudiante.
            <?php else: ?>
                <i class="fa-solid fa-circle-xmark text-danger mr-2"></i> 
                <strong>Error al enviar.</strong> No se pudo conectar con el servidor de correo. Revise la configuración.
            <?php endif; ?>
            <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="page-header d-flex justify-content-between align-items-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
            <i class="fa-solid fa-circle-arrow-left"></i> Volver
        </a>
    </div>

    <h1>Panel de Asistencia Institucional</h1>

    <?php if (!empty($criticos)): ?>
    <div class="glass-card mb-4 p-4" style="border-left: 5px solid #f87171;">
        <h5 class="mb-3" style="color: #f87171; font-weight: 700;">
            <i class="fa fa-triangle-exclamation"></i> Estudiantes con Ausentismo Crítico
        </h5>
        <div class="table-responsive">
            <table class="table table-sm text-white m-0">
                <thead>
                    <tr style="background: rgba(248, 113, 113, 0.1);">
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Inasistencias</th>
                        <th>Acción Administrativa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($criticos as $c): ?>
                    <tr>
                        <td class="text-left"><?= htmlspecialchars($c['Nombre']) ?></td>
                        <td><?= htmlspecialchars($c['Curso']) ?></td>
                        <td><span class="badge badge-danger" style="padding: 6px 12px;"><?= $c['Total_Faltas'] ?> Faltas</span></td>
                        <td>
                            <form method="POST" action="AsistenciaController.php">
                                <input type="hidden" name="accion" value="enviarAlerta">
                                <input type="hidden" name="email" value="<?= $c['Email'] ?>">
                                <input type="hidden" name="nombre" value="<?= $c['Nombre'] ?>">
                                <input type="hidden" name="faltas" value="<?= $c['Total_Faltas'] ?>">
                                <button type="submit" class="btn-ghost" style="border-color: #f87171; color: #f87171; font-size: 12px; height: 35px;">
                                    <i class="fa fa-paper-plane"></i> Notificar Estudiante
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="glass-card mb-4 p-3">
        <form method="GET" action="AsistenciaController.php" class="filter-form">
            <select name="curso">
                <option value="">Todos los Cursos</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['Id_Curso'] ?>" <?= ($cursoId == $c['Id_Curso']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['Nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="fecha">
                <option value="">Todas las Fechas</option>
                <?php foreach ($fechas as $f): ?>
                    <option value="<?= $f['Fecha'] ?>" <?= ($fecha == $f['Fecha']) ? 'selected' : '' ?>>
                        <?= $f['Fecha'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-ghost">
                <i class="fa fa-filter"></i> Filtrar
            </button>

            <a href="AsistenciaController.php?exportar=1<?= $fecha ? "&fecha=$fecha" : '' ?><?= $cursoId ? "&curso=$cursoId" : '' ?>" class="btn-ghost">
                <i class="fa fa-file-csv"></i> Exportar
            </a>
        </form>
    </div>

    <div class="glass-card p-3 table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estudiante</th>
                    <th>Curso</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reporte)): ?>
                    <?php foreach ($reporte as $fila): ?>
                        <tr>
                            <td><?= $fila['Id_Estudiante'] ?></td>
                            <td class="text-left"><?= htmlspecialchars($fila['Estudiante']) ?></td>
                            <td><?= htmlspecialchars($fila['Curso']) ?></td>
                            <td><?= htmlspecialchars($fila['Fecha']) ?></td>
                            <td>
                                <?php if($fila['Presente']): ?>
                                    <span class="badge badge-success" style="background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid #4ade80;">Presente</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="background: rgba(248, 113, 113, 0.2); color: #f87171; border: 1px solid #f87171;">Ausente</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No se encontraron registros.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <div class="pagination-wrap">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($paginaActual == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&curso=<?= $cursoId ?>&fecha=<?= $fecha ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>