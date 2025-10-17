<?php
session_start();

// ✅ Rutas seguras y absolutas
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/db.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/CursoModel.php";

// ✅ Inicializar modelo
$model = new CursoModel($pdo);

// ✅ Validar sesión activa
$rol = $_SESSION['rol'] ?? '';
$idUsuario = $_SESSION['id_usuario'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {

        // 🧱 ADMINISTRADOR CREA CURSO
        case 'crear_curso':
            if ($rol === 'Administrador') {
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $id_docente = intval($_POST['id_docente'] ?? 0);

                if ($nombre && $descripcion && $id_docente > 0) {
                    $model->crearCurso($nombre, $descripcion, $id_docente);
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?msg=curso_creado");
                    exit();
                } else {
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/nuevoCurso.php?error=datos_incompletos");
                    exit();
                }
            }
            break;

        // 🧩 ADMINISTRADOR EDITA CURSO o ASIGNA DOCENTE
        case 'editar_curso':
            if ($rol === 'Administrador') {
                $id_curso = intval($_POST['id_curso'] ?? 0);
                $nombre = trim($_POST['nombre'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $id_docente = intval($_POST['id_docente'] ?? 0);

                if ($id_curso > 0) {
                    // Recuperar nombre/descripcion si no se enviaron (por asignar docente)
                    $stmt = $pdo->prepare("SELECT Nombre, Descripcion FROM curso WHERE Id_Curso = ?");
                    $stmt->execute([$id_curso]);
                    $cursoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

                    $nombre = $nombre ?: ($cursoExistente['Nombre'] ?? '');
                    $descripcion = $descripcion ?: ($cursoExistente['Descripcion'] ?? '');

                    $model->actualizarCurso($id_curso, $nombre, $descripcion, $id_docente);

                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?msg=curso_actualizado");
                    exit();
                } else {
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?error=id_invalido");
                    exit();
                }
            }
            break;

        // 👨‍🏫 DOCENTE CREA TAREA
        case 'crear_tarea':
            if ($rol === 'Docente') {
                $titulo = trim($_POST['titulo'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $fecha_entrega = $_POST['fecha_entrega'] ?? '';
                $id_curso = intval($_POST['id_curso'] ?? 0);

                if ($titulo && $descripcion && $fecha_entrega && $id_curso > 0) {
                    $model->crearTarea($titulo, $descripcion, $fecha_entrega, $id_curso);
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/tareasDocente.php?id_curso=$id_curso&msg=tarea_creada");
                    exit();
                } else {
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/tareasDocente.php?id_curso=$id_curso&error=datos_incompletos");
                    exit();
                }
            }
            break;

        // 🎓 DOCENTE MATRICULA ESTUDIANTE
        case 'matricular_estudiante':
            if ($rol === 'Docente') {
                $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
                $id_curso = intval($_POST['id_curso'] ?? 0);

                if ($id_estudiante > 0 && $id_curso > 0) {
                    $model->matricularEstudiante($id_estudiante, $id_curso);
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?msg=estudiante_matriculado");
                    exit();
                } else {
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?error=datos_invalidos");
                    exit();
                }
            }
            break;

        // ✏️ DOCENTE EDITA TAREA
        case 'editar_tarea':
            if ($rol === 'Docente') {
                require_once $_SERVER["DOCUMENT_ROOT"] . "/Aula-Virtual-Santa-Teresita/model/TareaModel.php";
                $tareaModel = new TareaModel($pdo);

                $idTarea = intval($_POST['id_tarea'] ?? 0);
                $titulo = trim($_POST['titulo'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $fechaEntrega = $_POST['fecha_entrega'] ?? '';
                $idCurso = intval($_POST['id_curso'] ?? 0);

                if ($idTarea > 0 && $titulo && $descripcion && $fechaEntrega) {
                    $sql = "UPDATE tarea 
                            SET Titulo=:titulo, Descripcion=:descripcion, Fecha_Entrega=:fecha 
                            WHERE Id_Tarea=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':titulo' => $titulo,
                        ':descripcion' => $descripcion,
                        ':fecha' => $fechaEntrega,
                        ':id' => $idTarea
                    ]);
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/tareasDocente.php?id_curso=$idCurso&msg=tarea_actualizada");
                    exit();
                } else {
                    header("Location: /Aula-Virtual-Santa-Teresita/view/Docente/tareasDocente.php?id_curso=$idCurso&error=datos_invalidos");
                    exit();
                }
            }
            break;

        // 🗑️ DOCENTE ELIMINA TAREA
        case 'eliminar_tarea':
            if ($rol === 'Docente') {
                $idTarea = intval($_POST['id_tarea'] ?? 0);
                if ($idTarea > 0) {
                    $stmt = $pdo->prepare("DELETE FROM tarea WHERE Id_Tarea = ?");
                    $stmt->execute([$idTarea]);
                    header("Location: " . $_SERVER['HTTP_REFERER']);
                    exit();
                } else {
                    header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=id_invalido");
                    exit();
                }
            }
            break;

        // ❌ Acción no reconocida
        default:
            header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php?error=accion_desconocida");
            exit();
    }

} else {
    // 🚫 Si entran por GET, redirigir siempre al dashboard
    header("Location: /Aula-Virtual-Santa-Teresita/view/Cursos/dashboardCursos.php");
    exit();
}
?>
