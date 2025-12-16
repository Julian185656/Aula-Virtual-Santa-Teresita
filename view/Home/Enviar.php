<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
require_once __DIR__ . '/../../controller/EmailHelper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
    echo json_encode(['ok'=>false,'error'=>'No autorizado']);
    exit;
}

$idTarea = $_GET['id_tarea'] ?? null;

if (!$idTarea) {
    echo json_encode(['ok'=>false,'error'=>'No se ha seleccionado una tarea']);
    exit;
}

try {
    $cn = new CN_BD();
    $pdo = $cn->conectar();

    // Obtener estudiantes pendientes de la tarea
    $sql = "
    SELECT u.Nombre, u.Email
    FROM aulavirtual.matricula m
    INNER JOIN aulavirtual.usuario u
        ON m.Id_Estudiante = u.Id_Usuario
    LEFT JOIN aulavirtual.entrega_tarea e
        ON e.Id_Estudiante = m.Id_Estudiante
        AND e.Id_Tarea = ?
    WHERE m.Id_Curso = (SELECT Id_Curso FROM aulavirtual.tarea WHERE Id_Tarea = ?)
    AND e.Id_Entrega IS NULL
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idTarea, $idTarea]);
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $enviados = 0;
    foreach ($pendientes as $p) {
        $asunto = "⏰ Recordatorio de tarea pendiente";
        $contenido = "<h3>Hola {$p['Nombre']}</h3>
        <p>Tienes pendiente la entrega de la tarea.</p>";

        if (EnviarCorreo($asunto, $contenido, $p['Email'])) {
            $enviados++;
        }
    }

    echo json_encode([
        'ok' => true,
        'total_pendientes' => count($pendientes),
        'enviados' => $enviados
    ]);

} catch (Exception $e) {
    echo json_encode(['ok' => false,'error' => 'Error en el servidor: '.$e->getMessage()]);
}
