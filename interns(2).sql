-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 06 fév. 2023 à 22:15
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
-- Structure de la table `interns`
--

CREATE TABLE `interns` (
  `id` int(11) NOT NULL,
  `emp` int(11) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `dept` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `interns`
--

INSERT INTO `interns` (`id`, `emp`, `fullname`, `start_date`, `end_date`, `dept`) VALUES
(1, 10001, 'Raymond Barre', '2023-01-20', '2023-02-28', 'd006'),
(2, NULL, 'Luc Goossu', '2023-01-10', '2023-02-28', 'd007'),
(3, 10001, 'herve Mailleux', '2023-01-10', '2023-02-28', 'd006'),
(4, 10002, 'Marc Wilmots', '2023-01-01', '2023-01-03', 'd007'),
(5, NULL, 'Mermed Mamoud', '2023-01-10', '2023-02-28', 'd006'),
(6, 10005, 'Chris Blade', '2023-01-05', '2023-02-17', 'd008'),
(8, 10004, 'Dana White', '2023-01-13', '2023-03-31', 'd002'),
(10, 10001, 'Po Atan', '2023-01-13', '2023-03-31', 'd008'),
(11, 10001, 'Israel Adesanya', '2023-01-13', '2023-03-31', 'd001');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `interns`
--
ALTER TABLE `interns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept` (`dept`),
  ADD KEY `emp` (`emp`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `interns`
--
ALTER TABLE `interns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `interns`
--
ALTER TABLE `interns`
  ADD CONSTRAINT `interns_ibfk_1` FOREIGN KEY (`emp`) REFERENCES `employees` (`emp_no`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `interns_ibfk_2` FOREIGN KEY (`dept`) REFERENCES `departments` (`dept_no`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
