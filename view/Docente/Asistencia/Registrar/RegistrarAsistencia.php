<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Asistencia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap y iconos -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            color: #c4c3ca;
            padding: 40px 15px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
        }



        
        h2 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.5);
            color: #fff;
        }

        /* Formulario de filtros */
        form.row.g-3.align-items-end {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
            background: rgba(255,255,255,0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        form input, form select, form button, form a {
            border-radius: 15px;
            border: none;
        }

        form input[type="text"], form input[type="date"], form select {
            padding: 10px 15px;
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        form input::placeholder {
            color: #ddd;
        }

        form button, form a {
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            background: rgba(255,255,255,0.15);
            transition: 0.2s ease;
        }

        form button:hover, form a:hover {
            background: rgba(255,255,255,0.35);
        }

        /* Card de la tabla */
        .card {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
        }

        /* Tabla */
        table, table thead th, table tbody td {
            color: #fff !important;
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
        }

        table tr:nth-child(even) {
            background: rgba(255,255,255,0.02);
        }

        table tr:hover {
            background: rgba(255,255,255,0.1);
        }

        .estado-toggle {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .toggle-btn {
            padding: 6px 14px;
            border-radius: 6px;
            border: 2px solid transparent;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s ease;
        }

        .btn-presente {
            background: #e8fff0;
            border-color: #28a745;
            color: #28a745;
        }

        .btn-ausente {
            background: #ffffff;
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-presente.active {
            background: #28a745;
            color: white;
        }

        .btn-ausente.active {
            background: #dc3545;
            color: white;
        }

        .sticky-actions {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            padding: 10px 15px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.25);
            margin-top: 15px;
        }

        .btn-primary {
            background-color: #6a5acd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #836fff;
        }

        .pagination a {
            color: #fff;
            background: rgba(255,255,255,0.1);
            margin: 0 3px;
        }

        .pagination a:hover {
            background: rgba(255,255,255,0.35);
        }

        .pagination .active a {
            background: #6a5acd !important;
        }
    </style>
</head>





  <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn btn-outline-light mb-3" style="border-radius: 15px; padding: 8px 18px; text-decoration:none;">
        <i class="bi bi-arrow-left-circle-fill"></i> Volver
    </a>



<body>
<div class="container">

    <h2><i class="bi bi-clipboard2"></i> Registrar Asistencia
        <?php if (!empty($fecha)): ?>
            <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($fecha) ?></span>
        <?php endif; ?>
    </h2>

<form method="GET" class="d-flex flex-wrap justify-content-center align-items-center mb-4 p-3"
      style="background: rgba(255,255,255,0.05); border-radius:20px; backdrop-filter: blur(10px); border:1px solid rgba(255,255,255,0.25); gap:20px;">
    
    <!-- Fecha -->
    <input type="date" name="fecha" class="form-control" 
           value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>"
           style="min-width:160px; background: rgba(255,255,255,0.1); color:#fff; border-radius:15px; padding:10px 15px;">

    <!-- Selección de curso -->
    <select name="curso" class="form-select" required 
            style="min-width:200px; background: rgba(255,255,255,0.1); color:black; border-radius:15px; padding:10px 15px;">
        <option value="">Seleccione un curso</option>
        <?php foreach($cursos as $c): ?>
            <option value="<?= (int)$c['Id_Curso'] ?>" <?= (!empty($cursoId) && $cursoId == $c['Id_Curso'])?'selected':'' ?>>
                <?= htmlspecialchars($c['Curso']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Botón Cargar lista -->
    <button type="submit" class="btn btn-primary" style="padding:10px 20px; border-radius:15px;">
        <i class="bi bi-filter"></i> Cargar lista
    </button>

    <!-- Botón Restablecer -->
    <a href="RegistrarAsistenciaController.php" class="btn btn-outline-light" 
       style="padding:10px 20px; border-radius:15px;">
       <i class="bi bi-arrow-clockwise"></i> Restablecer
    </a>
</form>




    <!-- Tabla -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Lista de estudiantes</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm" id="btnMarcarTodos">
                        <i class="bi bi-check2-circle"></i> Todos presentes
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnMarcarNadie">
                        <i class="bi bi-x-circle"></i> Todos ausentes
                    </button>
                </div>
            </div>

            <form method="POST" id="formAsistencia">
                <input type="hidden" name="curso" value="<?= (int)($cursoId ?? 0) ?>">
                <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Correo</th>
                            <th>Curso</th>
                            <th class="text-center">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($alumnos)): ?>
                            <?php foreach($alumnos as $al):
                                $idEst = (int)$al['Id_Estudiante'];
                                $valorActual = $asistenciaMap[$idEst] ?? 1;
                                $isPresente = ($valorActual === 1);
                                ?>
                                <tr>
                                    <td><?= $idEst ?><input type="hidden" name="estudiante_id[]" value="<?= $idEst ?>"></td>
                                    <td><?= htmlspecialchars($al['Nombre']) ?></td>
                                    <td><?= htmlspecialchars($al['Email']) ?></td>
                                    <td><?= htmlspecialchars($al['Curso']) ?></td>
                                    <td class="text-center">
                                        <div class="estado-toggle" data-id="<?= $idEst ?>">
                                            <button type="button" class="toggle-btn btn-presente <?= $isPresente?'active':'' ?>">Presente</button>
                                            <button type="button" class="toggle-btn btn-ausente <?= $isPresente?'':'active' ?>">Ausente</button>
                                            <input type="hidden" name="estado[<?= $idEst ?>]" id="estado-<?= $idEst ?>" value="<?= $isPresente?1:0 ?>">
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Selecciona un curso y fecha para cargar la lista.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="sticky-actions d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        <?php if(!empty($alumnos)): ?>
                            Mostrando <?= count($alumnos) ?> de <?= (int)($totalRegistros ?? 0) ?> estudiantes.
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </form>

            <?php if(!empty($alumnos) && ($totalPaginas ?? 1) > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for($i=1;$i<=$totalPaginas;$i++): ?>
                            <li class="page-item <?= (isset($pagina) && $pagina==$i)?'active':'' ?>">
                                <a class="page-link" href="?curso=<?= (int)$cursoId ?>&fecha=<?= $fecha ?>&pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle estado
    $('.estado-toggle .toggle-btn').click(function(){
        var parent = $(this).closest('.estado-toggle');
        parent.find('.toggle-btn').removeClass('active');
        $(this).addClass('active');
        var id = parent.data('id');
        var val = $(this).hasClass('btn-presente') ? 1 : 0;
        $('#estado-'+id).val(val);
    });

    $('#btnMarcarTodos').click(function(){
        $('.estado-toggle').each(function(){
            $(this).find('.btn-presente').addClass('active');
            $(this).find('.btn-ausente').removeClass('active');
            var id = $(this).data('id');
            $('#estado-'+id).val(1);
        });
    });

    $('#btnMarcarNadie').click(function(){
        $('.estado-toggle').each(function(){
            $(this).find('.btn-ausente').addClass('active');
            $(this).find('.btn-presente').removeClass('active');
            var id = $(this).data('id');
            $('#estado-'+id).val(0);
        });
    });
</script>

</body>
</html>
