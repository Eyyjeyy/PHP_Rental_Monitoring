-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2024 at 03:05 AM
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
(66, 'Studio'),
(74, 'Duplex'),
(75, 'Micro Studio'),
(76, 'Loft'),
(77, 'Land');

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
(123, 40, 'Message', 'Message, ID: 259<br>Receiver, : aj<br>', '2024-08-26 11:19:24'),
(124, 40, 'Add', 'Added Category, ID: 72<br> Username: aj bizzarre adventrures', '2024-09-21 11:48:05'),
(125, 40, 'Delete', 'Deleted Category, ID: 72<br> Username: aj bizzarre adventrures', '2024-09-21 11:50:28'),
(126, 40, 'Add', 'Added Category, ID: 73<br> Username: lukas', '2024-09-21 11:50:36'),
(127, 40, 'Delete', 'Deleted Category, ID: 73<br> Username: lukas', '2024-09-21 11:50:52'),
(128, 40, 'Message', 'Message, ID: 267<br>Receiver, : aj<br>', '2024-10-03 13:22:53'),
(129, 40, 'Message', 'Message, ID: 269<br>Receiver, : aj<br>', '2024-10-03 13:29:16'),
(130, 40, 'Message', 'Message, ID: 270<br>Receiver, : aj<br>', '2024-10-03 13:29:41'),
(131, 40, 'Message', 'Message, ID: 271<br>Receiver, : aj<br>', '2024-10-03 13:30:12'),
(132, 40, 'Message', 'Message, ID: 272<br>Receiver, : aj<br>', '2024-10-03 16:30:48'),
(133, 40, 'Message', 'Message, ID: 273<br>Receiver, : aj<br>', '2024-10-03 16:31:51'),
(134, 40, 'Message', 'Message, ID: 274<br>Receiver, : aj<br>', '2024-10-03 16:33:33'),
(135, 40, 'Message', 'Message, ID: 275<br>Receiver, : aj<br>', '2024-10-03 16:43:57'),
(136, 40, 'Message', 'Message, ID: 276<br>Receiver, : aj<br>', '2024-10-03 16:50:51'),
(137, 40, 'Message', 'Message, ID: 277<br>Receiver, : aj<br>', '2024-10-03 16:56:08'),
(138, 40, 'Message', 'Message, ID: 278<br>Receiver, : aj<br>', '2024-10-03 16:56:23'),
(139, 40, 'Message', 'Message, ID: 279<br>Receiver, : aj<br>', '2024-10-03 16:58:58'),
(140, 40, 'Message', 'Message, ID: 280<br>Receiver, : aj<br>', '2024-10-03 17:03:24'),
(141, 40, 'Message', 'Message, ID: 281<br>Receiver, : aj<br>', '2024-10-03 17:19:45'),
(142, 40, 'Message', 'Message, ID: 282<br>Receiver, : aj<br>', '2024-10-03 17:20:45'),
(143, 40, 'Message', 'Message, ID: 283<br>Receiver, : aj<br>', '2024-10-03 17:28:29'),
(144, 40, 'Message', 'Message, ID: 288<br>Receiver, : aj<br>', '2024-10-03 17:46:59'),
(145, 40, 'Message', 'Message, ID: 289<br>Receiver, : aj<br>', '2024-10-03 17:47:52'),
(146, 40, 'Message', 'Message, ID: 290<br>Receiver, : aj<br>', '2024-10-03 17:51:43'),
(147, 40, 'Message', 'Message, ID: 291<br>Receiver, : aj<br>', '2024-10-03 18:00:22'),
(148, 40, 'Message', 'Message, ID: 292<br>Receiver, : aj<br>', '2024-10-03 18:01:03'),
(149, 40, 'Message', 'Message, ID: 293<br>Receiver, : aj<br>', '2024-10-03 18:01:41'),
(150, 40, 'Message', 'Message, ID: 294<br>Receiver, : aj<br>', '2024-10-03 18:02:27'),
(151, 40, 'Message', 'Message, ID: 296<br>Receiver, : aj<br>', '2024-10-03 18:24:38'),
(152, 40, 'Message', 'Message, ID: 297<br>Receiver, : aj<br>', '2024-10-03 18:25:22'),
(153, 40, 'Message', 'Message, ID: 298<br>Receiver, : aj<br>', '2024-10-03 18:41:47'),
(154, 40, 'Message', 'Message, ID: 299<br>Receiver, : aj<br>', '2024-10-03 18:43:52'),
(155, 40, 'Message', 'Message, ID: 300<br>Receiver, : aj<br>', '2024-10-03 18:44:20'),
(156, 40, 'Message', 'Message, ID: 301<br>Receiver, : aj<br>', '2024-10-03 18:46:59'),
(157, 40, 'Message', 'Message, ID: 302<br>Receiver, : aj<br>', '2024-10-03 18:50:52'),
(158, 40, 'Message', 'Message, ID: 303<br>Receiver, : aj<br>', '2024-10-03 18:51:35'),
(159, 40, 'Message', 'Message, ID: 305<br>Receiver, : admin12<br>', '2024-10-25 21:13:23'),
(160, 40, 'Message', 'Message, ID: 306<br>Receiver, : admin12<br>', '2024-10-25 21:15:44'),
(161, 40, 'Message', 'Message, ID: 307<br>Receiver, : admin12<br>', '2024-10-25 21:17:50'),
(162, 40, 'Delete', 'Deleted Category, ID: 71<br> Username: jjbaa', '2024-10-29 11:03:01'),
(163, 40, 'Delete', 'Deleted Category, ID: 65<br> Username: 093', '2024-10-29 11:03:03'),
(164, 40, 'Add', 'Added Category, ID: 74<br> Username: Duplex', '2024-10-29 11:03:49'),
(165, 40, 'Add', 'Added Category, ID: 75<br> Username: Micro Studio', '2024-10-29 11:04:18'),
(166, 40, 'Add', 'Added Category, ID: 76<br> Username: Loft', '2024-10-29 11:04:24'),
(167, 40, 'Add', 'Added House, ID: 858<br> Housename: Roxasville A<br> Category: 74<br> Price: 15000<br> Electric Account: John (132053845)<br> Water Account: John (93473595)', '2024-10-29 11:06:06'),
(168, 40, 'Add', 'Added House, ID: 859<br> Housename: Roxasville B<br> Category: 74<br> Price: 15000<br> Electric Account: Jan (3425645345)<br> Water Account: Jan (345334288)', '2024-10-29 11:06:37'),
(169, 40, 'Add', 'Added House, ID: 860<br> Housename: Bagatua Corner A<br> Category: 74<br> Price: 10000<br> Electric Account: John (21342342)<br> Water Account: John (42635636)', '2024-10-29 11:12:22'),
(170, 40, 'Add', 'Added House, ID: 861<br> Housename: Bagatua Corner B<br> Category: 74<br> Price: 10000<br> Electric Account: Jan (2342634)<br> Water Account: Jan (6354636)', '2024-10-29 11:12:47'),
(171, 40, 'Add', 'Added House, ID: 862<br> Housename: Bagatua Corner C<br> Category: 74<br> Price: 10000<br> Electric Account: Jan (45345345)<br> Water Account: Jan (34532347)', '2024-10-29 11:13:17'),
(172, 40, 'Add', 'Added House, ID: 863<br> Housename: Bagatua Corner D<br> Category: 66<br> Price: 10000<br> Electric Account: Jemille (4647457)<br> Water Account: Jemille (457452)', '2024-10-29 11:13:53'),
(173, 40, 'Update', 'Updated House, ID: 862<br>Category: Duplex -> Studio<br>Price: 10000 -> 10000', '2024-10-29 11:14:03'),
(174, 40, 'Add', 'Added House, ID: 864<br> Housename: Lagro A<br> Category: 74<br> Price: 16000<br> Electric Account: Mildred (3213426)<br> Water Account: Mildred (3437474)', '2024-10-29 11:15:19'),
(175, 40, 'Add', 'Added House, ID: 865<br> Housename: Lagro B<br> Category: 74<br> Price: 16000<br> Electric Account: Mildred (42346363)<br> Water Account: Mildred (6366346)', '2024-10-29 11:15:29'),
(176, 40, 'Add', 'Added House, ID: 866<br> Housename: Bagatua Side A<br> Category: 75<br> Price: 5000<br> Electric Account: Jerum (325252)<br> Water Account: Jerum (263453)', '2024-10-29 11:16:00'),
(177, 40, 'Add', 'Added House, ID: 867<br> Housename: Bagatua Side A<br> Category: 75<br> Price: 5000<br> Electric Account: Jerum (34537745)<br> Water Account: Jerum (45747455)', '2024-10-29 11:16:20'),
(178, 40, 'Delete', 'Deleted House, ID: 856<br> Housename: gg23<br> Category: Studio<br> Price: 42322', '2024-10-29 11:16:25'),
(179, 40, 'Add', 'Added Category, ID: 77<br> Username: Land', '2024-10-29 11:16:45'),
(180, 40, 'Add', 'Added House, ID: 868<br> Housename: Regalado<br> Category: 77<br> Price: 30000<br> Electric Account: Mikhail (23426346)<br> Water Account: Mikhail (46363466)', '2024-10-29 11:17:06'),
(181, 40, 'Update', 'Updated House, ID: 867<br>Housename: Bagatua Side A -> Bagatua Side B<br>Price: 5000 -> 5000', '2024-10-29 11:17:15'),
(182, 40, 'Add', 'Added User, ID: 84<br> Username: admin', '2024-10-29 11:18:15'),
(183, 40, 'Add', 'Added User, ID: 85<br> Username: user', '2024-10-29 11:18:25'),
(184, 40, 'Delete', 'Deleted User, ID: 40<br> Username: admin12', '2024-10-29 11:18:39'),
(185, 40, 'Delete', 'Deleted User, ID: 41<br> Username: aj', '2024-10-29 11:18:40'),
(186, 40, 'Delete', 'Deleted User, ID: 46<br> Username: Mad Max1', '2024-10-29 11:18:40'),
(187, 40, 'Delete', 'Deleted User, ID: 50<br> Username: asd_1', '2024-10-29 11:18:41'),
(188, 40, 'Delete', 'Deleted User, ID: 51<br> Username: asd_ ', '2024-10-29 11:18:41'),
(189, 40, 'Delete', 'Deleted User, ID: 60<br> Username: asd', '2024-10-29 11:18:43'),
(190, 40, 'Delete', 'Deleted User, ID: 61<br> Username: asd', '2024-10-29 11:18:44'),
(191, 40, 'Delete', 'Deleted User, ID: 62<br> Username: ajb', '2024-10-29 11:18:44'),
(192, 40, 'Delete', 'Deleted User, ID: 67<br> Username: gar', '2024-10-29 11:18:44'),
(193, 40, 'Delete', 'Deleted User, ID: 68<br> Username: aj', '2024-10-29 11:18:45'),
(194, 40, 'Delete', 'Deleted User, ID: 69<br> Username: aj', '2024-10-29 11:18:45'),
(195, 40, 'Delete', 'Deleted User, ID: 70<br> Username: OuterHeaven', '2024-10-29 11:18:45'),
(196, 40, 'Delete', 'Deleted User, ID: 71<br> Username: Bad', '2024-10-29 11:18:47'),
(197, 40, 'Delete', 'Deleted User, ID: 72<br> Username: testing1', '2024-10-29 11:18:47'),
(198, 40, 'Delete', 'Deleted User, ID: 73<br> Username: testing2', '2024-10-29 11:18:48'),
(199, 40, 'Delete', 'Deleted User, ID: 74<br> Username: test39099', '2024-10-29 11:18:48'),
(200, 40, 'Delete', 'Deleted User, ID: 80<br> Username: cancerarty', '2024-10-29 11:18:49'),
(201, 40, 'Delete', 'Deleted User, ID: 81<br> Username: asd', '2024-10-29 11:18:49'),
(202, 40, 'Delete', 'Deleted User, ID: 82<br> Username: asd', '2024-10-29 11:18:50'),
(203, 40, 'Delete', 'Deleted User, ID: 83<br> Username: asd', '2024-10-29 11:18:50'),
(204, 40, 'Delete', 'Deleted Tenant, ID: 61<br> Firstname: Broonam<br> Middlename: Mad3434567german<br> Lastname: Goodasd<br> User ID: 41<br> Username: aj<br> House ID: 846', '2024-10-29 11:20:39'),
(205, 40, 'Delete', 'Deleted Tenant, ID: 62<br> Firstname: John<br> Middlename: Payo<br> Lastname: Junio<br> User ID: 69<br> Username: aj<br> House ID: 839', '2024-10-29 11:20:40'),
(206, 40, 'Delete', 'Deleted Tenant, ID: 63<br> Firstname: Lucious<br> Middlename: Black<br> Lastname: Gorehound<br> User ID: 70<br> Username: OuterHeaven<br> House ID: 847', '2024-10-29 11:20:40'),
(207, 40, 'Delete', 'Deleted Tenant, ID: 66<br> Firstname: Romeo<br> Middlename: Juliet<br> Lastname: Echo<br> User ID: 71<br> Username: Bad<br> House ID: 847', '2024-10-29 11:20:40'),
(208, 40, 'Delete', 'Deleted Tenant, ID: 67<br> Firstname: test fname<br> Middlename: testmiddle<br> Lastname: testlastname<br> User ID: 72<br> Username: testing1<br> House ID: 838', '2024-10-29 11:20:41'),
(209, 40, 'Delete', 'Deleted Tenant, ID: 68<br> Firstname: test fname2<br> Middlename: test middle2<br> Lastname: testlastname2<br> User ID: 73<br> Username: testing2<br> House ID: 838', '2024-10-29 11:20:41'),
(210, 40, 'Delete', 'Deleted Tenant, ID: 73<br> Firstname: fnm<br> Middlename: mnm<br> Lastname: lnm<br> User ID: 74<br> Username: test39099<br> House ID: 856', '2024-10-29 11:20:42'),
(211, 40, 'Add', 'Added Tenant, ID: 74<br>First Name: Jerson<br>Middle Name: Wayas<br>Last Name: Lippad<br>Contact: 09324404218<br>User ID: 85<br>Username: user<br>House ID: 862<br>House Category: Studio', '2024-10-29 11:22:03'),
(212, 40, 'Delete', 'Deleted Expense, ID: 1<br>Expense Name : sample<br>Expense Info : yada yada<br>', '2024-10-29 11:25:26'),
(213, 40, 'Delete', 'Deleted Expense, ID: 4<br>Expense Name : varda<br>Expense Info : gg<br>', '2024-10-29 11:25:26'),
(214, 40, 'Delete', 'Deleted Expense, ID: 5<br>Expense Name : varda<br>Expense Info : 4324<br>', '2024-10-29 11:25:27'),
(215, 40, 'Delete', 'Deleted Expense, ID: 6<br>Expense Name : gg<br>Expense Info : varda<br>', '2024-10-29 11:25:27'),
(216, 40, 'Delete', 'Deleted Expense, ID: 7<br>Expense Name : fdsf<br>Expense Info : vardas<br>', '2024-10-29 11:25:27'),
(217, 40, 'Delete', 'Deleted Expense, ID: 8<br>Expense Name : asdas<br>Expense Info : gg<br>', '2024-10-29 11:25:28'),
(218, 40, 'Delete', 'Deleted Expense, ID: 9<br>Expense Name : ss<br>Expense Info : ss<br>', '2024-10-29 11:25:28'),
(219, 40, 'Delete', 'Deleted Expense, ID: 10<br>Expense Name : aasd<br>Expense Info : asd<br>', '2024-10-29 11:25:28'),
(220, 40, 'Delete', 'Deleted Expense, ID: 11<br>Expense Name : bbg<br>Expense Info : sprt<br>', '2024-10-29 11:25:29'),
(221, 40, 'Delete', 'Deleted Expense, ID: 12<br>Expense Name : testtt<br>Expense Info : yadgdggf<br>', '2024-10-29 11:25:29'),
(222, 40, 'Delete', 'Deleted Expense, ID: 13<br>Expense Name : uygh<br>Expense Info : gffdfg<br>', '2024-10-29 11:25:29'),
(223, 40, 'Delete', 'Deleted Expense, ID: 14<br>Expense Name : yada<br>Expense Info : asddf<br>', '2024-10-29 11:25:30'),
(224, 40, 'Delete', 'Deleted Expense, ID: 15<br>Expense Name : fdsg<br>Expense Info : asda<br>', '2024-10-29 11:25:30'),
(225, 40, 'Update', 'Updated Tenant, ID: 74<br>House ID: 862 -> 864<br>House Category: Studio -> Duplex', '2024-10-29 11:26:27'),
(226, 40, 'Add', 'Added User, ID: 86<br> Username: john', '2024-10-29 11:29:19'),
(227, 40, 'Add', 'Added User, ID: 87<br> Username: jan', '2024-10-29 11:29:38');

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
(13, 858, 'John', 132053845, 'John', 93473595),
(14, 859, 'Jan', 2147483647, 'Jan', 345334288),
(15, 860, 'John', 21342342, 'John', 42635636),
(16, 861, 'Jan', 2342634, 'Jan', 6354636),
(17, 862, 'Jan', 45345345, 'Jan', 34532347),
(18, 863, 'Jemille', 4647457, 'Jemille', 457452),
(19, 864, 'Mildred', 3213426, 'Mildred', 3437474),
(20, 865, 'Mildred', 42346363, 'Mildred', 6366346),
(21, 866, 'Jerum', 325252, 'Jerum', 263453),
(22, 867, 'Jerum', 34537745, 'Jerum', 45747455),
(23, 868, 'Mikhail', 23426346, 'Mikhail', 46363466);

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
(858, 'Roxasville A', 15000, 74),
(859, 'Roxasville B', 15000, 74),
(860, 'Bagatua Corner A', 10000, 74),
(861, 'Bagatua Corner B', 10000, 74),
(862, 'Bagatua Corner C', 10000, 66),
(863, 'Bagatua Corner D', 10000, 66),
(864, 'Lagro A', 16000, 74),
(865, 'Lagro B', 16000, 74),
(866, 'Bagatua Side A', 5000, 75),
(867, 'Bagatua Side B', 5000, 75),
(868, 'Regalado', 30000, 77);

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
  `image_path` varchar(255) DEFAULT NULL,
  `seen` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `users_id`, `message`, `timestamp`, `image_path`, `seen`) VALUES
(4, 40, 41, 10, 'Lupercal  _Horus', '2024-06-23 02:15:13', NULL, 1),
(6, 41, 40, 41, '123432', '2024-06-23 02:27:10', NULL, 1),
(7, 40, 41, 40, 'lupercall', '2024-06-24 23:17:17', NULL, 1),
(8, 40, 41, 40, 'lupercall', '2024-06-24 23:17:43', NULL, 1),
(9, 40, 41, 40, 'eyjey', '2024-06-24 23:43:56', NULL, 1),
(10, 40, 41, 40, 'Mango', '2024-06-24 23:45:44', NULL, 1),
(11, 40, 41, 40, '123', '2024-06-24 23:50:31', NULL, 1),
(12, 40, 41, 40, 'circumstances', '2024-06-25 00:03:47', NULL, 1),
(13, 40, 41, 40, 'circumstances', '2024-06-25 00:05:23', NULL, 1),
(14, 41, 40, 41, 'Horus Heressysyysysysys\r\n<p>Terra</p>', '2024-06-25 00:35:13', NULL, 1),
(15, 41, 40, 41, '<strong>This text is important!</strong>\r\n', '2024-06-25 00:35:36', NULL, 1),
(16, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:13:52', NULL, 1),
(17, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:34:23', NULL, 1),
(18, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:36:43', NULL, 1),
(19, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 22:56:48', NULL, 1),
(20, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:13:36', NULL, 1),
(21, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:14:10', NULL, 1),
(22, 40, 41, 40, 'asdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasdddddddddddddddddasddddddddddddddddd', '2024-06-27 23:14:24', NULL, 1),
(23, 40, 41, 40, '123', '2024-06-28 00:51:17', NULL, 1),
(24, 40, 41, 40, '124', '2024-06-28 00:51:25', NULL, 1),
(25, 40, 41, 40, '124', '2024-06-28 01:05:22', NULL, 1),
(26, 40, 41, 40, '124', '2024-06-28 01:15:05', './uploads/667d9e19a5fbe.jpg', 1),
(27, 40, 41, 40, '123', '2024-06-28 01:15:27', NULL, 1),
(28, 40, 41, 40, '123', '2024-06-28 01:15:29', NULL, 1),
(29, 40, 41, 40, '1234', '2024-06-28 01:15:29', NULL, 1),
(30, 40, 41, 40, '123', '2024-06-28 01:15:30', NULL, 1),
(31, 40, 41, 40, '123', '2024-06-28 01:15:34', './uploads/667d9e36bfcc8.jpg', 1),
(32, 40, 41, 40, '123', '2024-06-28 01:15:36', './uploads/667d9e387a135.jpg', 1),
(33, 40, 41, 40, '123', '2024-06-28 01:15:37', './uploads/667d9e39d12c0.jpg', 1),
(34, 40, 41, 40, '124', '2024-06-28 01:17:49', './uploads/667d9ebd1c2cf.jpg', 1),
(35, 40, 41, 40, '124', '2024-06-28 01:24:24', './uploads/667da048543f7.jpg', 1),
(36, 40, 41, 40, '124', '2024-06-28 01:24:31', './uploads/667da04f80989.jpg', 1),
(37, 40, 41, 40, '55', '2024-06-28 01:24:35', NULL, 1),
(38, 40, 41, 40, '123', '2024-06-28 01:35:55', './uploads/667da2fb0bd4c.jpeg', 1),
(39, 40, 41, 40, '123', '2024-06-28 01:36:21', './uploads/667da315916a3.jpeg', 1),
(40, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b081ff.jpeg', 1),
(41, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b33532.jpeg', 1),
(42, 40, 41, 40, '123', '2024-06-28 01:36:27', './uploads/667da31b59034.jpeg', 1),
(43, 40, 41, 40, '123', '2024-06-28 01:36:29', './uploads/667da31d18a40.jpeg', 1),
(44, 40, 41, 40, '123', '2024-06-28 01:43:29', './uploads/667da4c173227.jpeg', 1),
(45, 40, 41, 40, 'aj', '2024-06-28 01:47:47', './uploads/667da5c34850a.jpg', 1),
(46, 40, 41, 40, 'jhh', '2024-06-28 01:49:45', './uploads/667da63905825.jpeg', 1),
(47, 40, 41, 40, 'aj', '2024-06-28 01:49:53', NULL, 1),
(48, 40, 41, 40, 'ajj', '2024-06-28 01:49:56', './uploads/667da644d25b5.jpeg', 1),
(49, 40, 41, 40, 'ajj', '2024-06-28 01:49:57', './uploads/667da64568423.jpeg', 1),
(50, 40, 41, 40, 'ajj', '2024-06-28 01:49:57', './uploads/667da64592811.jpeg', 1),
(51, 40, 41, 40, 'ajj', '2024-06-28 01:50:21', './uploads/667da65d39193.jpg', 1),
(52, 40, 41, 40, 'hh', '2024-06-28 01:51:34', './uploads/667da6a64db49.jpeg', 1),
(53, 40, 41, 40, 'hah', '2024-06-28 01:53:30', './uploads/667da71a2ffb0.jpg', 1),
(54, 40, 41, 40, '54', '2024-06-28 01:56:53', './uploads/667da7e541801.png', 1),
(55, 40, 41, 40, '55', '2024-06-28 01:57:06', './uploads/667da7f2ab330.jpg', 1),
(56, 40, 41, 40, '23', '2024-06-28 01:59:34', './uploads/667da886ad05e.jpeg', 1),
(57, 40, 41, 40, '12', '2024-06-28 01:59:41', NULL, 1),
(58, 40, 41, 40, '5432', '2024-06-28 02:00:56', './uploads/667da8d856170.jpg', 1),
(59, 40, 41, 40, '243', '2024-06-28 02:04:09', NULL, 1),
(60, 40, 41, 40, '123', '2024-06-28 02:06:53', './uploads/667daa3da47d6.jpg', 1),
(61, 40, 41, 40, '554', '2024-06-28 02:08:26', './uploads/667daa9ab3676.jpg', 1),
(62, 40, 41, 40, '355', '2024-06-28 02:10:13', './uploads/667dab05e7083.jpg', 1),
(63, 40, 41, 40, '231', '2024-06-28 02:10:21', './uploads/667dab0dab5e1.jpg', 1),
(64, 40, 0, 40, '3434', '2024-06-28 02:13:04', './uploads/667dabb037356.jpeg', 0),
(65, 40, 0, 40, '234', '2024-06-28 02:17:18', './uploads/667dacae616d5.jpeg', 0),
(66, 40, 41, 40, '34345', '2024-06-28 02:18:08', './uploads/667dace00f555.jpeg', 1),
(67, 40, 41, 40, '2334', '2024-06-28 02:22:28', './uploads/667dade4df419.jpg', 1),
(68, 40, 41, 40, '432', '2024-06-28 02:28:53', './uploads/667daf651db3c.jpg', 1),
(69, 40, 41, 40, '435', '2024-06-28 02:29:33', './uploads/667daf8dbd93b.jpg', 1),
(70, 40, 41, 40, '432', '2024-06-28 02:33:28', './uploads/667db078c94bd.jpg', 1),
(71, 40, 41, 40, '432', '2024-06-28 02:33:37', './uploads/667db081c31b2.jpg', 1),
(72, 40, 41, 40, '4325', '2024-06-28 02:37:37', './uploads/667db171d711d.jpg', 1),
(73, 40, 41, 40, '4324', '2024-06-28 02:39:03', './uploads/667db1c7ca57c.jpeg', 1),
(74, 40, 0, 40, '325', '2024-06-28 02:44:55', './uploads/667db32712902.jpg', 0),
(108, 40, 0, 40, '12345', '2024-06-28 15:39:25', './uploads/667e68adce3c8.png', 0),
(121, 40, 0, 40, 'asd', '2024-06-28 16:04:27', NULL, 0),
(122, 40, 0, 40, '2134', '2024-06-28 16:11:30', NULL, 0),
(123, 40, 0, 40, '234', '2024-06-28 16:13:05', NULL, 0),
(124, 40, 0, 40, '543', '2024-06-28 16:13:10', './uploads/667e70967e86c.jpg', 0),
(125, 40, 0, 40, '123', '2024-06-28 16:21:31', NULL, 0),
(126, 40, 0, 40, '123', '2024-06-28 16:22:04', NULL, 0),
(127, 40, 0, 40, '0', '2024-06-28 16:25:38', '534', 0),
(128, 40, 0, 40, '55', '2024-06-28 16:27:56', NULL, 0),
(129, 40, 0, 40, '123', '2024-06-28 16:28:45', NULL, 0),
(130, 40, 0, 40, '23154', '2024-06-28 16:35:19', NULL, 0),
(131, 40, 0, 40, '123', '2024-06-28 16:36:34', NULL, 0),
(132, 40, 0, 40, '123', '2024-06-28 16:37:58', NULL, 0),
(133, 40, 0, 40, '234', '2024-06-28 16:38:27', NULL, 0),
(134, 40, 0, 40, '55553434', '2024-06-28 16:38:54', NULL, 0),
(135, 40, 0, 40, '908978', '2024-06-28 16:39:16', NULL, 0),
(136, 40, 0, 40, '255 c', '2024-06-28 16:43:54', NULL, 0),
(137, 40, 40, 40, '255 crt', '2024-06-28 16:44:32', NULL, 1),
(138, 40, 0, 40, '5342', '2024-06-28 16:47:49', NULL, 0),
(139, 40, 41, 40, '532', '2024-06-28 16:49:44', NULL, 1),
(140, 40, 41, 40, '32', '2024-06-28 16:49:49', NULL, 1),
(141, 40, 41, 40, '24', '2024-06-28 16:49:55', NULL, 1),
(142, 40, 41, 40, '43245435', '2024-06-28 16:53:15', NULL, 1),
(143, 41, 40, 41, 'I am so high in time', '2024-06-28 19:12:13', NULL, 1),
(144, 41, 40, 41, 'huha', '2024-06-28 19:12:19', NULL, 1),
(145, 40, 41, 40, '55', '2024-06-28 19:13:36', NULL, 1),
(146, 40, 41, 40, '24', '2024-06-28 19:13:41', NULL, 1),
(147, 40, 41, 40, '24', '2024-06-28 19:13:46', NULL, 1),
(148, 40, 41, 40, 'huha on time', '2024-06-28 19:14:19', NULL, 1),
(149, 40, 41, 40, ' ', '2024-06-28 19:23:08', NULL, 1),
(150, 40, 41, 40, '154', '2024-06-28 19:30:24', NULL, 1),
(151, 40, 41, 40, '12', '2024-06-28 19:30:39', NULL, 1),
(152, 40, 41, 40, '22', '2024-06-28 19:30:54', NULL, 1),
(153, 40, 41, 40, '55', '2024-06-28 19:30:56', NULL, 1),
(154, 40, 41, 40, '22', '2024-06-28 19:31:01', NULL, 1),
(155, 40, 41, 40, '23', '2024-06-30 03:18:21', NULL, 1),
(156, 40, 41, 40, '24', '2024-06-30 03:27:06', NULL, 1),
(157, 40, 41, 40, '32', '2024-06-30 21:44:35', NULL, 1),
(158, 40, 41, 40, '123', '2024-07-02 18:12:19', NULL, 1),
(159, 40, 41, 40, '123', '2024-07-02 18:15:22', NULL, 1),
(160, 41, 40, 41, 'asd', '2024-07-26 06:08:58', NULL, 1),
(161, 41, 40, 41, 'asd', '2024-07-26 06:11:15', NULL, 1),
(162, 41, 40, 41, 'asd', '2024-07-26 06:11:26', NULL, 1),
(163, 41, 40, 41, 'asd', '2024-07-26 06:11:29', NULL, 1),
(164, 41, 40, 41, 'asd', '2024-07-26 06:11:33', NULL, 1),
(165, 41, 40, 41, 'asd', '2024-07-26 06:11:56', NULL, 1),
(166, 41, 40, 41, 'asd', '2024-07-26 06:11:58', NULL, 1),
(167, 40, 41, 40, 'asd', '2024-07-26 06:13:43', NULL, 1),
(168, 41, 40, 41, 'asd', '2024-07-26 06:13:50', NULL, 1),
(169, 41, 40, 41, 'a', '2024-07-26 06:14:02', NULL, 1),
(170, 40, 41, 40, 'ffas', '2024-07-26 08:23:48', NULL, 1),
(171, 41, 41, 41, 'm41', '2024-07-26 17:02:10', NULL, 1),
(172, 41, 41, 41, 'asd', '2024-07-26 17:02:21', NULL, 1),
(173, 41, 41, 41, '456', '2024-07-26 17:02:49', NULL, 1),
(174, 41, 41, 41, 'as', '2024-07-26 17:19:09', NULL, 1),
(175, 41, 40, 41, 'as', '2024-08-03 13:00:59', NULL, 1),
(176, 41, 40, 41, '34', '2024-08-03 13:04:44', NULL, 1),
(177, 41, 40, 41, 'as', '2024-08-03 13:04:52', NULL, 1),
(178, 41, 40, 41, 'asas', '2024-08-03 13:05:07', NULL, 1),
(179, 41, 40, 41, 'a', '2024-08-03 13:05:12', NULL, 1),
(180, 41, 40, 41, '23', '2024-08-03 13:05:17', NULL, 1),
(181, 40, 41, 40, 'darci', '2024-08-12 11:44:08', NULL, 1),
(182, 40, 41, 40, 'lommy', '2024-08-12 12:09:47', NULL, 1),
(183, 40, 41, 40, 'meme', '2024-08-18 14:11:14', './uploads/66c19082b39c2.jpg', 1),
(184, 40, 41, 40, 'jeje', '2024-08-18 14:13:31', './uploads/66c1910b00ce2.jpg', 1),
(185, 40, 41, 40, 'jeje', '2024-08-18 14:14:39', './uploads/66c1914f347b3.jpg', 1),
(186, 40, 41, 40, 'keke', '2024-08-18 14:16:09', './uploads/66c191a908713.gif', 1),
(187, 40, 41, 40, 'meme', '2024-08-18 14:17:57', './uploads/66c19215e36dd.gif', 1),
(188, 41, 40, 41, 'asdasd', '2024-08-18 14:36:28', NULL, 1),
(189, 41, 40, 41, 'asd', '2024-08-18 14:36:55', NULL, 1),
(190, 41, 40, 41, 'sdf', '2024-08-18 14:36:56', NULL, 1),
(191, 41, 40, 41, 'fdsf', '2024-08-18 14:37:00', NULL, 1),
(192, 41, 40, 41, 'sdfds', '2024-08-18 14:37:05', NULL, 1),
(193, 41, 40, 41, 'ajj', '2024-08-18 14:57:15', NULL, 1),
(194, 41, 40, 41, 'gg', '2024-08-18 14:57:30', NULL, 1),
(195, 41, 40, 41, 'bababa', '2024-08-18 15:51:30', NULL, 1),
(196, 40, 41, 40, 'gg', '2024-08-18 15:51:39', NULL, 1),
(197, 41, 40, 41, 'bootstrap bill', '2024-08-18 16:59:18', NULL, 1),
(198, 40, 41, 40, 'aj', '2024-08-18 16:59:25', NULL, 1),
(199, 40, 41, 40, 'mm', '2024-08-18 17:16:44', NULL, 1),
(200, 40, 41, 40, 'yy', '2024-08-18 17:16:50', NULL, 1),
(201, 40, 41, 40, 'ee', '2024-08-18 17:18:21', NULL, 1),
(202, 40, 41, 40, 'kk', '2024-08-18 17:20:23', NULL, 1),
(203, 40, 41, 40, 'll', '2024-08-18 17:20:28', NULL, 1),
(204, 40, 41, 40, 'pp', '2024-08-18 17:20:36', NULL, 1),
(205, 40, 41, 40, 'mm', '2024-08-18 17:29:09', NULL, 1),
(206, 40, 41, 40, 'kk', '2024-08-19 09:49:34', './uploads/66c2a4aebebe5.png', 1),
(207, 40, 41, 40, 'lop', '2024-08-19 09:50:50', './uploads/66c2a4fa13301.mp4', 1),
(208, 40, 41, 40, 'lmao', '2024-08-19 10:21:10', './uploads/66c2ac16e4025.jpg', 1),
(209, 41, 40, 41, 'gg', '2024-08-19 11:56:47', NULL, 1),
(210, 41, 40, 41, 'asd', '2024-08-19 11:57:04', NULL, 1),
(211, 41, 40, 41, 'aa', '2024-08-19 11:57:29', NULL, 1),
(212, 41, 40, 41, 'gg', '2024-08-19 11:58:38', NULL, 1),
(213, 41, 40, 41, 'aa', '2024-08-19 12:35:17', NULL, 1),
(214, 41, 40, 41, 'qq', '2024-08-19 12:44:00', './uploads/66c2cd907d9c0.png', 1),
(215, 41, 40, 41, 'as', '2024-08-19 13:39:48', './uploads/66c2daa451e54.jpeg', 1),
(216, 41, 40, 41, 'sd', '2024-08-19 13:39:54', NULL, 1),
(217, 41, 40, 41, 'ff', '2024-08-19 13:40:03', './uploads/66c2dab35fa1c.jpeg', 1),
(218, 41, 40, 41, 'ff', '2024-08-19 13:40:11', NULL, 1),
(219, 41, 40, 41, 'as', '2024-08-19 13:42:29', './uploads/66c2db4560c15.png', 1),
(220, 41, 40, 41, 'g', '2024-08-19 13:42:36', './uploads/66c2db4c6e8b3.png', 1),
(221, 41, 40, 41, 'aas', '2024-08-19 13:42:46', './uploads/66c2db56757c4.png', 1),
(222, 41, 40, 41, 'as', '2024-08-19 13:42:51', './uploads/66c2db5b464a9.png', 1),
(223, 41, 40, 41, 'gg', '2024-08-19 13:53:15', './uploads/66c2ddcb61f3c.png', 1),
(224, 41, 40, 41, 'tt', '2024-08-19 13:53:18', NULL, 1),
(225, 41, 40, 41, 'gg', '2024-08-19 13:53:26', './uploads/66c2ddd63a3fb.jpg', 1),
(226, 41, 40, 41, 'gg', '2024-08-19 13:54:07', NULL, 1),
(227, 41, 40, 41, 'tt', '2024-08-19 13:54:18', './uploads/66c2de0a83049.jpg', 1),
(228, 41, 40, 41, 'yy', '2024-08-19 13:54:23', NULL, 1),
(229, 41, 40, 41, 'gg', '2024-08-19 13:59:17', './uploads/66c2df352d77f.mp4', 1),
(230, 41, 40, 41, 'tt', '2024-08-19 13:59:24', NULL, 1),
(231, 41, 40, 41, 'tt', '2024-08-19 13:59:32', './uploads/66c2df44603c2.mp4', 1),
(232, 41, 40, 41, 'tt', '2024-08-19 13:59:41', NULL, 1),
(233, 40, 41, 40, 'lol', '2024-08-22 23:26:09', NULL, 1),
(234, 40, 40, 40, 'gg', '2024-08-23 10:31:42', NULL, 1),
(235, 41, 40, 41, 'luper', '2024-08-23 11:34:41', NULL, 1),
(236, 40, 41, 40, 'gg', '2024-08-23 11:46:41', NULL, 1),
(237, 40, 41, 40, 'gg', '2024-08-23 11:48:25', './uploads/66c8068910225.jpg', 1),
(238, 40, 41, 40, 'tt', '2024-08-23 11:48:33', './uploads/66c80691be2c3.jpg', 1),
(239, 40, 41, 40, 'yy', '2024-08-23 11:56:19', './uploads/66c80863d669d.png', 1),
(240, 40, 41, 40, 'aa', '2024-08-23 11:57:33', './uploads/66c808ad4091e.png', 1),
(241, 40, 41, 40, 'sad', '2024-08-23 11:59:39', './uploads/66c8092b8250c.png', 1),
(242, 40, 41, 40, 'ee', '2024-08-23 11:59:45', NULL, 1),
(243, 40, 41, 40, 'test', '2024-08-23 12:00:33', NULL, 1),
(244, 40, 41, 40, 'test', '2024-08-23 12:00:41', './uploads/66c80969372de.jpg', 1),
(245, 40, 41, 40, 'uu', '2024-08-23 12:00:52', './uploads/66c8097400584.jpg', 1),
(246, 40, 41, 40, 'yy', '2024-08-23 12:01:09', './uploads/66c809853fa07.jpeg', 1),
(247, 40, 41, 40, 'uu', '2024-08-23 12:01:20', NULL, 1),
(248, 40, 41, 40, 'yy', '2024-08-23 12:01:50', './uploads/66c809aeecc6a.png', 1),
(249, 40, 41, 40, 'gg', '2024-08-23 12:02:23', './uploads/66c809cfc527f.png', 1),
(250, 40, 41, 40, 'uu', '2024-08-23 12:02:29', NULL, 1),
(251, 40, 41, 40, 'yy', '2024-08-23 12:03:58', NULL, 1),
(252, 40, 41, 40, 'yy', '2024-08-23 12:04:10', './uploads/66c80a3a718fb.png', 1),
(253, 40, 41, 40, 'yy', '2024-08-25 14:53:30', NULL, 1),
(254, 40, 41, 40, 'tt', '2024-08-25 15:09:57', NULL, 1),
(255, 40, 41, 40, 'uu', '2024-08-25 15:10:03', NULL, 1),
(256, 40, 41, 40, 'kk', '2024-08-25 15:10:07', NULL, 1),
(257, 40, 41, 40, 'q', '2024-08-25 15:10:13', NULL, 1),
(258, 40, 41, 40, 'aa', '2024-08-26 19:19:01', NULL, 1),
(259, 40, 41, 40, 'aaa', '2024-08-26 19:19:24', './uploads/66cc64bc3eda4.mp4', 1),
(260, 41, 41, 41, 'g', '2024-08-26 20:07:43', './uploads/66cc700f102b7.mp4', 1),
(261, 41, 41, 41, 'g', '2024-08-26 20:08:00', './uploads/66cc702001736.png', 1),
(262, 41, 41, 41, 'assssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssa', '2024-08-26 20:10:35', NULL, 1),
(263, 41, 41, 41, 'assssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaassssssssssaasssssss', '2024-08-26 20:10:42', NULL, 1),
(264, 41, 40, 41, 'gg', '2024-09-02 22:45:30', NULL, 1),
(265, 41, 40, 41, 'ee', '2024-09-02 22:45:38', NULL, 1),
(266, 41, 40, 41, 'gg', '2024-10-03 21:21:33', NULL, 1),
(267, 40, 41, 40, 'test seen', '2024-10-03 21:22:52', NULL, 1),
(268, 41, 40, 41, 'test seen 2nd\r\n', '2024-10-03 21:28:39', NULL, 1),
(269, 40, 41, 40, 'lupercal', '2024-10-03 21:29:16', NULL, 1),
(270, 40, 41, 40, 'jj', '2024-10-03 21:29:41', NULL, 1),
(271, 40, 41, 40, 'ii', '2024-10-03 21:30:12', NULL, 1),
(272, 40, 41, 40, 'yo', '2024-10-04 00:30:48', NULL, 1),
(273, 40, 41, 40, 'lupercal', '2024-10-04 00:31:51', NULL, 1),
(274, 40, 41, 40, 'yii\r\n', '2024-10-04 00:33:33', NULL, 1),
(275, 40, 41, 40, 'yollo', '2024-10-04 00:43:57', NULL, 1),
(276, 40, 41, 40, 'nice\r\n', '2024-10-04 00:50:51', NULL, 1),
(277, 40, 41, 40, 'progress', '2024-10-04 00:56:08', NULL, 1),
(278, 40, 41, 40, 'people\r\n', '2024-10-04 00:56:23', NULL, 1),
(279, 40, 41, 40, 'more', '2024-10-04 00:58:58', NULL, 1),
(280, 40, 41, 40, 'yo\r\n', '2024-10-04 01:03:24', NULL, 1),
(281, 40, 41, 40, 'hi', '2024-10-04 01:19:45', NULL, 1),
(282, 40, 41, 40, 'gg', '2024-10-04 01:20:45', NULL, 1),
(283, 40, 41, 40, 'last test', '2024-10-04 01:28:29', NULL, 1),
(284, 41, 40, 41, 'nice one', '2024-10-04 01:29:25', NULL, 1),
(285, 41, 40, 41, 'yrllo', '2024-10-04 01:43:30', NULL, 1),
(286, 41, 40, 41, 'yello', '2024-10-04 01:43:54', NULL, 1),
(287, 41, 40, 41, 'one last', '2024-10-04 01:45:44', NULL, 1),
(288, 40, 41, 40, 'horus heresy', '2024-10-04 01:46:59', NULL, 1),
(289, 40, 41, 40, 'gg', '2024-10-04 01:47:52', NULL, 1),
(290, 40, 41, 40, 'gg last na', '2024-10-04 01:51:43', NULL, 1),
(291, 40, 41, 40, 'last ule', '2024-10-04 02:00:22', NULL, 1),
(292, 40, 41, 40, 'isa pa', '2024-10-04 02:01:03', NULL, 1),
(293, 40, 41, 40, 'gg', '2024-10-04 02:01:41', NULL, 1),
(294, 40, 41, 40, 'hilaw', '2024-10-04 02:02:27', NULL, 1),
(295, 41, 40, 41, 'oks oks ', '2024-10-04 02:09:27', NULL, 1),
(296, 40, 41, 40, 'testing again', '2024-10-04 02:24:38', NULL, 1),
(297, 40, 41, 40, 'gg', '2024-10-04 02:25:22', NULL, 1),
(298, 40, 41, 40, 'gg', '2024-10-04 02:41:47', NULL, 1),
(299, 40, 41, 40, 'yooo', '2024-10-04 02:43:52', NULL, 1),
(300, 40, 41, 40, 'gg', '2024-10-04 02:44:20', NULL, 1),
(301, 40, 41, 40, 'hye\r\n', '2024-10-04 02:46:59', NULL, 1),
(302, 40, 41, 40, 'gg', '2024-10-04 02:50:52', NULL, 1),
(303, 40, 41, 40, 'ee', '2024-10-04 02:51:35', NULL, 1),
(304, 41, 40, 41, 'conflict', '2024-10-04 02:53:01', NULL, 1),
(305, 40, 40, 40, ' ', '2024-10-26 05:13:23', './uploads/671c09f3a6835.jpg', 1),
(306, 40, 40, 40, '', '2024-10-26 05:15:44', './uploads/671c0a808b72b.png', 1),
(307, 40, 40, 40, 'hello', '2024-10-26 05:17:50', NULL, 1),
(308, 41, 40, 41, 'shadow on the sun', '2024-10-26 05:22:38', NULL, 0),
(309, 41, 40, 41, '', '2024-10-26 05:26:48', './uploads/671c0d182a663.jpg', 0),
(310, 41, 40, 41, '', '2024-10-26 05:27:10', './uploads/671c0d2e1576e.png', 0);

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
(17, 'asdasd', 4000, 8768, 847456, '', '2028-06-02', 'true'),
(18, 'Aking sinta', 999, 61, 846, '../uploads/received_993104588452786_66f44ca55df29.jpeg', '2024-09-19', ''),
(19, 'Broonam Mad3434567german Goodasd', 5435234, 61, 846, '../uploads/GAMEPOSTER-1_66f44f10ccbec.jpg', '2024-09-26', ''),
(20, 'Broonam Mad3434567german Goodasd', 987, 61, 846, '../uploads/GAMEPOSTER_66f4530c5c7cb.jpg', '2024-09-26', '');

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
(74, 'Jerson', 'Wayas', 'Lippad', '09324404218', '85', 'user', 864, 'Duplex', '2024-10-29', NULL, '2024-10-30');

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
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `phonenumber` varchar(15) NOT NULL,
  `otp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `middlename`, `lastname`, `password`, `email`, `role`, `Date`, `phonenumber`, `otp`) VALUES
(84, 'admin', 'Jerson', 'Wayas', 'Lippad', '1234567', '', 'admin', '2024-10-29 19:18:15', '', NULL),
(85, 'user', 'Jerson', 'Wayas', 'Lippad', '1234567', '', 'user', '2024-10-29 19:18:25', '', NULL),
(86, 'john', 'John', 'North', 'Garcia', '1234567', '', 'user', '2024-10-29 19:29:19', '', NULL),
(87, 'jan', 'Jan', 'West', 'Nuevo', '1234567', '', 'user', '2024-10-29 19:29:38', '', NULL);

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=869;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

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
