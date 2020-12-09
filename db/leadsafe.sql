-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2020 at 05:05 AM
-- Server version: 5.6.47-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leadsafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `userType` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Admin',
  `profileImage` varchar(255) NOT NULL,
  `contactNumber` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Active',
  `authToken` text NOT NULL,
  `passToken` text NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullName`, `email`, `password`, `userType`, `profileImage`, `contactNumber`, `status`, `authToken`, `passToken`, `crd`, `upd`) VALUES
(1, 'Admin', 'admin@admin.com', '$2y$10$SDp3YVHXEL4sCKYaMudsC.JsEceJyE83JmHWLXsC7mKAObz/MlyBi', 1, 'gG10qye8kPotzWxu.jpg', '(01555) 777777', 1, '91ef292ab467f212a9993402e507d2ef11523d3b', 'e5309a9e62031ca2acfe429e2930c5a2a90dcf1d', '2019-08-01 13:15:47', '2020-06-04 11:46:12'),
(2, 'Vaibhav', 'vaibhavsharma.otc@gmail.com', '$2y$10$94HB8LW1aF0Ak9Sm5L3PvOZMc4Vu9eVi4WodjHo8bd9sOIw3AI38C', 1, 'dG8hgf6oaIqcwRD5.jpg', '(121) 212-1212', 1, 'c62d4a8735a6b908e4880acde5bcd86ceb89212f', 'f039aff9f58a4ed5150ab364b410681f7f7d23db', '2019-08-01 14:03:16', '2019-11-11 11:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskId` bigint(20) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Active',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `name`, `description`, `status`, `crd`, `upd`) VALUES
(1, 'Best Practices for Designing a', 'The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”', 1, '2020-05-27 10:19:30', '2020-05-27 10:19:30'),
(5, 'leadsafe', 'leadsafe', 1, '2020-05-29 13:42:10', '2020-05-29 13:42:10'),
(6, 'test drag and drop', 'The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”', 1, '2020-06-01 07:02:51', '2020-06-01 07:02:51'),
(9, 'test123', 'test', 1, '2020-06-03 08:39:42', '2020-06-03 08:51:19'),
(12, 'Superadmin Lorem Ipsum is sim', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 1, '2020-06-04 07:37:53', '2020-06-04 11:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `task_meta`
--

CREATE TABLE `task_meta` (
  `taskmetaId` bigint(20) NOT NULL,
  `taskId` bigint(20) NOT NULL,
  `fileType` enum('TEXT','IMAGE','VIDEO') NOT NULL,
  `file` text NOT NULL,
  `description` text NOT NULL,
  `sorting_order` bigint(20) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_meta`
--

INSERT INTO `task_meta` (`taskmetaId`, `taskId`, `fileType`, `file`, `description`, `sorting_order`, `crd`, `upd`) VALUES
(1, 1, 'TEXT', '', 'The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”', 1, '2020-06-03 16:13:56', '2020-05-27 10:19:30'),
(2, 1, 'TEXT', '', 'The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”', 3, '2020-06-03 16:14:28', '2020-05-27 10:19:30'),
(3, 1, 'IMAGE', '2347087aefeeb7632c1587477ea81746.png', '', 4, '2020-06-03 16:14:09', '2020-05-27 10:19:30'),
(4, 1, 'IMAGE', '376f0ca70a1900a9ec768ef25b793522.png', '', 2, '2020-06-03 16:14:28', '2020-05-27 10:19:30'),
(5, 1, 'VIDEO', '3db54b72da813f584eaf0ca482408554.mp4', '', 5, '2020-06-03 16:13:56', '2020-05-27 10:19:30'),
(6, 1, 'VIDEO', '957fd74789b2ad1344f351c09fd6f084.mp4', '', 7, '2020-06-03 16:14:28', '2020-05-27 10:19:30'),
(11, 5, 'TEXT', '', 'step 1\r\nstep 2\r\nstep 3', 2, '2020-06-04 07:17:01', '2020-05-29 13:42:10'),
(12, 5, 'IMAGE', '6550c1fb4992c49837031acc5bbe7471.jpg', '', 1, '2020-06-04 07:17:01', '2020-05-29 13:42:10'),
(13, 5, 'IMAGE', 'bc619370637dcbe3208650b15385453b.jpg', '', 5, '2020-06-04 07:19:48', '2020-05-29 13:42:10'),
(15, 1, 'IMAGE', '4b266610af1735a0789056244b03f893.jpg', '', 6, '2020-06-03 16:14:28', '2020-05-30 13:39:28'),
(16, 5, 'TEXT', '', 'The term Responsive design means developing a website in a way that adapts all the computer screen resolutions. Particularly this concept allows a 4 column layout that is 1292px wide, on 1025px wide screen that is divided into 2 columns automatically. It is adaptable for android phones and tablet screens. This designing method is known as “responsive web design”The term Responsive design means dev', 4, '2020-06-04 07:19:48', '2020-06-01 06:56:36'),
(18, 6, 'IMAGE', '50081a9795a162ea77774b6cec094aaa.jpg', '', 3, '2020-06-03 08:45:22', '2020-06-01 07:03:35'),
(19, 6, 'VIDEO', 'abc9f758d9b5892860432db994095766.mp4', '', 5, '2020-06-03 08:45:22', '2020-06-01 07:04:19'),
(32, 9, 'TEXT', '', 'step 1', 2, '2020-06-03 09:26:14', '2020-06-03 08:40:16'),
(33, 9, 'TEXT', '', 'step 2', 1, '2020-06-03 09:26:14', '2020-06-03 08:40:42'),
(34, 9, 'VIDEO', 'b9b55d9d00fff80b75bff2abb151498d.jpg', '', 3, '2020-06-03 09:26:14', '2020-06-03 08:41:29'),
(35, 9, 'IMAGE', '87a19eeb064323ad15160bd37f303e84.jpg', '', 4, '2020-06-03 09:26:14', '2020-06-03 08:41:59'),
(36, 6, 'TEXT', '', 'step 1', 2, '2020-06-03 08:45:27', '2020-06-03 08:44:39'),
(37, 6, 'TEXT', '', 'step 2', 1, '2020-06-03 08:45:27', '2020-06-03 08:44:55'),
(38, 9, 'IMAGE', '45841bf9874fc23527b12a6006705ca7.jpg', '', 5, '2020-06-03 09:26:14', '2020-06-03 09:25:59'),
(40, 9, 'VIDEO', 'bd7a329263785656ec1db49c40aeb0b2.mov', '', 0, '2020-06-03 12:46:36', '2020-06-03 12:46:36'),
(41, 9, 'VIDEO', '81c482e6f5d515a946df893a25591827.mov', '', 0, '2020-06-03 12:47:04', '2020-06-03 12:47:04'),
(42, 9, 'TEXT', '', 'TEXT1', 0, '2020-06-03 12:47:31', '2020-06-03 12:47:31'),
(50, 1, 'TEXT', '', 'steps 1 to complete tASK', 0, '2020-06-03 16:15:12', '2020-06-03 16:15:12'),
(51, 5, 'VIDEO', 'd807e0704624942d2720e40bcbd68de9.mp4', '', 3, '2020-06-04 07:19:48', '2020-06-04 07:18:14'),
(60, 12, 'TEXT', '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,', 2, '2020-06-04 11:50:41', '2020-06-04 11:48:00'),
(61, 12, 'IMAGE', '4b410788ef56ac69200e8d4245428031.jpg', '', 1, '2020-06-04 11:50:24', '2020-06-04 11:49:18'),
(62, 12, 'VIDEO', '6af258e418c74ff88e2ba65e95772612.MOV', '', 3, '2020-06-04 11:50:24', '2020-06-04 11:49:58'),
(63, 12, 'VIDEO', 'ce4223fe26e4d266cfde21e85126bc68.mp4', '', 0, '2020-06-04 11:58:07', '2020-06-04 11:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `profileImage` text NOT NULL,
  `contactNumber` varchar(255) NOT NULL,
  `userType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:Customer,2:Driver',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:Active ,0:Inactive',
  `authToken` text NOT NULL,
  `passToken` text NOT NULL,
  `deviceType` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:Web,1:Android,2:IOS',
  `deviceToken` text NOT NULL,
  `verifyEmail` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:Verify',
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskId`);

--
-- Indexes for table `task_meta`
--
ALTER TABLE `task_meta`
  ADD PRIMARY KEY (`taskmetaId`),
  ADD KEY `taskId` (`taskId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `task_meta`
--
ALTER TABLE `task_meta`
  MODIFY `taskmetaId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_meta`
--
ALTER TABLE `task_meta`
  ADD CONSTRAINT `task_meta_ibfk_1` FOREIGN KEY (`taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
