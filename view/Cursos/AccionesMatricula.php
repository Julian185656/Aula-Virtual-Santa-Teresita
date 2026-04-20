<?php
require_once __DIR__ . '/../../model/CursoModel.php';

$action = $_REQUEST['action'] ?? '';

if ($action === 'listar') {
    $search = $_GET['search'] ?? '';
    $cursoIdDestino = $_GET['cursoId'] ?? null;
    
    // Obtenemos estudiantes (ajusta el límite según tu necesidad)
    $estudiantesRaw = CursoModel::obtenerEstudiantes($search, 20, 0);
    $data = [];

    foreach ($estudiantesRaw as $e) {
        $idEst = $e['Id_Usuario'] ?? $e['id'];
        $nombresCursos = CursoModel::obtenerCursosPorEstudiante($idEst);
        
        $cursosFull = [];
        $yaMatriculado = false;
        
        foreach($nombresCursos as $nom) {
            $idC = CursoModel::obtenerCursoIdPorNombre($nom);
            $cursosFull[] = ['id' => $idC, 'nombre' => $nom];
            if ($idC == $cursoIdDestino) $yaMatriculado = true;
        }

        $data[] = [
            'id' => $idEst,
            'nombre' => $e['Nombre'] ?? $e['nombre'],
            'cursos' => $cursosFull,
            'ya_matriculado' => $yaMatriculado
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($action === 'matricular') {
    $idCurso = $_POST['idCurso'];
    $idEst = $_POST['idEstudiante'];
    CursoModel::matricularEstudiantes($idCurso, [$idEst]);
    echo "ok";
}

if ($action === 'eliminar') {
    $idCurso = $_POST['idCurso'];
    $idEst = $_POST['idEstudiante'];
    CursoModel::eliminarMatricula($idCurso, $idEst);
    echo "ok";
}