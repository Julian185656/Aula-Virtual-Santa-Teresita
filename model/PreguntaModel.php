<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";

class PreguntaModel {

    public static function obtenerPreguntasPorEncuesta($idEncuesta) {
        $pdo = (new CN_BD())->conectar();

        $sql = "SELECT * FROM aulavirtual.PreguntasEncuesta WHERE Id_Encuesta = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idEncuesta]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
