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


    $cursos = CursoModel::obtenerCursos();

 
    foreach ($cursos as $curso) {
        CursoModel::limpiarDocentesCurso($curso['id']);
    }

  
    if (empty($_POST['asignaciones'])) {
        return;
    }

   
    foreach ($_POST['asignaciones'] as $idCurso => $docentes) {

       
        if (count($docentes) > 1) {
            die("❌ Error: Un curso solo puede tener un profesor asignado.");
        }

     
        if (count($docentes) === 1) {
            CursoModel::asignarDocenteCurso($idCurso, $docentes[0]);
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
