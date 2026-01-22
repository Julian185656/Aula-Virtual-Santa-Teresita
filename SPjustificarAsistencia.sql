SET NOCOUNT ON;
GO

/* =========================================================
   A) COLUMNAS RECOMENDADAS EN aulavirtual.justificaciones
   ========================================================= */

IF COL_LENGTH('aulavirtual.justificaciones', 'id_docente_resuelve') IS NULL
BEGIN
    ALTER TABLE aulavirtual.justificaciones
    ADD id_docente_resolve INT NULL;
END
GO

IF COL_LENGTH('aulavirtual.justificaciones', 'fecha_resolucion') IS NULL
BEGIN
    ALTER TABLE aulavirtual.justificaciones
    ADD fecha_resolucion DATETIME NULL;
END
GO

IF COL_LENGTH('aulavirtual.justificaciones', 'comentario_docente') IS NULL
BEGIN
    ALTER TABLE aulavirtual.justificaciones
    ADD comentario_docente NVARCHAR(255) NULL;
END
GO


/* =========================================================
   B) SP: LISTAR SOLICITUDES DEL DOCENTE (filtros + paginación)
   - Considera "pendiente" cuando estado IS NULL o 'pendiente'
   Requiere:
     - aulavirtual.curso_docente(Id_Curso, Id_Docente)
     - aulavirtual.usuario(Id_Usuario, Nombre, Email)
     - aulavirtual.curso(Id_Curso, Nombre)
   ========================================================= */

CREATE OR ALTER PROCEDURE aulavirtual.sp_listar_solicitudes_justificacion_docente
    @Id_Docente INT,
    @Id_Curso   INT = NULL,
    @Desde      DATE = NULL,
    @Hasta      DATE = NULL,
    @Estado     NVARCHAR(20) = N'pendiente', -- pendiente|aprobada|denegada|NULL para todos
    @Page       INT = 1,
    @PageSize   INT = 15
AS
BEGIN
    SET NOCOUNT ON;

    IF (@Page < 1) SET @Page = 1;
    IF (@PageSize < 1) SET @PageSize = 15;

    DECLARE @Offset INT = (@Page - 1) * @PageSize;
    DECLARE @EstadoNorm NVARCHAR(20) = NULLIF(LTRIM(RTRIM(@Estado)), N'');

    IF OBJECT_ID('tempdb..#Q') IS NOT NULL DROP TABLE #Q;

    SELECT
        j.id,
        j.id_estudiante,
        u.Nombre AS Estudiante,
        u.Email  AS Correo,
        j.id_curso,
        c.Nombre AS Curso,
        j.fecha_ausencia,
        j.comprobante,
        ISNULL(j.estado, N'pendiente') AS estado,
        j.fecha_solicitud,
        j.id_docente_resolve,
        j.fecha_resolucion,
        j.comentario_docente
    INTO #Q
    FROM aulavirtual.justificaciones j
    INNER JOIN aulavirtual.curso_docente cd
        ON cd.Id_Curso = j.id_curso
       AND cd.Id_Docente = @Id_Docente
    INNER JOIN aulavirtual.usuario u
        ON u.Id_Usuario = j.id_estudiante
    INNER JOIN aulavirtual.curso c
        ON c.Id_Curso = j.id_curso
    WHERE
        (@Id_Curso IS NULL OR @Id_Curso = 0 OR j.id_curso = @Id_Curso)
        AND (@Desde IS NULL OR j.fecha_ausencia >= @Desde)
        AND (@Hasta IS NULL OR j.fecha_ausencia <= @Hasta)
        AND (
            @EstadoNorm IS NULL
            OR (
                @EstadoNorm = N'pendiente' AND ISNULL(j.estado, N'pendiente') = N'pendiente'
            )
            OR (
                @EstadoNorm <> N'pendiente' AND j.estado = @EstadoNorm
            )
        );

    -- Página de resultados
    SELECT *
    FROM #Q
    ORDER BY ISNULL(fecha_solicitud, '19000101') DESC, id DESC
    OFFSET @Offset ROWS FETCH NEXT @PageSize ROWS ONLY;

    -- Total
    SELECT COUNT(1) AS Total
    FROM #Q;
END
GO


/* =========================================================
   C) SP: RESOLVER SOLICITUD (aprobar/denegar)
   - Actualiza estado de justificación
   - Si aprueba => set Justificada=1 en asistencia + comentario
   - Si no existe asistencia, la crea como AUSENTE JUSTIFICADO
   - Valida: docente pertenece al curso + solicitud pendiente
   ========================================================= */

CREATE OR ALTER PROCEDURE aulavirtual.sp_resolver_solicitud_justificacion
    @Id_Justificacion INT,
    @Id_Docente INT,
    @Accion NVARCHAR(10),                 -- 'aprobar' | 'denegar'
    @ComentarioDocente NVARCHAR(255) = NULL
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @accionNorm NVARCHAR(10) = LOWER(LTRIM(RTRIM(ISNULL(@Accion, N''))));
    DECLARE @nuevoEstado NVARCHAR(20);

    IF (@accionNorm NOT IN (N'aprobar', N'denegar'))
    BEGIN
        RAISERROR('Acción inválida. Use aprobar o denegar.', 16, 1);
        RETURN;
    END

    SET @nuevoEstado = CASE WHEN @accionNorm = N'aprobar' THEN N'aprobada' ELSE N'denegada' END;

    IF (@ComentarioDocente IS NOT NULL)
    BEGIN
        SET @ComentarioDocente = LTRIM(RTRIM(@ComentarioDocente));
        IF (LEN(@ComentarioDocente) > 255) SET @ComentarioDocente = LEFT(@ComentarioDocente, 255);
    END

    BEGIN TRY
        BEGIN TRAN;

        DECLARE @Id_Estudiante INT, @Id_Curso INT, @FechaAus DATE, @EstadoActual NVARCHAR(20);

        -- Bloqueo de fila para evitar doble resolución
        SELECT
            @Id_Estudiante = j.id_estudiante,
            @Id_Curso = j.id_curso,
            @FechaAus = j.fecha_ausencia,
            @EstadoActual = ISNULL(j.estado, N'pendiente')
        FROM aulavirtual.justificaciones j WITH (UPDLOCK, ROWLOCK)
        WHERE j.id = @Id_Justificacion;

        IF (@Id_Estudiante IS NULL)
        BEGIN
            RAISERROR('No existe la solicitud.', 16, 1);
            ROLLBACK;
            RETURN;
        END

        -- Validar que el curso pertenece al docente
        IF NOT EXISTS (
            SELECT 1
            FROM aulavirtual.curso_docente
            WHERE Id_Curso = @Id_Curso
              AND Id_Docente = @Id_Docente
        )
        BEGIN
            RAISERROR('No autorizado: el curso no pertenece al docente.', 16, 1);
            ROLLBACK;
            RETURN;
        END

        -- Solo permitir si está pendiente
        IF (@EstadoActual <> N'pendiente')
        BEGIN
            RAISERROR('La solicitud ya fue resuelta.', 16, 1);
            ROLLBACK;
            RETURN;
        END

        -- Resolver solicitud
        UPDATE aulavirtual.justificaciones
        SET
            estado = @nuevoEstado,
            id_docente_resolve = @Id_Docente,
            fecha_resolucion = GETDATE(),
            comentario_docente = @ComentarioDocente
        WHERE id = @Id_Justificacion;

        -- Si aprueba => marcar asistencia como justificada
        IF (@nuevoEstado = N'aprobada')
        BEGIN
            UPDATE aulavirtual.asistencia
            SET
                Justificada = 1,
                ComentarioJustificacion = COALESCE(@ComentarioDocente, ComentarioJustificacion)
            WHERE Id_Estudiante = @Id_Estudiante
              AND Id_Curso = @Id_Curso
              AND Fecha = @FechaAus;

            -- Si no existía, la creamos como AUSENTE JUSTIFICADO
            IF (@@ROWCOUNT = 0)
            BEGIN
                INSERT INTO aulavirtual.asistencia
                    (Id_Estudiante, Id_Curso, Fecha, Presente, Justificada, ComentarioJustificacion)
                VALUES
                    (@Id_Estudiante, @Id_Curso, @FechaAus, 0, 1, @ComentarioDocente);
            END
        END

        COMMIT;

        SELECT
            1 AS ok,
            @nuevoEstado AS estado,
            @Id_Curso AS id_curso,
            @Id_Estudiante AS id_estudiante,
            @FechaAus AS fecha_ausencia;

    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @Err NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@Err, 16, 1);
    END CATCH
END
GO


/* =========================================================
   D) (OPCIONAL) SP: CREAR SOLICITUD DESDE CHATBOT
   - Guarda estado=pendiente (evita NULL)
   - Evita duplicados pendientes por estudiante/curso/fecha
   ========================================================= */

CREATE OR ALTER PROCEDURE aulavirtual.sp_crear_solicitud_justificacion
    @Id_Estudiante INT,
    @Id_Curso      INT,
    @Fecha_Ausencia DATE,
    @Comprobante   NVARCHAR(255)
AS
BEGIN
    SET NOCOUNT ON;

    IF (@Id_Estudiante IS NULL OR @Id_Curso IS NULL OR @Fecha_Ausencia IS NULL OR @Comprobante IS NULL OR LTRIM(RTRIM(@Comprobante)) = N'')
    BEGIN
        RAISERROR('Datos incompletos.', 16, 1);
        RETURN;
    END

    IF (@Fecha_Ausencia > CAST(GETDATE() AS DATE))
    BEGIN
        RAISERROR('No se permite fecha futura.', 16, 1);
        RETURN;
    END

    IF EXISTS (
        SELECT 1
        FROM aulavirtual.justificaciones
        WHERE id_estudiante = @Id_Estudiante
          AND id_curso = @Id_Curso
          AND fecha_ausencia = @Fecha_Ausencia
          AND ISNULL(estado, N'pendiente') = N'pendiente'
    )
    BEGIN
        RAISERROR('Ya existe una solicitud pendiente para esa fecha.', 16, 1);
        RETURN;
    END

    INSERT INTO aulavirtual.justificaciones
        (id_estudiante, fecha_ausencia, comprobante, estado, fecha_solicitud, id_curso)
    VALUES
        (@Id_Estudiante, @Fecha_Ausencia, @Comprobante, N'pendiente', GETDATE(), @Id_Curso);

    SELECT 1 AS ok, SCOPE_IDENTITY() AS id;
END
GO
