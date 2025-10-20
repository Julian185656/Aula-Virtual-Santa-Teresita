<?php
require_once "AsistenciaModel.php";

class AsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new AsistenciaModel();
    }

    public function mostrarReporte($mensaje = null, $tipo = 'info')
    {
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 15;
        $offset = ($pagina - 1) * $limite;

        $reporte = [];
        $fechas = [];

        try {
            $reporte = $this->model->obtenerReporte($fecha, $offset, $limite);
            $fechas = $this->model->obtenerFechas();
        } catch (Exception $e) {
            $mensaje = "❌ Error al obtener datos: " . $e->getMessage();
            $tipo = "danger";
        }

        include "ReporteAsistencia.php";
    }

    public function exportarCSV()
    {
        $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
        $reporte = $this->model->obtenerReporte($fecha, 0, 9999);

        if (!$reporte || count($reporte) === 0) {
            $this->mostrarReporte("⚠️ No hay registros para exportar.", "warning");
            return;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_asistencia.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array_keys($reporte[0]));
        foreach ($reporte as $fila) {
            fputcsv($output, $fila);
        }
        fclose($output);
        exit;
    }
}

$controller = new AsistenciaController();

if (isset($_GET['exportar']) && $_GET['exportar'] == '1') {
    $controller->exportarCSV();
} else {
    $controller->mostrarReporte();
}
