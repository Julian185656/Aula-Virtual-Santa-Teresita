<?php
require_once "../../../../model/db.php";

class SolicitudesJustificacionModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerCursosDocente(int $docenteId): array
    {
        try {
            $sql = "
                SELECT c.Id_Curso, c.Nombre
                FROM aulavirtual.curso c
                INNER JOIN aulavirtual.curso_docente cd ON cd.Id_Curso = c.Id_Curso
                WHERE cd.Id_Docente = :doc
                ORDER BY c.Nombre
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();
            return $rows;
        } catch (Exception $e) {
            return [];
        }
    }

    public function listarSolicitudes(
        int $docenteId,
        int $cursoId,
        ?string $desde,
        ?string $hasta,
        ?string $estado,
        int $pagina,
        int $limite
    ): array {
        try {
            $desdeN = $this->normalizarFechaNullable($desde);
            $hastaN = $this->normalizarFechaNullable($hasta);

            // estado: 'pendiente' / 'aprobada' / 'denegada' / 'todos'
            $estadoNorm = trim((string)$estado);
            if ($estadoNorm === '' || strtolower($estadoNorm) === 'todos') $estadoNorm = null;

            $stmt = $this->pdo->prepare("EXEC aulavirtual.sp_listar_solicitudes_justificacion_docente
                @Id_Docente = :doc,
                @Id_Curso = :curso,
                @Desde = :desde,
                @Hasta = :hasta,
                @Estado = :estado,
                @Page = :page,
                @PageSize = :ps");

            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->bindParam(':curso', $cursoId, PDO::PARAM_INT);

            // bindParam no acepta null bien sin tipo; hacemos variables
            $vDesde = $desdeN;
            $vHasta = $hastaN;
            $vEstado = $estadoNorm;

            $stmt->bindParam(':desde', $vDesde, PDO::PARAM_STR);
            $stmt->bindParam(':hasta', $vHasta, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $vEstado, PDO::PARAM_STR);

            $stmt->bindParam(':page', $pagina, PDO::PARAM_INT);
            $stmt->bindParam(':ps', $limite, PDO::PARAM_INT);

            $stmt->execute();

            // Result set 1: rows
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

            // Result set 2: total
            $stmt->nextRowset();
            $totalRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = (int)($totalRow['Total'] ?? 0);

            $stmt->closeCursor();

            return ['rows' => $rows, 'total' => $total];

        } catch (Exception $e) {
            throw new Exception("Error al listar solicitudes: " . $e->getMessage());
        }
    }

    public function resolverSolicitud(int $idJustificacion, int $docenteId, string $accion, ?string $comentarioDocente): bool
    {
        try {
            $comentario = $comentarioDocente !== null ? trim($comentarioDocente) : null;
            if ($comentario !== null && strlen($comentario) > 255) $comentario = substr($comentario, 0, 255);

            $stmt = $this->pdo->prepare("EXEC aulavirtual.sp_resolver_solicitud_justificacion
                @Id_Justificacion = :id,
                @Id_Docente = :doc,
                @Accion = :accion,
                @ComentarioDocente = :coment");

            $stmt->bindParam(':id', $idJustificacion, PDO::PARAM_INT);
            $stmt->bindParam(':doc', $docenteId, PDO::PARAM_INT);
            $stmt->bindParam(':accion', $accion, PDO::PARAM_STR);
            $stmt->bindParam(':coment', $comentario, PDO::PARAM_STR);

            $stmt->execute();

            // Si el SP devuelve ok=1, lo tomamos como true
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return isset($row['ok']) && (int)$row['ok'] === 1;

        } catch (Exception $e) {
            throw new Exception("Error al resolver solicitud: " . $e->getMessage());
        }
    }

    private function normalizarFechaNullable(?string $fecha): ?string
    {
        if (!$fecha || trim($fecha) === '') return null;

        $fecha = trim($fecha);
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }

        return substr($fecha, 0, 10); // YYYY-MM-DD
    }
}
