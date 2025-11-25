-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 03:08 AM
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
-- Database: `unity_pgsys_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `accId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL COMMENT '50 max but 8 min ',
  `saltedPass` varchar(60) NOT NULL COMMENT 'salted pass',
  `mobileNum` varchar(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `statusId` int(11) NOT NULL,
  `pgCode` int(11) NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accId`, `username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`, `pgCode`, `email`) VALUES
(56, '111', '$2y$10$dQrdUq0QJyzueQ2X0geShOoMh1q2A2DOoB4l.lmpS/1tYlEqwEgum', '111', 3, 4, 250315, '11@11.com'),
(57, 'test11', '$2y$10$r8cly18qGTS19oMxMRpm6ewFv88LF1xrsfQEzvPxEGELIzt7NtOPS', '123123123', 3, 4, 250316, '1212@1.com'),
(59, 'w123', '$2y$10$VeGr6mxDbWIgLYgYhEysf.lUY02Yp7zWdw/NyP1WbFbs7T.qFKluu', '12345123', 3, 4, 250318, '12@m.com'),
(60, 'abc', '$2y$10$KK.dWStOj2LQ1Y/y8YXF7.2U0dXI94PgUFI/B6BvvH0PZkxILvJ6a', '321321', 3, 4, 250321, 'fontejoedel1@gmail.com'),
(61, 'admin', '$2y$10$iMdqYHilF5Op.ybY9e.Uuel0wrnVBrrGYfOr1erZtBDSC7yC1l2se', '0912345678', 1, 1, 250322, 'admin@admin.com');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `addressId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `location` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `user_id`, `type`, `DateCreated`, `location`) VALUES
(1, 250322, 'ID', '2025-11-22 03:00:15', 'me.jpg'),
(2, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaa'),
(3, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaaa'),
(4, 250322, 'ID', '2025-11-22 03:07:55', 'aaaaaaaaaaa');

-- --------------------------------------------------------

--
-- Table structure for table `members_team`
--

CREATE TABLE `members_team` (
  `id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members_team`
--

INSERT INTO `members_team` (`id`, `team_id`, `member_id`) VALUES
(1, 1, 250323),
(2, 1, 250324);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `dateOfBirth` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) DEFAULT NULL,
  `profileImage` text DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`userId`, `firstName`, `lastName`, `gender`, `description`, `dateOfBirth`, `date_created`, `role_id`, `profileImage`, `address`) VALUES
(250315, '111', '111', 'male', NULL, '2025-11-04', '2025-11-15 18:33:08', NULL, NULL, NULL),
(250316, 'test11', '111', 'female', NULL, '2025-11-03', '2025-11-15 18:33:08', NULL, NULL, NULL),
(250318, 'qwq', 'q1q', 'female', NULL, '2025-10-27', '2025-11-15 18:33:08', NULL, NULL, NULL),
(250319, 'tqwe', '12jkqjhdkj', 'male', NULL, '2025-10-29', '2025-11-15 18:33:08', NULL, NULL, NULL),
(250320, 'tqwe', '12jkqjhdkj', 'male', NULL, '2025-10-29', '2025-11-15 18:33:08', NULL, NULL, NULL),
(250321, 'tqwe', '12jkqjhdkj', 'male', NULL, '2025-10-28', '2025-11-15 18:33:32', NULL, NULL, NULL),
(250322, 'admin', 'admin', 'female', NULL, '2025-10-28', '2025-11-15 19:03:32', NULL, 'profile_6909db8442615.jpg', NULL),
(250323, 'Response Team1', 'Response Team1', 'Team', NULL, '2025-11-18', '2025-11-21 19:23:11', 2, NULL, NULL),
(250324, 'Response Team2', 'Rs', 'Team', NULL, '2025-11-01', '2025-11-21 19:23:11', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `report_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `summary` text DEFAULT NULL,
  `legit_status` varchar(20) DEFAULT NULL,
  `ml_category` varchar(100) DEFAULT NULL,
  `severity` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `name`, `report_type`, `description`, `location`, `latitude`, `longitude`, `status`, `created_at`, `summary`, `legit_status`, `ml_category`, `severity`) VALUES
(25, 250318, 'w123', 'Fire', '<script>alert(\'XSS Attack!\');</script>', 'Lat: 13.99804, Lng: 121.10384', '13.998036539606128', '121.10383987426759', 'Pending', '2025-11-14 19:02:32', NULL, NULL, 'Fire', NULL),
(26, 250321, 'abc', 'Rescue', 'aaa', 'Lat: 13.99804, Lng: 121.12976', '13.998036539606128', '121.12976074218751', 'Pending', '2025-11-15 10:47:37', NULL, NULL, 'Rescue', NULL),
(29, 250316, 'test11', 'Fire', 'qwqw', 'Lat: 13.99037, Lng: 121.11002', '13.990374480636198', '121.1100196838379', 'Pending', '2025-11-15 22:28:23', NULL, NULL, 'Fire', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_images`
--

CREATE TABLE `report_images` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_images`
--

INSERT INTO `report_images` (`id`, `report_id`, `photo`) VALUES
(1, 29, 'test'),
(2, 26, 'tetst'),
(3, 29, 'test'),
(4, 29, 'tetst'),
(5, 29, '1762441784_gojo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `response_team`
--

CREATE TABLE `response_team` (
  `team_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `email` varchar(30) NOT NULL,
  `address` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response_team`
--

INSERT INTO `response_team` (`team_id`, `name`, `contact_number`, `is_active`, `email`, `address`, `latitude`, `longitude`) VALUES
(1, 'Team1', '12345678901', 1, '12@m.com', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleId` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleId`, `name`, `Description`) VALUES
(1, 'Admin', 'System administrator with full access'),
(2, 'Response_T', 'Manage Reports'),
(3, 'User', 'Regular user with basic access'),
(4, 'Guest', 'Limited access for temporary users');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusId` int(11) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusId`, `Name`, `Description`) VALUES
(1, 'Active', 'Account is active and can access the system'),
(2, 'Inactive', 'Account is temporarily disabled'),
(3, 'Suspended', 'Account suspended due to policy violation'),
(4, 'Pending', 'Account awaiting activation');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`accId`),
  ADD UNIQUE KEY `profileId` (`pgCode`),
  ADD UNIQUE KEY `pgCode` (`pgCode`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `mobileNum` (`mobileNum`),
  ADD KEY `account_ibfk_1` (`statusId`),
  ADD KEY `roleId` (`roleId`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`addressId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `members_team`
--
ALTER TABLE `members_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_ibfk_1` (`user_id`);

--
-- Indexes for table `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `response_team`
--
ALTER TABLE `response_team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `members_team`
--
ALTER TABLE `members_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250325;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `response_team`
--
ALTER TABLE `response_team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`statusId`) REFERENCES `status` (`statusId`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`roleId`) REFERENCES `roles` (`roleId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `account_ibfk_3` FOREIGN KEY (`pgCode`) REFERENCES `profile` (`userId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `profile` (`userId`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_team`
--
ALTER TABLE `members_team`
  ADD CONSTRAINT `members_team_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `profile` (`userId`) ON UPDATE SET NULL,
  ADD CONSTRAINT `members_team_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `response_team` (`team_id`) ON UPDATE SET NULL;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roleId`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `report_images`
--
ALTER TABLE `report_images`
  ADD CONSTRAINT `report_images_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
