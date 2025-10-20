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
