-- Veloura Jewels Database
-- Updated: product_images ON DELETE CASCADE, categories type field

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `veloura_jewels` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `veloura_jewels`;

DROP TABLE IF EXISTS `categories_products`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `contact_replies`;
DROP TABLE IF EXISTS `contact_submissions`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `categories` (
                              `id`   int(11)     NOT NULL AUTO_INCREMENT,
                              `name` varchar(64) NOT NULL,
                              `type` varchar(20) NOT NULL DEFAULT 'jewelry',
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=6;

CREATE TABLE `users` (
                         `id`           int(11)      NOT NULL AUTO_INCREMENT,
                         `email`        varchar(255) NOT NULL,
                         `password`     varchar(255) NOT NULL,
                         `first_name`   varchar(100) DEFAULT NULL,
                         `last_name`    varchar(100) DEFAULT NULL,
                         `phone`        varchar(20)  DEFAULT NULL,
                         `address`      varchar(500) DEFAULT NULL,
                         `nonce`        varchar(255) DEFAULT NULL,
                         `nonce_expiry` datetime     DEFAULT NULL,
                         `created`      datetime     DEFAULT NULL,
                         `modified`     datetime     DEFAULT NULL,
                         `role`         varchar(255) NOT NULL DEFAULT 'customer',
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci AUTO_INCREMENT=9;

CREATE TABLE `products` (
                            `id`             int(11)      NOT NULL AUTO_INCREMENT,
                            `name`           varchar(64)  NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price`     decimal(9,2) NOT NULL,
                            `supplier_email` varchar(320) DEFAULT NULL,
                            `created`        datetime     DEFAULT NULL,
                            `modified`       datetime     DEFAULT NULL,
                            `description`    TEXT         DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=24;

CREATE TABLE `product_variants` (
                                    `id`         int(11)     NOT NULL AUTO_INCREMENT,
                                    `product_id` int(11)     NOT NULL,
                                    `size`       varchar(20) NOT NULL,
                                    `stock`      int(11)     NOT NULL DEFAULT 0,
                                    PRIMARY KEY (`id`),
                                    KEY `product_id` (`product_id`),
                                    CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=44;

CREATE TABLE `categories_products` (
                                       `id`          int(11) NOT NULL AUTO_INCREMENT,
                                       `category_id` int(11) NOT NULL,
                                       `product_id`  int(11) NOT NULL,
                                       PRIMARY KEY (`id`),
                                       KEY `category_id` (`category_id`),
                                       KEY `product_id` (`product_id`),
                                       CONSTRAINT `categories_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
                                       CONSTRAINT `categories_products_ibfk_2` FOREIGN KEY (`product_id`)  REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=21;

CREATE TABLE `product_images` (
                                  `id`         int(11)       NOT NULL AUTO_INCREMENT,
                                  `product_id` int(11)       NOT NULL,
                                  `filename`   varchar(4096) NOT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `product_id` (`product_id`),
                                  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=46;

CREATE TABLE `contact_submissions` (
                                       `id`             int(11)      NOT NULL AUTO_INCREMENT,
                                       `first_name`     varchar(50)  NOT NULL,
                                       `last_name`      varchar(50)  NOT NULL,
                                       `email`          varchar(255) NOT NULL,
                                       `subject`        varchar(255) NOT NULL,
                                       `message`        text         NOT NULL,
                                       `captcha_passed` tinyint(1)   NOT NULL DEFAULT 0,
                                       `is_replied`     tinyint(1)   NOT NULL DEFAULT 0,
                                       `created`        datetime     DEFAULT current_timestamp(),
                                       `modified`       datetime     DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                       PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci AUTO_INCREMENT=5;

CREATE TABLE `contact_replies` (
                                   `id`                    int(11)      NOT NULL AUTO_INCREMENT,
                                   `contact_submission_id` int(11)      NOT NULL,
                                   `subject`               varchar(255) NOT NULL,
                                   `message`               text         NOT NULL,
                                   `sent_at`               datetime     DEFAULT current_timestamp(),
                                   `created`               datetime     DEFAULT current_timestamp(),
                                   `modified`              datetime     DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                   PRIMARY KEY (`id`),
                                   KEY `fk_contact_replies_submission` (`contact_submission_id`),
                                   CONSTRAINT `fk_contact_replies_submission` FOREIGN KEY (`contact_submission_id`) REFERENCES `contact_submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci AUTO_INCREMENT=4;

CREATE TABLE `orders` (
                          `id`                       int(11)       NOT NULL AUTO_INCREMENT,
                          `user_id`                  int(11)       DEFAULT NULL,
                          `stripe_session_id`        varchar(255)  DEFAULT NULL,
                          `stripe_payment_intent_id` varchar(255)  DEFAULT NULL,
                          `customer_email`           varchar(255)  DEFAULT NULL,
                          `status`                   varchar(50)   NOT NULL DEFAULT 'pending',
                          `total_amount`             decimal(10,2) NOT NULL DEFAULT 0.00,
                          `currency`                 varchar(10)   NOT NULL DEFAULT 'aud',
                          `created`                  datetime      DEFAULT current_timestamp(),
                          `modified`                 datetime      DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                          PRIMARY KEY (`id`),
                          KEY `user_id` (`user_id`),
                          CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `order_items` (
                               `id`            int(11)       NOT NULL AUTO_INCREMENT,
                               `order_id`      int(11)       NOT NULL,
                               `product_id`    int(11)       NOT NULL,
                               `variant_id`    int(11)       DEFAULT NULL,
                               `product_name`  varchar(255)  NOT NULL,
                               `selected_size` varchar(20)   DEFAULT NULL,
                               `unit_price`    decimal(10,2) NOT NULL,
                               `quantity`      int(11)       NOT NULL,
                               `subtotal`      decimal(10,2) NOT NULL,
                               `created`       datetime      DEFAULT current_timestamp(),
                               `modified`      datetime      DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                               PRIMARY KEY (`id`),
                               KEY `order_id`   (`order_id`),
                               KEY `product_id` (`product_id`),
                               KEY `variant_id` (`variant_id`),
                               CONSTRAINT `fk_order_items_order`   FOREIGN KEY (`order_id`)   REFERENCES `orders` (`id`)           ON DELETE CASCADE,
                               CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)         ON DELETE RESTRICT,
                               CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Data: categories
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `type`) VALUES
                                                    (1, 'Rings',     'jewelry'),
                                                    (2, 'Necklaces', 'jewelry'),
                                                    (3, 'Earrings',  'jewelry'),
                                                    (4, 'Bracelets', 'jewelry'),
                                                    (5, 'Brooches',  'jewelry');

-- --------------------------------------------------------
-- Data: users
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                                                                           (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                                                                           (7, '11@1.com',       '$2y$12$kY4TdsMfdvtMKcnMBebwPeInMYY7Oa8xN6Gl5Kemi0YcO5jr/SPnq', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-24 04:05:20', '2026-03-24 04:05:20', 'admin'),
                                                                                                                                                           (8, '22@2.com',       '$2y$12$HVY249.r40bMDIkKUxhfT.KfrsOXCuxrVjQKBCPeHbWgkO0bLsYeW', '11', '11', NULL, NULL, NULL, NULL, '2026-04-13 21:43:57', '2026-04-13 21:43:57', 'customer');

-- --------------------------------------------------------
-- Data: contact_submissions
-- --------------------------------------------------------
INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `subject`, `message`, `captcha_passed`, `is_replied`, `created`, `modified`) VALUES
                                                                                                                                                              (1, 'Jialin', 'Wu',   'jialinwu.island@gmail.com', '',                       '1',                          0, 1, '2026-03-20 02:40:43', '2026-03-24 06:32:52'),
                                                                                                                                                              (3, '11',     '11',   '11@1.com',                  '',                       '1wassadsad',                 0, 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25'),
                                                                                                                                                              (4, 'Hey',    'Test', 'Testing@mail.com',           'This is a test subject', 'I want to test the subject', 0, 0, '2026-03-24 06:23:08', '2026-03-24 06:23:08');

-- --------------------------------------------------------
-- Data: contact_replies
-- --------------------------------------------------------
INSERT INTO `contact_replies` (`id`, `contact_submission_id`, `subject`, `message`, `sent_at`, `created`, `modified`) VALUES
                                                                                                                          (1, 1, 'Re: Your enquiry', 'Hi Jialin,', '2026-03-23 10:10:33', '2026-03-23 10:10:33', '2026-03-23 10:10:33'),
                                                                                                                          (2, 4, 'Re: Your enquiry', 'Hi Hey,',    '2026-03-24 06:30:18', '2026-03-24 06:30:19', '2026-03-24 06:30:19'),
                                                                                                                          (3, 1, 'Re: Your enquiry', 'test',       '2026-03-24 06:32:52', '2026-03-24 06:32:52', '2026-03-24 06:32:52');

-- --------------------------------------------------------
-- Data: products
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `purchase_price`, `sale_price`, `supplier_email`, `created`, `modified`, `description`) VALUES
                                                                                                                                  (1,  'Art Deco Ring',                       50.00,  110.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:24:28', 'This ring is meticulously crafted for a perfect balance between bold structure and refined elegance. The design and stone setting ensure a unique brilliance. Inspired by Art Deco aesthetic, each piece embodies timeless sophistication.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nWidth: 0.5 cm\r\nColor: Silver'),
                                                                                                                                  (2,  'Statement Torsade Pavé Ring',         85.00,  210.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:26:17', 'This ring embodies elegance and versatility. Its radiant shine echoes pure light, creating a captivating sparkle that draws attention.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated, anti-tarnishing and hypoallergenic\r\nStone: White cubic zirconia\r\nWidth: 0.9 cm\r\nColor: Yellow'),
                                                                                                                                  (3,  'Dainty Rose Gold Ring',               30.00,  120.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:26:33', 'This ring is made with Alloy and 18k rose gold plated. It is microset with white cubic zirconia.\r\nThe APM Alloy is made of 95% recycled material. It is anti-tarnishing and Hypoallergenic.\r\nMaterial: Alloy made of 95% recycled material and 18k rose gold plated, white cubic zirconia, anti-tarnishing and Hypoallergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold'),
                                                                                                                                  (4,  'Chunky Ice Ring',                    100.00,  300.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:38:58', 'Sterling silver intertwines with paths of brilliant stones to reflect the geometric patterns created by broken ice.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (5,  'LOVE Morse Code Ring',                30.00,  120.00, 'supplier@pearlsea.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:27:20', 'This collection is using Morse code as a secret Love language.\r\nThis ring is embellished with the code LOVE on it, and the word LOVE is also engraved inside.\r\nMaterial: APM Rose Alloy made of 95% recycled material and 18k gold plated (3 microns), white cubic zirconia, anti-tarnishing and anti-allergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold'),
                                                                                                                                  (6,  'Art Deco Adjustable Necklace',        70.00,  230.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:28:09', 'This necklace is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nPendant size: Length 4.6 cm ; Width 0.7 cm\r\nTotal chain length: Adjustable to 48 cm maximum with sliding clasp'),
                                                                                                                                  (7,  'Art Deco Pavé Choker',               300.00,  780.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:28:26', 'This choker is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: Over 210 white cubic zirconia\r\nColor: Silver\r\nTotal chain length: 40 cm ; Width 0.8 cm'),
                                                                                                                                  (8,  'Lilac Torsade Adjustable Necklace',   60.00,  210.00, 'supplier@gemstone.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:29:56', 'This necklace embodies elegance and versatility.\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: Purple cubic zirconia\r\nColor: Yellow\r\nTotal chain length: Adjustable to 65 cm maximum with sliding clasp'),
                                                                                                                                  (9,  'Lumière Pavé Choker',                180.00,  620.00, 'supplier@pearlsea.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:30:20', 'This minimalist and versatile choker perfectly captures the essence of spring.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (10, 'Maille Marine Chain Necklace',       140.00,  410.00, 'supplier@gemstone.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:30:35', 'Material: Rose Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nTotal chain length: 45 cm'),
                                                                                                                                  (11, 'Statement Art Deco Drop Earrings',   150.00,  430.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:38:02', 'These earrings are meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nLength: 7.1 cm ; Width : 2.2 cm'),
                                                                                                                                  (12, 'Torsade Pavé Hoop Earrings',         55.00,  210.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:37:09', 'These earrings embody elegance and versatility.\r\nMaterial: APM Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: White cubic zirconia\r\nColor: Yellow\r\nLength: 2.1 cm ; Width : 0.7 cm'),
                                                                                                                                  (13, 'Dainty Rose Gold Hoop Earrings',     95.00,  275.00, 'supplier@gemstone.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:36:32', 'Material: Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nSize: Small (Length: 2.8 cm ; Width : 0.5 cm)'),
                                                                                                                                  (14, 'Asymmetric Cross Earrings',          65.00,  240.00, 'supplier@pearlsea.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:36:10', 'Material: Platinum and rhodium plated on patented white alloy, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 39 mm length, 20 mm width'),
                                                                                                                                  (15, 'Art Deco Pavé Bracelet',             90.00,  350.00, 'supplier@luxgems.com.au',   '2026-04-13 11:29:33', '2026-04-14 01:35:53', 'This bracelet is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver'),
                                                                                                                                  (16, 'Lilac Lumière Pavé Bracelet',        90.00,  300.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:32:10', 'Material: Platinum and rhodium plated on patented white alloy\r\nStone: Lilac cubic zirconia\r\nDimensions: 4 mm width'),
                                                                                                                                  (17, 'Maille Marine Chain Bracelet',       90.00,  265.00, 'supplier@gemstone.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:31:37', 'Material: Rose Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold'),
                                                                                                                                  (18, 'Up and Down Bracelet',              150.00,  510.00, 'supplier@pearlsea.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:31:17', 'Material: Sterling silver, ruthenium\r\nStone: White cubic zirconia\r\nColor: Dark Grey\r\nClosure: Magnet Clasp'),
                                                                                                                                  (19, 'Flower Meadow Brooch',               20.00,   70.00, 'supplier@gemstone.com.au',  '2026-04-13 11:29:33', '2026-04-14 01:42:22', 'The Flower Meadow Brooch features beautiful cateye stones in flower-like shapes with scattered multi-colour crystals.\r\nRose gold-coloured plating\r\nNickel free, lead free, cadmium free'),
                                                                                                                                  (20, 'Swaying Crystal Leaf Brooch',        20.00,   70.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-04-14 01:30:53', 'Features leaf-like stones in deep navy blue, fine stone detailing, and articulated detail.\r\nSilver-coloured plating\r\nNickel free, lead free, cadmium free');

-- --------------------------------------------------------
-- Data: product_variants
-- --------------------------------------------------------
INSERT INTO `product_variants` (`id`, `product_id`, `size`, `stock`) VALUES
                                                                         (1,  1, 'Size 5', 2), (2,  1, 'Size 6', 2), (3,  1, 'Size 7', 2), (4,  1, 'Size 8', 1), (5,  1, 'Size 9', 1),
                                                                         (6,  2, 'Size 5', 5), (7,  2, 'Size 6', 5), (8,  2, 'Size 7', 5), (9,  2, 'Size 8', 5), (10, 2, 'Size 9', 5),
                                                                         (11, 3, 'Size 5', 1), (12, 3, 'Size 6', 1), (13, 3, 'Size 7', 1), (14, 3, 'Size 8', 1), (15, 3, 'Size 9', 1),
                                                                         (16, 4, 'Size 5', 1), (17, 4, 'Size 6', 1), (18, 4, 'Size 7', 1), (19, 4, 'Size 8', 0), (20, 4, 'Size 9', 0),
                                                                         (21, 5, 'Size 5', 4), (22, 5, 'Size 6', 4), (23, 5, 'Size 7', 4), (24, 5, 'Size 8', 3), (25, 5, 'Size 9', 3),
                                                                         (26, 6,  'One Size', 4),  (27, 7,  'One Size', 14), (28, 8,  'One Size', 20), (29, 9,  'One Size', 7),
                                                                         (30, 10, 'One Size', 9),  (31, 11, 'One Size', 12), (32, 12, 'One Size', 30), (33, 13, 'One Size', 11),
                                                                         (34, 14, 'One Size', 16), (35, 15, 'One Size', 6),  (36, 16, 'One Size', 22), (37, 17, 'One Size', 13),
                                                                         (38, 18, 'One Size', 19), (39, 19, 'One Size', 15), (40, 20, 'One Size', 10);

-- --------------------------------------------------------
-- Data: categories_products
-- --------------------------------------------------------
INSERT INTO `categories_products` (`id`, `category_id`, `product_id`) VALUES
                                                                          (1,  1, 1),  (2,  1, 2),  (3,  1, 3),  (4,  1, 4),  (5,  1, 5),
                                                                          (6,  2, 6),  (7,  2, 7),  (8,  2, 8),  (9,  2, 9),  (10, 2, 10),
                                                                          (11, 3, 11), (12, 3, 12), (13, 3, 13), (14, 3, 14),
                                                                          (15, 4, 15), (16, 4, 16), (17, 4, 17), (18, 4, 18),
                                                                          (19, 5, 19), (20, 5, 20);

-- --------------------------------------------------------
-- Data: product_images
-- --------------------------------------------------------
INSERT INTO `product_images` (`id`, `product_id`, `filename`) VALUES
                                                                  (21, 1,  'art_deco_ring.png'),
                                                                  (22, 2,  'statement_torsade_pave___ring.png'),
                                                                  (23, 3,  'dainty_rose_gold_ring.png'),
                                                                  (25, 5,  'LOVE_morse_code_ring.png'),
                                                                  (26, 6,  'art_deco_adjustable_necklace.png'),
                                                                  (27, 7,  'art_deco_pave___choker.png'),
                                                                  (28, 8,  'lilac_torsade_adjustable_necklace.png'),
                                                                  (29, 9,  'lumiere-pave-choker.png'),
                                                                  (30, 10, 'maille-marine-chain-necklace.png'),
                                                                  (31, 20, 'swaying_crystal_leaf_brooch.png'),
                                                                  (33, 18, 'up-and-down-bracelet.png'),
                                                                  (34, 17, 'maille-marine-chain-bracelet.png'),
                                                                  (35, 16, 'lilac-lumiere-pave-bracelet.png'),
                                                                  (36, 15, 'art-deco-pave-bracelet.png'),
                                                                  (37, 14, 'asymmetric-cross-earrings.png'),
                                                                  (38, 13, 'dainty-rose-gold-hoop-earrings.png'),
                                                                  (39, 12, 'torsade-hoop-earrings.png'),
                                                                  (40, 11, 'art-deco-statement-drop-earrings.png'),
                                                                  (41, 4,  'chunky_rce_ring.png'),
                                                                  (44, 19, 'flower_meadow_brooch.png');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
