-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : mar. 19 août 2025 à 16:28
-- Version du serveur : 9.4.0
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `memory`
--

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE `cards` (
  `id` int NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cards`
--

INSERT INTO `cards` (`id`, `img_path`, `category`) VALUES
(1, './assets/cards/default/image022.png', 'default'),
(2, './assets/cards/default/image023.png', 'default'),
(3, './assets/cards/default/image024.png', 'default'),
(4, './assets/cards/default/image025.png', 'default'),
(5, './assets/cards/default/image026.png', 'default'),
(6, './assets/cards/default/image027.png', 'default'),
(7, './assets/cards/default/image028.png', 'default'),
(8, './assets/cards/default/image029.png', 'default'),
(9, './assets/cards/default/image030.png', 'default'),
(10, './assets/cards/default/image031.png', 'default'),
(11, './assets/cards/default/image032.png', 'default'),
(12, './assets/cards/default/image033.png', 'default'),
(13, './assets/cards/manga/yotanwa.png', 'manga'),
(14, './assets/cards/manga/toka.jpg', 'manga'),
(15, './assets/cards/manga/solanin_meiko1.jpg', 'manga'),
(16, './assets/cards/manga/shinobu.jpg', 'manga'),
(17, './assets/cards/manga/shin.png', 'manga'),
(18, './assets/cards/manga/rei.png', 'manga'),
(19, './assets/cards/manga/punpun.png', 'manga'),
(20, './assets/cards/manga/miyamoto.png', 'manga'),
(21, './assets/cards/manga/mha.jpg', 'manga'),
(22, './assets/cards/manga/kingdom.png', 'manga'),
(23, './assets/cards/manga/ken.png', 'manga'),
(24, './assets/cards/manga/karin.png', 'manga'),
(25, './assets/cards/manga/kaori.png', 'manga'),
(26, './assets/cards/manga/hitagi.png', 'manga'),
(27, './assets/cards/manga/guts.png', 'manga'),
(28, './assets/cards/manga/chisu.jpg', 'manga'),
(29, './assets/cards/manga/casca.png', 'manga');

-- --------------------------------------------------------

--
-- Structure de la table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `id` int NOT NULL,
  `score` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Index pour la table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT pour la table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
