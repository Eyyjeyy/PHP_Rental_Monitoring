-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 02:11 PM
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
-- Table structure for table `archives`
--

CREATE TABLE `archives` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `tenants_id` int(11) NOT NULL,
  `vacancydate` date NOT NULL,
  `archive_users_username` varchar(200) NOT NULL,
  `archive_users_firstname` varchar(200) NOT NULL,
  `archive_users_middlename` varchar(200) NOT NULL,
  `archive_users_lastname` varchar(200) NOT NULL,
  `archive_users_password` varchar(200) NOT NULL,
  `archive_users_email` varchar(200) NOT NULL,
  `archive_users_phonenumber` varchar(200) NOT NULL,
  `archive_houses_house_name` varchar(50) NOT NULL,
  `archive_tenants_date_start` varchar(100) NOT NULL,
  `archive_tenants_date_preferred` varchar(100) NOT NULL,
  `archive_contracts_filename` varchar(200) NOT NULL,
  `archive_contracts_fileurl` varchar(200) NOT NULL,
  `archive_contracts_datestart` varchar(200) NOT NULL,
  `archive_contracts_expirationdate` varchar(200) NOT NULL,
  `archive_contracts_upload_date` varchar(200) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `archives`
--

INSERT INTO `archives` (`id`, `users_id`, `tenants_id`, `vacancydate`, `archive_users_username`, `archive_users_firstname`, `archive_users_middlename`, `archive_users_lastname`, `archive_users_password`, `archive_users_email`, `archive_users_phonenumber`, `archive_houses_house_name`, `archive_tenants_date_start`, `archive_tenants_date_preferred`, `archive_contracts_filename`, `archive_contracts_fileurl`, `archive_contracts_datestart`, `archive_contracts_expirationdate`, `archive_contracts_upload_date`, `date_time`) VALUES
(10, 88, 77, '2024-12-13', 'ella', 'mae', 'deves', 'cruz', '1234567', 'jerslippad3@gmail.com', '09955835160', 'Bagatua Corner C', '2024-11-07', '2024-11-14', '', '', '', '', '', '0000-00-00 00:00:00'),
(12, 87, 75, '2024-12-20', 'jan', 'Jan', 'West', 'Nuevo', '1234567', 'jerslippad3@gmail.com', '09955835160', 'Regalado', '2024-11-02', '2024-11-03', 'Jan West Nuevo_contract.docx', '/asset/user_contracts/Jan West Nuevo_contract.docx', '2024-11-16', '2024-11-23', '', '0000-00-00 00:00:00'),
(13, 85, 74, '2024-12-21', 'user', 'Jerson', 'Wayas', 'Lippad', '1234567', 'jerslippad3@gmail.com', '09955835160', 'Lagro A', '2024-10-29', '2024-06-06', '', '', '', '', '', '0000-00-00 00:00:00'),
(20, 94, 80, '2024-12-19', 'ttt', 'ttt', 'ttt', 'tttt', '1234567', '', '', 'Regalado', '2024-12-08', '2024-12-27', '', '', '', '', '', '0000-00-00 00:00:00'),
(201, 95, 81, '2025-01-25', 'spiderman', 'rhino', 'mega', 'lol', '1234567', 'jerslippad3@gmail.com', '09955835160', 'Regalado', '2024-12-11', '2024-12-20', 'rhino mega lol_contract.docx', '/asset/user_contracts/rhino mega lol_contract.docx', '2024-12-11', '2025-01-11', '', '0000-00-00 00:00:00');

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
-- Table structure for table `contract_images`
--

CREATE TABLE `contract_images` (
  `id` int(11) NOT NULL,
  `physical_contract_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contract_images`
--

INSERT INTO `contract_images` (`id`, `physical_contract_id`, `image_path`, `uploaded_at`) VALUES
(1, 3, '1731946460_Screenshot_2024-10-07_115835.png', '2024-11-19'),
(2, 3, '1731946460_Screenshot_2024-10-07_110900.png', '2024-11-19'),
(3, 3, '1731946460_Screenshot_2024-10-07_061027.png', '2024-11-19'),
(4, 4, '1731948673_20240816_212931.jpg', '2024-11-19'),
(5, 4, '1731948673_Poster_with_final_list_-_64th_CE_2_.jpg', '2024-11-19'),
(6, 6, '1731954194_Signed_Student_Consent_Form.pdf', '2024-11-19');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `id` int(11) NOT NULL,
  `tenantid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `deposit_filepath` varchar(500) NOT NULL,
  `houses_id` int(11) NOT NULL,
  `depositamount` int(11) NOT NULL,
  `depositdate` date NOT NULL DEFAULT current_timestamp(),
  `approval` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `eviction_popup`
--

CREATE TABLE `eviction_popup` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `seen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eviction_popup`
--

INSERT INTO `eviction_popup` (`id`, `users_id`, `seen`) VALUES
(1, 85, 'true'),
(2, 85, 'true');

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
(324, 84, 'Add', 'Added Tenant, ID: 78<br>First Name: cow<br>Middle Name: duck<br>Last Name: oxen<br>Contact: <br>User ID: 92<br>Username: bull<br>House ID: 868<br>House Category: Land', '2024-12-07 19:48:08'),
(325, 84, 'Add', 'Added Tenant, ID: 79<br>First Name: kkk<br>Middle Name: lll<br>Last Name: oooo<br>Contact: <br>User ID: 93<br>Username: jjj<br>House ID: 868<br>House Category: Land', '2024-12-07 21:01:13'),
(326, 84, 'Add', 'Added User, ID: 94<br> Username: ttt', '2024-12-08 04:14:14'),
(327, 84, 'Add', 'Added Tenant, ID: 80<br>First Name: ttt<br>Middle Name: ttt<br>Last Name: tttt<br>Contact: <br>User ID: 94<br>Username: ttt<br>House ID: 868<br>House Category: Land', '2024-12-08 04:14:40'),
(328, 84, 'Delete', 'Deleted Archive, ID: 20<br> Archived Username: ttt', '2024-12-11 09:59:36'),
(329, 84, 'Add', 'Added User, ID: 95<br> Username: spiderman', '2024-12-11 10:33:01'),
(330, 84, 'Update', 'Updated User, ID: 95<br> New Username: spiderman<br> Old Username: spiderman', '2024-12-11 10:33:19'),
(331, 84, 'Add', 'Added Tenant, ID: 81<br>First Name: rhino<br>Middle Name: mega<br>Last Name: lol<br>Contact: <br>User ID: 95<br>Username: spiderman<br>House ID: 868<br>House Category: Land', '2024-12-11 10:33:35'),
(332, 84, 'Add', 'Added Tenant, ID: 83<br>First Name: table<br>Middle Name: chair<br>Last Name: pong<br>Contact: <br>User ID: 98<br>Username: ping<br>House ID: 868<br>House Category: Land', '2024-12-15 12:29:31'),
(333, 84, 'Update', 'Updated House, ID: 870<br>Price: 65435 -> 65435<br>Address:  -> macarthurr', '2024-12-15 12:41:08'),
(334, 84, 'Update', 'Updated House, ID: 870<br>Price: 65435 -> 65435<br>Address: macarthurr -> macarthurrasda', '2024-12-15 12:47:16');

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
(1, 846, 'aj', 765, '', 0, '09955835160', 'Banko de Oro'),
(3, 848, 'asd', 4234, 'asd', 54235, '09955835160', 'Banko de Oro'),
(13, 858, 'John', 132053845, 'John', 93473595, '09955835160', 'Banko de Oro'),
(14, 859, 'Jan', 2147483647, 'Jan', 345334288, '09955835160', 'Banko de Oro'),
(15, 860, 'John', 21342342, 'John', 42635636, '09955835160', 'Banko de Oro'),
(16, 861, 'Jan', 2342634, 'Jan', 6354636, '09955835160', 'Banko de Oro'),
(17, 862, 'Jan', 45345345, 'Jan', 34532347, '09955835160', 'Banko de Oro'),
(18, 863, 'Jemille', 4647457, 'Jemille', 457452, '09955835160', 'Banko de Oro'),
(19, 864, 'Mildred', 3213426, 'Mildred', 3437474, '09955835160', 'Banko de Oro'),
(20, 865, 'Mildred', 42346363, 'Mildred', 6366346, '09955835160', 'Banko de Oro'),
(21, 866, 'Jerum', 325252, 'Jerum', 263453, '09955835160', 'Banko de Oro'),
(22, 867, 'Jerum', 34537745, 'Jerum', 45747455, '09955835160', 'Banko de Oro'),
(23, 868, 'Mikhail', 23426346, 'Mikhail', 46363466, '09955835160', 'Banko de Oro'),
(25, 870, 'asdasdad', 2147483647, 'asdad', 2147483647, '12312312312', '12312312312'),
(26, 871, 'aj', 1234567890, 'emmy', 1234567890, '99999999999', '99999999999');

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
(868, 'Regalado', 30000, 77, ''),
(870, 'Tinesting Ako', 65435, 66, 'macarthurrasda');

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
(45, 89, 'Roxasville Paper', 'received_993104588452786_672f0a585df10.jpeg', '../uploads/received_993104588452786_672f0a585df10.jpeg', '2024-11-09 07:08:08');

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
  `approval` varchar(50) NOT NULL,
  `archive` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `name`, `amount`, `tenants_id`, `houses_id`, `filepath`, `date_payment`, `approval`, `archive`) VALUES
(29, 'asda', 0, 123, 123, 'asdad', '2025-09-03', '', ''),
(31, 'rhino mega lol', 434, 81, 868, '../uploads/received_993104588452786 (1)_67596ab11c912.jpeg', '2024-12-27', '', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `physical_contracts`
--

CREATE TABLE `physical_contracts` (
  `id` int(150) NOT NULL,
  `tenantid` int(150) NOT NULL,
  `adminid` int(150) NOT NULL,
  `fileurl` varchar(500) NOT NULL,
  `datestart` date NOT NULL DEFAULT current_timestamp(),
  `expirationdate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `physical_contracts`
--

INSERT INTO `physical_contracts` (`id`, `tenantid`, `adminid`, `fileurl`, `datestart`, `expirationdate`) VALUES
(3, 77, 84, '', '2024-11-21', '2024-11-30'),
(4, 76, 84, '', '2024-11-18', '2024-11-23'),
(6, 74, 84, '', '2024-11-18', '2024-11-23');

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
(74, 'Jerson', 'Wayas', 'Lippad', '09324404218', '85', 'user', 864, 'Duplex', '2024-10-29', NULL, '2024-06-06'),
(75, 'Jan', 'West', 'Nuevo', '09235552786', '87', 'jan', 868, 'Land', '2024-11-02', NULL, '2024-11-03'),
(76, 'John', 'North', 'Garcia', '09325557609', '86', 'john', 865, 'Duplex', '2024-11-02', NULL, '2024-11-04'),
(77, 'mae', 'deves', 'cruz', '', '88', 'ella', 862, 'Studio', '2024-11-07', NULL, '2024-11-14'),
(83, 'table', 'chair', 'pong', '', '98', 'ping', 868, 'Land', '2024-12-15', NULL, '2025-01-09');

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
(84, 'admin', 'Jerson', 'Wayas', 'Lippad', '1234567', 'jerslippad3@gmail.com', 'admin', '2024-10-29 19:18:15', '09955835160', NULL),
(85, 'user', 'Jerson', 'Wayas', 'Lippad', '1234567', 'jerslippad3@gmail.com', 'user', '2024-10-29 19:18:25', '09955835160', NULL),
(86, 'john', 'John', 'North', 'Garcia', '1234567', 'jerslippad3@gmail.com', 'user', '2024-10-29 19:29:19', '09955835160', NULL),
(87, 'jan', 'Jan', 'West', 'Nuevo', '1234567', 'jerslippad3@gmail.com', 'user', '2024-10-29 19:29:38', '09955835160', NULL),
(88, 'ella', 'mae', 'deves', 'cruz', '1234567', 'jerslippad3@gmail.com', 'user', '2024-11-07 09:46:08', '09955835160', NULL),
(97, 'emperor', 'horus', 'lupercal', 'guilliman', '1234567', 'jerslippad3@gmail.com', 'user', '2024-12-15 20:17:13', '09955835160', NULL),
(98, 'ping', 'table', 'chair', 'pong', '1234567', 'jerslippad3@gmail.com', 'user', '2024-12-15 20:28:16', '09955835160', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `contract_images`
--
ALTER TABLE `contract_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eviction_popup`
--
ALTER TABLE `eviction_popup`
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
-- Indexes for table `physical_contracts`
--
ALTER TABLE `physical_contracts`
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
-- AUTO_INCREMENT for table `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `contract_images`
--
ALTER TABLE `contract_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `eviction_popup`
--
ALTER TABLE `eviction_popup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=872;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `paper_categories`
--
ALTER TABLE `paper_categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `paper_files`
--
ALTER TABLE `paper_files`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `physical_contracts`
--
ALTER TABLE `physical_contracts`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

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
