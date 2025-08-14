-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 01:15 PM
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
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','error','success','login','logout','registration') DEFAULT 'info',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `message`, `type`, `created_at`) VALUES
(1, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:07:46'),
(2, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:08:00'),
(3, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:11:51'),
(4, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:23:35'),
(5, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:34:05'),
(6, NULL, 'Admin logout: admin', 'logout', '2025-08-13 06:35:49'),
(7, NULL, 'Admin logout: admin', 'logout', '2025-08-13 07:25:27'),
(8, NULL, 'Admin logout: admin', 'logout', '2025-08-13 07:26:51'),
(9, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:09:28'),
(10, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:09:45'),
(11, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:11:40'),
(12, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:26:45'),
(13, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:37:41'),
(14, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:46:27'),
(15, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:47:21'),
(16, NULL, 'Admin logout: admin', 'logout', '2025-08-13 08:55:53'),
(17, NULL, 'Admin logout: admin', 'logout', '2025-08-13 09:16:37'),
(18, NULL, 'Admin logout: admin', 'logout', '2025-08-13 09:31:16'),
(19, NULL, 'Admin logout: admin', 'logout', '2025-08-13 09:41:44'),
(20, NULL, 'Admin logout: admin', 'logout', '2025-08-13 10:00:45'),
(21, NULL, 'Admin logout: admin', 'logout', '2025-08-13 10:02:34'),
(22, NULL, 'Admin logout: admin', 'logout', '2025-08-13 10:37:36'),
(23, NULL, 'Admin logout: admin', 'logout', '2025-08-13 11:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','registrar','cashier') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'registrar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'registrar', '2025-08-13 05:34:21'),
(3, 'cashier', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cashier', '2025-08-13 05:34:21'),
(5, 'admin', '$2b$12$XD6ivV8Dgyxh4L4Q9h949.Dgaj/mbRStJ1.KYT4Pe3OLWLrgiTaI6', '', '2025-08-13 06:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `tuition_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `lab_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `misc_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `reference_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `status` enum('enrolled','dropped','completed') DEFAULT 'enrolled',
  `grade` varchar(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `name`, `email`, `course`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, '2023-5555', 'tina morant', 'morant@gmail.com', 'BSIT', '$2y$10$0Z18mSc6paDmmhIDgmjp2ujNtMM2lx2TMd2APdoneZCpurjdPv0oG', 'approved', '2025-08-13 06:44:12', '2025-08-13 07:25:24'),
(2, '2023-7777', 'kim domingo', 'domingo@gmail.com', 'BSIT', '$2y$10$6H66.oo7X7nTYMMk/i/Ate0JIlgKNh9GkME6fQ0ElJtF5ZgEZuc86', 'approved', '2025-08-13 06:44:40', '2025-08-13 07:25:22'),
(3, '2023-57508', 'neza dela cruz', 'neza@gmail.com', 'BSIT', '$2y$10$DSQ58uIPo2kgbhOxzzPBfOI/veeOz6AQSwLagX4bF5Ob1mbwry77O', 'approved', '2025-08-13 06:52:02', '2025-08-13 07:25:20'),
(5, '2023-9999', 'gen dayday', 'gendayday@gmail.com', 'BSIT', '$2y$10$3wzf8QsL7RGPjkC31FlzLuwTFwFiigixPyuf0CK4aqcA/9Gs1XHYS', 'rejected', '2025-08-13 06:57:01', '2025-08-13 07:25:04'),
(6, '2023-1414', 'shantel ivy', 'shantel@gmail.com', 'BSIT', '$2y$10$kFT0aekcA/j5k5.BlbHQZub9UN6mcfNIBfGwIVi5v5N2mbhFsmQNG', 'approved', '2025-08-13 06:58:52', '2025-08-13 07:24:59'),
(7, '2023-57897', 'wilyamday', 'wilyam@gmail.com', 'BSBA', '$2y$10$JSTaER7T327mXVro4q.RFOSE9sPSH7oqomiBCuEB15q6HydNznELe', 'approved', '2025-08-13 07:26:25', '2025-08-13 07:26:49'),
(8, '2039-553423', 'daday2', 'daydada@gmail.com', 'BSBA', '$2y$10$TQjWuFZ1CaYX4nOyF.NfW.RryLhIfoTQAkSGmHEarTJ88U0joMfLu', 'rejected', '2025-08-13 08:27:09', '2025-08-13 08:46:26'),
(9, '2023-57892', 'dayzxc', 'zxczxc@gmail.com', 'BSCS', '$2y$10$JB7g6ilFXfQfvzITKbqTz.5Ruvtks41PII8IXC1xgJ6yBAYtuBfU2', 'approved', '2025-08-13 08:33:57', '2025-08-13 08:46:23'),
(10, '2023-56383', 'modings', 'bieber@gmail.com', 'BSIT', '$2y$10$B6dMPDGelpGXw5Zd/oEudejGblprPjkiU6Vqtt2jHIXVRNpLaxCUC', 'approved', '2025-08-13 10:00:15', '2025-08-13 10:00:34'),
(11, '2020-123245', 'mod', 'bieberr@gmail.com', 'BSCS', '$2y$10$dCOFs2OZbVzwTT/3DWqKjuH3rpwDEATr0V/.OM3gumYWNH9yv45HK', 'rejected', '2025-08-13 10:54:43', '2025-08-13 11:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `units` int(11) NOT NULL,
  `department` varchar(50) NOT NULL,
  `year_level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `schedule` varchar(100) NOT NULL,
  `max_slots` int(11) NOT NULL DEFAULT 30,
  `available_slots` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `title`, `description`, `units`, `department`, `year_level`, `semester`, `instructor`, `schedule`, `max_slots`, `available_slots`, `is_active`, `created_at`) VALUES
(1, 'IT 302', 'Systems Integration and Architecture 1', NULL, 3, 'IT', 1, 0, 'Prof. Felices', 'MWF 8:00-9:00 AM', 40, 35, 1, '2025-08-13 05:34:39'),
(2, 'IT 301', 'Human Computer Interaction 2', NULL, 3, 'IT', 1, 0, 'Prof. Sunga', 'TTH 10:00-11:30 AM', 35, 28, 1, '2025-08-13 05:34:39'),
(3, 'IT 303', 'Networking 2', NULL, 3, 'IT', 2, 0, 'Prof. Nadala', 'MWF 1:00-2:00 PM', 30, 30, 1, '2025-08-13 05:34:39'),
(4, 'IT 304', 'Quantitative Method', NULL, 3, 'IT', 1, 0, 'Prof. Gagalac', 'TTH 2:00-3:30 PM', 35, 25, 1, '2025-08-13 05:34:39'),
(5, 'IT 306', 'Integrative Programming Technologies 2', NULL, 3, 'IT', 1, 0, 'Prof. Daguino', 'MWF 3:00-4:00 PM', 25, 20, 1, '2025-08-13 05:34:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`student_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
