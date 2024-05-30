-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 30 mai 2024 à 15:01
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `adresses`
--

INSERT INTO `adresses` (`id`, `utilisateur_id`, `nom`, `prenom`, `adresse_ligne1`, `adresse_ligne2`, `ville`, `code_postal`, `pays`, `numero_telephone`) VALUES
(6, 1, 'Schwartz', 'Oscar', '33 avenue de la résistance', '', 'Chaville', '92370', 'France', '0789311759'),
(7, 3, 'Charles', 'De Blauwe', '41 Rue Greffeulhe', '', 'Levallois-Perret', '92300', 'France', '0123456789');

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `description` text,
  `categorie` enum('meubles','objets_art','accessoire_vip','materiels_scolaires') DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `vendeur_id` int(11) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type_vente` enum('immediate','negociation','enchere') DEFAULT NULL,
  `etat` enum('neuf avec etiquette','neuf sans etiquette','tres bon etat','bon etat','satisfaisant') DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `nom`, `description`, `categorie`, `prix`, `quantite`, `vendeur_id`, `date_ajout`, `type_vente`, `etat`, `photo`, `video`) VALUES
(1, 'Oscar', 'caca', 'meubles', '123.00', 0, 1, '2024-05-28 22:09:46', 'immediate', 'bon etat', 'uploads/photo.jpg', ''),
(2, 'caca', 'bien coulante', 'accessoire_vip', '200.00', 91, 1, '2024-05-28 22:13:19', 'immediate', 'bon etat', 'uploads/photo.jpg', ''),
(3, 'Oscar', 'caca', 'meubles', '23.00', 0, 1, '2024-05-29 09:10:34', 'immediate', 'bon etat', 'uploads/photo.jpg', ''),
(4, 'Charles', 'pipi', 'objets_art', '10.00', 0, 1, '2024-05-29 09:21:47', 'negociation', 'bon etat', 'uploads/IMG_0061.jpg', ''),
(5, 'Undertaker', 'Gors caca poilue', 'objets_art', '1000.00', 0, 1, '2024-05-29 13:29:21', 'immediate', 'bon etat', 'uploads/stephan.jpg', ''),
(6, 'Pastéis de Nata', 'Miam', 'objets_art', '25.00', 17, 1, '2024-05-29 13:41:53', 'negociation', 'bon etat', 'uploads/image6.jpg', ''),
(7, 'Arduino', 'Nano bot', 'objets_art', '30.00', 0, 3, '2024-05-29 14:32:09', 'enchere', 'bon etat', 'uploads/Capture d\'écran 2023-05-08 172500.png', ''),
(8, 'Sac Jacquemus', 'Trait bo', 'accessoire_vip', '1000.00', 4, 1, '2024-05-29 15:27:19', 'enchere', 'bon etat', 'uploads/image8.jpg', ''),
(9, 'Madrid', 'Jude Bellingham', 'objets_art', '90.00', 10, 1, '2024-05-29 15:28:16', 'negociation', 'bon etat', 'uploads/image7.jpg', ''),
(10, 'Medo', 'salope', 'meubles', '100.00', 1, 1, '2024-05-29 15:31:25', 'immediate', 'bon etat', 'uploads/Capture d\'écran 2024-05-21 230150.png', ''),
(11, 'azer', 'oioi', 'objets_art', '100.00', 10, 1, '2024-05-29 16:07:57', 'immediate', 'neuf sans etiquette', 'uploads/Capture d\'écran 2023-03-17 094515.png', ''),
(12, 'yugr', 'zizi', 'meubles', '74.00', 5, 1, '2024-05-29 16:13:47', 'negociation', 'neuf avec etiquette', 'uploads/image-drole-animal_23-2151179430.jpg.avif', ''),
(13, 'gros zgeg', 'ezfzef', 'meubles', '65.00', 87, 5, '2024-05-30 09:22:44', 'negociation', 'neuf sans etiquette', 'uploads/image-drole-animal_23-2151179430.jpg.avif', ''),
(14, 'TEST MESSAGE', 'BONJOUR', 'objets_art', '89.00', 8, 5, '2024-05-30 10:24:49', 'negociation', 'neuf avec etiquette', 'uploads/IMG_8588.JPG', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `cartes`
--

INSERT INTO `cartes` (`id`, `utilisateur_id`, `type_carte`, `numero_carte`, `nom_carte`, `expiration`, `code_securite`) VALUES
(8, 1, 'Visa', '0397449277551832', 'Schwartz', '0000-00-00', '017'),
(13, 1, 'Visa', '1111111111111111', 'Schwartz', '2024-11-01', '098'),
(14, 3, 'AmericanExpress', '3749052690170009', 'De Blauwe', '2026-06-01', '000');

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
  `adresse_livraison` text,
  `date_commande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('en attente','expédiée','livrée','annulée') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `acheteur_id`, `article_id`, `quantite`, `prix_total`, `adresse_livraison`, `date_commande`, `status`) VALUES
(1, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:45:36', 'en attente'),
(2, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:46:15', 'en attente'),
(3, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:47:00', 'en attente'),
(4, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:55:30', 'en attente'),
(5, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:57:18', 'en attente'),
(6, 1, NULL, NULL, '123.00', NULL, '2024-05-29 09:57:27', 'en attente'),
(7, 1, NULL, NULL, '23.00', NULL, '2024-05-29 09:58:56', 'en attente'),
(8, 1, NULL, NULL, '400.00', NULL, '2024-05-29 10:48:28', 'en attente'),
(9, 1, NULL, NULL, '23.00', NULL, '2024-05-29 10:53:59', 'en attente'),
(10, 1, NULL, NULL, '123.00', NULL, '2024-05-29 10:59:30', 'en attente'),
(11, 1, NULL, NULL, '23.00', NULL, '2024-05-29 11:00:30', 'en attente'),
(12, 1, NULL, NULL, '123.00', NULL, '2024-05-29 11:09:01', 'en attente'),
(13, 1, NULL, NULL, '200.00', NULL, '2024-05-29 11:09:45', 'en attente'),
(14, 1, NULL, NULL, '200.00', NULL, '2024-05-29 11:20:25', 'en attente'),
(15, 1, NULL, NULL, '400.00', NULL, '2024-05-29 11:23:26', 'en attente'),
(16, 1, NULL, NULL, '100.00', NULL, '2024-05-29 13:32:15', 'en attente'),
(17, 1, NULL, NULL, '200.00', NULL, '2024-05-29 13:34:23', 'en attente'),
(18, 1, NULL, NULL, '75.00', NULL, '2024-05-29 13:45:45', 'en attente'),
(19, 1, NULL, NULL, '200.00', NULL, '2024-05-29 14:27:07', 'en attente'),
(20, 3, NULL, NULL, '0.00', NULL, '2024-05-29 14:47:56', 'en attente'),
(21, 3, NULL, NULL, '0.00', NULL, '2024-05-29 14:48:01', 'en attente'),
(22, 3, NULL, NULL, '25.00', NULL, '2024-05-29 14:48:18', 'en attente'),
(23, 3, NULL, NULL, '25.00', NULL, '2024-05-29 14:51:13', 'en attente'),
(24, 1, NULL, NULL, '55.00', NULL, '2024-05-29 15:02:06', 'en attente'),
(25, 1, NULL, NULL, '50.00', NULL, '2024-05-29 15:10:05', 'en attente'),
(26, 1, NULL, NULL, '1000.00', NULL, '2024-05-29 15:30:54', 'en attente'),
(27, 1, NULL, NULL, '3000.00', NULL, '2024-05-29 15:34:38', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_articles`
--

CREATE TABLE `commandes_articles` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commandes_articles`
--

INSERT INTO `commandes_articles` (`id`, `commande_id`, `article_id`, `quantite`) VALUES
(2, 5, 1, 1),
(3, 6, 1, 1),
(4, 7, 3, 1),
(5, 8, 2, 2),
(6, 9, 3, 1),
(7, 10, 1, 1),
(8, 11, 3, 1),
(9, 12, 1, 1),
(10, 13, 2, 1),
(11, 14, 2, 1),
(12, 15, 2, 2),
(13, 16, 4, 10),
(14, 17, 2, 1),
(15, 18, 6, 3),
(16, 19, 2, 1),
(17, 22, 6, 1),
(18, 23, 6, 1),
(19, 24, 6, 1),
(20, 24, 7, 1),
(21, 25, 6, 2),
(22, 26, 8, 1),
(23, 27, 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `encheres`
--

CREATE TABLE `encheres` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `montant_offre` decimal(10,2) DEFAULT NULL,
  `date_offre` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text,
  `date_evaluation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messagerie`
--

INSERT INTO `messagerie` (`id`, `user_id`, `vendeur_id`, `article_id`, `message`, `timestamp`) VALUES
(1, 6, 5, 14, 'Prix proposé: 80 € - Message: erer', '2024-05-30 10:45:13'),
(2, 6, 5, 14, 'dhfd', '2024-05-30 10:45:22'),
(3, 6, 5, 14, 'Prix proposé: 80 € - Message: dhjc', '2024-05-30 10:53:39'),
(4, 5, 5, 14, 'Votre offre a été acceptée. Vous pouvez maintenant ajouter l\'article à votre panier.', '2024-05-30 10:54:29'),
(5, 6, 5, 14, 'Prix proposé: 80 € - Message: frzfzefazefzaefazefazefz', '2024-05-30 11:23:04'),
(6, 5, 5, 14, 'Votre offre a été acceptée. Vous pouvez maintenant ajouter l\'article à votre panier.', '2024-05-30 11:37:02'),
(7, 6, 5, 14, 'bonjour\r\n', '2024-05-30 12:20:59'),
(8, 6, 1, 6, 'Prix proposé: 23 € - Message: Salut ma couille fais moi un prix', '2024-05-30 12:21:53'),
(9, 6, 1, 6, 'allé stp\r\n', '2024-05-30 12:22:03'),
(10, 6, 5, 14, 'bonjour', '2024-05-30 12:28:15'),
(11, 6, 5, 14, 'bonjour', '2024-05-30 12:28:20'),
(12, 6, 5, 14, 'Prix proposé: 27 € - Message: h', '2024-05-30 12:29:58'),
(13, 5, 5, 14, 'Votre offre a été acceptée. Vous pouvez maintenant ajouter l\'article à votre panier.', '2024-05-30 12:30:39'),
(14, 5, 5, 14, 'Felicitation', '2024-05-30 12:30:48');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `message` text,
  `date_notification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `acheteur_id`, `article_id`, `quantite`) VALUES
(34, 3, 2, 2),
(46, 5, 8, NULL),
(47, 5, 6, 2),
(48, 6, NULL, 1),
(49, 6, NULL, 1),
(50, 6, NULL, 1),
(51, 6, NULL, 1),
(52, 5, NULL, 1),
(53, 5, 2, 1),
(54, 6, NULL, 1),
(55, 6, NULL, 1),
(56, 6, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ras1`
--

CREATE TABLE `ras1` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','vendor','buyer') NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` text,
  `phone` varchar(50) DEFAULT NULL,
  `payment_method` enum('visa','mastercard','amex','paypal') DEFAULT NULL,
  `card_number` varchar(20) DEFAULT NULL,
  `card_name` varchar(255) DEFAULT NULL,
  `card_expiry` date DEFAULT NULL,
  `card_cvv` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ras2`
--

CREATE TABLE `ras2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ras3`
--

CREATE TABLE `ras3` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `ras4`
--

CREATE TABLE `ras4` (
  `id` int(11) NOT NULL,
  `name` enum('Meubles et objets d’art','Accessoire VIP','Matériels scolaires') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `acheteur_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `prix_final` decimal(10,2) DEFAULT NULL,
  `date_transaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type_transaction` enum('immediate','negociation','enchere') DEFAULT NULL,
  `status` enum('en cours','complété','annulé') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `adresse` text,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `type_utilisateur` enum('admin','vendeur','acheteur') DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `image_fond` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `adresse`, `ville`, `code_postal`, `pays`, `type_utilisateur`, `photo`, `image_fond`) VALUES
(1, 'Oscar', 'Schwartz', 'oscar.schwartz@edu.ece.fr', '$2y$10$8LYHsiW8GCM8bpJgQigL6.nKj2OTgHvPcgWydpkj29wttrvwtZl3S', NULL, NULL, NULL, NULL, 'acheteur', 'uploads/bambino.jpg', NULL),
(3, 'Charles', 'De Blauwe', 'charles.deblauwe@edu.ece.fr', '$2y$10$jjY1oWhw/FtFAhM6YEdihOBvXoLarb8DAnKVThu4H.ZBpnNS.0F8u', NULL, NULL, NULL, NULL, 'acheteur', 'uploads/Capture d\'écran 2023-11-19 184206.png', NULL),
(4, 'Oscar', 'Schwartz', 'oscar.schwartz92@gmail.com', '$2y$10$NEoDbZ0gxbx20uKJeh0dkOYshLdP.u16x1hGJNh2vRDeBO56mIMGG', NULL, NULL, NULL, NULL, 'acheteur', NULL, NULL),
(5, 'Charles', 'de Blauwe', 'charles.de.blauwe@gmail.com', '$2y$10$uFDPCkhKpV/rxDRQ3shgreq8QzLMMfb33i2tHvS28LaKmYKoxoncS', NULL, NULL, NULL, NULL, 'acheteur', 'uploads/IMG_8588.JPG', NULL),
(6, 'a', 'a', 'a@gmail.com', '$2y$10$1aLZo5GOP5yduZrpyxZUEu5Amuf.Z.E/3FOUkWY.gMcFsnVnbitOi', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Index pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vendeur_id` (`vendeur_id`),
  ADD KEY `article_id` (`article_id`);

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
-- Index pour la table `ras1`
--
ALTER TABLE `ras1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `ras2`
--
ALTER TABLE `ras2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Index pour la table `ras3`
--
ALTER TABLE `ras3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Index pour la table `ras4`
--
ALTER TABLE `ras4`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `commandes_articles`
--
ALTER TABLE `commandes_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `encheres`
--
ALTER TABLE `encheres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `ras1`
--
ALTER TABLE `ras1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ras2`
--
ALTER TABLE `ras2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ras3`
--
ALTER TABLE `ras3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ras4`
--
ALTER TABLE `ras4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- Contraintes pour la table `messagerie`
--
ALTER TABLE `messagerie`
  ADD CONSTRAINT `messagerie_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `messagerie_ibfk_2` FOREIGN KEY (`vendeur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `messagerie_ibfk_3` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

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
-- Contraintes pour la table `ras2`
--
ALTER TABLE `ras2`
  ADD CONSTRAINT `ras2_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ras4` (`id`),
  ADD CONSTRAINT `ras2_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `ras1` (`id`);

--
-- Contraintes pour la table `ras3`
--
ALTER TABLE `ras3`
  ADD CONSTRAINT `ras3_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `ras2` (`id`);

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
