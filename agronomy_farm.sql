-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 07:20 PM
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
-- Database: `agronomy_farm`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 28, 'some', 's@gmail.com', 'didnot get the crops', 'i am facing issue', '2025-06-20 15:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `body`, `created_at`) VALUES
(3, 'zdxfg', 'dsfh', '2025-05-19 02:43:17'),
(4, 'issue', '7bumujyhbjym', '2025-05-19 10:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `crops`
--

CREATE TABLE `crops` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crop_name` varchar(255) NOT NULL,
  `expected_harvest` date DEFAULT NULL,
  `quantity` varchar(100) NOT NULL,
  `harvest_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crops`
--

INSERT INTO `crops` (`id`, `user_id`, `crop_name`, `expected_harvest`, `quantity`, `harvest_date`, `created_at`, `price`) VALUES
(1, 8, 'Rice', '2025-11-11', '30', '2025-05-22', '2025-05-18 16:42:37', 0.00),
(3, 8, 'Banana', '4444-12-04', '40', '0000-00-00', '2025-05-18 16:49:48', 20.00),
(5, 7, 'Rice', '2025-02-22', '20', '0000-00-00', '2025-05-18 19:17:16', 0.00),
(6, 13, 'Rice', '2025-05-22', '120', '0000-00-00', '2025-05-19 04:12:59', 0.00),
(7, 13, 'Tomato', '2025-08-21', '1000', '0000-00-00', '2025-05-24 15:00:01', 0.00),
(8, 16, 'Banana', '2025-09-25', '250', '0000-00-00', '2025-05-26 10:06:51', 0.00),
(9, 16, 'vegetable', '2025-09-25', '330', '0000-00-00', '2025-05-26 10:07:18', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `submitted_at`, `created_at`) VALUES
(3, 13, 'chandra', '123@gmail.com', 'problem', 'i am facing issue with my crops', '2025-05-19 04:15:48', '2025-05-19 10:15:48'),
(4, 16, 'chinmoy sir', 'chinmoysir@gmail.com', 'issue', 'very problematic field', '2025-05-26 05:12:35', '2025-05-26 11:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `journal` varchar(255) NOT NULL,
  `publication_date` date NOT NULL,
  `researcher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`id`, `title`, `journal`, `publication_date`, `researcher_id`) VALUES
(2, 'jk', 'oi8uy', '2026-10-22', 9),
(3, 'The Rise of Agribusiness', 'oi8uy', '2025-02-13', 15),
(4, 'Agriculture in Bangladesh', 'hjkhk', '2024-09-18', 15),
(5, 'government', 'dealing with government issue', '2025-03-11', 17),
(6, 'business', 'farm and agronomy', '2025-04-10', 17),
(7, 'llkdlk', 'JKlkJ', '2025-06-26', 29);

-- --------------------------------------------------------

--
-- Table structure for table `required_crops`
--

CREATE TABLE `required_crops` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crop_name` varchar(100) NOT NULL,
  `expected_business` date NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `required_crops`
--

INSERT INTO `required_crops` (`id`, `user_id`, `crop_name`, `expected_business`, `quantity`, `created_at`) VALUES
(1, 28, 'Rice', '2025-08-21', '1000', '2025-06-20 09:31:00'),
(2, 28, 'tomato', '2025-08-12', '878', '2025-06-20 09:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `research_projects`
--

CREATE TABLE `research_projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `researcher_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completion` int(11) DEFAULT 0,
  `completion_percentage` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_projects`
--

INSERT INTO `research_projects` (`id`, `title`, `description`, `researcher_id`, `created_at`, `completion`, `completion_percentage`) VALUES
(5, 'xdcfgh', 'dfthuf', 9, '2025-05-18 17:54:01', 0, 60),
(6, 'mhj', 'l,hkj', 9, '2025-05-18 17:55:50', 0, 70),
(7, 'sedrtsdr', 'sdxrtyh', 2, '2025-05-18 20:49:19', 0, 58),
(8, 'Agriculture and Its Significance', 'Agriculture is the backbone of many developing economies, providing food, employment, and raw materials for various industries. It not only ensures food security but also plays a crucial role in economic development and poverty alleviation. Modern agriculture combines traditional practices with advanced technologies such as irrigation systems, fertilizers, biotechnology, and digital tools to increase yield and productivity. As the global population grows, sustainable agricultural practices are more important than ever to meet rising food demands.', 15, '2025-05-23 17:11:37', 0, 47),
(9, 'Agriculture in Bangladesh', 'In Bangladesh, agriculture is a vital sector that supports nearly 40% of the population directly or indirectly. Major crops like rice, jute, wheat, sugarcane, and vegetables are cultivated across the country. Bangladesh has made significant progress in food production over the years, becoming nearly self-sufficient in staple crops. However, the sector still faces challenges like natural disasters, climate change, land scarcity, and outdated farming methods, which need to be addressed through modern techniques and government support.', 15, '2025-05-23 17:12:10', 0, 39),
(10, '🧪 4. Bangladesh Council of Scientific and Industrial Research (BCSIR)', '🔗 Website: http://www.bcsir.gov.bd\r\n\r\nGovernment-funded research body under the Ministry of Science and Technology.\r\n\r\nFocuses on industrial research and innovation.\r\n\r\nPublishes technical research, patents, and scientific outp', 17, '2025-05-26 03:29:48', 0, 68),
(11, '🇧🇩 1. University Grants Commission of Bangladesh (UGC)', '🔗 Website: https://ugc.gov.bd\r\n\r\nThis is the official apex body of the Bangladeshi government for overseeing universities.\r\n\r\nThey publish research grants, policies, guidelines, and updates on academic activities.\r\n\r\nAlso maintains a list of recognized journals and research funding opportunities.', 17, '2025-05-26 03:30:20', 0, 89),
(12, 'ssd', 'kjhkgjhgjh', 29, '2025-06-20 15:56:28', 0, 32),
(13, 'bjkgg', 'kjagkjgdhaO', 29, '2025-06-20 15:56:57', 0, 67);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`) VALUES
('admin_email', 'ug2102006@cse.pstu.ac.bd'),
('maintenance_mode', '1'),
('site_name', 'Agro Farm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','farmer','researcher','seller') NOT NULL DEFAULT 'farmer',
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `address`, `profession`, `password`, `role`, `profile_image`, `created_at`, `latitude`, `longitude`) VALUES
(2, 'Arfan', 'arfan@gmail.com', '01532444444', 'Dumki, Patuakhali', 'student', '$2y$10$1pRzrAyyjSSOwM27hh2U2O75ET3.GGBARsvd6Y7JuR5Zh1Mu/8Qhu', 'researcher', '', '2025-05-18 15:41:44', NULL, NULL),
(7, 'Arfan', 'arfan2@gmail.com', '01532444444', 'Dumki, Patuakhali', 'student', '$2y$10$iFm8wT4Eg8oQLAxPtt2KIel/LX79e.eCSAH5n6cItHpBaT/TAkug.', 'farmer', '1747583607_photo_2025-04-07_11-58-43.jpg', '2025-05-18 15:53:27', NULL, NULL),
(8, 'Arfan', 'arfan4@gmail.com', '01532444444', 'Dumki, Patuakhali', 'farmer', '$2y$10$9TSYSUSRQBFMLK62lWCIU.IrbS30qG6Pw1QDyg1sK2Tv2RjhvciWy', 'farmer', '1747585176_photo_2025-04-07_11-58-43.jpg', '2025-05-18 16:19:36', NULL, NULL),
(9, 'Ahmed', 'ahmed@gmail.com', '01532444444', 'Dumki, Patuakhali', 'researcher', '$2y$10$rpmYn1QDZxK9..n.rJYpJ.WRprmqYkmJ4ZzlaJCdy7FTkLiwqjxzW', 'researcher', '1747587683_photo_2025-04-07_11-58-43.jpg', '2025-05-18 17:01:23', NULL, NULL),
(12, 'Udita', 'ug2102006@cse.pstu.ac.bd', '01868967027', 'Dumki, Patuakhali', 'researcher', '$2y$10$RqwEPzPqo0rtTgwNVoHqeerpABoe.Z3plzH2ztjJzb04R10yQs.RC', 'admin', '1747592399_photo_2025-04-07_11-58-43.jpg', '2025-05-18 18:19:59', NULL, NULL),
(13, 'chandra', '123@gmail.com', '4321', 'netrokona', 'farmer', '$2y$10$P7spyu.Reayow0U6jgHTlu2pAb6GsvRT3Aoti9knGfw0eqWUxeVAq', 'farmer', '1747627933_photo_2025-04-07_11-58-43.jpg', '2025-05-19 04:12:13', NULL, NULL),
(14, 'tui apu', 'tui123@gmail.com', '7890', 'barisal', 'student', '$2y$10$9djvSJAMB.p/oCboDBn4nuzRZuv1sksdbUgl/qbeGtdeDeZbKBtY6', 'researcher', '1748002906_IMG_5478.JPG', '2025-05-23 12:21:46', NULL, NULL),
(15, 'tinni', 'tini@gmail.com', '1234', 'dumki', 'researcher', '$2y$10$H1hEWjQgH75jjnq0UeGZw.N7xB/vhfVEXrJfTGVS2hekiQsXZhEya', 'researcher', '1748020237_IMG_5480.JPG', '2025-05-23 17:10:37', NULL, NULL),
(16, 'Chinmoy sir', 'chinmoysir@gmail.com', '123456', 'barisal', 'farmer', '$2y$10$sugvNIevLRe4glvCKP.rPOe9i/oAqbjTvNSadvIgFSL20Ady.FoA.', 'farmer', '1748228782_ps2.jpg', '2025-05-26 03:06:22', NULL, NULL),
(17, 'atik sir', 'atiksir@gmail.com', '12345', 'barisal', 'researcher', '$2y$10$MNFdZ3nAhpmwrO9Cpp/XIOvOeqrsZkGa5/4GZSc8ogBUjzOBiJkH2', 'researcher', '1748228833_ps1.jpg', '2025-05-26 03:07:13', NULL, NULL),
(18, 'pushpita', 'p@gmail.com', '12345', 'dumki', 'seller', '$2y$10$iklCYaNdVcwZhSz.mWUgUOVKhW8EcvprJjC/C9JTxOZ.oxWvlG06q', '', '1749992036_ps1.jpg', '2025-06-15 12:53:56', 22.46444160, 90.38426200),
(19, 'rumi', 'r@gmail.com', '1234', 'pagla', 'seller', '$2y$10$lZKQmu0/ZuGG5rXM.WLTKumz/YjEJMeeR0P1L0pUmKtRjEZ0xin26', '', '1749992355_UNSAB POSTER 2.png', '2025-06-15 12:59:15', 22.46444160, 90.38426200),
(20, 'kaushik', 'k@gmail.com', '1234', 'dumki', 'seller', '$2y$10$rKnpDb4C85O41mCYHA9ATO2zljmAryIqxsRx5Dh5draKN0tKl6Yt.', '', '1749999758_ps2.jpg', '2025-06-15 15:02:38', 22.46444160, 90.38426200),
(21, 'tapas', 't@gmail.com', '1234', 'netrkona', 'seller', '$2y$10$K1oHOf5wyuQ205H5F3oYzuaiXIcFwN2wusRkL5nODC1gitjsbGgZi', '', '1750000135_ps2.jpg', '2025-06-15 15:08:55', 22.46444160, 90.38426200),
(22, 'arpon', 'a@gmail.com', '1234', 'barhatta', 'farmer', '$2y$10$V1LY1SFq82bfcNtgtffT.eLzuynQ7K.HusTXDpNzxc6rOsEy6YabS', 'farmer', '1750000464_UNSAB POSTER 2.png', '2025-06-15 15:14:25', 22.46444160, 90.38426200),
(23, 'twaran', 'ta@gmail.com', '1234', 'dumki', 'seller', '$2y$10$cqkW42tU5TQeawovmZSCe.u2L5ohnawfxEpYi7P8v6Mtt3yovUKq6', '', '1750000982_ps2.jpg', '2025-06-15 15:23:02', 22.46444160, 90.38426200),
(24, 'arpita', 'aaa@gmail.com', '123456', 'khulna', 'seller', '$2y$10$quWnuH/PK.ONobhW/WlIju5q1eRi5NOcPade8lL0OGcO3U9P.ydOq', '', '1750095396_WIN_20250324_09_41_12_Pro.jpg', '2025-06-16 17:36:36', 22.46444160, 90.38426200),
(25, 'toron', 'to@gmail.com', '4321', 'bogura', 'seller', '$2y$10$I/NFa0svHUs5UtNWkJ33zeRklLCjwskehOwTPrF3EcJqhcob1KJyu', '', '1750096242_WIN_20250324_09_41_12_Pro.jpg', '2025-06-16 17:50:43', 22.46444160, 90.38426200),
(26, 'akiba', 'ak@gmail.com', '123456', 'dumki', 'farmer', '$2y$10$CvtJ3pqqLhj1DpMFBdyt0enqs0ECSwQx0vUKyZwSCNyWuxcOsib2O', 'farmer', '1750408755_Screenshot (4).png', '2025-06-20 08:39:15', 22.46444160, 90.38426200),
(27, 'basudha', 'b@gmail.com', '123', 'pagla', 'seller', '$2y$10$sqjluLHgGxIctiVMJx6vlefBb.4ToxywOLljGvWYRlF1cueKUPFZy', '', '1750410338_Screenshot (3).png', '2025-06-20 09:05:39', 22.46444160, 90.38426200),
(28, 'some', 's@gmail.com', '123', 'dumki', 'seller', '$2y$10$O3nF4wGk83MdGCknVBoqX.NhnFvG2iGMp.GTYPRjQsM0Grq8VzIee', 'seller', '1750411827_Screenshot (7).png', '2025-06-20 09:30:27', 22.46444160, 90.38426200),
(29, 'beauty', 'be@gmail.com', '1234', 'khulna', 'researcher', '$2y$10$XPigiPVoEFeBsj93TBWNRO9EGdaZSsIm5Ounl7v/T48rti8F441pW', 'researcher', '1750413545_Screenshot (7).png', '2025-06-20 09:59:05', 22.46444160, 90.38426200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crops`
--
ALTER TABLE `crops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `researcher_id` (`researcher_id`);

--
-- Indexes for table `required_crops`
--
ALTER TABLE `required_crops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `research_projects`
--
ALTER TABLE `research_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `crops`
--
ALTER TABLE `crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `required_crops`
--
ALTER TABLE `required_crops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `research_projects`
--
ALTER TABLE `research_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `crops`
--
ALTER TABLE `crops`
  ADD CONSTRAINT `crops_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_ibfk_1` FOREIGN KEY (`researcher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
