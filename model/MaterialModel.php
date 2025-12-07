<?php
/**
 * Modelo para la gestión de materiales de curso.
 * Permite registrar, consultar y eliminar archivos.
 */

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
        $sql = "INSERT INTO material_curso 
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

    /**
     * Obtiene todos los archivos subidos a un curso.
     */
    public function obtenerMaterialPorCurso($cursoId)
    {
        $sql = "SELECT *
                FROM material_curso
                WHERE Id_Curso = :cursoId
                ORDER BY Fecha_Subida DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cursoId' => $cursoId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un archivo específico por su ID.
     */
    public function obtenerMaterialPorID($idMaterial)
    {
        $sql = "SELECT *
                FROM material_curso
                WHERE Id_Material = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idMaterial]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Elimina un archivo del registro.
     */
    public function eliminarMaterial($idMaterial)
    {
        $stmt = $this->pdo->prepare("DELETE FROM material_curso WHERE Id_Material = ?");
        return $stmt->execute([$idMaterial]);
    }
}
