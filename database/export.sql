SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ledenadministratie`
--
CREATE DATABASE IF NOT EXISTS `ledenadministratie` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ledenadministratie`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bookyear`
--

CREATE TABLE `bookyear` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `bookyear`
--

INSERT INTO `bookyear` (`id`, `year`, `description`) VALUES
(1, 2025, 'Boekjaar 2025');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contributions`
--

CREATE TABLE `contributions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `member_type` int(11) NOT NULL,
  `bookyear_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `family`
--

CREATE TABLE `family` (
  `id` int(11) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `house_number` varchar(50) NOT NULL,
  `postal_code` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Nederland'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `family_members`
--

CREATE TABLE `family_members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int(11) NOT NULL,
  `family_member_type` enum('vader','moeder','zoon','dochter','oom','tante','neef','nicht','anders') NOT NULL,
  `member_type_id` int(11) NOT NULL,
  `family_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `member_type`
--

CREATE TABLE `member_type` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `minimum_age` int(11) DEFAULT NULL,
  `maximum_age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `member_type`
--

INSERT INTO `member_type` (`id`, `description`, `discount_percentage`, `minimum_age`, `maximum_age`) VALUES
(1, 'Jeugd', 55.00, 0, 7),
(2, 'Aspirant', 40.00, 8, 12),
(3, 'Junior', 25.00, 13, 17),
(4, 'Senior', 0.00, 18, 50),
(5, 'Oudere', 45.00, 51, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','secretaris','penningmeester') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$2pQf2MVVUWn4CtGFQEn9P.89Raj9OC.cT744p90RVP9lAORIGRkUC', 'admin'),
(2, 'secretaris', '$2y$10$6APhdOk0ENHkUYCDivUptO36ad4.M3.GNpgR77EsbIYGzRFyPW34q', 'secretaris'),
(3, 'penningmeester', '$2y$10$9sP18WCIL.On5aTUb9CqX.y53KGAw9ykrnwbmLXsqdrM5fiPzIcD.', 'penningmeester');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bookyear`
--
ALTER TABLE `bookyear`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- Indexen voor tabel `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookyear_id` (`bookyear_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `member_type` (`member_type`);

--
-- Indexen voor tabel `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `family_members`
--
ALTER TABLE `family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_type_id` (`member_type_id`),
  ADD KEY `family_id` (`family_id`);

--
-- Indexen voor tabel `member_type`
--
ALTER TABLE `member_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bookyear`
--
ALTER TABLE `bookyear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `family`
--
ALTER TABLE `family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `family_members`
--
ALTER TABLE `family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT voor een tabel `member_type`
--
ALTER TABLE `member_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_1` FOREIGN KEY (`bookyear_id`) REFERENCES `bookyear` (`id`),
  ADD CONSTRAINT `contributions_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `family_members` (`id`),
  ADD CONSTRAINT `contributions_ibfk_3` FOREIGN KEY (`member_type`) REFERENCES `member_type` (`id`);

--
-- Beperkingen voor tabel `family_members`
--
ALTER TABLE `family_members`
  ADD CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`member_type_id`) REFERENCES `member_type` (`id`),
  ADD CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`family_id`) REFERENCES `family` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
