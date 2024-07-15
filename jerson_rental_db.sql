-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 10:23 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jerson_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(65, '093'),
(66, 'Studio');

-- --------------------------------------------------------

--
-- Table structure for table `houseaccounts`
--

CREATE TABLE `houseaccounts` (
  `id` int(50) NOT NULL,
  `houses_id` int(50) NOT NULL,
  `elec_accname` varchar(50) NOT NULL,
  `elec_accnum` int(50) NOT NULL,
  `water_accname` varchar(50) NOT NULL,
  `water_accnum` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `houseaccounts`
--

INSERT INTO `houseaccounts` (`id`, `houses_id`, `elec_accname`, `elec_accnum`, `water_accname`, `water_accnum`) VALUES
(1, 846, 'aj', 765, '', 0),
(2, 847, 'ange', 1, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(50) NOT NULL,
  `house_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `category_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_name`, `price`, `category_id`) VALUES
(838, '54', 87, 65),
(839, '54', 23, 65),
(840, '123', 12, 65),
(841, '123', 123, 65),
(842, '123', 3214, 65),
(843, '2343', 543, 65),
(844, '2', 43, 65),
(845, '54', 123, 65),
(846, '54', 123, 65),
(847, '12', 54, 65);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(50) NOT NULL,
  `sender_id` int(50) NOT NULL,
  `receiver_id` int(50) NOT NULL,
  `users_id` int(50) NOT NULL,
  `message` varchar(500) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `users_id`, `message`, `timestamp`, `image_path`) VALUES
(4, 40, 41, 10, 'Lupercal  _Horus', '2024-06-23 02:15:13', NULL),
(6, 41, 40, 41, '123432', '2024-06-23 02:27:10', NULL),
(7, 40, 41, 40, 'lupercall', '2024-06-24 23:17:17', NULL),
(8, 40, 41, 40, 'lupercall', '2024-06-24 23:17:43', NULL),
(9, 40, 41, 40, 'eyjey', '2024-06-24 23:43:56', NULL),
(10, 40, 41, 40, 'Mango', '2024-06-24 23:45:44', NULL),
(11, 40, 41, 40, '123', '2024-06-24 23:50:31', NULL),
(12, 40, 41, 40, 'circumstances', '2024-06-25 00:03:47', NULL),
(13, 40, 41, 40, 'circumstances', '2024-06-25 00:05:23', NULL),
(14, 41, 40, 41, 'Horus Heressysyysysysys\r\n<p>Terra</p>', '2024-06-25 00:35:13', NULL),
(15, 41, 40, 41, '<strong>This text is important!</strong>\r\n', '2024-06-25 00:35:36', NULL),
(16, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:13:52', NULL),
(17, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:34:23', NULL),
(18, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:36:43', NULL),
(19, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:56:48', NULL),
(20, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:13:36', NULL),
(21, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:14:10', NULL),
(22, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:14:24', NULL),
(23, 40, 41, 40, '123', '2024-06-28 00:51:17', NULL),
(24, 40, 41, 40, '124', '2024-06-28 00:51:25', NULL),
(25, 40, 41, 40, '124', '2024-06-28 01:05:22', NULL),
(26, 40, 41, 40, '124', '2024-06-28 01:15:05', './uploads/667d9e19a5fbe.jpg'),
(27, 40, 41, 40, '123', '2024-06-28 01:15:27', NULL),
(28, 40, 41, 40, '123', '2024-06-28 01:15:29', NULL),
(29, 40, 41, 40, '1234', '2024-06-28 01:15:29', NULL),
(30, 40, 41, 40, '123', '2024-06-28 01:15:30', NULL),
(31, 40, 41, 40, '123', '2024-06-28 01:15:34', './uploads/667d9e36bfcc8.jpg'),
(32, 40, 41, 40, '123', '2024-06-28 01:15:36', './uploads/667d9e387a135.jpg'),
(33, 40, 41, 40, '123', '2024-06-28 01:15:37', './uploads/667d9e39d12c0.jpg'),
(34, 40, 41, 40, '124', '2024-06-28 01:17:49', './uploads/667d9ebd1c2cf.jpg'),
(35, 40, 41, 40, '124', '2024-06-28 01:24:24', './uploads/667da048543f7.jpg'),
(36, 40, 41, 40, '124', '2024-06-28 01:24:31', './uploads/667da04f80989.jpg'),
(37, 40, 41, 40, '55', '2024-06-28 01:24:35', NULL),
(38, 40, 41, 40, '123', '2024-06-28 01:35:55', './uploads/667da2fb0bd4c.jpeg'),
(39, 40, 41, 40, '123', '2024-06-28 01:36:21', './uploads/667da315916a3.jpeg'),
(40, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b081ff.jpeg'),
(41, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b33532.jpeg'),
(42, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b59034.jpeg'),
(43, 40, 41, 40, '123', '2024-06-28 01:36:29', './uploads/667da31d18a40.jpeg'),
(44, 40, 41, 40, '123', '2024-06-28 01:43:29', './uploads/667da4c173227.jpeg'),
(45, 40, 41, 40, 'aj', '2024-06-28 01:47:47', './uploads/667da5c34850a.jpg'),
(46, 40, 41, 40, 'jhh', '2024-06-28 01:49:45', './uploads/667da63905825.jpeg'),
(47, 40, 41, 40, 'aj', '2024-06-28 01:49:53', NULL),
(48, 40, 41, 40, 'ajj', '2024-06-28 01:49:56', './uploads/667da644d25b5.jpeg'),
(49, 40, 41, 40, 'ajj', '2024-06-28 01:49:57', './uploads/667da64568423.jpeg'),
(50, 40, 41, 40, 'ajj', '2024-06-28 01:49:57', './uploads/667da64592811.jpeg'),
(51, 40, 41, 40, 'ajj', '2024-06-28 01:50:21', './uploads/667da65d39193.jpg'),
(52, 40, 41, 40, 'hh', '2024-06-28 01:51:34', './uploads/667da6a64db49.jpeg'),
(53, 40, 41, 40, 'hah', '2024-06-28 01:53:30', './uploads/667da71a2ffb0.jpg'),
(54, 40, 41, 40, '54', '2024-06-28 01:56:53', './uploads/667da7e541801.png'),
(55, 40, 41, 40, '55', '2024-06-28 01:57:06', './uploads/667da7f2ab330.jpg'),
(56, 40, 41, 40, '23', '2024-06-28 01:59:34', './uploads/667da886ad05e.jpeg'),
(57, 40, 41, 40, '12', '2024-06-28 01:59:41', NULL),
(58, 40, 41, 40, '5432', '2024-06-28 02:00:56', './uploads/667da8d856170.jpg'),
(59, 40, 41, 40, '243', '2024-06-28 02:04:09', NULL),
(60, 40, 41, 40, '123', '2024-06-28 02:06:53', './uploads/667daa3da47d6.jpg'),
(61, 40, 41, 40, '554', '2024-06-28 02:08:26', './uploads/667daa9ab3676.jpg'),
(62, 40, 41, 40, '355', '2024-06-28 02:10:13', './uploads/667dab05e7083.jpg'),
(63, 40, 41, 40, '231', '2024-06-28 02:10:21', './uploads/667dab0dab5e1.jpg'),
(64, 40, 0, 40, '3434', '2024-06-28 02:13:04', './uploads/667dabb037356.jpeg'),
(65, 40, 0, 40, '234', '2024-06-28 02:17:18', './uploads/667dacae616d5.jpeg'),
(66, 40, 41, 40, '34345', '2024-06-28 02:18:08', './uploads/667dace00f555.jpeg'),
(67, 40, 41, 40, '2334', '2024-06-28 02:22:28', './uploads/667dade4df419.jpg'),
(68, 40, 41, 40, '432', '2024-06-28 02:28:53', './uploads/667daf651db3c.jpg'),
(69, 40, 41, 40, '435', '2024-06-28 02:29:33', './uploads/667daf8dbd93b.jpg'),
(70, 40, 41, 40, '432', '2024-06-28 02:33:28', './uploads/667db078c94bd.jpg'),
(71, 40, 41, 40, '432', '2024-06-28 02:33:37', './uploads/667db081c31b2.jpg'),
(72, 40, 41, 40, '4325', '2024-06-28 02:37:37', './uploads/667db171d711d.jpg'),
(73, 40, 41, 40, '4324', '2024-06-28 02:39:03', './uploads/667db1c7ca57c.jpeg'),
(74, 40, 0, 40, '325', '2024-06-28 02:44:55', './uploads/667db32712902.jpg'),
(108, 40, 0, 40, '12345', '2024-06-28 15:39:25', './uploads/667e68adce3c8.png'),
(121, 40, 0, 40, 'asd', '2024-06-28 16:04:27', NULL),
(122, 40, 0, 40, '2134', '2024-06-28 16:11:30', NULL),
(123, 40, 0, 40, '234', '2024-06-28 16:13:05', NULL),
(124, 40, 0, 40, '543', '2024-06-28 16:13:10', './uploads/667e70967e86c.jpg'),
(125, 40, 0, 40, '123', '2024-06-28 16:21:31', NULL),
(126, 40, 0, 40, '123', '2024-06-28 16:22:04', NULL),
(127, 40, 0, 40, '0', '2024-06-28 16:25:38', '534'),
(128, 40, 0, 40, '55', '2024-06-28 16:27:56', NULL),
(129, 40, 0, 40, '123', '2024-06-28 16:28:45', NULL),
(130, 40, 0, 40, '23154', '2024-06-28 16:35:19', NULL),
(131, 40, 0, 40, '123', '2024-06-28 16:36:34', NULL),
(132, 40, 0, 40, '123', '2024-06-28 16:37:58', NULL),
(133, 40, 0, 40, '234', '2024-06-28 16:38:27', NULL),
(134, 40, 0, 40, '55553434', '2024-06-28 16:38:54', NULL),
(135, 40, 0, 40, '908978', '2024-06-28 16:39:16', NULL),
(136, 40, 0, 40, '255 c', '2024-06-28 16:43:54', NULL),
(137, 40, 40, 40, '255 crt', '2024-06-28 16:44:32', NULL),
(138, 40, 0, 40, '5342', '2024-06-28 16:47:49', NULL),
(139, 40, 41, 40, '532', '2024-06-28 16:49:44', NULL),
(140, 40, 41, 40, '32', '2024-06-28 16:49:49', NULL),
(141, 40, 41, 40, '24', '2024-06-28 16:49:55', NULL),
(142, 40, 41, 40, '43245435', '2024-06-28 16:53:15', NULL),
(143, 41, 40, 41, 'I am so high in time', '2024-06-28 19:12:13', NULL),
(144, 41, 40, 41, 'huha', '2024-06-28 19:12:19', NULL),
(145, 40, 41, 40, '55', '2024-06-28 19:13:36', NULL),
(146, 40, 41, 40, '24', '2024-06-28 19:13:41', NULL),
(147, 40, 41, 40, '24', '2024-06-28 19:13:46', NULL),
(148, 40, 41, 40, 'huha on time', '2024-06-28 19:14:19', NULL),
(149, 40, 41, 40, ' ', '2024-06-28 19:23:08', NULL),
(150, 40, 41, 40, '154', '2024-06-28 19:30:24', NULL),
(151, 40, 41, 40, '12', '2024-06-28 19:30:39', NULL),
(152, 40, 41, 40, '22', '2024-06-28 19:30:54', NULL),
(153, 40, 41, 40, '55', '2024-06-28 19:30:56', NULL),
(154, 40, 41, 40, '22', '2024-06-28 19:31:01', NULL),
(155, 40, 41, 40, '23', '2024-06-30 03:18:21', NULL),
(156, 40, 41, 40, '24', '2024-06-30 03:27:06', NULL),
(157, 40, 41, 40, '32', '2024-06-30 21:44:35', NULL),
(158, 40, 41, 40, '123', '2024-07-02 18:12:19', NULL),
(159, 40, 41, 40, '123', '2024-07-02 18:15:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paper_categories`
--

CREATE TABLE `paper_categories` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paper_categories`
--

INSERT INTO `paper_categories` (`id`, `name`, `created_at`) VALUES
(63, '3432', '2024-07-01 13:58:59'),
(68, 'asd', '2024-07-02 18:43:27'),
(70, 'affd', '2024-07-03 16:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `paper_files`
--

CREATE TABLE `paper_files` (
  `id` int(50) NOT NULL,
  `category_id` int(50) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paper_files`
--

INSERT INTO `paper_files` (`id`, `category_id`, `file_name`, `file_url`, `uploaded_at`) VALUES
(1, 63, 'Mental Omega Screenshot 2023.04.11 - 12.13.09.91.png', '../uploads/Mental Omega Screenshot 2023.04.11 - 12.13.09.91.png', '2024-07-01 17:04:33'),
(3, 63, 'received_993104588452786.jpeg', '../uploads/received_993104588452786.jpeg', '2024-07-01 17:08:14'),
(23, 63, '6685763997254.docx', '../uploads/6685763997254.docx', '2024-07-03 16:03:05'),
(25, 63, 'Untitled_66858228aefaf.png', '../uploads/Untitled_66858228aefaf.png', '2024-07-03 16:54:00'),
(29, 63, 'rmPtZ1eY_400x400_6689056f5fbb2.jpg', '../uploads/rmPtZ1eY_400x400_6689056f5fbb2.jpg', '2024-07-06 08:50:55'),
(31, 63, 'BooTails Petspa & Mobile Grooming-Unleash Merchant Partnership Letter_66890be9852b5.docx', '../uploads/BooTails Petspa & Mobile Grooming-Unleash Merchant Partnership Letter_66890be9852b5.docx', '2024-07-06 09:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` double NOT NULL,
  `tenants_id` int(50) NOT NULL,
  `houses_id` int(50) NOT NULL,
  `filepath` varchar(200) NOT NULL,
  `date_payment` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `name`, `amount`, `tenants_id`, `houses_id`, `filepath`, `date_payment`) VALUES
(7, 'Lucious Black Gorehound', 0, 63, 847, '../uploads/GAMEPOSTER_666b08e7a6d95.jpg', '2024-06-13'),
(8, 'Lucious Black Gorehound', 54, 63, 847, '../uploads/GAMEPOSTER_666b08fddb94a.jpg', '2024-06-13'),
(9, 'Lucious Black Gorehound', 54, 63, 847, '../uploads/received_993104588452786_666b0958540c1.jpeg', '2024-06-14'),
(10, 'Mad3434 Good', 9000, 61, 846, '../uploads/2023-11-19 13-03-19_6683d246aae55.mp4', '2024-07-02');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `users_id` varchar(50) NOT NULL,
  `users_username` varchar(50) NOT NULL,
  `house_id` int(50) NOT NULL,
  `house_category` varchar(50) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date DEFAULT NULL,
  `date_preferred` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `fname`, `mname`, `lname`, `contact`, `users_id`, `users_username`, `house_id`, `house_category`, `date_start`, `date_end`, `date_preferred`) VALUES
(61, ' NeloAngelo', 'Mad3434', 'Good', '123', '41', 'aj', 846, '093', '2024-06-09', NULL, NULL),
(62, 'John', 'Payo', 'Junio', '0983', '69', 'aj', 839, '09', '2024-06-09', NULL, NULL),
(63, 'Lucious', 'Black', 'Gorehound', '093414', '70', 'OuterHeaven', 847, '093', '2024-05-01', NULL, '2024-05-01'),
(66, 'Romeo', 'Juliet', 'Echo', '123', '71', 'Bad', 847, '093', '2024-05-01', NULL, '2024-05-01'),
(67, 'test fname', 'testmiddle', 'testlastname', '0939', '72', 'testing1', 838, '093', '2024-07-07', NULL, '2024-06-07'),
(68, 'test fname2', 'test middle2', 'testlastname2', '123432', '73', 'testing2', 838, '093', '2024-07-07', NULL, '2024-03-13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `middlename`, `lastname`, `password`, `email`, `role`, `Date`) VALUES
(40, 'admin12', '', '', '', '123', '', 'admin', '2024-05-25 18:51:47'),
(41, 'aj', ' ', 'Mad3434', 'Good', '123', '', 'user', '2024-05-25 18:52:14'),
(46, 'Mad Max1', 'as12', 'saasasa34', 'ddasasas56', 'lastname_murder', '', 'admin', '2024-06-02 22:18:16'),
(50, 'asd_1', 'asd', 'asd', 'asd', 'sdf', '', 'admin', '2024-06-03 17:42:54'),
(51, 'asd_ ', 'asd', 'asd', 'asd', 'asd', '', 'admin', '2024-06-03 17:43:08'),
(60, 'asd', 'asd', 'asd', 'asd', '     asdasd', '', 'admin', '2024-06-03 18:43:49'),
(61, 'asd', 'asd', 'asd', 'asd', 'asd  ', '', 'admin', '2024-06-03 18:44:43'),
(62, 'ajb', 'aj', 'aj', 'aj', '__  123', '', 'admin', '2024-06-03 18:47:08'),
(67, 'gar', 'gar', 'gar', 'gar', 'gar_1', '', 'admin', '2024-06-03 18:57:00'),
(68, 'aj', 'aj', 'aj', 'aj', 'pass', '', 'admin', '2024-06-03 19:14:31'),
(69, 'aj', 'John', 'Payo', 'Junio', '1234', '', 'user', '2024-06-09 02:18:40'),
(70, 'OuterHeaven', 'Lucious', 'Black', 'Gorehound', '123', '', 'user', '2024-06-12 22:05:52'),
(71, 'Bad', 'Romeo', 'Juliet', 'Echo', '123', '', 'user', '2024-06-14 00:44:38'),
(72, 'testing1', 'test fname', 'testmiddle', 'testlastname', '123', 'redrider0939@gmail.com', 'user', '2024-07-07 19:19:18'),
(73, 'testing2', 'test fname2', 'test middle2', 'testlastname2', '123', 'ajunio.feudiliman@gmail.com', 'user', '2024-07-07 19:41:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id_fk` (`category_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paper_categories`
--
ALTER TABLE `paper_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paper_files`
--
ALTER TABLE `paper_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id_fk` (`category_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=848;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `paper_categories`
--
ALTER TABLE `paper_categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `paper_files`
--
ALTER TABLE `paper_files`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `houses`
--
ALTER TABLE `houses`
  ADD CONSTRAINT `houses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
