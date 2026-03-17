<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";

// Justificaciones por estado
$sql = "SELECT estado, COUNT(*) AS total FROM aulavirtual.justificaciones GROUP BY estado";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$labels=[]; $data=[];
foreach($rows as $r){ $labels[]=$r['estado']; $data[]=(int)$r['total']; }

echo json_encode(['labels'=>$labels,'data'=>$data]);
?>