-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 22 mai 2025 à 17:06
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
-- Base de données : `gestion_absences`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

CREATE TABLE `absences` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_seance` int(11) NOT NULL,
  `type_absence` enum('justifiée','non justifiée') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `absences`
--

INSERT INTO `absences` (`id`, `id_etudiant`, `id_seance`, `type_absence`) VALUES
(2, 18, 1, 'non justifiée'),
(3, 24, 1, 'justifiée');

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `nom_classe` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `id_salle`, `nom_classe`) VALUES
(1, 1, 'tdia1'),
(10, 3, 'id'),
(11, 4, 'gi'),
(12, 5, 'tdia2'),
(13, 6, 'gi2');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_absence`
--

CREATE TABLE `demandes_absence` (
  `id` int(11) NOT NULL,
  `id_seance` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `motif` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `emploi_du_temps`
--

CREATE TABLE `emploi_du_temps` (
  `id` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `jour_semaine` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `id_salle` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_classe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `id_user`, `id_classe`) VALUES
(18, 47, NULL),
(19, 48, NULL),
(22, 54, NULL),
(23, 54, 1),
(24, 55, NULL),
(25, 55, 1),
(26, 56, NULL),
(27, 56, 1),
(28, 57, NULL),
(29, 57, 11),
(30, 58, NULL),
(31, 58, 11),
(32, 59, NULL),
(33, 59, 11),
(34, 61, NULL),
(35, 61, 12),
(36, 62, NULL),
(37, 62, 11),
(38, 64, NULL),
(39, 64, 11),
(40, 65, NULL),
(41, 65, 11),
(42, 66, NULL),
(43, 66, 13);

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `id_classe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id`, `nom`, `id_classe`) VALUES
(2, 'Java', 1),
(3, 'web', 1),
(4, 'python ', 1),
(5, 'web', 1),
(6, 'web', 1),
(7, 'web', 1),
(8, 'web', 1),
(9, 'industrie', 1),
(10, 'web', 10),
(11, 'java', 11),
(12, 'ai', 12),
(13, 'tec', 11);

-- --------------------------------------------------------

--
-- Structure de la table `professeurs`
--

CREATE TABLE `professeurs` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `professeurs`
--

INSERT INTO `professeurs` (`id`, `id_user`) VALUES
(7, 49),
(8, 50),
(9, 51),
(10, 60),
(11, 60),
(12, 63),
(13, 63);

-- --------------------------------------------------------

--
-- Structure de la table `prof_module_classe`
--

CREATE TABLE `prof_module_classe` (
  `id` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `volume_horaire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prof_module_classe`
--

INSERT INTO `prof_module_classe` (`id`, `id_professeur`, `id_module`, `id_classe`, `volume_horaire`) VALUES
(2, 8, 11, 11, 30);

-- --------------------------------------------------------

--
-- Structure de la table `retards`
--

CREATE TABLE `retards` (
  `id` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_seance` int(11) NOT NULL,
  `duree_retard` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `nom`) VALUES
(1, 'salle10'),
(2, 'amphi'),
(3, 'salle 1'),
(4, 'salle2'),
(5, 'salle5'),
(6, 'salle3');

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

CREATE TABLE `seances` (
  `id` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `date_seance` date DEFAULT NULL,
  `jour_semaine` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` (`id`, `id_classe`, `id_module`, `id_professeur`, `id_salle`, `date_seance`, `jour_semaine`, `heure_debut`, `heure_fin`) VALUES
(1, 1, 6, 7, 1, '2025-05-20', 'Mardi', '10:30:00', '12:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','professeur','etudiant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(47, 'Etu', 'Deux', 'etudiant2@fa.com', '$2y$12$e7pPx1DwDfD25LvDnlGnY.chREVhndrYVG1rDrw4.8zOWZqWBCWSe', 'etudiant'),
(48, 'Etu', 'Trois', 'etudiant3@fa.com', '$2y$12$VkzGogwOtjEy7poLrnM0Qel1Mp0AWPyB91jHb955mYAFtpquvAuW2', 'etudiant'),
(49, 'Prof', 'Un', 'prof1@fa.com', '$2y$12$k6fS3zEyvGXb7JyHuMa5VuOQUSZQxioOOaGDCnY5qggc3.sdbLTlG', 'professeur'),
(50, 'Prof', 'Deux', 'prof2@fa.com', '$2y$12$zl.oL/FNQwwLxKGUbSumAOpFm3TIntJ0rwaGRkom4.tvdmflCrG3e', 'professeur'),
(51, 'Prof', 'Trois', 'prof3@fa.com', '$2y$12$1LQ9aL/y.1HzN0zsiuoZ6u6NMzFGR6eHF5O6fMo4w3BnIKYwq4gwy', 'professeur'),
(52, 'Admin', 'un', 'admin2@fa.com', '$2y$12$1yvZfH/FolWAv0hfG/ulPu2LInGqYQM.SLh3H5xMNCmb/L69R9gMC', 'admin'),
(54, 'asahaad', 'kaoutar', 'kaoutar.asahaad@fa.com', '$2y$10$0f1yrvzaqFU8i0RJ5S1VI.Y71s.k4627BHmybyuHIvJd5Q/.ykI.K', 'etudiant'),
(55, 'z', 's', 's.z@fa.com', '$2y$10$3ogn0kwb2AuVaPVgn7JpmebWUdwBZPRyfJDiv7mGnH/IUPY49cQQS', 'etudiant'),
(56, 'ashhd', 'kaa', 'kaa.ashhd@fa.com', '$2y$10$Ymgylj88RMdlrlFx57KjG.eCdLvixLUl1lL6jjosndPvRVvAcce/6', 'etudiant'),
(57, 'x', 'x', 'x.x@fa.com', '$2y$10$ovaFVf00yKeEa0Qlp4iE1uI3rDfnEKS40aoMHF3cE0zVvAmhy.Lp.', 'etudiant'),
(58, 'aam', 'www', 'www.aam@ecole.com', '$2y$10$ZR0PHNcqblJHkLpMdVpWe.etZD651OYSvSjCTr2xunCPRJ1VdhPQq', 'etudiant'),
(59, 'mm', 'oo', 'oo.mm@ecole.com', '$2y$10$g6ktGhI3NmEHn4Jd3Gu7gefvbr1E8CJCy3jfsQVgVT0VapIlwYKi.', 'etudiant'),
(60, 'm', 'u', 'u.m@ecole.com', '$2y$10$RhIzYoADMxN.hqv86JFPpuj9i0b4hox4MgkkUH9ZJoxEsptjuGCQu', 'professeur'),
(61, 'e', 'sdfgh', 'sdfgh.e@fa.com', '$2y$10$vz2egGvG7F9n5SH64DUKMOEbQxXtVc3EY1FKwyfRLfgg0D/k9v.Ba', 'etudiant'),
(62, 'as', 'ka', 'ka.as@fa.com', '$2y$10$iPOSTutLgHkbTk2lfo.AIudf5NUutfDu.8qGVQRDC3y.gYAAAaqBu', 'etudiant'),
(63, 'sdfgh', 'xcvbn', 'xcvbn.sdfgh@fa.com', '$2y$10$xUsG/fVfyUJ2Y.GdY.P/ouw4ziJlHIjp0QcJNbYpYTE90AinhnCwO', 'professeur'),
(64, 'spoong', 'poop', 'poop.spoong@fa.com', '$2y$10$E85JRU4vW2VGrIw5SDuSGO7NKVSF8m44sU1FxblOwCGJ.DSoVoL/C', 'etudiant'),
(65, 'n', 'nn', 'nn.n@fa.com', '$2y$10$kdUjZ6qY83VHTdzR9dqgvOOCM1xaNFPtzbvhU60MglkF.lGnINWdm', 'etudiant'),
(66, 'm', 'lkm', 'lkm.m@fa.com', '$2y$10$lQ4gN/KoUI.Q6JKp.qlb9uleMuMdVY777SDnKN3vvnLozcJzMb7Da', 'etudiant');

--
-- Déclencheurs `users`
--
DELIMITER $$
CREATE TRIGGER `ajouter_etudiant` AFTER INSERT ON `users` FOR EACH ROW BEGIN
  IF NEW.role = 'etudiant' THEN
    INSERT INTO etudiants (id_user) VALUES (NEW.id);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ajouter_professeur` AFTER INSERT ON `users` FOR EACH ROW BEGIN
  IF NEW.role = 'professeur' THEN
    INSERT INTO professeurs (id_user) VALUES (NEW.id);
  END IF;
END
$$
DELIMITER ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_seance` (`id_seance`);

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `demandes_absence`
--
ALTER TABLE `demandes_absence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_seance` (`id_seance`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_etudiant` (`id_etudiant`);

--
-- Index pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_professeur` (`id_professeur`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `professeurs`
--
ALTER TABLE `professeurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `prof_module_classe`
--
ALTER TABLE `prof_module_classe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_professeur` (`id_professeur`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `retards`
--
ALTER TABLE `retards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_seance` (`id_seance`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `seances`
--
ALTER TABLE `seances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_professeur` (`id_professeur`),
  ADD KEY `id_salle` (`id_salle`);

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
-- AUTO_INCREMENT pour la table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `demandes_absence`
--
ALTER TABLE `demandes_absence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `professeurs`
--
ALTER TABLE `professeurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `prof_module_classe`
--
ALTER TABLE `prof_module_classe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `retards`
--
ALTER TABLE `retards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `seances`
--
ALTER TABLE `seances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absences_ibfk_2` FOREIGN KEY (`id_seance`) REFERENCES `seances` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salles` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `demandes_absence`
--
ALTER TABLE `demandes_absence`
  ADD CONSTRAINT `demandes_absence_ibfk_1` FOREIGN KEY (`id_seance`) REFERENCES `seances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandes_absence_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandes_absence_ibfk_3` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD CONSTRAINT `emploi_du_temps_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `emploi_du_temps_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `emploi_du_temps_ibfk_3` FOREIGN KEY (`id_professeur`) REFERENCES `professeurs` (`id`),
  ADD CONSTRAINT `emploi_du_temps_ibfk_4` FOREIGN KEY (`id_salle`) REFERENCES `salles` (`id`);

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `etudiants_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `etudiants_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `professeurs`
--
ALTER TABLE `professeurs`
  ADD CONSTRAINT `professeurs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prof_module_classe`
--
ALTER TABLE `prof_module_classe`
  ADD CONSTRAINT `prof_module_classe_ibfk_1` FOREIGN KEY (`id_professeur`) REFERENCES `professeurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prof_module_classe_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prof_module_classe_ibfk_3` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `retards`
--
ALTER TABLE `retards`
  ADD CONSTRAINT `retards_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `retards_ibfk_2` FOREIGN KEY (`id_seance`) REFERENCES `seances` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `seances_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seances_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seances_ibfk_3` FOREIGN KEY (`id_professeur`) REFERENCES `professeurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seances_ibfk_4` FOREIGN KEY (`id_salle`) REFERENCES `salles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
