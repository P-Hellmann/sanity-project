-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 07 feb 2025 om 11:52
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sanity-project`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Ghost Hunting Gear', 'The ultimate toolkit for dealing with things that go bump in the night! EMF detectors, ghost traps, and high-grade salt (now available in stylish shakers). If you hear whispering when you\'re home alone, it\'s time to stock up!'),
(2, 'Demon Repellents', 'For when you accidentally read Latin out loud and now your furniture is floating. Holy water, Latin phrasebooks, and anti-possession charms—everything you need to keep demons out of your body.'),
(3, 'Cursed Artifacts', 'For those who enjoy living dangerously, we’ve gathered the finest selection of haunted dolls, cursed mirrors, and whispering jewelry. Warning: May come with a built-in vengeful spirit. No refunds.'),
(4, 'Protective Wards & Charms', 'Tired of unexpected hauntings? Ward off ghosts, demons, and unwanted visitors (yes, even your in-laws) with our handcrafted protective amulets, hex-breaking talismans, and enchanted salt circles.'),
(5, 'Specialized trucks', 'Do you need the ultimate workstation to exorcise a demon from someone\'s home? Our specialized ghost hunting trucks with a anti-ghost container attached to the back are the thing for you.'),
(6, 'Potions & Elixirs', 'Love potions, truth serums, and unidentified bubbling concoctions! Whether you need a boost of luck or an antidote for your latest magical mishap, our potions have you covered. Side effects may include glowing skin… literally.'),
(7, 'Paranormal Investigation Kits', 'EMF meters, spirit boxes, thermal cameras, and notepads for frantic ghost-scribbling—everything you need to prove your house isn’t just settling.'),
(8, 'Werewolf & Vampire Defense', 'Stock up on silver bullets, UV flashlights, garlic necklaces, and wooden stakes! Because if pop culture has taught us anything, you never know when you’ll need them.'),
(9, 'Spell Books & Scrolls', 'Learn the art of casting spells, summoning spirits, and not turning yourself into a toad. Includes beginner and advanced grimoires, plus emergency spell reversal guides!');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250205091425', '2025-02-05 10:14:31', 33),
('DoctrineMigrations\\Version20250205105342', '2025-02-05 11:53:48', 9),
('DoctrineMigrations\\Version20250205105607', '2025-02-05 11:56:13', 8),
('DoctrineMigrations\\Version20250205110557', '2025-02-05 12:06:01', 10),
('DoctrineMigrations\\Version20250205120215', '2025-02-05 13:02:22', 59),
('DoctrineMigrations\\Version20250205122416', '2025-02-05 13:24:19', 40),
('DoctrineMigrations\\Version20250205141614', '2025-02-05 15:16:17', 11),
('DoctrineMigrations\\Version20250205141727', '2025-02-05 15:17:30', 108),
('DoctrineMigrations\\Version20250205141824', '2025-02-05 15:18:27', 51),
('DoctrineMigrations\\Version20250205144018', '2025-02-05 15:40:21', 9),
('DoctrineMigrations\\Version20250205145652', '2025-02-05 15:56:55', 26),
('DoctrineMigrations\\Version20250205145832', '2025-02-05 15:58:35', 53),
('DoctrineMigrations\\Version20250205152348', '2025-02-05 16:23:50', 7),
('DoctrineMigrations\\Version20250206090357', '2025-02-06 10:04:16', 7),
('DoctrineMigrations\\Version20250206094202', '2025-02-06 10:42:10', 9),
('DoctrineMigrations\\Version20250206123515', '2025-02-06 13:41:38', 61),
('DoctrineMigrations\\Version20250207102913', '2025-02-07 11:29:19', 48);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `totalprice` decimal(10,2) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `comments` longtext DEFAULT NULL,
  `dateoforder` datetime NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`items`)),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `order`
--

INSERT INTO `order` (`id`, `totalprice`, `address`, `phone`, `comments`, `dateoforder`, `firstname`, `lastname`, `email`, `items`, `user_id`) VALUES
(4, 8.50, 'adminstreet 11', 612345678, 'no', '2025-02-06 14:04:22', 'admin', 'admin', 'admin1@admin.com', '{\"items\":[{\"product_id\":\"1\",\"product_name\":\"Flashlight\",\"price\":\"8.50\",\"quantity\":1}]}', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `stock`, `category_id`, `description`) VALUES
(1, 'Flashlight', 8.50, 9999, 1, NULL),
(2, 'Sanity Pills', 19.99, 9999, 2, NULL),
(3, 'Incense', 9.99, 9999, 3, NULL),
(4, 'Crucifix', 14.99, 9999, 4, NULL),
(5, 'Ghosthunting truck', 9999.99, 9999, 5, NULL),
(6, 'EMF Detector', 49.99, 100, 1, 'Detects electromagnetic fields and ghosts lurking around your house.'),
(7, 'Ghost Trap', 129.99, 50, 1, 'A professional-grade ghost trap for capturing spirits.'),
(8, 'Salt Shaker', 9.99, 200, 1, 'High-grade salt for creating protective barriers against spirits.'),
(9, 'Holy Water Bottle', 19.99, 150, 2, 'Blessed water that repels demons and bad vibes.'),
(10, 'Exorcism Kit', 199.99, 30, 2, 'Everything you need for an emergency exorcism.'),
(11, 'Sacred Incense', 12.99, 180, 2, 'Burn this incense to ward off evil spirits and demons.'),
(12, 'Haunted Doll', 49.99, 15, 3, 'A doll cursed to whisper in the night. Handle with care.'),
(13, 'Cursed Mirror', 89.99, 20, 3, 'A mirror with a dark past; reflections may not be your own.'),
(14, 'Voodoo Necklace', 24.99, 50, 3, 'A mysterious necklace rumored to bring misfortune.'),
(15, 'Protection Amulet', 15.99, 120, 4, 'A charm that shields you from negative energies.'),
(16, 'Hex-Breaking Talisman', 22.99, 90, 4, 'Break any hex with this enchanted talisman.'),
(17, 'Salt Circle Kit', 29.99, 75, 4, 'A complete kit for creating protective salt circles.'),
(18, 'Exorcism Truck', 89999.99, 5, 5, 'A fully equipped truck for ghost hunters and exorcists on the go.'),
(19, 'Cryptid Tracking Van', 74999.99, 3, 5, 'Equipped with night-vision and thermal cameras for tracking cryptids.'),
(20, 'Paranormal Investigation SUV', 49999.99, 8, 5, 'Rugged SUV with all the tools you need to chase down ghosts and goblins.'),
(21, 'Love Potion', 19.99, 200, 6, 'Brewed for those in need of a little extra romance in their life.'),
(22, 'Truth Serum', 29.99, 100, 6, 'For when you need to know the truth—no matter the cost.'),
(23, 'Elixir of Vitality', 39.99, 150, 6, 'A revitalizing potion to restore health and energy.'),
(24, 'Spirit Box', 69.99, 120, 7, 'A device that allows communication with spirits from the other side.'),
(25, 'Thermal Camera', 299.99, 30, 7, 'Capture the cold spots where ghosts are lurking with this advanced thermal camera.'),
(26, 'EMF Meter', 49.99, 150, 7, 'Detects electromagnetic fields that may signal paranormal activity.'),
(27, 'Silver Bullets', 19.99, 200, 8, 'Perfect for dealing with werewolves.'),
(28, 'UV Flashlight', 24.99, 180, 8, 'Keep vampires at bay with this high-powered UV flashlight.'),
(29, 'Garlic Necklace', 14.99, 220, 8, 'A stylish necklace that keeps vampires away.'),
(30, 'Grimoire of Dark Magic', 49.99, 40, 9, 'Learn powerful spells and ancient curses.'),
(31, 'Book of Summoning Spirits', 59.99, 25, 9, 'A tome of knowledge for calling spirits from beyond the grave.'),
(32, 'Scroll of Protection', 39.99, 60, 9, 'A scroll to protect you from dark forces. Be cautious when unrolling.');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`cart_data`)),
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `cart_data`, `updated_at`, `user_id`, `total`) VALUES
(2, '{\"items\":[{\"product_id\":\"1\",\"product_name\":\"Flashlight\",\"price\":\"8.50\",\"quantity\":1}]}', '2025-02-07 11:42:00', 1, 8.50),
(5, '{\"items\":[]}', NULL, 4, 0.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'admin1@admin1.com', '[\"ROLE_ADMIN\"]', '$2y$13$xnDt8mhX9mzXMNYkv9rS6.LRRGOXFKQb.9766E90yDKTCGQSkTRVu'),
(4, 'test123@test123.com', '[]', '$2y$13$sJ4DuUHhvGoLbu45zKdF0.aXs9CHuSsmthnvH2KViNx2kd2YDeNZe');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexen voor tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexen voor tabel `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Indexen voor tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Indexen voor tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_72AAD4F6A76ED395` (`user_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT voor een tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Beperkingen voor tabel `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `FK_72AAD4F6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
