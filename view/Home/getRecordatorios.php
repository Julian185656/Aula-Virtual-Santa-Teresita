<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Estudiante') {
    echo json_encode([]);
    exit;
}

$idEstudiante = $_SESSION['usuario']['id_usuario'];

$cn  = new CN_BD();
$pdo = $cn->conectar();

// Obtener tareas pendientes del estudiante dentro de los próximos 3 días
$sql = "
SELECT 
    t.Id_Tarea,
    t.Titulo,
    t.Descripcion,
    t.Fecha_Entrega,
    c.Nombre AS Curso
FROM aulavirtual.tarea t
INNER JOIN aulavirtual.curso c ON t.Id_Curso = c.Id_Curso
LEFT JOIN aulavirtual.entrega_tarea e 
    ON t.Id_Tarea = e.Id_Tarea 
    AND e.Id_Estudiante = :idUsuario
WHERE e.Id_Entrega IS NULL
  AND t.Fecha_Entrega >= CAST(GETDATE() AS DATE)
  AND t.Fecha_Entrega <= DATEADD(DAY, 7, GETDATE())
ORDER BY t.Fecha_Entrega ASC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idUsuario', $idEstudiante, PDO::PARAM_INT);
$stmt->execute();

$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tareas);
