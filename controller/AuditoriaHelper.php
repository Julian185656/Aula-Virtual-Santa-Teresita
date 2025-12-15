<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/CN_BD.php';

/**
 * Registra un evento en la tabla de auditoría.
 *
 * @param string $evento      Tipo de evento (LOGIN_EXITOSO, LOGIN_FALLIDO, LOGOUT, etc.)
 * @param string $modulo      Módulo del sistema (Autenticación, Usuarios, Cursos, etc.)
 * @param string $descripcion Descripción del evento
 * @param string $resultado   Resultado del evento (Éxito, Fallido, Error)
 */
function registrarAuditoria(
    string $evento,
    string $modulo,
    string $descripcion,
    string $resultado = 'Éxito'
): void {
    try {
        $pdo = (new CN_BD())->conectar();

        $stmt = $pdo->prepare("
            INSERT INTO aulavirtual.auditoria_eventos
                (Id_Usuario, Correo, Rol, Evento, Modulo, Descripcion, Resultado, Ip_Origen)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['usuario']['id_usuario'] ?? null,
            $_SESSION['usuario']['correo'] ?? null,
            $_SESSION['usuario']['rol'] ?? 'Invitado',
            $evento,
            $modulo,
            $descripcion,
            $resultado,
            obtenerIPReal()
        ]);
    } catch (Throwable $e) {
        // La auditoría nunca debe romper el sistema
        error_log('ERROR AUDITORIA: ' . $e->getMessage());
    }
}

/**
 * Obtiene la IP real del cliente.
 *
 * @return string
 */
function obtenerIPReal(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    return $_SERVER['REMOTE_ADDR'] ?? 'DESCONOCIDA';
}
