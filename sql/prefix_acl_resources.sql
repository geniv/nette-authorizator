-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vytvořeno: Pát 02. čen 2017, 00:36
-- Verze serveru: 10.0.29-MariaDB-0ubuntu0.16.04.1
-- Verze PHP: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `netteweb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `prefix_acl_resources`
--

CREATE TABLE `prefix_acl_resources` (
  `id` int(11) NOT NULL,
  `resource` varchar(100) DEFAULT NULL COMMENT 'zdroj'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='acl zdroje';

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_acl_resources`
--
ALTER TABLE `prefix_acl_resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_UNIQUE` (`resource`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_acl_resources`
--
ALTER TABLE `prefix_acl_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
