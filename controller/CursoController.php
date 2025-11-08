<?php
require_once __DIR__ . '/../model/CursoModel.php';

class CursoController {

    public static function crearCurso() {
        if (isset($_POST['nombre'], $_POST['descripcion'])) {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            CursoModel::crearCurso($nombre, $descripcion);
            header("Location: CrearCurso.php");
            exit;
        }
    }

    public static function asignarDocentes() {
        if (isset($_POST['idCurso'], $_POST['docentes'])) {
            $idCurso = $_POST['idCurso'];
            $docentes = $_POST['docentes'];
            CursoModel::asignarDocentes($idCurso, $docentes);
            header("Location: CrearCurso.php");
            exit;
        }
    }


    public static function eliminarCurso($idCurso) {
        if (!empty($idCurso)) {
            CursoModel::eliminarCurso($idCurso);
            header("Location: CrearCurso.php");
            exit;
        }
    }
}
