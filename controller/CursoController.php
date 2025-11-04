<?php
session_start();
require_once __DIR__ . '/../model/CursoModel.php';
require_once __DIR__ . '/../model/AlumnoModel.php';

class CursoController {

    public static function crearCurso() {
        if (isset($_POST['nombre'], $_POST['descripcion'])) {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);

            if (empty($nombre) || empty($descripcion)) {
                $_SESSION['error'] = "Por favor complete todos los campos.";
                header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/CrearCurso.php");
                exit;
            }

            try {
                $ok = CursoModel::crearCurso($nombre, $descripcion);
                if ($ok) {
                    $_SESSION['success'] = "Curso creado correctamente.";
                } else {
                    $_SESSION['error'] = "No se pudo crear el curso.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error interno: " . $e->getMessage();
            }

            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/CrearCurso.php");
            exit;
        } else {
            $_SESSION['error'] = "Datos incompletos enviados.";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/CrearCurso.php");
            exit;
        }
    }

    public static function asignarDocentes() {
        if (isset($_POST['idCurso'], $_POST['docentes'])) {
            $idCurso = $_POST['idCurso'];
            $docentes = $_POST['docentes'];
            CursoModel::asignarDocentes($idCurso, $docentes);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/HomeCurso.php");
            exit;
        }
    }

    public static function eliminarCurso($idCurso) {
        if (!empty($idCurso)) {
            CursoModel::eliminarCurso($idCurso);
            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/HomeCurso.php");
            exit;
        }
    }


    public static function matricularEstudiantes() {
        if (isset($_POST['idCurso'], $_POST['estudiantes'])) {
            $idCurso = $_POST['idCurso'];
            $estudiantes = $_POST['estudiantes'];

            try {
                CursoModel::matricularEstudiantes($idCurso, $estudiantes);
                $_SESSION['success'] = "Estudiantes matriculados correctamente.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al matricular: " . $e->getMessage();
            }

            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/MatricularEstudiantes.php");
            exit;
        } else {
            $_SESSION['error'] = "Datos incompletos para matricular.";
            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/MatricularEstudiantes.php");
            exit;
        }
    }


    public static function obtenerAlumnos($docenteId = null) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=aulavirtual;charset=utf8", "root", "");
            $alumnoModel = new AlumnoModel($pdo);
            return $alumnoModel->obtenerAlumnosDeDocente($docenteId);
        } catch (Exception $e) {
            return [];
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'], $_POST['descripcion'])) {
        CursoController::crearCurso();
    } elseif (isset($_POST['idCurso'], $_POST['docentes'])) {
        CursoController::asignarDocentes();
    } elseif (isset($_POST['idCursoEliminar'])) {
        CursoController::eliminarCurso($_POST['idCursoEliminar']);
    } elseif (isset($_POST['accion']) && $_POST['accion'] === 'matricularEstudiantes') {
        CursoController::matricularEstudiantes();
    } else {
        $_SESSION['error'] = "Solicitud no reconocida.";
        header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/HomeCurso.php");
        exit;
    }
}
?>
