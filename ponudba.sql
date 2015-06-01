-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2015 at 08:52 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trgovina`
--

-- --------------------------------------------------------

--
-- Table structure for table `ponudba`
--

CREATE TABLE IF NOT EXISTS `ponudba` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `izdelek` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zvrst` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cena` decimal(5,2) NOT NULL,
  `zaloga` smallint(6) NOT NULL,
  `akcija` tinyint(1) DEFAULT NULL COMMENT 'ali je izdelek v akciji',
  `slika` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'link do slike',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ponudba`
--

INSERT INTO `ponudba` (`id`, `izdelek`, `zvrst`, `cena`, `zaloga`, `akcija`, `slika`) VALUES
(1, 'Paul Mas Estate', 'Chardonnay', '8.99', 30, NULL, 'images/wine00.jpg'),
(2, 'Greenhough', 'Sauvignon', '6.49', 14, NULL, 'images/wine01.jpg'),
(3, 'Pablo Claro', 'Chardonnay', '4.98', 145, 1, 'images/wine02.jpg'),
(4, 'Selaks Reserve', 'Chardonnay', '6.12', 3, NULL, 'images/wine03.jpg'),
(5, 'Single Peak', 'Sauvignon', '14.00', 10, NULL, 'images/wine04.jpg'),
(6, 'JP Chenet', 'Syrah', '2.20', 29, 1, NULL),
(7, 'Movia', 'Sauvignon', '5.15', 500, 1, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
