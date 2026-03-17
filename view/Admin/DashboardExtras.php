<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/Aula-Virtual-Santa-Teresita/model/db.php";

// 🔹 Total de estudiantes
$totalEstudiantes = $pdo->query("SELECT COUNT(*) AS total FROM aulavirtual.usuario WHERE Rol='Estudiante'")->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 Total de docentes
$totalDocentes = $pdo->query("SELECT COUNT(*) AS total FROM aulavirtual.usuario WHERE Rol='Docente'")->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 Tareas pendientes (entregas no calificadas)
$tareasPendientes = $pdo->query("SELECT COUNT(*) AS total FROM aulavirtual.entrega_tarea WHERE Calificacion IS NULL")->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 Ausencias sin justificar
$ausenciasSinJustificar = $pdo->query("SELECT COUNT(*) AS total FROM aulavirtual.justificaciones WHERE estado IS NULL OR estado=''")->fetch(PDO::FETCH_ASSOC)['total'];

// 🔹 Datos para dispersión: asistencia vs calificación
$sql = "SELECT a.Id_Estudiante, 
               SUM(CASE WHEN a.Presente=1 THEN 1 ELSE 0 END)*100.0/COUNT(*) AS porcentajeAsistencia,
               AVG(e.Calificacion) AS promedioCalificacion
        FROM aulavirtual.asistencia a
        LEFT JOIN aulavirtual.entrega_tarea e ON e.Id_Estudiante=a.Id_Estudiante
        GROUP BY a.Id_Estudiante";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$scatterData = [];
foreach($rows as $r){
    if($r['promedioCalificacion'] !== null){
        $scatterData[] = [
            'x' => round($r['porcentajeAsistencia'],2),
            'y' => round($r['promedioCalificacion'],2)
        ];
    }
}

// Enviar JSON
echo json_encode([
    'totalEstudiantes' => (int)$totalEstudiantes,
    'totalDocentes' => (int)$totalDocentes,
    'tareasPendientes' => (int)$tareasPendientes,
    'ausenciasSinJustificar' => (int)$ausenciasSinJustificar,
    'scatterData' => $scatterData
]);
?>