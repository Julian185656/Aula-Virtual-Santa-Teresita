<?php
require_once __DIR__ . '/db.php';

class CursoModel {

    // Crear curso usando SP
    public static function crearCurso($nombre, $descripcion) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_crearCurso(:nombre, :descripcion)");
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
    }

    // Obtener docentes con nombre real usando SP
    public static function obtenerDocentes() {
        global $pdo;
        $stmt = $pdo->query("CALL sp_obtenerDocentes()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cursos usando SP
    public static function obtenerCursos() {
        global $pdo;
        $stmt = $pdo->query("CALL sp_obtenerCursos()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Asignar docentes a un curso usando SP
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

    // Eliminar curso usando SP
    public static function eliminarCurso($idCurso) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_eliminarCurso(:idCurso)");
        return $stmt->execute([':idCurso' => (int)$idCurso]);
    }

    // Obtener cursos asignados a un docente usando SP
    public static function obtenerCursosDocente($idDocente) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtenerCursosDocente(:idDocente)");
        $stmt->execute([':idDocente' => (int)$idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


// ✅ Nuevo método para obtener un curso por ID
    public static function obtenerCursoPorId($idCurso) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM curso WHERE Id_Curso = :idCurso LIMIT 1");
        $stmt->execute([':idCurso' => $idCurso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




public static function matricularEstudiantes($idCurso, $estudiantes) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO matricula (id_curso, id_estudiante)
        SELECT :idCursoInsert, :idEstudianteInsert
        WHERE NOT EXISTS (
            SELECT 1 FROM matricula 
            WHERE id_curso = :idCursoCheck AND id_estudiante = :idEstudianteCheck
        )
    ");

    foreach ($estudiantes as $idEstudiante) {
        $stmt->execute([
            ':idCursoInsert' => (int)$idCurso,
            ':idEstudianteInsert' => (int)$idEstudiante,
            ':idCursoCheck' => (int)$idCurso,
            ':idEstudianteCheck' => (int)$idEstudiante
        ]);
    }
    return true;
}


// Obtener todos los estudiantes (opcional filtro por nombre, paginación)


// Contar estudiantes (opcional filtro por nombre)


// Obtener estudiantes por curso (filtro, paginación)







// Obtener todos los estudiantes (filtro por nombre, paginación)


// Obtener estudiantes por curso
public static function obtenerEstudiantesPorCurso($idCurso, $nombre = '', $limit = 10, $offset = 0) {
    global $pdo;
    $sql = "SELECT u.Id_Usuario, u.Nombre
            FROM usuario u
            INNER JOIN matricula m ON u.Id_Usuario = m.id_estudiante
            WHERE m.id_curso = :idCurso AND u.Nombre LIKE :nombre
            ORDER BY u.Nombre
            LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idCurso', (int)$idCurso, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Contar estudiantes por curso
public static function contarEstudiantesPorCurso($idCurso, $nombre = '') {
    global $pdo;
    $sql = "SELECT COUNT(*)
            FROM usuario u
            INNER JOIN matricula m ON u.Id_Usuario = m.id_estudiante
            WHERE m.id_curso = :idCurso AND u.Nombre LIKE :nombre";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idCurso', (int)$idCurso, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
}




public static function obtenerEstudiantes($nombre = '', $limite = 10, $offset = 0) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT Id_Usuario, Nombre
        FROM usuario
        WHERE Rol = 'Estudiante' AND Nombre LIKE :nombre
        ORDER BY Nombre
        LIMIT :limite OFFSET :offset
    ");
    $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






public static function obtenerEstudiantesPaginado($nombre='', $limite=10, $offset=0) {
        global $pdo;
        $sql = "SELECT * FROM usuario 
                WHERE Rol='Estudiante' AND Nombre LIKE :nombre 
                ORDER BY Nombre 
                LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public static function contarEstudiantes($nombre = '') {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM usuario
        WHERE Rol='Estudiante' AND Nombre LIKE :nombre
    ");
    $stmt->execute([':nombre' => "%$nombre%"]);
    return $stmt->fetchColumn();
}






public static function obtenerCursosEstudiante($idEstudiante) {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtenerCursosEstudiante(:idEstudiante)");
        $stmt->execute([':idEstudiante' => $idEstudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public static function obtenerCursosPorEstudiante($idEstudiante) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.nombre
        FROM matricula m
        INNER JOIN curso c ON c.Id_Curso = m.id_curso
        WHERE m.id_estudiante = ?
    ");
    $stmt->execute([$idEstudiante]);
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $resultados;
}
public static function obtenerEstudiantesConCurso() {
    global $pdo;
    $stmt = $pdo->query("CALL sp_estudiantes_con_curso()");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $data;
}

public static function eliminarMatricula($idCurso, $idEstudiante) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM matricula WHERE id_curso = :idCurso AND id_estudiante = :idEstudiante");
    return $stmt->execute([':idCurso' => $idCurso, ':idEstudiante' => $idEstudiante]);
}

public static function obtenerCursoIdPorNombre($nombreCurso) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT Id_Curso FROM curso WHERE nombre = :nombre LIMIT 1");
    $stmt->execute([':nombre' => $nombreCurso]);
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $curso['Id_Curso'] ?? null;
}



}
?>
