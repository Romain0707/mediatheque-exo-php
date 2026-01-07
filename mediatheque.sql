-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 07 jan. 2026 à 08:49
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mediatheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `film`
--

DROP TABLE IF EXISTS `film`;
CREATE TABLE IF NOT EXISTS `film` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) DEFAULT NULL,
  `duree` int DEFAULT NULL,
  `synopsis` text,
  `img_path` varchar(255) DEFAULT NULL,
  `userid` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `film`
--

INSERT INTO `film` (`id`, `titre`, `duree`, `synopsis`, `img_path`, `userid`) VALUES
(1, 'Anaconda', 100, 'Doug et Griff sont amis d’enfance et partagent depuis toujours un rêve un peu fou : réaliser leur propre remake de leur film préféré, le cultissime ANACONDA. En pleine crise de la quarantaine, ils décident enfin de se lancer, et se retrouvent à tourner en plein cœur de l’Amazonie. Mais le rêve vire rapidement au cauchemar lorsqu’un véritable anaconda géant fait son apparition et transforme leur plateau déjà chaotique en un véritable piège mortel. Le film qu’ils meurent d’envie de faire ? Va être vraiment mortel… ', 'assets/img/img_695d6dbee98ac0.29946530.jpg', 7);

-- --------------------------------------------------------

--
-- Structure de la table `film_genre`
--

DROP TABLE IF EXISTS `film_genre`;
CREATE TABLE IF NOT EXISTS `film_genre` (
  `film_id` int DEFAULT NULL,
  `genre_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `film_genre`
--

INSERT INTO `film_genre` (`film_id`, `genre_id`) VALUES
(1, 2),
(1, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `film_realisateur`
--

DROP TABLE IF EXISTS `film_realisateur`;
CREATE TABLE IF NOT EXISTS `film_realisateur` (
  `film_id` int DEFAULT NULL,
  `realisateur_id` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `film_realisateur`
--

INSERT INTO `film_realisateur` (`film_id`, `realisateur_id`) VALUES
(3, 7),
(1, 6),
(1, 5);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE IF NOT EXISTS `genre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`id`, `nom`) VALUES
(1, 'Aventure'),
(2, 'Comédie'),
(3, 'test'),
(4, 'oui');

-- --------------------------------------------------------

--
-- Structure de la table `realisateur`
--

DROP TABLE IF EXISTS `realisateur`;
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `realisateur`
--

INSERT INTO `realisateur` (`id`, `nom`) VALUES
(7, 'test'),
(5, 'Etten Gormican'),
(6, 'Kevin Tom');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `password`) VALUES
(7, 'Jeanne', 'Darc', '$argon2i$v=19$m=65536,t=4,p=1$VkNQclNGZk85djdHWE4zdw$UV7R1EVSAJ0YDz00GfTv2vJB7wHOXTnef2PXZ45EVqA');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
