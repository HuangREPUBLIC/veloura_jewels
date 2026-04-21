-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 21, 2026 at 04:06 AM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(64) NOT NULL,
    `type` varchar(20) NOT NULL DEFAULT 'jewelry',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
CREATE TABLE IF NOT EXISTS `categories_products` (
                                                     `id` int(11) NOT NULL AUTO_INCREMENT,
    `category_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `category_id` (`category_id`),
    KEY `product_id` (`product_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
                                                                          (21, 5, 16),
                                                                          (22, 4, 24),
                                                                          (23, 2, 25),
                                                                          (24, 5, 26),
                                                                          (25, 8, 27),
                                                                          (26, 10, 28),
                                                                          (27, 7, 29);

-- --------------------------------------------------------

--
-- Table structure for table `contact_replies`
--

DROP TABLE IF EXISTS `contact_replies`;
CREATE TABLE IF NOT EXISTS `contact_replies` (
                                                 `id` int(11) NOT NULL AUTO_INCREMENT,
    `contact_submission_id` int(11) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `sent_at` datetime DEFAULT current_timestamp(),
    `created` datetime DEFAULT current_timestamp(),
    `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `fk_contact_replies_submission` (`contact_submission_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
CREATE TABLE IF NOT EXISTS `contact_submissions` (
                                                     `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `email` varchar(255) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `captcha_passed` tinyint(1) NOT NULL DEFAULT 0,
    `is_replied` tinyint(1) NOT NULL DEFAULT 0,
    `created` datetime DEFAULT current_timestamp(),
    `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
CREATE TABLE IF NOT EXISTS `orders` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `stripe_session_id` varchar(255) DEFAULT NULL,
    `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
    `customer_email` varchar(255) DEFAULT NULL,
    `status` varchar(50) NOT NULL DEFAULT 'pending',
    `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
    `currency` varchar(10) NOT NULL DEFAULT 'aud',
    `created` datetime DEFAULT current_timestamp(),
    `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `stripe_session_id`, `stripe_payment_intent_id`, `customer_email`, `status`, `total_amount`, `currency`, `created`, `modified`) VALUES
                                                                                                                                                                           (1, 6, 'cs_test_a1l4DTBWnxrajgwUpy7k24NjtJeZ16AnRqievRfTEJf36ArHdVZULwt3xz', NULL, 'admin@test.com', 'pending', 350.00, 'aud', '2026-04-20 16:37:29', '2026-04-20 16:37:30'),
                                                                                                                                                                           (2, NULL, 'cs_test_a1gbuDPGowLYnBKhQN1Y9OruqENi12hJ5Po1dGTI1a7d6BtHs99HlcJttd', NULL, NULL, 'pending', 350.00, 'aud', '2026-04-20 16:44:00', '2026-04-20 16:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `variant_id` int(11) DEFAULT NULL,
    `product_name` varchar(255) NOT NULL,
    `selected_size` varchar(20) DEFAULT NULL,
    `unit_price` decimal(10,2) NOT NULL,
    `quantity` int(11) NOT NULL,
    `subtotal` decimal(10,2) NOT NULL,
    `created` datetime DEFAULT current_timestamp(),
    `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `product_id` (`product_id`),
    KEY `variant_id` (`variant_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `selected_size`, `unit_price`, `quantity`, `subtotal`, `created`, `modified`) VALUES
                                                                                                                                                                           (1, 1, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-20 16:37:29', '2026-04-20 16:37:29'),
                                                                                                                                                                           (2, 2, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-20 16:44:00', '2026-04-20 16:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(64) NOT NULL,
    `purchase_price` decimal(9,2) NOT NULL,
    `sale_price` decimal(9,2) NOT NULL,
    `supplier_email` varchar(320) DEFAULT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL,
    `description` text DEFAULT NULL,
    `story` text DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `purchase_price`, `sale_price`, `supplier_email`, `created`, `modified`, `description`, `story`) VALUES
                                                                                                                                           (1, 'Art Deco Ring', 50.00, 110.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-16 12:20:04', 'This ring is meticulously crafted for a perfect balance between bold structure and refined elegance. The design and stone setting ensure a unique brilliance. Inspired by Art Deco aesthetic, each piece embodies timeless sophistication.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nWidth: 0.5 cm\r\nColor: Silver', NULL),
                                                                                                                                           (2, 'Duo Luxe Two-Tone Ring', 85.00, 210.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-20 23:53:13', 'Material: Sterling silver with 18K gold recynil\r\nStones: Cubic zirconia\r\nDimensions: 6 mm width', NULL),
                                                                                                                                           (3, 'Infinity Crossover Ring', 40.00, 130.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-21 00:28:42', 'Material: 18K yellow gold vermeil and rhodium-plated white gold on sterling silver base, made from recycled materials\r\nStone: Pavé cubic zirconia\r\nDimensions: 7 mm width, comfort-fit band', NULL),
                                                                                                                                           (4, 'Chunky Ice Ring', 100.00, 300.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-14 01:38:58', 'Sterling silver intertwines with paths of brilliant stones to reflect the geometric patterns created by broken ice.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver', NULL),
                                                                                                                                           (5, 'Eternal Spark White Gold Ring', 30.00, 120.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-20 23:54:01', 'Material: Rhodium plated on premium white alloy, made from recycled\r\nStones: Cubic zirconia\r\nBand Width: 3 mm', NULL),
                                                                                                                                           (6, 'Éclat Lariat Necklace', 70.00, 189.96, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-20 23:57:50', 'Material: 18K yellow gold vermeil on sterling silver, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 45 cm chain length, 32 mm drop pendant length', NULL),
                                                                                                                                           (7, 'Art Deco Pavé Choker', 300.00, 780.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:28:26', 'This choker is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: Over 210 white cubic zirconia\r\nColor: Silver\r\nTotal chain length: 40 cm ; Width 0.8 cm', NULL),
                                                                                                                                           (8, 'Eternal Dawn Necklace', 60.00, 210.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-20 21:01:31', 'Material: 14k White Gold plated on 925 Sterling Silver\r\nStone: High-grade teardrop Cubic Zirconia\r\nDimensions: 320 mm length, 5 mm width (pendant)\r\n', NULL),
                                                                                                                                           (9, 'Lumière Choker', 180.00, 620.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-20 23:50:33', 'This minimalist and versatile choker perfectly captures the essence of spring.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver', NULL),
                                                                                                                                           (10, 'Blush Halo Pendant Necklace', 140.00, 460.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-20 23:52:38', 'Material: 18K rose gold vermeil on sterling silver, made from recycled materials Stone: Lab-created pink sapphire and cubic zirconia Dimensions: 45 cm chain length, 18 mm pendant width', NULL),
                                                                                                                                           (11, 'Celeste Drop Earrings', 100.00, 380.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-21 00:18:09', 'Material: Rhodium-plated white gold vermeil on sterling silver, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 42 mm length, 12 mm width', NULL),
                                                                                                                                           (12, 'Aurelia Twist Hoops', 55.00, 185.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-21 00:13:54', 'Material: 18K yellow gold vermeil on sterling silver, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 28 mm diameter, 6 mm width', NULL),
                                                                                                                                           (13, 'Luna Twist Hoops', 95.00, 275.00, 'supplier@gemstone.com.au', '2026-04-13 11:29:33', '2026-04-21 00:49:31', 'Material: Rhodium-plated white gold vermeil on sterling silver base, made from recycled materials\r\nStone: Pavé cubic zirconia\r\nDimensions: 24 mm diameter, 5 mm width', NULL),
                                                                                                                                           (14, 'Cascading Drop Earrings', 75.00, 280.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-04-20 23:11:26', 'Material: Platinum and rhodium plated on patented white alloy, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 39 mm length, 20 mm width', NULL),
                                                                                                                                           (15, 'Éclat Lariat Bracelet', 50.00, 180.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-04-21 00:08:35', 'Material: 18K yellow gold vermeil on sterling silver, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 45 cm chain length, 32 mm drop pendant length', NULL),
                                                                                                                                           (16, 'Étoile Bloom Brooch', 90.00, 320.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-21 00:22:08', 'Material: Rhodium-plated white gold vermeil on sterling silver base, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 48 mm length, 32 mm width', NULL),
                                                                                                                                           (24, 'Rhodium Tennis Bracelet', 90.00, 300.00, 'supplier@gemstone.com.au', '2026-04-20 23:08:12', '2026-04-20 23:08:12', 'Material: Rhodium plated on premium white alloy, made from recycled materials\r\nStone: Swarovski crystals\r\nDimensions: 18cm length, 6cm width', NULL),
                                                                                                                                           (25, 'Elegant Silver Necklace', 85.00, 215.00, 'supplier@gemstone.com.au', '2026-04-20 23:17:07', '2026-04-20 23:17:07', 'Material: Rhodium plated on premium white alloy, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 45 cm chain length, 18 mm pendant width', NULL),
                                                                                                                                           (26, 'Fleur Pastel Bloom Brooch', 68.00, 220.00, 'supplier@gemstone.com.au', '2026-04-21 00:42:02', '2026-04-21 00:44:51', 'Material: Rose gold vermeil on sterling silver base, made from recycled materials\r\nStone: Lab-created morganite, lavender quartz, peridot, and freshwater pearl accents\r\nDimensions: 46 mm length, 34 mm width', NULL),
                                                                                                                                           (27, 'Éloise Bouclé Cushion', 80.00, 240.00, 'supplier@lifestyle.com.au', '2026-04-21 04:18:36', '2026-04-21 04:22:19', 'Material: Premium bouclé fabric cover with soft microfiber cushion insert\r\nColor: Soft ivory cream\r\nDimensions: 50 cm x 50 cm\r\nStyle: Japandi, Minimalist, Alpine Chic\r\nFeatures: Textured boucle finish, plush comfort filling, removable cover with hidden zipper, perfect for living rooms, bedrooms, and lounge styling', 'a'),
                                                                                                                                           (28, 'Sorelle Fringe Throw', 60.00, 260.00, 'supplier@homedecor.com.au', '2026-04-21 04:20:51', '2026-04-21 04:22:07', 'Material: Premium wool-blend fabric with soft-touch finish\r\nColor: Soft taupe grey\r\nDimensions: 130 cm x 170 cm\r\nStyle: Japandi, Minimalist, Alpine Chic\r\nFeatures: Lightweight yet warm, textured weave, hand-finished fringe edges, breathable and suitable for year-round use', 'a'),
                                                                                                                                           (29, 'Lumière Ceramic Vase', 120.00, 400.00, 'supplier@homedecor.com.au', '2026-04-21 04:21:58', '2026-04-21 04:21:58', 'Material: High-fired ceramic with matte speckled glaze\r\nColor: Warm off-white with natural speckle detailing\r\nDimensions: 24 cm height, 18 cm diameter\r\nStyle: Japandi, Minimalist, Contemporary\r\nFeatures: Hand-finished texture, watertight interior, durable ceramic construction, ideal for dried or fresh arrangements as well as standalone décor', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `filename` varchar(4096) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`) VALUES
                                                                  (21, 1, 'art_deco_ring.png'),
                                                                  (27, 7, 'art_deco_pave___choker.png'),
                                                                  (29, 9, 'lumiere-pave-choker.png'),
                                                                  (41, 4, 'chunky_rce_ring.png'),
                                                                  (47, 8, 'necklace1.png'),
                                                                  (51, 24, 'bracelet2.png'),
                                                                  (52, 14, 'earrings1.png'),
                                                                  (54, 25, 'necklaces3.png'),
                                                                  (55, 5, 'rings2.png'),
                                                                  (56, 2, 'rings3.png'),
                                                                  (57, 10, 'necklaces2.png'),
                                                                  (58, 6, 'necklaces4.png'),
                                                                  (63, 15, 'bracelet3.png'),
                                                                  (64, 12, 'earrings2.png'),
                                                                  (65, 11, 'earrings3.png'),
                                                                  (66, 16, 'brooch1.png'),
                                                                  (67, 3, 'rings4.png'),
                                                                  (72, 26, 'brooch2.png'),
                                                                  (74, 13, 'earrings4.png'),
                                                                  (75, 13, 'earrings4.png'),
                                                                  (76, 27, 'eloise_boucle_cushion.png'),
                                                                  (77, 28, 'sorelle_ringe_throw.png'),
                                                                  (78, 29, 'lumiere_ceramic_vase.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE IF NOT EXISTS `product_variants` (
                                                  `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `size` varchar(20) NOT NULL,
    `stock` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
                                                                         (35, 15, 'Size 6', 6),
                                                                         (36, 16, 'One Size', 22),
                                                                         (44, 24, 'Size 5', 25),
                                                                         (45, 24, 'Size 6', 30),
                                                                         (46, 24, 'Size 7', 30),
                                                                         (47, 24, 'Size 8', 26),
                                                                         (48, 24, 'Size 9', 10),
                                                                         (49, 25, 'One Size', 10),
                                                                         (50, 15, 'Size 7', 4),
                                                                         (51, 15, 'Size 8', 2),
                                                                         (52, 15, 'Size 9', 5),
                                                                         (53, 26, 'One Size', 6),
                                                                         (54, 27, 'One Size', 5),
                                                                         (55, 28, 'One Size', 3),
                                                                         (56, 29, 'One Size', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
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
    `role` varchar(255) NOT NULL DEFAULT 'customer',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                                                                           (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', 'Admin', 'Test', NULL, NULL, NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                                                                           (9, 'customer@gmail.com', '$2y$12$dpFy6UEE5lMm7GcYFM9KHewxxP6lHS1UUxqlARKrLqYpgupBvZAPq', 'Customer', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:19:40', '2026-04-16 13:19:40', 'customer'),
                                                                                                                                                           (10, 'staff@gmail.com', '$2y$12$7UeOSrqwNufTAgpnq0aFCuR0MqwCn1EUSN.GtTMrkePbB/kErewh6', 'Staff', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:20:17', '2026-04-16 13:20:17', 'staff');

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
