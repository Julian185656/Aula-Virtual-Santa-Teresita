<?php
require_once __DIR__ . '/db.php';

class TareaModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Obtener todas las tareas de un curso
    public function obtenerTareasPorCurso($idCurso)
    {
        $sql = "SELECT Id_Tarea, Titulo, Descripcion, Fecha_Entrega 
                FROM tarea 
                WHERE Id_Curso = :idCurso
                ORDER BY Fecha_Entrega ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idCurso' => $idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Crear nueva tarea
    public function crearTarea($idCurso, $titulo, $descripcion, $fechaEntrega)
    {
        $sql = "INSERT INTO tarea (Id_Curso, Titulo, Descripcion, Fecha_Entrega)
                VALUES (:idCurso, :titulo, :descripcion, :fecha)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idCurso' => $idCurso,
            ':titulo' => $titulo,
            ':descripcion' => $descripcion,
            ':fecha' => $fechaEntrega
        ]);
    }
}
?>
