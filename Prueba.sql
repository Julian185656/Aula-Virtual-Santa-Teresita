-- REPORTE DE RENDIMIENTO --

-- Muestra todas las calificaciones de los estudiantes --
-- Incluye: estudiante, curso, docente, calificación, comentario y fecha de entrega --
DELIMITER $$

CREATE PROCEDURE sp_ObtenerReporteCalificaciones (
    IN p_IdCurso INT,                 -- Curso específico (NULL para todos)
    IN p_Pagina INT,                  -- Número de página
    IN p_RegistrosPorPagina INT,      -- Registros por página (15)
    OUT p_TotalRegistros INT          -- Total de resultados
)
BEGIN
    DECLARE v_Offset INT;

    -- Calculamos el punto inicial para la paginación
    SET v_Offset = (p_Pagina - 1) * p_RegistrosPorPagina;

    -- Calculamos el total de registros (sin límite)
    IF p_IdCurso IS NULL THEN
        SELECT COUNT(*) INTO p_TotalRegistros
        FROM entrega_tarea et
        INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
        INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
        INNER JOIN usuario u ON et.Id_Estudiante = u.Id_Usuario
        INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente;
    ELSE
        SELECT COUNT(*) INTO p_TotalRegistros
        FROM entrega_tarea et
        INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
        INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
        INNER JOIN usuario u ON et.Id_Estudiante = u.Id_Usuario
        INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente
        WHERE cd.Id_Curso = p_IdCurso;
    END IF;

    -- Obtenemos los registros de la página actual
    IF p_IdCurso IS NULL THEN
        SELECT 
            u.Id_Usuario AS IdEstudiante,
            u.Nombre AS Estudiante,
            cd.Id_Curso,
            d.Especialidad AS Curso,
            d.Id_Docente,
            CONCAT('Prof. ', (SELECT Nombre FROM usuario WHERE Id_Usuario = d.Id_Docente)) AS Docente,
            et.Calificacion,
            et.Comentario,
            et.Fecha_Entrega
        FROM entrega_tarea et
        INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
        INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
        INNER JOIN usuario u ON et.Id_Estudiante = u.Id_Usuario
        INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente
        ORDER BY Curso, Estudiante, et.Fecha_Entrega
        LIMIT p_RegistrosPorPagina OFFSET v_Offset;
    ELSE
        SELECT 
            u.Id_Usuario AS IdEstudiante,
            u.Nombre AS Estudiante,
            cd.Id_Curso,
            d.Especialidad AS Curso,
            d.Id_Docente,
            CONCAT('Prof. ', (SELECT Nombre FROM usuario WHERE Id_Usuario = d.Id_Docente)) AS Docente,
            et.Calificacion,
            et.Comentario,
            et.Fecha_Entrega
        FROM entrega_tarea et
        INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
        INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
        INNER JOIN usuario u ON et.Id_Estudiante = u.Id_Usuario
        INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente
        WHERE cd.Id_Curso = p_IdCurso
        ORDER BY Estudiante, et.Fecha_Entrega
        LIMIT p_RegistrosPorPagina OFFSET v_Offset;
    END IF;
END$$

DELIMITER ;


DELIMITER $$

-- Muestra el promedio general por curso--
CREATE PROCEDURE sp_VerResumenCalificaciones()
BEGIN
    SELECT 
        cd.Id_Curso,
        d.Especialidad AS Curso,
        ROUND(AVG(et.Calificacion), 2) AS PromedioCurso
    FROM entrega_tarea et
    INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
    INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
    INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente
    GROUP BY cd.Id_Curso, d.Especialidad
    ORDER BY d.Especialidad;
END$$

DELIMITER ;


DELIMITER ;

DELIMITER $$

-- promedio individual de cada estudiante --
CREATE PROCEDURE sp_VerPromedioPorEstudiante(IN p_IdCurso INT)
BEGIN
    SELECT 
        u.Id_Usuario AS IdEstudiante,
        u.Nombre AS Estudiante,
        d.Especialidad AS Curso,
        ROUND(AVG(et.Calificacion), 2) AS PromedioEstudiante
    FROM entrega_tarea et
    INNER JOIN matricula m ON et.Id_Estudiante = m.Id_Estudiante
    INNER JOIN curso_docente cd ON m.Id_Curso = cd.Id_Curso
    INNER JOIN usuario u ON et.Id_Estudiante = u.Id_Usuario
    INNER JOIN docente d ON cd.Id_Docente = d.Id_Docente
    WHERE cd.Id_Curso = p_IdCurso
    GROUP BY u.Id_Usuario, u.Nombre, d.Especialidad
    ORDER BY Estudiante;
END$$

DELIMITER ;
