<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../model/db.php';

$id_estudiante = $_GET['id_estudiante'] ?? null;

if (!$id_estudiante) {
    echo json_encode(['ok' => false, 'error' => 'Faltan datos']);
    exit;
}

try {

    $sql = "SELECT c.Id_Curso, c.Nombre
            FROM aulavirtual.matricula m
            JOIN aulavirtual.curso c ON m.Id_Curso = c.Id_Curso
            WHERE m.Id_Estudiante = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_estudiante]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['ok' => true, 'cursos' => $cursos]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Error de servidor: ' . $e->getMessage()]);
}
?>
