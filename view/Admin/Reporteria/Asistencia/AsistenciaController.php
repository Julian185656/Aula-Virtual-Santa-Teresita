<?php
require_once "AsistenciaModel.php";

class AsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new AsistenciaModel();
    }

    public function mostrarReporte()
    {
        $fecha = $_GET['fecha'] ?? null;
        $cursoId = isset($_GET['curso']) && $_GET['curso'] !== '' ? (int)$_GET['curso'] : null;
        $pagina = max(1, intval($_GET['pagina'] ?? 1));
        $limite = 10;
        $offset = ($pagina - 1) * $limite;

        $cursos = $this->model->obtenerCursos();
        $fechas = $this->model->obtenerFechas();
        $totalRegistros = $this->model->contarRegistros($fecha, $cursoId);
        $reporte = $this->model->obtenerReporte($fecha, $cursoId, $offset, $limite);

        include "ReporteAsistencia.php";
    }

    public function exportarCSV()
    {
        $fecha = $_GET['fecha'] ?? null;
        $cursoId = isset($_GET['curso']) && $_GET['curso'] !== '' ? (int)$_GET['curso'] : null;

        $reporte = $this->model->obtenerReporte($fecha, $cursoId, 0, 9999);

        if (empty($reporte)) {
            $this->mostrarReporte();
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

// Ejecutar
$controller = new AsistenciaController();
if (isset($_GET['exportar']) && $_GET['exportar'] == '1') {
    $controller->exportarCSV();
} else {
    $controller->mostrarReporte();
}
