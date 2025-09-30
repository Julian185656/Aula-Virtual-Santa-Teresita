-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: aulavirtual
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asistencia`
--

DROP TABLE IF EXISTS `asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia` (
  `Id_Asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Estudiante` int(11) DEFAULT NULL,
  `Id_Curso` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Presente` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id_Asistencia`),
  KEY `Id_Estudiante` (`Id_Estudiante`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`Id_Estudiante`) REFERENCES `estudiante` (`Id_Estudiante`),
  CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia`
--

LOCK TABLES `asistencia` WRITE;
/*!40000 ALTER TABLE `asistencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `biblioteca`
--

DROP TABLE IF EXISTS `biblioteca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `biblioteca` (
  `Id_Recurso` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(200) DEFAULT NULL,
  `Archivo_URL` varchar(255) DEFAULT NULL,
  `Autor` varchar(100) DEFAULT NULL,
  `Tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_Recurso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biblioteca`
--

LOCK TABLES `biblioteca` WRITE;
/*!40000 ALTER TABLE `biblioteca` DISABLE KEYS */;
/*!40000 ALTER TABLE `biblioteca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calificacion`
--

DROP TABLE IF EXISTS `calificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calificacion` (
  `Id_Calificacion` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Evaluacion` int(11) DEFAULT NULL,
  `Id_Estudiante` int(11) DEFAULT NULL,
  `Nota` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`Id_Calificacion`),
  KEY `Id_Evaluacion` (`Id_Evaluacion`),
  KEY `Id_Estudiante` (`Id_Estudiante`),
  CONSTRAINT `calificacion_ibfk_1` FOREIGN KEY (`Id_Evaluacion`) REFERENCES `evaluacion` (`Id_Evaluacion`),
  CONSTRAINT `calificacion_ibfk_2` FOREIGN KEY (`Id_Estudiante`) REFERENCES `estudiante` (`Id_Estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calificacion`
--

LOCK TABLES `calificacion` WRITE;
/*!40000 ALTER TABLE `calificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `calificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentarios` (
  `Id_Comentario` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Foro` int(11) DEFAULT NULL,
  `Texto` text DEFAULT NULL,
  PRIMARY KEY (`Id_Comentario`),
  KEY `Id_Foro` (`Id_Foro`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`Id_Foro`) REFERENCES `foro` (`Id_Foro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios`
--

LOCK TABLES `comentarios` WRITE;
/*!40000 ALTER TABLE `comentarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso` (
  `Id_Curso` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Id_Docente` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Curso`),
  KEY `Id_Docente` (`Id_Docente`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`Id_Docente`) REFERENCES `docente` (`Id_Docente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
INSERT INTO `curso` VALUES (1,'Mate','Aprende ',91),(2,'Hola','a',91);
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente`
--

DROP TABLE IF EXISTS `docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docente` (
  `Id_Docente` int(11) NOT NULL,
  `Especialidad` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id_Docente`),
  CONSTRAINT `docente_ibfk_1` FOREIGN KEY (`Id_Docente`) REFERENCES `usuario` (`Id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente`
--

LOCK TABLES `docente` WRITE;
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` VALUES (91,'1'),(102,'1');
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encuesta`
--

DROP TABLE IF EXISTS `encuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta` (
  `Id_Encuesta` int(11) NOT NULL AUTO_INCREMENT,
  `Pregunta` text DEFAULT NULL,
  `Fecha_Publicacion` date DEFAULT NULL,
  `Id_Curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Encuesta`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `encuesta_ibfk_1` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuesta`
--

LOCK TABLES `encuesta` WRITE;
/*!40000 ALTER TABLE `encuesta` DISABLE KEYS */;
/*!40000 ALTER TABLE `encuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrega_tarea`
--

DROP TABLE IF EXISTS `entrega_tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrega_tarea` (
  `Id_Entrega` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Tarea` int(11) DEFAULT NULL,
  `Id_Estudiante` int(11) DEFAULT NULL,
  `Archivo_URL` varchar(255) DEFAULT NULL,
  `Fecha_Entrega` date DEFAULT NULL,
  `Calificacion` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`Id_Entrega`),
  KEY `Id_Tarea` (`Id_Tarea`),
  KEY `Id_Estudiante` (`Id_Estudiante`),
  CONSTRAINT `entrega_tarea_ibfk_1` FOREIGN KEY (`Id_Tarea`) REFERENCES `tarea` (`Id_Tarea`),
  CONSTRAINT `entrega_tarea_ibfk_2` FOREIGN KEY (`Id_Estudiante`) REFERENCES `estudiante` (`Id_Estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrega_tarea`
--

LOCK TABLES `entrega_tarea` WRITE;
/*!40000 ALTER TABLE `entrega_tarea` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrega_tarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante` (
  `Id_Estudiante` int(11) NOT NULL,
  `Grado` varchar(10) DEFAULT NULL,
  `Seccion` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`Id_Estudiante`),
  CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`Id_Estudiante`) REFERENCES `usuario` (`Id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante`
--

LOCK TABLES `estudiante` WRITE;
/*!40000 ALTER TABLE `estudiante` DISABLE KEYS */;
INSERT INTO `estudiante` VALUES (92,'1','1'),(101,'1','1');
/*!40000 ALTER TABLE `estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluacion`
--

DROP TABLE IF EXISTS `evaluacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluacion` (
  `Id_Evaluacion` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Id_Curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Evaluacion`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `evaluacion_ibfk_1` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluacion`
--

LOCK TABLES `evaluacion` WRITE;
/*!40000 ALTER TABLE `evaluacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foro`
--

DROP TABLE IF EXISTS `foro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foro` (
  `Id_Foro` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Foro`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `foro_ibfk_1` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foro`
--

LOCK TABLES `foro` WRITE;
/*!40000 ALTER TABLE `foro` DISABLE KEYS */;
/*!40000 ALTER TABLE `foro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `matricula`
--

DROP TABLE IF EXISTS `matricula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `matricula` (
  `Id_Matricula` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Estudiante` int(11) DEFAULT NULL,
  `Id_Curso` int(11) DEFAULT NULL,
  `Fecha_Matricula` date DEFAULT NULL,
  PRIMARY KEY (`Id_Matricula`),
  KEY `Id_Estudiante` (`Id_Estudiante`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `matricula_ibfk_1` FOREIGN KEY (`Id_Estudiante`) REFERENCES `estudiante` (`Id_Estudiante`),
  CONSTRAINT `matricula_ibfk_2` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matricula`
--

LOCK TABLES `matricula` WRITE;
/*!40000 ALTER TABLE `matricula` DISABLE KEYS */;
INSERT INTO `matricula` VALUES (1,92,1,'0000-00-00'),(2,101,1,'0000-00-00');
/*!40000 ALTER TABLE `matricula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `Id_Notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `Mensaje` text DEFAULT NULL,
  `Tipo` varchar(50) DEFAULT NULL,
  `Fecha_Envio` datetime DEFAULT NULL,
  `Id_Usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Notificacion`),
  KEY `Id_Usuario` (`Id_Usuario`),
  CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `padre`
--

DROP TABLE IF EXISTS `padre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `padre` (
  `Id_Padre` int(11) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id_Padre`),
  CONSTRAINT `padre_ibfk_1` FOREIGN KEY (`Id_Padre`) REFERENCES `usuario` (`Id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `padre`
--

LOCK TABLES `padre` WRITE;
/*!40000 ALTER TABLE `padre` DISABLE KEYS */;
/*!40000 ALTER TABLE `padre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soporte`
--

DROP TABLE IF EXISTS `soporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soporte` (
  `Id_Ticket` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Usuario` int(11) DEFAULT NULL,
  `Asunto` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Estado` enum('Abierto','En Proceso','Cerrado') DEFAULT 'Abierto',
  `Fecha_Creacion` datetime DEFAULT NULL,
  PRIMARY KEY (`Id_Ticket`),
  KEY `Id_Usuario` (`Id_Usuario`),
  CONSTRAINT `soporte_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soporte`
--

LOCK TABLES `soporte` WRITE;
/*!40000 ALTER TABLE `soporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `soporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarea`
--

DROP TABLE IF EXISTS `tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarea` (
  `Id_Tarea` int(11) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Fecha_Entrega` date DEFAULT NULL,
  `Id_Curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id_Tarea`),
  KEY `Id_Curso` (`Id_Curso`),
  CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`Id_Curso`) REFERENCES `curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarea`
--

LOCK TABLES `tarea` WRITE;
/*!40000 ALTER TABLE `tarea` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Rol` enum('Administrador','Docente','Estudiante','Padre') NOT NULL,
  `Estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `Telefono` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id_Usuario`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (84,'Julian','segurajulian711@santateresita.ac.cr','$2y$10$nblUF0kqX5c7xfOnFaKISuPTZSMKC4ChU.qZjVtnzLvzDSFUTNLiS','Administrador','Activo','222'),(91,'Pana','segurajulian71@santateresita.ac.cr','$2y$10$h/fGvuRhddEAScirooRjMuE1RgkMpRZtQ9vb8QwHxZMCSZ0fFQckq','Docente','Activo','123'),(92,'Juan','segurajulian712@santateresita.ac.cr','$2y$10$GbhwkBwNVRh0.lbhJc3vWuepXDfKEk75yCJ99cXC.ZBd/nmfJjwcS','Estudiante','Activo','123'),(100,'Yo','segurajulian71@gmail.com','$2y$10$h17YqqPXWWt5Y3J82shqnuL93gPAJIEM9JE77Vaw2gfTooOGc/URy','Administrador','Activo','123'),(101,'Luis','aa@santateresita.ac.cr','$2y$10$oCuzSA2.zXzm3KKNit5I6eW73OgEqqZ/XjZrV70QvbojH3MEaHABO','Docente','Inactivo','123'),(102,'PANA12','a@santateresita.ac.cr','$2y$10$8ENtPy4Ybs9mwCaCLCiKfuEnO4jrYTY2nBaVUC8xKPnVtF6YaKm.6','Docente','Activo','123');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_usuarios_detalle`
--

DROP TABLE IF EXISTS `vw_usuarios_detalle`;
/*!50001 DROP VIEW IF EXISTS `vw_usuarios_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_usuarios_detalle` AS SELECT 
 1 AS `Id_Usuario`,
 1 AS `Nombre`,
 1 AS `Email`,
 1 AS `Telefono`,
 1 AS `Rol`,
 1 AS `Estado`,
 1 AS `Especialidad`,
 1 AS `Grado`,
 1 AS `Seccion`,
 1 AS `EsPadre`,
 1 AS `EsDocente`,
 1 AS `EsEstudiante`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'aulavirtual'
--
/*!50003 DROP PROCEDURE IF EXISTS `actualizarcontrasennaUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarcontrasennaUsuario`(
    IN p_correo  VARCHAR(255),
    IN p_codigo  VARCHAR(255)
)
BEGIN
    UPDATE usuarios 
    SET contrasenna = p_codigo
    WHERE correo = p_correo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `actualizarUsuarioAdmin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarUsuarioAdmin`(
    IN p_Id_Usuario INT,
    IN p_Nombre VARCHAR(100),
    IN p_Email VARCHAR(100),
    IN p_Telefono VARCHAR(20),
    IN p_Contrasena VARCHAR(255),
    IN p_Rol VARCHAR(50),
    IN p_Estado VARCHAR(50)
)
BEGIN

    IF p_Contrasena IS NOT NULL AND p_Contrasena != '' THEN
        UPDATE usuario
        SET Nombre = p_Nombre,
            Email = p_Email,
            Telefono = p_Telefono,
            Contrasena = p_Contrasena,
            Rol = p_Rol,
            Estado = p_Estado
        WHERE Id_Usuario = p_Id_Usuario;
    ELSE
        UPDATE usuario
        SET Nombre = p_Nombre,
            Email = p_Email,
            Telefono = p_Telefono,
            Rol = p_Rol,
            Estado = p_Estado
        WHERE Id_Usuario = p_Id_Usuario;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `crearUsuarioAdmin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `crearUsuarioAdmin`(
  IN p_nombre       VARCHAR(100),
  IN p_email        VARCHAR(100),
  IN p_telefono     VARCHAR(20),
  IN p_contrasena   VARCHAR(255),
  IN p_rol          VARCHAR(20),  -- 'Administrador'|'Docente'|'Estudiante'|'Padre'
  IN p_estado       VARCHAR(10),  -- 'Activo'|'Inactivo'
  IN p_grado        VARCHAR(10),  -- solo si Estudiante
  IN p_seccion      VARCHAR(10),  -- solo si Estudiante
  IN p_especialidad VARCHAR(100)  -- solo si Docente
)
BEGIN
  DECLARE v_dominio VARCHAR(50) DEFAULT '@santateresita.ac.cr';
  DECLARE v_email_limpio VARCHAR(100);
  DECLARE v_id INT;
  DECLARE v_dup INT DEFAULT 0;

  SET v_email_limpio = TRIM(LOWER(p_email));

  IF RIGHT(v_email_limpio, LENGTH(v_dominio)) <> v_dominio THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'El correo debe ser institucional (@santateresita.ac.cr)';
  END IF;

  SELECT COUNT(*) INTO v_dup FROM usuario WHERE Email = v_email_limpio;
  IF v_dup > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'El correo ya está registrado';
  END IF;

  START TRANSACTION;

  INSERT INTO usuario (Nombre, Email, Telefono, Contrasena, Rol, Estado)
  VALUES (p_nombre, v_email_limpio, p_telefono, p_contrasena, p_rol, p_estado);

  SET v_id = LAST_INSERT_ID();

  IF p_rol = 'Padre' THEN
      INSERT INTO padre (Id_Padre, Telefono) VALUES (v_id, p_telefono);
  ELSEIF p_rol = 'Docente' THEN
      INSERT INTO docente (Id_Docente, Especialidad) VALUES (v_id, p_especialidad);
  ELSEIF p_rol = 'Estudiante' THEN
      INSERT INTO estudiante (Id_Estudiante, Grado, Seccion) VALUES (v_id, p_grado, p_seccion);
  END IF;

  COMMIT;

  CALL obtenerUsuarioDetalle(v_id);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `desactivarUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `desactivarUsuario`(
  IN p_id_usuario INT,
  IN p_nuevo_estado VARCHAR(10) -- 'Activo' | 'Inactivo'
)
BEGIN
  UPDATE usuario SET Estado = p_nuevo_estado WHERE Id_Usuario = p_id_usuario;
  CALL obtenerUsuarioDetalle(p_id_usuario);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `eliminarUsuarioFuerte` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarUsuarioFuerte`(IN p_id_usuario INT)
BEGIN
  DECLARE v_doc_cursos INT DEFAULT 0;
  DECLARE v_est_refs   INT DEFAULT 0;
  DECLARE v_otras_refs INT DEFAULT 0;


  SELECT COUNT(*) INTO v_doc_cursos FROM curso WHERE Id_Docente = p_id_usuario;


  SELECT (SELECT COUNT(*) FROM matricula      WHERE Id_Estudiante = p_id_usuario)
       + (SELECT COUNT(*) FROM entrega_tarea  WHERE Id_Estudiante = p_id_usuario)
       + (SELECT COUNT(*) FROM calificacion   WHERE Id_Estudiante = p_id_usuario)
       + (SELECT COUNT(*) FROM asistencia     WHERE Id_Estudiante = p_id_usuario)
    INTO v_est_refs;


  SELECT (SELECT COUNT(*) FROM soporte      WHERE Id_Usuario = p_id_usuario)
       + (SELECT COUNT(*) FROM notificacion WHERE Id_Usuario = p_id_usuario)
    INTO v_otras_refs;

  IF v_doc_cursos > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se puede eliminar: el usuario es Docente con cursos asignados.';
  END IF;

  IF v_est_refs > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se puede eliminar: el usuario es Estudiante con registros académicos.';
  END IF;

  IF v_otras_refs > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'No se puede eliminar: el usuario tiene registros de soporte/notificaciones.';
  END IF;

  START TRANSACTION;
    DELETE FROM padre      WHERE Id_Padre      = p_id_usuario;
    DELETE FROM docente    WHERE Id_Docente    = p_id_usuario;
    DELETE FROM estudiante WHERE Id_Estudiante = p_id_usuario;
    DELETE FROM usuario    WHERE Id_Usuario    = p_id_usuario;
  COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `obtenerUsuarioDetalle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerUsuarioDetalle`(IN p_id INT)
BEGIN
  SELECT * FROM vw_usuarios_detalle WHERE Id_Usuario = p_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `obtenerUsuarioRecuperarContrasena` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerUsuarioRecuperarContrasena`(
    IN p_correo VARCHAR(255)
)
BEGIN
    SELECT Id_Usuario
    FROM usuario
    WHERE Email = p_correo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `obtenerUsuarioRecuperarContrasenna` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerUsuarioRecuperarContrasenna`(IN p_correo VARCHAR(255))
BEGIN
    SELECT id_usuario 
    FROM usuarios 
    WHERE correo = p_correo;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `registroUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `registroUsuario`(
    IN p_nombre VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_telefono VARCHAR(20),
    IN p_contrasena VARCHAR(255),
    IN p_rol VARCHAR(50)
)
BEGIN
    DECLARE dominio VARCHAR(50) DEFAULT '@santateresita.ac.cr';

    IF RIGHT(LOWER(TRIM(p_email)), LENGTH(dominio)) = dominio THEN
        INSERT INTO USUARIO (Nombre, Email, Telefono, Contrasena, Rol, Estado)
        VALUES (p_nombre, TRIM(p_email), p_telefono, p_contrasena, p_rol, 'Activo');
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'El correo debe ser institucional (@santateresita.ac.cr)';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_obtener_alumnos_docente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_alumnos_docente`(IN docenteId INT)
BEGIN
    SELECT u.Id_Usuario AS id, u.Nombre AS nombre, u.Email AS correo,
           c.Id_Curso AS id_curso, e.Id_Estudiante AS id_estudiante
    FROM usuario u
    INNER JOIN estudiante e ON u.Id_Usuario = e.Id_Estudiante
    INNER JOIN matricula m ON e.Id_Estudiante = m.Id_Estudiante
    INNER JOIN curso c ON m.Id_Curso = c.Id_Curso
    WHERE c.Id_Docente = docenteId;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_obtener_perfil_alumno` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtener_perfil_alumno`(
    IN p_docente_id INT,
    IN p_alumno_id INT
)
BEGIN
    SELECT u.Id_Usuario, u.Nombre, u.Email, e.Grado, e.Seccion
    FROM usuario u
    INNER JOIN estudiante e ON u.Id_Usuario = e.Id_Estudiante
    INNER JOIN matricula m ON e.Id_Estudiante = m.Id_Estudiante
    INNER JOIN curso c ON m.Id_Curso = c.Id_Curso
    WHERE c.Id_Docente = p_docente_id
      AND u.Id_Usuario = p_alumno_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `vw_usuarios_detalle`
--

/*!50001 DROP VIEW IF EXISTS `vw_usuarios_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_usuarios_detalle` AS select `u`.`Id_Usuario` AS `Id_Usuario`,`u`.`Nombre` AS `Nombre`,`u`.`Email` AS `Email`,`u`.`Telefono` AS `Telefono`,`u`.`Rol` AS `Rol`,`u`.`Estado` AS `Estado`,`d`.`Especialidad` AS `Especialidad`,`e`.`Grado` AS `Grado`,`e`.`Seccion` AS `Seccion`,`p`.`Id_Padre` is not null AS `EsPadre`,`d`.`Id_Docente` is not null AS `EsDocente`,`e`.`Id_Estudiante` is not null AS `EsEstudiante` from (((`usuario` `u` left join `padre` `p` on(`p`.`Id_Padre` = `u`.`Id_Usuario`)) left join `docente` `d` on(`d`.`Id_Docente` = `u`.`Id_Usuario`)) left join `estudiante` `e` on(`e`.`Id_Estudiante` = `u`.`Id_Usuario`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-30  8:18:35
