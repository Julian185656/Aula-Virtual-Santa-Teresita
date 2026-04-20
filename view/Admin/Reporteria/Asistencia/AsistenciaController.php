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

        // Importante: Calcular total de páginas para la vista
        $totalPages = ceil($totalRegistros / $limite);

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

    public function enviarAlertaCritica()
    {
        $email = $_POST['email'] ?? null;
        $nombre = $_POST['nombre'] ?? 'Estudiante';
        $faltas = $_POST['faltas'] ?? 'varias';

        if ($email) {
            $asunto = "Alerta de Ausentismo - Escuela Santa Teresita";
            $contenido = "
                <div style='font-family: sans-serif; border: 1px solid #eee; padding: 20px;'>
                    <h2 style='color: #d9534f;'>Aviso de Inasistencias</h2>
                    <p>Estimado padre de familia o tutor de <b>$nombre</b>,</p>
                    <p>Le informamos que el estudiante ha acumulado un total de <b>$faltas faltas</b> en lo que va del periodo.</p>
                    <p>Le solicitamos presentarse a la institución para justificar estas ausencias o comunicarse con la administración.</p>
                    <br>
                    <p>Atentamente,<br>Administración Escuela Santa Teresita</p>
                </div>";
            
            // Llamamos a la función del Mailer
            $enviado = EnviarCorreo($asunto, $contenido, $email);
            
            // REDIRECCIÓN DINÁMICA: Vuelve al mismo archivo pasando el resultado por la URL
            $url = $_SERVER['PHP_SELF'] . "?alerta_enviada=" . ($enviado ? '1' : '0');
            header("Location: $url");
            exit;
        }
    }
}

// --- Lógica de arranque del Controlador ---

$controller = new AsistenciaController();

if (isset($_GET['exportar']) && $_GET['exportar'] == '1') {
    $controller->exportarCSV();
} 
elseif (isset($_POST['accion']) && $_POST['accion'] == 'enviarAlerta') {
    /**
     * IMPORTANTE: Ajustamos la ruta para cargar el Mailer (EmailHelper / MailController)
     * Usamos DOCUMENT_ROOT para que no falle sin importar la carpeta.
     */
    $rutaMailer = $_SERVER["DOCUMENT_ROOT"] . '/Aula-Virtual-Santa-Teresita/controller/EmailHelper.php';
    
    if (file_exists($rutaMailer)) {
        require_once $rutaMailer;
        $controller->enviarAlertaCritica();
    } else {
        // Si no lo encuentra, te avisará exactamente dónde lo está buscando
        die("Error: No se encontró el archivo del Mailer en: " . $rutaMailer);
    }
} 
else {
    // Por defecto, mostrar el reporte
    $controller->mostrarReporte();
}