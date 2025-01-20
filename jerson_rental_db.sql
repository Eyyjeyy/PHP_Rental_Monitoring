-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2025 at 04:27 PM
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
(1, 'studio');

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
(7, 1, 'Jerson Wayas Lippad', 'john angelo payo junio', '1737138126-678a9fcee8336_john angelo payo junio_contract.pdf', '/asset/testpdf/1737138126-678a9fcee8336_john angelo payo junio_contract.pdf', 'true', '2025-01-17', '2025-02-06', '2025-01-17 18:22:06');

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

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`id`, `tenantid`, `adminid`, `deposit_filepath`, `houses_id`, `depositamount`, `depositdate`, `approval`, `reason`) VALUES
(1, 1, 105, '../deposits/GAMEPOSTER_678a42f2233ac.jpg', 1, 9000, '2025-01-17', 'Unapproved', '');

-- --------------------------------------------------------

--
-- Table structure for table `eviction_popup`
--

CREATE TABLE `eviction_popup` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `seen` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eviction_popup`
--

INSERT INTO `eviction_popup` (`id`, `users_id`, `seen`, `file_path`) VALUES
(6, 105, 'true', 'eviction_1_1736987198_6788523e741a0.pdf'),
(7, 105, 'true', 'eviction_1_1737029148_6788f61c1f2b3.pdf'),
(8, 105, 'true', 'eviction_1_1737029969_6788f951c0cf3.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `eviction_setting`
--

CREATE TABLE `eviction_setting` (
  `id` int(11) NOT NULL,
  `landlord_address` varchar(200) NOT NULL,
  `days_to_pay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eviction_setting`
--

INSERT INTO `eviction_setting` (`id`, `landlord_address`, `days_to_pay`) VALUES
(1, 'Red Dragon Compound', 35);

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
(1, 'Floor Repair', 'Repaired', 300, 1, '2024-12-16'),
(2, 'Floor Repair', 'Repaired', 300, 1, '2024-12-16');

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
(1, 84, 'Add', 'Added User, ID: 104<br> Username: aj', '2025-01-14 20:57:59'),
(2, 84, 'Delete', 'Deleted User, ID: 104<br> Username: aj', '2025-01-14 20:58:04'),
(3, 84, 'Add', 'Added User, ID: 105<br> Username: aj', '2025-01-14 20:58:21'),
(4, 84, 'Update', 'Updated User, ID: 105<br> New Username: aj<br> Old Username: aj', '2025-01-14 20:58:37'),
(5, 84, 'Add', 'Added Category, ID: 1<br> Username: studio', '2025-01-14 20:59:47'),
(6, 84, 'Add', 'Added House, ID: 1<br> Housename: Serpent Lodging<br> Category: 1<br> Price: 565<br> Electric Account: serpentmeralco (656565656565)<br> Water Account: serpentmaynilad (656565656565)', '2025-01-14 21:00:37'),
(7, 84, 'Add', 'Added Tenant, ID: 1<br>First Name: john angelo<br>Middle Name: payo<br>Last Name: junio<br>Contact: <br>User ID: 105<br>Username: aj<br>House ID: 1<br>House Category: studio', '2025-01-14 21:05:42'),
(8, 84, 'Declined', 'Deposit Declined, ID: 1<br>Approval: Pending -> Declined', '2025-01-17 12:00:19'),
(9, 84, 'Delete', 'Deleted Contract, ID: 4<br>Contract Tenant Name: john angelo payo junio<br>', '2025-01-17 16:10:50'),
(10, 84, 'Delete', 'Deleted Contract, ID: 5<br>Contract Tenant Name: john angelo payo junio<br>', '2025-01-17 17:50:37'),
(11, 84, 'Delete', 'Deleted Contract, ID: 6<br>Contract Tenant Name: john angelo payo junio<br>', '2025-01-17 18:21:46'),
(12, 84, 'Add', 'Added Expenses, ID: 1<br>Expenses Name : Floor Repair<br>Expenses Info : Repaired<br>Expenses Amount : 400<br>House ID: 1<br>', '2025-01-18 12:14:37');

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
(1, 1, 'serpentmeralco', 2147483647, 'serpentmaynilad', 2147483647, '', '');

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
(1, 'Serpent Lodging', 565, 1, 'Hongkong Disneyland'),
(2, 'Cable House', 565, 1, 'Hongkong Disneyland');

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
(1, 'gg', 800, 1, 1, '', '2024-12-16', 'true', ''),
(2, 'gg', 200, 1, 1, '', '2024-12-16', 'true', ''),
(3, 'gg', 200, 2, 1, '', '2024-12-17', 'true', '');

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
  `date_preferred` date DEFAULT NULL,
  `notification_sent_months` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `fname`, `mname`, `lname`, `contact`, `users_id`, `users_username`, `house_id`, `house_category`, `date_start`, `date_end`, `date_preferred`, `notification_sent_months`) VALUES
(1, 'john angelo', 'payo', 'junio', '', '105', 'aj', 1, 'studio', '2025-01-15', NULL, '2024-08-04', 4),
(2, 'romeo', 'juliet', 'gary', '', '10500', 'romeo', 1, 'studio', '2025-01-15', NULL, '2024-08-04', 4);

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
(105, 'aj', 'john angelo', 'payo', 'junio', '1234567', 'johnangelo.junio@gmail.com', 'user', '2025-01-15 04:58:21', '09398380417', NULL);

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
-- Indexes for table `eviction_setting`
--
ALTER TABLE `eviction_setting`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contract_images`
--
ALTER TABLE `contract_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `eviction_popup`
--
ALTER TABLE `eviction_popup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `eviction_setting`
--
ALTER TABLE `eviction_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `houseaccounts`
--
ALTER TABLE `houseaccounts`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paper_categories`
--
ALTER TABLE `paper_categories`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paper_files`
--
ALTER TABLE `paper_files`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `physical_contracts`
--
ALTER TABLE `physical_contracts`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

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
