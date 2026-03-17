<?php
// getUsuariosDashboard.php
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";

// 1️⃣ Total por rol
$sqlRoles = "SELECT Rol, COUNT(*) AS Total FROM aulavirtual.usuario GROUP BY Rol";
$stmt = $pdo->query($sqlRoles);
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rolesLabels = [];
$rolesData = [];
foreach($roles as $r){
    $rolesLabels[] = $r['Rol'];
    $rolesData[] = (int)$r['Total'];
}

// 2️⃣ Total por estado
$sqlEstado = "SELECT Estado, COUNT(*) AS Total FROM aulavirtual.usuario GROUP BY Estado";
$stmt2 = $pdo->query($sqlEstado);
$estado = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$estadoLabels = [];
$estadoData = [];
foreach($estado as $e){
    $estadoLabels[] = $e['Estado'];
    $estadoData[] = (int)$e['Total'];
}

// Enviamos JSON
echo json_encode([
    'rolesLabels' => $rolesLabels,
    'rolesData' => $rolesData,
    'estadoLabels' => $estadoLabels,
    'estadoData' => $estadoData
]);
?>