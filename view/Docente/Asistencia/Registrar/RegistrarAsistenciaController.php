
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
        $fecha   = isset($_GET['fecha']) ? trim($_GET['fecha']) : date('Y-m-d');
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
            header("Location: RegistrarAsistenciaController.php?error=unauthorized");
            exit;
        }

        $cursoId = isset($_POST['curso']) ? (int)$_POST['curso'] : 0;
        $fecha   = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');

        if ($cursoId <= 0) {
            header("Location: RegistrarAsistenciaController.php?error=curso");
            exit;
        }

        $items = [];
        if (isset($_POST['estudiante_id']) && is_array($_POST['estudiante_id'])) {
            foreach ($_POST['estudiante_id'] as $estId) {
                $estId = (int)$estId;
                $pres = isset($_POST['estado'][$estId]) ? (int)$_POST['estado'][$estId] : 0;

                $items[] = [
                    'Id_Estudiante' => $estId,
                    'Presente' => $pres
                ];
            }
        }

        $res = $this->model->guardarLoteAsistencia(
            $cursoId,
            $fecha,
            $this->docenteId,
            $items
        );

        header("Location: RegistrarAsistenciaController.php?curso=$cursoId&fecha=$fecha&ok=1");
        exit;

    } catch (\Exception $e) {
        header("Location: RegistrarAsistenciaController.php?error=500");
        exit;
    }
}


   
private function obtenerDocenteIdDeSesion(): ?int
{
    // Verifica si existe sesión de usuario
    $usuario = $_SESSION['usuario'] ?? null;
    if (!$usuario) return null;

    $rol = $usuario['Rol'] ?? $usuario['rol'] ?? '';
    $id  = $usuario['Id_Usuario'] ?? $_SESSION['id_usuario'] ?? null;

    // Compara el rol en minúsculas para evitar problemas de mayúsculas
    if (!$id || strtolower(trim($rol)) !== 'docente') {
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
