<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Aula-Virtual-Santa-Teresita/model/db.php";
$pdo = (new CN_BD())->conectar();
class RankingModel {

    public static function obtenerRankingCurso($idCurso) {
        global $pdo;

        $sql = "
            SELECT 
                u.Id_Usuario AS id_estudiante,
                u.Nombre AS nombre,
                e.Grado,
                e.Seccion,
                COALESCE(SUM(et.Puntos_Ranking), 0) AS puntos_total
            FROM aulavirtual.matricula m
            JOIN aulavirtual.usuario u 
                ON u.Id_Usuario = m.Id_Estudiante
            LEFT JOIN aulavirtual.estudiante e 
                ON e.Id_Estudiante = m.Id_Estudiante
            LEFT JOIN aulavirtual.tarea t 
                ON t.Id_Curso = m.Id_Curso
            LEFT JOIN aulavirtual.entrega_tarea et  
                ON et.Id_Tarea = t.Id_Tarea 
               AND et.Id_Estudiante = m.Id_Estudiante
            WHERE m.Id_Curso = ?
            GROUP BY u.Id_Usuario, u.Nombre, e.Grado, e.Seccion
            ORDER BY puntos_total DESC, u.Nombre ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idCurso]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
