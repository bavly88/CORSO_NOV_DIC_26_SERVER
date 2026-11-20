
CREATE DATABASE IF NOT EXISTS `biblioteca` 
USE `biblioteca`;

CREATE TABLE IF NOT EXISTS `autori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `cognome` varchar(60) NOT NULL,
  `nazionalita` varchar(40) DEFAULT NULL,
  `anno_nascita` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_autori_cognome` (`cognome`)
)


INSERT INTO `autori` (`id`, `nome`, `cognome`, `nazionalita`, `anno_nascita`) VALUES
	(1, 'Italo', 'Calvino', 'italiana', 1923),
	(2, 'Elsa', 'Morante', 'italiana', 1912),
	(3, 'Primo', 'Levi', 'italiana', 1919),
	(4, 'Umberto', 'Eco', 'italiana', 1932),
	(5, 'Natalia', 'Ginzburg', 'italiana', 1916),
	(6, 'Leonardo', 'Sciascia', 'italiana', 1921),
	(7, 'Carlo', 'Fruttero', 'italiana', 1926),
	(8, 'Franco', 'Lucentini', 'italiana', 1920),
	(9, 'Virginia', 'Woolf', 'britannica', 1882),
	(10, 'Jorge Luis', 'Borges', 'argentina', 1899);

CREATE TABLE IF NOT EXISTS `editori` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `citta` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) 


INSERT INTO `editori` (`id`, `nome`, `citta`) VALUES
	(1, 'Einaudi', 'Torino'),
	(2, 'Mondadori', 'Milano'),
	(3, 'Bompiani', 'Milano'),
	(4, 'Feltrinelli', 'Milano'),
	(5, 'Adelphi', 'Milano'),
	(6, 'Sellerio', 'Palermo');

CREATE TABLE IF NOT EXISTS `generi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) 


INSERT INTO `generi` (`id`, `nome`) VALUES
	(1, 'Romanzo'),
	(2, 'Racconti'),
	(3, 'Saggio'),
	(4, 'Giallo'),
	(5, 'Poesia');

CREATE TABLE IF NOT EXISTS `libri` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titolo` varchar(200) NOT NULL,
  `isbn` char(13) DEFAULT NULL,
  `anno` smallint(6) DEFAULT NULL,
  `pagine` smallint(5) unsigned DEFAULT NULL,
  `editore_id` int(10) unsigned DEFAULT NULL,
  `genere_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `fk_libri_editore` (`editore_id`),
  KEY `fk_libri_genere` (`genere_id`),
  KEY `idx_libri_titolo` (`titolo`),
  CONSTRAINT `fk_libri_editore` FOREIGN KEY (`editore_id`) REFERENCES `editori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_libri_genere` FOREIGN KEY (`genere_id`) REFERENCES `generi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `chk_pagine` CHECK (`pagine` is null or `pagine` > 0)
) 

INSERT INTO `libri` (`id`, `titolo`, `isbn`, `anno`, `pagine`, `editore_id`, `genere_id`) VALUES
	(1, 'Il barone rampante', '9788804668237', 1957, 270, 2, 1),
	(2, 'Le città invisibili', '9788804668244', 1972, 168, 2, 1),
	(3, 'Marcovaldo', '9788804668213', 1963, 160, 2, 2),
	(4, 'La storia', '9788806219352', 1974, 660, 1, 1),
	(5, 'L\'isola di Arturo', '9788806219383', 1957, 400, 1, 1),
	(6, 'Il sistema periodico', '9788806219420', 1975, 240, 1, 2),
	(7, 'Se questo è un uomo', '9788806219406', 1947, 220, 1, 3),
	(8, 'Il nome della rosa', '9788845292613', 1980, 503, 3, 4),
	(9, 'Il pendolo di Foucault', '9788845292620', 1988, 509, 3, 1),
	(10, 'Lessico famigliare', '9788806219437', 1963, 230, 1, 1),
	(11, 'Il giorno della civetta', '9788845928475', 1961, 130, 5, 4),
	(12, 'A ciascuno il suo', '9788845928482', 1966, 160, 5, 4),
	(13, 'La donna della domenica', '9788804668251', 1972, 450, 2, 4),
	(14, 'Gita al faro', '9788807900099', 1927, 230, 4, 1),
	(15, 'Una stanza tutta per sé', '9788807900105', 1929, 160, 4, 3),
	(16, 'Manuale senza editore', NULL, 2020, NULL, NULL, 3);

-- Dump della struttura di tabella biblioteca.libri_autori
CREATE TABLE IF NOT EXISTS `libri_autori` (
  `libro_id` int(10) unsigned NOT NULL,
  `autore_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`libro_id`,`autore_id`),
  KEY `fk_la_autore` (`autore_id`),
  CONSTRAINT `fk_la_autore` FOREIGN KEY (`autore_id`) REFERENCES `autori` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_la_libro` FOREIGN KEY (`libro_id`) REFERENCES `libri` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dei dati della tabella biblioteca.libri_autori: ~16 rows (circa)
INSERT INTO `libri_autori` (`libro_id`, `autore_id`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 2),
	(5, 2),
	(6, 3),
	(7, 3),
	(8, 4),
	(9, 4),
	(10, 5),
	(11, 6),
	(12, 6),
	(13, 7),
	(13, 8),
	(14, 9),
	(15, 9);

-- Dump della struttura di tabella biblioteca.prestiti
CREATE TABLE IF NOT EXISTS `prestiti` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `libro_id` int(10) unsigned NOT NULL,
  `socio_id` int(10) unsigned NOT NULL,
  `data_prestito` date NOT NULL,
  `data_restituzione` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_prestiti_libro` (`libro_id`),
  KEY `fk_prestiti_socio` (`socio_id`),
  KEY `idx_prestiti_date` (`data_prestito`,`data_restituzione`),
  CONSTRAINT `fk_prestiti_libro` FOREIGN KEY (`libro_id`) REFERENCES `libri` (`id`),
  CONSTRAINT `fk_prestiti_socio` FOREIGN KEY (`socio_id`) REFERENCES `soci` (`id`),
  CONSTRAINT `chk_date` CHECK (`data_restituzione` is null or `data_restituzione` >= `data_prestito`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dei dati della tabella biblioteca.prestiti: ~20 rows (circa)
INSERT INTO `prestiti` (`id`, `libro_id`, `socio_id`, `data_prestito`, `data_restituzione`) VALUES
	(1, 8, 1, '2026-01-10', '2026-01-28'),
	(2, 4, 1, '2026-02-03', '2026-03-01'),
	(3, 1, 2, '2026-02-15', '2026-02-27'),
	(4, 11, 2, '2026-03-04', '2026-03-12'),
	(5, 12, 2, '2026-03-13', '2026-03-20'),
	(6, 8, 3, '2026-03-22', '2026-04-15'),
	(7, 7, 3, '2026-05-02', '2026-05-20'),
	(8, 2, 4, '2026-05-10', '2026-05-18'),
	(9, 13, 4, '2026-06-01', '2026-06-25'),
	(10, 8, 5, '2026-06-05', '2026-06-30'),
	(11, 10, 5, '2026-07-01', '2026-07-21'),
	(12, 14, 6, '2026-07-15', '2026-08-02'),
	(13, 3, 1, '2026-09-01', '2026-09-10'),
	(14, 9, 2, '2026-09-12', NULL),
	(15, 5, 3, '2026-09-20', NULL),
	(16, 11, 6, '2026-10-01', NULL),
	(17, 6, 7, '2026-10-05', '2026-10-15'),
	(18, 8, 7, '2026-10-16', NULL),
	(19, 1, 4, '2026-10-18', NULL),
	(20, 15, 5, '2026-10-20', '2026-10-22');

-- Dump della struttura di tabella biblioteca.soci
CREATE TABLE IF NOT EXISTS `soci` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `cognome` varchar(60) NOT NULL,
  `email` varchar(150) NOT NULL,
  `data_iscrizione` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dump dei dati della tabella biblioteca.soci: ~8 rows (circa)
INSERT INTO `soci` (`id`, `nome`, `cognome`, `email`, `data_iscrizione`) VALUES
	(1, 'Anna', 'Rossi', 'anna.rossi@example.com', '2024-09-10'),
	(2, 'Luca', 'Bianchi', 'luca.bianchi@example.com', '2025-01-15'),
	(3, 'Sara', 'Verdi', 'sara.verdi@example.com', '2025-03-02'),
	(4, 'Marco', 'Neri', 'marco.neri@example.com', '2025-06-20'),
	(5, 'Giulia', 'Russo', 'giulia.russo@example.com', '2025-11-05'),
	(6, 'Paolo', 'Ferrari', 'paolo.ferrari@example.com', '2026-02-11'),
	(7, 'Chiara', 'Esposito', 'chiara.esposito@example.com', '2026-05-30'),
	(8, 'Davide', 'Colombo', 'davide.colombo@example.com', '2026-09-01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
