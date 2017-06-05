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
-- Struktura tabulky `prefix_acl_prvilege`
--

CREATE TABLE `prefix_acl_prvilege` (
  `id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL COMMENT 'fk role',
  `id_resource` int(11) NOT NULL COMMENT 'fk zdroj',
  `privilege` varchar(255) DEFAULT NULL COMMENT 'opravneni',
  `type` enum('allow','deny') DEFAULT NULL COMMENT 'typ opravneni',
  `position` int(11) DEFAULT NULL COMMENT 'poradi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='acl opravneni';

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_acl_prvilege`
--
ALTER TABLE `prefix_acl_prvilege`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_resource_privilege_type_UNIQUE` (`id_role`,`id_resource`,`privilege`,`type`),
  ADD KEY `fk_acl_prvilege_acl_roles_idx` (`id_role`),
  ADD KEY `fk_acl_prvilege_acl_resources_idx` (`id_resource`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_acl_prvilege`
--
ALTER TABLE `prefix_acl_prvilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `prefix_acl_prvilege`
--
ALTER TABLE `prefix_acl_prvilege`
  ADD CONSTRAINT `fk_acl_prvilege_acl_resources` FOREIGN KEY (`id_resource`) REFERENCES `prefix_acl_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_acl_prvilege_acl_roles` FOREIGN KEY (`id_role`) REFERENCES `prefix_acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
