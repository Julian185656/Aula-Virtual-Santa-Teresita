<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";

// Roles
$sqlRoles = "SELECT Rol, COUNT(*) AS Total FROM aulavirtual.usuario GROUP BY Rol";
$roles = $pdo->query($sqlRoles)->fetchAll(PDO::FETCH_ASSOC);
$rolesLabels = $rolesData = [];
foreach($roles as $r){ $rolesLabels[] = $r['Rol']; $rolesData[] = (int)$r['Total']; }

// Estado
$sqlEstado = "SELECT Estado, COUNT(*) AS Total FROM aulavirtual.usuario GROUP BY Estado";
$estado = $pdo->query($sqlEstado)->fetchAll(PDO::FETCH_ASSOC);
$estadoLabels = $estadoData = [];
foreach($estado as $e){ $estadoLabels[] = $e['Estado']; $estadoData[] = (int)$e['Total']; }

echo json_encode([
    'rolesLabels'=>$rolesLabels,
    'rolesData'=>$rolesData,
    'estadoLabels'=>$estadoLabels,
    'estadoData'=>$estadoData
]);
?>