<?php
require_once "../../../../model/db.php";

class RegistrarAsistenciaModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Obtiene los cursos asignados al docente.
     * @return array
     */
public function obtenerCursosDocente(int $docenteId): array
{
    try {
        $sql = "
            SELECT c.Id_Curso, c.Nombre AS Curso
            FROM aulavirtual.curso c
            INNER JOIN aulavirtual.curso_docente cd
                ON cd.Id_Curso = c.Id_Curso
            WHERE cd.Id_Docente = :docenteId
            ORDER BY c.Nombre
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':docenteId', $docenteId, PDO::PARAM_INT);
        $stmt->execute();

        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $stmt->closeCursor();

        return $cursos;
    } catch (\Exception $e) {
        throw new \Exception("Error al obtener cursos del docente: " . $e->getMessage());
    }
}


    /**
     * Obtiene alumnos de un curso con paginación.
     * @return array ['alumnos' => [...], 'total' => int]
     */
public function obtenerAlumnosPaginado(int $cursoId, int $pagina = 1, int $limite = 15): array
{
    try {
        if ($pagina < 1) $pagina = 1;
        if ($limite < 1) $limite = 15;

        $offset = ($pagina - 1) * $limite;

    
        $stmtTotal = $this->pdo->prepare("
            SELECT COUNT(*) AS Total
            FROM aulavirtual.matricula m
            WHERE m.Id_Curso = :curso
        ");
        $stmtTotal->execute([':curso' => $cursoId]);
        $totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);
        $total = isset($totalRow['Total']) ? (int)$totalRow['Total'] : 0;

       
        $sql = "
            SELECT 
                m.Id_Estudiante,
                u.Nombre,
                u.Email,
                c.Id_Curso,
                c.Nombre AS Curso
            FROM aulavirtual.matricula m
            INNER JOIN aulavirtual.usuario u ON u.Id_Usuario = m.Id_Estudiante
            INNER JOIN aulavirtual.curso c   ON c.Id_Curso   = m.Id_Curso
            WHERE m.Id_Curso = :curso
            ORDER BY u.Nombre
            OFFSET $offset ROWS FETCH NEXT $limite ROWS ONLY
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':curso', $cursoId, PDO::PARAM_INT);
        $stmt->execute();

        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return ['alumnos' => $alumnos, 'total' => $total];

    } catch (\Exception $e) {
        throw new \Exception("Error al obtener alumnos del curso: " . $e->getMessage());
    }
}




public function obtenerAsistenciaDia(int $cursoId, string $fecha): array
{
    try {
        $fecha = $this->normalizarFecha($fecha);

        $stmt = $this->pdo->prepare("
            SELECT Id_Estudiante, Presente
            FROM aulavirtual.asistencia
            WHERE Id_Curso = :curso AND Fecha = :fecha
        ");
        $stmt->execute([
            ':curso' => $cursoId,
            ':fecha' => $fecha
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $stmt->closeCursor();

        $map = [];
        foreach ($rows as $r) {
            $map[(int)$r['Id_Estudiante']] = (int)$r['Presente'];
        }
        return $map;

    } catch (\Exception $e) {
        throw new \Exception("Error al obtener la asistencia del día: " . $e->getMessage());
    }
}


    /**
     * Guarda en lote la asistencia de un curso para una fecha.
     * Sin modificar la tabla:
     *  - Si ya existe registro (Id_Estudiante, Id_Curso, Fecha) → UPDATE.
     *  - Si no existe → INSERT.
     *
     * @param int   $cursoId
     * @param string $fecha
     * @param int   $docenteId  (no se usa en la tabla actual, pero se mantiene por firma)
     * @param array $items      [ ['Id_Estudiante' => int, 'Presente' => 0|1], ... ]
     * @return array ['ok' => bool, 'procesados' => int]
     */
public function guardarLoteAsistencia(int $cursoId, string $fecha, int $docenteId, array $items): array
{
    $fecha = $this->normalizarFecha($fecha);

    try {
        $this->pdo->beginTransaction();

     
        $stmtCurso = $this->pdo->prepare("SELECT 1 FROM aulavirtual.curso WHERE Id_Curso = :curso");
        $stmtCurso->execute([':curso' => $cursoId]);
        if (!$stmtCurso->fetch()) {
            throw new \Exception("El curso no existe.");
        }

     
        $stmtUpdate = $this->pdo->prepare("
            UPDATE aulavirtual.asistencia
               SET Presente = :presente
             WHERE Id_Estudiante = :estudiante
               AND Id_Curso      = :curso
               AND Fecha         = :fecha
        ");

       
        $stmtInsert = $this->pdo->prepare("
            INSERT INTO aulavirtual.asistencia (Id_Estudiante, Id_Curso, Fecha, Presente)
            VALUES (:estudiante, :curso, :fecha, :presente)
        ");

        $stmtEst = $this->pdo->query("SELECT Id_Usuario FROM aulavirtual.usuario WHERE Rol = 'Estudiante'");
        $estudiantesValidos = $stmtEst->fetchAll(PDO::FETCH_COLUMN, 0);

        $procesados = 0;

        foreach ($items as $item) {
            $est = (int)($item['Id_Estudiante'] ?? 0);
            $pres = isset($item['Presente']) && (int)$item['Presente'] === 1 ? 1 : 0;

         
            if (!in_array($est, $estudiantesValidos)) continue;

          
            $stmtUpdate->execute([
                ':presente'   => $pres,
                ':estudiante' => $est,
                ':curso'      => $cursoId,
                ':fecha'      => $fecha
            ]);

            
            if ($stmtUpdate->rowCount() === 0) {
                $stmtInsert->execute([
                    ':presente'   => $pres,
                    ':estudiante' => $est,
                    ':curso'      => $cursoId,
                    ':fecha'      => $fecha
                ]);
            }

            $procesados++;
        }

        $this->pdo->commit();
        return ['ok' => true, 'procesados' => $procesados];

    } catch (\Exception $e) {
        if ($this->pdo->inTransaction()) $this->pdo->rollBack();
        throw new \Exception("Error al guardar la asistencia: " . $e->getMessage());
    }
}



 
    private function normalizarFecha(?string $fecha): string
    {
        if (!$fecha || trim($fecha) === '') {
            return date('Y-m-d');
        }
     
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            [$d, $m, $y] = explode('/', $fecha);
            return sprintf('%04d-%02d-%02d', (int)$y, (int)$m, (int)$d);
        }
 
        return substr($fecha, 0, 10);
    }
}
