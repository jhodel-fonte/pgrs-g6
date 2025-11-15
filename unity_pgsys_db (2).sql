-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 01:22 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewAccount` (IN `uname` VARCHAR(50), IN `pass` VARCHAR(60), IN `num` VARCHAR(11), IN `role` INT, IN `status` INT, IN `code` INT, IN `email` VARCHAR(60))   INSERT INTO `account`(`username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`, `pgCode`, `email`) 
VALUES (uname, pass, num, role, status, code, email)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewAddress` (IN `profileID` INT, IN `street` VARCHAR(100), IN `city` VARCHAR(50), IN `province` VARCHAR(50), IN `postalCode` VARCHAR(5), IN `country` VARCHAR(50))   INSERT INTO `address`(`userId`, `street`, `city`, `province`, `postalCode`, `country`) 
VALUES (profileID, street, city, province, postalCode, country)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewProfile` (IN `fname` VARCHAR(30), IN `lname` VARCHAR(30), IN `gender` VARCHAR(10), IN `DOB` DATE)   BEGIN
INSERT INTO `profile`(`firstName`, `lastName`, `gender`, `dateOfBirth`) 
VALUES (fname, lname, gender, DOB);

SELECT LAST_INSERT_ID() AS pgId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetConfirnedUserAcc` (IN `InID` INT)   SELECT 
    a.accId,
    p.userId as pgCode,
    a.username,
    a.email,
    a.mobileNum,
    p.firstName,
    p.lastName,
    p.gender,
    p.dateOfBirth
FROM 
    account AS a
INNER JOIN 
    profile AS p ON a.pgCode = p.userId
WHERE a.accId = InID
LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProfileById` (IN `accid` INT)   SELECT * FROM `profile` WHERE `userId` = accid LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `isUsernameAvailable` (IN `uName` VARCHAR(50))   SELECT `username` FROM `account` where `username` = uName LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectUserAccById` (IN `id` INT)   SELECT * FROM account WHERE account.accId = id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectUserAccByUname` (IN `uname` VARCHAR(60))   SELECT * FROM `account` WHERE `username` = uname LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateAccById` (IN `id` INT, IN `colName` VARCHAR(64), IN `newValue` VARCHAR(60))   BEGIN
    SET @sql = CONCAT('UPDATE account SET ', colName, ' = ', newValue, ' WHERE accId = ', id);

    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

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
(56, '111', '$2y$10$dQrdUq0QJyzueQ2X0geShOoMh1q2A2DOoB4l.lmpS/1tYlEqwEgum', '111', 4, 4, 250315, '11@11.com'),
(57, 'test11', '$2y$10$r8cly18qGTS19oMxMRpm6ewFv88LF1xrsfQEzvPxEGELIzt7NtOPS', '123123123', 4, 4, 250316, '1212@1.com'),
(59, 'w123', '$2y$10$VeGr6mxDbWIgLYgYhEysf.lUY02Yp7zWdw/NyP1WbFbs7T.qFKluu', '12345123', 4, 4, 250318, '12@m.com');

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
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dateOfBirth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`userId`, `firstName`, `lastName`, `gender`, `dateOfBirth`) VALUES
(250315, '111', '111', 'male', '2025-11-04'),
(250316, 'test11', '111', 'female', '2025-11-03'),
(250318, 'qwq', 'q1q', 'female', '2025-10-27');

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
  `photo` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `summary` text DEFAULT NULL,
  `delay_status` varchar(20) DEFAULT NULL,
  `legit_status` varchar(20) DEFAULT NULL,
  `ml_category` varchar(100) DEFAULT NULL,
  `ml_summary` text DEFAULT NULL,
  `ml_legit` tinyint(1) DEFAULT 1,
  `ml_delay` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `name`, `report_type`, `description`, `photo`, `location`, `latitude`, `longitude`, `status`, `created_at`, `summary`, `delay_status`, `legit_status`, `ml_category`, `ml_summary`, `ml_legit`, `ml_delay`) VALUES
(25, 250318, 'w123', 'Fire', '<script>alert(\'XSS Attack!\');</script>', NULL, 'Lat: 13.99804, Lng: 121.10384', '13.998036539606128', '121.10383987426759', 'Pending', '2025-11-14 19:02:32', NULL, NULL, NULL, 'Fire', '', 1, 0);

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
(2, 'Manager', 'Can manage users and content'),
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
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_ibfk_1` (`user_id`);

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
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250319;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `profile` (`userId`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
