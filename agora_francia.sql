-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 02 juin 2024 à 20:18
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
-- Base de données : `agora_francia`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse_ligne1` varchar(255) NOT NULL,
  `adresse_ligne2` varchar(255) DEFAULT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(20) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `numero_telephone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `adresses`
--

INSERT INTO `adresses` (`id`, `utilisateur_id`, `nom`, `prenom`, `adresse_ligne1`, `adresse_ligne2`, `ville`, `code_postal`, `pays`, `numero_telephone`) VALUES
(6, 1, 'Schwartz', 'Oscar', '33 avenue de la résistance', '', 'Chaville', '92370', 'France', '0789311759'),
(7, 3, 'Charles', 'De Blauwe', '41 Rue Greffeulhe', '', 'Levallois-Perret', '92300', 'France', '0123456789'),
(8, 5, 'Schwartz', 'Oscar', '1 Avenue gérard piqué', '', 'Paris', '75016', 'France', '0123456789'),
(9, 7, 'Paire', 'Benoit', '6 rue puecqay', '', 'Paris', '75004', 'France', '0813876534');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `categorie` enum('meubles','objets_art','accessoire_vip','materiels_scolaires') DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `vendeur_id` int(11) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp(),
  `type_vente` enum('immediate','negociation','enchere') DEFAULT NULL,
  `etat` enum('neuf avec etiquette','neuf sans etiquette','tres bon etat','bon etat','satisfaisant') DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `date_fin` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `nom`, `description`, `categorie`, `prix`, `quantite`, `vendeur_id`, `date_ajout`, `type_vente`, `etat`, `photo`, `video`, `date_fin`) VALUES
(7, 'Arduino', 'Nano bot', 'objets_art', 30.00, 0, 3, '2024-05-29 14:32:09', 'enchere', 'bon etat', 'uploads/Capture d\'écran 2023-05-08 172500.png', '', '2024-05-30 20:13:45'),
(8, 'Sac Jacquemus', 'Trait bo', 'accessoire_vip', 1000.00, 2, 1, '2024-05-29 15:27:19', 'enchere', 'bon etat', 'uploads/image8.jpg', '', '2024-05-30 20:13:45'),
(9, 'Madrid', 'Jude Bellingham', 'objets_art', 90.00, 6, 1, '2024-05-29 15:28:16', 'negociation', 'bon etat', 'uploads/image7.jpg', '', '2024-05-30 20:13:45'),
(12, 'René', 'DIDI', 'meubles', 100.00, 2, 1, '2024-05-30 20:14:24', 'enchere', 'neuf avec etiquette', 'uploads/panda.jpg', '', '2024-05-30 20:21:54'),
(13, 'Pengolin', 'Poisson très frais', 'objets_art', 123.00, 0, 5, '2024-05-31 14:13:05', 'immediate', 'neuf avec etiquette', 'uploads/nguyen.jpg', '', '2024-05-31 14:13:05'),
(14, 'Habit', 'Ok', 'accessoire_vip', 30.00, 5, 5, '2024-06-01 22:15:31', 'negociation', 'neuf avec etiquette', 'uploads/image5.jpg', '', '2024-06-01 22:15:31'),
(15, 'Offre', 'mhhmm', 'meubles', 50.00, -3, 1, '2024-06-01 22:54:43', 'negociation', 'neuf avec etiquette', 'uploads/image7.jpg', '', '2024-06-01 22:54:43'),
(16, 'sape', 'azer', 'accessoire_vip', 100.00, 9, 1, '2024-06-02 13:43:30', 'negociation', 'neuf avec etiquette', 'uploads/image6.jpg', '', '2024-06-02 13:43:30'),
(17, 'azertyuiop', 'dzm,dmz', 'objets_art', 100.00, 2, 3, '2024-06-02 14:17:07', 'negociation', 'neuf avec etiquette', 'uploads/Capture d\'écran 2023-09-20 091251.png', '', '2024-06-02 14:17:07'),
(19, 'Stephan', 'azer', 'objets_art', 9.00, 0, 3, '2024-06-02 17:17:10', 'immediate', 'tres bon etat', 'uploads/Capture d\'écran 2024-05-30 195357.png', '', '2024-06-02 17:17:10'),
(20, 'Habitr', 'hgvkivk', 'objets_art', 900.00, 10, 1, '2024-06-02 17:23:45', 'immediate', 'neuf avec etiquette', 'uploads/pompe2.png', '', '2024-06-02 17:23:45');

-- --------------------------------------------------------

--
-- Structure de la table `cartes`
--

CREATE TABLE `cartes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `type_carte` enum('Visa','MasterCard','AmericanExpress','PayPal') DEFAULT NULL,
  `numero_carte` varchar(16) DEFAULT NULL,
  `nom_carte` varchar(100) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `code_securite` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cartes`
--

INSERT INTO `cartes` (`id`, `utilisateur_id`, `type_carte`, `numero_carte`, `nom_carte`, `expiration`, `code_securite`) VALUES
(8, 1, 'Visa', '0397449277551832', 'Schwartz', '0000-00-00', '017'),
(14, 3, 'AmericanExpress', '3749052690170009', 'De Blauwe', '2026-06-01', '000'),
(15, 5, 'Visa', '0000111122223333', 'MATUIDI', '0000-00-00', '111'),
(16, 7, 'Visa', '1111222200003333', 'Paire', '0000-00-00', '111');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `adresse_livraison` text DEFAULT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('en attente','expédiée','livrée','annulée') DEFAULT NULL,
  `type_transaction` enum('immediate','negociation','enchere') DEFAULT 'immediate'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `acheteur_id`, `article_id`, `quantite`, `prix_total`, `adresse_livraison`, `date_commande`, `status`, `type_transaction`) VALUES
(1, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:45:36', 'en attente', 'immediate'),
(2, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:46:15', 'en attente', 'immediate'),
(3, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:47:00', 'en attente', 'immediate'),
(4, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:55:30', 'en attente', 'immediate'),
(5, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:57:18', 'en attente', 'immediate'),
(6, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:57:27', 'en attente', 'immediate'),
(7, 1, NULL, NULL, 23.00, NULL, '2024-05-29 09:58:56', 'en attente', 'immediate'),
(8, 1, NULL, NULL, 400.00, NULL, '2024-05-29 10:48:28', 'en attente', 'immediate'),
(9, 1, NULL, NULL, 23.00, NULL, '2024-05-29 10:53:59', 'en attente', 'immediate'),
(10, 1, NULL, NULL, 123.00, NULL, '2024-05-29 10:59:30', 'en attente', 'immediate'),
(11, 1, NULL, NULL, 23.00, NULL, '2024-05-29 11:00:30', 'en attente', 'immediate'),
(12, 1, NULL, NULL, 123.00, NULL, '2024-05-29 11:09:01', 'en attente', 'immediate'),
(13, 1, NULL, NULL, 200.00, NULL, '2024-05-29 11:09:45', 'en attente', 'immediate'),
(14, 1, NULL, NULL, 200.00, NULL, '2024-05-29 11:20:25', 'en attente', 'immediate'),
(15, 1, NULL, NULL, 400.00, NULL, '2024-05-29 11:23:26', 'en attente', 'immediate'),
(16, 1, NULL, NULL, 100.00, NULL, '2024-05-29 13:32:15', 'en attente', 'immediate'),
(17, 1, NULL, NULL, 200.00, NULL, '2024-05-29 13:34:23', 'en attente', 'immediate'),
(18, 1, NULL, NULL, 75.00, NULL, '2024-05-29 13:45:45', 'en attente', 'immediate'),
(19, 1, NULL, NULL, 200.00, NULL, '2024-05-29 14:27:07', 'en attente', 'immediate'),
(20, 3, NULL, NULL, 0.00, NULL, '2024-05-29 14:47:56', 'en attente', 'immediate'),
(21, 3, NULL, NULL, 0.00, NULL, '2024-05-29 14:48:01', 'en attente', 'immediate'),
(22, 3, NULL, NULL, 25.00, NULL, '2024-05-29 14:48:18', 'en attente', 'immediate'),
(23, 3, NULL, NULL, 25.00, NULL, '2024-05-29 14:51:13', 'en attente', 'immediate'),
(24, 1, NULL, NULL, 55.00, NULL, '2024-05-29 15:02:06', 'en attente', 'immediate'),
(25, 1, NULL, NULL, 50.00, NULL, '2024-05-29 15:10:05', 'en attente', 'immediate'),
(26, 1, NULL, NULL, 1000.00, NULL, '2024-05-29 15:30:54', 'en attente', 'immediate'),
(27, 1, NULL, NULL, 3000.00, NULL, '2024-05-29 15:34:38', 'en attente', 'immediate'),
(28, 1, NULL, NULL, 2000.00, NULL, '2024-05-29 17:26:35', 'en attente', 'immediate'),
(29, 6, NULL, NULL, 100.00, NULL, '2024-05-30 20:59:15', 'en attente', 'immediate'),
(30, 1, NULL, NULL, 123.00, 'Schwartz Oscar, 33 avenue de la résistance, , Chaville, 92370, France, 0789311759', '2024-05-31 14:13:40', 'en attente', 'immediate'),
(31, 1, NULL, NULL, 123.00, 'Schwartz Oscar, 33 avenue de la résistance, , Chaville, 92370, France, 0789311759', '2024-05-31 14:16:54', 'en attente', 'immediate'),
(32, 1, NULL, NULL, 123.00, 'Schwartz Oscar, 33 avenue de la résistance, , Chaville, 92370, France, 0789311759', '2024-05-31 14:23:54', 'en attente', 'immediate'),
(33, 1, 9, 1, 80.00, NULL, '2024-05-31 15:27:05', 'en attente', 'negociation'),
(34, 5, NULL, NULL, 100.00, NULL, '2024-06-01 22:07:10', 'en attente', 'immediate'),
(35, 5, NULL, NULL, 100.00, '{\"nom\":null,\"prenom\":null,\"adresse_ligne1\":\"1 Avenue g\\u00e9rard piqu\\u00e9\",\"adresse_ligne2\":\"\",\"ville\":\"Paris\",\"code_postal\":\"75016\",\"pays\":\"France\",\"numero_telephone\":\"0123456789\"}', '2024-06-01 22:25:34', 'en attente', 'immediate'),
(36, 5, NULL, NULL, 100.00, '{\"nom\":null,\"prenom\":null,\"adresse_ligne1\":\"1 Avenue g\\u00e9rard piqu\\u00e9\",\"adresse_ligne2\":\"\",\"ville\":\"Paris\",\"code_postal\":\"75016\",\"pays\":\"France\",\"numero_telephone\":\"0123456789\"}', '2024-06-01 22:33:21', 'en attente', 'immediate'),
(37, 5, 14, 1, 0.00, NULL, '2024-06-01 22:53:01', 'en attente', 'negociation'),
(38, 1, 15, 1, 0.00, NULL, '2024-06-01 22:55:14', 'en attente', 'negociation'),
(39, 1, NULL, NULL, 123.00, '{\"nom\":null,\"prenom\":null,\"adresse_ligne1\":\"33 avenue de la r\\u00e9sistance\",\"adresse_ligne2\":\"\",\"ville\":\"Chaville\",\"code_postal\":\"92370\",\"pays\":\"France\",\"numero_telephone\":\"0789311759\"}', '2024-06-02 00:08:14', 'en attente', 'immediate'),
(40, 1, NULL, NULL, 80.00, '', '2024-06-02 00:44:52', 'en attente', 'immediate'),
(41, 1, NULL, NULL, NULL, '', '2024-06-02 00:49:45', 'en attente', 'immediate'),
(42, 1, NULL, NULL, NULL, '', '2024-06-02 00:57:50', 'en attente', 'immediate'),
(43, 1, NULL, NULL, NULL, '', '2024-06-02 01:03:04', 'en attente', 'immediate'),
(44, 1, NULL, NULL, NULL, '', '2024-06-02 01:03:42', 'en attente', 'immediate'),
(45, 1, NULL, NULL, NULL, '', '2024-06-02 01:09:05', 'en attente', 'immediate'),
(46, 1, NULL, NULL, NULL, '', '2024-06-02 01:13:04', 'en attente', 'immediate'),
(47, 1, 14, 1, 40.00, '', '2024-06-02 13:05:02', 'en attente', 'immediate'),
(48, 1, 9, 1, 40.00, '33 avenue de la résistance , Chaville, 92370, France, 0789311759', '2024-06-02 13:16:46', 'en attente', 'immediate'),
(49, 5, 14, 1, NULL, '1 Avenue gérard piqué , Paris, 75016, France, 0123456789', '2024-06-02 13:41:04', 'en attente', 'immediate'),
(50, 1, 16, 1, 0.00, '33 avenue de la résistance , Chaville, 92370, France, 0789311759', '2024-06-02 14:01:29', 'en attente', 'immediate'),
(51, 1, 9, 1, 0.00, '33 avenue de la résistance , Chaville, 92370, France, 0789311759', '2024-06-02 14:05:37', 'en attente', 'immediate'),
(52, 5, 14, 1, 85.00, '1 Avenue gérard piqué , Paris, 75016, France, 0123456789', '2024-06-02 14:15:39', 'en attente', 'immediate'),
(53, 3, 17, 1, 90.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 14:17:43', 'en attente', 'immediate'),
(54, 3, 17, 1, 0.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 14:27:28', 'en attente', 'immediate'),
(55, 3, 17, 1, 0.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 14:41:04', 'en attente', 'immediate'),
(56, 3, 17, 1, 0.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 14:47:46', 'en attente', 'immediate'),
(57, 5, 14, 1, 90.00, '1 Avenue gérard piqué , Paris, 75016, France, 0123456789', '2024-06-02 14:52:57', 'en attente', 'immediate'),
(58, 5, 14, 1, 20.00, '1 Avenue gérard piqué , Paris, 75016, France, 0123456789', '2024-06-02 14:55:45', 'en attente', 'immediate'),
(59, 3, 17, 1, 20.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 15:01:12', 'en attente', 'immediate'),
(60, 3, 17, 1, 20.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 15:02:33', 'en attente', 'immediate'),
(61, 3, 17, 1, 20.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 15:11:36', 'en attente', 'immediate'),
(62, 5, NULL, NULL, 100.00, '{\"nom\":null,\"prenom\":null,\"adresse_ligne1\":\"1 Avenue g\\u00e9rard piqu\\u00e9\",\"adresse_ligne2\":\"\",\"ville\":\"Paris\",\"code_postal\":\"75016\",\"pays\":\"France\",\"numero_telephone\":\"0123456789\"}', '2024-06-02 15:14:13', 'en attente', 'immediate'),
(63, 3, 17, 1, 20.00, '41 Rue Greffeulhe , Levallois-Perret, 92300, France, 0123456789', '2024-06-02 15:17:08', 'en attente', 'immediate'),
(64, 7, NULL, NULL, 36.00, '{\"nom\":\"Paire\",\"prenom\":\"Benoit\",\"adresse_ligne1\":\"6 rue puecqay\",\"adresse_ligne2\":\"\",\"ville\":\"Paris\",\"code_postal\":\"75004\",\"pays\":\"France\",\"numero_telephone\":\"0813876534\"}', '2024-06-02 17:18:43', 'en attente', 'immediate');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_articles`
--

CREATE TABLE `commandes_articles` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes_articles`
--

INSERT INTO `commandes_articles` (`id`, `commande_id`, `article_id`, `quantite`) VALUES
(20, 24, 7, 1),
(22, 26, 8, 1),
(24, 28, 8, 2),
(26, 30, 13, 1),
(27, 31, 13, 1),
(28, 32, 13, 1),
(32, 39, 13, 1),
(33, 40, 9, 1),
(34, 41, 15, 1),
(35, 42, 15, 1),
(36, 43, 15, 1),
(37, 44, 15, 1),
(38, 45, 15, 1),
(39, 46, 15, 1),
(40, 47, 15, 1),
(41, 48, 9, 1),
(42, 49, 14, 1),
(43, 50, 16, 1),
(44, 51, 9, 1),
(45, 52, 14, 1),
(46, 53, 17, 1),
(47, 54, 17, 1),
(48, 55, 17, 1),
(49, 56, 17, 1),
(50, 57, 14, 1),
(51, 58, 14, 1),
(52, 59, 17, 1),
(53, 60, 17, 1),
(54, 61, 17, 1),
(56, 63, 17, 1),
(57, 64, 19, 4);

-- --------------------------------------------------------

--
-- Structure de la table `encheres`
--

CREATE TABLE `encheres` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `montant_offre` decimal(10,2) DEFAULT NULL,
  `date_offre` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `encheres`
--

INSERT INTO `encheres` (`id`, `article_id`, `acheteur_id`, `montant_offre`, `date_offre`) VALUES
(1, 12, 3, 101.00, '2024-05-30 20:15:05'),
(2, 12, 5, 105.00, '2024-05-30 20:17:20'),
(3, 12, 3, 170.00, '2024-05-30 20:17:27'),
(4, 12, 5, 171.00, '2024-05-30 20:20:06'),
(5, 12, 3, 171.01, '2024-05-30 20:20:41');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` >= 1 and `note` <= 5),
  `commentaire` text DEFAULT NULL,
  `date_evaluation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messagerie`
--

CREATE TABLE `messagerie` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendeur_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `messagerie`
--

INSERT INTO `messagerie` (`id`, `user_id`, `vendeur_id`, `article_id`, `message`, `timestamp`) VALUES
(0, 1, 5, 14, 'Prix proposé: 25 € - Message: ok', '2024-06-01 22:16:02'),
(0, 1, 5, 14, 'ok', '2024-06-01 22:16:43'),
(0, 1, 5, 14, 'Slt', '2024-06-01 22:52:51'),
(0, 5, 1, 15, 'Prix proposé: 40 € - Message: slt', '2024-06-01 22:54:58'),
(0, 1, 5, 14, 'Prix proposé: 20 € - Message: 20', '2024-06-01 23:06:38'),
(0, 5, 1, 15, 'Prix proposé: 40 € - Message: 40', '2024-06-01 23:18:57'),
(0, 1, 1, 15, 'Contre-offre : OK', '2024-06-01 23:19:10'),
(0, 5, 1, 15, 'non', '2024-06-01 23:20:54'),
(0, 1, 1, 15, 'Contre-offre : non', '2024-06-01 23:21:05'),
(0, 5, 1, 15, 'ok', '2024-06-01 23:27:30'),
(0, 1, 1, 15, 'Contre-offre du vendeur : 47 €', '2024-06-01 23:28:05'),
(0, 5, 1, 15, 'hmmm', '2024-06-01 23:28:25'),
(0, 1, 1, 15, 'Contre-offre du vendeur : 47 €', '2024-06-01 23:28:58'),
(0, 5, 1, 15, 'Offre de l\'acheteur : 46 €', '2024-06-01 23:32:06'),
(0, 1, 1, 15, 'Contre-offre du vendeur : 47 €', '2024-06-01 23:32:25'),
(0, 5, 1, 15, 'Offre de l\'acheteur : 47 €', '2024-06-01 23:32:55'),
(0, 1, 1, 15, 'ok', '2024-06-01 23:41:48'),
(0, 5, 1, 15, 'Offre de l\'acheteur : 46 €', '2024-06-01 23:54:52'),
(0, 1, 1, 15, 'ok', '2024-06-01 23:55:00'),
(0, 5, 1, 15, 'Offre de l\'acheteur : 46 €', '2024-06-02 00:06:27'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:06:37'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:14:30'),
(0, 5, 1, 15, 'slt', '2024-06-02 00:14:43'),
(0, 5, 1, 15, 'Offre de l\'acheteur : 48 €', '2024-06-02 00:14:52'),
(0, 1, 1, 15, 'J\'accepte', '2024-06-02 00:15:02'),
(0, 5, 1, 15, 'Prix proposé: 40 € - Message: zarr', '2024-06-02 00:15:32'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:15:40'),
(0, 5, 1, 15, 'Proposition de prix : 48', '2024-06-02 00:23:40'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:23:50'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:37:31'),
(0, 5, 1, 15, 'Proposition de prix : 47', '2024-06-02 00:49:32'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=41\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 00:49:45'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:56:15'),
(0, 5, 1, 15, 'ok', '2024-06-02 00:57:01'),
(0, 5, 1, 15, 'Proposition de prix : 40', '2024-06-02 00:57:11'),
(0, 1, 1, 15, 'ok', '2024-06-02 00:57:22'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=42\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 00:57:50'),
(0, 5, 1, 15, 'Proposition de prix : 40', '2024-06-02 01:02:12'),
(0, 1, 1, 15, 'ok', '2024-06-02 01:02:29'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=43\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 01:03:04'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : \'<a href=\'order_success.php?id=44\' class=\'btn-primary\'>Voir la commande</a>\'', '2024-06-02 01:03:42'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=45\' class=\'btn-primary\' style=\'display:inline-block;padding:10px 20px;background-color:#007BFF;color:white;border-radius:5px;text-decoration:none;\'>Voir la commande</a>', '2024-06-02 01:09:05'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <form action=\'order_success.php\' method=\'get\' style=\'display:inline-block;\'>\n                    <input type=\'hidden\' name=\'id\' value=\'46\'>\n                    <button type=\'submit\' class=\'btn-primary\' style=\'display:inline-block;padding:10px 20px;background-color:#007BFF;color:white;border-radius:5px;text-decoration:none;border:none;cursor:pointer;\'>Voir la commande</button>\n                </form>', '2024-06-02 01:13:04'),
(0, 5, 1, 15, 'Proposition de prix : 40', '2024-06-02 13:04:50'),
(0, 1, 1, 15, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 13:05:02'),
(0, 5, 1, 9, 'Prix proposé: 50 € - Message: Salut\r\n', '2024-06-02 13:13:07'),
(0, 1, 1, 9, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 13:16:46'),
(0, 1, 5, 14, 'Prix proposé: 20 € - Message: a', '2024-06-02 13:25:10'),
(0, 5, 5, 14, 'ok', '2024-06-02 13:25:22'),
(0, 5, 5, 14, 'm', '2024-06-02 13:25:34'),
(0, 1, 5, 14, 'Proposition de prix : 20', '2024-06-02 13:38:37'),
(0, 5, 5, 14, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 13:41:04'),
(0, 5, 1, 16, 'Prix proposé: 70 € - Message: envoie fesse\r\n', '2024-06-02 13:43:50'),
(0, 1, 1, 16, 'Votre offre a été refusée.', '2024-06-02 13:44:09'),
(0, 5, 1, 16, 'Proposition de prix : 85', '2024-06-02 13:44:22'),
(0, 1, 1, 16, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 14:01:29'),
(0, 5, 1, 9, 'Prix proposé: 80 € - Message: oki doki', '2024-06-02 14:05:26'),
(0, 1, 1, 9, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 14:05:37'),
(0, 3, 5, 14, 'Prix proposé: 20 € - Message: azer', '2024-06-02 14:15:02'),
(0, 5, 5, 14, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 14:15:39'),
(0, 3, 5, 14, 'Proposition de prix : 20', '2024-06-02 14:52:44'),
(0, 5, 5, 14, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=57\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 14:52:57'),
(0, 3, 5, 14, 'Proposition de prix : 20', '2024-06-02 14:55:28'),
(0, 5, 5, 14, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 14:55:45'),
(0, 5, 1, 16, 'Proposition de prix : 90', '2024-06-02 15:00:11'),
(0, 5, 3, 17, 'Prix proposé: 90 € - Message: caca', '2024-06-02 15:00:45'),
(0, 3, 3, 17, 'Votre offre a été acceptée. Votre commande a été créée avec succès.', '2024-06-02 15:02:33'),
(0, 5, 3, 17, 'Proposition de prix : 80', '2024-06-02 15:11:29'),
(0, 3, 3, 17, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=61\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 15:11:36'),
(0, 5, 3, 17, 'Proposition de prix : 80', '2024-06-02 15:17:00'),
(0, 3, 3, 17, 'Votre offre a été acceptée. Vous pouvez voir la commande ici : <a href=\'order_success.php?id=63\' class=\'btn-primary\'>Voir la commande</a>', '2024-06-02 15:17:08');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_notification` timestamp NOT NULL DEFAULT current_timestamp(),
  `lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `utilisateur_id`, `article_id`, `message`, `date_notification`, `lu`) VALUES
(1, 5, NULL, 'L\'utilisateur Oscar Schwartz a demandé à devenir vendeur.', '2024-05-30 20:24:42', 1),
(2, 5, NULL, 'L\'utilisateur Oscar Schwartz a demandé à devenir vendeur.', '2024-05-30 20:24:55', 1),
(3, 6, NULL, 'L\'utilisateur Blaise Matuidi a demandé à devenir vendeur.', '2024-05-30 20:50:32', 1),
(4, 7, NULL, 'L\'utilisateur Benoit Paire a demandé à devenir vendeur.', '2024-06-02 17:12:11', 1),
(5, 7, NULL, 'L\'utilisateur Benoit Paire a demandé à devenir vendeur.', '2024-06-02 17:12:25', 0);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `prix_final` decimal(10,2) DEFAULT NULL,
  `date_transaction` timestamp NOT NULL DEFAULT current_timestamp(),
  `type_transaction` enum('immediate','negociation','enchere') DEFAULT NULL,
  `status` enum('en cours','complété','annulé') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `type_utilisateur` enum('admin','vendeur','acheteur') DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `image_fond` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `adresse`, `ville`, `code_postal`, `pays`, `type_utilisateur`, `photo`, `image_fond`) VALUES
(1, 'Oscar', 'Schwartz', 'oscar.schwartz@edu.ece.fr', '$2y$10$8LYHsiW8GCM8bpJgQigL6.nKj2OTgHvPcgWydpkj29wttrvwtZl3S', NULL, NULL, NULL, NULL, 'admin', 'uploads/bambino.jpg', NULL),
(3, 'Charles', 'De Blauwe', 'charles.deblauwe@edu.ece.fr', '$2y$10$jjY1oWhw/FtFAhM6YEdihOBvXoLarb8DAnKVThu4H.ZBpnNS.0F8u', NULL, NULL, NULL, NULL, 'admin', 'uploads/Capture d\'écran 2023-11-19 184206.png', NULL),
(4, 'Oscar', 'Schwartz', 'oscar.schwartz92@gmail.com', '$2y$10$NEoDbZ0gxbx20uKJeh0dkOYshLdP.u16x1hGJNh2vRDeBO56mIMGG', NULL, NULL, NULL, NULL, 'acheteur', NULL, NULL),
(5, 'Schwartz', 'Oscar', 'oscar.schwartz92@icloud.com', '$2y$10$jf2.0Nnw3f4rsUif26wIA.JOeRSjvCi0uwR5M6/TYvlfXSPG7aLkC', NULL, NULL, NULL, NULL, 'vendeur', 'uploads/Capture d\'écran 2024-05-30 195210.png', NULL),
(6, 'Matuidi', 'Blaise', 'a@gmail.com', '$2y$10$UerQSj5FdiNpm8M5WBvrMeVKlh4TM2Kvk5WXDbdphVvPkag7Wjsju', NULL, NULL, NULL, NULL, 'vendeur', 'uploads/stephan.jpg', NULL),
(7, 'Paire', 'Benoit', 'b@gmail.com', '$2y$10$/ydzydYGYvac/eAN55EaAuDAf9Gtqc/tS0hpklhdcuUIathruT9FO', NULL, NULL, NULL, NULL, 'vendeur', 'uploads/Capture d\'écran 2024-05-17 174342.png', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendeur_id` (`vendeur_id`);

--
-- Index pour la table `cartes`
--
ALTER TABLE `cartes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acheteur_id` (`acheteur_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `commandes_articles`
--
ALTER TABLE `commandes_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `acheteur_id` (`acheteur_id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acheteur_id` (`acheteur_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acheteur_id` (`acheteur_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `commandes_articles`
--
ALTER TABLE `commandes_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `encheres`
--
ALTER TABLE `encheres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`vendeur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `cartes`
--
ALTER TABLE `cartes`
  ADD CONSTRAINT `cartes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`acheteur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Contraintes pour la table `commandes_articles`
--
ALTER TABLE `commandes_articles`
  ADD CONSTRAINT `commandes_articles_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`),
  ADD CONSTRAINT `commandes_articles_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Contraintes pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD CONSTRAINT `encheres_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `encheres_ibfk_2` FOREIGN KEY (`acheteur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`acheteur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`acheteur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
