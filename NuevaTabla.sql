DROP TABLE IF EXISTS agenda;

CREATE TABLE agenda (
  Id_Agenda INT AUTO_INCREMENT PRIMARY KEY,
  Id_Docente INT NOT NULL,
  Titulo VARCHAR(100) NOT NULL,
  Descripcion TEXT,
  Fecha DATE NOT NULL,
  Hora TIME NOT NULL,
  FOREIGN KEY (Id_Docente) REFERENCES usuario(Id_Usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE agenda ADD COLUMN Estado ENUM('Pendiente', 'Completada', 'Cancelada') DEFAULT 'Pendiente' AFTER Hora;
