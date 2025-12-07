<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/MaterialModel.php";

/**********************************************************
 * VALIDACIÓN DE ACCESO
 **********************************************************/

// Obtener rol sin importar estructura
$rol = null;

// Caso 1: Login guarda $_SESSION['usuario']['rol']
if (isset($_SESSION['usuario']['rol'])) {
    $rol = strtolower($_SESSION['usuario']['rol']);
}
// Caso 2: Login guarda $_SESSION['rol']
elseif (isset($_SESSION['rol'])) {
    $rol = strtolower($_SESSION['rol']);
}

if (!isset($_SESSION['id_usuario']) || !in_array($rol, ['administrador', 'docente'])) {
    header("Location: /Aula-Virtual-Santa-Teresita/view/Login/Login.php?error=NoAutorizado");
    exit();
}

/**********************************************************
 * INSTANCIA DEL MODELO
 **********************************************************/
$model = new MaterialModel($pdo);
$idUsuario = $_SESSION['id_usuario'];

/**********************************************************
 * SUBIR ARCHIVO
 **********************************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';

    if ($accion === 'subir') {

        $cursoId     = intval($_POST['Id_Curso']);
        $titulo      = trim($_POST['Titulo']);
        $descripcion = trim($_POST['Descripcion'] ?? '');
        $archivo     = $_FILES['archivo'];

        if ($archivo['error'] === 0) {

            $nombreArchivo = time() . "_" . basename($archivo['name']);

            $directorio = $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/uploads/materiales/";
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $rutaDestino = $directorio . $nombreArchivo;

            move_uploaded_file($archivo['tmp_name'], $rutaDestino);

            $rutaBD = "/Aula-Virtual-Santa-Teresita/uploads/materiales/" . $nombreArchivo;

            $model->guardarMaterial($cursoId, $idUsuario, $titulo, $descripcion, $rutaBD);

            // Redirección correcta según rol
            if ($rol === "administrador") {
                header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=$cursoId&msg=subido");
            } else {
                header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Material.php?curso=$cursoId&msg=subido");
            }
            exit();
        }

        header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Material.php?curso=$cursoId&msg=error");
        exit();
    }

    /**********************************************************
     * ELIMINAR ARCHIVO
     **********************************************************/
    if ($accion === 'eliminar') {

        $idMaterial = intval($_POST['idMaterial']);
        $cursoId    = intval($_POST['Id_Curso']);

        $material = $model->obtenerMaterialPorID($idMaterial);

        if ($material) {
            $ruta = $_SERVER["DOCUMENT_ROOT"] . $material['Archivo_URL'];
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        $model->eliminarMaterial($idMaterial);

        if ($rol === "administrador") {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Admin/MaterialAdmin.php?curso=$cursoId&msg=eliminado");
        } else {
            header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/Material.php?curso=$cursoId&msg=eliminado");
        }
        exit();
    }
}

/**********************************************************
 * DESCARGAR ARCHIVO
 **********************************************************/
if (isset($_GET['accion']) && $_GET['accion'] === 'descargar') {

    $idMaterial = intval($_GET['id']);
    $material = $model->obtenerMaterialPorID($idMaterial);

    if ($material) {

        $ruta = $_SERVER["DOCUMENT_ROOT"] . $material['Archivo_URL'];

        if (file_exists($ruta)) {
            header("Content-Disposition: attachment; filename=" . basename($material['Archivo_URL']));
            header("Content-Type: application/octet-stream");
            readfile($ruta);
        }
    }

    exit();
}
?>
