<?php
require_once "RendimientoModel.php";

class RendimientoController
{
    private $model;

    public function __construct()
    {
        $this->model = new RendimientoModel();
    }

    public function mostrarReporte()
    {
        $idCurso = isset($_GET['curso']) ? intval($_GET['curso']) : null;
        $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
        $limite = 15;

        $mensaje = $_GET['msg'] ?? null;
        $tipo = $_GET['tipo'] ?? 'info';

        try {
            // EXPORTAR EXCEL
            if (isset($_GET['export']) && $_GET['export'] == 1) {
                $reporteCompleto = $this->model->obtenerReporte($idCurso, 1, 1000000)['reporte'];

                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=reporte_calificaciones.xls");
                header("Pragma: no-cache");
                header("Expires: 0");

                echo "<table border='1'>
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Docente</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Fecha de Entrega</th>
                        </tr>";

                foreach ($reporteCompleto as $fila) {
                    echo "<tr>
                        <td>{$fila['Estudiante']}</td>
                        <td>{$fila['Curso']}</td>
                        <td>{$fila['Docente']}</td>
                        <td>{$fila['Calificacion']}</td>
                        <td>{$fila['Comentario']}</td>
                        <td>{$fila['Fecha_Entrega']}</td>
                    </tr>";
                }
                echo "</table>";
                exit(); // muy importante para que no cargue más HTML
            }

            // Reporte paginado
            $resultado = $this->model->obtenerReporte($idCurso, $pagina, $limite);
            $reporte = $resultado['reporte'];
            $totalRegistros = $resultado['total'];

            $totalPaginas = ceil($totalRegistros / $limite);
            $paginaActual = $pagina;

            // Resumen
            if (isset($_GET['verResumen']) && $_GET['verResumen'] == 1) {
                $resumen = $this->model->obtenerResumen();
                $mensaje = "✅ Resumen de promedios mostrado correctamente.";
                $tipo = "success";
            }

            $cursos = $this->model->obtenerCursos();

        } catch (Exception $e) {
            $mensaje = "❌ Error: " . $e->getMessage();
            $tipo = "danger";
        }

        include "ReporteCalificaciones.php";
    }
}

// Ejecutar controlador
$controller = new RendimientoController();
$controller->mostrarReporte();
