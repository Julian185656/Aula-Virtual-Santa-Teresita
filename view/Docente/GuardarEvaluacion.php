<?php
session_start();

require_once __DIR__ . "/controller_guard_docente.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/model/db.php";

$idTarea      = (int)($_POST['Id_Tarea'] ?? 0);
$idCurso      = (int)($_POST['Id_Curso'] ?? 0);
$idEstudiante = (int)($_POST['Id_Estudiante'] ?? 0);
$nota         = isset($_POST['Calificacion']) ? (float)$_POST['Calificacion'] : null;
$comentario   = trim($_POST['Comentario'] ?? '');

if ($idTarea<=0 || $idCurso<=0 || $idEstudiante<=0 || $nota===null || $nota<0 || $nota>100) {
  http_response_code(400);
  exit("Datos inválidos");
}

$chk = $pdo->prepare("SELECT 1 FROM matricula WHERE Id_Estudiante=? AND Id_Curso=?");
$chk->execute([$idEstudiante, $idCurso]);
if (!$chk->fetch()) {
  http_response_code(403);
  exit("No autorizado");
}

$sel = $pdo->prepare("SELECT Id_Entrega FROM entrega_tarea WHERE Id_Tarea=? AND Id_Estudiante=?");
$sel->execute([$idTarea, $idEstudiante]);
$entrega = $sel->fetch(PDO::FETCH_ASSOC);
if (!$entrega) {
  http_response_code(409);
  exit("No existe entrega para evaluar");
}

$upd = $pdo->prepare("UPDATE entrega_tarea
                      SET Calificacion = ?, Comentario = ?
                      WHERE Id_Entrega = ?");
$upd->execute([$nota, $comentario, $entrega['Id_Entrega']]);

header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/EvaluarTarea.php?idTarea={$idTarea}&idCurso={$idCurso}&ok=1");
exit;
