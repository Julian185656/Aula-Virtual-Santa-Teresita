<?php
session_start();
require_once "RegistrarAsistenciaModel.php";

class RegistrarAsistenciaController
{
    private $model;
    private $docenteId;

    public function __construct()
    {
        $this->model = new RegistrarAsistenciaModel();
        $this->docenteId = $this->obtenerDocenteIdDeSesion();
    }

    public function mostrarFormulario(?string $mensaje = null, string $tipo = 'info')
    {
        try {
            $cursoId = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
            $fecha   = $_GET['fecha'] ?? date('Y-m-d');
            $pagina  = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
            $limite  = 15;

            $cursos = $this->docenteId ? $this->model->obtenerCursosDocente($this->docenteId) : [];
            $alumnos = [];
            $totalRegistros = 0;
            $asistenciaMap = [];

            if ($cursoId > 0) {
                $resultado = $this->model->obtenerAlumnosPaginado($cursoId, $pagina, $limite);
                $alumnos = $resultado['alumnos'] ?? [];
                $totalRegistros = $resultado['total'] ?? 0;
                $asistenciaMap = $this->model->obtenerAsistenciaDia($cursoId, $fecha);
            }

            $totalPaginas = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limite) : 1;
        } catch (\Exception $e) {
            $mensaje = " Error al cargar la asistencia: " . $e->getMessage();
            $tipo = "danger";
            $cursos = $cursos ?? [];
            $alumnos = [];
            $asistenciaMap = [];
            $cursoId = $cursoId ?? 0;
            $fecha = $fecha ?? date('Y-m-d');
            $pagina = $pagina ?? 1;
            $totalRegistros = 0;
            $totalPaginas = 1;
        }

        include "RegistrarAsistencia.php";
    }

    public function guardar()
    {
        try {
            if (!$this->docenteId) {
                header("HTTP/1.1 401 Unauthorized");
                echo json_encode(['ok' => false, 'mensaje' => 'No autorizado']);
                exit;
            }

            // Detectar JSON POST (AJAX)
            $data = $_POST;
            if (empty($data)) {
                $data = json_decode(file_get_contents('php://input'), true) ?: [];
                if (!empty($data['items'])) {
                    $_POST['curso'] = $data['curso'] ?? 0;
                    $_POST['fecha'] = $data['fecha'] ?? date('Y-m-d');
                    $_POST['estudiante_id'] = array_column($data['items'], 'Id_Estudiante');
                    $_POST['estado'] = array_combine(
                        array_column($data['items'], 'Id_Estudiante'),
                        array_column($data['items'], 'Presente')
                    );
                }
            }

            $cursoId = (int)($_POST['curso'] ?? 0);
            $fecha   = $_POST['fecha'] ?? date('Y-m-d');

            if ($cursoId <= 0) {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['ok' => false, 'mensaje' => 'Curso inválido']);
                exit;
            }

            $items = [];
            if (isset($_POST['estudiante_id']) && is_array($_POST['estudiante_id'])) {
                foreach ($_POST['estudiante_id'] as $estId) {
                    $estId = (int)$estId;
                    $pres = isset($_POST['estado'][$estId]) ? (int)$_POST['estado'][$estId] : 0;
                    $items[] = ['Id_Estudiante' => $estId, 'Presente' => $pres];
                }
            }

            $res = $this->model->guardarLoteAsistencia($cursoId, $fecha, $this->docenteId, $items);

            echo json_encode(['ok' => true, 'procesados' => $res['procesados'], 'mensaje' => 'Asistencia guardada']);
            exit;

        } catch (\Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(['ok' => false, 'mensaje' => 'Error al guardar la asistencia']);
            exit;
        }
    }

    private function obtenerDocenteIdDeSesion(): ?int
    {
        $usuario = $_SESSION['usuario'] ?? null;
        if (!$usuario) return null;

        $rol = strtolower(trim($usuario['Rol'] ?? $usuario['rol'] ?? ''));
        $id  = $usuario['Id_Usuario'] ?? $_SESSION['id_usuario'] ?? null;

        if (!$id || $rol !== 'docente') return null;
        return (int)$id;
    }
}

// --- Router simple ---
$controller = new RegistrarAsistenciaController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->guardar();
} else {
    $controller->mostrarFormulario();
}