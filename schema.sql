-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 14 déc. 2025 à 14:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `product-category`
--

CREATE TABLE `product-category` (
  `id` int(11) NOT NULL,
  `label` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product-category`
--

INSERT INTO `product-category` (`id`, `label`, `description`) VALUES
(6, 'AAAA', 'BBBCDAAAAAA');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `label` varchar(4098) NOT NULL,
  `type` enum('TEXT','CHECKBOX','RADIO','SWITCH','SLIDER') NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`id`, `label`, `type`, `details`, `rate`) VALUES
(24, 'Aliqua Reprehenderi', 'SWITCH', '{\"correct\":\"on\"}', 95),
(25, 'Quia culpa ut incid', 'SWITCH', '{\"correct\":\"off\"}', 58),
(26, 'Consequuntur quae el', 'TEXT', '{\"correct\":\"Quis expedita obcaec\"}', 26),
(27, 'Autem veniam soluta', 'RADIO', '{\"choices\":[{\"id\":\"Nihil cumque numquam\",\"label\":\"Pariatur Ad ea itaq\",\"correct\":false},{\"id\":\"Voluptatem animi di\",\"label\":\"Voluptatem iusto ni\",\"correct\":true},{\"id\":\"Amet architecto nec\",\"label\":\"Delectus deserunt a\",\"correct\":false},{\"id\":\"Quos ad esse adipisc\",\"label\":\"Cillum ad non aut qu\",\"correct\":true}]}', 71),
(28, 'Ex expedita nisi dol', 'RADIO', '{\"choices\":[{\"id\":\"Libero et non aut ip\",\"label\":\"Anim a nostrum quasi\",\"correct\":true},{\"id\":\"Eos fugit beatae f\",\"label\":\"Voluptatem nisi moll\",\"correct\":true},{\"id\":\"Est deserunt incidi\",\"label\":\"Est commodo praesen\",\"correct\":true},{\"id\":\"Sed Nam nisi sint mo\",\"label\":\"Ut voluptas tempore\",\"correct\":false}]}', 70);

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz`
--

INSERT INTO `quiz` (`id`, `name`, `description`) VALUES
(11, 'Brady Gould', 'Autem in ea veniam');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question`
--

CREATE TABLE `quiz_question` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_question`
--

INSERT INTO `quiz_question` (`question_id`, `quiz_id`, `ordering`) VALUES
(24, 11, 0),
(25, 11, 1),
(26, 11, 2),
(27, 11, 3),
(28, 11, 4);

-- --------------------------------------------------------

--
-- Structure de la table `submission`
--

CREATE TABLE `submission` (
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `birthdate`, `email`, `password`) VALUES
(1, '', '', NULL, 'softsurgery2@gmail.com', '129ee45b6d00663fb8b0fa682a7c7fe3'),
(2, '', '', NULL, 'softsurgery3@gmail.com', '129ee45b6d00663fb8b0fa682a7c7fe3'),
(3, '', '', NULL, 'softsurgery@gmail.com', '129ee45b6d00663fb8b0fa682a7c7fe3');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `product-category`
--
ALTER TABLE `product-category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD KEY `question_id` (`question_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Index pour la table `submission`
--
ALTER TABLE `submission`
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `product-category`
--
ALTER TABLE `product-category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD CONSTRAINT `quiz_question_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quiz_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
