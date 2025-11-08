<?php
require_once __DIR__ . '/db.php';

class TareaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerCursosDocente($idDocente) {
        $stmt = $this->pdo->prepare("CALL sp_obtenerCursosDocente(:idDocente)");
        $stmt->execute([':idDocente' => $idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function crearTarea($idCurso, $titulo, $descripcion, $fechaEntrega) {
        $stmt = $this->pdo->prepare("CALL sp_crearTarea(:idCurso, :titulo, :descripcion, :fechaEntrega)");
        return $stmt->execute([
            ':idCurso' => $idCurso,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fechaEntrega' => $fechaEntrega
        ]);
    }


    public function obtenerTareasPorCurso($idCurso) {
        $stmt = $this->pdo->prepare("CALL sp_obtenerTareasPorCurso(:idCurso)");
        $stmt->execute([':idCurso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerTareaPorId($idTarea) {
        $stmt = $this->pdo->prepare("CALL sp_obtenerTareaPorId(:idTarea)");
        $stmt->execute([':idTarea' => $idTarea]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editarTarea($idTarea, $titulo, $descripcion, $fechaEntrega) {
        $stmt = $this->pdo->prepare("CALL sp_editarTarea(:idTarea, :titulo, :descripcion, :fechaEntrega)");
        return $stmt->execute([
            ':idTarea' => $idTarea,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fechaEntrega' => $fechaEntrega
        ]);
    }

    public function eliminarTarea($idTarea) {
        $stmt = $this->pdo->prepare("CALL sp_eliminarTarea(:idTarea)");
        return $stmt->execute([':idTarea' => $idTarea]);
    }




    public static function obtenerTareasEstudiante($idEstudiante, $idCurso = null) {
    global $pdo;
    $stmt = $pdo->prepare("CALL sp_obtenerTareasEstudiantePorCurso(:idEstudiante, :idCurso)");
    $stmt->execute([
        ':idEstudiante' => (int)$idEstudiante,
        ':idCurso' => $idCurso ? (int)$idCurso : null
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
