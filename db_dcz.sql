-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 11 Lis 2017, 13:00
-- Wersja serwera: 10.1.25-MariaDB
-- Wersja PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `db_dcz`
--
CREATE DATABASE IF NOT EXISTS `db_dcz` DEFAULT CHARACTER SET latin2 COLLATE latin2_general_ci;
USE `db_dcz`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chat_messages`
--

CREATE TABLE `chat_messages` (
  `msg_id` bigint(20) NOT NULL,
  `req_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `from_type` varchar(1) COLLATE utf8mb4_polish_ci NOT NULL,
  `time` datetime NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `chat_requests`
--

CREATE TABLE `chat_requests` (
  `ss_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `last_time_user` datetime NOT NULL,
  `browser` varchar(300) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `adviser` int(11) NOT NULL,
  `last_time_adviser` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `chat_requests`
--

INSERT INTO `chat_requests` (`ss_id`, `user`, `ip`, `last_time_user`, `browser`, `adviser`, `last_time_adviser`) VALUES
('9hl3bbh3an9jqm7uppig55du23', 0, '127.0.0.1', '0000-00-00 00:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `id_specialization` int(11) DEFAULT NULL,
  `id_member` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `testing_date` date NOT NULL,
  `description` varchar(300) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `document_type`
--

CREATE TABLE `document_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `document_type`
--

INSERT INTO `document_type` (`id`, `name`) VALUES
(2, 'Wyniki badań'),
(3, 'Zdjęcie rentgenowskie'),
(4, 'Wypis ze szpitala'),
(5, 'Zdjęcie USG'),
(6, 'Inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `entry`
--

CREATE TABLE `entry` (
  `id` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8_polish_ci NOT NULL,
  `section_id` int(11) NOT NULL,
  `summary` varchar(1000) COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `created` datetime NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `photo` varchar(100) COLLATE utf8_polish_ci NOT NULL DEFAULT '',
  `author` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `entry_section`
--

CREATE TABLE `entry_section` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `entry_section`
--

INSERT INTO `entry_section` (`id`, `name`) VALUES
(1, 'Medycyna'),
(2, 'Sport');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `illness`
--

CREATE TABLE `illness` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `latin_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `description` varchar(300) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `illness`
--

INSERT INTO `illness` (`id`, `name`, `latin_name`, `description`) VALUES
(1, 'Cukrzyca', 'diabetes', 'Grupa chorób metabolicznych charakteryzująca się hiperglikemią (podwyższonym poziomem cukru we krwi) wynikającą z defektu produkcji lub działania insuliny wydzielanej przez komórki beta trzustki.'),
(2, 'Nadciśnienie tętnicze', 'hypertonia arterialis', 'Schorzenie układu krążenia krwi charakteryzujące się okresowo lub stale podwyższonym ciśnieniem tętniczym.'),
(3, 'Niedociśnienie tętnicze', 'hipotonia', 'Obniżenie ciśnienia tętniczego poniżej 100 mm Hg ciśnienia skurczowego lub 60 mm Hg ciśnienia rozkurczowego.'),
(4, 'Choroba wieńcowa', 'morbus ischaemicus cordis', 'Zespół objawów chorobowych będących następstwem przewlekłego stanu niedostatecznego zaopatrzenia komórek mięśnia sercowego w tlen i substancje odżywcze.'),
(6, 'Wysoki cholesterol', 'hiperlipidemia', 'Zespół zaburzeń metabolicznych objawiających się podwyższonymi poziomami frakcji cholesterolu lub trójglicerydów w surowicy krwi.'),
(7, 'Otyłość', 'obesitas', 'Nadmierne nagromadzenie tkanki tłuszczowej w organizmie, przekraczające jego fizjologiczne potrzeby i możliwości adaptacyjne.'),
(8, 'Grypa', 'influenza', 'Ostra choroba zakaźna układu oddechowego wywołana zakażeniem wirusem grypy.'),
(9, 'Ospa wietrzna', 'varicella', 'Choroba zakaźna wieku dziecięcego spowodowana przez pierwotne zakażeniem wirusem ospy wietrznej i półpaśca (VZV), charakteryzująca się znaczną zaraźliwością.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `medicine`
--

CREATE TABLE `medicine` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `surname` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `sex` varchar(1) COLLATE utf8mb4_polish_ci NOT NULL,
  `birth` date NOT NULL,
  `growth` smallint(6) NOT NULL,
  `weight` smallint(6) NOT NULL,
  `created` datetime NOT NULL,
  `last_edit` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `member_illness`
--

CREATE TABLE `member_illness` (
  `member_id` int(11) NOT NULL,
  `illness_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'gość'),
(2, 'zalogowany'),
(3, 'administrator'),
(4, 'doradca');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `specialization`
--

CREATE TABLE `specialization` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `specialization`
--

INSERT INTO `specialization` (`id`, `name`) VALUES
(1, 'alergolog'),
(2, 'urolog'),
(3, 'ginekolog'),
(4, 'dermatolog'),
(6, 'endokrynolog'),
(7, 'chirurg'),
(8, 'diabetyk'),
(9, 'gastrolog'),
(10, 'audiolog'),
(11, 'angiolog'),
(13, 'podolog'),
(14, 'genetyk'),
(16, 'hematolog'),
(17, 'hipertensjolog'),
(18, 'immunolog'),
(19, 'kardiochirurg'),
(20, 'kardiolog'),
(21, 'lekarz rodzinny'),
(22, 'nefrolog'),
(23, 'neonatolog'),
(24, 'neurochirurg'),
(25, 'neurolog'),
(26, 'nauropatolog'),
(27, 'okulista'),
(28, 'onkolog'),
(29, 'ortopeda'),
(30, 'laryngolog'),
(31, 'morfolog'),
(32, 'pediatra'),
(34, 'psychiatra'),
(35, 'radiolog'),
(36, 'radioterapeuta'),
(37, 'reumatolog'),
(38, 'seksuolog');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '2',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `role`, `password`) VALUES
(4, 'admin@poczta.domena.pl', 3, '5f4dcc3b5aa765d61d8327deb882cf99');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `chat_requests`
--
ALTER TABLE `chat_requests`
  ADD PRIMARY KEY (`ss_id`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member` (`id_member`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_type_2` (`id_type`),
  ADD KEY `id_specialization` (`id_specialization`);

--
-- Indexes for table `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entry`
--
ALTER TABLE `entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `entry_section`
--
ALTER TABLE `entry_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `illness`
--
ALTER TABLE `illness`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_3` (`name`),
  ADD KEY `name` (`name`),
  ADD KEY `latin_name` (`latin_name`),
  ADD KEY `name_2` (`name`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `member_illness`
--
ALTER TABLE `member_illness`
  ADD KEY `member_id` (`member_id`,`illness_id`),
  ADD KEY `illness_id` (`illness_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `msg_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `document_type`
--
ALTER TABLE `document_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `entry`
--
ALTER TABLE `entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `entry_section`
--
ALTER TABLE `entry_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `illness`
--
ALTER TABLE `illness`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT dla tabeli `medicine`
--
ALTER TABLE `medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `specialization`
--
ALTER TABLE `specialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
