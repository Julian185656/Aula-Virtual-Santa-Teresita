<?php
require_once __DIR__ . '/../../model/CursoModel.php';
$cursos = CursoModel::obtenerCursos();
$cursoSeleccionado = $_GET['idCursoMatricula'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Matricular Estudiantes</title>

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg: #2a2b38;
            --text: #fff;
            --glass1: rgba(255, 255, 255, .10);
            --glass2: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .20);
            --shadow: 0 14px 44px rgba(0, 0, 0, .42);
            --radius: 20px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            background-color: var(--bg);
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-repeat: repeat;
            background-size: 600px;
            color: #c4c3ca;
            padding: 40px 15px;
        }

        .page-wrap { max-width: 1100px; margin: auto; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px;
            background: rgba(255,255,255,.12); color: #fff !important; 
            border-radius: 12px; border: 1px solid rgba(255,255,255,.35);
            text-decoration: none !important; transition: .2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.2); transform: translateY(-1px); }

        h1 { text-align: center; font-weight: 700; color: #fff; margin: 20px 0 30px; text-shadow: 0 2px 10px rgba(0,0,0,.3); }

        .glass-card {
            background: rgba(255,255,255,.07);
            padding: 30px;
            border-radius: var(--radius);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
        }

        /* Filtros con estilo de Editar Usuario */
        .form-style {
            padding: 12px;
            background: rgba(255,255,255,.12);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.25);
            color: #fff;
            outline: none;
            min-width: 280px;
            transition: all .2s;
        }
        .form-style:focus { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.15); }
        select.form-style option { background: #1f272b; color: #fff; }

        /* TABLA CON CORRECCIÓN DE SUPERPOSICIÓN (Sticky Header) */
        .table-wrap { 
            overflow-y: auto; 
            max-height: 550px; 
            margin-top: 20px; 
            border-radius: 15px;
            border: 1px solid var(--stroke);
        }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }

        th { 
            background: #2a2b38; /* Color sólido para bloquear la transparencia al scrollear */
            padding: 18px 15px; 
            color: #fff; 
            position: sticky; 
            top: 0; 
            z-index: 10; /* Prioridad de capa sobre el contenido */
            border-bottom: 2px solid var(--stroke);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        /* Sutil efecto glass sobre el fondo sólido del header */
        th::after {
            content: '';
            position: absolute; left: 0; top: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.03);
            z-index: -1;
        }

        td { 
            padding: 14px; 
            border-bottom: 1px solid rgba(255,255,255,0.05); 
            color: #fff; 
            background: transparent;
            vertical-align: middle;
        }

        /* Switch Moderno */
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #555; transition: .4s; border-radius: 34px;
        }
        .slider:before {
            position: absolute; content: ""; height: 14px; width: 14px;
            left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%;
        }
        input:checked + .slider { background-color: #28c85a; }
        input:checked + .slider:before { transform: translateX(20px); }

        /* Chips */
        .chip {
            display: inline-flex; align-items: center; background: rgba(255,255,255,0.08);
            border: 1px solid var(--stroke); padding: 5px 12px; border-radius: 20px;
            margin: 3px; font-size: 12px; transition: .2s;
        }
        .chip:hover { background: rgba(255,255,255,0.15); }
        .btn-del-chip { margin-left: 8px; color: #ff6b6b; cursor: pointer; font-size: 14px; }

        /* MODAL (Estilo exacto de tu Editor de Usuario) */
        .modal-content {
            background: rgba(29,30,40,0.95) !important;
            border: 1px solid rgba(255,255,255,0.15) !important;
            border-radius: 20px !important;
            backdrop-filter: blur(15px);
        }
        .modal-header, .modal-footer { border: none !important; }
        .modal-title { font-weight: 600; color: #fff; }
    </style>
</head>
<body>

<div class="page-wrap">
    <div class="page-header d-flex justify-content-between align-items-center">
        <a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-back">
            <i class="fa-solid fa-circle-arrow-left"></i> Volver
        </a>
    </div>

    <h1>Reporte de Matrícula</h1>

    <div class="glass-card">
        <div class="d-flex justify-content-center flex-wrap mb-4" style="gap: 20px;">
            <select id="cursoDestino" class="form-style">
                <option value="">Seleccione el curso destino...</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="busqueda" class="form-style" placeholder="🔍 Buscar estudiante por nombre...">
        </div>

        <div class="table-wrap">
            <table class="text-center">
                <thead>
                    <tr>
                        <th width="120">Matrícula</th>
                        <th class="text-left" style="padding-left: 20px;">Estudiante</th>
                        <th>Cursos Activos</th>
                    </tr>
                </thead>
                <tbody id="tablaEstudiantes">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cambio</h5>
                <button class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p>¿Deseas eliminar la inscripción del estudiante en el curso:</p>
                <h5 id="cursoNombreModal" style="color: #ff6b6b; font-weight: 700;"></h5>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-outline-light px-4" data-dismiss="modal" style="border-radius:12px;">Cancelar</button>
                <button id="confirmBtn" class="btn btn-danger px-4" style="border-radius:12px; background: #ff6b6b; border: none;">Eliminar Matrícula</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<script>
    let tempDeleteData = { eid: null, cid: null };

    // Función principal para cargar datos sin recargar la página
    function cargarEstudiantes() {
        const cid = document.getElementById('cursoDestino').value;
        const search = document.getElementById('busqueda').value;

        fetch(`AccionesMatricula.php?action=listar&search=${search}&cursoId=${cid}`)
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tablaEstudiantes');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="py-5" style="opacity:0.5">No se encontraron resultados</td></tr>';
                    return;
                }

                data.forEach(e => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>
                            <label class="switch">
                                <input type="checkbox" ${e.ya_matriculado ? 'checked' : ''} 
                                       onchange="gestionarMatricula(this, ${e.id})" ${!cid ? 'disabled' : ''}>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="text-left" style="padding-left: 20px;">
                            <span style="color:#fff; font-weight: 600;">${e.nombre}</span>
                        </td>
                        <td>
                            ${e.cursos.map(c => `
                                <span class="chip">
                                    ${c.nombre} 
                                    <i class="bi bi-x-circle-fill btn-del-chip" onclick="solicitarEliminacion(${e.id}, ${c.id}, '${c.nombre}')"></i>
                                </span>
                            `).join('') || '<small style="opacity:0.3">Ninguno</small>'}
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            });
    }

    // Matricular instantáneamente con el Switch
    function gestionarMatricula(cb, eid) {
        const cid = document.getElementById('cursoDestino').value;
        const action = cb.checked ? 'matricular' : 'eliminar';
        
        fetch('AccionesMatricula.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=${action}&idCurso=${cid}&idEstudiante=${eid}`
        }).then(() => cargarEstudiantes());
    }

    // Abrir modal de confirmación para eliminar desde un Chip
    function solicitarEliminacion(eid, cid, cname) {
        tempDeleteData = { eid, cid };
        document.getElementById('cursoNombreModal').innerText = cname;
        $('#confirmModal').modal('show');
    }

    // Ejecutar eliminación tras confirmar en el modal
    document.getElementById('confirmBtn').addEventListener('click', function() {
        fetch('AccionesMatricula.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=eliminar&idCurso=${tempDeleteData.cid}&idEstudiante=${tempDeleteData.eid}`
        }).then(() => {
            $('#confirmModal').modal('hide');
            cargarEstudiantes();
        });
    });

    // Listeners para filtros
    document.getElementById('cursoDestino').addEventListener('change', cargarEstudiantes);
    document.getElementById('busqueda').addEventListener('input', cargarEstudiantes);
    
    // Carga inicial
    window.onload = cargarEstudiantes;
</script>

</body>
</html>