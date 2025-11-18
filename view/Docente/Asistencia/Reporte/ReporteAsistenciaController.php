<?php
session_start();
require_once "ReporteAsistenciaModel.php";

class ReporteAsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new ReporteAsistenciaModel();
    }

    public function manejar()
    {
        // ===== 1. Validar sesión =====
        if (!isset($_SESSION['usuario'])) {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $rol     = $usuario['Rol'] ?? ($_SESSION['rol'] ?? null);

        if ($rol !== 'Docente') {
            http_response_code(403);
            echo "Acceso no autorizado.";
            exit;
        }

        // ===== 2. Obtener ID del docente igual que en los otros módulos =====
        // (por si en tu sesión lo guardas como Id_Docente o Id_Usuario)
        $docenteId = 0;

        if (isset($usuario['Id_Docente'])) {
            $docenteId = (int)$usuario['Id_Docente'];
        } elseif (isset($usuario['Id_Usuario'])) {
            $docenteId = (int)$usuario['Id_Usuario'];
        } elseif (isset($_SESSION['id_usuario'])) {
            $docenteId = (int)$_SESSION['id_usuario'];
        }

        $cursos      = [];
        $alumnos     = [];
        $mensaje     = null;
        $tipo        = 'info';

        $cursoId     = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
        $desde       = $_GET['desde'] ?? '';
        $hasta       = $_GET['hasta'] ?? '';
        $idEst       = isset($_GET['estudiante']) ? (int)$_GET['estudiante'] : 0;
        $exportFlag  = isset($_GET['export']) ? (int)$_GET['export'] : 0;

        try {
            if ($docenteId <= 0) {
                throw new \Exception("No se pudo determinar el ID del docente desde la sesión.");
            }

            // ===== 3. Cursos del docente =====
            $cursos = $this->model->obtenerCursosDocente($docenteId);

            // Si hay curso seleccionado, cargamos estudiantes
            if ($cursoId > 0) {
                $alumnos = $this->model->obtenerEstudiantesCurso($cursoId);
            }

            // ===== 4. Exportación CSV =====
            if ($exportFlag === 1 && $cursoId > 0) {
                $this->exportarCsv($cursoId, $desde, $hasta, $idEst);
                return; // IMPORTANTE: no seguir a la vista
            }
        } catch (\Exception $e) {
            $mensaje = "Error al cargar datos: " . $e->getMessage();
            $tipo    = "danger";
        }

        // Variables que usa la vista
        $cursoSeleccionado = $cursoId;
        $desdeSel          = $desde;
        $hastaSel          = $hasta;
        $estudianteSel     = $idEst;

        include "ReporteAsistencia.php";
    }

    // ===== Exportar CSV =====
    private function exportarCsv(int $cursoId, ?string $desde, ?string $hasta, ?int $idEstudiante = null)
    {
        try {
            $rows = $this->model->obtenerReporteCurso($cursoId, $desde, $hasta, $idEstudiante ?: null);
        } catch (\Exception $e) {
            header('Content-Type: text/plain; charset=utf-8');
            echo "Error al generar el reporte: " . $e->getMessage();
            return;
        }

        $nombreArchivo = "reporte_asistencia_curso_{$cursoId}_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');

        // BOM para que Excel reconozca UTF-8
        echo "\xEF\xBB\xBF";

        $out = fopen('php://output', 'w');

        // Cabecera
        fputcsv($out, [
            'Fecha',
            'ID Estudiante',
            'Estudiante',
            'Email',
            'Curso',
            'Presente',
            'Justificada',
            'Comentario'
        ]);

        foreach ($rows as $r) {
            $presenteTxt    = ((int)$r['Presente'] === 1) ? 'Presente' : 'Ausente';
            $justificadaTxt = ((int)($r['Justificada'] ?? 0) === 1) ? 'Sí' : 'No';

            fputcsv($out, [
                $r['Fecha'],
                $r['Id_Estudiante'],
                $r['Estudiante'],
                $r['Email'],
                $r['Curso'],
                $presenteTxt,
                $justificadaTxt,
                $r['Comentario_Justificacion']
            ]);
        }

        fclose($out);
        exit;
    }
}

$controller = new ReporteAsistenciaController();
$controller->manejar();
