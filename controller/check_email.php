<?php
require __DIR__ . '/../model/db.php';

header('Content-Type: application/json');

$email = strtolower(trim($_GET['email'] ?? ''));

if ($email === '') {
    echo json_encode(['exists' => false]);
    exit;
}

$pdo = (new CN_BD())->conectar();

$stmt = $pdo->prepare("
    SELECT 1
    FROM aulavirtual.usuario
    WHERE Email = ?
");
$stmt->execute([$email]);

echo json_encode([
    'exists' => (bool)$stmt->fetch()
]);
