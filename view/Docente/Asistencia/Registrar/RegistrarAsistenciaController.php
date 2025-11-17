
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
        // Obtiene el ID de usuario desde la sesión y lo usa como Id_Docente (IDs iguales 1:1)
        $this->docenteId = $this->obtenerDocenteIdDeSesion();
    }

    /** Carga la vista principal: cursos, alumnos (15/pág) y asistencia precargada */
    public function mostrarFormulario(string $mensaje = null, string $tipo = 'info')
    {
        try {
            // --- Lectura de parámetros GET
            $cursoId = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
            $fecha   = isset($_GET['fecha']) ? trim($_GET['fecha']) : date('Y-m-d');
            $pagina  = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
            $limite  = 15;

            // --- Cursos asignados al docente
            $cursos = $this->docenteId ? $this->model->obtenerCursosDocente($this->docenteId) : [];

            // --- Datos para la tabla (solo si hay curso seleccionado)
            $alumnos = [];
            $totalRegistros = 0;
            $asistenciaMap = [];

            if ($cursoId > 0) {
                $resultado    = $this->model->obtenerAlumnosPaginado($cursoId, $pagina, $limite);
                $alumnos      = $resultado['alumnos'] ?? [];
                $totalRegistros = $resultado['total'] ?? 0;

                // Precarga de asistencia del día
                $asistenciaMap = $this->model->obtenerAsistenciaDia($cursoId, $fecha);
            }

            // --- Cálculo de paginación (para la vista)
            $totalPaginas = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limite) : 1;
        } catch (\Exception $e) {
            $mensaje = "❌ Error al cargar la asistencia: " . $e->getMessage();
            $tipo = "danger";
            // En caso de error, dejamos arrays vacíos para que la vista muestre estados vacíos
            $cursos = $cursos ?? [];
            $alumnos = [];
            $asistenciaMap = [];
            $cursoId = $cursoId ?? 0;
            $fecha = $fecha ?? date('Y-m-d');
            $pagina = $pagina ?? 1;
            $totalRegistros = 0;
            $totalPaginas = 1;
        }

        // Renderiza la vista (mismo patrón que tu proyecto)
        include "RegistrarAsistencia.php";
    }

    /** Endpoint de guardado (POST): acepta JSON o form tradicional */
    public function guardar()
    {
        // Forzar respuesta JSON si es AJAX; si no lo es, igual devolvemos JSON y la vista lo manejará con toasts.
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!$this->docenteId) {
                http_response_code(401);
                echo json_encode(['ok' => false, 'mensaje' => 'No autorizado.']);
                return;
            }

            // Acepta JSON: { curso, fecha, items: [ {Id_Estudiante, Presente}, ... ] }
            $raw = file_get_contents('php://input');
            $payload = json_decode($raw, true);

            if (is_array($payload) && isset($payload['curso'])) {
                $cursoId = (int)$payload['curso'];
                $fecha   = isset($payload['fecha']) ? (string)$payload['fecha'] : date('Y-m-d');
                $items   = isset($payload['items']) && is_array($payload['items']) ? $payload['items'] : [];
            } else {
                // Form tradicional (por si decides postear sin fetch)
                $cursoId = isset($_POST['curso']) ? (int)$_POST['curso'] : 0;
                $fecha   = isset($_POST['fecha']) ? (string)$_POST['fecha'] : date('Y-m-d');

                // Espera arrays tipo estudiante_id[] y presente[estudiante_id]
                $items = [];
                if (isset($_POST['estudiante_id']) && is_array($_POST['estudiante_id'])) {
                    foreach ($_POST['estudiante_id'] as $estId) {
                        $estId = (int)$estId;
                        // Si viene presente[ID]=1, se marca 1; si no, 0
                        $pres = 0;
                        if (isset($_POST['presente'][$estId])) {
                            $pres = (int)($_POST['presente'][$estId] == '1');
                        }
                        $items[] = ['Id_Estudiante' => $estId, 'Presente' => $pres];
                    }
                }
            }

            if ($cursoId <= 0) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'mensaje' => 'Curso inválido.']);
                return;
            }

            // Guardado en lote
            $res = $this->model->guardarLoteAsistencia($cursoId, $fecha, $this->docenteId, $items);

            echo json_encode([
                'ok' => $res['ok'],
                'procesados' => $res['procesados'],
                'mensaje' => $res['ok']
                    ? "✅ Asistencia guardada. Registros procesados: {$res['procesados']}."
                    : "No se pudo guardar la asistencia."
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'mensaje' => "❌ Error al guardar: " . $e->getMessage()
            ]);
        }
    }

    /** Obtiene el ID de usuario (docente) desde la sesión con tus convenciones */
    private function obtenerDocenteIdDeSesion(): ?int
    {
        // Compatibilidad con tus dos formas de guardar la sesión (según Home.php)
        $rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
        $id  = $_SESSION['usuario']['Id_Usuario'] ?? ($_SESSION['id_usuario'] ?? null);

        if (!$id || $rol !== 'Docente') {
            return null; // restringe la funcionalidad a Docente
        }
        return (int)$id;
    }
}

// --- Router simple estilo del proyecto ---
$controller = new RegistrarAsistenciaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Endpoint AJAX/POST para guardar
    $controller->guardar();
} else {
    // Vista principal (GET)
    $controller->mostrarFormulario();
}
