<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../model/db.php';


$id_docente = $_SESSION['id_usuario'] ?? null;
if (!$id_docente) {
    echo json_encode(['ok'=>false, 'error'=>'No tienes permiso']);
    exit;
}


$sql = "
    SELECT j.id, j.id_estudiante, j.fecha_ausencia, j.comprobante, j.estado, u.Nombre, u.Email
    FROM aulavirtual.justificaciones j
    JOIN aulavirtual.usuario u ON u.Id_Usuario = j.id_estudiante
    WHERE j.id_curso IN (
        SELECT Id_Curso
        FROM aulavirtual.curso_docente
        WHERE Id_Docente = ?
    )
    ORDER BY j.fecha_solicitud DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_docente]);

$justificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['ok'=>true, 'justificaciones'=>$justificaciones]);
?>
