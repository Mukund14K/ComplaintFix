-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: May 24, 2026 at 09:26 AM
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
-- Database: `complaintfix`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `description` text NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `status` enum('Pending','In Progress','Resolved') DEFAULT 'Pending',
  `created_at` date NOT NULL,
  `conclusion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `user_id`, `category`, `subject`, `description`, `location`, `priority`, `status`, `created_at`, `conclusion`) VALUES
(1, 4, 'Academic', 'Failure for proper correction', 'ofpsgsgsfhsfhdfh', NULL, 'High', 'Resolved', '2026-05-13', NULL),
(2, 6, 'Academic Affairs', NULL, 'Improper correction', '', 'High', 'In Progress', '2026-05-13', ''),
(3, 6, 'Examination & Results', NULL, 'Improper correction of papers', 'AIEM LHC4', 'High', 'Resolved', '2026-05-14', NULL),
(4, 7, 'Library Services', NULL, 'Books for object oriented programming not available', 'Library', 'Medium', 'Resolved', '2026-05-15', 'The book has been brought as per your needs'),
(5, 7, 'Academic Affairs', NULL, 'Late submission of examination form', 'Exam cell', 'High', 'Resolved', '2026-05-15', 'Your form will be accepted'),
(6, 6, 'Library Services', NULL, 'Book of economics not available', 'Library', 'Medium', 'Resolved', '2026-05-17', 'We have brought in your book ');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_updates`
--

CREATE TABLE `complaint_updates` (
  `update_id` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `update_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_updates`
--

INSERT INTO `complaint_updates` (`update_id`, `complaint_id`, `update_text`, `created_at`) VALUES
(1, 3, 'The complaint is currently being investigated and the respective teachers responsible for correction are called for interview.', '2026-05-14 12:01:32'),
(2, 4, 'Currently investigation is being done', '2026-05-15 17:18:45'),
(3, 5, 'Currently discussing about action to be taken', '2026-05-15 18:22:37'),
(4, 2, 'investigating', '2026-05-15 18:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(20) DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `student_id`, `phone_no`, `department`, `academic_year`, `created_at`, `role`) VALUES
(1, 'Johnny', 'student@example.com', '$2y$10$SuAyxTlMmFaUs6cvn7Jiq.yaSobDAKPOOBKVDtx4MfVj1o0SO2Isu', NULL, NULL, NULL, NULL, '2026-05-14 01:53:15', 'student'),
(3, 'Johnny', 'ab@gmail.com', '$2y$10$tkDVzAHPerjt/7I3AZhYruJ.EJaJa6BEwmlPvSs41M3Qr9iGA8eQC', NULL, NULL, NULL, NULL, '2026-05-14 01:59:35', 'student'),
(4, 'MK', 'hddhd@gmail.com', '$2y$10$bJz1Vucef0xOvtrWGGHI5.lGjtv7cyOBmbaviQeY8UxvwfRaeTUaO', NULL, NULL, NULL, NULL, '2026-05-14 02:02:11', 'student'),
(5, 'KK', 'new@gmail.com', '$2y$10$Vm3xh6.5mr.eApGv.lvolO8SzhNNnHL4.7e5uWNXo1KrSF8kjrKwG', NULL, NULL, NULL, NULL, '2026-05-14 02:51:08', 'student'),
(6, 'CJ', 'cj@gmail.com', '$2y$10$xElZaDZpQ/YZhZXePgMoaegnfjCAXcKvlpzze2Varw/KtTO85ATdS', NULL, NULL, NULL, NULL, '2026-05-14 03:06:17', 'admin'),
(7, 'Mukund', 'm@gmail.com', '$2y$10$h/Tzc19Ob1cKZwo5fA1qW.oXYRUIpRfCeY9eXPVIp.Q5jMj7tladO', NULL, NULL, NULL, NULL, '2026-05-15 22:45:23', 'student'),
(8, 'Mukund', 'new@college.edu', '$2y$10$XqWV.Yrzfb/.l/QfglWpCOh08Ox6mFBVC1bC4stsr0epOpRv3GVlm', NULL, NULL, NULL, NULL, '2026-05-24 00:21:25', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `complaint_updates`
--
ALTER TABLE `complaint_updates`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `complaint_id` (`complaint_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `complaint_updates`
--
ALTER TABLE `complaint_updates`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `complaint_updates`
--
ALTER TABLE `complaint_updates`
  ADD CONSTRAINT `complaint_updates_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`complaint_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
