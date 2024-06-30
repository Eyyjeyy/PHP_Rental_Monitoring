-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 07:18 AM
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
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `users_id`, `message`, `timestamp`) VALUES
(4, 40, 41, 10, 'Lupercal  _Horus', '2024-06-23 02:15:13'),
(6, 41, 40, 41, '123432', '2024-06-23 02:27:10'),
(7, 40, 41, 40, 'lupercall', '2024-06-24 23:17:17'),
(8, 40, 41, 40, 'lupercall', '2024-06-24 23:17:43'),
(9, 40, 41, 40, 'eyjey', '2024-06-24 23:43:56'),
(10, 40, 41, 40, 'Mango', '2024-06-24 23:45:44'),
(11, 40, 41, 40, '123', '2024-06-24 23:50:31'),
(12, 40, 41, 40, 'circumstances', '2024-06-25 00:03:47'),
(13, 40, 41, 40, 'circumstances', '2024-06-25 00:05:23'),
(14, 41, 40, 41, 'Horus Heressysyysysysys\r\n<p>Terra</p>', '2024-06-25 00:35:13'),
(15, 41, 40, 41, '<strong>This text is important!</strong>\r\n', '2024-06-25 00:35:36');

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
(9, 'Lucious Black Gorehound', 54, 63, 847, '../uploads/received_993104588452786_666b0958540c1.jpeg', '2024-06-14');

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
(66, 'Romeo', 'Juliet', 'Echo', '123', '71', 'Bad', 847, '093', '2024-05-01', NULL, '2024-05-01');

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
  `role` varchar(50) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `middlename`, `lastname`, `password`, `role`, `Date`) VALUES
(40, 'admin', '', '', '', '123', 'admin', '2024-05-25 18:51:47'),
(41, 'aj', ' ', 'Mad3434', 'Good', '123', 'user', '2024-05-25 18:52:14'),
(46, 'Mad Max1', 'as12', 'saasasa34', 'ddasasas56', 'lastname_murder', 'admin', '2024-06-02 22:18:16'),
(50, 'asd_1', 'asd', 'asd', 'asd', 'sdf', 'admin', '2024-06-03 17:42:54'),
(51, 'asd_ ', 'asd', 'asd', 'asd', 'asd', 'admin', '2024-06-03 17:43:08'),
(60, 'asd', 'asd', 'asd', 'asd', '     asdasd', 'admin', '2024-06-03 18:43:49'),
(61, 'asd', 'asd', 'asd', 'asd', 'asd  ', 'admin', '2024-06-03 18:44:43'),
(62, 'ajb', 'aj', 'aj', 'aj', '__  123', 'admin', '2024-06-03 18:47:08'),
(67, 'gar', 'gar', 'gar', 'gar', 'gar_1', 'admin', '2024-06-03 18:57:00'),
(68, 'aj', 'aj', 'aj', 'aj', 'pass', 'admin', '2024-06-03 19:14:31'),
(69, 'aj', 'John', 'Payo', 'Junio', '1234', 'user', '2024-06-09 02:18:40'),
(70, 'OuterHeaven', 'Lucious', 'Black', 'Gorehound', '123', 'user', '2024-06-12 22:05:52'),
(71, 'Bad', 'Romeo', 'Juliet', 'Echo', '123', 'user', '2024-06-14 00:44:38');

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
