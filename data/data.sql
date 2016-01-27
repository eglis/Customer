-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Gen 27, 2016 alle 20:07
-- Versione del server: 5.6.28-0ubuntu0.15.10.1
-- Versione PHP: 5.6.11-1ubuntu3.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shineisp2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(200) DEFAULT NULL,
  `birthdistrict` varchar(50) DEFAULT NULL,
  `birthcountry` varchar(50) DEFAULT NULL,
  `birthnationality` varchar(50) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `vat` double DEFAULT NULL,
  `taxfree` tinyint(1) NOT NULL DEFAULT '0',
  `taxpayernumber` varchar(20) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `legalform_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `issubscriber` tinyint(1) NOT NULL DEFAULT '0',
  `note` text,
  `createdat` datetime DEFAULT NULL,
  `updatedat` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `customer`
--

INSERT INTO `customer` (`id`, `user_id`, `uid`, `company`, `firstname`, `lastname`, `birthdate`, `birthplace`, `birthdistrict`, `birthcountry`, `birthnationality`, `gender`, `vat`, `taxfree`, `taxpayernumber`, `type_id`, `status_id`, `legalform_id`, `language_id`, `group_id`, `issubscriber`, `note`, `createdat`, `updatedat`) VALUES
(1, 101, '72ed5a46-a349-4d77-b83f-128eead94399', 'Shine Software', 'John', 'Doe', '1977-11-04', 'Brighton', NULL, NULL, 'English', 'M', 20.5, 0, 'IT746482929288', 2, 12, 2, 2, 2, 0, '', '2014-08-18 09:27:47', '2016-01-27 19:52:26');

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_address`
--

DROP TABLE IF EXISTS `customer_address`;
CREATE TABLE IF NOT EXISTS `customer_address` (
  `id` int(11) NOT NULL,
  `street` text NOT NULL,
  `city` varchar(150) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `customer_address`
--

INSERT INTO `customer_address` (`id`, `street`, `city`, `area`, `code`, `latitude`, `longitude`, `customer_id`, `country_id`, `region_id`, `province_id`) VALUES
(1, 'Via Belpoggio, 6', 'Trieste', NULL, '', 45.6448, 13.7606, 1, 82, 6, 94);

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_company_type`
--

DROP TABLE IF EXISTS `customer_company_type`;
CREATE TABLE IF NOT EXISTS `customer_company_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `legalform_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `customer_company_type`
--

INSERT INTO `customer_company_type` (`id`, `name`, `legalform_id`, `active`) VALUES
(1, 'S.p.A.', 2, 1),
(2, 'S.r.l.', 2, 1),
(3, 's.n.c.', 2, 1),
(4, 's.a.s.', 2, 1),
(5, 's.c.a.r.l.', 2, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_contact`
--

DROP TABLE IF EXISTS `customer_contact`;
CREATE TABLE IF NOT EXISTS `customer_contact` (
  `id` int(11) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `type_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `customer_contact`
--

INSERT INTO `customer_contact` (`id`, `contact`, `type_id`, `customer_id`) VALUES
(1, '+44.3748.3323', 1, 1),
(2, '+44.3748.3323', 1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_contact_type`
--

DROP TABLE IF EXISTS `customer_contact_type`;
CREATE TABLE IF NOT EXISTS `customer_contact_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `customer_contact_type`
--

INSERT INTO `customer_contact_type` (`id`, `name`, `enabled`) VALUES
(1, 'Telephone', 1),
(2, 'Skype', 1),
(3, 'Additional E-mail', 1),
(4, 'Mobile', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_group`
--

DROP TABLE IF EXISTS `customer_group`;
CREATE TABLE IF NOT EXISTS `customer_group` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `customer_group`
--

INSERT INTO `customer_group` (`id`, `name`, `enabled`) VALUES
(1, 'generic', 1),
(2, 'retailer', 1),
(3, 'wholesaler', 1),
(4, 'other', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `customer_legalform`
--

DROP TABLE IF EXISTS `customer_legalform`;
CREATE TABLE IF NOT EXISTS `customer_legalform` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `customer_legalform`
--

INSERT INTO `customer_legalform` (`id`, `name`) VALUES
(1, 'Individual'),
(2, 'Corporation'),
(3, 'Association'),
(4, 'Other');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legalform_id` (`legalform_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indici per le tabelle `customer_address`
--
ALTER TABLE `customer_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id_idx` (`country_id`),
  ADD KEY `addresses_customer_id_idx` (`customer_id`),
  ADD KEY `addresses_region_id_idx` (`region_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indici per le tabelle `customer_company_type`
--
ALTER TABLE `customer_company_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legalform_id` (`legalform_id`);

--
-- Indici per le tabelle `customer_contact`
--
ALTER TABLE `customer_contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indici per le tabelle `customer_contact_type`
--
ALTER TABLE `customer_contact_type`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `customer_group`
--
ALTER TABLE `customer_group`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `customer_legalform`
--
ALTER TABLE `customer_legalform`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `customer_address`
--
ALTER TABLE `customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `customer_company_type`
--
ALTER TABLE `customer_company_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `customer_contact`
--
ALTER TABLE `customer_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `customer_contact_type`
--
ALTER TABLE `customer_contact_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `customer_group`
--
ALTER TABLE `customer_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `customer_legalform`
--
ALTER TABLE `customer_legalform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `customer_address`
--
ALTER TABLE `customer_address`
  ADD CONSTRAINT `customer_address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_address_ibfk_2` FOREIGN KEY (`region_id`) REFERENCES `base_region` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `customer_address_ibfk_3` FOREIGN KEY (`country_id`) REFERENCES `base_country` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `customer_address_ibfk_4` FOREIGN KEY (`province_id`) REFERENCES `base_province` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;