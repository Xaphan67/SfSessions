-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage des données de la table sfsessions.categorie : ~0 rows (environ)

-- Listage des données de la table sfsessions.doctrine_migration_versions : ~1 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20230918115045', '2023-09-18 11:51:02', 584);

-- Listage des données de la table sfsessions.formation : ~3 rows (environ)
INSERT INTO `formation` (`id`, `nom`) VALUES
	(1, 'Formation 1'),
	(2, 'Formation 2'),
	(3, 'Formation 3');

-- Listage des données de la table sfsessions.messenger_messages : ~0 rows (environ)

-- Listage des données de la table sfsessions.module : ~0 rows (environ)

-- Listage des données de la table sfsessions.programme : ~0 rows (environ)

-- Listage des données de la table sfsessions.session : ~6 rows (environ)
INSERT INTO `session` (`id`, `formation_id`, `nom`, `places`, `date_debut`, `date_fin`) VALUES
	(1, 1, 'Initiation Compatibilité', 10, '2023-06-10', '2023-07-20'),
	(2, 1, 'Initiation à Word et Excel', 8, '2023-06-17', '2023-06-29'),
	(3, 1, 'Initiation Infographie (PS, INDD, AI)', 10, '2023-06-17', '2023-07-20'),
	(4, 1, 'Perfectionnement Word, Excel, Powerpoint', 6, '2023-07-08', '2023-07-12'),
	(5, 1, 'Initiation Bureautoque et infographie', 10, '2023-07-12', '2023-08-07'),
	(6, 1, 'Initiation en PHP / SQL', 12, '2023-09-01', '2023-12-12');

-- Listage des données de la table sfsessions.session_stagiaire : ~0 rows (environ)

-- Listage des données de la table sfsessions.stagiaire : ~1 rows (environ)
INSERT INTO `stagiaire` (`id`, `prenom`, `nom`, `sexe`, `date_naissance`, `ville`, `email`, `telephone`) VALUES
	(1, 'Cédric', 'FALDA', 'H', '1987-07-29', 'HANGENBIETEN', 'cedric.falda@gmail.com', '0604190584');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
