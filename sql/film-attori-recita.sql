-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versione server:              10.3.16-MariaDB - mariadb.org binary distribution
-- S.O. server:                  Win64
-- HeidiSQL Versione:            11.1.0.6116
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dump della struttura di tabella film_attori_recita.attori
DROP TABLE IF EXISTS `attori`;
CREATE TABLE IF NOT EXISTS `attori` (
  `idA` int(11) NOT NULL AUTO_INCREMENT,
  `cognome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dataNas` date NOT NULL,
  `nazione` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`idA`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dump dei dati della tabella film_attori_recita.attori: ~10 rows (circa)
/*!40000 ALTER TABLE `attori` DISABLE KEYS */;
INSERT INTO `attori` (`idA`, `cognome`, `nome`, `dataNas`, `nazione`) VALUES
	(1, 'Depp', 'Johnny', '1974-05-25', 'USA'),
	(2, 'Di Caprio', 'Leonardo', '1974-11-11', 'USA'),
	(3, 'Fox', 'Jamie', '1987-06-04', 'USA'),
	(4, 'Jackson', 'Samuel', '1985-03-21', 'USA'),
	(5, 'Jolie', 'Angelina', '1975-06-04', 'USA'),
	(6, 'Zalone', 'Checco', '1977-06-03', 'ITA'),
	(7, 'Proietti', 'Luigi', '1940-11-02', 'ITA'),
	(8, 'Connery', 'Sean', '1930-08-25', 'GBR'),
	(9, 'Dench', 'Judi', '1934-12-09', 'GBR'),
	(10, 'Cucinotta', 'Maria Grazia', '1968-07-27', 'ITA'),
	(11, 'Pippi', 'Pippo', '1930-05-01', 'ITA');
/*!40000 ALTER TABLE `attori` ENABLE KEYS */;

-- Dump della struttura di procedura film_attori_recita.attori_compleanno_compiuto
DROP PROCEDURE IF EXISTS `attori_compleanno_compiuto`;
DELIMITER //
CREATE PROCEDURE `attori_compleanno_compiuto`()
    DETERMINISTIC
    COMMENT 'Attori che hanno già compiuto gli anni nell''anno corrente prima della data odierna'
BEGIN
SELECT cognome, nome
FROM attori
WHERE MONTH(attori.dataNas)<MONTH(CURDATE()) 
OR MONTH(CURDATE())=MONTH(attori.dataNas) 
AND DAY(CURDATE())=DAY(attori.datanas);
END//
DELIMITER ;

-- Dump della struttura di tabella film_attori_recita.film
DROP TABLE IF EXISTS `film`;
CREATE TABLE IF NOT EXISTS `film` (
  `idF` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `anno` int(11) DEFAULT NULL,
  `durata` int(11) DEFAULT NULL COMMENT 'durata in minuti',
  `incasso` int(11) DEFAULT NULL,
  `idr` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idF`),
  KEY `FK_film_registi` (`idr`),
  CONSTRAINT `FK_film_registi` FOREIGN KEY (`idr`) REFERENCES `registi` (`idr`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dump dei dati della tabella film_attori_recita.film: ~17 rows (circa)
/*!40000 ALTER TABLE `film` DISABLE KEYS */;
INSERT INTO `film` (`idF`, `titolo`, `anno`, `durata`, `incasso`, `idr`) VALUES
	(1, 'Jango', 2013, 165, NULL, 'tarque'),
	(2, 'Titanic', 1997, 195, NULL, 'camjam'),
	(3, 'Santiago Italia', 2018, NULL, NULL, NULL),
	(4, 'Edward mani di forbice', 1991, 105, NULL, 'burtim'),
	(5, 'Arancia meccanica', 1971, 197, NULL, 'kubsta'),
	(6, 'Lanterne Rosse', 1991, 125, NULL, NULL),
	(7, 'Tutto su mia sorella', 1999, 108, NULL, 'almped'),
	(8, 'Si salvi chi può', 1980, NULL, NULL, NULL),
	(9, '007- Skyfall', 2012, 143, NULL, NULL),
	(10, 'Il postino', 1994, 108, NULL, NULL),
	(11, '007-Il mondo non basta', 1999, 128, NULL, NULL),
	(12, 'Il pianeta delle scimmie', 2017, 130, NULL, 'burtim'),
	(17, 'L\'America Sconosciuta', 1967, 200, NULL, 'camjam'),
	(18, 'Il cavaliere "innamorato"', 2000, 150, NULL, 'camjam'),
	(19, 'I giorni felici', 2021, 180, NULL, 'zhayim'),
	(21, '2020 i sopravvissuti', 2021, 200, NULL, NULL),
	(22, '2021 la vendetta', 2021, 100, NULL, 'camjam');
/*!40000 ALTER TABLE `film` ENABLE KEYS */;

-- Dump della struttura di procedura film_attori_recita.film_tra_anno_e_anno
DROP PROCEDURE IF EXISTS `film_tra_anno_e_anno`;
DELIMITER //
CREATE PROCEDURE `film_tra_anno_e_anno`(
	IN `anno_inizio` INT,
	IN `anno_fine` INT
)
BEGIN
SELECT f.titolo, f.anno, f.durata, r.cognome, r.nome
FROM film AS f
LEFT JOIN registi AS r ON f.idr=r.idr
WHERE anno BETWEEN anno_inizio AND anno_fine
ORDER BY anno ASC; /* ci vuole il punto e virgola */
END//
DELIMITER ;

-- Dump della struttura di procedura film_attori_recita.incremento_cachet
DROP PROCEDURE IF EXISTS `incremento_cachet`;
DELIMITER //
CREATE PROCEDURE `incremento_cachet`(
	IN `val_incr` FLOAT,
	IN `titolo_film` VARCHAR(50)
)
BEGIN
UPDATE recita
SET cachet = cachet-cachet*val_incr
WHERE recita.idF = (
	SELECT film.idF FROM film WHERE titolo=titolo_film COLLATE utf8_unicode_ci
);
END//
DELIMITER ;

-- Dump della struttura di tabella film_attori_recita.recita
DROP TABLE IF EXISTS `recita`;
CREATE TABLE IF NOT EXISTS `recita` (
  `idA` int(11) NOT NULL,
  `idF` int(11) NOT NULL,
  `ruolo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'personaggio interpretato',
  `cachet` int(11) DEFAULT NULL COMMENT '''stipendio'' percepito nel film',
  PRIMARY KEY (`idA`,`idF`),
  KEY `film_recita` (`idF`),
  CONSTRAINT `attori_recita` FOREIGN KEY (`idA`) REFERENCES `attori` (`idA`) ON UPDATE CASCADE,
  CONSTRAINT `film_recita` FOREIGN KEY (`idF`) REFERENCES `film` (`idF`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dump dei dati della tabella film_attori_recita.recita: ~12 rows (circa)
/*!40000 ALTER TABLE `recita` DISABLE KEYS */;
INSERT INTO `recita` (`idA`, `idF`, `ruolo`, `cachet`) VALUES
	(1, 4, 'Edward', 294),
	(1, 8, NULL, 302),
	(2, 1, 'Cattivo', 694),
	(2, 2, 'lo sfigato innamorato', 411),
	(3, 1, 'Django', 694),
	(3, 8, NULL, 535),
	(4, 1, 'servo mica tanto buono', 694),
	(7, 10, 'non è vero', 694),
	(8, 11, 'james bond', 222),
	(9, 9, 'mi8', 148),
	(10, 10, 'donna', 586),
	(10, 11, 'pochi minuti', 554);
/*!40000 ALTER TABLE `recita` ENABLE KEYS */;

-- Dump della struttura di tabella film_attori_recita.registi
DROP TABLE IF EXISTS `registi`;
CREATE TABLE IF NOT EXISTS `registi` (
  `idr` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `cognome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nazione` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_n` date DEFAULT NULL,
  PRIMARY KEY (`idr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dump dei dati della tabella film_attori_recita.registi: ~12 rows (circa)
/*!40000 ALTER TABLE `registi` DISABLE KEYS */;
INSERT INTO `registi` (`idr`, `cognome`, `nome`, `nazione`, `data_n`) VALUES
	('almped', 'Almodovar', 'Pedro', 'ESP', '1949-09-25'),
	('besluc', 'Besson', 'Luc', 'FRA', '1959-03-18'),
	('burtim', 'Burton', 'Tim', 'USA', '1958-08-25'),
	('camjam', 'Cameron', 'James', 'CAN', NULL),
	('FleFed', 'Flecchia', 'Federico', 'ITA', '1991-02-20'),
	('godjea', 'Godard', 'Jean.Luc', 'FRA', NULL),
	('kubsta', 'Kubrik', 'Stanley', 'USA', '1928-07-26'),
	('mornan', 'Moretti', 'Nanni', 'ITA', '1953-08-19'),
	('NolChr', 'Nolan', 'Christopher', 'ENG', '1970-07-30'),
	('sorpao', 'Sorrentino', 'Paolo', 'ITA', '1970-05-31'),
	('tarque', 'Tarantino', 'Quentin', 'USA', '1963-03-27'),
	('zhayim', 'Zhang', 'Yimou', 'CHI', '1951-11-14');
/*!40000 ALTER TABLE `registi` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
