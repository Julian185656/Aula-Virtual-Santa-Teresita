<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";

// Promedio de calificaciones por curso
$sql = "SELECT c.Nombre, AVG(e.Calificacion) AS Promedio
        FROM aulavirtual.entrega_tarea e
        JOIN aulavirtual.tarea t ON t.Id_Tarea=e.Id_Tarea
        JOIN aulavirtual.curso c ON c.Id_Curso=t.Id_Curso
        GROUP BY c.Nombre";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$labels=[]; $data=[];
foreach($rows as $r){
    $labels[] = $r['Nombre'];
    $data[] = round($r['Promedio'],2);
}

echo json_encode(['labels'=>$labels,'data'=>$data]);
?>