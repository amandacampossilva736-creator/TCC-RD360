-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: eletro
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `as_built`
--

DROP TABLE IF EXISTS `as_built`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `as_built` (
  `n_as_built` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `observacoes` text,
  `data_fiscalizacao` date DEFAULT NULL,
  `fk_obra_id_obra` int DEFAULT NULL,
  `fk_status_id_status` int DEFAULT NULL,
  PRIMARY KEY (`n_as_built`),
  KEY `fk_obra_id_obra` (`fk_obra_id_obra`),
  KEY `fk_status_id_status` (`fk_status_id_status`),
  CONSTRAINT `as_built_ibfk_1` FOREIGN KEY (`fk_obra_id_obra`) REFERENCES `obra` (`id_obra`),
  CONSTRAINT `as_built_ibfk_2` FOREIGN KEY (`fk_status_id_status`) REFERENCES `status` (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `as_built`
--

LOCK TABLES `as_built` WRITE;
/*!40000 ALTER TABLE `as_built` DISABLE KEYS */;
INSERT INTO `as_built` VALUES (7,'As Built Teste RD360','Amanda Campos','Testando alterações.','2026-09-22',10,2);
/*!40000 ALTER TABLE `as_built` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipamentos`
--

DROP TABLE IF EXISTS `equipamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipamentos` (
  `id_equipamento` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(30) DEFAULT NULL,
  `potencia` varchar(30) DEFAULT NULL,
  `placa` varchar(100) DEFAULT NULL,
  `cia` varchar(100) DEFAULT NULL,
  `fk_poste_geolocalizacao_nota` varchar(50) DEFAULT NULL,
  `fk_poste_geolocalizacao_id_geolocalizacao` int DEFAULT NULL,
  PRIMARY KEY (`id_equipamento`),
  KEY `fk_poste_geolocalizacao_nota` (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`),
  CONSTRAINT `equipamentos_ibfk_1` FOREIGN KEY (`fk_poste_geolocalizacao_nota`, `fk_poste_geolocalizacao_id_geolocalizacao`) REFERENCES `poste_geolocalizacao` (`nota`, `id_geolocalizacao`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipamentos`
--

LOCK TABLES `equipamentos` WRITE;
/*!40000 ALTER TABLE `equipamentos` DISABLE KEYS */;
INSERT INTO `equipamentos` VALUES (6,'','','J02475','456987','teste-00000',1);
/*!40000 ALTER TABLE `equipamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estrutura_primaria`
--

DROP TABLE IF EXISTS `estrutura_primaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estrutura_primaria` (
  `id_estrutura_primaria` int NOT NULL AUTO_INCREMENT,
  `tipo_estrutura` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL,
  PRIMARY KEY (`id_estrutura_primaria`),
  KEY `fk_pontos_coletados_id_ponto` (`fk_pontos_coletados_id_ponto`),
  CONSTRAINT `estrutura_primaria_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estrutura_primaria`
--

LOCK TABLES `estrutura_primaria` WRITE;
/*!40000 ALTER TABLE `estrutura_primaria` DISABLE KEYS */;
/*!40000 ALTER TABLE `estrutura_primaria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estrutura_secundaria`
--

DROP TABLE IF EXISTS `estrutura_secundaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estrutura_secundaria` (
  `id_estrutura_secundaria` int NOT NULL AUTO_INCREMENT,
  `tipo_estrutura` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL,
  PRIMARY KEY (`id_estrutura_secundaria`),
  KEY `fk_pontos_coletados_id_ponto` (`fk_pontos_coletados_id_ponto`),
  CONSTRAINT `estrutura_secundaria_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estrutura_secundaria`
--

LOCK TABLES `estrutura_secundaria` WRITE;
/*!40000 ALTER TABLE `estrutura_secundaria` DISABLE KEYS */;
/*!40000 ALTER TABLE `estrutura_secundaria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos`
--

DROP TABLE IF EXISTS `fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fotos` (
  `id_foto` int NOT NULL AUTO_INCREMENT,
  `n_ponto` int DEFAULT NULL,
  `caminho_imagem` varchar(255) DEFAULT NULL,
  `data_foto` date DEFAULT NULL,
  `fk_as_built_n_as_built` int DEFAULT NULL,
  `fk_pontos_coletados_id_ponto` int DEFAULT NULL,
  PRIMARY KEY (`id_foto`),
  KEY `fk_as_built_n_as_built` (`fk_as_built_n_as_built`),
  KEY `fk_fotos_ponto` (`fk_pontos_coletados_id_ponto`),
  CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`fk_as_built_n_as_built`) REFERENCES `as_built` (`n_as_built`),
  CONSTRAINT `fotos_ibfk_2` FOREIGN KEY (`fk_pontos_coletados_id_ponto`) REFERENCES `pontos_coletados` (`id_ponto`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos`
--

LOCK TABLES `fotos` WRITE;
/*!40000 ALTER TABLE `fotos` DISABLE KEYS */;
INSERT INTO `fotos` VALUES (1,NULL,'uploads/rd360_6ab2975f851058.91265586.jpg','2026-09-22',7,NULL),(2,1,'uploads/rd360_6ab2c3a150c5c.jpeg','2026-09-22',NULL,1);
/*!40000 ALTER TABLE `fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `municipio`
--

DROP TABLE IF EXISTS `municipio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `municipio` (
  `id_municipio` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `municipio`
--

LOCK TABLES `municipio` WRITE;
/*!40000 ALTER TABLE `municipio` DISABLE KEYS */;
INSERT INTO `municipio` VALUES (1,'Feira de Santana','BA'),(2,'varzea do poço','BA'),(3,'JACOBINA','BA');
/*!40000 ALTER TABLE `municipio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obra`
--

DROP TABLE IF EXISTS `obra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `obra` (
  `id_obra` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) DEFAULT NULL,
  `nota` varchar(50) DEFAULT NULL,
  `projeto_obra` varchar(100) DEFAULT NULL,
  `id_investimento` varchar(50) DEFAULT NULL,
  `qtd_postes` int DEFAULT NULL,
  `uc_previsto` int DEFAULT NULL,
  `data_energizacao` date DEFAULT NULL,
  `fk_usuario_id_usuario` int DEFAULT NULL,
  `fk_municipio_id_municipio` int DEFAULT NULL,
  PRIMARY KEY (`id_obra`),
  KEY `fk_usuario_id_usuario` (`fk_usuario_id_usuario`),
  KEY `fk_municipio_id_municipio` (`fk_municipio_id_municipio`),
  CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`fk_usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `obra_ibfk_2` FOREIGN KEY (`fk_municipio_id_municipio`) REFERENCES `municipio` (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obra`
--

LOCK TABLES `obra` WRITE;
/*!40000 ALTER TABLE `obra` DISABLE KEYS */;
INSERT INTO `obra` VALUES (10,'Teste-alterações','23567849','B-0098213','12',6,18,'2026-09-09',1,2);
/*!40000 ALTER TABLE `obra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pontos_coletados`
--

DROP TABLE IF EXISTS `pontos_coletados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pontos_coletados` (
  `id_ponto` int NOT NULL AUTO_INCREMENT,
  `nota` varchar(50) DEFAULT NULL,
  `nota_obra` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fk_as_built_n_as_built` int DEFAULT NULL,
  `fk_poste_geolocalizacao_nota` varchar(50) DEFAULT NULL,
  `fk_poste_geolocalizacao_id_geolocalizacao` int DEFAULT NULL,
  PRIMARY KEY (`id_ponto`),
  KEY `fk_as_built_n_as_built` (`fk_as_built_n_as_built`),
  KEY `fk_poste_geolocalizacao_nota` (`fk_poste_geolocalizacao_nota`,`fk_poste_geolocalizacao_id_geolocalizacao`),
  CONSTRAINT `pontos_coletados_ibfk_1` FOREIGN KEY (`fk_as_built_n_as_built`) REFERENCES `as_built` (`n_as_built`),
  CONSTRAINT `pontos_coletados_ibfk_2` FOREIGN KEY (`fk_poste_geolocalizacao_nota`, `fk_poste_geolocalizacao_id_geolocalizacao`) REFERENCES `poste_geolocalizacao` (`nota`, `id_geolocalizacao`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pontos_coletados`
--

LOCK TABLES `pontos_coletados` WRITE;
/*!40000 ALTER TABLE `pontos_coletados` DISABLE KEYS */;
INSERT INTO `pontos_coletados` VALUES (1,'teste-00000','23567849',-11.18236048,-40.53705910,7,'teste-00000',1);
/*!40000 ALTER TABLE `pontos_coletados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poste_geolocalizacao`
--

DROP TABLE IF EXISTS `poste_geolocalizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `poste_geolocalizacao` (
  `nota` varchar(50) NOT NULL,
  `id_geolocalizacao` int NOT NULL,
  `tipo_poste` varchar(50) DEFAULT NULL,
  `material_poste` varchar(30) DEFAULT NULL,
  `altura_poste` varchar(20) DEFAULT NULL,
  `esforco_poste` varchar(20) DEFAULT NULL,
  `coordenada_latitude` decimal(10,8) DEFAULT NULL,
  `cordenada_longitude` decimal(11,8) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fk_obra_id_obra` int DEFAULT NULL,
  PRIMARY KEY (`nota`,`id_geolocalizacao`),
  KEY `fk_obra_id_obra` (`fk_obra_id_obra`),
  CONSTRAINT `poste_geolocalizacao_ibfk_1` FOREIGN KEY (`fk_obra_id_obra`) REFERENCES `obra` (`id_obra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poste_geolocalizacao`
--

LOCK TABLES `poste_geolocalizacao` WRITE;
/*!40000 ALTER TABLE `poste_geolocalizacao` DISABLE KEYS */;
INSERT INTO `poste_geolocalizacao` VALUES ('teste-00000',1,'Duplo T','Fibra de vidro','5m','123',-11.18236048,-40.53705910,-11.18236048,-40.53705910,10);
/*!40000 ALTER TABLE `poste_geolocalizacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `nome_status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Pendente'),(2,'Em andamento'),(3,'Concluída');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo_usuario` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Amanda Campos','amanda@gmail.com','$2y$10$xydXqxAiHSP8rXU8guVB9.J.6HESc32iU8Rr4RLUvNhGICOf2WjtO','Técnico');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vao`
--

DROP TABLE IF EXISTS `vao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vao` (
  `id_vao` int NOT NULL AUTO_INCREMENT,
  `distancia` varchar(30) DEFAULT NULL,
  `tipo_condutor` varchar(100) DEFAULT NULL,
  `fk_pontos_coletados_id_ponto_origem` int DEFAULT NULL,
  `fk_pontos_coletados_id_ponto_destino` int DEFAULT NULL,
  PRIMARY KEY (`id_vao`),
  KEY `fk_pontos_coletados_id_ponto_origem` (`fk_pontos_coletados_id_ponto_origem`),
  KEY `fk_pontos_coletados_id_ponto_destino` (`fk_pontos_coletados_id_ponto_destino`),
  CONSTRAINT `vao_ibfk_1` FOREIGN KEY (`fk_pontos_coletados_id_ponto_origem`) REFERENCES `pontos_coletados` (`id_ponto`),
  CONSTRAINT `vao_ibfk_2` FOREIGN KEY (`fk_pontos_coletados_id_ponto_destino`) REFERENCES `pontos_coletados` (`id_ponto`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vao`
--

LOCK TABLES `vao` WRITE;
/*!40000 ALTER TABLE `vao` DISABLE KEYS */;
/*!40000 ALTER TABLE `vao` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-22 16:04:47
