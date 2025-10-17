<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";

class CursoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerTodos() {
        $sql = "SELECT c.Id_Curso, c.Nombre, c.Descripcion, u.Nombre AS Docente
                FROM curso c
                LEFT JOIN usuario u ON c.Id_Docente = u.Id_Usuario";
        $stmt = $this->pdo->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // 🆕 Crear curso nuevo
    public function crearCurso($nombre, $descripcion, $idDocente) {
        try {
            $sql = "INSERT INTO curso (Nombre, Descripcion, Id_Docente)
                    VALUES (:nombre, :descripcion, :idDocente)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':idDocente' => $idDocente
            ]);
        } catch (PDOException $e) {
            error_log("Error al crear curso: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCurso($id, $nombre, $descripcion, $idDocente) {
        try {
            $sql = "UPDATE curso 
                    SET Nombre = :nombre, Descripcion = :descripcion, Id_Docente = :idDocente
                    WHERE Id_Curso = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':idDocente' => $idDocente,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar curso: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCursosDocente($idDocente) {
    $sql = "SELECT c.Id_Curso, c.Nombre, c.Descripcion, u.Nombre AS Docente
            FROM curso c
            INNER JOIN usuario u ON c.Id_Docente = u.Id_Usuario
            WHERE c.Id_Docente = :idDocente";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':idDocente' => $idDocente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function crearTarea($titulo, $descripcion, $fechaEntrega, $idCurso) {
        try {
            $sql = "INSERT INTO tarea (Titulo, Descripcion, Fecha_Entrega, Id_Curso)
                    VALUES (:titulo, :descripcion, :fechaEntrega, :idCurso)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':fechaEntrega' => $fechaEntrega,
                ':idCurso' => $idCurso
            ]);
        } catch (PDOException $e) {
            error_log("Error al crear tarea: " . $e->getMessage());
            return false;
        }
    }

    public function matricularEstudiante($idEstudiante, $idCurso) {
        try {
            $sql = "INSERT INTO matricula (Id_Estudiante, Id_Curso)
                    VALUES (:idEstudiante, :idCurso)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':idEstudiante' => $idEstudiante,
                ':idCurso' => $idCurso
            ]);
        } catch (PDOException $e) {
            error_log("Error al matricular estudiante: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCursosEstudiante($idEstudiante) {
        $sql = "SELECT c.Id_Curso, c.Nombre, c.Descripcion, u.Nombre AS Docente
                FROM matricula m
                INNER JOIN curso c ON m.Id_Curso = c.Id_Curso
                INNER JOIN usuario u ON c.Id_Docente = u.Id_Usuario
                WHERE m.Id_Estudiante = :idEstudiante";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idEstudiante' => $idEstudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerCursoPorId($idCurso) {
        $sql = "SELECT c.*, u.Nombre AS Docente
                FROM curso c
                LEFT JOIN usuario u ON c.Id_Docente = u.Id_Usuario
                WHERE c.Id_Curso = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
