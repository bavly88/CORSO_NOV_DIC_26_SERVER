-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versione server:              11.8.10-MariaDB - MariaDB Server
-- S.O. server:                  Win64
-- HeidiSQL Versione:            12.21.0.7344
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dump della struttura del database palestra
CREATE DATABASE IF NOT EXISTS `palestra` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `palestra`;

-- Dump della struttura di tabella palestra.corsi
CREATE TABLE IF NOT EXISTS `corsi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `id_istruttore` int(10) unsigned DEFAULT NULL,
  `id_sala` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_corso_istruttore` (`id_istruttore`),
  KEY `fk_corso_sala` (`id_sala`),
  CONSTRAINT `fk_corso_istruttore` FOREIGN KEY (`id_istruttore`) REFERENCES `istruttori` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `fk_corso_sala` FOREIGN KEY (`id_sala`) REFERENCES `sale` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella palestra.iscritti
CREATE TABLE IF NOT EXISTS `iscritti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `tessera` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella palestra.iscrizioni
CREATE TABLE IF NOT EXISTS `iscrizioni` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_corso` int(10) unsigned NOT NULL,
  `id_iscritto` int(10) unsigned NOT NULL,
  `pagamento` tinyint(1) NOT NULL,
  `data_iscrizione` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK-_iscrizione_iscritto` (`id_iscritto`),
  CONSTRAINT `FK-_iscrizione_iscritto` FOREIGN KEY (`id_iscritto`) REFERENCES `iscritti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella palestra.istruttori
CREATE TABLE IF NOT EXISTS `istruttori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella palestra.sale
CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- L’esportazione dei dati non era selezionata.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
