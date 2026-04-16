-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 01:21 PM
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

--
-- Database: `veloura_jewels`
--
CREATE DATABASE IF NOT EXISTS `veloura_jewels` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `veloura_jewels`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
                              `id` int(11) NOT NULL,
                              `name` varchar(64) NOT NULL,
                              `type` varchar(20) NOT NULL DEFAULT 'jewelry'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`) VALUES
                                                    (1, 'Rings', 'jewelry'),
                                                    (2, 'Necklaces', 'jewelry'),
                                                    (3, 'Earrings', 'jewelry'),
                                                    (4, 'Bracelets', 'jewelry'),
                                                    (5, 'Brooches', 'jewelry'),
                                                    (6, 'Candles', 'home_decor'),
                                                    (7, 'Vases', 'home_decor'),
                                                    (8, 'Cushions', 'home_decor'),
                                                    (9, 'Wall Art', 'home_decor'),
                                                    (10, 'Throws', 'home_decor');

-- --------------------------------------------------------

--
-- Table structure for table `categories_products`
--

DROP TABLE IF EXISTS `categories_products`;
CREATE TABLE `categories_products` (
                                       `id` int(11) NOT NULL,
                                       `category_id` int(11) NOT NULL,
                                       `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories_products`
--

INSERT INTO `categories_products` (`id`, `category_id`, `product_id`) VALUES
                                                                          (1, 1, 1),
                                                                          (2, 1, 2),
                                                                          (3, 1, 3),
                                                                          (4, 1, 4),
                                                                          (5, 1, 5),
                                                                          (6, 2, 6),
                                                                          (7, 2, 7),
                                                                          (8, 2, 8),
                                                                          (9, 2, 9),
                                                                          (10, 2, 10),
                                                                          (11, 3, 11),
                                                                          (12, 3, 12),
                                                                          (13, 3, 13),
                                                                          (14, 3, 14),
                                                                          (15, 4, 15),
                                                                          (16, 4, 16),
                                                                          (17, 4, 17),
                                                                          (18, 4, 18),
                                                                          (19, 5, 19);

-- --------------------------------------------------------

--
-- Table structure for table `contact_replies`
--

DROP TABLE IF EXISTS `contact_replies`;
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
                                                                                                                          (3, 1, 'Re: Your enquiry', 'test', '2026-03-24 06:32:52', '2026-03-24 06:32:52', '2026-03-24 06:32:52'),
                                                                                                                          (4, 4, 'Re: This is a test subject', 'Hi Hey,', '2026-04-16 12:00:12', '2026-04-16 12:00:12', '2026-04-16 12:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

DROP TABLE IF EXISTS `contact_submissions`;
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
                                                                                                                                                              (4, 'Hey', 'Test', 'Testing@mail.com', 'This is a test subject', 'I want to test the subject', 0, 1, '2026-03-24 06:23:08', '2026-04-16 12:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
                          `id` int(11) NOT NULL,
                          `user_id` int(11) DEFAULT NULL,
                          `stripe_session_id` varchar(255) DEFAULT NULL,
                          `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
                          `customer_email` varchar(255) DEFAULT NULL,
                          `status` varchar(50) NOT NULL DEFAULT 'pending',
                          `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
                          `currency` varchar(10) NOT NULL DEFAULT 'aud',
                          `created` datetime DEFAULT current_timestamp(),
                          `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
                               `id` int(11) NOT NULL,
                               `order_id` int(11) NOT NULL,
                               `product_id` int(11) NOT NULL,
                               `variant_id` int(11) DEFAULT NULL,
                               `product_name` varchar(255) NOT NULL,
                               `selected_size` varchar(20) DEFAULT NULL,
                               `unit_price` decimal(10,2) NOT NULL,
                               `quantity` int(11) NOT NULL,
                               `subtotal` decimal(10,2) NOT NULL,
                               `created` datetime DEFAULT current_timestamp(),
                               `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
                            `id` int(11) NOT NULL,
                            `name` varchar(64) NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price` decimal(9,2) NOT NULL,
                            `supplier_email` varchar(320) DEFAULT NULL,
                            `created` datetime DEFAULT NULL,
                            `modified` datetime DEFAULT NULL,
                            `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `purchase_price`, `sale_price`, `supplier_email`, `created`, `modified`, `description`) VALUES
                                                                                                                                  (1, 'Art Deco Ring', 50.00, 110.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-16 12:20:04', 'This ring is meticulously crafted for a perfect balance between bold structure and refined elegance. The design and stone setting ensure a unique brilliance. Inspired by Art Deco aesthetic, each piece embodies timeless sophistication.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nWidth: 0.5 cm\r\nColor: Silver'),
                                                                                                                                  (2, 'Statement Torsade Pavé Ring', 85.00, 210.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:26:17', 'This ring embodies elegance and versatility. Its radiant shine echoes pure light, creating a captivating sparkle that draws attention.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated, anti-tarnishing and hypoallergenic\r\nStone: White cubic zirconia\r\nWidth: 0.9 cm\r\nColor: Yellow'),
                                                                                                                                  (3, 'Dainty Rose Gold Ring', 30.00, 120.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:26:33', 'This ring is made with Alloy and 18k rose gold plated. It is microset with white cubic zirconia.\r\nThe APM Alloy is made of 95% recycled material. It is anti-tarnishing and Hypoallergenic.\r\nMaterial: Alloy made of 95% recycled material and 18k rose gold plated, white cubic zirconia, anti-tarnishing and Hypoallergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold'),
                                                                                                                                  (4, 'Chunky Ice Ring', 100.00, 300.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:38:58', 'Sterling silver intertwines with paths of brilliant stones to reflect the geometric patterns created by broken ice.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (5, 'LOVE Morse Code Ring', 30.00, 120.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-14 01:27:20', 'This collection is using Morse code as a secret Love language.\r\nThis ring is embellished with the code LOVE on it, and the word LOVE is also engraved inside.\r\nMaterial: APM Rose Alloy made of 95% recycled material and 18k gold plated (3 microns), white cubic zirconia, anti-tarnishing and anti-allergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold'),
                                                                                                                                  (6, 'Art Deco Adjustable Necklace', 70.00, 230.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:28:09', 'This necklace is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nPendant size: Length 4.6 cm ; Width 0.7 cm\r\nTotal chain length: Adjustable to 48 cm maximum with sliding clasp'),
                                                                                                                                  (7, 'Art Deco Pavé Choker', 300.00, 780.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:28:26', 'This choker is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: Over 210 white cubic zirconia\r\nColor: Silver\r\nTotal chain length: 40 cm ; Width 0.8 cm'),
                                                                                                                                  (8, 'Lilac Torsade Adjustable Necklace', 60.00, 210.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-14 01:29:56', 'This necklace embodies elegance and versatility.\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: Purple cubic zirconia\r\nColor: Yellow\r\nTotal chain length: Adjustable to 65 cm maximum with sliding clasp'),
                                                                                                                                  (9, 'Lumière Pavé Choker', 180.00, 620.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-14 01:30:20', 'This minimalist and versatile choker perfectly captures the essence of spring.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (10, 'Maille Marine Chain Necklace', 140.00, 410.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-14 01:30:35', 'Material: Rose Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nTotal chain length: 45 cm'),
                                                                                                                                  (11, 'Statement Art Deco Drop Earrings', 150.00, 430.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:38:02', 'These earrings are meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nLength: 7.1 cm ; Width : 2.2 cm'),
                                                                                                                                  (12, 'Torsade Pavé Hoop Earrings', 55.00, 210.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:37:09', 'These earrings embody elegance and versatility.\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: White cubic zirconia\r\nColor: Yellow\r\nLength: 2.1 cm ; Width : 0.7 cm'),
                                                                                                                                  (13, 'Dainty Rose Gold Hoop Earrings', 95.00, 275.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-14 01:36:32', 'Material: Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nSize: Small (Length: 2.8 cm ; Width : 0.5 cm)'),
                                                                                                                                  (14, 'Asymmetric Cross Earrings', 65.00, 240.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-14 01:36:10', 'Material: Platinum and rhodium plated on patented white alloy, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 39 mm length, 20 mm width'),
                                                                                                                                  (15, 'Art Deco Pavé Bracelet', 90.00, 350.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:35:53', 'This bracelet is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (16, 'Lilac Lumière Pavé Bracelet', 90.00, 300.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:32:10', 'Material: Platinum and rhodium plated on patented white alloy\r\nStone: Lilac cubic zirconia\r\nDimensions: 4 mm width'),
                                                                                                                                  (17, 'Maille Marine Chain Bracelet', 90.00, 265.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-14 01:31:37', 'Material: Rose Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold'),
                                                                                                                                  (18, 'Up and Down Bracelet', 150.00, 510.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-14 01:31:17', 'Material: Sterling silver, ruthenium\r\nStone: White cubic zirconia\r\nColor: Dark Grey\r\nClosure: Magnet Clasp'),
                                                                                                                                  (19, 'Flower Meadow Brooch', 20.00, 70.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-14 01:42:22', 'The Flower Meadow Brooch features beautiful cateye stones in flower-like shapes with scattered multi-colour crystals.\r\nRose gold-coloured plating\r\nNickel free, lead free, cadmium free');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
                                  `id` int(11) NOT NULL,
                                  `product_id` int(11) NOT NULL,
                                  `filename` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`) VALUES
                                                                  (21, 1, 'art_deco_ring.png'),
                                                                  (22, 2, 'statement_torsade_pave___ring.png'),
                                                                  (23, 3, 'dainty_rose_gold_ring.png'),
                                                                  (25, 5, 'LOVE_morse_code_ring.png'),
                                                                  (26, 6, 'art_deco_adjustable_necklace.png'),
                                                                  (27, 7, 'art_deco_pave___choker.png'),
                                                                  (28, 8, 'lilac_torsade_adjustable_necklace.png'),
                                                                  (29, 9, 'lumiere-pave-choker.png'),
                                                                  (30, 10, 'maille-marine-chain-necklace.png'),
                                                                  (33, 18, 'up-and-down-bracelet.png'),
                                                                  (34, 17, 'maille-marine-chain-bracelet.png'),
                                                                  (35, 16, 'lilac-lumiere-pave-bracelet.png'),
                                                                  (36, 15, 'art-deco-pave-bracelet.png'),
                                                                  (37, 14, 'asymmetric-cross-earrings.png'),
                                                                  (38, 13, 'dainty-rose-gold-hoop-earrings.png'),
                                                                  (39, 12, 'torsade-hoop-earrings.png'),
                                                                  (40, 11, 'art-deco-statement-drop-earrings.png'),
                                                                  (41, 4, 'chunky_rce_ring.png'),
                                                                  (44, 19, 'flower_meadow_brooch.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
                                    `id` int(11) NOT NULL,
                                    `product_id` int(11) NOT NULL,
                                    `size` varchar(20) NOT NULL,
                                    `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `stock`) VALUES
                                                                         (1, 1, 'Size 5', 6),
                                                                         (2, 1, 'Size 6', 6),
                                                                         (3, 1, 'Size 7', 8),
                                                                         (4, 1, 'Size 8', 8),
                                                                         (5, 1, 'Size 9', 10),
                                                                         (6, 2, 'Size 5', 5),
                                                                         (7, 2, 'Size 6', 5),
                                                                         (8, 2, 'Size 7', 5),
                                                                         (9, 2, 'Size 8', 5),
                                                                         (10, 2, 'Size 9', 5),
                                                                         (11, 3, 'Size 5', 1),
                                                                         (12, 3, 'Size 6', 1),
                                                                         (13, 3, 'Size 7', 1),
                                                                         (14, 3, 'Size 8', 1),
                                                                         (15, 3, 'Size 9', 1),
                                                                         (16, 4, 'Size 5', 1),
                                                                         (17, 4, 'Size 6', 1),
                                                                         (18, 4, 'Size 7', 1),
                                                                         (19, 4, 'Size 8', 0),
                                                                         (20, 4, 'Size 9', 0),
                                                                         (21, 5, 'Size 5', 4),
                                                                         (22, 5, 'Size 6', 4),
                                                                         (23, 5, 'Size 7', 4),
                                                                         (24, 5, 'Size 8', 3),
                                                                         (25, 5, 'Size 9', 3),
                                                                         (26, 6, 'One Size', 4),
                                                                         (27, 7, 'One Size', 14),
                                                                         (28, 8, 'One Size', 20),
                                                                         (29, 9, 'One Size', 7),
                                                                         (30, 10, 'One Size', 9),
                                                                         (31, 11, 'One Size', 12),
                                                                         (32, 12, 'One Size', 30),
                                                                         (33, 13, 'One Size', 11),
                                                                         (34, 14, 'One Size', 16),
                                                                         (35, 15, 'One Size', 6),
                                                                         (36, 16, 'One Size', 22),
                                                                         (37, 17, 'One Size', 13),
                                                                         (38, 18, 'One Size', 19),
                                                                         (39, 19, 'One Size', 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `email` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `first_name` varchar(100) DEFAULT NULL,
                         `last_name` varchar(100) DEFAULT NULL,
                         `phone` varchar(20) DEFAULT NULL,
                         `address` varchar(500) DEFAULT NULL,
                         `nonce` varchar(255) DEFAULT NULL,
                         `nonce_expiry` datetime DEFAULT NULL,
                         `created` datetime DEFAULT NULL,
                         `modified` datetime DEFAULT NULL,
                         `role` varchar(255) NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                                                                           (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', 'Admin', 'Test', NULL, NULL, NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                                                                           (9, 'cutsomer@gmail.com', '$2y$12$dpFy6UEE5lMm7GcYFM9KHewxxP6lHS1UUxqlARKrLqYpgupBvZAPq', 'Customer', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:19:40', '2026-04-16 13:19:40', 'customer'),
                                                                                                                                                           (10, 'staff@gmail.com', '$2y$12$7UeOSrqwNufTAgpnq0aFCuR0MqwCn1EUSN.GtTMrkePbB/kErewh6', 'Staff', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:20:17', '2026-04-16 13:20:17', 'staff');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
    ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

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
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
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
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories_products`
--
ALTER TABLE `categories_products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contact_replies`
--
ALTER TABLE `contact_replies`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories_products`
--
ALTER TABLE `categories_products`
    ADD CONSTRAINT `categories_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `categories_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_replies`
--
ALTER TABLE `contact_replies`
    ADD CONSTRAINT `fk_contact_replies_submission` FOREIGN KEY (`contact_submission_id`) REFERENCES `contact_submissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
    ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
    ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
    ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
    ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
