<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";

// Asistencias por curso (porcentaje)
$sql = "SELECT c.Nombre, 
        SUM(CASE WHEN a.Presente=1 THEN 1 ELSE 0 END) AS Presentes, 
        COUNT(*) AS Total
        FROM aulavirtual.asistencia a
        JOIN aulavirtual.curso c ON c.Id_Curso=a.Id_Curso
        GROUP BY c.Nombre";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$labels=[]; $data=[];
foreach($rows as $r){
    $labels[] = $r['Nombre'];
    $data[] = $r['Total']>0 ? round($r['Presentes']/$r['Total']*100,2) : 0;
}

echo json_encode(['labels'=>$labels,'data'=>$data]);
?>