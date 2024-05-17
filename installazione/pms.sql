SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `abilita` (
  `ID_Abilita` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Descrizione` varchar(1500) COLLATE utf8_unicode_ci NOT NULL,
  `Costo` float NOT NULL DEFAULT '0',
  `Note` varchar(1500) COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID_Abilita`),
  UNIQUE KEY `Nome` (`Nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `attivazioni` (
  `ID_Da_Attivare` int(11) NOT NULL,
  `Email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Codice` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID_Da_Attivare`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `competenze` (
  `Personaggio` int(11) NOT NULL,
  `Abilita` int(11) NOT NULL,
  PRIMARY KEY (`Personaggio`,`Abilita`),
  KEY `Personaggio` (`Personaggio`),
  KEY `Abilita` (`Abilita`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `messaggio` (
  `ID` tinyint(4) NOT NULL,
  `messaggio` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `messaggio` (`ID`, `messaggio`) VALUES
(0, 'Messaggio per i giocatori qui!');

CREATE TABLE IF NOT EXISTS `personaggio` (
  `ID_Personaggio` int(11) NOT NULL AUTO_INCREMENT,
  `Proprietario` int(11) NOT NULL,
  `Nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Descrizione` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Regno` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'Nessuno',
  `Razza` varchar(50) COLLATE utf8_unicode_ci DEFAULT '?',
  `Background` varchar(10000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Avatar` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Punti` int(11) NOT NULL DEFAULT '0',
  `Nota` varchar(800) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID_Personaggio`),
  UNIQUE KEY `Nome` (`Nome`),
  KEY `Proprietario` (`Proprietario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessioni` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `hash` varchar(256) CHARACTER SET utf8 NOT NULL,
  `addr` varchar(16) NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `utente` (
  `ID_Utente` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pwd` varchar(65) COLLATE utf8_unicode_ci NOT NULL,
  `Username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Master` tinyint(1) NOT NULL DEFAULT '0',
  `Attivo` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID_Utente`),
  UNIQUE KEY `Email` (`Username`),
  UNIQUE KEY `Email_2` (`Email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `attivazioni`
  ADD CONSTRAINT `attivazioni_ibfk_1` FOREIGN KEY (`ID_Da_Attivare`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `competenze`
  ADD CONSTRAINT `competenze_ibfk_1` FOREIGN KEY (`Personaggio`) REFERENCES `personaggio` (`ID_Personaggio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `competenze_ibfk_2` FOREIGN KEY (`Abilita`) REFERENCES `abilita` (`ID_Abilita`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `personaggio`
  ADD CONSTRAINT `personaggio_ibfk_1` FOREIGN KEY (`Proprietario`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sessioni`
  ADD CONSTRAINT `sessioni_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `utente` (`ID_Utente`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
