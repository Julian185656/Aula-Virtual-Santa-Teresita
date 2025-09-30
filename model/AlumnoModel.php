<?php
class AlumnoModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function obtenerAlumnosDeDocente($docenteId) {
        $sql = "CALL sp_obtener_alumnos_docente(?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$docenteId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $result;
    }
}
?>
