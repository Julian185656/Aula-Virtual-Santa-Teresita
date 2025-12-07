<?php
require_once __DIR__ . '/db.php';

class ForoModel
{
    /** Crear publicación del estudiante */
    public static function crearPublicacion(int $idCurso, int $idAutor, string $titulo, string $contenido): bool {
        global $pdo;
        $sql = "INSERT INTO foro (Id_Curso, Titulo, Contenido, Id_Autor) VALUES (?,?,?,?)";
        $st  = $pdo->prepare($sql);
        return $st->execute([$idCurso, trim($titulo), trim($contenido), $idAutor]);
    }



public static function listarComentariosPorPublicacion(int $idForo)
{
    global $pdo;

    $sql = "SELECT c.Id_Comentario, c.Texto, c.Fecha_Creacion,
                   u.Nombre AS Autor
            FROM comentarios_foro c
            INNER JOIN usuarios u ON u.Id_Usuario = c.Id_Usuario
            WHERE c.Id_Foro = ?
            ORDER BY c.Fecha_Creacion ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idForo]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




    /** Listar publicaciones activas por curso (más recientes arriba) */
    public static function listarPublicacionesPorCurso(int $idCurso): array {
        global $pdo;
        $sql = "SELECT f.Id_Foro, f.Titulo, f.Contenido, f.Fecha_Creacion,
                       f.Id_Autor, u.Nombre AS Autor
                FROM foro f
                JOIN usuario u ON u.Id_Usuario = f.Id_Autor
                WHERE f.Id_Curso = ? AND f.Estado = 'Activo'
                ORDER BY f.Fecha_Creacion DESC";
        $st = $pdo->prepare($sql);
        $st->execute([$idCurso]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Crear respuesta (docente o estudiante) */
    public static function responder(int $idForo, int $idAutor, string $texto): bool {
        global $pdo;
        $sql = "INSERT INTO comentarios (Id_Foro, Id_Autor, Texto) VALUES (?,?,?)";
        $st  = $pdo->prepare($sql);
        return $st->execute([$idForo, $idAutor, trim($texto)]);
    }

    /** Listar respuestas activas de una publicación */
    public static function listarComentarios(int $idForo): array {
        global $pdo;
        $sql = "SELECT c.Id_Comentario, c.Texto, c.Fecha_Creacion,
                       c.Id_Autor, u.Nombre AS Autor
                FROM comentarios c
                JOIN usuario u ON u.Id_Usuario = c.Id_Autor
                WHERE c.Id_Foro = ? AND c.Estado = 'Activo'
                ORDER BY c.Fecha_Creacion ASC";
        $st = $pdo->prepare($sql);
        $st->execute([$idForo]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== Moderación (Admin) ===== */

    /** Ocultar publicación (soft delete) */
    public static function eliminarPublicacion(int $idForo): bool {
        global $pdo;
        $st = $pdo->prepare("UPDATE foro SET Estado='Eliminado' WHERE Id_Foro=?");
        return $st->execute([$idForo]);
    }

    /** Ocultar comentario (soft delete) */
    public static function eliminarComentario(int $idComentario): bool {
        global $pdo;
        $st = $pdo->prepare("UPDATE comentarios SET Estado='Eliminado' WHERE Id_Comentario=?");
        return $st->execute([$idComentario]);
    }

    /**
     * Listar todas las publicaciones para administración.
     * - Filtro opcional por nombre de curso (LIKE).
     * - Trae: Id_Foro, Estado, Fecha_Creacion, Id_Curso, Nombre del curso, Título,
     *   resumen de contenido (200 chars) y Autor.
     */
    public static function adminListar(?string $cursoNombre = null): array 
{
    global $pdo;

    $sql = "SELECT f.Id_Foro, f.Estado, f.Fecha_Creacion,
                   c.Id_Curso, c.Nombre AS Curso,
                   f.Titulo, LEFT(f.Contenido, 200) AS Resumen,
                   u.Nombre AS Autor
            FROM foro f
            JOIN curso c   ON c.Id_Curso   = f.Id_Curso
            JOIN usuario u ON u.Id_Usuario = f.Id_Autor
            WHERE 1=1";

    $params = [];

    if (!empty($cursoNombre)) {
        $sql .= " AND c.Nombre LIKE ?";
        $params[] = "%$cursoNombre%";
    }

    $sql .= " ORDER BY f.Fecha_Creacion DESC";

    $st = $pdo->prepare($sql);
    $st->execute($params);

    return $st->fetchAll(PDO::FETCH_ASSOC);
}

}
