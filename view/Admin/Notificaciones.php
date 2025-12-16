<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/NotificacionModel.php";

$rol = $_SESSION['rol'] ?? '';
if ($rol !== 'Administrador') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
    exit();
}

$model = new NotificacionModel($pdo);
$notificaciones = $model->obtenerHistorial();
$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Gestión de Notificaciones</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ------------------------------ */
/* 🔥 ESTILO GLOBAL TIPO "USUARIOS" */
/* ------------------------------ */

body {
    font-family: 'Poppins', sans-serif;
    font-weight: 300;
    font-size: 15px;
    color: #fff;
    padding: 40px 20px;
    background-color: #2a2b38;
    background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
    background-repeat: repeat;
    background-size: 600px;
    background-position: center top;
    overflow-x: hidden;
}

h2 {
    text-align:center;
    font-weight:700;
    margin-bottom:30px;
    text-shadow:0 2px 10px rgba(0,0,0,0.4);
}

/* ------------------------------ */
/* 🔹 BOTÓN VOLVER */
/* ------------------------------ */

.btn-volver {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 18px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.1);
    color: #fff;
    text-decoration: none;
    transition: .2s;
}
.btn-volver:hover {
    background: rgba(255,255,255,0.25);
}

/* ------------------------------ */
/* 💎 TARJETAS GLASS */
/* ------------------------------ */
.card-glass,
.card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    color: #fff;
}

/* ------------------------------ */
/* 📝 FORMULARIO */
/* ------------------------------ */

.form-control, textarea {
    border-radius: 12px;
    padding: 12px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
}

.form-control::placeholder {
    color: #ccc;
}

/* BOTÓN PRINCIPAL */
.btn-primary {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    background: #ffffff;
    color: #1f272b;
    font-weight: 700;
    border: none;
}
.btn-primary:hover {
    background: #e6e6e6;
}

/* ------------------------------ */
/* 📊 TABLA */
/* ------------------------------ */

.table-container {
    max-height: 420px;
    overflow-y: auto;
    margin-top: 20px;
    border-radius: 16px;
}

table {
    width: 100%;
    color: white;
}

thead {
    background: rgba(255,255,255,0.15);
    position: sticky;
    top: 0;
}

tbody tr:nth-child(even) {
    background: rgba(255,255,255,0.04);
}

tbody tr:hover {
    background: rgba(255,255,255,0.12);
}

.fila-pendiente {
    background: rgba(255, 182, 72, 0.2) !important;
}

.badge-success { background: #28c76f; }
.badge-secondary { background: #6c757d; }

/* Botones tabla */
.btn-outline-primary {
    border-color: #ff9f43;
    color: #ff9f43;
}
.btn-outline-primary:hover {
    background: #ff9f43;
    color:#fff;
}

.btn-outline-danger {
    border-color: #ff4c4c;
    color: #ff4c4c;
}
.btn-outline-danger:hover {
    background: #ff4c4c;
    color:#fff;
}

</style>
</head>
<body>

<!-- BOTÓN VOLVER -->
<a href="/Aula-Virtual-Santa-Teresita/view/Home/Home.php" class="btn-volver">
    <i class="fa fa-arrow-left"></i> Volver
</a>

<h2>Gestión de Notificaciones</h2>

<div class="container" style="max-width: 1100px;">

    <!-- MENSAJES -->
    <?php if ($msg): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- CREAR NOTIFICACIÓN -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Crear nueva notificación</h5>

            <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php">
                <input type="hidden" name="accion" value="crear">

                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label>Asunto</label>
                        <input type="text" name="asunto" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Destinatario</label>
                        <input type="email" name="destinatario" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Fecha de envío</label>
                        <input type="date" name="fecha_envio" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Hora de envío</label>
                        <input type="time" name="hora_envio" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label>Mensaje</label>
                        <textarea name="mensaje" rows="4" class="form-control" required></textarea>
                    </div>

                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fa fa-paper-plane"></i> Guardar Notificación
                </button>
            </form>
        </div>
    </div>

    <!-- HISTORIAL -->
    <div class="card-glass p-4">
        <h5 class="fw-bold mb-3">Historial de Notificaciones</h5>

        <?php if (empty($notificaciones)): ?>
            <div class="alert alert-warning text-center">No hay notificaciones registradas.</div>
        <?php else: ?>

        <div class="table-container">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Asunto</th>
                        <th>Destinatario</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($notificaciones as $n): ?>
                    <tr class="<?= $n['Estado'] === 'Pendiente' ? 'fila-pendiente' : '' ?>">
                        <td><?= htmlspecialchars($n['Asunto']) ?></td>
                        <td><?= htmlspecialchars($n['Destinatario']) ?></td>
                        <td><?= htmlspecialchars($n['Fecha_Envio']) ?></td>
                        <td><?= htmlspecialchars($n['Hora_Envio']) ?></td>

                        <td>
                            <?php if ($n['Estado'] === 'Enviada'): ?>
                                <span class="badge badge-success">Enviada</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Pendiente</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($n['Estado'] === 'Pendiente'): ?>
                                <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                                    <input type="hidden" name="accion" value="enviar_inmediato">
                                    <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <form method="POST" action="/Aula-Virtual-Santa-Teresita/controller/NotificacionController.php" class="d-inline">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $n['Id_Notificacion'] ?>">
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

        <?php endif; ?>
    </div>
</div>

</body>
</html>
