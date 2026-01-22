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

          <!-- ✅ NUEVO APARTADO -->
          <div class="col-md-6 col-lg-3">
            <div class="role-card">
              <i class="fa fa-file-circle-check"></i>
              <h4>Solicitudes de Justificación</h4>
              <p>Revisa comprobantes enviados por estudiantes. Aprueba o deniega.</p>
              <a href="/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Solicitudes/SolicitudesJustificacionController.php">Ir</a>
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

<?php
// ===== MOSTRAR SOLO A DOCENTE / ESTUDIANTE =====
$rolChat = $_SESSION['usuario']['Rol'] ?? $_SESSION['usuario']['rol'] ?? ($_SESSION['rol'] ?? '');
$rolChat = strtolower(trim((string)$rolChat));

$idUsuarioChat = $_SESSION['usuario']['Id_Usuario'] ?? $_SESSION['usuario']['id_usuario'] ?? ($_SESSION['id_usuario'] ?? '');
$idUsuarioChat = (string)$idUsuarioChat;

$mostrarChat = in_array($rolChat, ['docente', 'estudiante'], true);
?>

<?php if ($mostrarChat): ?>

<!-- BOTÓN FLOTANTE -->
<button id="chatbot-btn" type="button" aria-label="Abrir asistente">
  <i class="fa-solid fa-comments"></i>
</button>

<!-- VENTANA CHATBOT -->
<div id="chatbot-window" aria-hidden="true">
  <div id="chatbot-header">
    <span class="cb-title"><i class="fa-solid fa-robot"></i> Asistente Virtual</span>
    <button id="chatbot-close" type="button" aria-label="Cerrar">&times;</button>
  </div>

  <div id="chatbot-body"></div>

  <div id="chatbot-footer">
    <input type="text" id="chatbot-input" placeholder="Escribe tu mensaje..." autocomplete="off">
    <button id="chatbot-send" type="button" aria-label="Enviar">
      <i class="fa-solid fa-paper-plane"></i>
    </button>
  </div>
</div>

<style>
/* Botón flotante */
#chatbot-btn{
  position:fixed;
  right:22px;
  bottom:22px;
  width:62px;
  height:62px;
  border-radius:50%;
  border:1px solid rgba(255,255,255,.22);
  background:rgba(255,255,255,.12);
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  z-index:9999;
  box-shadow:0 12px 30px rgba(0,0,0,.35);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  transition: transform .18s, background .18s, border-color .18s;
}
#chatbot-btn:hover{
  transform: translateY(-2px) scale(1.05);
  background:rgba(255,255,255,.18);
  border-color:rgba(255,255,255,.30);
}
#chatbot-btn i{ font-size:22px; }

/* Ventana */
#chatbot-window{
  position:fixed;
  right:22px;
  bottom:96px;
  width:380px;
  max-width: calc(100vw - 44px);
  max-height:520px;
  display:none; /* ✅ oculto por defecto */
  flex-direction:column;
  overflow:hidden;
  border-radius:16px;
  border:1px solid rgba(255,255,255,.18);
  background:rgba(18,19,28,.72);
  color:#fff;
  box-shadow:0 18px 44px rgba(0,0,0,.45);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  z-index:9999;
}

/* Header */
#chatbot-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:12px 14px;
  background:rgba(255,255,255,.10);
  border-bottom:1px solid rgba(255,255,255,.14);
  font-weight:800;
}
#chatbot-header .cb-title{ display:flex; align-items:center; gap:8px; }
#chatbot-close{
  background:transparent;
  border:none;
  color:#fff;
  font-size:22px;
  cursor:pointer;
  line-height:1;
  opacity:.9;
}
#chatbot-close:hover{ opacity:1; }

/* Body */
#chatbot-body{
  padding:12px;
  overflow-y:auto;
  flex:1;
  font-size:14px;
}

/* Burbujas */
.cb-msg{ margin:10px 0; display:flex; }
.cb-msg.user{ justify-content:flex-end; }
.cb-bubble{
  max-width: 88%;
  padding:10px 12px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.14);
  background:rgba(255,255,255,.10);
  line-height:1.35;
  word-break: break-word;
}
.cb-msg.user .cb-bubble{
  background:rgba(13,110,253,.22);
  border-color:rgba(13,110,253,.35);
}

/* UI dentro del chat */
.cb-small{ font-size:12px; opacity:.85; }
.cb-divider{ height:1px; background:rgba(255,255,255,.12); margin:10px 0; }
.cb-chip{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid rgba(255,255,255,.16);
  background:rgba(255,255,255,.08);
  margin:4px 6px 0 0;
  font-size:12px;
  cursor:pointer;
  color:#fff;
  text-decoration:none;
}
.cb-chip:hover{ background:rgba(255,255,255,.14); }

.cb-btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:7px 10px;
  border-radius:12px;
  border:1px solid rgba(255,255,255,.18);
  background:rgba(255,255,255,.10);
  color:#fff;
  cursor:pointer;
  font-size:12px;
  font-weight:800;
  margin-top:6px;
  transition:.15s;
}
.cb-btn:hover{ background:rgba(255,255,255,.18); }
.cb-btn.primary{ background:rgba(13,110,253,.35); border-color:rgba(13,110,253,.35); }
.cb-btn.primary:hover{ background:rgba(13,110,253,.45); }

.cb-pill{
  display:inline-block;
  padding:2px 8px;
  border-radius:999px;
  border:1px solid rgba(255,255,255,.18);
  background:rgba(255,255,255,.08);
  font-size:12px;
  margin-left:6px;
}

/* Footer */
#chatbot-footer{
  display:flex;
  gap:8px;
  padding:10px;
  border-top:1px solid rgba(255,255,255,.14);
  background:rgba(255,255,255,.06);
}
#chatbot-input{
  flex:1;
  padding:10px 10px;
  border-radius:12px;
  border:1px solid rgba(255,255,255,.18);
  outline:none;
  background:rgba(255,255,255,.10);
  color:#fff;
}
#chatbot-input::placeholder{ color:rgba(255,255,255,.65); }

#chatbot-send{
  width:44px;
  border-radius:12px;
  border:1px solid rgba(255,255,255,.18);
  background:rgba(255,255,255,.12);
  color:#fff;
  cursor:pointer;
  transition:.18s;
}
#chatbot-send:hover{ background:rgba(255,255,255,.20); }

@media (max-width: 420px){
  #chatbot-window{ width: calc(100vw - 44px); }
}

/* ✅ FIX: opciones del select visibles (dropdown) */
#chatbot-window select option,
#chatbot-window datalist option {
  background: #2a2b38 !important;
  color: #ffffff !important;
}

/* opcional: el placeholder un poco más gris */
#chatbot-window select option[value=""] {
  color: rgba(255,255,255,.75) !important;
}

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const chatBtn    = document.getElementById("chatbot-btn");
  const chatWindow = document.getElementById("chatbot-window");
  const chatClose  = document.getElementById("chatbot-close");
  const chatBody   = document.getElementById("chatbot-body");
  const chatInput  = document.getElementById("chatbot-input");
  const chatSend   = document.getElementById("chatbot-send");

  const rol = <?= json_encode($rolChat) ?>;      // "docente" | "estudiante"
  const idUsuario = <?= json_encode($idUsuarioChat) ?>;

  function addMsg(tipo, html){
    const wrap = document.createElement("div");
    wrap.className = "cb-msg " + (tipo === "user" ? "user" : "bot");

    const bubble = document.createElement("div");
    bubble.className = "cb-bubble";
    bubble.innerHTML = html;

    wrap.appendChild(bubble);
    chatBody.appendChild(wrap);
    chatBody.scrollTop = chatBody.scrollHeight;
  }

  function abrir(){
    chatWindow.style.display = "flex";
    chatWindow.setAttribute("aria-hidden","false");
    if (!chatBody.dataset.init){
      chatBody.dataset.init = "1";
      mensajeInicial();
    }
    chatInput.focus();
  }
  function cerrar(){
    chatWindow.style.display = "none";
    chatWindow.setAttribute("aria-hidden","true");
  }

  chatBtn.addEventListener("click", abrir);
  chatClose.addEventListener("click", cerrar);

  document.addEventListener("keydown", (e) => {
    if(e.key === "Escape") cerrar();
  });

  function mensajeInicial(){
    let msg = "Hola 👋<br><br><b>Escribe:</b><br>";
    if(rol === "docente"){
      msg += `• <b>cursos</b> <span class="cb-small">(ver cursos + tareas + recordatorios)</span><br>`;
      msg += `• <b>asistencia</b><br>`;
    }else{
      msg += `• <b>tareas</b> o <b>pendientes</b><br>`;
      msg += `• <b>justificacion</b><br>`;
    }
    addMsg("bot", msg);
  }

  function enviar(){
    const text = (chatInput.value || "").trim();
    if(!text) return;
    addMsg("user", escapeHtml(text));
    chatInput.value = "";
    manejar(text.toLowerCase());
  }

  chatSend.addEventListener("click", enviar);
  chatInput.addEventListener("keydown", (e) => {
    if(e.key === "Enter"){
      e.preventDefault();
      enviar();
    }
  });

  function manejar(txt){
    // DOCENTE: NO redirige al escribir "cursos" (muestra lista como antes)
    if(rol === "docente"){
      if(txt.includes("curso")){
        mostrarCursosYTareas();
        return;
      }
      if(txt.includes("asistencia")){
        addMsg("bot","Abriendo asistencia...");
        window.location.href = "/Aula-Virtual-Santa-Teresita/view/Docente/Asistencia/Registrar/RegistrarAsistenciaController.php";
        return;
      }
      addMsg("bot","Prueba con <b>cursos</b> o <b>asistencia</b> 🙂");
      return;
    }

    // ESTUDIANTE
    if(rol === "estudiante"){
      if(txt.includes("tarea") || txt.includes("pendiente")){
        obtenerTareasPendientes();
        return;
      }
      if(txt.includes("justificacion") || txt.includes("justificación")){
        manejarJustificacion();
        return;
      }
      addMsg("bot","Prueba con <b>tareas</b>, <b>pendientes</b> o <b>justificacion</b> 🙂");
      return;
    }
  }

  // =========================
  // DOCENTE: cursos + tareas + recordatorios
  // Endpoint esperado: /view/Home/obtener_cursos_tareas.php  => { ok:true, cursos:[{ nombre, tareas:[{id,titulo,pendientes}] }] }
  // Recordatorio: /view/Home/Enviar.php?id_tarea=XX => { ok:true, enviados: N }
  // =========================
  function mostrarCursosYTareas(){
    addMsg("bot","Cargando tus cursos y tareas...");

    fetch("/Aula-Virtual-Santa-Teresita/view/Home/obtener_cursos_tareas.php")
      .then(r => r.json())
      .then(data => {
        if(!data || data.ok === false){
          addMsg("bot","❌ No pude cargar cursos/tareas.");
          return;
        }

        const cursos = data.cursos || [];
        if(cursos.length === 0){
          addMsg("bot","No tienes cursos asignados 😢");
          return;
        }

        let html = `<b>Tus cursos y tareas:</b><div class="cb-divider"></div>`;

        cursos.forEach(c => {
          const nombreCurso = escapeHtml(c.nombre || c.Nombre || "Curso");
          html += `<div style="margin-bottom:10px;">
                    <div><b>${nombreCurso}</b></div>`;

          const tareas = (c.tareas || c.Tareas || []);
          if(!tareas.length){
            html += `<div class="cb-small">Sin tareas registradas.</div>`;
          }else{
            tareas.forEach(t => {
              const id = t.id ?? t.Id_Tarea ?? t.Id ?? null;
              const titulo = escapeHtml(t.titulo || t.Titulo || "Tarea");
              const pendientes = Number(t.pendientes ?? t.Pendientes ?? 0);

              html += `<div class="cb-small" style="margin-top:6px;">
                        • ${titulo}
                        <span class="cb-pill">⏰ ${pendientes} pendientes</span>
                      </div>`;

              if(id && pendientes > 0){
                html += `<button class="cb-btn primary"
                              data-action="recordatorio"
                              data-id-tarea="${String(id)}">
                          Enviar recordatorio
                        </button>`;
              }else{
                html += `<div class="cb-small">🎉 Todos entregaron</div>`;
              }
            });
          }

          html += `</div>`;
        });

        addMsg("bot", html);
      })
      .catch(() => addMsg("bot","❌ Error de conexión al cargar cursos/tareas."));
  }

  // Click en botones dentro del chat (delegado)
  chatBody.addEventListener("click", function(e){
    const btn = e.target.closest("button");
    if(!btn) return;

    const action = btn.dataset.action;
    if(action === "recordatorio"){
      const idTarea = btn.dataset.idTarea;
      if(!idTarea) return;

      btn.disabled = true;
      btn.textContent = "Enviando...";

      fetch(`/Aula-Virtual-Santa-Teresita/view/Home/Enviar.php?id_tarea=${encodeURIComponent(idTarea)}`)
        .then(r => r.json())
        .then(data => {
          if(data && data.ok){
            addMsg("bot", `📧 Recordatorios enviados: <b>${escapeHtml(String(data.enviados ?? 0))}</b>`);
          }else{
            addMsg("bot", "❌ No se pudieron enviar los recordatorios.");
          }
        })
        .catch(() => addMsg("bot","❌ Error de conexión al enviar recordatorios."))
        .finally(() => {
          btn.disabled = false;
          btn.textContent = "Enviar recordatorio";
        });
    }
  });

  // =========================
  // ESTUDIANTE: tareas pendientes
  // Endpoint: /view/Home/obtener_tareas.php => array
  // =========================
  function obtenerTareasPendientes(){
    addMsg("bot","Buscando tareas pendientes...");

    fetch("/Aula-Virtual-Santa-Teresita/view/Home/obtener_tareas.php")
      .then(r => r.json())
      .then(data => {
        if (!data || !Array.isArray(data) || data.length === 0) {
          addMsg("bot","No tienes tareas pendientes 🎉");
          return;
        }
        let html = "<b>Tus tareas pendientes:</b><div class='cb-divider'></div>";
        data.forEach(t => {
          html += `• <b>${escapeHtml(t.Titulo ?? t.titulo ?? "Tarea")}</b>
                  <span class="cb-pill">Entrega: ${escapeHtml(t.Fecha_Entrega ?? t.fecha ?? "-")}</span><br>`;
        });
        addMsg("bot", html);
      })
      .catch(() => addMsg("bot","❌ Error al obtener tareas."));
  }

  // =========================
  // ESTUDIANTE: justificación
  // Endpoints:
  //  - /view/Home/obtener_cursos.php?id_estudiante=ID => { ok:true, cursos:[{Id_Curso,Nombre}] }
  //  - /view/Home/enviar_justificacion.php POST(FormData) => { ok:true, mensaje:"..." }
  // =========================
  function manejarJustificacion(){
    addMsg("bot","Ok ✅ Vamos a enviar una justificación. Cargando cursos...");

    fetch(`/Aula-Virtual-Santa-Teresita/view/Home/obtener_cursos.php?id_estudiante=${encodeURIComponent(idUsuario)}`)
      .then(res => res.json())
      .then(data => {
        if(!data || !data.ok){
          addMsg("bot","❌ No pude cargar cursos.");
          return;
        }

        const cursos = data.cursos || [];
        let opciones = '<option value="">--Selecciona un curso--</option>';
        cursos.forEach(c => {
          opciones += `<option value="${escapeAttr(String(c.Id_Curso))}">${escapeHtml(c.Nombre)}</option>`;
        });

        addMsg("bot", `
          <b>Formulario de justificación</b>
          <div class="cb-small">Completa y envía:</div>

          <form id="justificacion-form" style="margin-top:8px;">
            <div style="display:grid; gap:8px;">
              <select name="id_curso" required
                style="padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.10);color:#fff;">
                ${opciones}
              </select>

              <input type="date" name="fecha_ausencia" required
                style="padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.10);color:#fff;">

              <input type="file" name="comprobante" required
                style="padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.10);color:#fff;">

              <button type="submit" class="cb-btn primary">Enviar justificación</button>
            </div>
          </form>
        `);

        // limitar fecha: no futuro
        const form = document.getElementById("justificacion-form");
        const inputDate = form.querySelector('input[type="date"]');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        inputDate.max = now.toISOString().split("T")[0];
      })
      .catch(() => addMsg("bot","❌ Error de conexión al cargar cursos."));
  }

  // Enviar form de justificación (delegado)
  chatBody.addEventListener("submit", function(e){
    const form = e.target;
    if(form.id !== "justificacion-form") return;

    e.preventDefault();
    const formData = new FormData(form);
    formData.append("id_estudiante", idUsuario);

    addMsg("bot","Enviando justificación...");

    fetch("/Aula-Virtual-Santa-Teresita/view/Home/enviar_justificacion.php", {
      method:"POST",
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      if(data && data.ok) addMsg("bot", "✅ " + escapeHtml(data.mensaje || "Justificación enviada."));
      else addMsg("bot", "❌ " + escapeHtml(data.error || "No se pudo enviar."));
      form.remove();
    })
    .catch(() => addMsg("bot","❌ Error de conexión al enviar."));
  });

  // Helpers: evitar inyectar HTML raro desde inputs
  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, (m) => ({
      "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
    }[m]));
  }
  function escapeAttr(str){
    return String(str).replace(/"/g, "&quot;");
  }
});
</script>

<?php endif; ?>
</body>
</html>
