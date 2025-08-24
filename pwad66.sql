-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 02:45 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwad66`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(6) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `photo`, `created_at`) VALUES
(2, 'araman', 'araman666@gmail.com', '$2y$10$Z79jCgKBjh1B6KRr0vz5AubA1Ac2Awt6o8gcHpHbBUcnS3zgrsCja', '1754732904-user.jpg', '2025-08-04 10:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sizes` varchar(200) DEFAULT NULL,
  `colors` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `product_id`, `sizes`, `colors`, `created_at`) VALUES
(5, 6, 'L,XL', '', '2025-08-06 09:45:34'),
(6, 10, '', '#b01c1c,#b3f4d3', '2025-08-10 09:52:13'),
(7, 11, 'L', '#000000,#ebe5e5', '2025-08-10 09:52:55'),
(8, 12, '', '#645e5e,#533df5', '2025-08-10 09:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(6) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`) VALUES
(1, 'Books', '2025-07-31'),
(2, 'Cloths', '2025-07-31'),
(3, 'Electronics', '2025-07-31'),
(4, 'Foods', '2025-07-31');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_type` enum('in','out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `change_type`, `quantity`, `remarks`, `created_at`) VALUES
(1, 6, 'in', 20, 'Denim T-Shirt', '2025-08-06 10:27:04'),
(2, 6, 'out', 50, 'Sold by Hasan', '2025-08-06 10:31:59'),
(3, 9, 'in', 5, '', '2025-08-06 10:33:25'),
(4, 9, 'in', 5, '', '2025-08-06 10:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `unit_price` int(8) NOT NULL,
  `selling_price` int(8) NOT NULL,
  `stock_amount` int(11) DEFAULT NULL,
  `has_attributes` tinyint(1) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `book_type` varchar(50) DEFAULT NULL,
  `virtual_file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `description`, `product_image`, `unit_price`, `selling_price`, `stock_amount`, `has_attributes`, `category_id`, `book_type`, `virtual_file`, `created_at`) VALUES
(6, 'T-Shirt', 'Denim T-Shirt', '1754473534_t-shirt.jpg', 500, 600, 70, 1, 2, NULL, NULL, '2025-08-06 09:45:34'),
(9, 'Laravel12 Tutorial', 'TaTa McGrow Hill', '1754473942_laravel12.jpg', 750, 1200, 60, 0, 1, 'downloadable', '1754473942_file-sample_150kB.pdf', '2025-08-06 09:52:22'),
(10, 'Three Pcs', 'lorem ipsum dolor sit ammet', '1754819533_threepcs.jpg', 650, 1000, 50, 1, 2, NULL, NULL, '2025-08-10 09:52:13'),
(11, 'Trouser', 'lorem ipsum dololr sit ammet', '1754819575_trouser.jpg', 300, 450, 100, 1, 2, NULL, NULL, '2025-08-10 09:52:55'),
(12, 'Samsung Galaxy S25 Ultra', 'lorem ipsum dolor sit amet', '1754819655_phone.jpg', 100000, 115000, 20, 1, 3, NULL, NULL, '2025-08-10 09:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(150) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `reset_token` varchar(150) NOT NULL,
  `reset_expires` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attributes`
--
ALTER TABLE `attributes`
  ADD CONSTRAINT `attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
