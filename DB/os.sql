-- --------------------------------------------------------
-- 主機:                           127.0.0.1
-- 伺服器版本:                        8.0.13 - MySQL Community Server - GPL
-- 伺服器操作系統:                      Win64
-- HeidiSQL 版本:                  10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 os 的資料庫結構
DROP DATABASE IF EXISTS `os`;
CREATE DATABASE IF NOT EXISTS `os` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `os`;

-- 傾印  表格 os.cadastro_pedido 結構
DROP TABLE IF EXISTS `cadastro_pedido`;
CREATE TABLE IF NOT EXISTS `cadastro_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cd_cliente` int(11) NOT NULL,
  `cd_tipo` int(11) NOT NULL,
  `cd_status` int(11) NOT NULL,
  `cd_funcionario` int(11) NOT NULL,
  `marca` varchar(60) NOT NULL,
  `modelo` varchar(60) NOT NULL,
  `defeito` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_pedido` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cadastro_pedido_tipo_idx` (`cd_tipo`),
  KEY `fk_cadastro_pedido_status1_idx` (`cd_status`),
  KEY `fk_cadastro_pedido_tb_login1_idx` (`cd_funcionario`),
  KEY `fk_tb_cadastro_pedido_tb_cliente1_idx` (`cd_cliente`),
  CONSTRAINT `fk_cadastro_pedido_status1` FOREIGN KEY (`cd_status`) REFERENCES `status` (`id`),
  CONSTRAINT `fk_cadastro_pedido_tb_login1` FOREIGN KEY (`cd_funcionario`) REFERENCES `funcionario` (`id`),
  CONSTRAINT `fk_cadastro_pedido_tipo` FOREIGN KEY (`cd_tipo`) REFERENCES `tipo` (`id`),
  CONSTRAINT `fk_tb_cadastro_pedido_tb_cliente1` FOREIGN KEY (`cd_cliente`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.cadastro_pedido 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `cadastro_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `cadastro_pedido` ENABLE KEYS */;

-- 傾印  表格 os.ci_sessions 結構
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.ci_sessions 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;

-- 傾印  表格 os.cliente 結構
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefone` varchar(40) NOT NULL,
  `cpf` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '0 = sem logar, 1 = admin, 2 = cliente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `telefone` (`telefone`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.cliente 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;

-- 傾印  表格 os.funcionario 結構
DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE IF NOT EXISTS `funcionario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefone` varchar(40) NOT NULL,
  `cpf` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 = sem logar, 1 = admin, 2 = cliente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `telefone` (`telefone`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.funcionario 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `funcionario` DISABLE KEYS */;
INSERT INTO `funcionario` (`id`, `nome`, `email`, `password`, `telefone`, `cpf`, `status`) VALUES
	(1, 'admin', 'admin@senac.com', '23b81d13f8555c6357d59ec97f948cf37993d02b', '123456789', '343.839.300-00', 1);
/*!40000 ALTER TABLE `funcionario` ENABLE KEYS */;

-- 傾印  表格 os.item_pedido 結構
DROP TABLE IF EXISTS `item_pedido`;
CREATE TABLE IF NOT EXISTS `item_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cd_cadastro_pedido` int(11) NOT NULL,
  `cd_servicos` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cadastro_pedido_has_servicos_servicos1_idx` (`cd_servicos`),
  KEY `fk_cadastro_pedido_has_servicos_cadastro_pedido1_idx` (`cd_cadastro_pedido`),
  CONSTRAINT `fk_cadastro_pedido_has_servicos_cadastro_pedido1` FOREIGN KEY (`cd_cadastro_pedido`) REFERENCES `cadastro_pedido` (`id`),
  CONSTRAINT `fk_cadastro_pedido_has_servicos_servicos1` FOREIGN KEY (`cd_servicos`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.item_pedido 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `item_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_pedido` ENABLE KEYS */;

-- 傾印  表格 os.logs 結構
DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3747 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.logs 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;

-- 傾印  表格 os.servicos 結構
DROP TABLE IF EXISTS `servicos`;
CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servico` varchar(255) NOT NULL,
  `precos` float(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.servicos 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `servicos` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicos` ENABLE KEYS */;

-- 傾印  表格 os.status 結構
DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.status 的資料：~5 rows (大約)
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` (`id`, `status`) VALUES
	(1, 'Aguardando análise'),
	(2, 'Aguardando peça'),
	(3, 'Em análise'),
	(4, 'Pronto a entrega'),
	(5, 'Sem solução');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;

-- 傾印  表格 os.tipo 結構
DROP TABLE IF EXISTS `tipo`;
CREATE TABLE IF NOT EXISTS `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.tipo 的資料：~2 rows (大約)
/*!40000 ALTER TABLE `tipo` DISABLE KEYS */;
INSERT INTO `tipo` (`id`, `type`) VALUES
	(1, 'Celular'),
	(2, 'Notebook');
/*!40000 ALTER TABLE `tipo` ENABLE KEYS */;

-- 傾印  表格 os.token 結構
DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cd_funcionario` int(11) NOT NULL,
  `apikey` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_token_tb_usuario1_idx` (`cd_funcionario`),
  CONSTRAINT `fk_token_tb_usuario1` FOREIGN KEY (`cd_funcionario`) REFERENCES `funcionario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- 正在傾印表格  os.token 的資料：~0 rows (大約)
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` (`id`, `cd_funcionario`, `apikey`) VALUES
	(1, 1, 'd125a874318684c7d491a20acbd3b879');
/*!40000 ALTER TABLE `token` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
