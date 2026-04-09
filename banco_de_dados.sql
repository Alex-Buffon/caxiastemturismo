-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: caxiasturismo
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` varchar(255) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `alt_text` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'img1.png','Descubra o Interior de Caxias do Sul','Roteiros de charme, gastronomia e natureza na Serra Gaúcha.','#roteiros',1,'2026-03-12 12:16:28',''),(2,'img.santalucia.png','Santa Lúcia','Um refúgio rural com belas paisagens naturais.','#destinos',2,'2026-03-12 12:16:28',''),(3,'img.fazendasouza.png','Fazenda Souza','Distrito que preserva a autêntica cultura italiana.','#destinos',3,'2026-03-12 12:16:28','');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depoimentos`
--

DROP TABLE IF EXISTS `depoimentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `depoimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `aprovado_em` timestamp NULL DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `destino` varchar(255) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `nota` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depoimentos`
--

LOCK TABLES `depoimentos` WRITE;
/*!40000 ALTER TABLE `depoimentos` DISABLE KEYS */;
INSERT INTO `depoimentos` VALUES (1,'Maria Silva','maria@example.com','Adorei a visita! Recomendo para quem busca tranquilidade e boa comida.','rejected','2026-03-13 12:28:38','2026-03-13 14:44:18','Porto Alegre','Santa Lúcia','127','Seeder',5),(2,'João Pereira','joao@example.com','Um lugar incrível, cheio de história e natureza. Voltarei em breve!','approved','2026-03-13 12:28:38','2026-03-13 12:28:38','Florianópolis','Galópolis','127','Seeder',4),(3,'Ana Costa','ana@example.com','Uma experiência inesquecível: paisagens, acolhimento e muita cultura local.','approved','2026-03-13 12:28:38','2026-03-13 12:28:38','Curitiba','Terceira Légua','127','Seeder',5);
/*!40000 ALTER TABLE `depoimentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinos`
--

DROP TABLE IF EXISTS `destinos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destinos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_keywords` varchar(255) DEFAULT '',
  `meta_description` text DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinos`
--

LOCK TABLES `destinos` WRITE;
/*!40000 ALTER TABLE `destinos` DISABLE KEYS */;
INSERT INTO `destinos` VALUES (1,'Santa Lúcia e Vila Oliva','santa-lucia','Explore as belezas e a tranquilidade de Santa L·cia e Vila Oliva.','img.santalucia.png','Natureza',0,1,'2026-03-09 13:53:23','2026-03-09 13:58:11','',''),(2,'Fazenda Souza e Vila Seca','fazenda-souza','Descubra a hist¾ria e a cultura de Fazenda Souza e Vila Seca.','img.fazendasouza.png','História',0,1,'2026-03-09 13:53:23','2026-03-12 19:24:42','',''),(3,'Terceira Légua','terceira-legua','Aprecie a gastronomia e as paisagens da Terceira LÚgua.','img.terceiralegua.png','Gastronomia',0,0,'2026-03-09 13:53:23','2026-03-12 19:25:53','',''),(4,'Galópolis e Região','galopolis','Conheþa o patrim¶nio hist¾rico e natural de Gal¾polis e seus arredores.','img.galopolis.png','História',0,1,'2026-03-09 13:53:23','2026-03-12 19:23:42','',''),(5,'Turismo Religioso','turismo-religioso','Descubra os templos e a fé que movem a região.','img.turismoreligioso.png','',0,0,'2026-03-09 13:53:23','2026-03-12 14:11:34','','');
/*!40000 ALTER TABLE `destinos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empreendimentos`
--

DROP TABLE IF EXISTS `empreendimentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empreendimentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destino_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `destino_id` (`destino_id`),
  CONSTRAINT `empreendimentos_ibfk_1` FOREIGN KEY (`destino_id`) REFERENCES `destinos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empreendimentos`
--

LOCK TABLES `empreendimentos` WRITE;
/*!40000 ALTER TABLE `empreendimentos` DISABLE KEYS */;
INSERT INTO `empreendimentos` VALUES (1,4,'Pousada Histórica','Pousada aconchegante no centro hist¾rico de Gal¾polis.','(54) 3222-1111','BR-116, km 160',NULL,'Hotel, Pousada',''),(2,4,'Restaurante da Nona','A verdadeira culinßria italiana. Pratos fartos.','(54) 3222-2222','Rua Central, 123',NULL,'Restaurante, CafÚ',''),(3,1,'Vinícola Santa Lúcia','Experimente vinhos artesanais e conheþa a produþÒo local em um ambiente familiar.','','','img.santalucia.png','Vinícola, Gastronomia',''),(4,1,'Pousada Recanto da Serra','Hospedagem aconchegante com vista para as montanhas e cafÚ colonial.',NULL,NULL,'img.terceiralegua.png','Pousada, Hospedagem',''),(5,2,'Café Colonial Souza','Sabores tÝpicos da serra em um ambiente acolhedor e familiar.','(54) 3222-1111','Rua Corso 1252 - Vila Oliva','img.fazendasouza.png','Restaurante, Café Colonial',''),(6,2,'Sítio Vila Seca','Vivencie a vida rural, trilhas e produtos coloniais direto do produtor.','','','img.terceiralegua.png','Agroturismo, Rural',''),(7,3,'Restaurante Típico Légua','Gastronomia tÝpica italiana com pratos feitos com receitas tradicionais da nona.','','','img.terceiralegua.png','Restaurante, Gastronomia Italiana',''),(8,3,'Vinícola da Serra Gaúcha','Vinhos artesanais produzidos com uvas da regiÒo, visite a cantina e deguste.','','','img.terceiralegua.png','Vinícola, Degustação',''),(9,5,'Santuário Nossa Senhora de Caravaggio','Um dos mais importantes centros religiosos do Sul do Brasil, com romarias ao longo do ano.','','','img1.png','#santuariocaravaggio',''),(10,5,'Paróquia São Luís Gonzaga','Igreja hist¾rica com arquitetura italiana preservada, patrim¶nio da imigraþÒo.','','','img1.png','Turismo Religioso, Igreja','');
/*!40000 ALTER TABLE `empreendimentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galeria`
--

DROP TABLE IF EXISTS `galeria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galeria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` varchar(255) NOT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `alt_text` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galeria`
--

LOCK TABLES `galeria` WRITE;
/*!40000 ALTER TABLE `galeria` DISABLE KEYS */;
INSERT INTO `galeria` VALUES (1,'img.santalucia.png','Santa Lúcia - Paisagem Rural',1,'2026-03-12 12:11:33',''),(2,'img.fazendasouza.png','Fazenda Souza - Cultura Italiana',2,'2026-03-12 12:11:33',''),(3,'img.galopolis.png','Galópolis - Patrimônio Histórico',3,'2026-03-12 12:11:33',''),(4,'img.terceiralegua.png','Terceira Légua - Natureza',4,'2026-03-12 12:11:33',''),(5,'img.turismoreligioso.png','Turismo Religioso',5,'2026-03-12 12:11:33','');
/*!40000 ALTER TABLE `galeria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opcoes_site`
--

DROP TABLE IF EXISTS `opcoes_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opcoes_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opcoes_site`
--

LOCK TABLES `opcoes_site` WRITE;
/*!40000 ALTER TABLE `opcoes_site` DISABLE KEYS */;
INSERT INTO `opcoes_site` VALUES (1,'site_titulo','Caxias Tem Turismo'),(2,'site_descricao','Seu guia completo para explorar o melhor do turismo rural, gastronômico e religioso na Serra Gaúcha.'),(3,'contato_email','contato@caxiastemturismo.com.br'),(4,'contato_telefone','(54) 98122-2284'),(5,'contato_whatsapp','5554981222284'),(6,'contato_endereco','Caxias do Sul, RS'),(7,'social_facebook','https://www.facebook.com/caxiastemturismo'),(8,'social_instagram','https://www.instagram.com/caxiastemturismo'),(9,'social_youtube',''),(10,'site_keywords','turismo caxias do sul, serra gaúcha, roteiros turísticos, gastronomia italiana, vinícolas, natureza, ecoturismo'),(11,'site_analytics_id',''),(12,'home_sobre_titulo','Uma imersão na cultura local'),(13,'home_sobre_texto','Longe da agitação da cidade, o interior de Caxias do Sul preserva as tradições dos imigrantes italianos, com paisagens deslumbrantes, vinícolas familiares e uma culinária inesquecível. Explore os caminhos e descubra um novo lado da Serra Gaúcha.'),(14,'home_stats_visitantes','5000'),(15,'home_stats_roteiros','50');
/*!40000 ALTER TABLE `opcoes_site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prestadores`
--

DROP TABLE IF EXISTS `prestadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prestadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('agencia','agente','transportador') NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `link_mapa` varchar(255) DEFAULT NULL,
  `contato` varchar(100) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `url_contato` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prestadores`
--

LOCK TABLES `prestadores` WRITE;
/*!40000 ALTER TABLE `prestadores` DISABLE KEYS */;
INSERT INTO `prestadores` VALUES (1,'agencia','Voo Livre Turismo','Agência especializada em pacotes nacionais e internacionais com atendimento personalizado.','img.santalucia.png','https://www.google.com/maps/search/agencia+turismo+caxias','(54) 3221-0000','2026-03-09 16:43:58',''),(2,'agencia','Serra Gaúcha Operadora','Receptivo especializado em roteiros de vinho, queijo e cultura italiana na região.','img.fazendasouza.png','https://www.google.com/maps/search/agencia+turismo+caxias','(54) 3222-1111','2026-03-09 16:43:58',''),(3,'agencia','Caxias Travel','Sua parceira ideal para viagens de negócios e lazer, com os melhores preços de passagens.','img.terceiralegua.png','https://www.google.com/maps/search/agencia+turismo+caxias','(54) 3223-2222','2026-03-09 16:43:58','54999324050'),(4,'agente','Ana Paula - Consultora','Especialista em roteiros religiosos e históricos por Caxias do Sul e arredores.','img.turismoreligioso.png','','(54) 99123-4567','2026-03-09 16:43:58',''),(5,'agente','Ricardo Guia Local','Conheça os segredos das colônias e a história da imigração com quem vive a região.','img.galopolis.png','','(54) 98111-2222','2026-03-09 16:43:58',''),(6,'agente','Marina Viagens','Consultoria personalizada para famílias que buscam experiências autênticas na Serra.','img1.png','','(54) 99988-7766','2026-03-09 16:43:58',''),(7,'transportador','TransSerra Vans','Transporte de passageiros em vans executivas e micro-ônibus com total segurança.','img.fazendasouza.png','','(54) 3214-5566','2026-03-09 16:43:58',''),(8,'transportador','Caminhos do Vinho','Especializada em transfers para vinícolas, casamentos e eventos corporativos.','img.galopolis.png','','(54) 3215-6677','2026-03-09 16:43:58','54999324050'),(9,'transportador','Luxo Tour Executivo','Carros de luxo com motorista bilíngue para maior conforto e sofisticação.','img.santalucia.png','','(54) 3216-7788','2026-03-09 16:43:58','');
/*!40000 ALTER TABLE `prestadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador','admin@caxiasturismo.com.br','$2y$10$Rg3V1OuPxMx.WKsA9Qabse3IJ4xTGVC4.mmHZL4kLa4MDDURtgYFy','2026-03-09 13:46:47');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-09 18:02:10
