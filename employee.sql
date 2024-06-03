-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 04:48 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `employee`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(11) NOT NULL,
  `pin_code` int(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `mobile`, `password`, `location`, `pin_code`, `created_at`) VALUES
(1, 'SANDEEP', 'SINGH', 'sandeep_2007032@gita.edu.in', '6299323290', '$2y$10$cnweeKC3VHugVOhepYaWrudIJpasC4NOGOjuMFaeoOwfUjgPvS8z6', 'buxar', 802101, '2024-06-03 07:17:31'),
(2, 'SANDEEP kumar', 'SINGH', 'singhsandeepkumar008@gmail.com', '6299435686', '$2y$10$LAyk1DUxD7sOCHsvDsGVOe2rVJLiN/f0PxMRMyPoDoDduNKZg.OrS', 'patna', 802101, '2024-06-03 07:21:02'),
(3, 'Sandeep Kumar', 'Singh', 'singhsandeepkumar0081@gmail.com', '6299435686', '$2y$10$zNH0B3qgq99sZ/HycHoN9OSaucvZPH0xlXgmFJo8IXn.pP/bG0t1u', '', 0, '2024-06-03 11:03:57'),
(4, 'SANDEEP', 'skyss', 'sky43811@gmaiaql.com', '6299435686', '$2y$10$Wba.5BkBmRcaQYfpOu/wp.V6..zgmwiDX93r4laPqLrBZbDWQ7R0i', 'buxaxaarrr', 802101, '2024-06-03 11:25:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
