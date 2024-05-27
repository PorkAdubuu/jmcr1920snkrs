-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2024 at 07:59 AM
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
-- Database: `1920snkrs`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`, `Description`, `CreatedAt`) VALUES
(1, 'Men', 'Men Originals', '2024-05-23 06:19:25'),
(2, 'Women', 'Women Originals', '2024-05-23 06:19:25'),
(3, 'Kids', 'Kiddo', '2024-05-23 06:20:49'),
(4, 'Sports', 'Sportswear', '2024-05-23 06:20:49'),
(5, 'Unisex', 'For men and women', '2024-05-23 06:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_id` int(11) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_id`, `prod_name`, `Description`, `Price`, `ImageURL`, `CreatedAt`) VALUES
(1, 'Sneaker 1', 'For casual and basketball ', '8999.00', 'assets/product/product-2.jpg', '2024-05-19 12:30:53'),
(5, 'Sneka 2', 'safasfg', '2999.00', 'assets/product/product-1.jpg', '2024-05-19 15:51:38'),
(6, 'Jordan', 'saf', '2422.00', 'assets/product/product-3.jpg', '2024-05-20 14:38:13'),
(7, 'prod4', 'asfa', '2512.00', 'assets/products/prod4.png', '2024-05-20 14:38:13'),
(8, 'prod5', 'sf', '2145.00', 'assets/products/prod5.png', '2024-05-20 14:41:15'),
(9, 'prod6', 'fg', '2512.00', 'assets/products/prod6.png', '2024-05-20 14:41:15'),
(10, 'prod7', 'sf', '2145.00', 'assets/products/prod2.png', '2024-05-20 14:43:07'),
(11, 'prod8', 'fg', '2512.00', 'assets/products/prod6.png', '2024-05-20 14:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `product_data`
--

CREATE TABLE `product_data` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `isNew` tinyint(1) DEFAULT 0,
  `onDiscount` tinyint(1) DEFAULT 0,
  `Discount` decimal(5,2) DEFAULT 0.00,
  `DiscountExpirationDate` date DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_data`
--

INSERT INTO `product_data` (`id`, `product_id`, `isNew`, `onDiscount`, `Discount`, `DiscountExpirationDate`, `CategoryID`) VALUES
(1124, 5, 1, 0, '0.00', NULL, 1),
(1245, 1, 1, 1, '20.00', '2024-05-25', 5),
(1246, 6, 1, 1, '30.00', '2024-05-29', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `keyPass` varchar(100) NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `keyPass`, `datecreated`) VALUES
(60431, 'quizquest1', 'botewod322@deligy.com', '$2y$10$09D8.t5YOQMJ4dKKVIotS.PnhQ68WzxPZz9HiU6pqpqolUF3JQYv6', '', '2024-05-06 03:29:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `product_data`
--
ALTER TABLE `product_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `FK_Category` (`CategoryID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_data`
--
ALTER TABLE `product_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1247;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60432;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_data`
--
ALTER TABLE `product_data`
  ADD CONSTRAINT `FK_Category` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  ADD CONSTRAINT `product_data_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`Product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
