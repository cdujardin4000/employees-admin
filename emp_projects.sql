-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 fév. 2023 à 00:33
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
-- Structure de la table `emp_projects`
--

CREATE TABLE `emp_projects` (
  `project_id` int(11) NOT NULL,
  `emp_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `emp_projects`
--

INSERT INTO `emp_projects` (`project_id`, `emp_no`) VALUES
(1, 10001),
(2, 10002),
(3, 10005),
(1, 10009),
(2, 10005),
(4, 10002);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `emp_projects`
--
ALTER TABLE `emp_projects`
  ADD KEY `project_id` (`project_id`),
  ADD KEY `emp_no` (`emp_no`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emp_projects`
--
ALTER TABLE `emp_projects`
  ADD CONSTRAINT `emp_projects_ibfk_1` FOREIGN KEY (`emp_no`) REFERENCES `employees` (`emp_no`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `emp_projects_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
