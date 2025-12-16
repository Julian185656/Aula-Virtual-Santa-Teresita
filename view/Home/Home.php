<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Grad School HTML5 Template</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
    <link rel="stylesheet" href="../assets/css/owl.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">

    <style>
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }


        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }


        .home-overlay {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            color: #fff;
            text-align: center;
            padding-top: 120px;
        }

        .main-banner {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }



        .header-text,
        .caption,
        .caption h6,
        .caption h2 {
            position: relative;
            z-index: 2;
        }

        .role-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 50px;
            width: 100%;
            max-width: 1200px;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.13);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            color: #fff;
            flex: 0 1 260px;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
        }

        .role-card i {
            font-size: 45px;
            margin-bottom: 15px;
            color: #fff;
        }

        .role-card h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .role-card p {
            color: #e7e7e7;
            font-size: 15px;
        }

        .role-card a {
            display: inline-block;
            margin-top: 15px;
            color: #ffffff;
            padding: 8px 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            text-decoration: none;
            transition: 0.2s ease;
        }

        .role-card a:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .home-overlay {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 120px;
            z-index: 10;
        }

        .home-title {
            color: white;
            font-size: 2rem;
            margin-bottom: 25px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }



#chatbot-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    width: 65px;
    height: 65px;
    background: #0d6efd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 26px;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* Ventana del chatbot */
#chatbot-window {
    position: fixed;
    bottom: 100px;
    right: 25px;
    width: 320px;
    height: 420px;
    background: rgba(0,0,0,0.85);
    border-radius: 15px;
    display: none;
    flex-direction: column;
    z-index: 9999;
    color: white;
}

/* Header */
.chatbot-header {
    padding: 10px 15px;
    background: #0d6efd;
    border-radius: 15px 15px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Body */
.chatbot-body {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
}

/* Footer */
.chatbot-footer {
    display: flex;
    padding: 10px;
    gap: 5px;
}

.chatbot-footer input {
    flex: 1;
    border-radius: 8px;
    border: none;
    padding: 6px;
}

.chatbot-footer button {
    background: #0d6efd;
    border: none;
    color: white;
    border-radius: 8px;
    padding: 0 10px;
}






    </style>
</head>




<body>

    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="#"><em>Santa</em> Teresita</a>
        </div>

        <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>

        <nav id="menu" class="main-nav" role="navigation">
            <ul class="main-menu">

                <?php if (isset($_SESSION['nombre'])): ?>
                    <?php $rolActual = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null); ?>

                    <?php if ($rolActual === 'Administrador'): ?>

                    <?php elseif ($rolActual === 'Docente'): ?>

                    <?php elseif ($rolActual === 'Estudiante'): ?>

                    <?php endif; ?>

                    <li><a href="#" style="color: #ffffff;"><?php echo htmlspecialchars($_SESSION['nombre']); ?></a></li>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color: red;">Cerrar sesión</a></li>

                <?php else: ?>
                    <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" style="color: #ff0000;">Iniciar sesión</a></li>

                <?php endif; ?>

            </ul>
        </nav>
    </header>



    <section class="section main-banner" id="top">
        <video autoplay muted loop playsinline id="bg-video">
            <source src="../assets/images/course-video.mp4" type="video/mp4">
        </video>

        <div class="video-overlay"></div>
        <div class="home-overlay">
            <h2 class="home-title">Herramientas</h2>

            <div class="role-cards">

                <?php if ($rolActual === 'Administrador'): ?>

                    <div class="role-card">
                        <i class="fa fa-users"></i>
                        <h4>Gestión de Usuarios</h4>
                        <p>Agregar, editar y eliminar perfiles.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/admin_usuarios_list.php">Ir</a>
                    </div>

                    <div class="role-card">
                        <i class="fa fa-shield-halved"></i>
                        <h4>Auditoría</h4>
                        <p>Registro de eventos y seguridad del sistema.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/admin_auditoria_list.php">Ir</a>
                    </div>


                    <div class="role-card">
                        <i class="fa fa-chart-bar"></i>
                        <h4>Reportes</h4>
                        <p>Estadísticas y reportes generales.</p>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalReporteria">Ir</a>
                    </div>

                    <div class="role-card">
                        <i class="fa fa-book"></i>
                        <h4>Gestionar Cursos</h4>
                        <p>Crear, editar y administrar cursos.</p>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalGestionCursos">Ir</a>
                    </div>

                    <div class="role-card">
                        <i class="fa fa-bell"></i>
                        <h4>Notificaciones</h4>
                        <p>Enviar y consultar avisos.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Notificaciones.php">Ir</a>
                    </div>

                    <div class="role-card">
                        <i class="fa fa-comments"></i>
                        <h4>Foros</h4>
                        <p>Gestionar y moderar foros.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Foro/ForoAdmin.php">Ir</a>
                    </div>

                    <?php if (isset($_SESSION['rol']) && strtolower($_SESSION['rol']) === 'administrador'): ?>
                        <div class="role-card">
                            <i class="fa fa-comments"></i>
                            <h4>Rendimiento General</h4>
                            <p>Rendimiento General</p>
                            <a href="/Aula-Virtual-Santa-Teresita/view/Admin/RendimientoGeneral.php">Ir</a>
                        </div>
                    <?php endif; ?>


                <?php elseif ($rolActual === 'Docente'): ?>





                    <div class="role-card">
                        <i class="fa fa-graduation-cap"></i>
                        <h4>Ver Perfiles Alumnos</h4>
                        <p>Ver Perfiles Alumnos</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/ListaDocente.php">Ir</a>
                    </div>



                    <div class="role-card">
                        <i class="fa fa-graduation-cap"></i>
                        <h4>Mis Cursos</h4>
                        <p>Administrar contenido y tareas.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/MisCursos.php">Ir</a>
                    </div>
                    <div class="role-card">
                        <i class="fa fa-calendar-alt"></i>
                        <h4>Mi Agenda</h4>
                        <p>Planificar actividades.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Agenda.php">Ir</a>
                    </div>

                    <div class="role-card">
                        <i class="fa fa-clipboard-list"></i>
                        <h4>Asistencias</h4>
                        <p>Control de asistencia.</p>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalAsistencias">Ir</a>

                    </div>

                <?php elseif ($rolActual === 'Estudiante'): ?>

                    <div class="role-card">
                        <i class="fa fa-book-open"></i>
                        <h4>Mis Cursos</h4>
                        <p>Accede a contenido y material.</p>
                        <a href="/Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php">Ir</a>
                    </div>








                    <div class="role-card">
                        <i class="fa fa-headset"></i>
                        <h4>Soporte Técnico</h4>
                        <p>Contacto directo.</p>
                        <a href="https://wa.me/50622222222">Ir</a>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </section>


    <div class="modal fade" id="modalAsistencias" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content"
                style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px);
             border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">

                <div class="modal-header">
                    <h3 class="modal-title">Gestión de Asistencias</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row justify-content-center g-4">

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-plus-circle"></i>
                                <h4>Registrar Asistencia</h4>
                                <p>Agregar asistencia para los estudiantes.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Registrar/RegistrarAsistenciaController.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-eye"></i>
                                <h4>Historial de asistencia</h4>
                                <p>Consulta el historial de asistencia individual de los estudiantes.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Historial/HistorialAsistenciaController.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-download"></i>
                                <h4>Justificacion de Ausencias</h4>
                                <p>Registra como justificada la ausencias de los estudiantes, con justificacion Valida</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Justificacion/JustificarAusenciaController.php">Ir</a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="modalGestionCursos" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content"
                style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px);
             border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">

                <div class="modal-header">
                    <h3 class="modal-title">Gestión de Cursos</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row justify-content-center g-4">

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-plus-circle"></i>
                                <h4>Crear Curso</h4>
                                <p>Agregar nuevos cursos.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/CrearCursos.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-edit"></i>
                                <h4>Eliminar</h4>
                                <p>Eliminar cursos existentes.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/EliminarCurso.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-layer-group"></i>
                                <h4>Asignar Profesores</h4>
                                <p>Asignar docentes a un curso.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/AsignarDocentes.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa fa-user-graduate"></i>
                                <h4>Estudiantes</h4>
                                <p>Gestionar matrícula.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Cursos/MatricularEstudiantes.php">Ir</a>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa-solid fa-folder-open"></i>
                                <h4>Material del Curso</h4>
                                <p>Subir, ver y eliminar archivos del curso.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Admin/SeleccionarCursoMaterial.php">Ir</a>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade" id="modalReporteria" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content"
                style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px);
             border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">

                <div class="modal-header">
                    <h3 class="modal-title">Módulo de Reportería</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-4 justify-content-center">

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa-solid fa-clipboard-check"></i>
                                <h4>Asistencia</h4>
                                <p>Descargar reportes de asistencia.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Asistencia/AsistenciaController.php">Ir</a>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="role-card">
                                <i class="fa-solid fa-chart-line"></i>
                                <h4>Calificaciones</h4>
                                <p>Rendimiento general por curso.</p>
                                <a href="/Aula-Virtual-Santa-Teresita/view/Admin/Reporteria/Rendimiento/RendimientoController.php">Ir</a>
                            </div>
                        </div>


                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
    </div>



<!-- BOTÓN FLOTANTE -->
<div id="chatbot-btn" style="position: fixed; bottom: 20px; right: 20px; background: #007bff; color: #fff; padding: 10px 15px; border-radius: 50%; cursor: pointer; z-index: 1000;">
    💬
</div>

<!-- VENTANA CHATBOT -->
<div id="chatbot-window" style="display: none; position: fixed; bottom: 80px; right: 20px; width: 350px; max-height: 500px; background: #fff; border: 1px solid #ccc; border-radius: 10px; flex-direction: column; overflow: hidden; z-index: 1000;">
    <div id="chatbot-header" style="background: #007bff; color: #fff; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
        <span>Asistente Virtual</span>
        <button id="chatbot-close" style="background: none; border: none; color: #fff; font-size: 18px; cursor: pointer;">&times;</button>
    </div>
    <div id="chatbot-body" style="padding: 10px; flex: 1; overflow-y: auto; color: #000; font-family: Arial, sans-serif;"></div>
    <div id="chatbot-footer" style="padding: 10px; display: flex; gap: 5px; color: #000; font-family: Arial, sans-serif;">
        <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." style="flex:1; padding: 5px; color: #000;">
        <button id="chatbot-send" style="padding: 5px 10px; color: #000;">Enviar</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // ELEMENTOS
    const chatBtn = document.getElementById("chatbot-btn");
    const chatWindow = document.getElementById("chatbot-window");
    const chatClose = document.getElementById("chatbot-close");
    const chatBody = document.getElementById("chatbot-body");
    const chatInput = document.getElementById("chatbot-input");
    const chatSend = document.getElementById("chatbot-send");

    // Mostrar / ocultar chatbot
    chatBtn.onclick = () => chatWindow.style.display = "flex";
    chatClose.onclick = () => chatWindow.style.display = "none";

    // VARIABLES PHP
    const rol = "<?php echo $_SESSION['usuario']['rol'] ?? 'Invitado'; ?>";
    const idUsuario = "<?php echo $_SESSION['id_usuario'] ?? ''; ?>";


if (rol === "Administrador") {
        chatBtn.style.display = "none"; // oculta el botón flotante
        chatWindow.style.display = "none"; // opcional, oculta la ventana si ya estaba abierta
        return; // no sigue inicializando nada más
    }

    // MENSAJE INICIAL
  function mensajeInicial() {
    let msg = "Hola 👋, estas son las palabras que puedes usar:<br>";

    if (rol === "Docente") {
        msg += "lista y ver cursos";
    } else if (rol === "Estudiante") {
        msg += "tareas, justificación";
    } else {
        msg += "inicia sesión";
    }

    chatBody.innerHTML += `<div><b>Bot:</b> ${msg}</div>`;
    chatBody.scrollTop = chatBody.scrollHeight;
}


    
    function manejarJustificacion() {
    chatBody.innerHTML += `<div><b>Bot:</b> Por favor, selecciona el curso, la fecha y adjunta tu comprobante.</div>`;

    // Traer cursos del estudiante vía fetch
    fetch(`/Aula-Virtual-Santa-Teresita/view/Home/obtener_cursos.php?id_estudiante=${idUsuario}`)
    .then(res => res.json())
    .then(data => {
        if(!data.ok) {
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error al cargar cursos</div>`;
            return;
        }

        let opciones = '<option value="">--Selecciona un curso--</option>';
        data.cursos.forEach(c => {
            opciones += `<option value="${c.Id_Curso}">${c.Nombre}</option>`;
        });

        chatBody.innerHTML += `
            <form id="justificacion-form" enctype="multipart/form-data">
                <label>Curso:</label>
                <select name="id_curso" required>${opciones}</select><br>
                <label>Fecha de ausencia:</label>
                <input type="date" name="fecha_ausencia" required><br>
                <label>Comprobante:</label>
                <input type="file" name="comprobante" required><br>
                <button type="submit">Enviar Justificación</button>
            </form>
        `;
        chatBody.scrollTop = chatBody.scrollHeight;
    })
    .catch(err=>{
        console.error(err);
        chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error de conexión al cargar cursos</div>`;
    });
}

// Manejo del envío de la justificación
chatBody.addEventListener("submit", function(event) {
    const form = event.target;
    if (form.id !== "justificacion-form") return;
    event.preventDefault();

    const formData = new FormData(form);
    formData.append("id_estudiante", idUsuario);

    fetch('/Aula-Virtual-Santa-Teresita/view/Home/enviar_justificacion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) chatBody.innerHTML += `<div><b>Bot:</b> ✅ ${data.mensaje}</div>`;
        else chatBody.innerHTML += `<div><b>Bot:</b> ❌ ${data.error}</div>`;
        chatBody.scrollTop = chatBody.scrollHeight;
        form.remove();
    })
    .catch(err => {
        console.error(err);
        chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error de conexión</div>`;
        chatBody.scrollTop = chatBody.scrollHeight;
    });
});

    function cargarJustificaciones() {
        fetch('/Aula-Virtual-Santa-Teresita/view/Home/obtener_justificaciones.php')
        .then(res => res.json())
        .then(data => {
            if(!data.ok){ chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error al cargar</div>`; return; }
            chatBody.innerHTML += `<div style="margin-top:10px;"><b>Justificaciones pendientes:</b></div>`;
            data.justificaciones.forEach(j=>{
                chatBody.innerHTML += `
                    <div style="border:1px solid #ccc; padding:5px; margin:5px 0; border-radius:5px;">
                        <b>Estudiante:</b> ${j.Nombre}<br>
                        <b>Fecha:</b> ${j.fecha_ausencia}<br>
                        <a href="${j.comprobante}" target="_blank">Ver comprobante</a><br>
                        <b>Estado:</b> ${j.estado}<br>
                        ${j.estado==='pendiente'?`
                            <button data-id="${j.id}" data-accion="aprobar">Aprobar</button> 
                            <button data-id="${j.id}" data-accion="denegar">Denegar</button>
                        `:''}
                    </div>
                `;
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(err=>{
            console.error(err);
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error de conexión</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    }

    // APROBAR / DENEGAR
    chatBody.addEventListener("click", function(event){
        const btn = event.target;
        if(btn.tagName !== "BUTTON") return;

        const id = btn.dataset.id;
        const accion = btn.dataset.accion;
        if(!id || !accion) return;

        const formData = new FormData();
        formData.append('id', id);
        formData.append('accion', accion);

        fetch('/Aula-Virtual-Santa-Teresita/view/Home/procesar_justificacion.php',{method:'POST',body:formData})
        .then(res=>res.json())
        .then(data=>{
            if(data.ok) chatBody.innerHTML += `<div><b>Bot:</b> ✅ ${data.mensaje}</div>`;
            else chatBody.innerHTML += `<div><b>Bot:</b> ❌ ${data.error}</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
            cargarJustificaciones();
        })
        .catch(err=>{
            console.error(err);
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error de conexión</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    });

    // FUNCIONES DE CURSOS / TAREAS
    function mostrarCursosYTareas() {
        fetch('/Aula-Virtual-Santa-Teresita/view/Home/obtener_cursos_tareas.php')
        .then(res => res.json())
        .then(data => {
            if (!data.ok) { chatBody.innerHTML += `<div><b>Bot:</b> ❌ ${data.error}</div>`; return; }
            if (data.cursos.length === 0) chatBody.innerHTML += `<div><b>Bot:</b> No tienes cursos asignados 😢</div>`;
            else {
                data.cursos.forEach(curso => {
                    let html = `<div><b>Curso:</b> ${curso.nombre}</div>`;
                    curso.tareas.forEach(tarea => {
                        html += `<div>- Tarea: ${tarea.titulo} `;
                        if (tarea.pendientes > 0) html += `(⏰ ${tarea.pendientes} estudiantes no han entregado) <button onclick="enviarRecordatorio(${tarea.id})">Enviar recordatorio</button>`;
                        else html += `(🎉 Todos entregaron)`;
                        html += `</div>`;
                    });
                    chatBody.innerHTML += `<div>${html}</div>`;
                });
            }
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(err => {
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error al cargar cursos y tareas</div>`;
            console.error(err);
        });
    }

    window.enviarRecordatorio = function(idTarea) {
        fetch(`/Aula-Virtual-Santa-Teresita/view/Home/Enviar.php?id_tarea=${idTarea}`)
        .then(res => res.json())
        .then(data => {
            if (data.ok) chatBody.innerHTML += `<div><b>Bot:</b> 📧 ${data.enviados} recordatorios enviados correctamente</div>`;
            else chatBody.innerHTML += `<div><b>Bot:</b> ❌ ${data.error || 'Error al enviar correos'}</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(err => {
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error en la conexión</div>`;
            console.error(err);
        });
    };

    function obtenerTareasPendientes() {
        fetch('/Aula-Virtual-Santa-Teresita/view/Home/obtener_tareas.php')
        .then(res => res.json())
        .then(tareas => {
            let msg = "";
            if (tareas.length > 0) {
                msg = "<b>Bot:</b> Tienes las siguientes tareas pendientes (próximos 3 días):<br>";
                tareas.forEach(tarea => {
                    msg += `- <b>${tarea.Titulo}</b> (Curso: ${tarea.Curso}, Entrega: ${tarea.Fecha_Entrega})<br>`;
                });
            } else {
                msg = "<b>Bot:</b> No tienes tareas pendientes por ahora. ¡Sigue así!";
            }
            chatBody.innerHTML += `<div>${msg}</div>`;
            chatBody.scrollTop = chatBody.scrollHeight;
        })
        .catch(err => {
            chatBody.innerHTML += `<div><b>Bot:</b> ❌ Error al obtener tareas pendientes</div>`;
            console.error(err);
        });
    }

    // MANEJO DE MENSAJES
    function manejarPalabraClave(text) {
        const txt = text.toLowerCase();
        if (rol === "Estudiante" && (txt.includes("ver tareas") || txt.includes("mis tareas"))) { obtenerTareasPendientes(); return; }
        if (rol === "Docente" && (txt.includes("ver cursos") || txt.includes("cursos"))) { mostrarCursosYTareas(); return; }
        if (txt.includes("justificación")) { manejarJustificacion(); return; }
        if (txt.includes("lista")) { cargarJustificaciones(); return; }

        chatBody.innerHTML += `<div><b>Bot:</b> No entendí tu mensaje, pero pronto aprenderé</div>`;
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    chatSend.onclick = () => {
        const text = chatInput.value.trim();
        if (!text) return;
        chatBody.innerHTML += `<div><b>Tú:</b> ${text}</div>`;
        chatInput.value = "";
        setTimeout(() => manejarPalabraClave(text), 200);
    };

    // INICIALIZAR
    mensajeInicial();

});
</script>







    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/isotope.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/lightbox.js"></script>
    <script src="../assets/js/tabs.js"></script>
    <script src="../assets/js/video.js"></script>
    <script src="../assets/js/slick-slider.js"></script>
    <script src="../assets/js/custom.js"></script>

</body>

</html>