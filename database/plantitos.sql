-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 03:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plantitos`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'asmodeus@gmail.com', '$2y$10$P49LBel8CCIC55GI3bbeB.XuSCGc5jAHaxvfv1E/7XTFTBpWgejnC');

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `scientific_name` varchar(255) DEFAULT NULL,
  `category` enum('underwater','garden','hanging','indoor') NOT NULL,
  `description` text DEFAULT NULL,
  `care_instructions` text DEFAULT NULL,
  `environment` text DEFAULT NULL,
  `toxicity` tinyint(1) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`id`, `name`, `scientific_name`, `category`, `description`, `care_instructions`, `environment`, `toxicity`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Java Fern', 'Microsorum pteropus', 'underwater', 'A popular aquatic fern with long, flowing leaves that add a natural look to aquariums.', 'Plant in substrate or attach to driftwood. Trim dead leaves. Low maintenance.', 'Prefers low to medium light. Can grow in various water conditions. Temperature: 68-82°F (20-28°C).', 0, '1743912030_java-fern04.jpg', '2025-04-06 03:46:45', '2025-04-06 04:00:30'),
(2, 'Amazon Sword', 'Echinodorus grisebachii', 'underwater', 'Large, broad leaves that create a dramatic centerpiece in aquariums.', 'Requires nutrient-rich substrate. Regular fertilization needed. Trim outer leaves when they turn yellow.', 'Medium to high light. Soft to moderately hard water. Temperature: 72-82°F (22-28°C).', 0, '1743911787_amazonsword.jpg', '2025-04-06 03:46:45', '2025-04-06 03:56:27'),
(3, 'Anubias', 'Anubias barteri', 'underwater', 'Hardy plant with thick, dark green leaves that can grow both submerged and emersed.', 'Attach to rocks or driftwood. Do not bury rhizome. Low maintenance.', 'Low to medium light. Can grow in various water conditions. Temperature: 72-82°F (22-28°C).', 0, '1743911638_anubias.jpg', '2025-04-06 03:46:45', '2025-04-06 03:53:58'),
(4, 'Hornwort', 'Ceratophyllum demersum', 'underwater', 'Fast-growing plant with feathery leaves that provides excellent cover for fish.', 'Can float or be planted. Regular trimming needed. No special care required.', 'Medium to high light. Adapts to various water conditions. Temperature: 59-86°F (15-30°C).', 0, '1743911552_Hornwort.jpg', '2025-04-06 03:46:45', '2025-04-06 03:52:32'),
(5, 'Water Wisteria', 'Hygrophila difformis', 'underwater', 'Beautiful plant with delicate, lace-like leaves that adds texture to aquariums.', 'Plant in substrate. Regular trimming promotes bushier growth. Medium maintenance.', 'Medium to high light. Soft to moderately hard water. Temperature: 70-82°F (21-28°C).', 0, '1743912054_wisteria_-_submersed_leaves.jpg', '2025-04-06 03:46:45', '2025-04-06 04:00:54'),
(6, 'Tomato', 'Solanum lycopersicum', 'garden', 'Popular garden vegetable producing juicy, red fruits. Available in many varieties.', 'Plant in full sun. Regular watering. Support with cages or stakes. Fertilize monthly.', 'Well-draining soil. Full sun. Temperature: 65-85°F (18-29°C).', 0, '1743912189_tomato.jpg', '2025-04-06 03:46:45', '2025-04-06 04:03:09'),
(7, 'Basil', 'Ocimum basilicum', 'garden', 'Aromatic herb with fragrant leaves, perfect for cooking and garden borders.', 'Pinch off flower buds. Regular harvesting promotes growth. Water when soil is dry.', 'Full sun to partial shade. Rich, well-draining soil. Temperature: 50-80°F (10-27°C).', 0, '1743911894_Basil.jpg', '2025-04-06 03:46:45', '2025-04-06 03:58:14'),
(8, 'Lavender', 'Lavandula angustifolia', 'garden', 'Fragrant flowering plant with purple blooms, attracts pollinators.', 'Prune after flowering. Well-draining soil essential. Drought tolerant once established.', 'Full sun. Sandy, well-draining soil. Temperature: 45-85°F (7-29°C).', 0, '1743911904_lavender.jpg', '2025-04-06 03:46:45', '2025-04-06 03:58:24'),
(9, 'Sunflower', 'Helianthus annuus', 'garden', 'Tall, cheerful flowers that follow the sun. Great for cutting gardens.', 'Support tall varieties. Regular watering. Deadhead spent flowers.', 'Full sun. Rich, well-draining soil. Temperature: 70-78°F (21-26°C).', 0, '1743912126_sunflower.jpg', '2025-04-06 03:46:45', '2025-04-06 04:02:06'),
(10, 'Pepper', 'Capsicum annuum', 'garden', 'Versatile vegetable producing colorful fruits in various shapes and heat levels.', 'Support plants. Regular watering. Fertilize monthly. Harvest when fruits are firm.', 'Full sun. Rich, well-draining soil. Temperature: 70-85°F (21-29°C).', 0, '1743912078_Green-Yellow-Red-Pepper-2009.jpg', '2025-04-06 03:46:45', '2025-04-06 04:01:18'),
(11, 'String of Pearls', 'Senecio rowleyanus', 'hanging', 'Unique succulent with trailing stems of round, bead-like leaves.', 'Water when soil is dry. Bright indirect light. Protect from direct sun.', 'Well-draining soil. Bright indirect light. Temperature: 70-80°F (21-27°C).', 1, '1743911875_StringofPearls.jpg', '2025-04-06 03:46:45', '2025-04-06 03:57:55'),
(12, 'Pothos', 'Epipremnum aureum', 'hanging', 'Versatile vine with heart-shaped leaves, perfect for hanging baskets.', 'Water when top soil is dry. Trim to promote bushiness. Low maintenance.', 'Bright indirect light. Well-draining soil. Temperature: 65-85°F (18-29°C).', 1, '1743912283_pothos.jpg', '2025-04-06 03:46:45', '2025-04-06 04:04:43'),
(13, 'Spider Plant', 'Chlorophytum comosum', 'hanging', 'Easy-care plant producing arching leaves and baby plantlets.', 'Water when soil is dry. Remove brown tips. Propagate from plantlets.', 'Bright indirect light. Well-draining soil. Temperature: 65-80°F (18-27°C).', 0, '1743911860_SpiderPlant.jpg', '2025-04-06 03:46:45', '2025-04-06 03:57:40'),
(14, 'String of Hearts', 'Ceropegia woodii', 'hanging', 'Delicate trailing plant with heart-shaped leaves and purple undersides.', 'Water sparingly. Bright indirect light. Allow soil to dry between waterings.', 'Bright indirect light. Well-draining soil. Temperature: 65-80°F (18-27°C).', 0, '1743912336_stringofhearts.jpg', '2025-04-06 03:46:45', '2025-04-06 04:05:36'),
(15, 'Burro\'s Tail', 'Sedum morganianum', 'hanging', 'Succulent with trailing stems covered in plump, blue-green leaves.', 'Water sparingly. Protect from direct sun. Handle with care - leaves fall easily.', 'Bright indirect light. Well-draining soil. Temperature: 65-75°F (18-24°C).', 0, '1743912235_Burros-tail.jpg', '2025-04-06 03:46:45', '2025-04-06 04:03:55'),
(16, 'Snake Plant', 'Sansevieria trifasciata', 'indoor', 'Sturdy plant with upright, sword-like leaves. Excellent air purifier.', 'Water when soil is dry. Tolerates low light. Very low maintenance.', 'Low to bright indirect light. Well-draining soil. Temperature: 60-85°F (16-29°C).', 1, '1743911955_252928147_624464455353622_4896700120352281858_n.png', '2025-04-06 03:46:45', '2025-04-06 03:59:15'),
(17, 'Peace Lily', 'Spathiphyllum', 'indoor', 'Elegant plant with dark leaves and white flowers. Great for low-light areas.', 'Keep soil moist. Mist leaves. Remove spent flowers.', 'Low to medium light. Rich, well-draining soil. Temperature: 65-80°F (18-27°C).', 1, '1743912598_qYNPupRnspGWPF4886Z7hB-1200-80.jpg', '2025-04-06 03:46:45', '2025-04-06 04:09:58'),
(18, 'ZZ Plant', 'Zamioculcas zamiifolia', 'indoor', 'Modern plant with glossy, dark green leaves. Extremely low maintenance.', 'Water when soil is dry. Tolerates neglect. Low maintenance.', 'Low to bright indirect light. Well-draining soil. Temperature: 65-79°F (18-26°C).', 1, '1743911944_how-to-zz_Mokkie.jpg', '2025-04-06 03:46:45', '2025-04-06 03:59:04'),
(19, 'Monstera', 'Monstera deliciosa', 'indoor', 'Tropical plant with large, split leaves. Popular for its dramatic appearance.', 'Water when top soil is dry. Support with moss pole. Mist leaves.', 'Bright indirect light. Rich, well-draining soil. Temperature: 65-85°F (18-29°C).', 1, '1743912580_monstera.jpg', '2025-04-06 03:46:45', '2025-04-06 04:09:40'),
(20, 'Chinese Evergreen', 'Aglaonema', 'indoor', 'Colorful plant with variegated leaves. Adapts well to indoor conditions.', 'Water when soil is dry. Wipe leaves occasionally. Low maintenance.', 'Low to medium light. Well-draining soil. Temperature: 65-80°F (18-27°C).', 1, '1743912547_Red-Chinese-Evergreen.jpg', '2025-04-06 03:46:45', '2025-04-06 04:09:07');

-- --------------------------------------------------------

--
-- Table structure for table `plant_care_reminders`
--

CREATE TABLE `plant_care_reminders` (
  `id` int(11) NOT NULL,
  `user_garden_plant_id` int(11) NOT NULL,
  `reminder_type` enum('water','mist','fertilize') NOT NULL,
  `reminder_date` datetime NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plant_care_reminders`
--

INSERT INTO `plant_care_reminders` (`id`, `user_garden_plant_id`, `reminder_type`, `reminder_date`, `is_completed`, `created_at`) VALUES
(19, 4, 'water', '2025-04-13 13:36:31', 1, '2025-04-06 19:36:31'),
(20, 4, 'mist', '2025-04-09 13:36:32', 1, '2025-04-06 19:36:32'),
(21, 4, 'fertilize', '2025-05-06 13:36:32', 1, '2025-04-06 19:36:32'),
(22, 4, 'water', '2025-04-13 13:36:33', 0, '2025-04-06 19:36:33'),
(23, 4, 'mist', '2025-04-09 13:36:34', 1, '2025-04-06 19:36:34'),
(24, 4, 'fertilize', '2025-05-06 13:36:35', 1, '2025-04-06 19:36:35'),
(25, 4, 'mist', '2025-04-09 14:34:46', 1, '2025-04-06 20:34:46'),
(26, 4, 'fertilize', '2025-05-06 14:34:47', 0, '2025-04-06 20:34:47'),
(27, 4, 'mist', '2025-04-09 14:34:48', 0, '2025-04-06 20:34:48'),
(34, 6, 'water', '2025-04-13 14:41:49', 1, '2025-04-06 20:41:49'),
(35, 6, 'mist', '2025-04-09 14:41:49', 1, '2025-04-06 20:41:49'),
(36, 6, 'fertilize', '2025-05-06 14:41:49', 1, '2025-04-06 20:41:49'),
(37, 6, 'fertilize', '2025-05-06 14:41:51', 1, '2025-04-06 20:41:51'),
(38, 6, 'mist', '2025-04-09 14:41:52', 1, '2025-04-06 20:41:52'),
(39, 6, 'water', '2025-04-13 14:41:53', 1, '2025-04-06 20:41:53'),
(40, 6, 'fertilize', '2025-05-06 14:42:15', 0, '2025-04-06 20:42:15'),
(41, 6, 'mist', '2025-04-09 14:42:18', 1, '2025-04-06 20:42:18'),
(42, 6, 'mist', '2025-04-09 14:52:38', 0, '2025-04-06 20:52:38'),
(43, 6, 'water', '2025-04-13 14:52:38', 0, '2025-04-06 20:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `plant_diseases`
--

CREATE TABLE `plant_diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `scientific_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `prevention` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plant_diseases`
--

INSERT INTO `plant_diseases` (`id`, `name`, `scientific_name`, `description`, `symptoms`, `treatment`, `prevention`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Powdery Mildew', 'Erysiphe spp.', 'A fungal disease that appears as white or gray powdery spots on leaves, stems, and flowers.', 'White or gray powdery spots on leaves, distorted leaves, yellowing, premature leaf drop', 'Remove infected parts, apply fungicide, improve air circulation', 'Plant resistant varieties, maintain good air circulation, avoid overhead watering', 'powdery_mildew.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(2, 'Root Rot', 'Phytophthora spp.', 'A serious fungal disease that affects the roots of plants, causing them to decay.', 'Wilting, yellowing leaves, soft brown roots, stunted growth', 'Remove affected roots, repot with fresh soil, apply fungicide', 'Use well-draining soil, avoid overwatering, ensure proper drainage', 'root_rot.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(3, 'Leaf Spot Disease', 'Various fungi and bacteria', 'A common disease causing spots or lesions on leaves, which can lead to defoliation.', 'Brown or black spots on leaves, yellow halos around spots, leaf drop', 'Remove infected leaves, apply appropriate fungicide or bactericide', 'Avoid overhead watering, maintain good air circulation, remove fallen leaves', 'leaf_spot.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(4, 'Botrytis Blight', 'Botrytis cinerea', 'A fungal disease that causes gray mold on flowers, fruits, and leaves.', 'Gray fuzzy mold, brown spots, wilting flowers, rotting fruits', 'Remove infected parts, improve air circulation, apply fungicide', 'Maintain good air circulation, avoid overcrowding, remove dead plant material', 'botrytis_blight.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(5, 'Downy Mildew', 'Peronospora spp.', 'A fungal disease that appears as yellow patches on leaves with fuzzy growth underneath.', 'Yellow patches on leaves, fuzzy growth on undersides, distorted growth', 'Remove infected parts, apply fungicide, improve air circulation', 'Plant resistant varieties, maintain good air circulation, avoid overhead watering', 'downy_mildew.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(6, 'Fusarium Wilt', 'Fusarium oxysporum', 'A soil-borne fungal disease that causes wilting and yellowing of leaves.', 'Wilting, yellowing leaves, brown vascular tissue, stunted growth', 'Remove infected plants, solarize soil, use resistant varieties', 'Use disease-free soil, practice crop rotation, maintain proper soil pH', 'fusarium_wilt.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(7, 'Black Spot', 'Diplocarpon rosae', 'A fungal disease common in roses, causing black spots with yellow halos on leaves.', 'Black spots with yellow halos, leaf yellowing, premature leaf drop', 'Remove infected leaves, apply fungicide, improve air circulation', 'Plant resistant varieties, maintain good air circulation, avoid overhead watering', 'black_spot.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(8, 'Anthracnose', 'Colletotrichum spp.', 'A fungal disease causing dark, sunken lesions on leaves, stems, and fruits.', 'Dark sunken lesions, leaf spots, fruit rot, stem cankers', 'Remove infected parts, apply fungicide, improve air circulation', 'Maintain good air circulation, avoid overhead watering, remove fallen debris', 'anthracnose.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(9, 'Bacterial Leaf Blight', 'Xanthomonas spp.', 'A bacterial disease causing yellow to brown lesions on leaves with a water-soaked appearance.', 'Yellow to brown lesions, water-soaked appearance, leaf death', 'Remove infected parts, apply copper-based bactericide', 'Use disease-free seeds, avoid overhead watering, maintain good air circulation', 'bacterial_leaf_blight.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17'),
(10, 'Verticillium Wilt', 'Verticillium dahliae', 'A soil-borne fungal disease causing wilting and yellowing of leaves, often affecting one side of the plant.', 'Wilting, yellowing leaves, brown vascular tissue, one-sided symptoms', 'Remove infected plants, solarize soil, use resistant varieties', 'Use disease-free soil, practice crop rotation, maintain proper soil pH', 'verticillium_wilt.jpg', '2025-04-05 19:46:45', '2025-04-06 11:05:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `profile_image`, `reset_token`, `reset_expiry`, `created_at`, `updated_at`) VALUES
(1, 'jhon.emerwin05@gmail.com', 'Emerwin', '$2y$10$G4Z2LJ.BQdeL/ODAOsiS0.Xz78mlBYO5lrFsr1dkgY3fydaZI2CAi', 'uploads/profile_images/profile_1_1743908005.jpg', NULL, NULL, '2025-04-06 02:43:39', '2025-04-06 02:53:25'),
(2, 'jhon.emerwinv.3@gmail.com', 'lebel', '$2y$10$K9DrP5qMCBoYnth2ZtJh9.9YHZ3a0VZsqOSOupxJN08v9XHWrJ8/e', 'uploads/profile_images/profile_2_1743909886.jpg', NULL, NULL, '2025-04-06 03:24:29', '2025-04-06 03:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_garden_plants`
--

CREATE TABLE `user_garden_plants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plant_id` int(11) NOT NULL,
  `plant_name` varchar(255) NOT NULL,
  `plant_image` varchar(255) DEFAULT NULL,
  `date_added` datetime DEFAULT current_timestamp(),
  `last_watered` datetime DEFAULT NULL,
  `last_misted` datetime DEFAULT NULL,
  `last_fertilized` datetime DEFAULT NULL,
  `watering_frequency` int(11) DEFAULT NULL,
  `misting_frequency` int(11) DEFAULT NULL,
  `fertilizing_frequency` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_garden_plants`
--

INSERT INTO `user_garden_plants` (`id`, `user_id`, `plant_id`, `plant_name`, `plant_image`, `date_added`, `last_watered`, `last_misted`, `last_fertilized`, `watering_frequency`, `misting_frequency`, `fertilizing_frequency`, `notes`) VALUES
(4, 1, 4, 'Hornwort', '../uploads/1743911552_Hornwort.jpg', '2025-04-06 19:36:31', '2025-04-06 19:36:33', '2025-04-06 20:34:48', '2025-04-06 20:34:47', 7, 3, 30, 'labombi'),
(6, 2, 6, 'Tomato', '../uploads/1743912189_tomato.jpg', '2025-04-06 20:41:49', '2025-04-06 20:52:38', '2025-04-06 20:52:38', '2025-04-06 20:42:15', 7, 3, 30, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_garden_plant_id` (`user_garden_plant_id`);

--
-- Indexes for table `plant_diseases`
--
ALTER TABLE `plant_diseases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_garden_plants`
--
ALTER TABLE `user_garden_plants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plant_id` (`plant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `plant_diseases`
--
ALTER TABLE `plant_diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_garden_plants`
--
ALTER TABLE `user_garden_plants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  ADD CONSTRAINT `plant_care_reminders_ibfk_1` FOREIGN KEY (`user_garden_plant_id`) REFERENCES `user_garden_plants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_garden_plants`
--
ALTER TABLE `user_garden_plants`
  ADD CONSTRAINT `user_garden_plants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_garden_plants_ibfk_2` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
