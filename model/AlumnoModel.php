<?php

$pdo = (new CN_BD())->conectar();

class AlumnoModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function obtenerAlumnosDeDocente($docenteId) {
        $sql = "
            SELECT 
                u.Id_Usuario AS id,
                u.Nombre AS nombre,
                u.Email AS correo,
                m.Id_Curso AS id_curso
            FROM 
                aulavirtual.usuario AS u
                INNER JOIN aulavirtual.matricula AS m ON u.Id_Usuario = m.Id_Estudiante
                INNER JOIN aulavirtual.curso AS c ON m.Id_Curso = c.Id_Curso
                INNER JOIN aulavirtual.curso_docente AS cd ON c.Id_Curso = cd.Id_Curso
            WHERE cd.Id_Docente = :docenteId
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':docenteId', $docenteId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerAlumnosDeDocentePaginado($docenteId, $limit, $offset, $search = '') {
        $sql = "SELECT Id_Usuario AS id, Nombre AS nombre, Email AS correo, 'CursoX' AS id_curso
                FROM aulavirtual.usuario
                WHERE Rol = 'Estudiante'";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND Nombre LIKE ?";
            $params[] = "%$search%";
        }

 
        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql .= " ORDER BY Nombre OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function contarAlumnos($docenteId, $search = '') {
        $sql = "SELECT COUNT(*) AS total
                FROM aulavirtual.usuario
                WHERE Rol = 'Estudiante'";

        $params = [];
        if (!empty($search)) {
            $sql .= " AND Nombre LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }




}


?>
