<?php
session_start();

require_once __DIR__ . '/../../../../model/db.php';
require_once __DIR__ . '/ParticipacionModel.php';

$model = new ParticipacionModel();


$periodo = isset($_GET['periodo']) && $_GET['periodo'] !== '' ? $_GET['periodo'] : null;
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$limite = 15;
$offset = ($pagina - 1) * $limite;


$reporte = $model->obtenerReporteParticipacion($periodo, $offset, $limite);
$periodos = $model->obtenerPeriodos();


$totalRegistros = is_array($reporte) ? count($reporte) : 0;
$totalPaginas = ($totalRegistros < $limite) ? 1 : ceil($totalRegistros / $limite);


if (isset($_GET['exportar']) && $_GET['exportar'] === 'pdf') {

    $rutaFPDF = __DIR__ . '/../../../vendor/fpdf/fpdf.php';


    if (!file_exists($rutaFPDF)) {
        echo "<h4 style='color:red;text-align:center;margin-top:50px'>
            ❌ No se encontró la librería FPDF.<br>
            Colócala en: <code>view/vendor/fpdf/fpdf.php</code>
        </h4>";
        exit;
    }

    require_once $rutaFPDF;
    date_default_timezone_set('America/Costa_Rica');
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, utf8_decode('Reporte General de Participación'), 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 6, utf8_decode('Generado el: ' . date('Y-m-d H:i')), 0, 1, 'C');
            $this->Ln(4);


            $this->SetFillColor(0, 74, 173);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(50, 8, utf8_decode('Estudiante'), 1, 0, 'C', true);
            $this->Cell(40, 8, utf8_decode('Curso'), 1, 0, 'C', true);
            $this->Cell(40, 8, utf8_decode('Docente'), 1, 0, 'C', true);
            $this->Cell(25, 8, utf8_decode('Periodo'), 1, 0, 'C', true);
            $this->Cell(25, 8, utf8_decode('Promedio'), 1, 0, 'C', true);
            $this->Cell(40, 8, utf8_decode('Valoración'), 1, 1, 'C', true);
            $this->SetTextColor(0, 0, 0);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }


    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 9);

    if (!empty($reporte)) {
        foreach ($reporte as $fila) {
            $pdf->Cell(50, 8, utf8_decode($fila['Estudiante']), 1);
            $pdf->Cell(40, 8, utf8_decode($fila['Curso']), 1);
            $pdf->Cell(40, 8, utf8_decode($fila['Docente']), 1);
            $pdf->Cell(25, 8, utf8_decode($fila['Periodo']), 1, 0, 'C');
            $pdf->Cell(25, 8, utf8_decode($fila['PromedioParticipacion']), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($fila['ValoracionCualitativa']), 1, 1, 'C');
        }
    } else {
        $pdf->Cell(0, 10, utf8_decode('No hay registros para mostrar.'), 1, 1, 'C');
    }


    $nombreArchivo = 'Reporte_Participacion_' . ($periodo ?? 'General') . '.pdf';
    $pdf->Output('I', $nombreArchivo);
    exit;
}


require_once __DIR__ . '/ReporteParticipacion.php';
