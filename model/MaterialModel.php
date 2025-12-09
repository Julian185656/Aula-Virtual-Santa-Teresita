<?php
$pdo = (new CN_BD())->conectar();

require_once __DIR__ . '/db.php';

class MaterialModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Guarda un archivo asociado a un curso.
     */
    public function guardarMaterial($cursoId, $usuarioId, $titulo, $descripcion, $rutaArchivo)
    {
        $sql = "INSERT INTO aulavirtual.material_curso 
                (Id_Curso, Id_Usuario, Titulo, Descripcion, Archivo_URL)
                VALUES (:cursoId, :usuarioId, :titulo, :descripcion, :archivo)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':cursoId'    => $cursoId,
            ':usuarioId'  => $usuarioId,
            ':titulo'     => $titulo,
            ':descripcion'=> $descripcion,
            ':archivo'    => $rutaArchivo
        ]);
    }


    public function obtenerMaterialPorCurso($cursoId)
    {
        $sql = "SELECT *
                FROM aulavirtual.material_curso
                WHERE Id_Curso = :cursoId
                ORDER BY Fecha_Subida DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cursoId' => $cursoId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMaterialPorID($idMaterial)
    {
        $sql = "SELECT *
                FROM aulavirtual.material_curso
                WHERE Id_Material = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idMaterial]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  
    public function eliminarMaterial($idMaterial)
    {
        $stmt = $this->pdo->prepare("DELETE FROM aulavirtual.material_curso WHERE Id_Material = ?");
        return $stmt->execute([$idMaterial]);
    }
}
