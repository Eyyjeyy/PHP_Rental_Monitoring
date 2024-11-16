-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 01:47 PM
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
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(50) NOT NULL,
  `tenants_id` int(50) NOT NULL,
  `adminname` varchar(500) NOT NULL,
  `tenantname` varchar(500) NOT NULL,
  `filename` varchar(500) NOT NULL,
  `fileurl` varchar(500) NOT NULL,
  `tenantapproval` varchar(50) NOT NULL,
  `datestart` date DEFAULT current_timestamp(),
  `expirationdate` date NOT NULL DEFAULT current_timestamp(),
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `tenants_id`, `adminname`, `tenantname`, `filename`, `fileurl`, `tenantapproval`, `datestart`, `expirationdate`, `upload_date`) VALUES
(38, 75, 'Jerson Wayas Lippad', 'Jan West Nuevo', 'Jan West Nuevo_contract.docx', '/asset/user_contracts/Jan West Nuevo_contract.docx', 'true', '2024-11-16', '2024-11-23', '2024-11-16 10:03:17');

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
(16, 'CR Repair', 'Broken Faucet', 500, 858, '2024-11-02'),
(17, 'Roof', 'Roof Leakage', 5000, 860, '2024-11-02'),
(18, 'Paint', 'White Paint for Rennovation', 2000, 863, '2024-11-02'),
(19, 'Paint', 'Gray Paint for Rennovation', 3000, 858, '2024-11-02');

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
(227, 40, 'Add', 'Added User, ID: 87<br> Username: jan', '2024-10-29 11:29:38'),
(228, 84, 'Add', 'Added Expenses, ID: 16<br>Expenses Name : CR Repair<br>Expenses Info : Broken Faucet<br>Expenses Amount : 500<br>House ID: 858<br>', '2024-11-02 06:01:49'),
(229, 84, 'Delete', 'Deleted Paper Category, ID: 63<br>Category Name, : 3432<br>', '2024-11-02 06:02:20'),
(230, 84, 'Delete', 'Deleted Paper Category, ID: 68<br>Category Name, : asd<br>', '2024-11-02 06:02:21'),
(231, 84, 'Delete', 'Deleted Paper Category, ID: 70<br>Category Name, : affd<br>', '2024-11-02 06:02:22'),
(232, 84, 'Delete', 'Deleted Paper Category, ID: 83<br>Category Name, : xds<br>', '2024-11-02 06:02:23'),
(233, 84, 'Delete', 'Deleted Paper Category, ID: 87<br>Category Name, : bebe<br>', '2024-11-02 06:02:23'),
(234, 84, 'Add', 'Added Paper Category, ID: 89<br>Category Name, : Roxasville Paper<br>', '2024-11-02 06:02:47'),
(235, 84, 'Add', 'Added Paper Category, ID: 90<br>Category Name, : Bagatua Paper<br>', '2024-11-02 06:02:51'),
(236, 84, 'Add', 'Added Paper Category, ID: 91<br>Category Name, : Regalado Paper<br>', '2024-11-02 06:02:55'),
(237, 84, 'Add', 'Added Paper Category, ID: 92<br>Category Name, : Lagro Paper<br>', '2024-11-02 06:03:09'),
(238, 84, 'Delete', 'Deleted Paper, ID: 1<br>Paper Name, : Mental Omega Screenshot 2023.04.11 - 12.13.09.91.png<br>', '2024-11-02 06:03:11'),
(239, 84, 'Delete', 'Deleted Paper, ID: 3<br>Paper Name, : received_993104588452786.jpeg<br>', '2024-11-02 06:03:11'),
(240, 84, 'Delete', 'Deleted Paper, ID: 23<br>Paper Name, : 6685763997254.docx<br>', '2024-11-02 06:03:12'),
(241, 84, 'Delete', 'Deleted Paper, ID: 25<br>Paper Name, : Untitled_66858228aefaf.png<br>', '2024-11-02 06:03:12'),
(242, 84, 'Delete', 'Deleted Paper, ID: 31<br>Paper Name, : BooTails Petspa & Mobile Grooming-Unleash Merchant Partnership Letter_66890be9852b5.docx<br>', '2024-11-02 06:03:12'),
(243, 84, 'Delete', 'Deleted Paper, ID: 32<br>Paper Name, : Terms and Conditions for Unleash_669a700d8f616.docx<br>', '2024-11-02 06:03:13'),
(244, 84, 'Delete', 'Deleted Paper, ID: 38<br>Paper Name, : Untitled_669a891a62e5c.png<br>', '2024-11-02 06:03:14'),
(245, 84, 'Delete', 'Deleted Paper, ID: 40<br>Paper Name, : Untitled_66b9cf3e1e116.png<br>', '2024-11-02 06:03:14'),
(246, 84, 'Delete', 'Deleted Paper, ID: 41<br>Paper Name, : GAMEPOSTER_66b9d129518a2.jpg<br>', '2024-11-02 06:03:14'),
(247, 84, 'Add', 'Added Paper, ID: 42<br>Category Name : Roxasville Paper<br>File Name : paper_6725c125f2292.jpeg<br>', '2024-11-02 06:05:25'),
(248, 84, 'Message', 'Message, ID: 311<br>Receiver, : admin<br>', '2024-11-02 06:08:28'),
(249, 84, 'Add', 'Added Expenses, ID: 17<br>Expenses Name : Roof<br>Expenses Info : Roof Leakage<br>Expenses Amount : 5000<br>House ID: 860<br>', '2024-11-02 06:09:16'),
(250, 84, 'Add', 'Added Expenses, ID: 18<br>Expenses Name : Paint<br>Expenses Info : White Paint for Rennovation<br>Expenses Amount : 2000<br>House ID: 863<br>', '2024-11-02 06:09:43'),
(251, 84, 'Add', 'Added Expenses, ID: 19<br>Expenses Name : Paint<br>Expenses Info : Gray Paint for Rennovation<br>Expenses Amount : 3000<br>House ID: 858<br>', '2024-11-02 06:10:10'),
(252, 84, 'Add', 'Added Tenant, ID: 75<br>First Name: Jan<br>Middle Name: West<br>Last Name: Nuevo<br>Contact: 09235552786<br>User ID: 87<br>Username: jan<br>House ID: 868<br>House Category: Land', '2024-11-02 06:12:37'),
(253, 84, 'Add', 'Added Tenant, ID: 76<br>First Name: John<br>Middle Name: North<br>Last Name: Garcia<br>Contact: 09325557609<br>User ID: 86<br>Username: john<br>House ID: 865<br>House Category: Duplex', '2024-11-02 06:13:27'),
(254, 84, 'Approve', 'Payment Approved, ID: 22<br>Approval: Pending -> Accepted', '2024-11-02 06:33:18'),
(255, 84, 'Approve', 'Payment Approved, ID: 23<br>Approval: Pending -> Accepted', '2024-11-02 06:33:19'),
(256, 84, 'Declined', 'Payment Declined, ID: 22<br>Approval: Accepted -> Declined', '2024-11-02 06:33:45'),
(257, 84, 'Approve', 'Payment Approved, ID: 21<br>Approval: Pending -> Accepted', '2024-11-02 06:33:46'),
(258, 84, 'Approve', 'Payment Approved, ID: 22<br>Approval: Declined -> Accepted', '2024-11-02 06:33:54'),
(259, 84, 'Declined', 'Payment Declined, ID: 22<br>Approval: Accepted -> Declined', '2024-11-02 06:33:57'),
(260, 84, 'Add', 'Added User, ID: 88<br> Username: ella', '2024-11-07 01:46:08'),
(261, 84, 'Add', 'Added Tenant, ID: 77<br>First Name: mae<br>Middle Name: deves<br>Last Name: cruz<br>Contact: <br>User ID: 88<br>Username: ella<br>House ID: 862<br>House Category: Studio', '2024-11-07 01:51:00'),
(262, 84, 'Add', 'Added User, ID: 89<br> Username: lupercal', '2024-11-08 10:31:56'),
(263, 84, 'Add', 'Added Paper, ID: 43<br>Category Name : Lagro Paper<br>File Name : GAMEPOSTER_672f05ed6d1b5.jpg<br>', '2024-11-09 06:49:17'),
(264, 84, 'Add', 'Added Paper, ID: 44<br>Category Name : Roxasville Paper<br>File Name : Untitled_672f09f9630fb.png<br>', '2024-11-09 07:06:33'),
(265, 84, 'Add', 'Added Paper Category, ID: 93<br>Category Name, : <br>', '2024-11-09 07:08:08'),
(266, 84, 'Add', 'Added Paper, ID: 45<br>Category Name : Roxasville Paper<br>File Name : received_993104588452786_672f0a585df10.jpeg<br>', '2024-11-09 07:08:08'),
(267, 84, 'Add', 'Added Paper Category, ID: 94<br>Category Name, : <br>', '2024-11-09 07:09:01'),
(268, 84, 'Add', 'Added Paper Category, ID: 95<br>Category Name, : <br>', '2024-11-09 07:09:02'),
(269, 84, 'Add', 'Added Paper, ID: 46<br>Category Name : Roxasville Paper<br>File Name : Untitled_672f0b419e774.png<br>', '2024-11-09 07:12:01'),
(270, 84, 'Add', 'Added Paper, ID: 47<br>Category Name : Roxasville Paper<br>File Name : Untitled_672f0bdc64a6f.png<br>', '2024-11-09 07:14:36'),
(271, 84, 'Delete', 'Deleted Expense, ID: 33<br>Contract Tenant Name : Jerson Wayas Lippad<br>', '2024-11-15 13:53:18'),
(272, 84, 'Delete', 'Deleted Contract, ID: 34<br>Contract Tenant Name: Jan West Nuevo<br>', '2024-11-15 14:36:59'),
(273, 84, 'Delete', 'Deleted Contract, ID: 37<br>Contract Tenant Name: Jerson Wayas Lippad<br>', '2024-11-16 10:02:56');

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
  `water_accnum` int(50) NOT NULL,
  `gcash` varchar(50) NOT NULL,
  `bank` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `houseaccounts`
--

INSERT INTO `houseaccounts` (`id`, `houses_id`, `elec_accname`, `elec_accnum`, `water_accname`, `water_accnum`, `gcash`, `bank`) VALUES
(1, 846, 'aj', 765, '', 0, '', ''),
(3, 848, 'asd', 4234, 'asd', 54235, '', ''),
(13, 858, 'John', 132053845, 'John', 93473595, '', ''),
(14, 859, 'Jan', 2147483647, 'Jan', 345334288, '', ''),
(15, 860, 'John', 21342342, 'John', 42635636, '', ''),
(16, 861, 'Jan', 2342634, 'Jan', 6354636, '', ''),
(17, 862, 'Jan', 45345345, 'Jan', 34532347, '', ''),
(18, 863, 'Jemille', 4647457, 'Jemille', 457452, '', ''),
(19, 864, 'Mildred', 3213426, 'Mildred', 3437474, '', ''),
(20, 865, 'Mildred', 42346363, 'Mildred', 6366346, '', ''),
(21, 866, 'Jerum', 325252, 'Jerum', 263453, '', ''),
(22, 867, 'Jerum', 34537745, 'Jerum', 45747455, '', ''),
(23, 868, 'Mikhail', 23426346, 'Mikhail', 46363466, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(50) NOT NULL,
  `house_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `category_id` int(50) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_name`, `price`, `category_id`, `address`) VALUES
(858, 'Roxasville A', 15000, 74, ''),
(859, 'Roxasville B', 15000, 74, ''),
(860, 'Bagatua Corner A', 10000, 74, ''),
(861, 'Bagatua Corner B', 10000, 74, ''),
(862, 'Bagatua Corner C', 10000, 66, ''),
(863, 'Bagatua Corner D', 10000, 66, ''),
(864, 'Lagro A', 16000, 74, ''),
(865, 'Lagro B', 16000, 74, ''),
(866, 'Bagatua Side A', 5000, 75, ''),
(867, 'Bagatua Side B', 5000, 75, ''),
(868, 'Regalado', 30000, 77, '');

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
(89, 'Roxasville Paper', '2024-11-02 06:02:47'),
(90, 'Bagatua Paper', '2024-11-02 06:02:51'),
(91, 'Regalado Paper', '2024-11-02 06:02:55'),
(92, 'Lagro Paper', '2024-11-02 06:03:09'),
(93, '', '2024-11-09 07:08:08'),
(94, '', '2024-11-09 07:09:01'),
(95, '', '2024-11-09 07:09:02');

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
(42, 89, 'Roxasville Paper', 'paper_6725c125f2292.jpeg', '../uploads/paper_6725c125f2292.jpeg', '2024-11-02 06:05:25'),
(43, 92, 'Lagro Paper', 'GAMEPOSTER_672f05ed6d1b5.jpg', '../uploads/GAMEPOSTER_672f05ed6d1b5.jpg', '2024-11-09 06:49:17'),
(44, 89, 'Roxasville Paper', 'Untitled_672f09f9630fb.png', '../uploads/Untitled_672f09f9630fb.png', '2024-11-09 07:06:33'),
(45, 89, 'Roxasville Paper', 'received_993104588452786_672f0a585df10.jpeg', '../uploads/received_993104588452786_672f0a585df10.jpeg', '2024-11-09 07:08:08'),
(46, 89, 'Roxasville Paper', 'Untitled_672f0b419e774.png', '../uploads/Untitled_672f0b419e774.png', '2024-11-09 07:12:01'),
(47, 89, 'Roxasville Paper', 'Untitled_672f0bdc64a6f.png', '../uploads/Untitled_672f0bdc64a6f.png', '2024-11-09 07:14:36');

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
(21, 'Jerson Wayas Lippad', 16000, 74, 864, '../uploads/bdo receipt_6725c73b161d5.png', '2024-11-02', 'true'),
(22, 'Jerson Wayas Lippad', 0, 74, 864, '../uploads/pay maya receipt_6725c74fbce78.jpg', '2024-10-02', 'false'),
(23, 'John North Garcia', 16000, 76, 865, '../uploads/gcash receipt_6725c79088e73.jpg', '2024-09-02', 'true');

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
(74, 'Jerson', 'Wayas', 'Lippad', '09324404218', '85', 'user', 864, 'Duplex', '2024-10-29', NULL, '2024-10-06'),
(75, 'Jan', 'West', 'Nuevo', '09235552786', '87', 'jan', 868, 'Land', '2024-11-02', NULL, '2024-11-03'),
(76, 'John', 'North', 'Garcia', '09325557609', '86', 'john', 865, 'Duplex', '2024-11-02', NULL, '2024-11-04'),
(77, 'mae', 'deves', 'cruz', '', '88', 'ella', 862, 'Studio', '2024-11-07', NULL, '2024-11-14');

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
(87, 'jan', 'Jan', 'West', 'Nuevo', '1234567', '', 'user', '2024-10-29 19:29:38', '', NULL),
(88, 'ella', 'mae', 'deves', 'cruz', '1234567', '', 'user', '2024-11-07 09:46:08', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
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
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

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
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `paper_categories`
--
ALTER TABLE `paper_categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `paper_files`
--
ALTER TABLE `paper_files`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

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
