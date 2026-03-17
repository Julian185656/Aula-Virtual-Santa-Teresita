<?php
require_once __DIR__ . '/db.php';
$pdo = (new CN_BD())->conectar();
class TareaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerCursosDocente($idDocente) {
        $stmt = $this->pdo->prepare("EXEC aulavirtual.sp_obtenerCursosDocente :idDocente");
        $stmt->execute([':idDocente' => $idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





public function crearTarea($idCurso, $titulo, $descripcion, $fechaEntrega) {
    $stmt = $this->pdo->prepare(
        "EXEC aulavirtual.sp_crearTarea 
        @p_idCurso = :idCurso, 
        @p_titulo = :titulo, 
        @p_descripcion = :descripcion, 
        @p_fechaEntrega = :fechaEntrega"
    );
    return $stmt->execute([
        ':idCurso' => $idCurso,
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':fechaEntrega' => $fechaEntrega
    ]);
}


public function obtenerTareasPorCurso($idCurso) {
    $stmt = $this->pdo->prepare(
        "EXEC aulavirtual.sp_obtenerTareasPorCurso @p_idCurso = :idCurso"
    );
    return $stmt->execute([':idCurso' => $idCurso]) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
}


public function obtenerTareaPorId($idTarea) {
    $stmt = $this->pdo->prepare(
        "EXEC aulavirtual.sp_obtenerTareaPorId @p_idTarea = :idTarea"
    );
    $stmt->execute([':idTarea' => $idTarea]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function editarTarea($idTarea, $titulo, $descripcion, $fechaEntrega) {
    $stmt = $this->pdo->prepare(
        "EXEC aulavirtual.sp_editarTarea 
         @p_idTarea = :idTarea, 
         @p_titulo = :titulo, 
         @p_descripcion = :descripcion, 
         @p_fechaEntrega = :fechaEntrega"
    );
    return $stmt->execute([
        ':idTarea' => $idTarea,
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':fechaEntrega' => $fechaEntrega
    ]);
}


public function eliminarTarea($idTarea) {

    $stmtEntrega = $this->pdo->prepare(
        "DELETE FROM aulavirtual.entrega_tarea WHERE Id_Tarea = :idTarea"
    );
    $stmtEntrega->execute([':idTarea' => $idTarea]);

    
    $stmtTarea = $this->pdo->prepare(
        "DELETE FROM aulavirtual.tarea WHERE Id_Tarea = :idTarea"
    );
    return $stmtTarea->execute([':idTarea' => $idTarea]);
}









public static function obtenerTareasEstudiante($idUsuario, $idCurso) {
    global $pdo;
    $sql = "SELECT t.Id_Tarea, t.Titulo, t.Descripcion, t.Fecha_Entrega AS FechaLimite,
                   e.Id_Entrega, e.Archivo_URL, e.Calificacion, e.Comentario
            FROM aulavirtual.tarea t
            LEFT JOIN aulavirtual.entrega_tarea e
                   ON t.Id_Tarea = e.Id_Tarea AND e.Id_Estudiante = :idUsuario
            WHERE t.Id_Curso = :idCurso
            ORDER BY t.Fecha_Entrega ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['idUsuario'=>$idUsuario, 'idCurso'=>$idCurso]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public static function borrarEntrega($idEntrega, $idUsuario)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM aulavirtual.entrega_tarea WHERE Id_Entrega = :idEntrega AND Id_Estudiante = :idUsuario");
        $stmt->execute([':idEntrega' => $idEntrega, ':idUsuario' => $idUsuario]);
        return $stmt->rowCount() > 0;
    }








}
?>
