-- Veloura Jewels Database - Complete Version
-- Generated: 2026-04-09

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
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Table structure
-- --------------------------------------------------------

CREATE TABLE `categories` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `name` varchar(64) NOT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `email` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `nonce` varchar(255) DEFAULT NULL,
                         `nonce_expiry` datetime DEFAULT NULL,
                         `created` datetime DEFAULT NULL,
                         `modified` datetime DEFAULT NULL,
                         `role` varchar(255) NOT NULL DEFAULT 'customer',
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `products` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `name` varchar(64) NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price` decimal(9,2) NOT NULL,
                            `stock` int(11) NOT NULL DEFAULT 0,
                            `supplier_email` varchar(320) DEFAULT NULL,
                            `created` datetime DEFAULT NULL,
                            `modified` datetime DEFAULT NULL,
                            `description` TEXT DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categories_products` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `category_id` int(11) NOT NULL,
                                       `product_id` int(11) NOT NULL,
                                       PRIMARY KEY (`id`),
                                       KEY `category_id` (`category_id`),
                                       KEY `product_id` (`product_id`),
                                       CONSTRAINT `categories_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
                                       CONSTRAINT `categories_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `product_images` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `product_id` int(11) NOT NULL,
                                  `filename` varchar(4096) NOT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `product_id` (`product_id`),
                                  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `contact_submissions` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `contact_replies` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `contact_submission_id` int(11) NOT NULL,
                                   `subject` varchar(255) NOT NULL,
                                   `message` text NOT NULL,
                                   `sent_at` datetime DEFAULT current_timestamp(),
                                   `created` datetime DEFAULT current_timestamp(),
                                   `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                   PRIMARY KEY (`id`),
                                   KEY `fk_contact_replies_submission` (`contact_submission_id`),
                                   CONSTRAINT `fk_contact_replies_submission` FOREIGN KEY (`contact_submission_id`) REFERENCES `contact_submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

CREATE TABLE `orders` (
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
                          KEY `user_id` (`user_id`),
                          CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `order_items` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `order_id` int(11) NOT NULL,
                               `product_id` int(11) NOT NULL,
                               `product_name` varchar(255) NOT NULL,
                               `unit_price` decimal(10,2) NOT NULL,
                               `quantity` int(11) NOT NULL,
                               `subtotal` decimal(10,2) NOT NULL,
                               `created` datetime DEFAULT current_timestamp(),
                               `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                               PRIMARY KEY (`id`),
                               KEY `order_id` (`order_id`),
                               KEY `product_id` (`product_id`),
                               CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
                               CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Data: users
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `email`, `password`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                            (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                            (7, '11@1.com',       '$2y$12$kY4TdsMfdvtMKcnMBebwPeInMYY7Oa8xN6Gl5Kemi0YcO5jr/SPnq', NULL, NULL, '2026-03-24 04:05:20', '2026-03-24 04:05:20', 'admin');

-- --------------------------------------------------------
-- Data: contact_submissions
-- --------------------------------------------------------
INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `subject`, `message`, `captcha_passed`, `is_replied`, `created`, `modified`) VALUES
                                                                                                                                                              (1, 'Jialin', 'Wu',   'jialinwu.island@gmail.com', '',                      '1',                            0, 1, '2026-03-20 02:40:43', '2026-03-24 06:32:52'),
                                                                                                                                                              (3, '11',     '11',   '11@1.com',                  '',                      '1wassadsad',                   0, 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25'),
                                                                                                                                                              (4, 'Hey',    'Test', 'Testing@mail.com',           'This is a test subject','I want to test the subject',   0, 0, '2026-03-24 06:23:08', '2026-03-24 06:23:08');

-- --------------------------------------------------------
-- Data: contact_replies
-- --------------------------------------------------------
INSERT INTO `contact_replies` (`id`, `contact_submission_id`, `subject`, `message`, `sent_at`, `created`, `modified`) VALUES
                                                                                                                          (1, 1, 'Re: Your enquiry', 'Hi Jialin,', '2026-03-23 10:10:33', '2026-03-23 10:10:33', '2026-03-23 10:10:33'),
                                                                                                                          (2, 4, 'Re: Your enquiry', 'Hi Hey,',    '2026-03-24 06:30:18', '2026-03-24 06:30:19', '2026-03-24 06:30:19'),
                                                                                                                          (3, 1, 'Re: Your enquiry', 'test',       '2026-03-24 06:32:52', '2026-03-24 06:32:52', '2026-03-24 06:32:52');

-- --------------------------------------------------------
-- Data: categories
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`) VALUES
                                            (1, 'Rings'),
                                            (2, 'Necklaces'),
                                            (3, 'Earrings'),
                                            (4, 'Bracelets'),
                                            (5, 'Brooches');

-- --------------------------------------------------------
-- Data: products
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `purchase_price`, `sale_price`, `stock`, `supplier_email`, `created`, `modified`, `description`) VALUES
                                                                                                                                           (1,  'Diamond Solitaire Ring',    450.00, 1299.00,  8, 'supplier@luxgems.com.au',   NOW(), NOW(), 'A timeless classic featuring a 0.5ct round brilliant diamond set in 18k white gold. The six-prong setting maximises light reflection for exceptional brilliance. Comes with GIA certification. Available in sizes 5-9.'),
                                                                                                                                           (2,  'Rose Gold Twisted Band',     85.00,  249.00, 25, 'supplier@goldcraft.com.au', NOW(), NOW(), 'Elegantly twisted 14k rose gold band with a brushed finish. Comfortable everyday wear with a 2mm profile. Hypoallergenic and tarnish-resistant. Perfect as a stacking ring or standalone piece.'),
                                                                                                                                           (3,  'Emerald Halo Ring',         320.00,  890.00,  5, 'supplier@luxgems.com.au',   NOW(), NOW(), 'A stunning 0.8ct natural Colombian emerald encircled by 18 pave-set diamonds totalling 0.3ct. Set in 18k yellow gold with a split shank design. Each stone is hand-selected for colour and clarity.'),
                                                                                                                                           (4,  'Sapphire Three-Stone Ring', 380.00, 1050.00,  3, 'supplier@luxgems.com.au',   NOW(), NOW(), 'Inspired by the Royal Collection. Features a 1ct oval Ceylon sapphire flanked by two round diamonds (0.25ct each) in a platinum setting. Symbolising past, present and future.'),
                                                                                                                                           (5,  'Pearl Cocktail Ring',        60.00,  185.00, 18, 'supplier@pearlsea.com.au',  NOW(), NOW(), 'A bold statement piece featuring a 10mm freshwater pearl in a floral sterling silver setting with rhodium plating. The adjustable band fits sizes 6-9. Pairs beautifully with evening wear.'),
                                                                                                                                           (6,  'Diamond Tennis Necklace',   720.00, 1980.00,  4, 'supplier@luxgems.com.au',   NOW(), NOW(), '45cm sterling silver chain set with 3.5ct of brilliant-cut diamonds in a continuous line. Each stone is individually set in four-prong bezels for maximum security and sparkle. Lobster clasp closure.'),
                                                                                                                                           (7,  'Gold Lariat Necklace',      110.00,  320.00, 14, 'supplier@goldcraft.com.au', NOW(), NOW(), 'Minimalist 14k gold Y-necklace with a polished bar drop. 60cm adjustable chain with a sleek sliding mechanism. Versatile enough for both casual and formal occasions.'),
                                                                                                                                           (8,  'Amethyst Pendant Necklace',  75.00,  220.00, 20, 'supplier@gemstone.com.au',  NOW(), NOW(), 'Faceted 8mm oval amethyst pendant in a sterling silver bezel setting. 40cm box chain included. The deep violet hue of the stone is enhanced by the simple setting. February birthstone gift option.'),
                                                                                                                                           (9,  'Pearl Strand Necklace',     180.00,  520.00,  7, 'supplier@pearlsea.com.au',  NOW(), NOW(), '45cm single strand of 7-8mm Akoya cultured pearls with exceptional lustre. Knotted between each pearl for security and drape. Sterling silver ball clasp. Presented in a signature Veloura gift box.'),
                                                                                                                                           (10, 'Ruby Heart Pendant',        140.00,  410.00,  9, 'supplier@gemstone.com.au',  NOW(), NOW(), 'Heart-shaped 1.2ct natural ruby pendant in 18k rose gold. The vivid red stone symbolises passion and love. 40cm fine chain included. Certificate of authenticity provided.'),
                                                                                                                                           (11, 'Diamond Stud Earrings',     380.00, 1050.00, 12, 'supplier@luxgems.com.au',   NOW(), NOW(), 'Classic 0.5ct total weight diamond studs in 18k white gold four-prong settings. Push-back butterfly closures for security. GIA-certified stones with excellent cut grade. Suitable for pierced ears only.'),
                                                                                                                                           (12, 'Gold Hoop Earrings',         55.00,  160.00, 30, 'supplier@goldcraft.com.au', NOW(), NOW(), 'Lightweight 14k yellow gold huggie hoops with a 20mm diameter. Hinged click-in closure for easy wear. High-polish finish that catches the light. Comfortable for all-day wear.'),
                                                                                                                                           (13, 'Chandelier Drop Earrings',   95.00,  275.00, 11, 'supplier@gemstone.com.au',  NOW(), NOW(), 'Statement earrings featuring cascading tiers of aquamarine and white topaz in sterling silver. Total drop length 6cm. Fishhook ear wires. Perfect for weddings and formal events.'),
                                                                                                                                           (14, 'Pearl Drop Earrings',        65.00,  195.00, 16, 'supplier@pearlsea.com.au',  NOW(), NOW(), '8mm freshwater pearl drops suspended from sterling silver shepherd hooks. The pearls have a soft pink overtone and high lustre. Simple, elegant and suitable for everyday wear or special occasions.'),
                                                                                                                                           (15, 'Gold Tennis Bracelet',      530.00, 1480.00,  6, 'supplier@luxgems.com.au',   NOW(), NOW(), '18cm 18k white gold bracelet set with 4ct of brilliant-cut diamonds in a continuous line. Box clasp with safety catch. Each diamond is individually set for flexibility. Timeless investment piece.'),
                                                                                                                                           (16, 'Charm Bracelet',             70.00,  210.00, 22, 'supplier@goldcraft.com.au', NOW(), NOW(), 'Sterling silver belcher chain bracelet with five pre-set charms including a heart, star, moon, key and infinity symbol. 18cm with a lobster clasp. Additional charms can be added from our charm collection.'),
                                                                                                                                           (17, 'Amethyst Bangle',            90.00,  265.00, 13, 'supplier@gemstone.com.au',  NOW(), NOW(), 'Rigid 14k gold-plated brass bangle set with seven 4mm round amethysts. Internal diameter 6.5cm to fit most wrists. The gradient of violet stones adds a pop of colour to any outfit.'),
                                                                                                                                           (18, 'Pearl Wrap Bracelet',        55.00,  165.00, 19, 'supplier@pearlsea.com.au',  NOW(), NOW(), 'Three-strand freshwater pearl bracelet with sterling silver box clasp. Each strand features 5-6mm white pearls with a satin finish. 18cm length. Elegant yet understated.'),
                                                                                                                                           (19, 'Butterfly Brooch',           45.00,  135.00, 15, 'supplier@gemstone.com.au',  NOW(), NOW(), 'Intricate butterfly brooch in rhodium-plated sterling silver with pave-set cubic zirconia wings. 4cm wingspan. Double pin-back for secure fastening. A whimsical accent for jackets, scarves and bags.'),
                                                                                                                                           (20, 'Floral Gold Brooch',         60.00,  178.00, 10, 'supplier@goldcraft.com.au', NOW(), NOW(), 'Five-petal flower brooch in 14k gold-plated brass with a 6mm freshwater pearl centre. 3.5cm diameter. Single pin-back closure. Adds a touch of vintage glamour to lapels and evening wear.');

-- --------------------------------------------------------
-- Data: categories_products
-- --------------------------------------------------------
INSERT INTO `categories_products` (`id`, `category_id`, `product_id`) VALUES
                                                                          (1,  1, 1),  (2,  1, 2),  (3,  1, 3),  (4,  1, 4),  (5,  1, 5),
                                                                          (6,  2, 6),  (7,  2, 7),  (8,  2, 8),  (9,  2, 9),  (10, 2, 10),
                                                                          (11, 3, 11), (12, 3, 12), (13, 3, 13), (14, 3, 14),
                                                                          (15, 4, 15), (16, 4, 16), (17, 4, 17), (18, 4, 18),
                                                                          (19, 5, 19), (20, 5, 20);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
