<?php
session_start();

// Guard y DB con rutas seguras
require_once __DIR__ . "/controller_guard_docente.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/model/db.php";

$idTarea      = (int)($_POST['Id_Tarea'] ?? 0);
$idCurso      = (int)($_POST['Id_Curso'] ?? 0);
$idEstudiante = (int)($_POST['Id_Estudiante'] ?? 0);

$nota       = isset($_POST['Calificacion']) ? (float)$_POST['Calificacion'] : null;
$comentario = trim($_POST['Comentario'] ?? '');

// NUEVO: puntos de ranking (1–10)
$puntosRanking = isset($_POST['Puntos_Ranking']) ? (int)$_POST['Puntos_Ranking'] : null;

if (
    $idTarea <= 0 || $idCurso <= 0 || $idEstudiante <= 0 ||
    $nota === null || $nota < 0 || $nota > 100 ||
    $puntosRanking === null || $puntosRanking < 1 || $puntosRanking > 10
) {
    http_response_code(400);
    exit("Datos inválidos");
}

// Verifica que el estudiante pertenece al curso
$chk = $pdo->prepare("SELECT 1 FROM aulavirtual.matricula WHERE Id_Estudiante=? AND Id_Curso=?");
$chk->execute([$idEstudiante, $idCurso]);
if (!$chk->fetch()) {
    http_response_code(403);
    exit("No autorizado");
}

// Verifica que exista la entrega (solo se evalúan entregas)
$sel = $pdo->prepare("SELECT Id_Entrega FROM aulavirtual.entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
$sel->execute([$idTarea, $idEstudiante]);
$entrega = $sel->fetch(PDO::FETCH_ASSOC);
if (!$entrega) {
    http_response_code(409);
    exit("No existe entrega para evaluar");
}

// Actualiza calificación, comentario y puntos de ranking
$upd = $pdo->prepare("
    UPDATE aulavirtual.entrega_tarea
    SET Calificacion = ?, Comentario = ?, Puntos_Ranking = ?
    WHERE Id_Entrega = ?
");
$upd->execute([$nota, $comentario, $puntosRanking, $entrega['Id_Entrega']]);

header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/EvaluarTarea.php?idTarea={$idTarea}&idCurso={$idCurso}&ok=1");
exit;
