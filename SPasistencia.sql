DELIMITER $$

/* 1) Cursos del docente */
DROP PROCEDURE IF EXISTS sp_asist_cursos_por_docente $$
CREATE PROCEDURE sp_asist_cursos_por_docente(IN p_docente_id INT)
BEGIN
    /* Devuelve los cursos asignados al docente */
    SELECT 
        c.Id_Curso,
        c.Nombre AS Curso
    FROM curso_docente cd
    INNER JOIN curso c ON c.Id_Curso = cd.Id_Curso
    WHERE cd.Id_Docente = p_docente_id
    ORDER BY c.Nombre;
END $$


/* 2) Alumnos del curso con paginación (15 por página típicamente)
   - p_pagina: 1..N
   - p_limite: tamaño de página (15 por convención)
   - p_total: OUT con el total de alumnos del curso (para paginación)
*/
DROP PROCEDURE IF EXISTS sp_asist_alumnos_por_curso $$
CREATE PROCEDURE sp_asist_alumnos_por_curso(
    IN  p_curso_id INT,
    IN  p_pagina   INT,
    IN  p_limite   INT,
    OUT p_total    INT
)
BEGIN
    DECLARE v_offset INT;

    /* Normaliza parámetros */
    IF p_pagina IS NULL OR p_pagina < 1 THEN SET p_pagina = 1; END IF;
    IF p_limite IS NULL OR p_limite < 1 THEN SET p_limite = 15; END IF;
    SET v_offset = (p_pagina - 1) * p_limite;

    /* Total de matriculados en el curso */
    SELECT COUNT(*) INTO p_total
    FROM matricula m
    WHERE m.Id_Curso = p_curso_id;

    /* Página de alumnos (ID, nombre, email, curso) */
    SELECT 
        e.Id_Estudiante,
        u.Nombre,
        u.Email,
        c.Id_Curso,
        c.Nombre AS Curso
    FROM matricula m
    INNER JOIN estudiante e ON e.Id_Estudiante = m.Id_Estudiante
    INNER JOIN usuario   u ON u.Id_Usuario   = e.Id_Estudiante   -- IDs iguales (1:1)
    INNER JOIN curso     c ON c.Id_Curso     = m.Id_Curso
    WHERE m.Id_Curso = p_curso_id
    ORDER BY u.Nombre
    LIMIT p_limite OFFSET v_offset;
END $$


/* 3) Asistencia ya registrada para un curso en una fecha (precarga) */
DROP PROCEDURE IF EXISTS sp_asist_obtener_dia $$
CREATE PROCEDURE sp_asist_obtener_dia(
    IN p_curso_id INT,
    IN p_fecha    DATE
)
BEGIN
    SELECT 
        a.Id_Estudiante,
        a.Presente
        -- Si más adelante agregas Observacion/Docente, se añaden aquí
    FROM asistencia a
    WHERE a.Id_Curso = p_curso_id
      AND a.Fecha    = p_fecha;
END $$


/* 4) Upsert unitario (update/insert) con validaciones
   - Valida que el docente tenga asignado ese curso
   - Valida que el estudiante esté matriculado en ese curso
   - Fuerza el estado a 0/1
*/
DROP PROCEDURE IF EXISTS sp_asist_upsert_uno $$
CREATE PROCEDURE sp_asist_upsert_uno(
    IN p_curso_id      INT,
    IN p_estudiante_id INT,
    IN p_fecha         DATE,
    IN p_presente      TINYINT,
    IN p_docente_id    INT
)
BEGIN
    /* Seguridad/Reglas básicas en BD */

    /* 4.1: ¿El curso está asignado al docente? */
    IF NOT EXISTS (
        SELECT 1 
        FROM curso_docente cd 
        WHERE cd.Id_Curso = p_curso_id 
          AND cd.Id_Docente = p_docente_id
    ) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'El docente no tiene asignado este curso.';
    END IF;

    /* 4.2: ¿El estudiante está matriculado en el curso? */
    IF NOT EXISTS (
        SELECT 1 
        FROM matricula m 
        WHERE m.Id_Curso = p_curso_id 
          AND m.Id_Estudiante = p_estudiante_id
    ) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'El estudiante no está matriculado en el curso.';
    END IF;

    /* 4.3: Normaliza estado: solo 0 o 1 */
    SET p_presente = IF(p_presente IS NULL OR p_presente = 0, 0, 1);

    /* 4.4: Intento de UPDATE primero */
    UPDATE asistencia
       SET Presente = p_presente
     WHERE Id_Curso      = p_curso_id
       AND Id_Estudiante = p_estudiante_id
       AND Fecha         = p_fecha;

    /* 4.5: Si no afectó filas, INSERT */
    IF ROW_COUNT() = 0 THEN
        INSERT INTO asistencia (Id_Estudiante, Id_Curso, Fecha, Presente)
        VALUES (p_estudiante_id, p_curso_id, p_fecha, p_presente);
    END IF;
END $$

DELIMITER ;


--- SP Para el Historial de Asistencia
DELIMITER $$

CREATE PROCEDURE sp_asist_historial_alumno (
    IN  p_curso       INT,
    IN  p_estudiante  INT,
    IN  p_fecha_desde DATE,
    IN  p_fecha_hasta DATE,
    IN  p_pagina      INT,
    IN  p_limite      INT,
    OUT p_total       INT
)
BEGIN
    DECLARE v_offset INT;

    -- Normalizar fechas
    IF p_fecha_desde IS NULL THEN
        SET p_fecha_desde = '2000-01-01';
    END IF;

    IF p_fecha_hasta IS NULL THEN
        SET p_fecha_hasta = CURDATE();
    END IF;

    -- Normalizar paginación
    IF p_pagina IS NULL OR p_pagina < 1 THEN
        SET p_pagina = 1;
    END IF;

    IF p_limite IS NULL OR p_limite < 1 THEN
        SET p_limite = 15;
    END IF;

    SET v_offset = (p_pagina - 1) * p_limite;

    -- Total de registros en el rango (para la paginación)
    SELECT COUNT(*) INTO p_total
    FROM asistencia a
    WHERE a.Id_Curso      = p_curso
      AND a.Id_Estudiante = p_estudiante
      AND a.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta;

    -- Query principal con datos + totales del alumno en ese rango
    SELECT
        a.Fecha,
        a.Presente,
        CASE 
            WHEN a.Presente = 1 THEN 'Presente'
            ELSE 'Ausente'
        END AS EstadoTexto,
        u.Nombre AS Estudiante,
        u.Email  AS Correo,
        c.Nombre AS Curso,

        -- Totales del alumno en ese curso y rango
        (SELECT COUNT(*)
         FROM asistencia a2
         WHERE a2.Id_Curso      = p_curso
           AND a2.Id_Estudiante = p_estudiante
           AND a2.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta
           AND a2.Presente = 1) AS TotalPresentes,

        (SELECT COUNT(*)
         FROM asistencia a3
         WHERE a3.Id_Curso      = p_curso
           AND a3.Id_Estudiante = p_estudiante
           AND a3.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta
           AND a3.Presente = 0) AS TotalAusentes

    FROM asistencia a
    INNER JOIN estudiante e ON a.Id_Estudiante = e.Id_Estudiante
    INNER JOIN usuario   u  ON e.Id_Estudiante = u.Id_Usuario
    INNER JOIN curso     c  ON a.Id_Curso      = c.Id_Curso
    WHERE a.Id_Curso      = p_curso
      AND a.Id_Estudiante = p_estudiante
      AND a.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta
    ORDER BY a.Fecha ASC
    LIMIT v_offset, p_limite;
END$$

DELIMITER ;

--- Justificacion de Ausencias-----

--// se altera la tabla asistencia para agregar la justificacion//----
ALTER TABLE asistencia
ADD COLUMN Justificada TINYINT(1) NOT NULL DEFAULT 0 AFTER Presente,
ADD COLUMN ComentarioJustificacion VARCHAR(255) NULL AFTER Justificada;

DROP PROCEDURE IF EXISTS sp_asist_ausencias_pendientes;
DELIMITER $$

CREATE PROCEDURE sp_asist_ausencias_pendientes (
    IN  p_docente     INT,
    IN  p_curso       INT,       -- 0 = todos los cursos del docente
    IN  p_fecha_desde DATE,
    IN  p_fecha_hasta DATE,
    IN  p_pagina      INT,
    IN  p_limite      INT,
    OUT p_total       INT
)
BEGIN
    DECLARE v_offset INT;

    -- Normalizar fechas
    IF p_fecha_desde IS NULL THEN
        SET p_fecha_desde = '2000-01-01';
    END IF;

    IF p_fecha_hasta IS NULL THEN
        SET p_fecha_hasta = CURDATE();
    END IF;

    -- Normalizar paginación
    IF p_pagina IS NULL OR p_pagina < 1 THEN
        SET p_pagina = 1;
    END IF;

    IF p_limite IS NULL OR p_limite < 1 THEN
        SET p_limite = 15;
    END IF;

    SET v_offset = (p_pagina - 1) * p_limite;

    -- Conteo total de ausencias pendientes
    SELECT COUNT(*) INTO p_total
    FROM asistencia a
    INNER JOIN curso_docente cd ON a.Id_Curso = cd.Id_Curso
    WHERE cd.Id_Docente   = p_docente
      AND a.Presente      = 0          -- ausente
      AND a.Justificada   = 0          -- no justificada
      AND a.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta
      AND (p_curso = 0 OR a.Id_Curso = p_curso);

    -- Listado paginado
    SELECT
        a.Id_Curso,
        a.Id_Estudiante,
        a.Fecha,
        a.Presente,
        a.Justificada,
        a.ComentarioJustificacion,
        u.Nombre AS Estudiante,
        u.Email  AS Correo,
        c.Nombre AS Curso
    FROM asistencia a
    INNER JOIN curso_docente cd ON a.Id_Curso = cd.Id_Curso
    INNER JOIN estudiante e     ON a.Id_Estudiante = e.Id_Estudiante
    INNER JOIN usuario   u      ON e.Id_Estudiante = u.Id_Usuario
    INNER JOIN curso     c      ON a.Id_Curso      = c.Id_Curso
    WHERE cd.Id_Docente   = p_docente
      AND a.Presente      = 0
      AND a.Justificada   = 0
      AND a.Fecha BETWEEN p_fecha_desde AND p_fecha_hasta
      AND (p_curso = 0 OR a.Id_Curso = p_curso)
    ORDER BY a.Fecha ASC
    LIMIT v_offset, p_limite;
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS sp_asist_marcar_justificada;
DELIMITER $$

CREATE PROCEDURE sp_asist_marcar_justificada (
    IN p_curso      INT,
    IN p_estudiante INT,
    IN p_fecha      DATE,
    IN p_docente    INT,
    IN p_comentario VARCHAR(255)
)
BEGIN
    -- Solo actualizamos si realmente es AUSENTE y NO está justificada aún
    UPDATE asistencia a
    INNER JOIN curso_docente cd 
        ON a.Id_Curso = cd.Id_Curso
    SET 
        a.Justificada             = 1,
        a.ComentarioJustificacion = p_comentario
    WHERE a.Id_Curso      = p_curso
      AND a.Id_Estudiante = p_estudiante
      AND a.Fecha         = p_fecha
      AND a.Presente      = 0        -- era ausencia
      AND a.Justificada   = 0        -- no justificada aún
      AND cd.Id_Docente   = p_docente; -- solo su propio curso
END$$

DELIMITER ;




--- Descargar reporte estudiante --

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_asist_reporte_curso $$
CREATE PROCEDURE sp_asist_reporte_curso(
    IN p_curso      INT,
    IN p_desde      DATE,
    IN p_hasta      DATE,
    IN p_estudiante INT
)
BEGIN
    -- Normalizar fechas
    IF p_desde IS NULL OR p_desde = '' THEN
        SET p_desde = '1900-01-01';
    END IF;

    IF p_hasta IS NULL OR p_hasta = '' THEN
        SET p_hasta = '2999-12-31';
    END IF;

    SELECT
        a.Id_Asistencia,
        a.Fecha,
        a.Id_Estudiante,
        u.Nombre  AS Estudiante,
        u.Email   AS Email,
        c.Id_Curso,
        c.Nombre  AS Curso,
        a.Presente,
        a.Justificada,
       a.ComentarioJustificacion AS Comentario_Justificacion
    FROM asistencia a
    INNER JOIN estudiante e ON e.Id_Estudiante = a.Id_Estudiante
    INNER JOIN usuario   u ON u.Id_Usuario    = e.Id_Estudiante
    INNER JOIN curso     c ON c.Id_Curso      = a.Id_Curso
    WHERE a.Id_Curso = p_curso
      AND a.Fecha BETWEEN p_desde AND p_hasta
      AND (
            p_estudiante IS NULL OR
            p_estudiante = 0 OR
            a.Id_Estudiante = p_estudiante
          )
    ORDER BY u.Nombre, a.Fecha;
END $$

DELIMITER ;
