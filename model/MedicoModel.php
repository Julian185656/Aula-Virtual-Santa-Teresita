<?php
class MedicoModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Obtener info médica de un estudiante
    public function obtenerInfoMedica($idEstudiante) {
        $sql = "SELECT Alergias, Medicamentos, EnfermedadesCronicas, Observaciones
                FROM aulavirtual.informacion_medica
                WHERE Id_Estudiante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idEstudiante]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar info médica (insert o update)
    public function guardarInfoMedica($id, $alergias, $medicamentos, $enfermedades, $observaciones) {
        $sql = "
        IF EXISTS (SELECT 1 FROM aulavirtual.informacion_medica WHERE Id_Estudiante = ?)
            UPDATE aulavirtual.informacion_medica
            SET Alergias = ?, Medicamentos = ?, EnfermedadesCronicas = ?, Observaciones = ?
            WHERE Id_Estudiante = ?
        ELSE
            INSERT INTO aulavirtual.informacion_medica
            (Id_Estudiante, Alergias, Medicamentos, EnfermedadesCronicas, Observaciones)
            VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $alergias, $medicamentos, $enfermedades, $observaciones, $id, $id, $alergias, $medicamentos, $enfermedades, $observaciones]);
    }
}
?>
