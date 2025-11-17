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