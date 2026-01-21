<?php
// /view/Home/Home.php
session_start();
$rolActual = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Santa Teresita - Home</title>

  <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">

  <!-- Bootstrap 4 CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (para tus íconos del home) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-grad-school.css">
  <link rel="stylesheet" href="../assets/css/owl.css">
  <link rel="stylesheet" href="../assets/css/lightbox.css">

  <style>
    #bg-video{
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    .video-overlay{
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1;
    }

    .main-banner{
      position: relative;
      height: 100vh;
      overflow: hidden;
    }

    .home-overlay{
      position: absolute;
      top: 0; left: 50%;
      transform: translateX(-50%);
      width: 100%;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 120px;
      z-index: 10;
      color: #fff;
      text-align: center;
    }

    .home-title{
      color: white;
      font-size: 2rem;
      margin-bottom: 25px;
      text-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    .role-cards{
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 25px;
      margin-top: 50px;
      width: 100%;
      max-width: 1200px;
    }

    .role-card{
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

    .role-card:hover{
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
    }

    .role-card i{
      font-size: 45px;
      margin-bottom: 15px;
      color: #fff;
    }

    .role-card h4{
      font-weight: 700;
      margin-bottom: 10px;
    }

    .role-card p{
      color: #e7e7e7;
      font-size: 15px;
    }

    .role-card a{
      display: inline-block;
      margin-top: 15px;
      color: #ffffff;
      padding: 8px 20px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.15);
      text-decoration: none;
      transition: 0.2s ease;
    }

    .role-card a:hover{
      background: rgba(255, 255, 255, 0.35);
    }

    /* =========================================================
       FIX: Bootstrap 4 NO soporta g-4 (gap). Evita cards montadas
    ========================================================= */
    .modal-cards-row{
      margin-left: -12px;
      margin-right: -12px;
    }
    .modal-cards-row > [class*="col-"]{
      padding-left: 12px;
      padding-right: 12px;
      margin-bottom: 18px;
    }
    .modal-cards-row .role-card{
      width: 100%;
      height: 100%;
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
        <li><a href="#" style="color:#ffffff;"><?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
        <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Logout.php" style="color:red;">Cerrar sesión</a></li>
      <?php else: ?>
        <li><a href="/Aula-Virtual-Santa-Teresita/view/Login/Login.php" style="color:#ff0000;">Iniciar sesión</a></li>
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
          <!-- Bootstrap 4: data-toggle / data-target -->
          <a href="#" data-toggle="modal" data-target="#modalReporteria">Ir</a>
        </div>

        <div class="role-card">
          <i class="fa fa-book"></i>
          <h4>Gestionar Cursos</h4>
          <p>Crear, editar y administrar cursos.</p>
          <!-- Bootstrap 4: data-toggle / data-target -->
          <a href="#" data-toggle="modal" data-target="#modalGestionCursos">Ir</a>
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
          <!-- Bootstrap 4: data-toggle / data-target -->
          <a href="#" data-toggle="modal" data-target="#modalAsistencias">Ir</a>
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

<!-- ===================== MODAL ASISTENCIAS (BS4) ===================== -->
<div class="modal fade" id="modalAsistencias" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">
      <div class="modal-header">
        <h3 class="modal-title">Gestión de Asistencias</h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- 🔧 CAMBIO: g-4 -> modal-cards-row (BS4) -->
        <div class="row justify-content-center modal-cards-row">

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
        <button class="btn btn-light" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL GESTIÓN CURSOS (BS4) ===================== -->
<div class="modal fade" id="modalGestionCursos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">
      <div class="modal-header">
        <h3 class="modal-title">Gestión de Cursos</h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- 🔧 CAMBIO: g-4 -> modal-cards-row (BS4) -->
        <div class="row justify-content-center modal-cards-row">

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
        <button class="btn btn-light" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ===================== MODAL REPORTERÍA (BS4) ===================== -->
<div class="modal fade" id="modalReporteria" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content" style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border-radius: 20px; border: 1px solid rgba(255,255,255,0.25); color:white;">
      <div class="modal-header">
        <h3 class="modal-title">Módulo de Reportería</h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- 🔧 CAMBIO: g-4 -> modal-cards-row (BS4) -->
        <div class="row justify-content-center modal-cards-row">

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

      <div class="modal-footer">
        <button class="btn btn-light" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ===================== Bootstrap 4 JS (OBLIGATORIO para modals) ===================== -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<!-- BOTÓN FLOTANTE -->
<div id="chatbot-btn">💬</div>

<!-- VENTANA DEL CHATBOT -->
<div id="chatbot-window">
  <div id="chatbot-header">
    <strong>Asistente Virtual</strong>
    <button id="chatbot-close">×</button>
  </div>
  <div id="chatbot-body"></div>
  <div id="chatbot-footer">
    <input id="chatbot-input" type="text" placeholder="Escribe algo...">
    <button id="chatbot-send">Enviar</button>
  </div>
</div>

<!-- ESTILOS -->
<style>
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
  font-size: 28px;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 10px 25px rgba(0,0,0,0.3);
  transition: transform 0.2s;
}
#chatbot-btn:hover { transform: scale(1.1); }

#chatbot-window {
  display: none;
  position: fixed;
  bottom: 100px;
  right: 25px;
  width: 360px;
  max-height: 520px;
  background: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.3);
  z-index: 9999;
  display: flex;
  flex-direction: column;
  font-family: 'Segoe UI', sans-serif;
}

#chatbot-header {
  background: linear-gradient(90deg, #0d6efd, #0a58ca);
  color: white;
  padding: 14px 18px;
  font-weight: bold;
  font-size: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
}

#chatbot-close {
  background: transparent;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}

#chatbot-body {
  padding: 12px;
  flex: 1;
  overflow-y: auto;
  font-size: 14px;
  color: #333;
}

#chatbot-footer {
  padding: 10px;
  display: flex;
  gap: 6px;
  border-top: 1px solid #ddd;
  background: #fff;
}

#chatbot-input {
  flex: 1;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 14px;
}

#chatbot-send {
  background: #0d6efd;
  color: white;
  padding: 8px 14px;
  border-radius: 6px;
  border: none;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s;
}
#chatbot-send:hover {
  background: #0b5ed7;
}
</style>

<!-- SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("chatbot-btn");
  const win = document.getElementById("chatbot-window");
  const closeBtn = document.getElementById("chatbot-close");
  const body = document.getElementById("chatbot-body");
  const input = document.getElementById("chatbot-input");
  const send = document.getElementById("chatbot-send");

  const rol = "<?php echo $_SESSION['usuario']['Rol'] ?? 'Invitado'; ?>".trim().toLowerCase();
  const idUsuario = "<?php echo $_SESSION['id_usuario'] ?? ''; ?>";

  btn.onclick = () => {
    win.style.display = "flex";
    mensajeInicial();
  };

  closeBtn.onclick = () => win.style.display = "none";

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
                <input type="date" id="fecha_ausencia" name="fecha_ausencia" required><br>
                <label>Comprobante:</label>
                <input type="file" name="comprobante" required><br>
                <button type="submit">Enviar Justificación</button>
            </form>
        `;
        chatBody.scrollTop = chatBody.scrollHeight;

        // PERMITIR HOY Y DÍAS ANTERIORES (NO FUTURO)
        const inputFecha = document.getElementById("fecha_ausencia");
        const ahora = new Date();
        ahora.setMinutes(ahora.getMinutes() - ahora.getTimezoneOffset());
        const hoy = ahora.toISOString().split("T")[0];

        inputFecha.max = hoy;              // bloquea fechas futuras
        inputFecha.removeAttribute("min"); // permite fechas pasadas
        inputFecha.value = hoy;            // deja hoy por defecto
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
    fetch("/Aula-Virtual-Santa-Teresita/view/Home/obtener_tareas.php")
      .then(r => r.json())
      .then(data => {
        if (!data || data.length === 0) {
          body.innerHTML += `<div><b>Bot:</b> No tienes tareas pendientes 🎉</div>`;
        } else {
          let html = "<b>Bot:</b> Tienes estas tareas:<br>";
          data.forEach(t => {
            html += `• <b>${t.Titulo}</b> (Entrega: ${t.Fecha_Entrega})<br>`;
          });
          body.innerHTML += `<div>${html}</div>`;
        }
      })
      .catch(() => {
        body.innerHTML += `<div><b>Bot:</b> Error al obtener tareas.</div>`;
      });
  }
});
</script>

</body>
</html>
