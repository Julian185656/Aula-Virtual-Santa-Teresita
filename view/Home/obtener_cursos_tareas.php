<?php
session_start();
require_once __DIR__ . '/../../model/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'Docente') {
    echo json_encode(['ok' => false, 'error' => 'No autorizado']);
    exit;
}

$idDocente = $_SESSION['usuario']['id_usuario'];

try {
    $cn = new CN_BD();
    $pdo = $cn->conectar();

    // Obtener cursos del docente usando curso_docente
    $sqlCursos = "
        SELECT c.Id_Curso, c.Nombre
        FROM aulavirtual.curso_docente cd
        INNER JOIN aulavirtual.curso c ON cd.Id_Curso = c.Id_Curso
        WHERE cd.Id_Docente = ?
    ";
    $stmt = $pdo->prepare($sqlCursos);
    $stmt->execute([$idDocente]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultCursos = [];
    foreach ($cursos as $c) {
        // Obtener tareas del curso
        $sqlTareas = "SELECT Id_Tarea, Titulo FROM aulavirtual.tarea WHERE Id_Curso = ?";
        $stmtT = $pdo->prepare($sqlTareas);
        $stmtT->execute([$c['Id_Curso']]);
        $tareas = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        $tareasConPendientes = [];
        foreach ($tareas as $t) {
            // Contar estudiantes que no han entregado la tarea
            $sqlPendientes = "
                SELECT COUNT(*) as pendientes
                FROM aulavirtual.matricula m
                LEFT JOIN aulavirtual.entrega_tarea e
                    ON e.Id_Estudiante = m.Id_Estudiante AND e.Id_Tarea = ?
                WHERE m.Id_Curso = ? AND e.Id_Entrega IS NULL
            ";
            $stmtP = $pdo->prepare($sqlPendientes);
            $stmtP->execute([$t['Id_Tarea'], $c['Id_Curso']]);
            $pendiente = $stmtP->fetch(PDO::FETCH_ASSOC)['pendientes'] ?? 0;

            $tareasConPendientes[] = [
                'id' => $t['Id_Tarea'],
                'titulo' => $t['Titulo'],
                'pendientes' => (int)$pendiente
            ];
        }

        $resultCursos[] = [
            'id' => $c['Id_Curso'],
            'nombre' => $c['Nombre'],
            'tareas' => $tareasConPendientes
        ];
    }

    echo json_encode(['ok' => true, 'cursos' => $resultCursos]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Error en el servidor: '.$e->getMessage()]);
}
