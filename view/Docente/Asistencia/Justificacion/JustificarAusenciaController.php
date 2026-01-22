<?php
session_start();
require_once "JustificacionAsistenciaModel.php";

class JustificacionAsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new JustificacionAsistenciaModel();
    }

    public function index()
    {
     
        $mensaje = null;
        $tipo    = 'info';

        
        $docenteId = $_SESSION['usuario']['Id_Usuario'] ?? ($_SESSION['id_usuario'] ?? null);
        if (!$docenteId) {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit;
        }

      
        $cursoId    = isset($_GET['curso'])  ? (int)$_GET['curso']  : 0;
        $pagina     = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        $fechaDesde = $_GET['desde'] ?? null;
        $fechaHasta = $_GET['hasta'] ?? null;

       
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cursoIdPost   = (int)($_POST['curso']      ?? 0);
            $estudianteId  = (int)($_POST['estudiante'] ?? 0);
            $fecha         = $_POST['fecha']           ?? null;
            $comentario    = $_POST['comentario']      ?? '';
            
            $fechaDesde    = $_POST['desde']           ?? $fechaDesde;
            $fechaHasta    = $_POST['hasta']           ?? $fechaHasta;
            $pagina        = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : $pagina;

         
            if ($cursoIdPost > 0) {
                $cursoId = $cursoIdPost;
            }

            try {
                if ($cursoId > 0 && $estudianteId > 0 && !empty($fecha)) {
                    $ok = $this->model->marcarAusenciaJustificada(
                        $cursoId,
                        $estudianteId,
                        $fecha,
                        $docenteId,
                        $comentario
                    );

                    if ($ok) {
                        $mensaje = "✅ Ausencia justificada correctamente";
                        $tipo    = "success";
                    } else {
                        $mensaje = "No se pudo justificar la ausencia (verifica que siga pendiente).";
                        $tipo    = "warning";
                    }
                } else {
                    $mensaje = "Datos incompletos para justificar la ausencia.";
                    $tipo    = "danger";
                }
            } catch (\Exception $e) {
                $mensaje = "Error al justificar la ausencia: " . $e->getMessage();
                $tipo    = "danger";
            }
        }

   
        $cursos         = [];
        $ausencias      = [];
        $totalRegistros = 0;
        $totalPaginas   = 1;
        $limite         = 15;

        try {
            $cursos = $this->model->obtenerCursosDocente($docenteId);

         
            if ($cursoId > 0) {
                $resultado = $this->model->obtenerAusenciasPendientes(
                    $docenteId,
                    $cursoId,
                    $fechaDesde,
                    $fechaHasta,
                    $pagina,
                    $limite
                );

                $ausencias      = $resultado['ausencias'] ?? [];
                $totalRegistros = $resultado['total']     ?? 0;
                $totalPaginas   = $totalRegistros > 0
                    ? (int)ceil($totalRegistros / $limite)
                    : 1;
            }
        } catch (\Exception $e) {
           
            if (!$mensaje) {
                $mensaje = "Error al cargar las ausencias: " . $e->getMessage();
                $tipo    = "danger";
            }
        }

  
        include "JustificacionAsistencia.php";
    }
}

$controller = new JustificacionAsistenciaController();
$controller->index();