<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";

class EncuestaModel {

 
    public static function obtenerEncuestasCurso($idCurso) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM aulavirtual.Encuestas 
            WHERE Id_Curso = ?
        ");
        $stmt->execute([$idCurso]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public static function crearEncuesta($idCurso, $titulo) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            INSERT INTO aulavirtual.Encuestas (Id_Curso, Titulo)
            VALUES (?, ?)
        ");
        $stmt->execute([$idCurso, $titulo]);
        return $pdo->lastInsertId();
    }

 
    public static function obtenerEncuesta($idEncuesta) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM aulavirtual.Encuestas 
            WHERE Id_Encuesta = ?
        ");
        $stmt->execute([$idEncuesta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

public static function obtenerEncuestaPorCurso($idCurso) {
    $pdo = (new CN_BD())->conectar();
    $sql = "SELECT * FROM aulavirtual.Encuestas WHERE Id_Curso = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idCurso]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    public static function crearPregunta($idEncuesta, $preguntaTexto) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            INSERT INTO aulavirtual.PreguntasEncuesta (Id_Encuesta, Pregunta)
            VALUES (?, ?)
        ");
        $stmt->execute([$idEncuesta, $preguntaTexto]);
        return $pdo->lastInsertId();
    }


    public static function obtenerPreguntas($idEncuesta) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT * 
            FROM aulavirtual.PreguntasEncuesta
            WHERE Id_Encuesta = ?
        ");
        $stmt->execute([$idEncuesta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 
    public static function guardarRespuesta($idPregunta, $idUsuario, $respuesta) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            INSERT INTO aulavirtual.RespuestasEncuesta (Id_Pregunta, Id_Usuario, Respuesta)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$idPregunta, $idUsuario, $respuesta]);
    }


    public static function obtenerRespuestasUsuario($idEncuesta, $idUsuario) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT r.*, p.Pregunta 
            FROM aulavirtual.RespuestasEncuesta r
            INNER JOIN aulavirtual.PreguntasEncuesta p 
                ON r.Id_Pregunta = p.Id_Pregunta
            WHERE p.Id_Encuesta = ? AND r.Id_Usuario = ?
        ");
        $stmt->execute([$idEncuesta, $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerTodasRespuestas($idEncuesta) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT r.*, p.Pregunta, u.Nombre AS NombreUsuario 
            FROM aulavirtual.RespuestasEncuesta r
            INNER JOIN aulavirtual.PreguntasEncuesta p 
                ON r.Id_Pregunta = p.Id_Pregunta
            INNER JOIN aulavirtual.usuario u 
                ON r.Id_Usuario = u.Id_Usuario
            WHERE p.Id_Encuesta = ?
            ORDER BY r.FechaRespuesta DESC
        ");
        $stmt->execute([$idEncuesta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerRespuestas($idEncuesta) {
        $pdo = (new CN_BD())->conectar();
        $stmt = $pdo->prepare("
            SELECT r.Respuesta, r.Id_Pregunta, u.Nombre
            FROM aulavirtual.RespuestasEncuesta r
            INNER JOIN aulavirtual.usuario u ON r.Id_Usuario = u.Id_Usuario
            INNER JOIN aulavirtual.PreguntasEncuesta p ON r.Id_Pregunta = p.Id_Pregunta
            WHERE p.Id_Encuesta = ?
        ");
        $stmt->execute([$idEncuesta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public static function eliminarEncuesta($idEncuesta)
{
    $pdo = (new CN_BD())->conectar();


    $sql = "DELETE FROM aulavirtual.RespuestasEncuesta WHERE Id_Pregunta IN 
            (SELECT Id_Pregunta FROM aulavirtual.PreguntasEncuesta WHERE Id_Encuesta = ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idEncuesta]);


    $sql = "DELETE FROM aulavirtual.PreguntasEncuesta WHERE Id_Encuesta = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idEncuesta]);


    $sql = "DELETE FROM aulavirtual.Encuestas WHERE Id_Encuesta = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idEncuesta]);
}



public static function usuarioYaRespondio($idEncuesta, $idUsuario)
{
    $pdo = (new CN_BD())->conectar();

    $sql = "SELECT Id_Pregunta FROM aulavirtual.PreguntasEncuesta WHERE Id_Encuesta = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idEncuesta]);
    $preguntas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($preguntas)) {
        return false;
    }

    $placeholders = implode(',', array_fill(0, count($preguntas), '?'));

    
    $sql2 = "SELECT COUNT(*) 
             FROM aulavirtual.RespuestasEncuesta 
             WHERE Id_Usuario = ? 
               AND Id_Pregunta IN ($placeholders)";

    $stmt2 = $pdo->prepare($sql2);


    $params = array_merge([$idUsuario], $preguntas);

    $stmt2->execute($params);

  
    return $stmt2->fetchColumn() > 0;
}


}
?>
