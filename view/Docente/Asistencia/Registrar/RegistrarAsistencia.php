<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap e iconos -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">




    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #ffffff;
            padding: 40px 15px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            overflow-x: hidden;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
        }

        /* Contenedor filtros */
        .filtro-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        .filtro-box input,
        .filtro-box select {
            min-width: 180px;
            padding: 10px 15px;
            border-radius: 15px;
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            border: none;
        }

        .filtro-box select option {
            color: #000;
        }

        .filtro-box button,
        .filtro-box a {
            padding: 10px 20px;
            border-radius: 15px;
            border: none;
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            text-decoration: none;
            transition: 0.2s;
        }

        .filtro-box button:hover,
        .filtro-box a:hover {
            background: rgba(255,255,255,0.35);
        }

        /* Card */
        .card {
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        /* Tabla FIX DEFINITIVO */
        .table,
        .table th,
        .table td,
        .table thead th,
        .table tbody td {
            color: #ffffff !important;
        }

        .table thead {
            background: rgba(255,255,255,0.15);
        }

        .table tbody tr:nth-child(even) {
            background: rgba(255,255,255,0.06);
        }

        .table tbody tr:hover td {
            background: rgba(255,255,255,0.12);
            color: #ffffff !important;
        }

        .table .text-muted {
            color: rgba(255,255,255,0.7) !important;
        }

        /* Estado */
        .estado-toggle {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .toggle-btn {
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .btn-presente {
            border-color: #22c55e;
            color: #22c55e;
            background: rgba(34,197,94,0.15);
        }

        .btn-ausente {
            border-color: #ef4444;
            color: #ef4444;
            background: rgba(239,68,68,0.15);
        }

        .btn-presente.active {
            background: #22c55e;
            color: #ffffff;
        }

        .btn-ausente.active {
            background: #ef4444;
            color: #ffffff;
        }

        /* Acciones */
        .sticky-actions {
            margin-top: 15px;
            padding: 12px 15px;
            border-radius: 15px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-primary {
            background: #6a5acd;
            border: none;
            border-radius: 15px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: #836fff;
        }

        .pagination .page-link {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            border: none;
        }

        .pagination .active .page-link {
            background: #6a5acd;
        }
    </style>
</head>

<body>

<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php"
   class="btn btn-outline-light mb-3"
   style="border-radius:15px;padding:8px 18px;text-decoration:none;">
    <i class="bi bi-arrow-left-circle-fill"></i> Volver
</a>

<div class="container">

    <h2>
        <i class="bi bi-clipboard2"></i> Registrar Asistencia
        <?php if (!empty($fecha)): ?>
            <span class="badge bg-light text-dark"><?= htmlspecialchars($fecha) ?></span>
        <?php endif; ?>
    </h2>

    <form method="GET" class="filtro-box">
        <input type="date" name="fecha"
               value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

        <select name="curso" required>
            <option value="">Seleccione un curso</option>
            <?php foreach($cursos as $c): ?>
                <option value="<?= (int)$c['Id_Curso'] ?>"
                    <?= (!empty($cursoId) && $cursoId == $c['Id_Curso'])?'selected':'' ?>>
                    <?= htmlspecialchars($c['Curso']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">
            <i class="bi bi-filter"></i> Cargar lista
        </button>

        <a href="RegistrarAsistenciaController.php">
            <i class="bi bi-arrow-clockwise"></i> Restablecer
        </a>
    </form>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Lista de estudiantes</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success btn-sm" id="btnMarcarTodos">
                    Todos presentes
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btnMarcarNadie">
                    Todos ausentes
                </button>
            </div>
        </div>

        <form method="POST" id="formAsistencia">
            <input type="hidden" name="curso" value="<?= (int)($cursoId ?? 0) ?>">
            <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

            <div class="table-responsive">
                <table class="table table-borderless text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Correo</th>
                            <th>Curso</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($alumnos)): ?>
                        <?php foreach($alumnos as $al):
                            $idEst = (int)$al['Id_Estudiante'];
                            $valor = $asistenciaMap[$idEst] ?? 1;
                        ?>
                        <tr>
                            <td><?= $idEst ?><input type="hidden" name="estudiante_id[]" value="<?= $idEst ?>"></td>
                            <td><?= htmlspecialchars($al['Nombre']) ?></td>
                            <td><?= htmlspecialchars($al['Email']) ?></td>
                            <td><?= htmlspecialchars($al['Curso']) ?></td>
                            <td>
                                <div class="estado-toggle" data-id="<?= $idEst ?>">
                                    <button type="button" class="toggle-btn btn-presente <?= $valor ? 'active':'' ?>">Presente</button>
                                    <button type="button" class="toggle-btn btn-ausente <?= !$valor ? 'active':'' ?>">Ausente</button>
                                    <input type="hidden" name="estado[<?= $idEst ?>]" id="estado-<?= $idEst ?>" value="<?= $valor ?>">
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-muted py-4">
                                Selecciona un curso y fecha para cargar la lista.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="sticky-actions">
                <div class="text-muted">
                    <?php if(!empty($alumnos)): ?>
                        Mostrando <?= count($alumnos) ?> estudiantes
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>

<?php if (!empty($_GET['ok'])): ?>
<div class="alert alert-success alert-dismissible fade show text-center"
     style="border-radius:15px;background:rgba(34,197,94,0.15);border:1px solid #22c55e;color:#22c55e;">
    <i class="bi bi-check-circle-fill"></i>
    <?= $_GET['ok'] === 'editado'
        ? '✏️ Asistencia actualizada correctamente'
        : '✅ Asistencia guardada correctamente'
    ?>
    <button type="button" class="close text-success" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
<?php endif; ?>

<?php if (!empty($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show text-center"
     style="border-radius:15px;background:rgba(239,68,68,0.15);border:1px solid #ef4444;color:#ef4444;">
    <i class="bi bi-x-circle-fill"></i>
    ❌ Error al guardar la asistencia
    <button type="button" class="close text-danger" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
<?php endif; ?>


    
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('.estado-toggle .toggle-btn').click(function(){
    const box = $(this).closest('.estado-toggle');
    box.find('.toggle-btn').removeClass('active');
    $(this).addClass('active');
    const id = box.data('id');
    $('#estado-'+id).val($(this).hasClass('btn-presente') ? 1 : 0);
});

$('#btnMarcarTodos').click(function(){
    $('.estado-toggle').each(function(){
        const id = $(this).data('id');
        $(this).find('.btn-presente').addClass('active');
        $(this).find('.btn-ausente').removeClass('active');
        $('#estado-'+id).val(1);
    });
});

$('#btnMarcarNadie').click(function(){
    $('.estado-toggle').each(function(){
        const id = $(this).data('id');
        $(this).find('.btn-ausente').addClass('active');
        $(this).find('.btn-presente').removeClass('active');
        $('#estado-'+id).val(0);
    });
});
</script>

</body>
</html>
