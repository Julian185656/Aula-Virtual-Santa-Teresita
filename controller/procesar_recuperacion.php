<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php';
include_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/LoginModel.php";



if (!empty($_POST['correo'])) {
    $correo = $_POST['correo'];
    $codigo = GenerarCodigo();

    $resultado = recuperarContrasenna($correo);

    if ($resultado) {

        $resultadoCambio = cambiarContrasenna($correo, $codigo);

        if (  $resultadoCambio ){
            $contenido = '
                    <html>
                    <head>
                        <style>
                        .correo-container {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            background-color: #f4f6f8;
                            border-radius: 10px;
                            max-width: 500px;
                            margin: auto;
                            color: #333;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        }
                        .logo {
                            display: block;
                            margin: 0 auto 20px auto;
                            width: 120px;
                            height: auto;
                        }
                        .codigo {
                            font-size: 24px;
                            font-weight: bold;
                            color:rgb(79, 131, 221);
                            text-align: center;
                            margin: 20px 0;
                        }
                        .mensaje {
                            font-size: 16px;
                            line-height: 1.6;
                            text-align: center;
                        }
                        </style>
                    </head>
                    <body>
                        <div class="correo-container">
                        <img src="https://scontent.fsyq8-1.fna.fbcdn.net/v/t39.30808-1/432980083_943947464405571_1899512884430500918_n.jpg?stp=c19.19.410.410a_dst-jpg_s200x200_tt6&_nc_cat=100&ccb=1-7&_nc_sid=2d3e12&_nc_ohc=p87iCyYBe0oQ7kNvwEEX-ux&_nc_oc=AdluAkvZfwBRY1SSQSkgtqxoFBgq1eQUISJByzfSk-YQX6SurCwYLCCwp-gBT44RSZU&_nc_zt=24&_nc_ht=scontent.fsyq8-1.fna&_nc_gid=ge65gC25F81zk4qVbSBDmw&oh=00_AfGVSz9nAahiNfWo_9kWngX6-bWpaEzpjSFAnDxP1RT0bg&oe=680B67E6" alt="Logo Importadora Mora" class="logo" />
                        <div class="mensaje">
                            <p>Hola,</p>
                            <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                            <p>Utiliza el siguiente código de seguridad para continuar con el proceso:</p>
                            <div class="codigo">' . $codigo . '</div>
                            <p>Recuerda realizar el cambio de contraseña una vez que ingreses al sistema.</p>
                            <p>Si no realizaste esta solicitud, puedes ignorar este mensaje.</p>
                            <br>
                            <p>Atentamente,<br><b>Grupo Mora S.A</b></p>
                        </div>
                        </div>
                    </body>
                    </html>';


            $resultadoCorreo = EnviarCorreo("Recuperar Contraseña", $contenido, $correo);

            if ($resultadoCorreo) {
                echo "<div class='alert alert-success'>Revisa tu correo para recuperar tu contraseña.</div>";
            } else {
                echo "<div class='alert alert-danger'>No se pudo enviar el correo.</div>";
            }
        }else{
            echo "<div class='alert alert-warning'>No se pudo cambiar la contraseña.</div>";
        }

    } else {
        echo "<div class='alert alert-warning'>Correo no registrado.</div>";
    }
}

function GenerarCodigo() {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    for ($i = 0; $i < 6; $i++) {
        $codigo .= $alphabet[rand(0, strlen($alphabet) - 1)];
    }
    return $codigo;
}