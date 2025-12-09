<?php
require_once __DIR__ . '/../model/CursoModel.php';

class CursoController {

    public static function crearCurso() {
        if (isset($_POST['nombre'], $_POST['descripcion'])) {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            CursoModel::crearCurso($nombre, $descripcion);
            header("Location: CrearCursos.php");
            exit;
        }
    }

public static function asignarDocentes() {
    if (!empty($_POST['asignaciones'])) {
        foreach ($_POST['asignaciones'] as $idCurso => $docentes) {
            CursoModel::asignarDocentes($idCurso, $docentes);
        }
    }
}



    public static function eliminarCurso($idCurso) {
        if (!empty($idCurso)) {
            CursoModel::eliminarCurso($idCurso);
            header("Location: EliminarCurso.php");
            exit;
        }
    }


    
}
