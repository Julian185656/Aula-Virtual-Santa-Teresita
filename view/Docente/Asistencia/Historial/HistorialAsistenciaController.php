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
        // === 1. Verificar docente logueado ===
        $docenteId = $_SESSION['usuario']['Id_Usuario'] ?? ($_SESSION['id_usuario'] ?? null);
        if (!$docenteId) {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit;
        }

        // === 2. Parámetros de filtros ===
        $cursoId    = isset($_GET['curso'])   ? (int)$_GET['curso']   : 0;
        $alumnoId   = isset($_GET['alumno'])  ? (int)$_GET['alumno']  : 0;
        $pagina     = isset($_GET['pagina'])  ? max(1, (int)$_GET['pagina']) : 1;
        $limite     = 15;

        $fechaDesde = $_GET['desde'] ?? null;
        $fechaHasta = $_GET['hasta'] ?? null;

        // === 3. Obtener cursos del docente ===
        $cursos = [];
        $alumnos = [];
        $historial = [];
        $resumen = ['presentes' => 0, 'ausentes' => 0];
        $totalRegistros = 0;
        $totalPaginas   = 1;
        $alumnoNombre   = null;
        $cursoNombre    = null;

        try {
            $cursos = $this->model->obtenerCursosDocente($docenteId);

            // Si se seleccionó curso, cargamos alumnos de ese curso
            if ($cursoId > 0) {
                $alumnos = $this->model->obtenerAlumnosCurso($cursoId);
            }

            // Si además hay alumno seleccionado, cargamos su historial
            if ($cursoId > 0 && $alumnoId > 0) {

                $resultado = $this->model->obtenerHistorialAlumno(
                    $cursoId,
                    $alumnoId,
                    $fechaDesde,
                    $fechaHasta,
                    $pagina,
                    $limite
                );

                $historial      = $resultado['historial'] ?? [];
                $totalRegistros = $resultado['total']     ?? 0;
                $resumen        = $resultado['resumen']   ?? ['presentes' => 0, 'ausentes' => 0];

                $totalPaginas = $totalRegistros > 0
                    ? (int)ceil($totalRegistros / $limite)
                    : 1;

                // Intentar obtener nombre del alumno / curso
                if (!empty($historial)) {
                    $alumnoNombre = $historial[0]['Estudiante'] ?? null;
                    $cursoNombre  = $historial[0]['Curso']      ?? null;
                } else {
                    // fallback: buscar en la lista de alumnos
                    foreach ($alumnos as $al) {
                        if ((int)$al['Id_Estudiante'] === $alumnoId) {
                            $alumnoNombre = $al['Nombre'];
                            break;
                        }
                    }
                    // fallback curso desde lista cursos
                    foreach ($cursos as $c) {
                        if ((int)$c['Id_Curso'] === $cursoId) {
                            $cursoNombre = $c['Curso'] ?? ($c['Nombre'] ?? null);
                            break;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $mensajeError = $e->getMessage();
            // Podríamos mandar este mensaje a la vista si quieres mostrar un toast.
        }

        // === 4. Incluir la vista ===
        include "HistorialAsistencia.php";
    }
}

$controller = new HistorialAsistenciaController();
$controller->index();
