<?php
session_start();
require_once "SolicitudesJustificacionModel.php";

class SolicitudesJustificacionController
{
    private $model;

    public function __construct()
    {
        $this->model = new SolicitudesJustificacionModel();
    }

    public function index()
    {
        $mensaje = null;
        $tipo = 'info';

        // Docente logueado
        $docenteId = $_SESSION['usuario']['Id_Usuario'] ?? ($_SESSION['id_usuario'] ?? null);
        if (!$docenteId) {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php");
            exit;
        }

        // Filtros (GET)
        $cursoId    = isset($_GET['curso']) ? (int)$_GET['curso'] : 0;
        $fechaDesde = $_GET['desde'] ?? null;
        $fechaHasta = $_GET['hasta'] ?? null;
        $estado     = $_GET['estado'] ?? 'pendiente';
        $pagina     = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

        // POST: aprobar/denegar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idJustificacion = (int)($_POST['id'] ?? 0);
            $accion          = $_POST['accion'] ?? '';
            $comentarioDoc   = $_POST['comentario_docente'] ?? null;

            // mantener filtros al volver
            $cursoId    = (int)($_POST['curso'] ?? $cursoId);
            $fechaDesde = $_POST['desde'] ?? $fechaDesde;
            $fechaHasta = $_POST['hasta'] ?? $fechaHasta;
            $estado     = $_POST['estado'] ?? $estado;
            $pagina     = isset($_POST['pagina']) ? max(1, (int)$_POST['pagina']) : $pagina;

            try {
                if ($idJustificacion > 0 && in_array($accion, ['aprobar','denegar'], true)) {
                    $ok = $this->model->resolverSolicitud(
                        $idJustificacion,
                        (int)$docenteId,
                        $accion,
                        $comentarioDoc
                    );

                    if ($ok) {
                        $mensaje = ($accion === 'aprobar')
                            ? "✅ Solicitud aprobada y asistencia marcada como justificada."
                            : "✅ Solicitud denegada.";
                        $tipo = "success";
                    } else {
                        $mensaje = "No se pudo resolver la solicitud. Puede que ya no esté pendiente.";
                        $tipo = "warning";
                    }
                } else {
                    $mensaje = "Datos incompletos para resolver la solicitud.";
                    $tipo = "danger";
                }
            } catch (Exception $e) {
                $mensaje = "Error al resolver la solicitud: " . $e->getMessage();
                $tipo = "danger";
            }
        }

        // Cargar cursos del docente
        $cursos = $this->model->obtenerCursosDocente((int)$docenteId);

        // Cargar solicitudes
        $limite = 15;
        $solicitudes = [];
        $totalRegistros = 0;
        $totalPaginas = 1;

        if ($cursoId > 0) {
            $resultado = $this->model->listarSolicitudes(
                (int)$docenteId,
                $cursoId,
                $fechaDesde,
                $fechaHasta,
                $estado,
                $pagina,
                $limite
            );

            $solicitudes = $resultado['rows'] ?? [];
            $totalRegistros = (int)($resultado['total'] ?? 0);
            $totalPaginas = $totalRegistros > 0 ? (int)ceil($totalRegistros / $limite) : 1;
        }

        include "SolicitudesJustificacion.php";
    }
}

$controller = new SolicitudesJustificacionController();
$controller->index();
