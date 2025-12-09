<?php
require_once __DIR__ . '/db.php';
$pdo = (new CN_BD())->conectar();
class CursoModel {


    public static function crearCurso($nombre, $descripcion) {
        global $pdo;
        $stmt = $pdo->prepare(" EXEC aulavirtual.sp_crearCurso :nombre, :descripcion");
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
    }

    
public static function obtenerDocentes() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT Id_Usuario AS id, Nombre AS nombre 
        FROM aulavirtual.Usuario
        WHERE Rol='Docente' AND Estado='Activo'
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    public static function obtenerCursos() {
        global $pdo;
        $stmt = $pdo->query("EXEC aulavirtual. sp_obtenerCursos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public static function asignarDocentes($idCurso, $docentes) {
    global $pdo;

    $stmtCheck = $pdo->prepare("SELECT COUNT(*) 
                                FROM aulavirtual.curso_docente 
                                WHERE Id_Curso = :idCurso AND Id_Docente = :idDocente");

    $stmtInsert = $pdo->prepare("INSERT INTO aulavirtual.curso_docente (Id_Curso, Id_Docente)
                                 VALUES (:idCurso, :idDocente)");

    foreach ($docentes as $idDocente) {
        
        $stmtCheck->execute([
            ':idCurso' => (int)$idCurso,
            ':idDocente' => (int)$idDocente
        ]);

        if ($stmtCheck->fetchColumn() == 0) {
            $stmtInsert->execute([
                ':idCurso' => (int)$idCurso,
                ':idDocente' => (int)$idDocente
            ]);
        }
    
    }

    return true;
}





    public static function eliminarCurso($idCurso) {
        global $pdo;
   $stmt = $pdo->prepare("EXEC aulavirtual.sp_eliminarCurso ?");
return $stmt->execute([$idCurso]);

    }


    public static function obtenerCursosDocente($idDocente) {
        global $pdo;
        $stmt = $pdo->prepare("EXEC aulavirtual.sp_obtenerCursosDocente :idDocente");
        $stmt->execute([':idDocente' => (int)$idDocente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public static function obtenerCursoPorId($idCurso) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT TOP 1 * FROM aulavirtual.curso WHERE Id_Curso = :idCurso");
    $stmt->execute([':idCurso' => $idCurso]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}




public static function matricularEstudiantes($idCurso, $estudiantes) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO aulavirtual.matricula (id_curso, id_estudiante)
        SELECT :idCursoInsert, :idEstudianteInsert
        WHERE NOT EXISTS (
            SELECT 1 FROM aulavirtual.matricula 
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


public static function obtenerEstudiantesPorCurso($idCurso, $nombre = '', $limit = 10, $offset = 0) {
    global $pdo;
    $sql = "SELECT u.Id_Usuario, u.Nombre
            FROM  aulavirtual.usuario u
            INNER JOIN aulavirtual.matricula m ON u.Id_Usuario = m.id_estudiante
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


public static function contarEstudiantesPorCurso($idCurso, $nombre = '') {
    global $pdo;
    $sql = "SELECT COUNT(*)
            FROM aulavirtual.usuario u
            INNER JOIN aulavirtual.matricula m ON u.Id_Usuario = m.id_estudiante
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
        FROM aulavirtual.usuario
        WHERE Rol = 'Estudiante' AND Nombre LIKE :nombre
        ORDER BY Nombre
        OFFSET :offset ROWS
        FETCH NEXT :limite ROWS ONLY
    ");
    $stmt->bindValue(':nombre', "%$nombre%", PDO::PARAM_STR);
    $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






public static function obtenerEstudiantesPaginado($nombre='', $limite=10, $offset=0) {
        global $pdo;
        $sql = "SELECT * FROM aulavirtual.usuario 
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
        FROM aulavirtual.usuario
        WHERE Rol='Estudiante' AND Nombre LIKE :nombre
    ");
    $stmt->execute([':nombre' => "%$nombre%"]);
    return $stmt->fetchColumn();
}


public static function obtenerCursosAdmin() {
    global $pdo;

    $sql = "SELECT Id_Curso, Nombre, Descripcion FROM aulavirtual.curso ORDER BY Nombre ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public static function obtenerCursosEstudiante($idEstudiante) {
    global $pdo;


    $stmt = $pdo->prepare("EXEC aulavirtual.sp_obtenerCursosEstudiante ?");
    

    $stmt->execute([$idEstudiante]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function obtenerCursosPorEstudiante($idEstudiante) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.nombre
        FROM aulavirtual.matricula m
        INNER JOIN aulavirtual.curso c ON c.Id_Curso = m.id_curso
        WHERE m.id_estudiante = ?
    ");
    $stmt->execute([$idEstudiante]);
    $resultados = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $resultados;
}
public static function obtenerEstudiantesConCurso() {
    global $pdo;
    $stmt = $pdo->query("EXEC aulavirtual.sp_estudiantes_con_curso");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $data;
}

public static function eliminarMatricula($idCurso, $idEstudiante) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM aulavirtual.matricula WHERE id_curso = :idCurso AND id_estudiante = :idEstudiante");
    return $stmt->execute([':idCurso' => $idCurso, ':idEstudiante' => $idEstudiante]);
}

public static function obtenerCursoIdPorNombre($nombreCurso) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT TOP 1 Id_Curso 
        FROM aulavirtual.curso 
        WHERE nombre = :nombre
    ");
    $stmt->execute([':nombre' => $nombreCurso]);
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $curso['Id_Curso'] ?? null;
}

public static function verificarMatricula(int $idEstudiante, int $idCurso): bool {
    global $pdo;
    $sql = $sql = "SELECT COUNT(*) FROM aulavirtual.matricula WHERE Id_Estudiante = ? AND Id_Curso = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idEstudiante, $idCurso]);
    return $stmt->fetchColumn() > 0;
}



}
?>
