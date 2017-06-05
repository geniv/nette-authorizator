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
-- Struktura tabulky `prefix_acl_roles`
--

CREATE TABLE `prefix_acl_roles` (
  `id` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL COMMENT 'rodic',
  `role` varchar(50) DEFAULT NULL COMMENT 'role'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='acl role';

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `prefix_acl_roles`
--
ALTER TABLE `prefix_acl_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_UNIQUE` (`role`),
  ADD KEY `fk_acl_roles_acl_roles_idx` (`id_parent`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `prefix_acl_roles`
--
ALTER TABLE `prefix_acl_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `prefix_acl_roles`
--
ALTER TABLE `prefix_acl_roles`
  ADD CONSTRAINT `fk_acl_roles_acl_roles` FOREIGN KEY (`id_parent`) REFERENCES `prefix_acl_roles` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
