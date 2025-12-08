<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/RankingModel.php";

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['rol']) !== 'estudiante') {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

$idCurso = isset($_GET['idCurso']) ? (int) $_GET['idCurso'] : 0;

if ($idCurso <= 0) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Estudiante/MisCursosEstudiante.php");
    exit();
}

$ranking = RankingModel::obtenerRankingCurso($idCurso);

require $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/view/Ranking/RankingCurso.php";
