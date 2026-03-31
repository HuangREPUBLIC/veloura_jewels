-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2026 at 06:13 PM
-- Server version: 11.8.6-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `veloura_jewels` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `veloura_jewels`;

DROP TABLE IF EXISTS `categories_products`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `contact_replies`;
DROP TABLE IF EXISTS `contact_submissions`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;
-- --------------------------------------------------------

--
-- Table structure for table `categories`
--
CREATE TABLE `categories` (
                              `id` int(11) NOT NULL,
                              `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories_products`
--
CREATE TABLE `categories_products` (
                                       `id` int(11) NOT NULL,
                                       `category_id` int(11) NOT NULL,
                                       `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_replies`
--
CREATE TABLE `contact_replies` (
                                   `id` int(11) NOT NULL,
                                   `contact_submission_id` int(11) NOT NULL,
                                   `subject` varchar(255) NOT NULL,
                                   `message` text NOT NULL,
                                   `sent_at` datetime DEFAULT current_timestamp(),
                                   `created` datetime DEFAULT current_timestamp(),
                                   `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_replies`
--

INSERT INTO `contact_replies` (`id`, `contact_submission_id`, `subject`, `message`, `sent_at`, `created`, `modified`) VALUES
                                                                                                                          (1, 1, 'Re: Your enquiry', 'Hi Jialin,', '2026-03-23 10:10:33', '2026-03-23 10:10:33', '2026-03-23 10:10:33'),
                                                                                                                          (2, 4, 'Re: Your enquiry', 'Hi Hey,', '2026-03-24 06:30:18', '2026-03-24 06:30:19', '2026-03-24 06:30:19'),
                                                                                                                          (3, 1, 'Re: Your enquiry', 'test', '2026-03-24 06:32:52', '2026-03-24 06:32:52', '2026-03-24 06:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--
CREATE TABLE `contact_submissions` (
                                       `id` int(11) NOT NULL,
                                       `first_name` varchar(50) NOT NULL,
                                       `last_name` varchar(50) NOT NULL,
                                       `email` varchar(255) NOT NULL,
                                       `subject` varchar(255) NOT NULL,
                                       `message` text NOT NULL,
                                       `captcha_passed` tinyint(1) NOT NULL DEFAULT 0,
                                       `is_replied` tinyint(1) NOT NULL DEFAULT 0,
                                       `created` datetime DEFAULT current_timestamp(),
                                       `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `subject`, `message`, `captcha_passed`, `is_replied`, `created`, `modified`) VALUES
                                                                                                                                                              (1, 'Jialin', 'Wu', 'jialinwu.island@gmail.com', '', '1', 0, 1, '2026-03-20 02:40:43', '2026-03-24 06:32:52'),
                                                                                                                                                              (3, '11', '11', '11@1.com', '', '1wassadsad', 0, 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25'),
                                                                                                                                                              (4, 'Hey', 'Test', 'Testing@mail.com', 'This is a test subject', 'I want to test the subject', 0, 0, '2026-03-24 06:23:08', '2026-03-24 06:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
CREATE TABLE `products` (
                            `id` int(11) NOT NULL,
                            `name` varchar(64) NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price` decimal(9,2) NOT NULL,
                            `stock` int(11) NOT NULL DEFAULT 0,
                            `supplier_email` varchar(320) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--
CREATE TABLE `product_images` (
                                  `id` int(11) NOT NULL,
                                  `product_id` int(11) NOT NULL,
                                  `filename` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `email` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `nonce` varchar(255) DEFAULT NULL,
                         `nonce_expiry` datetime DEFAULT NULL,
                         `created` datetime DEFAULT NULL,
                         `modified` datetime DEFAULT NULL,
                         `role` varchar(255) NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                            (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                            (7, '11@1.com', '$2y$12$kY4TdsMfdvtMKcnMBebwPeInMYY7Oa8xN6Gl5Kemi0YcO5jr/SPnq', NULL, NULL, '2026-03-24 04:05:20', '2026-03-24 04:05:20', 'admin');

UPDATE users SET role = 'admin' WHERE id = 3;
UPDATE users SET role = 'full_time' WHERE id = 6;
UPDATE users SET role = 'part_time' WHERE id = 7;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories_products`
--
ALTER TABLE `categories_products`
    ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_replies`
--
ALTER TABLE `contact_replies`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_replies_submission` (`contact_submission_id`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
    ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

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
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories_products`
--
ALTER TABLE `categories_products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_replies`
--
ALTER TABLE `contact_replies`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories_products`
--
ALTER TABLE `categories_products`
    ADD CONSTRAINT `categories_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `categories_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `contact_replies`
--
ALTER TABLE `contact_replies`
    ADD CONSTRAINT `fk_contact_replies_submission` FOREIGN KEY (`contact_submission_id`) REFERENCES `contact_submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
    ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
