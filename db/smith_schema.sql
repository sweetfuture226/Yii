-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: smith
-- ------------------------------------------------------
-- Server version	5.6.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acoes`
--

DROP TABLE IF EXISTS `acoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acao` varchar(45) DEFAULT NULL,
  `data_hora_host` timestamp NULL DEFAULT NULL,
  `data_hora_servidor` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(45) DEFAULT NULL,
  `versao_windows` varchar(64) DEFAULT NULL,
  `nome_host` varchar(45) DEFAULT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Utilizada para o instalador antigo, remover quando possível.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `atividade_externa`
--

DROP TABLE IF EXISTS `atividade_externa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `atividade_externa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duracao` time DEFAULT NULL,
  `data` date DEFAULT NULL,
  `data_hora_servidor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title_completo` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `hora_host` time NOT NULL,
  `nome_host` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `host_domain` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `atividade_extra` int(11) NOT NULL,
  `fk_log` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=1458 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calendario_feriados`
--

DROP TABLE IF EXISTS `calendario_feriados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendario_feriados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `changelog`
--

DROP TABLE IF EXISTS `changelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `changelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `caminho` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='lista de novas funcionalidades';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador`
--

DROP TABLE IF EXISTS `colaborador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_equipe` int(11) DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salario` float DEFAULT '1000',
  `horas_semana` time DEFAULT NULL,
  `ad` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `sobrenome` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `valor_hora` float DEFAULT NULL,
  `data_inativacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pro_pessoa_pro_equipe` (`fk_equipe`),
  KEY `ad` (`ad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_has_falta`
--

DROP TABLE IF EXISTS `colaborador_has_falta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_has_falta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_has_ferias`
--

DROP TABLE IF EXISTS `colaborador_has_ferias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_has_ferias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL COMMENT 'Tabela que armazena as férias de cada colaborador.',
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_has_metrica`
--

DROP TABLE IF EXISTS `colaborador_has_metrica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_has_metrica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_metrica` int(11) NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_has_salario`
--

DROP TABLE IF EXISTS `colaborador_has_salario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_has_salario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` float NOT NULL,
  `data_inicio` date NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__pro_pessoa` (`fk_colaborador`),
  KEY `FK__empresa` (`fk_empresa`),
  CONSTRAINT `FK__empresa` FOREIGN KEY (`fk_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK__pro_pessoa` FOREIGN KEY (`fk_colaborador`) REFERENCES `colaborador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='historico dos salarios dos colaboradores';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_sem_metrica`
--

DROP TABLE IF EXISTS `colaborador_sem_metrica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_sem_metrica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_colaborador` int(11) NOT NULL,
  `fk_metrica` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Colaboradores que não estão associados a métrica, mas desenvolveram atividades relacionadas.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `colaborador_sem_produtividade`
--

DROP TABLE IF EXISTS `colaborador_sem_produtividade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colaborador_sem_produtividade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` date DEFAULT NULL,
  `fk_empresa` int(11) NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log de colaboradores que não tiveram captura em determinada data.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contrato`
--

DROP TABLE IF EXISTS `contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  `finalizada` tinyint(1) NOT NULL DEFAULT '0',
  `coordenador` int(11) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `data_finalizacao` datetime DEFAULT NULL,
  `tempo_previsto` time DEFAULT NULL,
  `receber_email` tinyint(4) DEFAULT '0',
  `moeda` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`nome`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disciplina`
--

DROP TABLE IF EXISTS `disciplina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `previsto` time NOT NULL DEFAULT '00:00:00',
  `finalizado` int(11) NOT NULL DEFAULT '0',
  `fk_contrato` int(11) DEFAULT NULL,
  `fk_disciplina` int(11) DEFAULT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `disciplina_id` (`fk_disciplina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documento_sem_contrato`
--

DROP TABLE IF EXISTS `documento_sem_contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento_sem_contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `programa` varchar(255) NOT NULL,
  `documento` varchar(255) NOT NULL,
  `fk_colaborador` varchar(255) NOT NULL,
  `duracao` float NOT NULL,
  `data` date NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Lista de documentos que estão sem referência entre os contratos existentes.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public/avatar/no_logo.png',
  `serial` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wizard` int(11) DEFAULT '0',
  `passo_wizard` int(11) DEFAULT '1',
  `colaboradores_previstos` int(11) NOT NULL DEFAULT '0',
  `dias_colaboradores_a_mais` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresa_has_parametro`
--

DROP TABLE IF EXISTS `empresa_has_parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa_has_parametro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `almoco_inicio` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `almoco_fim` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tempo_ocio` int(11) DEFAULT '300000',
  `porcentagem` varchar(5) DEFAULT NULL,
  `fk_empresa` int(11) NOT NULL,
  `andamento_obra` varchar(255) NOT NULL,
  `tipo_empresa` varchar(255) DEFAULT NULL,
  `tarifa_energia` float DEFAULT NULL,
  `horario_entrada` varchar(20) NOT NULL,
  `horario_saida` varchar(20) NOT NULL,
  `moeda` varchar(4) NOT NULL DEFAULT 'BRL',
  PRIMARY KEY (`id`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Parâmetros de configuração do ambiente da empresa.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipe`
--

DROP TABLE IF EXISTS `equipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `meta` float NOT NULL DEFAULT '60',
  PRIMARY KEY (`id`),
  KEY `fk_empresa` (`fk_empresa`),
  KEY `fk_empresa_2` (`fk_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `executavel`
--

DROP TABLE IF EXISTS `executavel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `executavel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `versao` varchar(5) NOT NULL,
  `data` datetime NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela que contêm informações sobre as versões do instalador sendo utilizada.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grf_colaborador_consolidado`
--

DROP TABLE IF EXISTS `grf_colaborador_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grf_colaborador_consolidado` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `data` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_saida` time NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_colaborador_idx` (`fk_colaborador`),
  KEY `fk_fk_empresax` (`fk_empresa`),
  CONSTRAINT `fk_empresa` FOREIGN KEY (`fk_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grf_hora_extra_consolidado`
--

DROP TABLE IF EXISTS `grf_hora_extra_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grf_hora_extra_consolidado` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `duracao` float NOT NULL,
  `produtividade` float NOT NULL,
  `data` date NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fim` datetime NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fk_empresax` (`fk_empresa`),
  CONSTRAINT `fk_empresa_hec` FOREIGN KEY (`fk_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grf_produtividade_consolidado`
--

DROP TABLE IF EXISTS `grf_produtividade_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grf_produtividade_consolidado` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `equipe` varchar(255) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `duracao` float NOT NULL,
  `hora_total` float NOT NULL,
  `data` date NOT NULL,
  `fk_colaborador` bigint(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fk_empresax` (`fk_empresa`),
  CONSTRAINT `fk_fk_empresax` FOREIGN KEY (`fk_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grf_programa_consolidado`
--

DROP TABLE IF EXISTS `grf_programa_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grf_programa_consolidado` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(255) NOT NULL,
  `programa` varchar(255) NOT NULL,
  `duracao` float NOT NULL,
  `data` date NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grf_programa_consolidado_1_idx` (`fk_empresa`),
  KEY `fk_empresa` (`fk_empresa`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grf_projeto_consolidado`
--

DROP TABLE IF EXISTS `grf_projeto_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grf_projeto_consolidado` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `documento` varchar(255) NOT NULL,
  `duracao` float NOT NULL,
  `data` date NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_obra` int(11) NOT NULL,
  `associado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lista_negra_programa`
--

DROP TABLE IF EXISTS `lista_negra_programa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_negra_programa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `programa` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `porcentagem` float NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `tempo_absoluto` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `programa` (`programa`),
  KEY `fk_blacklist_1_idx` (`fk_empresa`),
  CONSTRAINT `fk_blacklist_1` FOREIGN KEY (`fk_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lista_negra_site`
--

DROP TABLE IF EXISTS `lista_negra_site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_negra_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `programa` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `porcentagem` float NOT NULL,
  `tempo_absoluto` float NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_acesso`
--

DROP TABLE IF EXISTS `log_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_acesso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(255) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `data_horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tempo_resposta` float DEFAULT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabela com registros de acesso dos clientes aos relatorios do sistema.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_atividade`
--

DROP TABLE IF EXISTS `log_atividade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_atividade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duracao` time DEFAULT NULL,
  `data` date DEFAULT NULL,
  `title_completo` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hora_host` time DEFAULT NULL,
  `nome_host` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_domain` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_hora_servidor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `atividade_extra` int(11) DEFAULT '0',
  `fk_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `data` (`data`),
  KEY `programa` (`programa`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=105342682 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_atividade_consolidado`
--

DROP TABLE IF EXISTS `log_atividade_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_atividade_consolidado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duracao` time DEFAULT NULL,
  `data` date DEFAULT NULL,
  `title_completo` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_host` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host_domain` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `num_logs` int(11) DEFAULT '1',
  `fk_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `duracao` (`duracao`),
  KEY `data` (`data`),
  KEY `programa` (`programa`),
  KEY `fk_empresa` (`fk_empresa`),
  KEY `data_2` (`data`)
) ENGINE=InnoDB AUTO_INCREMENT=6316219 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_atividade_historico`
--

DROP TABLE IF EXISTS `log_atividade_historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_atividade_historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `programa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `duracao` time DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora_host` datetime DEFAULT NULL,
  `serial_empresa` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario` (`usuario`),
  KEY `data` (`data`),
  KEY `programa` (`programa`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=105288643 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='registros de log que foram removidos da tabela log_atividade devido a uma inserção de atividade externa.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_programa_consolidado`
--

DROP TABLE IF EXISTS `log_programa_consolidado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_programa_consolidado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) DEFAULT NULL,
  `programa` varchar(255) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `duracao` time DEFAULT NULL,
  `data` date DEFAULT NULL,
  `title_completo` varchar(1024) DEFAULT NULL,
  `nome_host` varchar(45) DEFAULT NULL,
  `host_domain` varchar(64) DEFAULT NULL,
  `serial_empresa` varchar(36) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1624331 DEFAULT CHARSET=latin1 COMMENT='logs agrupados por programa; desativar quando não for mais necessário. utilizar grf_programa_consolidado';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `metrica`
--

DROP TABLE IF EXISTS `metrica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metrica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `atuacao` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `programa` varchar(255) NOT NULL,
  `criterio` varchar(255) NOT NULL,
  `sufixo` varchar(255) DEFAULT NULL,
  `meta` int(11) DEFAULT NULL,
  `meta_tempo` int(11) DEFAULT NULL,
  `min_t` int(11) DEFAULT NULL,
  `max_t` int(11) DEFAULT NULL,
  `fk_empresa` int(11) NOT NULL,
  `serial_empresa` varchar(36) NOT NULL,
  `favorito` int(11) NOT NULL DEFAULT '0',
  `min_e` int(11) DEFAULT NULL,
  `max_e` int(11) DEFAULT NULL,
  `meta_entrada` int(11) DEFAULT NULL,
  `alerta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `metrica_consolidada`
--

DROP TABLE IF EXISTS `metrica_consolidada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metrica_consolidada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `total` time NOT NULL,
  `media` time NOT NULL,
  `entradas` int(11) NOT NULL,
  `fk_metrica` int(11) NOT NULL,
  `fk_colaborador` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `metrica_has_limite`
--

DROP TABLE IF EXISTS `metrica_has_limite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metrica_has_limite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `min_t` int(11) NOT NULL,
  `max_t` int(11) NOT NULL,
  `fk_metrica` int(11) NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `data` date NOT NULL,
  `min_e` int(11) DEFAULT NULL,
  `max_e` int(11) DEFAULT NULL,
  `meta_entrada` bit(1) DEFAULT NULL,
  `meta_tempo` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Histórico de limites máximos e minimos de entradas ou tempo';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notificacao`
--

DROP TABLE IF EXISTS `notificacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notificacao` varchar(255) NOT NULL,
  `fk_usuario` bigint(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `action` varchar(225) DEFAULT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  `data_notificacao` datetime DEFAULT NULL,
  `status` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notificacao_1_idx` (`fk_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programa_permitido`
--

DROP TABLE IF EXISTS `programa_permitido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programa_permitido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serial_empresa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fk_empresa` int(11) NOT NULL,
  `fk_equipe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `programa_permitido_contrato`
--

DROP TABLE IF EXISTS `programa_permitido_contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `programa_permitido_contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Programas utilizados para a consolidação de registros de documentos sem contrato';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `site_permitido`
--

DROP TABLE IF EXISTS `site_permitido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_permitido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fk_empresa` int(11) DEFAULT NULL,
  `fk_equipe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `traducao`
--

DROP TABLE IF EXISTS `traducao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traducao` (
  `id` int(11) NOT NULL,
  `language` varchar(16) NOT NULL,
  `translation` text,
  PRIMARY KEY (`id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `traducao_literal`
--

DROP TABLE IF EXISTS `traducao_literal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traducao_literal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(32) NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Literais em português a serem traduzidos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_access`
--

DROP TABLE IF EXISTS `usergroups_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_access` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element` int(3) NOT NULL,
  `element_id` bigint(20) NOT NULL,
  `module` varchar(140) NOT NULL,
  `controller` varchar(140) NOT NULL,
  `permission` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_configuration`
--

DROP TABLE IF EXISTS `usergroups_configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_configuration` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rule` varchar(40) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  `options` text,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_cron`
--

DROP TABLE IF EXISTS `usergroups_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_cron` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `lapse` int(6) DEFAULT NULL,
  `last_occurrence` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_group`
--

DROP TABLE IF EXISTS `usergroups_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(120) NOT NULL,
  `level` int(6) DEFAULT NULL,
  `home` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupname` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_lookup`
--

DROP TABLE IF EXISTS `usergroups_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_lookup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `element` varchar(20) DEFAULT NULL,
  `value` int(5) DEFAULT NULL,
  `text` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usergroups_user`
--

DROP TABLE IF EXISTS `usergroups_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usergroups_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_id` bigint(20) DEFAULT NULL,
  `username` varchar(120) NOT NULL,
  `password` varchar(120) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `home` varchar(120) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `question` text,
  `answer` text,
  `creation_date` datetime DEFAULT NULL,
  `activation_code` varchar(30) DEFAULT NULL,
  `activation_time` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `ban` datetime DEFAULT NULL,
  `ban_reason` text,
  `fk_empresa` int(11) DEFAULT NULL,
  `serial_empresa` varchar(36) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `last_change_passwd` date DEFAULT NULL,
  `fk_equipe` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `group_id_idxfk` (`group_id`),
  KEY `fk_empresa` (`fk_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario_has_contrato`
--

DROP TABLE IF EXISTS `usuario_has_contrato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_has_contrato` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_contrato` int(11) NOT NULL,
  `fk_usergroups_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Relação de coordenador com os contratos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `versao_instalador`
--

DROP TABLE IF EXISTS `versao_instalador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `versao_instalador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `versao` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-15 11:04:23
