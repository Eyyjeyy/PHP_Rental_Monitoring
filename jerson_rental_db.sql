-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2024 at 03:15 PM
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
(66, 'Studio'),
(71, 'jjbaa');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `info` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `house_id` int(11) DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `info`, `amount`, `house_id`, `date`) VALUES
(1, 'sample', 'yada yada', 5000, 838, '2024-08-14'),
(4, 'varda', 'gg', 88, 55, '2024-08-15'),
(5, 'varda', '4324', 90.99, NULL, '2024-08-15'),
(6, 'gg', 'varda', 91, NULL, '2024-08-15'),
(7, 'fdsf', 'vardas', 90.9999, NULL, '2024-08-15'),
(8, 'asdas', 'gg', 91, NULL, '2024-08-15'),
(9, 'ss', 'ss', 23, NULL, '2024-08-15'),
(10, 'aasd', 'asd', 91, NULL, '2024-08-15'),
(11, 'bbg', 'sprt', 9000, NULL, '2024-08-21'),
(12, 'testtt', 'yadgdggf', 777, 838, '2024-08-21'),
(13, 'uygh', 'gffdfg', 10000, NULL, '2024-08-21'),
(14, 'yada', 'asddf', 2000, 840, '2024-08-21'),
(15, 'fdsg', 'asda', 200, 846, '2024-08-24');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `admin_id`, `action`, `details`, `timestamp`) VALUES
(1, 40, 'delete', 'Deleted User75', '2024-08-01 18:46:19'),
(2, 40, 'delete', 'Deleted User 76', '2024-08-01 18:48:27'),
(3, 40, 'delete', 'Deleted User, ID: 77', '2024-08-01 18:49:04'),
(4, 40, 'delete', 'Deleted User, ID: <br> 78', '2024-08-06 22:18:24'),
(5, 40, 'delete', 'Deleted User, ID: 79<br> Username: mkmk', '2024-08-06 22:29:57'),
(6, 40, 'Update', 'Updated User, ID: 74<br> Username: test390', '2024-08-08 05:59:14'),
(7, 40, 'Update', 'Updated User, ID: 74<br> New Username: test39099<br> Old Username: test390', '2024-08-08 06:07:55'),
(8, 40, 'Add', 'Added User, ID: 80<br> Username: cancerarty', '2024-08-08 17:49:01'),
(9, 40, 'Delete', 'Deleted House, ID: 851<br> Housename: testkill', '2024-08-08 18:06:14'),
(10, 40, 'Delete', 'Deleted House, ID: 852<br> Housename: sfsdfsdf', '2024-08-08 18:07:15'),
(11, 40, 'Delete', 'Deleted House, ID: 853<br> Housename: asdad<br> Category: <br> Price: 7657', '2024-08-08 18:10:46'),
(12, 40, 'Delete', 'Deleted House, ID: 854<br> Housename: test443<br> Category: Studio<br> Price: 989768', '2024-08-08 18:19:17'),
(13, 40, 'Update', 'Updated House, ID: 855<br> Housename: ggg<br> Category: 093<br> Price: 423425', '2024-08-10 23:56:49'),
(14, 40, 'Update', 'Updated House, ID: 855<br> Housename: ggg43<br> Category: 093<br> Price: 423425', '2024-08-11 00:04:16'),
(15, 40, 'Update', 'Updated House, ID: 855<br>Housename: ggg43233 -> ggg<br>Category: 093 -> 65<br>Price: 423425 -> 423425<br>Electric Account Number: 42345 -> 42345<br>Water Account Number: 5342345 -> 5342345', '2024-08-11 00:40:40'),
(16, 40, 'Delete', 'Deleted House, ID: 855<br> Housename: ggg<br> Category: 093<br> Price: 423425', '2024-08-11 00:51:06'),
(17, 40, 'Update', 'Updated House, ID: 856<br>Housename: ggggg -> gg<br>Category: 093 -> 65<br>Price: 423432 -> 423432<br>Electric Account Number: 3254 -> 3254<br>Water Account Number: 543534 -> 543534', '2024-08-11 00:52:13'),
(18, 40, 'Update', 'Updated House, ID: 856<br>Category: 093 -> 66<br>Price: 423432 -> 423432<br>Electric Account Number: 3254 -> 3254<br>Water Account Number: 543534 -> 543534', '2024-08-11 00:52:46'),
(19, 40, 'Update', 'Updated House, ID: 856<br>Category: Studio -> 66<br>Price: 423432 -> 423432334<br>Electric Account Number: 3254 -> 3254<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:01:31'),
(20, 40, 'Update', 'Updated House, ID: 856<br>Price: 423432334 -> 423<br>Electric Account Number: 3254 -> 3254<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:09:27'),
(21, 40, 'Update', 'Updated House, ID: 856<br>Housename: gg -> gg23<br>Price: 423 -> 423<br>Electric Account Number: 3254 -> 3254<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:35:20'),
(22, 40, 'Update', 'Updated House, ID: 856<br>Price: 423 -> 423<br>Electric Account Number: 3254 -> 325466<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:37:36'),
(23, 40, 'Update', 'Updated House, ID: 856<br>Price: 423 -> 423<br>Electric Account Number: 325466 -> 325466<br>Electric Account Name: asdasd -> asdasd2313<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:38:29'),
(24, 40, 'Update', 'Updated House, ID: 856<br>Price: 423 -> 42322<br>Electric Account Number: 325466 -> 325466<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:40:11'),
(25, 40, 'Update', 'Updated House, ID: 856<br>Housename: gg23 -> gg234<br>Price: 42322 -> 42322<br>Electric Account Number: 325466 -> 325466<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:47:57'),
(26, 40, 'Update', 'Updated House, ID: 856<br>Housename: gg234 -> gg23<br>Price: 42322 -> 42322<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:48:12'),
(27, 40, 'Update', 'Updated House, ID: 856<br>Housename: gg23 -> gg234<br>Price: 42322 -> 42322<br>Electric Account Number: 325466 -> 325466<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:48:37'),
(28, 40, 'Update', 'Updated House, ID: 856<br>Housename: gg234 -> gg23<br>Price: 42322 -> 42322<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:49:54'),
(29, 40, 'Update', 'Updated House, ID: 856<br>Price: 42322 -> 42322<br>Electric Account Number: 325466 -> 3254662<br>Water Account Number: 543534 -> 543534', '2024-08-11 01:50:15'),
(30, 40, 'Delete', 'Deleted Category, ID: 69<br> Username: jk', '2024-08-11 07:19:10'),
(31, 40, 'Update', 'Updated Category, ID: 70<br>Housename: jjk -> jjka', '2024-08-11 08:18:39'),
(32, 40, 'Delete', 'Deleted Category, ID: 70<br> Username: jjka', '2024-08-11 08:22:55'),
(33, 40, 'Add', 'Added Category, ID: 71<br> Username: jjba', '2024-08-11 08:23:00'),
(34, 40, 'Update', 'Updated Category, ID: 71<br>Housename: jjba -> jjbaa', '2024-08-11 08:36:33'),
(35, 40, 'Update', 'Updated Tenant, ID: 71<br>Contact: 09398380416 -> 09398380415', '2024-08-11 09:18:36'),
(36, 40, 'Delete', 'Deleted Tenant, ID: 71<br> Firstname: fnm<br> Middlename: mnm<br> Lastname: lnm<br> User ID: 74<br> Username: test39099<br> House ID: 838', '2024-08-11 12:41:36'),
(37, 40, 'Add', 'Added Tenant, ID: 72<br>First Name: fnm<br>Middle Name: mnm<br>Last Name: lnm<br>Contact: 55555555555<br>User ID: 74<br>Username: test39099<br>House ID: 838', '2024-08-12 01:11:44'),
(38, 40, 'Delete', 'Deleted Tenant, ID: 72<br> Firstname: fnm<br> Middlename: mnm<br> Lastname: lnm<br> User ID: 74<br> Username: test39099<br> House ID: 838', '2024-08-12 01:13:53'),
(39, 40, 'Add', 'Added Tenant, ID: 73<br>First Name: fnm<br>Middle Name: mnm<br>Last Name: lnm<br>Contact: 99999999999<br>User ID: 74<br>Username: test39099<br>House ID: 856<br>House Category: Studio', '2024-08-12 01:14:14'),
(40, 40, 'Message', 'Message, ID: 181<br>', '2024-08-12 03:44:08'),
(41, 40, 'Message', 'Message, ID: 182<br>Receiver, : aj<br>', '2024-08-12 04:09:47'),
(42, 40, 'Add', 'Added Paper Category, ID: 86<br>Category Name, : bibe<br>', '2024-08-12 06:26:44'),
(43, 40, 'Delete', 'Deleted Paper Category, ID: 86<br>Category Name, : bibe<br>', '2024-08-12 08:19:23'),
(44, 40, 'Add', 'Added Paper Category, ID: 87<br>Category Name, : bebe<br>', '2024-08-12 08:23:34'),
(45, 40, 'Delete', 'Deleted Paper, ID: 39<br>Paper Name, : TICAP-14-Awards-Night-Seating-Arrangement_66b9c69d8a495.pdf<br>', '2024-08-12 08:29:09'),
(46, 40, 'Add', 'Added Paper, ID: 40<br>Category Name, : bebe<br>', '2024-08-12 09:00:46'),
(47, 40, 'Add', 'Added Paper, ID: 41<br>Category Name, : bebe<br>File Name, : GAMEPOSTER_66b9d129518a2.jpg<br>', '2024-08-12 09:08:57'),
(48, 40, 'Approve', 'Payment Approved, ID: 12<br>Approval: false -> Accepted', '2024-08-12 10:42:25'),
(49, 40, 'Approve', 'Payment Approved, ID: 12<br>Approval: Declined -> Accepted', '2024-08-12 10:50:31'),
(50, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Pending -> Accepted', '2024-08-12 10:51:47'),
(51, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Declined -> Accepted', '2024-08-12 10:53:08'),
(52, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Declined -> Accepted', '2024-08-12 11:00:11'),
(53, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Accepted -> Declined', '2024-08-12 11:00:13'),
(54, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Declined -> Accepted', '2024-08-12 11:00:33'),
(55, 40, 'Declined', 'Payment Approved, ID: 13<br>Approval: Accepted -> Declined', '2024-08-12 11:00:34'),
(56, 40, 'Approve', 'Payment Approved, ID: 13<br>Approval: Declined -> Accepted', '2024-08-12 11:22:06'),
(57, 40, 'Declined', 'Payment Declined, ID: 13<br>Approval: Accepted -> Declined', '2024-08-12 11:22:07'),
(58, 40, 'Add', 'Added Expenses, ID: 3<br>Expenses Name, : Test form<br>Expenses Info, : form info_1 123<br>', '2024-08-14 09:29:33'),
(59, 40, 'Update', 'Updated Expense, ID: 3<br>Expenses Info:  -> form info_1 1234', '2024-08-14 10:02:45'),
(60, 40, 'Update', 'Updated Expense, ID: 3<br>Expenses Info: form info_1 1234 -> form info_1 12344', '2024-08-14 10:03:27'),
(61, 40, 'Update', 'Updated Expense, ID: 3<br>Expenses Info: form info_1 12344 -> form info_1 123', '2024-08-14 10:14:35'),
(62, 40, 'Delete', 'Deleted Expense, ID: 3<br>Expense Name, : Test form<br>Expense Info, : form info_1 123<br>', '2024-08-14 10:21:02'),
(63, 40, 'Delete', 'Deleted Expense, ID: 2<br>Expense Name, : mag<br>Expense Info, : asdafds<br>', '2024-08-14 10:21:14'),
(64, 40, 'Add', 'Added Expenses, ID: 4<br>Expenses Name, : varda<br>Expenses Info, : gg<br>Expenses Amount, : 88<br>', '2024-08-14 22:25:47'),
(65, 40, 'Add', 'Added Expenses, ID: 5<br>Expenses Name : varda<br>Expenses Info : 4324<br>Expenses Amount : 90.99<br>', '2024-08-14 22:42:26'),
(66, 40, 'Add', 'Added Expenses, ID: 6<br>Expenses Name : gg<br>Expenses Info : varda<br>Expenses Amount : 91<br>', '2024-08-14 22:42:34'),
(67, 40, 'Add', 'Added Expenses, ID: 7<br>Expenses Name : fdsf<br>Expenses Info : vardas<br>Expenses Amount : 90.9999<br>', '2024-08-14 22:43:34'),
(68, 40, 'Add', 'Added Expenses, ID: 8<br>Expenses Name : asdas<br>Expenses Info : gg<br>Expenses Amount : 91<br>', '2024-08-14 22:44:50'),
(69, 40, 'Add', 'Added House, ID: 857<br> Housename: asdas2313<br> Category: 65<br> Price: 123<br> Electric Account: asdad (32138)<br> Water Account: asdad (543242)', '2024-08-14 22:48:29'),
(70, 40, 'Delete', 'Deleted House, ID: 857<br> Housename: asdas2313<br> Category: 093<br> Price: 123', '2024-08-14 22:53:25'),
(71, 40, 'Add', 'Added Expenses, ID: 9<br>Expenses Name : ss<br>Expenses Info : ss<br>Expenses Amount : 23<br>', '2024-08-14 22:57:36'),
(72, 40, 'Add', 'Added Expenses, ID: 10<br>Expenses Name : aasd<br>Expenses Info : asd<br>Expenses Amount : 91<br>', '2024-08-14 23:01:46'),
(73, 41, 'Update', 'Updated User ID: 41<br>Email : 202010108@fit.edu.phhhh -> 202010108@fit.edu.phhhhh', '2024-08-17 11:36:26'),
(74, 41, 'Update', 'Updated User ID: 41<br>Firstname: Broo -> Broonam<br>Middlename : Mad3434567 -> Mad3434567german<br>Lastname : Good -> Goodasd<br>Contact : 123 -> 123456', '2024-08-17 11:42:02'),
(75, 41, 'Update', 'Updated User ID: 41<br>Password : 123456 -> 123777', '2024-08-17 11:42:54'),
(76, 40, 'Message', 'Message, ID: 183<br>Receiver, : aj<br>', '2024-08-18 06:11:14'),
(77, 40, 'Message', 'Message, ID: 184<br>Receiver, : aj<br>', '2024-08-18 06:13:31'),
(78, 40, 'Message', 'Message, ID: 185<br>Receiver, : aj<br>', '2024-08-18 06:14:39'),
(79, 40, 'Message', 'Message, ID: 186<br>Receiver, : aj<br>', '2024-08-18 06:16:09'),
(80, 40, 'Message', 'Message, ID: 187<br>Receiver, : aj<br>', '2024-08-18 06:17:57'),
(81, 40, 'Message', 'Message, ID: 196<br>Receiver, : aj<br>', '2024-08-18 07:51:39'),
(82, 40, 'Message', 'Message, ID: 198<br>Receiver, : aj<br>', '2024-08-18 08:59:25'),
(83, 40, 'Message', 'Message, ID: 199<br>Receiver, : aj<br>', '2024-08-18 09:16:44'),
(84, 40, 'Message', 'Message, ID: 200<br>Receiver, : aj<br>', '2024-08-18 09:16:50'),
(85, 40, 'Message', 'Message, ID: 201<br>Receiver, : aj<br>', '2024-08-18 09:18:21'),
(86, 40, 'Message', 'Message, ID: 202<br>Receiver, : aj<br>', '2024-08-18 09:20:23'),
(87, 40, 'Message', 'Message, ID: 203<br>Receiver, : aj<br>', '2024-08-18 09:20:28'),
(88, 40, 'Message', 'Message, ID: 204<br>Receiver, : aj<br>', '2024-08-18 09:20:36'),
(89, 40, 'Message', 'Message, ID: 205<br>Receiver, : aj<br>', '2024-08-18 09:29:09'),
(90, 40, 'Message', 'Message, ID: 206<br>Receiver, : aj<br>', '2024-08-19 01:49:34'),
(91, 40, 'Message', 'Message, ID: 207<br>Receiver, : aj<br>', '2024-08-19 01:50:50'),
(92, 40, 'Message', 'Message, ID: 208<br>Receiver, : aj<br>', '2024-08-19 02:21:10'),
(93, 40, 'Add', 'Added Paper Category, ID: 88<br>Category Name, : testgg<br>', '2024-08-20 12:47:49'),
(94, 40, 'Delete', 'Deleted Paper Category, ID: 88<br>Category Name, : testgg<br>', '2024-08-20 12:48:10'),
(95, 40, 'Add', 'Added Expenses, ID: 11<br>Expenses Name : bbg<br>Expenses Info : sprt<br>Expenses Amount : 9000<br>', '2024-08-21 00:36:59'),
(96, 40, 'Add', 'Added Expenses, ID: 12<br>Expenses Name : testtt<br>Expenses Info : yadgdggf<br>Expenses Amount : 777<br>House ID: 838<br>', '2024-08-21 09:08:32'),
(97, 40, 'Add', 'Added Expenses, ID: 13<br>Expenses Name : uygh<br>Expenses Info : gffdfg<br>Expenses Amount : 767687<br>', '2024-08-21 10:00:31'),
(98, 40, 'Message', 'Message, ID: 233<br>Receiver, : aj<br>', '2024-08-22 15:26:09'),
(99, 40, 'Message', 'Message, ID: 234<br>Receiver, : admin12<br>', '2024-08-23 02:31:42'),
(100, 40, 'Message', 'Message, ID: 236<br>Receiver, : aj<br>', '2024-08-23 03:46:41'),
(101, 40, 'Message', 'Message, ID: 237<br>Receiver, : aj<br>', '2024-08-23 03:48:25'),
(102, 40, 'Message', 'Message, ID: 238<br>Receiver, : aj<br>', '2024-08-23 03:48:33'),
(103, 40, 'Message', 'Message, ID: 239<br>Receiver, : aj<br>', '2024-08-23 03:56:19'),
(104, 40, 'Message', 'Message, ID: 240<br>Receiver, : aj<br>', '2024-08-23 03:57:33'),
(105, 40, 'Message', 'Message, ID: 241<br>Receiver, : aj<br>', '2024-08-23 03:59:39'),
(106, 40, 'Message', 'Message, ID: 242<br>Receiver, : aj<br>', '2024-08-23 03:59:45'),
(107, 40, 'Message', 'Message, ID: 243<br>Receiver, : aj<br>', '2024-08-23 04:00:33'),
(108, 40, 'Message', 'Message, ID: 244<br>Receiver, : aj<br>', '2024-08-23 04:00:41'),
(109, 40, 'Message', 'Message, ID: 245<br>Receiver, : aj<br>', '2024-08-23 04:00:52'),
(110, 40, 'Message', 'Message, ID: 246<br>Receiver, : aj<br>', '2024-08-23 04:01:09'),
(111, 40, 'Message', 'Message, ID: 247<br>Receiver, : aj<br>', '2024-08-23 04:01:20'),
(112, 40, 'Message', 'Message, ID: 248<br>Receiver, : aj<br>', '2024-08-23 04:01:51'),
(113, 40, 'Message', 'Message, ID: 249<br>Receiver, : aj<br>', '2024-08-23 04:02:23'),
(114, 40, 'Message', 'Message, ID: 250<br>Receiver, : aj<br>', '2024-08-23 04:02:29'),
(115, 40, 'Message', 'Message, ID: 251<br>Receiver, : aj<br>', '2024-08-23 04:03:58'),
(116, 40, 'Message', 'Message, ID: 252<br>Receiver, : aj<br>', '2024-08-23 04:04:10'),
(117, 40, 'Message', 'Message, ID: 253<br>Receiver, : aj<br>', '2024-08-25 06:53:30'),
(118, 40, 'Message', 'Message, ID: 254<br>Receiver, : aj<br>', '2024-08-25 07:09:57'),
(119, 40, 'Message', 'Message, ID: 255<br>Receiver, : aj<br>', '2024-08-25 07:10:03'),
(120, 40, 'Message', 'Message, ID: 256<br>Receiver, : aj<br>', '2024-08-25 07:10:07'),
(121, 40, 'Message', 'Message, ID: 257<br>Receiver, : aj<br>', '2024-08-25 07:10:13'),
(122, 40, 'Message', 'Message, ID: 258<br>Receiver, : aj<br>', '2024-08-26 11:19:01'),
(123, 40, 'Message', 'Message, ID: 259<br>Receiver, : aj<br>', '2024-08-26 11:19:24');

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
(3, 848, 'asd', 4234, 'asd', 54235),
(11, 856, 'asdasd2313', 3254662, 'fsdfds', 543534);

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
(848, 'asd', 123, 65),
(856, 'gg23', 42322, 66);

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
(159, 40, 41, 40, '123', '2024-07-02 18:15:22', NULL),
(160, 41, 40, 41, 'asd', '2024-07-26 06:08:58', NULL),
(161, 41, 40, 41, 'asd', '2024-07-26 06:11:15', NULL),
(162, 41, 40, 41, 'asd', '2024-07-26 06:11:26', NULL),
(163, 41, 40, 41, 'asd', '2024-07-26 06:11:29', NULL),
(164, 41, 40, 41, 'asd', '2024-07-26 06:11:33', NULL),
(165, 41, 40, 41, 'asd', '2024-07-26 06:11:56', NULL),
(166, 41, 40, 41, 'asd', '2024-07-26 06:11:58', NULL),
(167, 40, 41, 40, 'asd', '2024-07-26 06:13:43', NULL),
(168, 41, 40, 41, 'asd', '2024-07-26 06:13:50', NULL),
(169, 41, 40, 41, 'a', '2024-07-26 06:14:02', NULL),
(170, 40, 41, 40, 'ffas', '2024-07-26 08:23:48', NULL),
(171, 41, 41, 41, 'm41', '2024-07-26 17:02:10', NULL),
(172, 41, 41, 41, 'asd', '2024-07-26 17:02:21', NULL),
(173, 41, 41, 41, '456', '2024-07-26 17:02:49', NULL),
(174, 41, 41, 41, 'as', '2024-07-26 17:19:09', NULL),
(175, 41, 40, 41, 'as', '2024-08-03 13:00:59', NULL),
(176, 41, 40, 41, '34', '2024-08-03 13:04:44', NULL),
(177, 41, 40, 41, 'as', '2024-08-03 13:04:52', NULL),
(178, 41, 40, 41, 'asas', '2024-08-03 13:05:07', NULL),
(179, 41, 40, 41, 'a', '2024-08-03 13:05:12', NULL),
(180, 41, 40, 41, '23', '2024-08-03 13:05:17', NULL),
(181, 40, 41, 40, 'darci', '2024-08-12 11:44:08', NULL),
(182, 40, 41, 40, 'lommy', '2024-08-12 12:09:47', NULL),
(183, 40, 41, 40, 'meme', '2024-08-18 14:11:14', './uploads/66c19082b39c2.jpg'),
(184, 40, 41, 40, 'jeje', '2024-08-18 14:13:31', './uploads/66c1910b00ce2.jpg'),
(185, 40, 41, 40, 'jeje', '2024-08-18 14:14:39', './uploads/66c1914f347b3.jpg'),
(186, 40, 41, 40, 'keke', '2024-08-18 14:16:09', './uploads/66c191a908713.gif'),
(187, 40, 41, 40, 'meme', '2024-08-18 14:17:57', './uploads/66c19215e36dd.gif'),
(188, 41, 40, 41, 'asdasd', '2024-08-18 14:36:28', NULL),
(189, 41, 40, 41, 'asd', '2024-08-18 14:36:55', NULL),
(190, 41, 40, 41, 'sdf', '2024-08-18 14:36:56', NULL),
(191, 41, 40, 41, 'fdsf', '2024-08-18 14:37:00', NULL),
(192, 41, 40, 41, 'sdfds', '2024-08-18 14:37:05', NULL),
(193, 41, 40, 41, 'ajj', '2024-08-18 14:57:15', NULL),
(194, 41, 40, 41, 'gg', '2024-08-18 14:57:30', NULL),
(195, 41, 40, 41, 'bababa', '2024-08-18 15:51:30', NULL),
(196, 40, 41, 40, 'gg', '2024-08-18 15:51:39', NULL),
(197, 41, 40, 41, 'bootstrap bill', '2024-08-18 16:59:18', NULL),
(198, 40, 41, 40, 'aj', '2024-08-18 16:59:25', NULL),
(199, 40, 41, 40, 'mm', '2024-08-18 17:16:44', NULL),
(200, 40, 41, 40, 'yy', '2024-08-18 17:16:50', NULL),
(201, 40, 41, 40, 'ee', '2024-08-18 17:18:21', NULL),
(202, 40, 41, 40, 'kk', '2024-08-18 17:20:23', NULL),
(203, 40, 41, 40, 'll', '2024-08-18 17:20:28', NULL),
(204, 40, 41, 40, 'pp', '2024-08-18 17:20:36', NULL),
(205, 40, 41, 40, 'mm', '2024-08-18 17:29:09', NULL),
(206, 40, 41, 40, 'kk', '2024-08-19 09:49:34', './uploads/66c2a4aebebe5.png'),
(207, 40, 41, 40, 'lop', '2024-08-19 09:50:50', './uploads/66c2a4fa13301.mp4'),
(208, 40, 41, 40, 'lmao', '2024-08-19 10:21:10', './uploads/66c2ac16e4025.jpg'),
(209, 41, 40, 41, 'gg', '2024-08-19 11:56:47', NULL),
(210, 41, 40, 41, 'asd', '2024-08-19 11:57:04', NULL),
(211, 41, 40, 41, 'aa', '2024-08-19 11:57:29', NULL),
(212, 41, 40, 41, 'gg', '2024-08-19 11:58:38', NULL),
(213, 41, 40, 41, 'aa', '2024-08-19 12:35:17', NULL),
(214, 41, 40, 41, 'qq', '2024-08-19 12:44:00', './uploads/66c2cd907d9c0.png'),
(215, 41, 40, 41, 'as', '2024-08-19 13:39:48', './uploads/66c2daa451e54.jpeg'),
(216, 41, 40, 41, 'sd', '2024-08-19 13:39:54', NULL),
(217, 41, 40, 41, 'ff', '2024-08-19 13:40:03', './uploads/66c2dab35fa1c.jpeg'),
(218, 41, 40, 41, 'ff', '2024-08-19 13:40:11', NULL),
(219, 41, 40, 41, 'as', '2024-08-19 13:42:29', './uploads/66c2db4560c15.png'),
(220, 41, 40, 41, 'g', '2024-08-19 13:42:36', './uploads/66c2db4c6e8b3.png'),
(221, 41, 40, 41, 'aas', '2024-08-19 13:42:46', './uploads/66c2db56757c4.png'),
(222, 41, 40, 41, 'as', '2024-08-19 13:42:51', './uploads/66c2db5b464a9.png'),
(223, 41, 40, 41, 'gg', '2024-08-19 13:53:15', './uploads/66c2ddcb61f3c.png'),
(224, 41, 40, 41, 'tt', '2024-08-19 13:53:18', NULL),
(225, 41, 40, 41, 'gg', '2024-08-19 13:53:26', './uploads/66c2ddd63a3fb.jpg'),
(226, 41, 40, 41, 'gg', '2024-08-19 13:54:07', NULL),
(227, 41, 40, 41, 'tt', '2024-08-19 13:54:18', './uploads/66c2de0a83049.jpg'),
(228, 41, 40, 41, 'yy', '2024-08-19 13:54:23', NULL),
(229, 41, 40, 41, 'gg', '2024-08-19 13:59:17', './uploads/66c2df352d77f.mp4'),
(230, 41, 40, 41, 'tt', '2024-08-19 13:59:24', NULL),
(231, 41, 40, 41, 'tt', '2024-08-19 13:59:32', './uploads/66c2df44603c2.mp4'),
(232, 41, 40, 41, 'tt', '2024-08-19 13:59:41', NULL),
(233, 40, 41, 40, 'lol', '2024-08-22 23:26:09', NULL),
(234, 40, 40, 40, 'gg', '2024-08-23 10:31:42', NULL),
(235, 41, 40, 41, 'luper', '2024-08-23 11:34:41', NULL),
(236, 40, 41, 40, 'gg', '2024-08-23 11:46:41', NULL),
(237, 40, 41, 40, 'gg', '2024-08-23 11:48:25', './uploads/66c8068910225.jpg'),
(238, 40, 41, 40, 'tt', '2024-08-23 11:48:33', './uploads/66c80691be2c3.jpg'),
(239, 40, 41, 40, 'yy', '2024-08-23 11:56:19', './uploads/66c80863d669d.png'),
(240, 40, 41, 40, 'aa', '2024-08-23 11:57:33', './uploads/66c808ad4091e.png'),
(241, 40, 41, 40, 'sad', '2024-08-23 11:59:39', './uploads/66c8092b8250c.png'),
(242, 40, 41, 40, 'ee', '2024-08-23 11:59:45', NULL),
(243, 40, 41, 40, 'test', '2024-08-23 12:00:33', NULL),
(244, 40, 41, 40, 'test', '2024-08-23 12:00:41', './uploads/66c80969372de.jpg'),
(245, 40, 41, 40, 'uu', '2024-08-23 12:00:52', './uploads/66c8097400584.jpg'),
(246, 40, 41, 40, 'yy', '2024-08-23 12:01:09', './uploads/66c809853fa07.jpeg'),
(247, 40, 41, 40, 'uu', '2024-08-23 12:01:20', NULL),
(248, 40, 41, 40, 'yy', '2024-08-23 12:01:50', './uploads/66c809aeecc6a.png'),
(249, 40, 41, 40, 'gg', '2024-08-23 12:02:23', './uploads/66c809cfc527f.png'),
(250, 40, 41, 40, 'uu', '2024-08-23 12:02:29', NULL),
(251, 40, 41, 40, 'yy', '2024-08-23 12:03:58', NULL),
(252, 40, 41, 40, 'yy', '2024-08-23 12:04:10', './uploads/66c80a3a718fb.png'),
(253, 40, 41, 40, 'yy', '2024-08-25 14:53:30', NULL),
(254, 40, 41, 40, 'tt', '2024-08-25 15:09:57', NULL),
(255, 40, 41, 40, 'uu', '2024-08-25 15:10:03', NULL),
(256, 40, 41, 40, 'kk', '2024-08-25 15:10:07', NULL),
(257, 40, 41, 40, 'q', '2024-08-25 15:10:13', NULL),
(258, 40, 41, 40, 'aa', '2024-08-26 19:19:01', NULL),
(259, 40, 41, 40, 'aaa', '2024-08-26 19:19:24', './uploads/66cc64bc3eda4.mp4'),
(260, 41, 41, 41, 'g', '2024-08-26 20:07:43', './uploads/66cc700f102b7.mp4'),
(261, 41, 41, 41, 'g', '2024-08-26 20:08:00', './uploads/66cc702001736.png'),
(262, 41, 41, 41, 'assssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssa', '2024-08-26 20:10:35', NULL),
(263, 41, 41, 41, 'assssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaasssssss', '2024-08-26 20:10:42', NULL);

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
(70, 'affd', '2024-07-03 16:53:49'),
(83, 'xds', '2024-07-18 18:50:47'),
(87, 'bebe', '2024-08-12 08:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `paper_files`
--

CREATE TABLE `paper_files` (
  `id` int(50) NOT NULL,
  `category_id` int(50) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `paper_files`
--

INSERT INTO `paper_files` (`id`, `category_id`, `category_name`, `file_name`, `file_url`, `uploaded_at`) VALUES
(1, 63, '', 'Mental Omega Screenshot 2023.04.11 - 12.13.09.91.png', '../uploads/Mental Omega Screenshot 2023.04.11 - 12.13.09.91.png', '2024-07-01 17:04:33'),
(3, 63, '', 'received_993104588452786.jpeg', '../uploads/received_993104588452786.jpeg', '2024-07-01 17:08:14'),
(23, 63, '', '6685763997254.docx', '../uploads/6685763997254.docx', '2024-07-03 16:03:05'),
(25, 63, '', 'Untitled_66858228aefaf.png', '../uploads/Untitled_66858228aefaf.png', '2024-07-03 16:54:00'),
(31, 63, '', 'BooTails Petspa & Mobile Grooming-Unleash Merchant Partnership Letter_66890be9852b5.docx', '../uploads/BooTails Petspa & Mobile Grooming-Unleash Merchant Partnership Letter_66890be9852b5.docx', '2024-07-06 09:18:33'),
(32, 85, '', 'Terms and Conditions for Unleash_669a700d8f616.docx', '../uploads/Terms and Conditions for Unleash_669a700d8f616.docx', '2024-07-19 13:54:21'),
(38, 83, 'xds', 'Untitled_669a891a62e5c.png', '../uploads/Untitled_669a891a62e5c.png', '2024-07-19 15:41:14'),
(40, 87, 'bebe', 'Untitled_66b9cf3e1e116.png', '../uploads/Untitled_66b9cf3e1e116.png', '2024-08-12 09:00:46'),
(41, 87, 'bebe', 'GAMEPOSTER_66b9d129518a2.jpg', '../uploads/GAMEPOSTER_66b9d129518a2.jpg', '2024-08-12 09:08:57');

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
  `date_payment` date NOT NULL,
  `approval` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `name`, `amount`, `tenants_id`, `houses_id`, `filepath`, `date_payment`, `approval`) VALUES
(7, 'Lucious Black Gorehound', 0, 63, 847, '../uploads/GAMEPOSTER_666b08e7a6d95.jpg', '2024-06-13', ''),
(8, 'Lucious Black Gorehound', 54, 63, 847, '../uploads/GAMEPOSTER_666b08fddb94a.jpg', '2024-06-13', 'true'),
(9, 'Lucious Black Gorehound', 54, 63, 847, '../uploads/received_993104588452786_666b0958540c1.jpeg', '2024-06-14', ''),
(10, 'Broonam Mad3434567german Goodasd', 9000, 61, 846, '../uploads/2023-11-19 13-03-19_6683d246aae55.mp4', '2024-07-02', 'true'),
(11, 'Broonam Mad3434567german Goodasd', 3000, 61, 846, '../uploads/received_993104588452786_6697935ad360f.jpeg', '2024-07-17', 'true'),
(12, 'Broonam Mad3434567german Goodasd', 50, 61, 846, '../uploads/Untitled_66a0d66bb54de.png', '2024-07-24', 'true'),
(13, 'Broonam Mad3434567german Goodasd', 500, 61, 846, '../uploads/Untitled_66b9e91dc024e.png', '2024-08-12', 'false'),
(15, 'test testsdfds', 8000, 0, 0, '', '2025-10-08', 'true'),
(16, 'tsdfsdfx', 10000, 87, 243, '', '2022-05-03', ''),
(17, 'asdasd', 4000, 8768, 847456, '', '2028-06-02', 'true');

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
(61, 'Broonam', 'Mad3434567german', 'Goodasd', '123456', '41', 'aj', 846, '093', '2024-05-09', NULL, NULL),
(62, 'John', 'Payo', 'Junio', '0983', '69', 'aj', 839, '09', '2024-06-09', NULL, NULL),
(63, 'Lucious', 'Black', 'Gorehound', '093414', '70', 'OuterHeaven', 847, '093', '2024-05-01', NULL, '2024-05-01'),
(66, 'Romeo', 'Juliet', 'Echo', '123', '71', 'Bad', 847, '093', '2024-05-01', NULL, '2024-05-01'),
(67, 'test fname', 'testmiddle', 'testlastname', '0939', '72', 'testing1', 838, '093', '2024-07-07', NULL, '2024-06-07'),
(68, 'test fname2', 'test middle2', 'testlastname2', '123432', '73', 'testing2', 838, '093', '2024-07-07', NULL, '2024-03-13'),
(73, 'fnm', 'mnm', 'lnm', '99999999999', '74', 'test39099', 856, 'Studio', '2024-08-12', NULL, NULL);

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
(41, 'aj', 'Broonam', 'Mad3434567german', 'Goodasd', '123777', '202010108@fit.edu.phhhhh', 'user', '2024-05-25 18:52:14'),
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
(73, 'testing2', 'test fname2', 'test middle2', 'testlastname2', '123', 'ajunio.feudiliman@gmail.com', 'user', '2024-07-07 19:41:52'),
(74, 'test39099', 'fnm', 'mnm', 'lnm', '123', '', 'user', '2024-07-18 15:55:16'),
(80, 'cancerarty', 'asd', 'fac', 'dsdsds', '12', '', 'admin', '2024-08-09 01:49:01'),
(81, 'asd', 'asd', 'asd', 'asd', '123', 'asd@gmail.com', 'user', '2024-09-01 19:15:20'),
(82, 'asd', 'asd', 'asd', 'asd', '123', 'fsdf@gmail.com', 'user', '2024-09-01 19:17:42'),
(83, 'asd', 'asd', 'asd', 'asd', '123', 'Asd@gmail.com', 'user', '2024-09-01 19:20:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=858;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `paper_categories`
--
ALTER TABLE `paper_categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `paper_files`
--
ALTER TABLE `paper_files`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
