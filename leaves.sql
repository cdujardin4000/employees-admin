-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 fév. 2023 à 11:16
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `employee_base`
--

-- --------------------------------------------------------

--
-- Structure de la table `leaves`
--

CREATE TABLE `leaves` (
  `leave_id` int(11) NOT NULL,
  `emp_no` int(6) NOT NULL,
  `type` varchar(15) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `leaves`
--

INSERT INTO `leaves` (`leave_id`, `emp_no`, `type`, `from_date`, `to_date`) VALUES
(1, 10001, 'paternity', '2022-01-30 16:09:52', '2023-02-02 19:54:43'),
(2, 10002, 'sick', '2023-01-22 01:06:05', '2023-02-24 00:43:40'),
(3, 10003, 'sick', '2023-01-21 19:51:07', '2023-01-31 19:51:42'),
(4, 10001, 'training', '2023-01-04 20:23:22', '2023-01-24 00:44:03'),
(5, 10001, 'training', '2023-01-07 20:23:22', '2023-01-20 20:23:22'),
(6, 10006, 'training', '2023-01-07 20:23:22', '2023-01-23 21:33:25'),
(7, 10008, 'training', '2023-01-07 20:23:22', '2023-01-23 23:59:46');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `emp_no_2` (`emp_no`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_ibfk_1` FOREIGN KEY (`emp_no`) REFERENCES `employees` (`emp_no`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
