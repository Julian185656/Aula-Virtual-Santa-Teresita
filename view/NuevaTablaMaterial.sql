CREATE TABLE material_curso (
    Id_Material INT AUTO_INCREMENT PRIMARY KEY,
    Id_Curso INT NOT NULL,
    Id_Usuario INT NOT NULL,
    Titulo VARCHAR(255) NOT NULL,
    Descripcion VARCHAR(500) NULL,
    Archivo_URL VARCHAR(500) NOT NULL,
    Fecha_Subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_material_curso
        FOREIGN KEY (Id_Curso) REFERENCES curso(Id_Curso)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_material_usuario
        FOREIGN KEY (Id_Usuario) REFERENCES usuario(Id_Usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);
