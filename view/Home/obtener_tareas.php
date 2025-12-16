<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
header('Content-Type: application/json');


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Estudiante') {
    echo json_encode([]);
    exit;
}

$idEstudiante = $_SESSION['usuario']['id_usuario'];


$cn = new CN_BD();
$pdo = $cn->conectar();


$sql = "
SELECT 
    t.Titulo,
    t.Fecha_Entrega,
    c.Nombre AS Curso
FROM aulavirtual.tarea t
INNER JOIN aulavirtual.curso c 
    ON t.Id_Curso = c.Id_Curso
LEFT JOIN aulavirtual.entrega_tarea e 
    ON t.Id_Tarea = e.Id_Tarea
    AND e.Id_Estudiante = ?
WHERE e.Id_Entrega IS NULL
AND t.Fecha_Entrega >= CAST(GETDATE() AS DATE)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idEstudiante]);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tareas);
