SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `ledenadministratie` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ledenadministratie`;

CREATE TABLE `bookyear` (
  `id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bookyear` (`id`, `year`, `description`) VALUES
(1, 2025, 'Boekjaar 2025');

CREATE TABLE `contributions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `member_type` int(11) NOT NULL,
  `bookyear_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Hoeveel een lid nog moet betalen';

CREATE TABLE `family` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `street` varchar(255) NOT NULL,
  `house_number` varchar(10) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Nederland'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `family_members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int(11) NOT NULL,
  `member_type_id` int(11) NOT NULL,
  `family_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `member_type` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `minimum_age` int(11) DEFAULT NULL,
  `maximum_age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `member_type` (`id`, `description`, `discount_percentage`, `minimum_age`, `maximum_age`) VALUES
(1, 'Jeugd', 55.00, 0, 7),
(2, 'Aspirant', 40.00, 8, 12),
(3, 'Junior', 25.00, 13, 17),
(4, 'Senior', 0.00, 18, 50),
(5, 'Oudere', 45.00, 51, NULL);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','penningmeester','secretaris') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$2pQf2MVVUWn4CtGFQEn9P.89Raj9OC.cT744p90RVP9lAORIGRkUC', 'admin'),
(2, 'secretaris', '$2y$10$6APhdOk0ENHkUYCDivUptO36ad4.M3.GNpgR77EsbIYGzRFyPW34q', 'secretaris'),
(3, 'penningmeester', '$2y$10$9sP18WCIL.On5aTUb9CqX.y53KGAw9ykrnwbmLXsqdrM5fiPzIcD.', 'penningmeester');


ALTER TABLE `bookyear`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year_unique` (`year`);

ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookyear_id` (`bookyear_id`),
  ADD KEY `member_id` (`member_id`) USING BTREE,
  ADD KEY `member_type` (`member_type`);

ALTER TABLE `family`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_type_id` (`member_type_id`),
  ADD KEY `family_id` (`family_id`);

ALTER TABLE `member_type`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);


ALTER TABLE `bookyear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `member_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;


ALTER TABLE `contributions`
  ADD CONSTRAINT `contributie_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `family_members` (`id`),
  ADD CONSTRAINT `contributions_ibfk_1` FOREIGN KEY (`member_type`) REFERENCES `member_type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contributions_bookyear` FOREIGN KEY (`bookyear_id`) REFERENCES `bookyear` (`id`) ON DELETE SET NULL;

ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`member_type_id`) REFERENCES `member_type` (`id`),
  ADD CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`family_id`) REFERENCES `family` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
