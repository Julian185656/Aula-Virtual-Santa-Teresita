<?php
require __DIR__ . '/../../controller/auth_admin.php';

$roles = ['Administrador', 'Docente', 'Estudiante'];
$estados = ['Activo', 'Inactivo'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo usuario</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.7;
            color: #c4c3ca;
            padding: 40px 15px;
            background-color: #2a2b38;
            background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/pat.svg');
            background-position: bottom center;
            background-repeat: no-repeat;
            background-size: 300%;
            overflow-x: hidden;
        }

        .card-container {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.4);
        }

        h1 {
            text-align: center;
            font-size: 1.7rem;
            margin-bottom: 25px;
            color: #fff;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-style {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            outline: none;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .form-style::placeholder {
            color: #ddd;
        }

        select.form-style option {
            background: #1f272b;
            color: #fff;
        }

        .feedback {
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .btn-custom {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            color: #fff;
            cursor: pointer;
            transition: 0.25s ease;
            font-size: 1rem;
        }

        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            transition: 0.25s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.35);
        }
    </style>
</head>

<body>

    <div class="card-container">
        <h1>Nuevo usuario</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message'];
                                            unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message'];
                                                unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <form action="admin_usuario_new_post.php" method="post">

            <div class="form-group">
                <input name="Nombre" class="form-style" placeholder="Nombre completo" required>
            </div>

            <div class="form-group">
                <input
                    id="email"
                    name="Email"
                    type="email"
                    class="form-style"
                    placeholder="Email institucional"
                    required>
                <small id="emailFeedback" class="feedback"></small>
            </div>

            <div class="form-group">
                <input
                    id="telefono"
                    name="Telefono"
                    class="form-style"
                    placeholder="Teléfono (8 dígitos)"
                    maxlength="8"
                    inputmode="numeric"
                    autocomplete="off">
                <small id="telefonoFeedback" class="feedback"></small>
            </div>


            <div class="form-group">
                <input
                    id="password"
                    name="Contrasena"
                    type="password"
                    class="form-style"
                    placeholder="Contraseña"
                    required>
                <small id="passwordFeedback" class="feedback"></small>
            </div>

            <div class="form-group">
                <select name="Rol" id="rol" class="form-style">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r ?>"><?= $r ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <select name="Estado" class="form-style">
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e ?>"><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-custom">Crear</button>
            <a href="admin_usuarios_list.php" class="btn-back">Volver</a>
        </form>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const emailFeedback = document.getElementById('emailFeedback');

        let emailTimeout = null;

        emailInput.addEventListener('input', () => {
            clearTimeout(emailTimeout);

            const email = emailInput.value.trim().toLowerCase();
            const regexInstitucional = /^[^@\s]+@santateresita\.ac\.cr$/i;

            if (email === '') {
                emailFeedback.textContent = '';
                return;
            }

            if (!regexInstitucional.test(email)) {
                emailFeedback.textContent = '✖ Debe usar @santateresita.ac.cr';
                emailFeedback.style.color = '#dc3545';
                return;
            }

            // Espera 400ms antes de consultar (evita demasiadas peticiones)
            emailTimeout = setTimeout(() => {
                fetch(`/Aula-Virtual-Santa-Teresita/controller/check_email.php?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            emailFeedback.textContent = '✖ El correo ya está registrado';
                            emailFeedback.style.color = '#dc3545';
                        } else {
                            emailFeedback.textContent = '✔ Correo disponible';
                            emailFeedback.style.color = '#28a745';
                        }
                    })
                    .catch(() => {
                        emailFeedback.textContent = '';
                    });
            }, 400);
        });

        /* ===============================
           CONTRASEÑA FUERTE
        =============================== */
        const passwordInput = document.getElementById('password');
        const passwordFeedback = document.getElementById('passwordFeedback');

        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;

            const lengthOK = value.length >= 8;
            const upperOK = /[A-Z]/.test(value);
            const numberOK = /\d/.test(value);
            const specialOK = /[^A-Za-z0-9]/.test(value);

            if (value === '') {
                passwordFeedback.textContent = '';
                return;
            }

            if (lengthOK && upperOK && numberOK && specialOK) {
                passwordFeedback.textContent = '✔ Contraseña fuerte';
                passwordFeedback.style.color = '#28a745';
            } else {
                passwordFeedback.innerHTML =
                    '✖ La contraseña debe tener:<br>' +
                    (lengthOK ? '✔' : '✖') + ' 8 caracteres<br>' +
                    (upperOK ? '✔' : '✖') + ' una mayúscula<br>' +
                    (numberOK ? '✔' : '✖') + ' un número<br>' +
                    (specialOK ? '✔' : '✖') + ' un carácter especial';
                passwordFeedback.style.color = '#dc3545';
            }
        });
        /* ===============================
   TELÉFONO (SOLO NÚMEROS, 8 DÍGITOS)
=============================== */
        const telefonoInput = document.getElementById('telefono');
        const telefonoFeedback = document.getElementById('telefonoFeedback');

        telefonoInput.addEventListener('input', () => {
            // Eliminar cualquier carácter que no sea número
            telefonoInput.value = telefonoInput.value.replace(/\D/g, '');

            const telefono = telefonoInput.value;

            if (telefono === '') {
                telefonoFeedback.textContent = '';
                return;
            }

            if (telefono.length === 8) {
                telefonoFeedback.textContent = '✔ Teléfono válido';
                telefonoFeedback.style.color = '#28a745';
            } else {
                telefonoFeedback.textContent = '✖ Debe contener exactamente 8 números';
                telefonoFeedback.style.color = '#dc3545';
            }
        });
        telefonoInput.addEventListener('paste', () => {
            setTimeout(() => {
                telefonoInput.value = telefonoInput.value.replace(/\D/g, '');
            }, 0);
        });
    </script>

</body>

</html>