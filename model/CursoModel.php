<?php
require_once __DIR__ . '/db.php';

class CursoModel {


    public static function crearCurso($nombre, $descripcion) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_crearCurso(:nombre, :descripcion)");
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
    }


    public static function obtenerDocentes() {
        global $pdo;
        $stmt = $pdo->query("CALL sp_obtenerDocentes()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerCursos() {
        global $pdo;
        $stmt = $pdo->query("CALL sp_obtenerCursos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function asignarDocentes($idCurso, $docentes) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_asignarDocentes(:idCurso, :idDocente)");
        foreach ($docentes as $idDocente) {
            $stmt->execute([
                ':idCurso' => (int)$idCurso,
                ':idDocente' => (int)$idDocente
            ]);
        }
        return true;
    }


    public static function eliminarCurso($idCurso) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_eliminarCurso(:idCurso)");
        return $stmt->execute([':idCurso' => (int)$idCurso]);
    }


    public static function obtenerCursosDocente($idDocente) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtenerCursosDocente(:idDocente)");
        $stmt->execute([':idDocente' => (int)$idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function obtenerCursoPorId($idCurso) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM curso WHERE Id_Curso = :idCurso LIMIT 1");
        $stmt->execute([':idCurso' => $idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


public static function obtenerEstudiantes() {
    global $pdo;
    $stmt = $pdo->query("CALL sp_obtenerEstudiantes()");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function matricularEstudiantes($idCurso, $estudiantes) {
    global $pdo;
    $stmt = $pdo->prepare("CALL sp_matricularEstudiantes(:idCurso, :idEstudiante)");
    foreach ($estudiantes as $idEstudiante) {
        $stmt->execute([
            ':idCurso' => (int)$idCurso,
            ':idEstudiante' => (int)$idEstudiante
        ]);
    }
    return true;
}


public static function obtenerCursosEstudiante($idEstudiante) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtenerCursosEstudiante(:idEstudiante)");
        $stmt->execute([':idEstudiante' => $idEstudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





}
?>
