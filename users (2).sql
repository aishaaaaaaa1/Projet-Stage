-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 29 juil. 2025 à 04:41
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
-- Base de données : `users`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ajouter_au_panier` (IN `p_commande_id` INT, IN `p_produit_id` INT, IN `p_quantite` INT)   BEGIN
    DECLARE v_prix DECIMAL(10, 2);
    
    -- Récupérer le prix actuel du produit
    SELECT prix INTO v_prix FROM produits WHERE id = p_produit_id;
    
    -- Ajouter ou mettre à jour la ligne de commande
    INSERT INTO lignes_commande (commande_id, produit_id, quantite, prix_unitaire)
    VALUES (p_commande_id, p_produit_id, p_quantite, v_prix)
    ON DUPLICATE KEY UPDATE quantite = quantite + p_quantite;
    
    -- Mettre à jour le montant total de la commande
    UPDATE commandes 
    SET montant_total = (
        SELECT SUM(prix_unitaire * quantite) 
        FROM lignes_commande 
        WHERE commande_id = p_commande_id
    )
    WHERE id = p_commande_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `sous_categorie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `sous_categorie`) VALUES
(1, 'Sandwichs', ' Déjeuner '),
(2, 'Quiches & Pizzas', ' Déjeuner '),
(3, 'Patisseries individuelles', ' Pâtisserie '),
(4, 'Boisson', 'Soda'),
(5, ' Viennoiserie', 'Petit-déjeuner ');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `type` varchar(20) NOT NULL,
  `adresse_livraison` text DEFAULT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `methode_paiement` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `user_id`, `date_commande`, `type`, `adresse_livraison`, `montant_total`, `methode_paiement`) VALUES
(1, NULL, '2025-07-21 08:45:07', 'en ligne', 'N/A', 202.60, NULL),
(2, NULL, '2025-07-21 08:52:30', 'en ligne', 'N/A', 32.10, NULL),
(3, NULL, '2025-07-21 08:59:44', 'en ligne', 'N/A', 9.80, NULL),
(4, NULL, '2025-07-21 10:43:17', 'en ligne', '102', 32.00, NULL),
(5, 33, '2025-07-21 10:55:30', 'en ligne', '102', 14.30, NULL),
(6, 33, '2025-07-22 22:45:01', 'en ligne', '102', 31.90, NULL),
(7, 39, '2025-07-23 05:46:16', 'en ligne', 'N/A', 7.00, NULL),
(8, 39, '2025-07-23 06:13:43', 'en ligne', '101', 9.50, NULL),
(9, 39, '2025-07-25 22:24:50', 'en ligne', '101', 32.10, NULL),
(10, 39, '2025-07-26 02:38:23', 'en ligne', '103', 6.00, NULL),
(11, 41, '2025-07-29 03:36:54', 'en ligne', '101', 16.00, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `description` text NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_creation` date NOT NULL,
  `date_echeance` date NOT NULL,
  `statut` varchar(20) DEFAULT 'impayé',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `id_etudiant`, `description`, `montant`, `date_creation`, `date_echeance`, `statut`, `created_at`, `updated_at`) VALUES
(5, 33, 'Réservation chambre 2 - 2025-07-25 (3 mois)', 240.00, '2025-07-21', '2025-08-20', 'Payée', '2025-07-21 09:34:56', '2025-07-21 09:56:05'),
(6, 39, 'Réservation chambre 1 - 2025-07-23 (1 mois)', 50.00, '2025-07-23', '2025-08-22', 'Payée', '2025-07-23 05:13:59', '2025-07-23 05:18:38'),
(7, 39, 'Réservation chambre 10 - 2025-07-25 (1 mois)', 111.00, '2025-07-25', '2025-08-24', 'Payée', '2025-07-25 21:41:22', '2025-07-25 21:45:47'),
(8, 39, 'Réservation chambre 2 - 2026-01-26 (1 mois)', 80.00, '2025-07-26', '2025-08-25', 'Payée', '2025-07-26 01:40:20', '2025-07-26 01:40:26'),
(9, 41, 'Réservation chambre 13 - 2025-07-29 (1 mois)', 420.00, '2025-07-29', '2025-08-28', 'Payée', '2025-07-29 01:36:09', '2025-07-29 01:36:23'),
(10, 41, 'Réservation chambre 11 - 2025-07-29 (1 mois)', 80.00, '2025-07-29', '2025-08-28', 'Payée', '2025-07-29 02:02:42', '2025-07-29 02:02:57');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `annee_scolaire` varchar(10) NOT NULL,
  `filiere` varchar(100) NOT NULL,
  `niveau` varchar(20) NOT NULL,
  `date_inscription` date NOT NULL,
  `statut` varchar(20) DEFAULT 'en attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lignes_commande`
--

CREATE TABLE `lignes_commande` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) DEFAULT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lignes_commande`
--

INSERT INTO `lignes_commande` (`id`, `commande_id`, `produit_id`, `quantite`, `prix_unitaire`) VALUES
(1, 1, 5, 5, 5.20),
(2, 1, 4, 8, 2.50),
(3, 1, 3, 14, 4.50),
(4, 1, 1, 2, 12.50),
(5, 1, 2, 7, 9.80),
(6, 2, 1, 1, 12.50),
(7, 2, 2, 2, 9.80),
(8, 3, 2, 1, 9.80),
(9, 4, 1, 1, 12.50),
(10, 4, 2, 1, 9.80),
(11, 4, 3, 1, 4.50),
(12, 4, 5, 1, 5.20),
(13, 5, 2, 1, 9.80),
(14, 5, 3, 1, 4.50),
(15, 6, 2, 3, 9.80),
(16, 6, 4, 1, 2.50),
(17, 7, 3, 1, 4.50),
(18, 7, 4, 1, 2.50),
(19, 8, 3, 1, 4.50),
(20, 8, 4, 2, 2.50),
(21, 9, 1, 1, 12.50),
(22, 9, 2, 2, 9.80),
(23, 10, 21, 1, 2.00),
(24, 10, 22, 1, 4.00),
(25, 11, 3, 3, 4.50),
(26, 11, 4, 1, 2.50);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `categorie_id`, `image_url`) VALUES
(1, 'Le sandwich dieppois', '  ', 12.50, 1, 'uploads/5149_1.webp'),
(2, 'La part de pizza royale', ' ', 9.80, 2, 'uploads/2023_pack_cat_pizza_plaque_royal_part_plu_33963.webp'),
(3, 'éclair au chocolat', '  ', 4.50, 3, 'uploads/76_1.webp'),
(4, 'Cappuccino', '  ', 2.50, 4, 'uploads/2021_pack_dri_capuccino_2.webp'),
(5, 'Le muffin au chocolat blanc cranberrie', '   ', 5.20, 5, 'uploads/5258.webp'),
(8, 'Le wrap saumon', ' ', 10.00, 1, 'uploads/wrap_saumon_-_plu_33121.webp'),
(9, 'Le sandwich grainé poulet', ' ', 12.00, 1, 'uploads/10809_1.webp'),
(10, 'Le sandwich brioché grainé poulet avocat', ' ', 8.00, 1, 'uploads/swd_poulet_avocat.webp'),
(11, 'Le nordique avocat', '  ', 10.00, 1, 'uploads/2022_pack_cat_sandwich_nordique_avocat_saumon.webp'),
(12, 'La part de pizza provençale', ' ', 6.00, 2, 'uploads/2023_pack_cat_pizza_plaque_provencale_part_plu_33965.webp'),
(13, 'La part de pizza 3 fromages', '  ', 8.00, 2, 'uploads/2023_pack_cat_pizza_plaque_3_fromages_part_plu_33955.webp'),
(14, 'La quiche aux légumes individuelle', '  ', 10.00, 2, 'uploads/quiche_aux_le_gumes.webp'),
(15, 'La quiche lorraine', ' ', 5.00, 2, 'uploads/77_-_quiche_lorraine.webp'),
(16, 'La part de flan nature', ' ', 2.00, 3, 'uploads/72_1.webp'),
(17, 'La part de moelleux au chocolat', ' ', 2.00, 3, 'uploads/3092_1.webp'),
(18, 'opéra', ' ', 2.00, 3, 'uploads/op_ra.webp'),
(19, 'Le cheesecake caramel', ' ', 3.00, 3, 'uploads/2024_pack_pas_cheesecake_caramel_part.webp'),
(20, 'La crêpe pâte à tartiner', ' ', 4.00, 3, 'uploads/cr_pe_p_te_tartiner.webp'),
(21, 'Expresso', ' ', 2.00, 4, 'uploads/2021_pack_dri_expresso_3.webp'),
(22, 'Onctueux viennois', ' ', 4.00, 4, 'uploads/2021_pack_dri_onctueux_viennois_1_1.webp'),
(23, 'Fanta orange 33cl', '  ', 4.00, 4, 'uploads/canette_fanta.webp'),
(24, 'Coca Cola cherry 50cl', ' ', 3.00, 4, 'uploads/paul_boissons_800x800px_.webp'),
(25, 'Le pain au chocolat', ' ', 4.00, 5, 'uploads/1_1 (1).webp'),
(26, 'Le muffin au chocolat noir', ' ', 5.00, 5, 'uploads/5278_1.webp'),
(27, 'Le croissant aux amandes', ' ', 10.00, 5, 'uploads/8_1.webp'),
(28, 'La gourmandise', ' ', 3.00, 5, 'uploads/141_1 (1).webp'),
(29, 'Le croissant', '  ', 6.00, 5, 'uploads/2_1.webp');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `status` varchar(50) DEFAULT 'En attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nb_mois` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `user_id`, `room_id`, `date_reservation`, `time_start`, `time_end`, `status`, `created_at`, `nb_mois`) VALUES
(8, 39, 8, '2025-07-25', '22:38:00', '22:39:00', 'Refusée', '2025-07-25 21:38:55', 1),
(9, 39, 10, '2025-07-25', '22:40:00', '22:43:00', 'Acceptée', '2025-07-25 21:41:03', 1),
(12, 41, 13, '2025-07-29', '02:35:00', '02:37:00', 'Acceptée', '2025-07-29 01:35:16', 1),
(13, 41, 11, '2025-07-29', '03:02:00', '03:07:00', 'Acceptée', '2025-07-29 02:02:27', 1);

-- --------------------------------------------------------

--
-- Structure de la table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Disponible',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `room`
--

INSERT INTO `room` (`room_id`, `room_number`, `category_id`, `description`, `image`, `price`, `status`, `created_at`) VALUES
(8, '104', 3, ' ', 'uploads/room_1753400528.jpg', 200.00, 'Occupée', '2025-07-24 23:42:08'),
(10, '105', 1, ' ', 'uploads/room_1753400528.jpg', 111.00, 'Disponible', '2025-07-24 23:46:56'),
(11, '101', 1, ' ', 'uploads/room_1753400528.jpg', 80.00, 'Disponible', '2025-07-28 23:50:50'),
(12, '102', 2, ' ', 'uploads/room_1753400528.jpg', 160.00, 'Disponible', '2025-07-28 23:51:11'),
(13, '103', 3, ' ', 'uploads/room_1753400528.jpg', 420.00, 'Disponible', '2025-07-28 23:51:41');

-- --------------------------------------------------------

--
-- Structure de la table `room_category`
--

CREATE TABLE `room_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL COMMENT 'Nombre de personnes que la chambre peut accueillir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `room_category`
--

INSERT INTO `room_category` (`category_id`, `category_name`, `capacity`) VALUES
(1, '1 person', 1),
(2, '2 person', 2),
(3, '4 person', 4);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `annee_scolaire` varchar(10) NOT NULL,
  `filiere` varchar(100) NOT NULL,
  `niveau` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `annee_scolaire`, `filiere`, `niveau`) VALUES
(28, 'Insaf', 'Insafkazti@gmail.com', '$2y$10$lQrhtQ2en6o4SsJwlL1lcOTsKSF7/CgDoVrHoggZAXGDaZDN/mEOy', '2024', 'CDL', 'BAC+1'),
(29, 'Mustapha', 'muwtaphashahrazad@gmail.com', '$2y$10$k/EHry9BrycY2OK/S6dRpu4.LSONjjm7NgexfhCoqrQ4YC8YH.Zpi', '2024', 'CDL', 'BAC+1'),
(33, 'aicha', '.', '$2y$10$GnNCyzSl8OQIv.b1Gt2NcONp2drIsp5PTWCWyoW0xulZT7HMmwI5e', '2024', 'CDL', 'BAC+1'),
(34, 'Sara', 'elghalisara12@gmail.com', '$2y$10$ZkhHk.KhfLsrniVgYETX1eu1hMSnQhhY79tNGVT0Unthv88ol7XUG', '2024', 'CDL', 'BAC+1'),
(35, 'aicha', 'outajeraicha12@gmail.com', '$2y$10$R2KuDMyqIMIzjgUgSIKm/.5cpFTPOwf5Rt/pCEmGN1pGwnf4TW3p6', '2024', 'CDL', 'BAC+1'),
(36, 'sara', 'elghalisara6@gmail.com', '$2y$10$wtSajs32zvb1Re8nNx/so.uPMF.tt9WJJ27tiKPXHz2s95O8gk2G.', '2024', 'CDL', 'BAC+1'),
(37, 'KHADIJA', 'khadijaoutajer1@gmail.com', '$2y$10$B71O/ZEOQJW.I7Z4pZQVJ.srDbZp2JVprWzNNSxnTKkTD07xAHfEO', '2024', 'CDL', 'BAC+1'),
(38, 'Mustapha', 'outajermustapha1@gmail.com', '$2y$10$Y5J0BTxPglj3EK0JnME2B./dYQ124verxJ5IBplCOv4vFpUZviDyG', '2024', 'CDL', 'BAC+1'),
(39, 'aicha', 'aichaoutajer2@gmail.com', '$2y$10$dThR0827HhkIvo3k5yO2D.FkvIgZmK6uK/PsMDqqrRMCeXhQupT1W', '2024', 'CDL', 'BAC+1'),
(40, 'sara', 'sarahsaraah4123@gmail.com', '$2y$10$rxqAJVcxg1n6ToMsWzQdqe/zggkq7Bi5bp7zQMB7Jfoj8j2MbEegS', '2024', 'CDL', 'BAC+1'),
(41, 'Ayoub', 'rakanbinibini@gmail.com', '$2y$10$X6bwEJr9xMKJ/ZCZT8.Jh.ufCBqiNzuPDKWfbjeRBeSze1dradL7S', '2024', 'CDL', 'BAC+1');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_factures_user` (`id_etudiant`);

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inscriptions_user` (`id_etudiant`);

--
-- Index pour la table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commande_id` (`commande_id`),
  ADD KEY `produit_id` (`produit_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Index pour la table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `room_category`
--
ALTER TABLE `room_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `room_category`
--
ALTER TABLE `room_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `fk_factures_user` FOREIGN KEY (`id_etudiant`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `fk_inscriptions_user` FOREIGN KEY (`id_etudiant`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD CONSTRAINT `lignes_commande_ibfk_1` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`),
  ADD CONSTRAINT `lignes_commande_ibfk_2` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`);

--
-- Contraintes pour la table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `room_category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
