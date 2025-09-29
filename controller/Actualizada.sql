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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `matricula`
--

LOCK TABLES `matricula` WRITE;
/*!40000 ALTER TABLE `matricula` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'fdgdfg','fdggfdgfd@gmail.com','$2y$10$V0Klm2OcgMntnfZaMpAuJOQMKhFuS3CSdHgJi6fdLbRRdhy6eqKDC','Estudiante','Activo','fdgfdgfdg'),(4,'fdgdfg','dsadasd@gmail.com','$2y$10$X0s2LOsUme8vrx3FoQRtvebDZ7hFg9X.PifLLxHPCGeMhM14HGJrm','Estudiante','Activo','asdsadasd'),(5,'j','j@gmail.com','$2y$10$j7yrzWCyMkOpq0ob9RX6zeDesJdI8bUbNiulnQtWAvVydNemIRWYu','Estudiante','Activo','j'),(8,'yyyyyyyyyyyyyy','yyyyyyyy@gmail.com','$2y$10$Pdg3povCERY.11AKwpJMhOYGXyxpJkLeQgeTRfDbLGYrP8n5NMwSW','Estudiante','Activo','yyyyyyyyyyy'),(10,'fsdfsdf','fghfgh@gmail.com','$2y$10$K9FGnb790VxE9lD/21oWe.CeSaqGr.BL4tQfHeZ4B2/8HW8Z7luPK','Docente','Activo','324234'),(11,'sadasd','se@gmail.com','$2y$10$kOtPm7MMNeyvdSJZoAnZSe/0tQjROm.ZuNZcS8yLOniwWuMFFL72e','Estudiante','Activo','324234'),(12,'ujuju','jujuujuj@gmail.com','$2y$10$WcXsEAbLLu4U0CVrXLbL9.zrGW3UcwuksNXTEFAJRGThF802J1eOC','Estudiante','Activo','34234432'),(13,'dsffsdfsd','jujuuasdjuj@gmail.com','$2y$10$XBBYjfWOhxVfeptkzqCIGOzWVv1PbN458IDFr7blLAcdRUh4RnRcW','Estudiante','Activo','32423'),(14,'julian','julian@gmail.com','$2y$10$gkxRBhTfTgcoc0gFFw8/UuHbyHr8XqaZm0/fHz6Zt11JwJ1P6ZPsu','Estudiante','Activo','324234'),(15,'fghghgjh','ghjgjhg@gmail.com','$2y$10$SVTI6TtjW7M29bylf8drB.O0Z3jaFC3KyCRBFCmdIp0pNmNDEGfNC','Docente','Activo','67575567567'),(16,'hthhfhft','fthfhtf@gmail.com','$2y$10$8cz5.Wvdri6RiXcqPKhLQOv/zIKHSphOwShirUIixJ0lHXganauL6','Padre','Activo','awdada'),(17,'Juan','Juan@gmail.com','$2y$10$CwS8O3Kuom4nvWe4ZLU2YOwUwDxb1gBdRFjhj9E.Bhcjc/1a.vm9K','Docente','Activo','adadwawd'),(18,'dfsfsd','sdfsdfsd@gmail.com','$2y$10$x9Tv3ePAMv9daZcVZDaBB.ZGNE4Q5rLMK7Pn3g.OgLVkmcFKC6o7u','Estudiante','Activo','asdasd'),(19,'sdfsdf','sdfsdf@gmail.com','$2y$10$IbWUDsmAD1bg.dB.TQbiZ.nBtk7Xobke/zdaahkoAl5bFCcPNGTgq','Estudiante','Activo','adsd'),(21,'Julian','asdasd@gmail.com','$2y$10$F/z5nqawGLkwc4JBijaJ1ORlzOjpuovmryruUlKX1o6BqlBqvSHwS','Estudiante','Activo','asdasd'),(24,'Julian','jaja@gmail.com','$2y$10$VlNcdi5TFyMGUc5Fx7//UucjHkDREJGwvSk2keo6QLZQofdsfJ7YW','Estudiante','Activo','asdas'),(25,'dfsdf','dsfsdf@gmail.com','$2y$10$0w3qDAJ.ztMgHYyRLKO0B.1Vi072HFGQ/Qfd7KX75s8Xso8lLM17K','Estudiante','Activo','rwewe'),(26,'Jose','asd@gmail.com','$2y$10$RI7rIszhvCr/MGNNwTuCQOBvYCI2v80jfcNmPn1CLNa5cpkdvInXG','Estudiante','Activo','sad'),(27,'Jose','asdas@gmail.com','$2y$10$4KLlQ4dCfCY8Snvd3DhpK.gcwCsfL78d9f0zI2y9O3cJJjHUJfefe','Estudiante','Activo','asdas'),(28,'www','asdsa@gmail.com','$2y$10$Jy7fgWbIv0TRDHNV75U1Hu01pFqys0f9qxiRPrgj3ubrIMc8LK7bi','Estudiante','Activo','asd'),(30,'ggg','f@gmail.com','$2y$10$B3bWfbS5kVH2sfPxbd/c7.1l3Qe5t4X/wmyhgLX2dLblM6wyq6VKi','Estudiante','Activo','asd'),(35,'jjj','fa@gmail.com','$2y$10$bDqNae0WLFlLsqv0zAFEgukavBFEqxZ8VFu8dAOJz7LCmk2HoZnwm','Estudiante','Activo','asdasd'),(36,'ttt','faa@gmail.com','$2y$10$cozXEZAzMiNZiTTDVzH7keM7AyiuDzEx.x6lR6gwAtsZ75Xq4OHnq','Estudiante','Activo','asd'),(37,'kkk','fe@gmail.com','$2y$10$EPNTBbk3QgkKR8fZBQnd3OJfHM2oldAiTQm6uhLRsy..mR0EYnk1G','Estudiante','Activo','dsad'),(38,'sadasd','fq@gmail.com','$2y$10$aeb.YwEEZN1TRLvxxbJKkuSXnuGCIFyvw5I7IhqelWtc/nFRoJtj6','Estudiante','Activo','asdas'),(40,'fff','fnnn@gmail.com','$2y$10$pq0w1h4ulKGCaqenfXO4X.IxYzDooxbHtmSXwx/aDEMA/9.16fSLC','Estudiante','Activo','sdf'),(41,'Julian Olivares','segurajulian71@santateresita.ac.cr','$2y$10$6h.dXMR3b7qXWyk68DqEeOd12zpQnH/88wNfqC9grdVfgJb7vL.Yi','Docente','Activo','34234324'),(42,'Julian Olivares','segurajulian@santateresita.ac.cr','$2y$10$lOZ.yP4izpGyyDHgXjZlNO5MlR4EkR.iSl1p0Coh1V8RNTbi2hRvi','Administrador','Activo','23432424'),(43,'Julian Olivares','ssegurajulian@santateresita.ac.cr','$2y$10$SgmkD1TlGykkBPxloBdIHemroHuQLNAbGOgX8KuEHulEfUmjQ5NDC','Administrador','Activo','12312');
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

-- Dump completed on 2025-09-29  3:54:58
