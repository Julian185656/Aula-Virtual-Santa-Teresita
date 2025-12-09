<?php
session_start();
require_once "HistorialAsistenciaModel.php";

class HistorialAsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new HistorialAsistenciaModel();
    }

    public function index()
    {
        $docenteId = $_SESSION['id_usuario'] ?? null;
        if (!$docenteId) {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit;
        }

        $cursoId = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
        $usuarioId = isset($_GET['alumno']) ? (int)$_GET['alumno'] : 0;
        $pagina = isset($_GET['pagina']) ? max(1,(int)$_GET['pagina']) : 1;

        $fechaDesde = $_GET['desde'] ?? null;
        $fechaHasta = $_GET['hasta'] ?? null;

        $cursos = $this->model->obtenerCursosDocente($docenteId);
        $alumnos = ($cursoId>0) ? $this->model->obtenerAlumnosCurso($cursoId) : [];

        $historial = [];
        $resumen = ['presentes'=>0,'ausentes'=>0];
        $totalPaginas = 1;
        $alumnoNombre = null;
        $cursoNombre = null;

        if($cursoId>0 && $usuarioId>0){
            $resultado = $this->model->obtenerHistorialAlumno($cursoId, $usuarioId, $fechaDesde, $fechaHasta, $pagina);
            $historial = $resultado['historial'];
            $resumen = $resultado['resumen'];
            $totalPaginas = ($resultado['total']>0) ? ceil($resultado['total']/15) : 1;

            foreach($alumnos as $al){
                if((int)$al['Id']==$usuarioId){
                    $alumnoNombre = $al['Nombre'];
                    break;
                }
            }
            foreach($cursos as $c){
                if((int)$c['Id_Curso']==$cursoId){
                    $cursoNombre = $c['Curso'] ?? ($c['Nombre'] ?? 'Curso');
                    break;
                }
            }
        }

        include "HistorialAsistencia.php";
    }
}

$controller = new HistorialAsistenciaController();
$controller->index();
