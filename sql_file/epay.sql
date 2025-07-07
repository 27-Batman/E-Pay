-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 05:54 PM
-- Server version: 8.0.40
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epay`
--

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `login_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`id`, `email`, `login_time`, `ip_address`) VALUES
(1, 'admin@gmail.com', '2025-02-24 02:47:55', '::1'),
(2, 'anuragsing092@gmail.com', '2025-02-24 03:18:57', '::1'),
(3, 'guragaidipendra7@gmail.com', '2025-02-24 03:26:20', '::1'),
(4, 'admin@gmail.com', '2025-02-24 03:50:15', '::1'),
(5, 'admin@gmail.com', '2025-02-24 03:55:53', '::1'),
(6, 'admin@gmail.com', '2025-02-24 04:08:33', '::1'),
(7, 'admin@gmail.com', '2025-02-24 04:17:31', '::1'),
(8, 'admin@gmail.com', '2025-02-24 04:20:28', '::1'),
(9, 'admin@gmail.com', '2025-02-24 04:22:16', '::1'),
(10, 'test@gmail.com', '2025-02-24 05:33:25', '::1'),
(11, 'admin@gmail.com', '2025-02-24 05:34:27', '::1'),
(12, 'admin@gmail.com', '2025-02-24 05:37:45', '::1'),
(13, 'admin@gmail.com', '2025-02-24 05:37:56', '::1'),
(14, 'admin@gmail.com', '2025-02-24 05:45:30', '::1'),
(15, 'admin@gmail.com', '2025-02-24 05:47:32', '::1'),
(16, 'admin@gmail.com', '2025-02-24 05:49:02', '::1'),
(17, 'admin@gmail.com', '2025-02-24 05:58:00', '::1'),
(18, 'admin@gmail.com', '2025-02-24 06:08:14', '::1'),
(19, 'test@gmail.com', '2025-02-24 06:29:40', '::1'),
(20, 'admin@gmail.com', '2025-02-24 06:33:29', '::1'),
(21, 'test@gmail.com', '2025-02-24 06:35:51', '::1'),
(22, 'admin@gmail.com', '2025-02-24 06:37:10', '::1'),
(23, 'admin@gmail.com', '2025-02-24 06:38:24', '::1'),
(24, 'admin@gmail.com', '2025-02-24 07:41:20', '::1'),
(25, 'asnil@gmail.com', '2025-02-24 07:48:50', '::1'),
(26, 'admin@gmail.com', '2025-02-24 07:49:50', '::1'),
(27, 'admin@gmail.com', '2025-02-24 16:22:57', '::1'),
(28, 'admin@gmail.com', '2025-02-25 08:06:52', '::1'),
(29, 'ram@gmail.com', '2025-02-25 18:14:57', '::1'),
(30, 'dahal@gmail.com', '2025-02-26 02:14:48', '::1'),
(31, 'dahal@gmail.com', '2025-02-26 02:28:52', '::1'),
(32, 'dahal@gmail.com', '2025-02-26 02:31:50', '::1'),
(33, 'dahal@gmail.com', '2025-02-26 14:48:48', '::1'),
(34, 'admin@gmail.com', '2025-02-26 16:41:14', '::1'),
(35, 'guragaidipendra6@gmail.com', '2025-02-26 16:42:47', '::1'),
(36, 'admin@gmail.com', '2025-02-26 16:51:39', '::1'),
(37, 'admin@gmail.com', '2025-02-26 16:58:46', '::1'),
(38, 'ram@gmail.com', '2025-02-26 17:00:20', '::1'),
(39, 'admin@gmail.com', '2025-02-26 17:14:48', '::1'),
(40, 'hari@gmail.com', '2025-02-26 17:20:49', '::1'),
(41, 'admin@gmail.com', '2025-02-26 17:28:43', '::1'),
(42, 'ram@gmail.com', '2025-02-26 19:42:17', '::1'),
(43, 'ram@gmail.com', '2025-02-26 19:46:30', '::1'),
(44, 'ram@gmail.com', '2025-02-26 20:40:05', '::1'),
(45, 'admin@gmail.com', '2025-02-26 20:40:35', '::1'),
(46, 'admin@gmail.com', '2025-02-26 20:42:28', '::1'),
(47, 'aarush@gmail.com', '2025-02-26 20:48:29', '::1'),
(48, 'admin@gmail.com', '2025-02-26 20:51:23', '::1'),
(49, 'aarush@gmail.com', '2025-02-26 21:06:52', '::1'),
(50, 'aarush@gmail.com', '2025-02-26 21:08:20', '::1'),
(51, 'admin@gmail.com', '2025-02-26 21:19:59', '::1'),
(52, 'anisha@gmail.com', '2025-02-26 21:41:24', '::1'),
(53, 'anisha@gmail.com', '2025-02-26 23:14:11', '::1'),
(54, 'abc@gmail.com', '2025-02-27 01:32:36', '::1'),
(55, 'admin@gmail.com', '2025-02-27 01:32:54', '::1'),
(56, 'alok@gmail.com', '2025-02-27 01:52:58', '::1'),
(57, 'admin@gmail.com', '2025-02-27 01:53:31', '::1'),
(58, 'admin@gmail.com', '2025-02-27 02:29:57', '::1'),
(59, 'admin@gmail.com', '2025-02-27 03:23:21', '::1'),
(60, 'bhumi@gmail.com', '2025-02-27 03:24:36', '::1'),
(61, 'ramesh@gmail.com', '2025-02-27 03:29:30', '::1'),
(62, 'pagal@sunab.com', '2025-05-19 10:36:11', '::1'),
(63, 'admin@gmail.com', '2025-05-19 10:37:14', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `sc_no` varchar(255) NOT NULL,
  `bill_amt` decimal(10,2) NOT NULL,
  `rebate_fine` decimal(10,2) DEFAULT '0.00',
  `payable_amt` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `sc_no`, `bill_amt`, `rebate_fine`, `payable_amt`, `due_date`, `status`, `created_at`) VALUES
(1, '1', 100.00, 0.00, 100.00, '2025-02-26', 'paid', '2025-02-26 16:43:04'),
(5, '4', 100.00, 0.00, 100.00, '2025-02-26', 'pending', '2025-02-26 20:46:35'),
(7, '5', 100.00, 0.00, 100.00, '2025-02-26', 'pending', '2025-02-26 20:51:44'),
(11, '100', 100.00, 0.00, 100.00, '2025-02-27', 'pending', '2025-02-27 03:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` int NOT NULL,
  `sc_no` varchar(255) NOT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `sc_no`, `payment_date`, `amount`, `transaction_id`, `amount_paid`) VALUES
(1, '1', '2025-02-26 16:48:14', 100.00, 'TXN_1740588191_1', 0.00),
(2, '1', '2025-02-26 16:48:15', 100.00, 'TXN_1740588191_1', 0.00),
(3, '1', '2025-02-26 17:24:23', 100.00, 'TXN_1740590545_4', 0.00),
(4, '1', '2025-02-26 16:48:48', 100.00, 'TXN_1740588191_1', 0.00),
(5, '4', '2025-02-26 17:28:11', 100.00, 'TXN_1740590832_4', 0.00),
(6, '2', '2025-02-26 19:47:52', 20.00, 'TXN_1740599192_3', 0.00),
(7, '7', '2025-02-26 23:16:26', 30.00, 'TXN_1740611655_10', 0.00),
(8, '10', '2025-02-27 01:36:34', 100.00, 'TXN_1740619999_15', 0.00),
(10, '150', '2025-02-27 03:31:33', 100.00, 'TXN_1740627030_150', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `counter` int NOT NULL,
  `userid` varchar(255) NOT NULL,
  `snno` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `contact`, `counter`, `userid`, `snno`, `password`, `gender`, `created_at`) VALUES
(28, 'Anurag Singh', 'anuragsing@gmail.com', '9854218456', 120, '1', '1', '$2y$10$5kUi2h8ewBphOWbGhJKgnOYRfHgIXZJPbOxyzYqfADooszq/azRK2', 'Male', '2025-02-26 16:42:19'),
(29, 'Dipendra Guragain', 'guragaidipendra6@gmail.com', '1236985421', 120, '2', '5', '$2y$10$Xe82V2jY6qszdoDUeW4REev77h7LsMvxQkOD9QIPdP.Dt8Wl94dJK', 'Male', '2025-02-26 16:52:08'),
(30, 'Dipendra Guragain', 'ram@gmail.com', '0123456789', 120, '3', '2', '$2y$10$uNIJeGD5vFSEJ2qpac.Cuu5ZnwM0brNvO3888ONGvR7ysN/3mCghq', 'Male', '2025-02-26 17:00:02'),
(31, 'Hari Dahal', 'hari@gmail.com', '1245789630', 120, '4', '4', '$2y$10$pPtvUIm.mfh2VZh91NpAR.mtX5uZPwnPZKpaoqzC8aiII9xVFRt96', 'Male', '2025-02-26 17:20:13'),
(32, 'Aarush Guragain', 'aarush@gmail.com', '9869010332', 120, '5', '6', '$2y$10$ux0zPsw7wa3F1.1L5Kx5LuBqPuUJgGKGxRkjy2j4AdcOoGvDOMwsW', 'Other', '2025-02-26 20:47:52'),
(33, 'Anisha Ghimire', 'anisha@gmail.com', '9869010361', 384, '10', '7', '$2y$10$dxd8ZoJf8sNcscCyHawmZO88BJPY2.tcp.DubBkx7bB2k5XtLaYq2', 'Female', '2025-02-26 21:40:16'),
(34, 'Dipendra Guragain', 'abc@gmail.com', '7894561230', 391, '15', '10', '$2y$10$6LXu7kSa6PQg0l7MOgkHXeKHudF4.E7aJB2mo.3s0uEQi6zupYUX6', 'Female', '2025-02-27 01:32:10'),
(36, 'Bhumika', 'bhumi@gmail.com', '9848586855', 273, '100', '100', '$2y$10$Fs77cpB0PC9t.L/MqTjoju0IS/FeANdXK.bgjwSNpnkS.LgujFy8C', 'Female', '2025-02-27 03:24:16'),
(37, 'Sujan Sharma', 'sujian@gmail.com', '9848586878', 273, '120', '120', '$2y$10$MYAdAfNbpmyiPWbknBi2vub6Kaor63Q3OhzAUGjUq8gf5M4H9x7.y', 'Male', '2025-02-27 03:26:05'),
(38, 'Ramesh', 'ramesh@gmail.com', '9852365410', 299, '150', '150', '$2y$10$wWmkOd.56Ij40AAnq5UhOO19YClTbFIUsSoYeAWCxXzAiKy.eSpr2', 'Male', '2025-02-27 03:29:18'),
(39, 'Sunab', 'pagal@sunab.com', '9813453997', 243, '20232023', '202020', '$2y$10$3y2RU0p8iGGxLerI5yJc5OzCSsoNjuUak5fPTp66g4zSq.CYBLN4u', 'Male', '2025-05-19 10:35:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_sc_no` (`sc_no`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_history_sc_no` (`sc_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_snno` (`snno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_sc_no` FOREIGN KEY (`sc_no`) REFERENCES `users` (`snno`) ON DELETE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `fk_payment_history_sc_no` FOREIGN KEY (`sc_no`) REFERENCES `users` (`snno`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
