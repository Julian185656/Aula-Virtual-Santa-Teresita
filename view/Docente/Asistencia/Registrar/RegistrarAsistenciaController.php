
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

    
    public function mostrarFormulario(string $mensaje = null, string $tipo = 'info')
    {
        try {
         
            $cursoId = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
            $fecha   = isset($_GET['fecha']) ? trim($_GET['fecha']) : date('Y-m-d');
            $pagina  = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
            $limite  = 15;

         
            $cursos = $this->docenteId ? $this->model->obtenerCursosDocente($this->docenteId) : [];

        
            $alumnos = [];
            $totalRegistros = 0;
            $asistenciaMap = [];

            if ($cursoId > 0) {
                $resultado    = $this->model->obtenerAlumnosPaginado($cursoId, $pagina, $limite);
                $alumnos      = $resultado['alumnos'] ?? [];
                $totalRegistros = $resultado['total'] ?? 0;

           
                $asistenciaMap = $this->model->obtenerAsistenciaDia($cursoId, $fecha);
            }

          
            $totalPaginas = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limite) : 1;
        } catch (\Exception $e) {
            $mensaje = "❌ Error al cargar la asistencia: " . $e->getMessage();
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
       
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!$this->docenteId) {
                http_response_code(401);
                echo json_encode(['ok' => false, 'mensaje' => 'No autorizado.']);
                return;
            }

           
            $raw = file_get_contents('php://input');
            $payload = json_decode($raw, true);

            if (is_array($payload) && isset($payload['curso'])) {
                $cursoId = (int)$payload['curso'];
                $fecha   = isset($payload['fecha']) ? (string)$payload['fecha'] : date('Y-m-d');
                $items   = isset($payload['items']) && is_array($payload['items']) ? $payload['items'] : [];
            } else {
              
                $cursoId = isset($_POST['curso']) ? (int)$_POST['curso'] : 0;
                $fecha   = isset($_POST['fecha']) ? (string)$_POST['fecha'] : date('Y-m-d');

                
                $items = [];
                if (isset($_POST['estudiante_id']) && is_array($_POST['estudiante_id'])) {
                    foreach ($_POST['estudiante_id'] as $estId) {
                        $estId = (int)$estId;
                     
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

   
    private function obtenerDocenteIdDeSesion(): ?int
    {
      
        $rol = $_SESSION['usuario']['Rol'] ?? ($_SESSION['rol'] ?? null);
        $id  = $_SESSION['usuario']['Id_Usuario'] ?? ($_SESSION['id_usuario'] ?? null);

        if (!$id || $rol !== 'Docente') {
            return null; 
        }
        return (int)$id;
    }
}


$controller = new RegistrarAsistenciaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $controller->guardar();
} else {

    $controller->mostrarFormulario();
}
