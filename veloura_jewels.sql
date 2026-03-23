-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 23, 2026 at 09:23 PM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `veloura_jewels`
--

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
    (1, 1, 'Re: Your enquiry', 'Hi Jialin,', '2026-03-23 10:10:33', '2026-03-23 10:10:33', '2026-03-23 10:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
                                       `id` int(11) NOT NULL,
                                       `first_name` varchar(50) NOT NULL,
                                       `last_name` varchar(50) NOT NULL,
                                       `email` varchar(255) NOT NULL,
                                       `message` text NOT NULL,
                                       `captcha_passed` tinyint(1) NOT NULL DEFAULT 0,
                                       `created` datetime DEFAULT current_timestamp(),
                                       `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `message`, `captcha_passed`, `created`, `modified`) VALUES
                                                                                                                                     (1, 'Jialin', 'Wu', 'jialinwu.island@gmail.com', '1', 0, '2026-03-20 02:40:43', '2026-03-20 02:40:43'),
                                                                                                                                     (3, '11', '11', '11@1.com', '1wassadsad', 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
                            `id` int(11) NOT NULL,
                            `name` varchar(64) NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price` decimal(9,2) NOT NULL,
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
                                                                                                            (1, 'test@example.com', '123456', NULL, NULL, '2026-03-23 13:31:14', '2026-03-23 13:31:14', 'admin'),
                                                                                                            (2, 'hmcc0010@student.monash.edu', '$2y$12$GRnZFtFO.MxJtQVI.f5VzObyTs7cDhlMNjAyw4rvJsZFZssyKvmzm', 'c4fa8dc0e6f7eccd3df377656c35f36970eb685878657aed7a5ae4410bda3a53e725c9970bba0ab63e243c411e3c06b9999577dcc0c81b435e436a28e1113436', '2026-03-30 06:51:01', '2026-03-23 02:46:29', '2026-03-23 06:51:01', 'admin'),
                                                                                                            (3, 'jwuu0179@student.monash.edu', '$2y$12$.6oasfs3NEu8vHt02YEiJexIK13ZWAFO.JE0wIxAtVo2w7Xigbliq', '6e6359045c2622e7308c9b46ea4f07387368910dccaa66efbfd1708e2b1bd9f8fc4b11e47f2288b78f79f46bbdcfca2b55dd8968c772a559c10b6ce3faa32b31', '2026-03-30 06:51:34', '2026-03-23 15:35:34', '2026-03-23 06:51:34', 'admin'),
                                                                                                            (5, '11@1.com', '$2y$12$VXqAq1UuVfnGuC2JqC56n.8R8tC0MV9vZWJmWokuNMq9gv6vATgBC', '25931c150d9e5bb47e4c280d20bd8e47f13534799873431f3584ef9a654442ff0c629de4f078ee91f0a47460b2215d55e127be27727ebf07db2a27f248f747b4', '2026-03-30 07:12:58', '2026-03-23 04:47:58', '2026-03-23 07:12:58', 'customer');

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
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
