<?php
require_once "RendimientoModel.php";

class RendimientoController
{
    private $model;

    public function __construct()
    {
        $this->model = new RendimientoModel();
    }

    /**
     * reporte principal de calificaciones
     */
    public function mostrarReporte($mensaje = null, $tipo = 'info')
    {

        $idCurso = isset($_GET['curso']) ? intval($_GET['curso']) : null;
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 15;


        $reporte = [];
        $totalRegistros = 0;
        $resumen = [];

        try {
            //  reporte principal
            $resultado = $this->model->obtenerReporte($idCurso, $pagina, $limite);
            $reporte = $resultado['reporte'];
            $totalRegistros = $resultado['total'];


            if (isset($_GET['verResumen']) && $_GET['verResumen'] == 1) {
                $resumen = $this->model->obtenerResumen();
                $mensaje = "✅ Resumen de promedios mostrado correctamente.";
                $tipo = "success";
            }
        } catch (Exception $e) {
            $mensaje = "❌ Error al cargar el reporte: " . $e->getMessage();
            $tipo = "danger";
        }

        include "ReporteCalificaciones.php";
    }
}


$controller = new RendimientoController();
$controller->mostrarReporte();
