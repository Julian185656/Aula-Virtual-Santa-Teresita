--UTILICEMOS ESTE SCRIPT PARA REALIZAR PRUEBAS 
--Y NO PASAR BORRANDO LA DB

--//PROBAR REPORTES DE ASISTENCIA//--
USE aulavirtual;
INSERT INTO usuario (Id_Usuario, Nombre, Email, Contrasena, Rol, Estado, Telefono) VALUES
(1196, 'Carlos Gómez', 'carlos@santateresita.ac.cr', '$2y$10$NxJq5Qt8LXXIiRrUyznjJuXGO6BTnWuC5mhE.RHcSDdOYis8xuf1O', 'Docente', 'Activo', '88888888'),
(1197, 'Laura Torres', 'laura@santateresita.ac.cr', '$2y$10$NxJq5Qt8LXXIiRrUyznjJuXGO6BTnWuC5mhE.RHcSDdOYis8xuf1O', 'Docente', 'Activo', '77777777'),
(1198, 'Ana López', 'ana@santateresita.ac.cr', '$2y$10$NxJq5Qt8LXXIiRrUyznjJuXGO6BTnWuC5mhE.RHcSDdOYis8xuf1O', 'Estudiante', 'Activo', '55555555'),
(1199, 'Miguel Hernández', 'miguel@santateresita.ac.cr', '$2y$10$NxJq5Qt8LXXIiRrUyznjJuXGO6BTnWuC5mhE.RHcSDdOYis8xuf1O', 'Estudiante', 'Activo', '66666666'),
(1200, 'Lucía Castillo', 'lucia@santateresita.ac.cr', '$2y$10$NxJq5Qt8LXXIiRrUyznjJuXGO6BTnWuC5mhE.RHcSDdOYis8xuf1O', 'Estudiante', 'Activo', '44444444');

-- Insertamos docentes y estudiantes relacionados
INSERT INTO docente (Id_Docente, Especialidad)
VALUES (1196, 'Matemáticas'), (1197, 'Artes Plasticas');

INSERT INTO estudiante (Id_Estudiante, Grado, Seccion)
VALUES (1198, '4to', 'A'), (1199, '4to', 'A'), (1200, '5to', 'B');

-- Insertamos cursos
INSERT INTO curso (Nombre, Descripcion, Id_Docente)
VALUES 
('Matemáticas', 'Curso de operaciones fundamentales', 1196),
('Artes Plasticas', 'Teoria del color', 1197);

-- Insertamos matrículas
INSERT INTO matricula (Id_Estudiante, Id_Curso, Fecha_Matricula)
VALUES 
(1198, 1, CURDATE()),
(1199, 1, CURDATE()),
(1200, 2, CURDATE());

-- Insertamos asistencias (2 fechas distintas)
INSERT INTO asistencia (Id_Estudiante, Id_Curso, Fecha, Presente)
VALUES 
(1198, 11, '2025-10-15', 1),
(1199, 12, '2025-10-15', 1),
(1200, 12, '2025-10-15', 0),
(1198, 11, '2025-10-16', 0),
(1199, 11, '2025-10-16', 1),
(1200, 12, '2025-10-16', 1);

--// SP PARA FILTRACION DE DATOS //

-- SP devuelve las fechas distinas registradas en la tabla asistencia
DELIMITER $$

CREATE PROCEDURE sp_FechaAsistencia()
BEGIN
    SELECT DISTINCT Fecha
    FROM asistencia
    ORDER BY Fecha DESC;
END $$

DELIMITER ;

-- SP Reporte completo de asistencia
DELIMITER $$

CREATE PROCEDURE sp_ReporteAsistencia(
    IN p_fecha DATE,
    IN p_offset INT,
    IN p_limit INT
)
BEGIN
    SELECT 
        a.Id_Asistencia,
        e.Id_Estudiante,
        u_e.Nombre AS Estudiante,
        e.Grado,
        e.Seccion,
        c.Id_Curso AS IdCurso,
        c.Nombre AS Curso,
        d.Id_Docente AS IdDocente,
        u_d.Nombre AS Docente,
        a.Fecha,
        a.Presente
    FROM asistencia a
    INNER JOIN estudiante e ON a.Id_Estudiante = e.Id_Estudiante
    INNER JOIN usuario u_e ON e.Id_Estudiante = u_e.Id_Usuario
    INNER JOIN curso c ON a.Id_Curso = c.Id_Curso
    INNER JOIN docente d ON c.Id_Docente = d.Id_Docente
    INNER JOIN usuario u_d ON d.Id_Docente = u_d.Id_Usuario
    WHERE p_fecha IS NULL OR a.Fecha = p_fecha
    ORDER BY a.Fecha DESC, u_e.Nombre ASC
    LIMIT p_limit OFFSET p_offset;
END $$

DELIMITER ;

-- REPORTE DE PARTICIPACION --

-- TABLA DE PARTICIPACION PARA EL REPORTE --
CREATE TABLE participacion (
    Id_Participacion INT AUTO_INCREMENT PRIMARY KEY,
    Id_Estudiante INT NOT NULL,
    Id_Curso INT NOT NULL,
    Id_Docente INT NOT NULL,
    Fecha DATE NOT NULL,
    Periodo VARCHAR(20) NOT NULL, -- Ej: '2025-I', '2025-II'
    NivelParticipacion INT NOT NULL, -- Escala de 1 a 5 o 1 a 10
    Observacion VARCHAR(255) NULL,
    FOREIGN KEY (Id_Estudiante) REFERENCES estudiante(Id_Estudiante),
    FOREIGN KEY (Id_Curso) REFERENCES curso(Id_Curso),
    FOREIGN KEY (Id_Docente) REFERENCES docente(Id_Docente)
);
-- DATOS PRUEBA --
INSERT INTO participacion (Id_Estudiante, Id_Curso, Id_Docente, Fecha, Periodo, NivelParticipacion, Observacion)
VALUES
(1198, 11, 1196, '2025-10-10', '2025-I', 5, 'Excelente participación en clase'),
(1199, 12, 1197, '2025-10-10', '2025-I', 3, 'Participa ocasionalmente'),
(1200, 12, 1197, '2025-10-10', '2025-I', 4, 'Buena interacción'),
(1198, 11, 1196, '2025-10-16', '2025-II', 4, 'Participa con interés'),
(1200, 12, 1197, '2025-10-16', '2025-II', 2, 'Poca participación en debates');

-- // SP PARA FILTRACION DE DATOS // --

-- REPORTE DE PARTICIPACION --
DELIMITER $$

CREATE PROCEDURE sp_ReporteParticipacion(
    IN p_periodo VARCHAR(20),
    IN p_offset INT,
    IN p_limit INT
)
BEGIN
    SELECT 
        u_e.Nombre AS Estudiante,
        c.Nombre AS Curso,
        u_d.Nombre AS Docente,
        p.Periodo,
        ROUND(AVG(p.NivelParticipacion), 2) AS PromedioParticipacion,
        CASE
            WHEN AVG(p.NivelParticipacion) >= 4 THEN 'Alta participación'
            WHEN AVG(p.NivelParticipacion) >= 2.5 THEN 'Media participación'
            ELSE 'Baja participación'
        END AS ValoracionCualitativa
    FROM participacion p
    INNER JOIN estudiante e ON p.Id_Estudiante = e.Id_Estudiante
    INNER JOIN usuario u_e ON e.Id_Estudiante = u_e.Id_Usuario
    INNER JOIN docente d ON p.Id_Docente = d.Id_Docente
    INNER JOIN usuario u_d ON d.Id_Docente = u_d.Id_Usuario
    INNER JOIN curso c ON p.Id_Curso = c.Id_Curso
    WHERE p_periodo IS NULL OR p.Periodo = p_periodo
    GROUP BY e.Id_Estudiante, c.Id_Curso, p.Periodo
    ORDER BY u_e.Nombre ASC
    LIMIT p_limit OFFSET p_offset;
END $$

DELIMITER ;

-- UTILIZA SELECT PARA DEVOLVER LOS PERIODOS DISPONIBLES --
DELIMITER $$

CREATE PROCEDURE sp_PeriodosParticipacion()
BEGIN
    SELECT DISTINCT Periodo
    FROM participacion
    ORDER BY Periodo DESC;
END $$

DELIMITER ;

DELIMITER $$

-- PAGINACION --
CREATE PROCEDURE sp_ReporteParticipacion_Total(IN p_periodo VARCHAR(20))
BEGIN
    SELECT COUNT(*) AS total
    FROM (
        SELECT 1
        FROM participacion p
        INNER JOIN estudiante e ON p.Id_Estudiante = e.Id_Estudiante
        INNER JOIN curso c ON p.Id_Curso = c.Id_Curso
        WHERE p_periodo IS NULL OR p.Periodo = p_periodo
        GROUP BY e.Id_Estudiante, c.Id_Curso, p.Periodo
    ) x;
END $$

DELIMITER ;
