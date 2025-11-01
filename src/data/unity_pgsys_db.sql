-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 01:37 PM
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewAccount` (IN `uname` VARCHAR(50), IN `pass` VARCHAR(60), IN `num` INT(11), IN `role` INT, IN `status` INT)   INSERT INTO `account`(`username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`) 
VALUES (uname, pass, num, role, status)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewAddress` (IN `profileID` INT, IN `street` VARCHAR(100), IN `city` VARCHAR(50), IN `province` VARCHAR(50), IN `postalCode` VARCHAR(5), IN `country` VARCHAR(50))   INSERT INTO `address`(`userId`, `street`, `city`, `province`, `postalCode`, `country`) 
VALUES (profileID, street, city, province, postalCode, country)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateNewProfile` (IN `fname` VARCHAR(30), IN `lname` VARCHAR(30), IN `gender` VARCHAR(10), IN `DOB` DATE)   INSERT INTO `profile`(`firstName`, `lastName`, `gender`, `dateOfBirth`) 
VALUES (fname, lname, gender, DOB)$$

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
(1, 'meow', '$2y$10$cLfVtvI7Noiuyxd032mZLeI1HNZllHEt8p1QKOtiZgEEekP921qO6', '09171234567', 2, 1, 101, ''),
(2, 'woof', 's@ltP@ss2', '09181234567', 3, 1, 102, ''),
(3, 'chirp', 's@ltP@ss3', '09192223333', 2, 2, 103, ''),
(4, 'buzz', 's@ltP@ss4', '09201112222', 1, 1, 104, ''),
(5, 'quack', 's@ltP@ss5', '09334445555', 3, 3, 105, '');

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
(101, 'Moush', 'Rivera', 'female', '1995-06-12'),
(102, 'Jace', 'Tan', 'male', '1990-03-25'),
(103, 'Luna', 'Santos', 'female', '1998-11-04'),
(104, 'Rico', 'Garcia', 'male', '1987-08-19'),
(105, 'Kaye', 'Lopez', 'female', '2001-01-30'),
(108, 'whis', 'per', 'Female', '2025-10-01'),
(250000, 'whis', 'pers', 'Female', '2025-10-01'),
(250001, 'whis', 'persa', 'Female', '2025-10-01');

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
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `addressId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250002;

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
  ADD CONSTRAINT `account_ibfk_3` FOREIGN KEY (`pgCode`) REFERENCES `profile` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `profile` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
