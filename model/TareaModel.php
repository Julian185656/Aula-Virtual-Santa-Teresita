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




// Crear nueva tarea
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
    $stmt = $this->pdo->prepare(
        "EXEC aulavirtual.sp_eliminarTarea @idTarea = :idTarea"
    );
    return $stmt->execute([':idTarea' => $idTarea]);
}







public static function obtenerTareasEstudiante($idEstudiante, $idCurso = null, $offset = 0, $limite = 10) {
    global $pdo;

    $sql = "
        SELECT t.Id_Tarea, t.Titulo, t.Descripcion, t.Fecha_Entrega, c.Nombre AS Curso
        FROM aulavirtual.Tarea t
        INNER JOIN aulavirtual.Curso c ON t.Id_Curso = c.Id_Curso
        INNER JOIN aulavirtual.Matricula m ON c.Id_Curso = m.Id_Curso
        WHERE m.Id_Estudiante = :idEstudiante
    ";

    if ($idCurso !== null) {
        $sql .= " AND c.Id_Curso = :idCurso";
    }

    $sql .= " ORDER BY t.Fecha_Entrega
              OFFSET :offset ROWS
              FETCH NEXT :limite ROWS ONLY";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idEstudiante', (int)$idEstudiante, PDO::PARAM_INT);

    if ($idCurso !== null) {
        $stmt->bindValue(':idCurso', (int)$idCurso, PDO::PARAM_INT);
    }

    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}








}
?>
