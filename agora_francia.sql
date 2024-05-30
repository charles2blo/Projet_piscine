-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 30 mai 2024 à 22:52
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
(7, 3, 'Charles', 'De Blauwe', '41 Rue Greffeulhe', '', 'Levallois-Perret', '92300', 'France', '0123456789');

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
(9, 'Madrid', 'Jude Bellingham', 'objets_art', 90.00, 10, 1, '2024-05-29 15:28:16', 'negociation', 'bon etat', 'uploads/image7.jpg', '', '2024-05-30 20:13:45'),
(11, 'azer', 'oioi', 'objets_art', 100.00, 10, 1, '2024-05-29 16:07:57', 'immediate', 'neuf sans etiquette', 'uploads/Capture d\'écran 2023-03-17 094515.png', '', '2024-05-30 20:13:45'),
(12, 'René', 'DIDI', 'meubles', 100.00, 2, 1, '2024-05-30 20:14:24', 'enchere', 'neuf avec etiquette', 'uploads/panda.jpg', '', '2024-05-30 20:21:54');

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
  `adresse_livraison` text DEFAULT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('en attente','expédiée','livrée','annulée') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `acheteur_id`, `article_id`, `quantite`, `prix_total`, `adresse_livraison`, `date_commande`, `status`) VALUES
(1, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:45:36', 'en attente'),
(2, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:46:15', 'en attente'),
(3, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:47:00', 'en attente'),
(4, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:55:30', 'en attente'),
(5, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:57:18', 'en attente'),
(6, 1, NULL, NULL, 123.00, NULL, '2024-05-29 09:57:27', 'en attente'),
(7, 1, NULL, NULL, 23.00, NULL, '2024-05-29 09:58:56', 'en attente'),
(8, 1, NULL, NULL, 400.00, NULL, '2024-05-29 10:48:28', 'en attente'),
(9, 1, NULL, NULL, 23.00, NULL, '2024-05-29 10:53:59', 'en attente'),
(10, 1, NULL, NULL, 123.00, NULL, '2024-05-29 10:59:30', 'en attente'),
(11, 1, NULL, NULL, 23.00, NULL, '2024-05-29 11:00:30', 'en attente'),
(12, 1, NULL, NULL, 123.00, NULL, '2024-05-29 11:09:01', 'en attente'),
(13, 1, NULL, NULL, 200.00, NULL, '2024-05-29 11:09:45', 'en attente'),
(14, 1, NULL, NULL, 200.00, NULL, '2024-05-29 11:20:25', 'en attente'),
(15, 1, NULL, NULL, 400.00, NULL, '2024-05-29 11:23:26', 'en attente'),
(16, 1, NULL, NULL, 100.00, NULL, '2024-05-29 13:32:15', 'en attente'),
(17, 1, NULL, NULL, 200.00, NULL, '2024-05-29 13:34:23', 'en attente'),
(18, 1, NULL, NULL, 75.00, NULL, '2024-05-29 13:45:45', 'en attente'),
(19, 1, NULL, NULL, 200.00, NULL, '2024-05-29 14:27:07', 'en attente'),
(20, 3, NULL, NULL, 0.00, NULL, '2024-05-29 14:47:56', 'en attente'),
(21, 3, NULL, NULL, 0.00, NULL, '2024-05-29 14:48:01', 'en attente'),
(22, 3, NULL, NULL, 25.00, NULL, '2024-05-29 14:48:18', 'en attente'),
(23, 3, NULL, NULL, 25.00, NULL, '2024-05-29 14:51:13', 'en attente'),
(24, 1, NULL, NULL, 55.00, NULL, '2024-05-29 15:02:06', 'en attente'),
(25, 1, NULL, NULL, 50.00, NULL, '2024-05-29 15:10:05', 'en attente'),
(26, 1, NULL, NULL, 1000.00, NULL, '2024-05-29 15:30:54', 'en attente'),
(27, 1, NULL, NULL, 3000.00, NULL, '2024-05-29 15:34:38', 'en attente'),
(28, 1, NULL, NULL, 2000.00, NULL, '2024-05-29 17:26:35', 'en attente');

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
(24, 28, 8, 2);

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
(3, 6, NULL, 'L\'utilisateur Blaise Matuidi a demandé à devenir vendeur.', '2024-05-30 20:50:32', 1);

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
(6, 'Matuidi', 'Blaise', 'a@gmail.com', '$2y$10$UerQSj5FdiNpm8M5WBvrMeVKlh4TM2Kvk5WXDbdphVvPkag7Wjsju', NULL, NULL, NULL, NULL, 'vendeur', 'uploads/stephan.jpg', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `commandes_articles`
--
ALTER TABLE `commandes_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
