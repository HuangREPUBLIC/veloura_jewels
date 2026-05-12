-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 12, 2026 at 10:26 PM
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(64) NOT NULL,
    `type` varchar(20) NOT NULL DEFAULT 'jewelry',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
                                                    (10, 'Throws', 'home_decor'),
                                                    (11, 'Curtain', 'home_decor');

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
CREATE TABLE IF NOT EXISTS `cms_pages` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
    `slug` varchar(100) NOT NULL,
    `title` varchar(200) NOT NULL,
    `sort_order` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_slug` (`slug`)
    ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `slug`, `title`, `sort_order`) VALUES
                                                                  (1, 'global', 'Global / Footer', 0),
                                                                  (2, 'home', 'Homepage', 1),
                                                                  (3, 'faq', 'FAQ', 2),
                                                                  (4, 'jewelry', 'Jewellery', 3),
                                                                  (5, 'home_decor', 'Home Décor', 4),
                                                                  (6, 'story', 'Story', 5),
                                                                  (7, 'location', 'Location', 6),
                                                                  (8, 'contact', 'Contact', 7);

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
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_replies`
--

INSERT INTO `contact_replies` (`id`, `contact_submission_id`, `subject`, `message`, `sent_at`, `created`, `modified`) VALUES
                                                                                                                          (1, 1, 'Re: Your enquiry', 'Hi Jialin,', '2026-03-23 10:10:33', '2026-03-23 10:10:33', '2026-03-23 10:10:33'),
                                                                                                                          (2, 4, 'Re: Your enquiry', 'Hi Hey,', '2026-03-24 06:30:18', '2026-03-24 06:30:19', '2026-03-24 06:30:19'),
                                                                                                                          (3, 1, 'Re: Your enquiry', 'test', '2026-03-24 06:32:52', '2026-03-24 06:32:52', '2026-03-24 06:32:52'),
                                                                                                                          (4, 4, 'Re: This is a test subject', 'Hi Hey,', '2026-04-16 12:00:12', '2026-04-16 12:00:12', '2026-04-16 12:00:12'),
                                                                                                                          (5, 10, 'Re: kj k', 'Hi Hunter, hbub', '2026-05-07 12:49:12', '2026-05-07 12:49:12', '2026-05-07 12:49:12'),
                                                                                                                          (6, 6, 'Re: testing email', 'Hi Customer, no', '2026-05-07 13:18:46', '2026-05-07 13:18:46', '2026-05-07 13:18:46');

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
    ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `subject`, `message`, `captcha_passed`, `is_replied`, `created`, `modified`) VALUES
                                                                                                                                                              (1, 'Jialin', 'Wu', 'jialinwu.island@gmail.com', '', '1', 0, 1, '2026-03-20 02:40:43', '2026-03-24 06:32:52'),
                                                                                                                                                              (3, '11', '11', '11@1.com', '', '1wassadsad', 0, 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25'),
                                                                                                                                                              (4, 'Hey', 'Test', 'Testing@mail.com', 'This is a test subject', 'I want to test the subject', 0, 1, '2026-03-24 06:23:08', '2026-04-16 12:00:12'),
                                                                                                                                                              (5, 'Customer', 'Test', 'test1@u26s1141.iedev.org', 'testing email', 'TEST', 0, 0, '2026-04-22 00:40:01', '2026-04-22 00:40:01'),
                                                                                                                                                              (6, 'Customer', 'we', 'test1@u26s1141.iedev.org', 'testing email', 'test', 0, 1, '2026-04-22 00:47:12', '2026-05-07 13:18:46'),
                                                                                                                                                              (7, 'Customer', 'Test', 'test1@u26s1141.iedev.org', 'testing email', 'tesret', 0, 0, '2026-04-22 01:16:42', '2026-04-22 01:16:42'),
                                                                                                                                                              (8, 'Customer', 'Test', 'test1@u26s1141.iedev.org', 'testing email', 'wwww', 0, 0, '2026-04-22 01:22:27', '2026-04-22 01:22:27'),
                                                                                                                                                              (9, 'sami', 'Test', 'test1@u26s1141.iedev.org', 'devTesting', 'Testing if email works on dev version', 0, 0, '2026-04-22 01:27:19', '2026-04-22 01:27:19'),
                                                                                                                                                              (10, 'Hunter', 'McConnell', 'hmcc0010@student.monash.edu', 'kj k', 'kjn', 0, 1, '2026-05-07 12:47:45', '2026-05-07 12:49:12'),
                                                                                                                                                              (11, 'Customer', 'Test', 'customer@gmail.com', 'Hello ', 'MEssage', 0, 0, '2026-05-07 13:15:52', '2026-05-07 13:15:52'),
                                                                                                                                                              (12, 'Jialin', 'Wu', 'jialinwu.island@gmail.com', '1qwefghh', '12345', 0, 0, '2026-05-08 00:24:10', '2026-05-08 00:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `faq_items`
--

DROP TABLE IF EXISTS `faq_items`;
CREATE TABLE IF NOT EXISTS `faq_items` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
    `question` varchar(500) NOT NULL,
    `answer` text NOT NULL,
    `sort_order` int(11) NOT NULL DEFAULT 0,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `faq_items`
--

INSERT INTO `faq_items` (`id`, `question`, `answer`, `sort_order`, `is_active`, `created`, `modified`) VALUES
                                                                                                           (1, 'What types of products does Veloura Jewels offer?', 'Veloura Jewels offers handcrafted jewellery and home décor pieces designed to add elegance and individuality to your everyday life.', 1, 1, '2026-05-06 17:18:25', '2026-05-06 17:18:25'),
                                                                                                           (2, 'Are all Veloura Jewels products handmade?', 'Yes, our products are carefully handcrafted to create unique pieces with a personal and artistic touch.', 2, 1, '2026-05-06 17:18:25', '2026-05-06 17:18:25'),
                                                                                                           (3, 'How can I get in touch with Veloura Jewels?', 'You can contact us through the Contact page by submitting your name, email address, and message.', 3, 1, '2026-05-06 17:18:25', '2026-05-06 17:18:25'),
                                                                                                           (4, 'Where can I browse your collections?', 'You can explore our products through the Jewelry and Home Decor pages available on the website.', 4, 1, '2026-05-06 17:18:25', '2026-05-06 17:18:25'),
                                                                                                           (5, 'Can I enquire about a product before purchasing?', 'Yes, you are welcome to contact us if you would like more information about a product before making a purchase.', 5, 1, '2026-05-06 17:18:25', '2026-05-06 17:18:25');

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
    ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `stripe_session_id`, `stripe_payment_intent_id`, `customer_email`, `status`, `total_amount`, `currency`, `created`, `modified`) VALUES
                                                                                                                                                                           (1, 6, 'cs_test_a1l4DTBWnxrajgwUpy7k24NjtJeZ16AnRqievRfTEJf36ArHdVZULwt3xz', NULL, 'admin@test.com', 'paid', 350.00, 'aud', '2026-04-20 16:37:29', '2026-04-20 16:37:30'),
                                                                                                                                                                           (2, NULL, 'cs_test_a1gbuDPGowLYnBKhQN1Y9OruqENi12hJ5Po1dGTI1a7d6BtHs99HlcJttd', NULL, NULL, 'paid', 350.00, 'aud', '2026-04-20 16:44:00', '2026-04-20 16:44:01'),
                                                                                                                                                                           (3, 9, 'cs_test_a12fcQZo6hkBecnwREzjDPYP9fpvK04vzVLcIJ0TQcZi2aXNEh63GC2wU8', NULL, 'customer@gmail.com', 'paid', 350.00, 'aud', '2026-04-21 15:47:42', '2026-04-21 15:47:42'),
                                                                                                                                                                           (4, 9, 'cs_test_a1lwmJTr15l36y9ylqeGWW9w7T0dT7jALdCS9FqwgNpHyp88rB9ymhEYri', NULL, 'customer@gmail.com', 'paid', 350.00, 'aud', '2026-04-21 23:00:16', '2026-04-21 23:00:16'),
                                                                                                                                                                           (5, NULL, 'cs_test_a1StVwcss4jHslKJwI6c9WZ7A2YEqNzuzOuwaN42iz5EMLDQhpCIBnhLGi', NULL, NULL, 'paid', 780.00, 'aud', '2026-04-21 23:03:52', '2026-04-21 23:03:53'),
                                                                                                                                                                           (6, 6, 'cs_test_a1TgqMv8Anz1NN2L05p0OBHkyytoxeH9GFYMSAxPoOdOiRqaKQzmRdeCkS', NULL, 'admin@test.com', 'paid', 780.00, 'aud', '2026-04-21 23:04:13', '2026-04-21 23:04:13'),
                                                                                                                                                                           (7, NULL, 'cs_test_a14zmXyuekW92FhWM9ih7KhWtVTRQ4bE9lpteD6SiShxYKhhXFVpD21XlS', NULL, NULL, 'paid', 350.00, 'aud', '2026-05-04 12:15:14', '2026-05-04 12:15:15'),
                                                                                                                                                                           (8, NULL, 'cs_test_a1IM4Z0vJmYRMqmn15EsliMxWkl4zK1IljJepxZFbof2hmlpTFGusKMkWG', NULL, NULL, 'paid', 350.00, 'aud', '2026-05-04 12:15:46', '2026-05-04 12:15:47'),
                                                                                                                                                                           (9, NULL, 'cs_test_a1TOnt0PcwJDMNNQjrmNXpC8ZOm3LrNGsW8M2scewkjyJHFYLqB8pCWEI6', NULL, NULL, 'paid', 350.00, 'aud', '2026-05-04 12:15:47', '2026-05-04 12:15:47'),
                                                                                                                                                                           (10, NULL, 'cs_test_a1Iorxr5TnirjUa7Tp1xm1pCSCafr3O8aSi7kM06D4dm34VNxjIlpl6qne', NULL, NULL, 'paid', 80.00, 'aud', '2026-05-06 02:05:19', '2026-05-06 02:05:20'),
                                                                                                                                                                           (11, NULL, 'cs_test_a1B1Neqa9OlrHa22HUcEdc3kNf3ItX1LxQ9GpREO1Fmx2lQWG7EMyMaqGQ', NULL, NULL, 'pending', 350.00, 'aud', '2026-05-06 02:12:28', '2026-05-06 02:12:29'),
                                                                                                                                                                           (12, NULL, 'cs_test_a1jvw8JOib0tsrCNIT4QsKHHBF2sXNeERXfZ3nqaKlfIsTypSFl2MCCnOk', NULL, NULL, 'pending', 350.00, 'aud', '2026-05-06 02:13:30', '2026-05-06 02:13:31'),
                                                                                                                                                                           (13, NULL, 'cs_test_a1CtLwDNDriGZy6Gihk47M82AYiQQOKBcfvffaIeSXNImwjqyKTe20xGN1', NULL, NULL, 'pending', 350.00, 'aud', '2026-05-06 02:13:36', '2026-05-06 02:13:37'),
                                                                                                                                                                           (14, 6, 'cs_test_a1RtEmePfmCJmkWTrZo0zhKPisRtIJsTPqFOQrKtPDyu67QDDadmjfr9rH', NULL, 'admin@test.com', 'pending', 700.00, 'aud', '2026-05-06 02:14:14', '2026-05-06 02:14:14'),
                                                                                                                                                                           (15, NULL, 'cs_test_a19RlFfrx48wT0qa2u1Av6yN4K5A3KHBBxyzCGxs7f3i5hPVsPpSGTPnht', NULL, NULL, 'cancelled', 700.00, 'aud', '2026-05-06 02:16:46', '2026-05-06 02:18:09'),
                                                                                                                                                                           (16, 9, 'cs_test_a1gP6uQqmBgZh2NvHdz7Es6UcX4Zs4shqlcRPvTv6PMNzmI4vkjObUbrLd', NULL, 'customer@gmail.com', 'cancelled', 700.00, 'aud', '2026-05-06 02:18:33', '2026-05-06 02:18:39'),
                                                                                                                                                                           (17, 6, 'cs_test_a1hNho1neh42l1WF3nkPZJye97ggpfI6UAmr1PkF7A8r6kkTUQN5q4UODz', NULL, 'admin@test.com', 'cancelled', 350.00, 'aud', '2026-05-07 17:41:56', '2026-05-07 17:43:01');

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
    ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `selected_size`, `unit_price`, `quantity`, `subtotal`, `created`, `modified`) VALUES
                                                                                                                                                                           (1, 1, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-20 16:37:29', '2026-04-20 16:37:29'),
                                                                                                                                                                           (2, 2, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-20 16:44:00', '2026-04-20 16:44:00'),
                                                                                                                                                                           (3, 3, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-21 15:47:42', '2026-04-21 15:47:42'),
                                                                                                                                                                           (4, 4, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-04-21 23:00:16', '2026-04-21 23:00:16'),
                                                                                                                                                                           (5, 5, 7, 27, 'Art Deco Pavé Choker', 'One Size', 780.00, 1, 780.00, '2026-04-21 23:03:52', '2026-04-21 23:03:52'),
                                                                                                                                                                           (6, 6, 7, 27, 'Art Deco Pavé Choker', 'One Size', 780.00, 1, 780.00, '2026-04-21 23:04:13', '2026-04-21 23:04:13'),
                                                                                                                                                                           (7, 7, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-04 12:15:14', '2026-05-04 12:15:14'),
                                                                                                                                                                           (8, 8, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-04 12:15:46', '2026-05-04 12:15:46'),
                                                                                                                                                                           (9, 9, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-04 12:15:47', '2026-05-04 12:15:47'),
                                                                                                                                                                           (10, 10, 45, 72, 'Crimson Candle', 'One Size', 10.00, 8, 80.00, '2026-05-06 02:05:19', '2026-05-06 02:05:20'),
                                                                                                                                                                           (11, 11, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-06 02:12:28', '2026-05-06 02:12:28'),
                                                                                                                                                                           (12, 12, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-06 02:13:30', '2026-05-06 02:13:30'),
                                                                                                                                                                           (13, 13, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-06 02:13:36', '2026-05-06 02:13:36'),
                                                                                                                                                                           (14, 14, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 2, 700.00, '2026-05-06 02:14:14', '2026-05-06 02:14:14'),
                                                                                                                                                                           (15, 15, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 2, 700.00, '2026-05-06 02:16:46', '2026-05-06 02:16:46'),
                                                                                                                                                                           (16, 16, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 2, 700.00, '2026-05-06 02:18:33', '2026-05-06 02:18:33'),
                                                                                                                                                                           (17, 17, 15, 35, 'Art Deco Pavé Bracelet', 'One Size', 350.00, 1, 350.00, '2026-05-07 17:41:56', '2026-05-07 17:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
CREATE TABLE IF NOT EXISTS `page_content` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
    `page_id` int(11) NOT NULL,
    `content_key` varchar(100) NOT NULL,
    `content_value` text DEFAULT NULL,
    `label` varchar(200) NOT NULL,
    `content_type` enum('text','textarea','image') NOT NULL DEFAULT 'text',
    `sort_order` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_page_key` (`page_id`,`content_key`)
    ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `page_content`
--

INSERT INTO `page_content` (`id`, `page_id`, `content_key`, `content_value`, `label`, `content_type`, `sort_order`) VALUES
                                                                                                                        (2, 1, 'footer_tagline', 'Handcrafted jewellery & home décor, made with love in Brooksdale.', 'Footer Tagline', 'textarea', 2),
                                                                                                                        (3, 2, 'hero_label', 'Handcrafted with love', 'Hero Label', 'text', 1),
                                                                                                                        (4, 2, 'hero_heading', 'Jewels that tell\nyour story.', 'Hero Heading', 'textarea', 2),
                                                                                                                        (5, 2, 'hero_subtext', 'Artisan-made pieces, each shaped by hand to bring warmth and meaning into your everyday.', 'Hero Subtext', 'textarea', 3),
                                                                                                                        (6, 2, 'brand_story_tag', 'Our Journey', 'Brand Story Label', 'text', 4),
                                                                                                                        (7, 2, 'brand_story_body_1', 'Founded by Sarah Smith in Brooksdale, Veloura Jewels is dedicated to creating unique, handcrafted pieces that blend creativity and meaningful design. Every ring, necklace, and home accent is shaped with intention — to bring beauty and elegance into your home and wardrobe.', 'Brand Story (Paragraph 1)', 'textarea', 6),
                                                                                                                        (8, 2, 'brand_story_body_2', 'We believe that what you wear and what surrounds you should feel personal. That is why no two Veloura pieces are exactly alike.', 'Brand Story (Paragraph 2)', 'textarea', 7),
                                                                                                                        (9, 3, 'faq_heading', 'Frequently Asked Questions', 'Page Heading', 'text', 1),
                                                                                                                        (10, 3, 'faq_subtext', 'Find answers to common questions about Veloura Jewels, our products, and how to get in touch.', 'Page Subtext', 'textarea', 2),
                                                                                                                        (11, 4, 'hero_heading', 'Our Jewellery Collection', 'Page Heading', 'text', 1),
                                                                                                                        (12, 4, 'hero_subtext', 'Discover timeless pieces crafted to elevate every occasion.', 'Page Subtext', 'textarea', 2),
                                                                                                                        (13, 5, 'hero_heading', 'Our Home Decor Collection', 'Page Heading', 'text', 1),
                                                                                                                        (14, 5, 'hero_subtext', 'Discover timeless pieces crafted to elevate every occasion.', 'Page Subtext', 'textarea', 2),
                                                                                                                        (15, 6, 'page_heading', 'Our Story', 'Page Heading', 'text', 1),
                                                                                                                        (16, 6, 'body_1', 'Founded by Sarah Smith, Veloura Jewels began with a simple belief: the pieces we wear and the spaces we live in should tell a story.', 'Story (Paragraph 1)', 'textarea', 2),
                                                                                                                        (17, 6, 'body_2', 'What started as a passion for timeless jewellery soon evolved into a carefully curated collection of jewellery and home décor designed to bring elegance, warmth, and individuality into everyday life. Sarah created Veloura Jewels to offer more than beautiful products — she wanted to create meaningful pieces that help people celebrate moments, memories, and personal style.', 'Story (Paragraph 2)', 'textarea', 3),
                                                                                                                        (18, 7, 'page_heading', 'Visit Veloura', 'Page Heading', 'text', 1),
                                                                                                                        (20, 7, 'address', '88 Elizabeth Road, Melbourne VIC 3000', 'Address', 'text', 3),
                                                                                                                        (21, 7, 'phone', '123 456 7890', 'Phone', 'text', 4),
                                                                                                                        (22, 7, 'hours', '10:00AM – 6:00PM', 'Opening Hours', 'textarea', 5),
                                                                                                                        (23, 6, 'body_3', 'Inspired by modern sophistication and classic craftsmanship, Veloura Jewels blends refined design with a sense of comfort and authenticity. Every collection is selected with attention to detail, quality, and versatility, allowing customers to express themselves through both fashion and home styling.', 'Story (Paragraph 3)', 'textarea', 4),
                                                                                                                        (24, 6, 'body_4', 'Whether it\'s a delicate necklace worn every day, a statement ring for a special occasion, or décor that transforms a house into a home, Veloura Jewels is built around the idea that beauty should feel personal.', 'Story (Paragraph 4)', 'textarea', 5),
(25, 6, 'hero_sub', 'Founded by Sarah Smith — pieces crafted to carry meaning.', 'Hero Subtitle', 'text', 0),
(26, 7, 'store_image', 'store.png', 'Store Image', 'image', 0),
(27, 7, 'store_image_inside', 'storeInterior.png', 'Store Interior Image', 'image', 1),
(28, 2, 'brand_story_title', 'About\nVeloura Jewels', 'Brand Story Title', 'textarea', 5),
(29, 2, 'featured_jewellery_title', 'Every piece has a story.', 'Featured Jewellery Title', 'text', 8),
(30, 2, 'featured_jewellery_subtext', 'Trace the journey that made your jewellery one of a kind.', 'Featured Jewellery Subtext', 'textarea', 9),
(31, 2, 'featured_home_title', 'Make your space yours.', 'Featured Home Décor Title', 'text', 10),
(32, 2, 'featured_home_subtext', 'Handcrafted home accents designed to bring warmth and character to every room.', 'Featured Home Décor Subtext', 'textarea', 11),
(33, 2, 'contact_cta_label', 'We\'d love to hear from you', 'Contact CTA Label', 'text', 12),
                                                                                                                        (34, 2, 'contact_cta_title', 'Have a question or a special request?', 'Contact CTA Title', 'text', 13),
                                                                                                                        (35, 2, 'contact_cta_subtext', 'Our team is always happy to help — whether it\'s a custom order, a gift idea, or just a chat about what we make.', 'Contact CTA Subtext', 'textarea', 14),
(36, 8, 'contact_heading', 'Contact Veloura Jewels', 'Page Heading', 'text', 1),
(37, 8, 'contact_subtext', 'We\'d love to hear from you! Whether you have questions about our handcrafted jewellery, want to discuss a custom piece, or simply want to learn more about our story, feel free to reach out.', 'Page Subtext', 'textarea', 2);

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
    `featured` tinyint(1) NOT NULL DEFAULT 0,
    `category_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_products_category` (`category_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `purchase_price`, `sale_price`, `supplier_email`, `created`, `modified`, `description`, `story`, `featured`, `category_id`) VALUES
                                                                                                                                                                      (1, 'Art Deco Ring', 45.00, 115.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-11 16:51:40', 'This ring is meticulously crafted for a perfect balance between bold structure and refined elegance. The design and stone setting ensure a unique brilliance. Inspired by Art Deco aesthetic, each piece embodies timeless sophistication.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nWidth: 0.5 cm\r\nColor: Silver', 'In a precise atelier, the Art Deco Ring is crafted with sharp geometric lines, each facet carefully shaped and aligned by hand to reflect the elegance of the 1920s. The final setting is polished to enhance its symmetry and brilliance, resulting in a timeless statement piece.\r\n', 0, 1),
                                                                                                                                                                      (2, 'Statement Torsade Pavé Ring', 80.00, 215.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:10', 'This ring embodies elegance and versatility. Its radiant shine echoes pure light, creating a captivating sparkle that draws attention.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Yellow Alloy made of 95% recycled material and 18k yellow gold plated, anti-tarnishing and hypoallergenic\r\nStone: White cubic zirconia\r\nWidth: 0.9 cm\r\nColor: Yellow', 'In a meticulous atelier, the Statement Torsade Pavé Ring is formed by twisting fine metal into a bold spiral, then hand-setting pavé stones along its curves for continuous brilliance. The design is carefully polished to emphasize its sculptural form and luminous detail.\r\n', 1, 1),
                                                                                                                                                                      (3, 'Dainty Rose Gold Ring', 28.00, 115.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-07 13:17:22', 'This ring is made with Alloy and 18k rose gold plated. It is microset with white cubic zirconia.\r\nThe Alloy is made of 95% recycled material. It is anti-tarnishing and Hypoallergenic.\r\nMaterial: Alloy made of 95% recycled material and 18k rose gold plated, white cubic zirconia, anti-tarnishing and Hypoallergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold', 'In a refined workshop, the Dainty Rose Gold Ring is carefully shaped and polished to achieve a soft, warm glow that highlights its delicate form. Each piece is finished by hand to ensure a smooth, minimalist elegance designed for everyday wear.\r\n', 1, 1),
                                                                                                                                                                      (4, 'Chunky Ice Ring', 95.00, 289.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-04 21:21:51', 'Sterling silver intertwines with paths of brilliant stones to reflect the geometric patterns created by broken ice.\r\nAll our products are handcrafted in our ateliers\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver', 'In a bold atelier, the Chunky Ice Ring is cast with substantial volume and carefully sculpted edges, then polished to a high, glass-like shine for maximum impact. Each piece is finished by hand to enhance its crisp, modern brilliance and sculptural presence.\r\n', 1, 1),
                                                                                                                                                                      (5, 'LOVE Morse Code Ring', 28.00, 115.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:02', 'This collection is using Morse code as a secret Love language.\r\nThis ring is embellished with the code LOVE on it, and the word LOVE is also engraved inside.\r\nMaterial: Rose Alloy made of 95% recycled material and 18k gold plated (3 microns), white cubic zirconia, anti-tarnishing and anti-allergenic\r\nAll our products are handcrafted and microset by hand in our ateliers\r\nColor: Rose gold', 'In a precise atelier, the LOVE Morse Code Ring is crafted with tiny, carefully placed stones and metal beads that encode the word \"LOVE\" in subtle rhythmic patterning. Each ring is hand-finished to ensure the message is both discreet and beautifully refined in its minimalist design.\r\n', 1, 1),
                                                                                                                                                                      (6, 'Art Deco Adjustable Necklace', 65.00, 225.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-11 16:52:59', 'This necklace is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nPendant size: Length 4.6 cm ; Width 0.7 cm\r\nTotal chain length: Adjustable to 48 cm maximum with sliding clasp', 'In a precision-led atelier, the Art Deco Adjustable Necklace is crafted with clean geometric lines, each element carefully shaped and assembled by hand. The adjustable mechanism is seamlessly integrated, ensuring both versatility and refined elegance in a timeless design.\r\n', 0, 2),
                                                                                                                                                                      (7, 'Art Deco Pavé Choker', 285.00, 769.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 22:00:10', 'This choker is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: Over 210 white cubic zirconia\r\nColor: Silver\r\nTotal chain length: 40 cm ; Width 0.8 cm', 'In a meticulous atelier, the Art Deco Pavé Choker is crafted with precise geometric forms, each tiny stone hand-set to create a seamless ribbon of light. The piece is carefully assembled to sit elegantly at the neckline, capturing the bold symmetry of the Art Deco era.\r\n', 0, 2),
                                                                                                                                                                      (8, 'Lilac Torsade Adjustable Necklace', 55.00, 199.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:01', 'This necklace embodies elegance and versatility.\r\nMaterial: Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: Purple cubic zirconia\r\nColor: Yellow\r\nTotal chain length: Adjustable to 65 cm maximum with sliding clasp', 'In a refined atelier, the Lilac Torsade Adjustable Necklace is crafted by delicately twisting metal into a graceful spiral, then accented with soft lilac tones that catch the light. The adjustable design is seamlessly integrated, blending versatility with elegant, handcrafted detail.\r\n', 1, 2),
                                                                                                                                                                      (9, 'Lumière Pavé Choker', 175.00, 599.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:04', 'This minimalist and versatile choker perfectly captures the essence of spring.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver', 'In a precision-focused atelier, the Lumière Pavé Choker is meticulously assembled as artisans hand-set each stone to create a continuous band of brilliance. The finished piece is carefully polished to enhance its radiant glow, embodying refined craftsmanship and modern elegance.\r\n', 1, 2),
                                                                                                                                                                      (10, 'Maille Marine Chain Necklace', 135.00, 395.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:04', 'Material: Rose Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nTotal chain length: 45 cm', 'In a skilled workshop, the Maille Marine Chain Necklace is crafted by interlinking polished metal loops in a classic marine-inspired pattern, each connection carefully secured by hand. The piece is then refined to a smooth finish, highlighting its strength, rhythm, and understated elegance.\r\n', 1, 2),
                                                                                                                                                                      (11, 'Statement Art Deco Drop Earrings', 145.00, 419.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:09', 'These earrings are meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver\r\nLength: 7.1 cm ; Width : 2.2 cm', 'In a precision-driven atelier, the Statement Art Deco Drop Earrings take shape as artisans cut and assemble bold geometric elements, then hand-set each stone in crisp, symmetrical lines. The result is a striking pair that channels 1920s glamour through meticulous craftsmanship and modern refinement.', 1, 3),
                                                                                                                                                                      (12, 'Torsade Pavé Hoop Earrings', 52.00, 199.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 21:22:15', 'These earrings embody elegance and versatility.\r\nMaterial: Yellow Alloy made of 95% recycled material and 18k yellow gold plated\r\nStone: White cubic zirconia\r\nColor: Yellow\r\nLength: 2.1 cm ; Width : 0.7 cm', 'In a meticulous workshop, the Torsade Pavé Hoop Earrings are formed by twisting fine metal strands into an elegant spiral, then hand-setting each tiny stone along the curves for a seamless sparkle. The process blends structural precision with delicate craftsmanship, creating hoops that shimmer with every turn.\r\n', 1, 3),
                                                                                                                                                                      (13, 'Dainty Rose Gold Hoop Earrings', 90.00, 265.00, 'supplier@pearlsea.com.au', '2026-04-13 11:29:33', '2026-05-04 21:21:53', 'Material: Alloy made of 95% recycled material and 18k gold plated, white cubic zirconia\r\nColor: Rose gold\r\nSize: Small (Length: 2.8 cm ; Width : 0.5 cm)', 'In a refined workshop, the Dainty Rose Gold Hoop Earrings are carefully shaped from fine metal and polished to a soft, warm glow. Each pair is finished with precise detailing to achieve a lightweight, elegant design made for everyday wear.\r\n', 1, 3),
                                                                                                                                                                      (14, 'Asymmetric Cross Earrings', 62.00, 235.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-04 21:21:22', 'Material: Platinum and rhodium plated on patented white alloy, made from recycled materials\r\nStone: Cubic zirconia\r\nDimensions: 39 mm length, 20 mm width', 'In a contemporary atelier, the Asymmetric Cross Earrings are thoughtfully crafted with mismatched silhouettes, each piece shaped and balanced by hand for a modern edge. The design comes together through careful finishing, highlighting contrast and individuality in every detail.\r\n', 1, 3),
                                                                                                                                                                      (15, 'Art Deco Pavé Bracelet', 88.00, 345.00, 'supplier@luxgems.com.au', '2026-04-13 11:29:33', '2026-05-04 21:21:18', 'This bracelet is meticulously crafted for a perfect balance between bold structure and refined elegance.\r\nMaterial: Sterling silver\r\nStone: White cubic zirconia\r\nColor: Silver', 'Under the glow of a 1920s ballroom chandelier, the Art Deco pavé bracelet caught every flicker of light, each stone set like a secret carefully placed in time. It wasn\'t just jewelry—it was a quiet declaration of elegance that outlived the night and everyone in it.', 1, 4),
(16, 'Lilac Lumière Pavé Bracelet', 85.00, 289.00, 'supplier@goldcraft.com.au', '2026-04-13 11:29:33', '2026-05-04 21:21:58', 'Material: Platinum and rhodium plated on patented white alloy\r\nStone: Lilac cubic zirconia\r\nDimensions: 4 mm width', 'In a quiet atelier, the Lilac Lumière Pavé Bracelet is brought to life as artisans delicately set each lilac-hued stone to capture a soft, radiant glow. Every detail is carefully crafted to reflect light with a gentle shimmer, evoking elegance in every movement.\r\n', 1, 4),
(34, 'Waves Painting', 42.00, 109.00, 'supplier@artworks.com.au', '2026-04-21 12:58:01', '2026-05-04 23:42:22', 'Colour: Blue and Brown\r\nMaterial: Medium-density fibreboard (MDF), canvas and metal\r\nDimensions: 90cm (H) x 60cm (W) x 2.5cm (D)\r\nWeight: 3.1kg', 'A wave painting rises from the canvas as the artist layers sweeping strokes of deep blues and foaming whites, capturing the moment water gathers its strength. In its final form, the piece feels alive—an instant of motion frozen just before the sea breaks into thunder.', 1, 9),
(35, 'Golden Blue Heart Painting', 18.00, 49.00, 'supplier@artworks.com.au', '2026-04-21 13:03:00', '2026-05-04 23:41:18', 'Colour: Blue and Gold\r\nMaterials: Medium-density fibreboard (MDF), canvas and metal\r\nDimensions: 42cm (H), 32cm (W), 3cm(D)', 'A half-blue, half-gold heart painting comes to life as the artist blends cool serenity with radiant warmth, letting the two colors meet in a luminous seam that feels almost electric. In its final form, the heart becomes a quiet symbol of contrast—softness and brilliance held together in a single, glowing shape.', 1, 9),
(36, 'Flower Painting', 38.00, 89.00, 'supplier@artworks.com.au', '2026-04-21 13:07:44', '2026-05-04 23:40:13', 'Colour: Red, Yellow, Green and Blue\r\nMaterials: Fibreboard, canvas and metal\r\nDimensions: 55cm (H) x 75cm (W) x 2.5cm (D)', 'A painting of red and yellow flowers comes alive as the artist layers bold crimson petals against warm golden blooms, letting the colors spark like sunlight meeting flame. When the final brushstroke lands, the piece radiates a vibrant energy—an ode to nature at its most spirited.', 1, 9),
(37, 'Butterfly Landing Painting', 58.00, 139.00, 'supplier@artworks.com.au', '2026-04-21 13:14:02', '2026-05-11 16:39:16', 'Colour: Orange, Blue, Green, Yellow and Purple\r\nMaterials: Medium-density fibreboard (MDF), canvas and metal\r\nDimensions: 70cm (H) x 100cm (W) x 3cm (D)', 'A multi-coloured butterfly landing on an orange flower takes shape on the canvas as the artist layers shimmering blues, pinks, and golds across delicate wings, letting them glow against the warm burst of petals. In its final moment, the painting feels like a breath held in nature—a fleeting touch of beauty captured before the butterfly lifts away.', 1, 9),
(38, 'Simple Candle', 4.00, 12.00, 'supplier@candleco.com.au', '2026-04-21 13:18:47', '2026-05-11 15:54:33', 'Colour: White\r\nMaterial: Metal and Paraffin\r\nDimensions: 7.5cm (H) x 7.5cm (Dia.)', 'A simple candle in a metal bowl takes shape as molten wax is poured and left to cool, the soft flame later rising to cast warm light against the bowl\'s cool, reflective surface. In its finished form, it feels like a quiet moment captured warmth held gently inside something forged from fire.', 1, 6),
                                                                                                                                                                      (39, 'Cauldron Candle', 12.00, 29.00, 'supplier@candleco.com.au', '2026-04-21 13:24:31', '2026-05-11 15:56:59', 'Colour: Black and White\r\nMaterial: Paraffin and Stone\r\nDimensions: 10cm (H) x 7.5cm (Dia.)', 'A candle in a stone cauldron takes form as wax is slowly poured into the carved vessel, the rough stone holding the warmth like an ancient hearth. When the flame is finally lit, it glows against the rugged surface, turning the cauldron into a quiet well of fire and shadow.', 1, 6),
                                                                                                                                                                      (41, 'Linen Cushion', 10.00, 25.00, 'supplier@textilecraft.com.au', '2026-04-21 13:39:18', '2026-05-11 16:20:02', 'Colour: Red\r\nMaterial: Velvet and Cotton\r\nDimensions: 20cm (H), 20cm (W), 6cm (D)', 'A round red cushion comes to life as soft fabric is stretched over plush filling, its vibrant color giving the simple shape a warm, inviting presence. Once finished, it feels like a small circle of comfort—bold, bright, and ready to soften any space it touches.', 1, 8),
                                                                                                                                                                      (42, 'Striped Cushion', 18.00, 45.00, 'supplier@textilecraft.com.au', '2026-04-21 13:43:41', '2026-05-11 16:18:48', 'Colour: White\r\nMaterial: Polyester\r\nDimensions: 42cm (L) x 42cm (W)', 'A classic square white cushion comes together as crisp fabric is stitched around soft filling, its clean lines giving it a timeless, effortless elegance. Once finished, it becomes a quiet anchor in any room—simple, bright, and ready to soften the space with understated comfort.', 1, 8),
                                                                                                                                                                      (45, 'Crimson Candle', 9.00, 24.00, 'supplier@candleco.com.au', '2026-04-22 18:49:04', '2026-05-11 16:02:52', 'Colour: Crimson Red\r\nMaterial: Paraffin\r\nDimensions: 29cm (H), 9cm (D)', 'In a small artisanal studio, the crimson candle is hand-poured in layers, blending richly pigmented wax to achieve its deep, glowing red hue. Each candle is carefully cured and finished to ensure a clean burn and a bold, atmospheric presence.\r\n', 1, 6),
                                                                                                                                                                      (47, 'Jacquard Cushion', 30.00, 72.00, 'supplier@textilecraft.com.au', '2026-04-22 18:55:02', '2026-05-11 16:18:24', 'Colur: Orange\r\nMaterial: Polyester fabric\r\nDimensions: 60cm (H), 48cm (W)', 'In a carefully curated textile studio, the cushion is assembled by layering rich orange fabrics with bold mosaic-inspired prints, each panel precisely cut and aligned for visual rhythm. It is then hand-finished to ensure a balanced contrast between vibrant pattern and smooth, solid tones for a modern, expressive accent piece.\r\n', 1, 8),
                                                                                                                                                                      (49, 'Floral Jacquard Throw', 38.00, 95.00, 'supplier@textilecraft.com.au', '2026-05-11 16:23:50', '2026-05-11 16:23:50', 'Colour: Ivory and blush\r\nMaterial: Woven jacquard fabric, polyester blend\r\nDimensions: 150cm (W) x 200cm (L)\r\nWeight: 800g', 'In a specialist textile studio, the Floral Jacquard Throw is woven on precision looms that interlace threads to form raised floral motifs across a soft, ivory ground. Each throw is finished with hand-rolled edges and carefully inspected for evenness of pattern before it leaves the mill — a piece designed to drape beautifully across a bed, sofa, or armchair.', 0, 10),
                                                                                                                                                                      (50, 'Multipurpose Striped Throw', 20.00, 55.00, 'supplier@textilecraft.com.au', '2026-05-11 16:25:31', '2026-05-11 16:25:31', 'Colour: Natural and grey\r\nMaterial: Cotton and polyester blend\r\nDimensions: 130cm (W) x 180cm (L)\r\nWeight: 600g', 'Woven in a clean, rhythmic stripe on traditional looms, the Multipurpose Striped Throw blends the softness of cotton with the durability of polyester. Finished with neat fringe edges, it moves effortlessly from sofa accent to picnic blanket, adapting wherever warmth and texture are needed most.', 0, 10),
                                                                                                                                                                      (51, 'Chenille Throw', 22.00, 55.00, 'supplier@textilecraft.com.au', '2026-05-11 16:28:01', '2026-05-11 16:28:01', 'Colour: Warm taupe\r\nMaterial: Chenille yarn, polyester backing\r\nDimensions: 130cm (W) x 160cm (L)\r\nWeight: 900g', 'The Chenille Throw is crafted from soft, tufted chenille yarn that creates a velvety pile with a gentle sheen. Each throw is carefully finished for weight and drape, resulting in a piece that feels substantial yet supple — designed to cocoon and comfort through every season.', 0, 10),
                                                                                                                                                                      (52, 'Double Width Linen Curtain', 62.00, 145.00, 'supplier@textilecraft.com.au', '2026-05-11 16:30:27', '2026-05-11 16:30:27', 'Colour: Natural linen\r\nMaterial: 100% European linen\r\nDimensions: 250cm (H) x 270cm (W)\r\nHeader: 7cm rod pocket tape', 'Cut from a single length of heavy European linen, the Double Width Curtain filters light with a quiet warmth, casting a soft glow across any room. The natural fibres are left largely untreated to preserve their texture and character, each panel finished with a generous hem that holds its fall with effortless grace.', 0, 11),
                                                                                                                                                                      (53, 'Distressed Textured Ceramic Vase', 44.00, 109.00, 'supplier@ceramicarts.com.au', '2026-05-11 16:32:55', '2026-05-11 16:32:55', 'Colour: Aged white with terracotta undertones\r\nMaterial: Stoneware ceramic\r\nDimensions: 38cm (H) x 16cm (Dia.)', 'Thrown on a wheel in a small ceramics studio, the Distressed Textured Ceramic Vase is shaped by hand and then finished with deliberate surface marking to create its characteristic aged quality. The natural clay body is fired at high temperature before a weathered glaze is applied in warm, earthy tones — a vessel that carries the honest marks of its making.', 0, 7),
                                                                                                                                                                      (54, 'Textured Ceramic Vase', 28.00, 72.00, 'supplier@ceramicarts.com.au', '2026-05-11 16:34:17', '2026-05-11 16:34:17', 'Colour: Matte stone grey\r\nMaterial: Stoneware ceramic\r\nDimensions: 26cm (H) x 12cm (Dia.)', 'Hand-thrown and surface-textured in a studio setting, the Textured Ceramic Vase is shaped with deliberate ridges and soft impressions that catch light and shadow across its form. Fired in a matte stone glaze, it feels considered and grounded — a quiet vessel that complements cut stems and dried botanicals equally well.', 0, 7);

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
    ) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`) VALUES
                                                                  (21, 1, 'art_deco_ring.png'),
                                                                  (22, 2, 'statement_torsade_pave___ring.png'),
                                                                  (23, 3, 'dainty_rose_gold_ring.png'),
                                                                  (25, 5, 'LOVE_morse_code_ring.png'),
                                                                  (27, 7, 'art_deco_pave___choker.png'),
                                                                  (28, 8, 'lilac_torsade_adjustable_necklace.png'),
                                                                  (29, 9, 'lumiere-pave-choker.png'),
                                                                  (30, 10, 'maille-marine-chain-necklace.png'),
                                                                  (35, 16, 'lilac-lumiere-pave-bracelet.png'),
                                                                  (36, 15, 'art-deco-pave-bracelet.png'),
                                                                  (37, 14, 'asymmetric-cross-earrings.png'),
                                                                  (38, 13, 'dainty-rose-gold-hoop-earrings.png'),
                                                                  (39, 12, 'torsade-hoop-earrings.png'),
                                                                  (40, 11, 'art-deco-statement-drop-earrings.png'),
                                                                  (41, 4, 'chunky_rce_ring.png'),
                                                                  (91, 34, 'Screenshot_2026-04-21_125329.png'),
                                                                  (92, 35, 'Screenshot_2026-04-21_125908.png'),
                                                                  (93, 36, 'Screenshot_2026-04-21_130457.png'),
                                                                  (94, 37, 'Screenshot_2026-04-21_131058.png'),
                                                                  (113, 16, 'Copilot_20260504_162402.png'),
                                                                  (114, 15, 'Copilot_20260504_162942.png'),
                                                                  (115, 14, 'Copilot_20260504_163148.png'),
                                                                  (116, 13, 'Copilot_20260504_163440.png'),
                                                                  (117, 12, 'Copilot_20260504_163820.png'),
                                                                  (118, 11, 'Copilot_20260504_164057.png'),
                                                                  (119, 10, 'Copilot_20260504_164805.png'),
                                                                  (120, 9, 'Copilot_20260504_165010.png'),
                                                                  (121, 8, 'Copilot_20260504_165204.png'),
                                                                  (122, 7, 'Copilot_20260504_165404.png'),
                                                                  (124, 5, 'Copilot_20260504_165811.png'),
                                                                  (125, 4, 'Copilot_20260504_165953.png'),
                                                                  (126, 3, 'Copilot_20260504_170157.png'),
                                                                  (127, 2, 'Copilot_20260504_170337.png'),
                                                                  (128, 1, 'Copilot_20260504_170459.png'),
                                                                  (140, 37, 'f4f49bea-e080-4421-bb51-30d59193b325.png'),
                                                                  (141, 36, '7253548a-e787-48b7-a439-4dcaedf7cf83.png'),
                                                                  (142, 35, '185081e2-bcdd-47ee-93bf-6648f82ee9a8.png'),
                                                                  (143, 34, 'dcbe07bd-b2d5-4cc8-ba96-142777353154.png'),
                                                                  (148, 6, 'Copilot_20260506_212001.png'),
                                                                  (149, 6, 'Copilot_20260504_165515.png'),
                                                                  (161, 38, 'Copilot_20260511_155247.png'),
                                                                  (162, 38, 'Copilot_20260511_155416.png'),
                                                                  (163, 39, 'Copilot_20260511_155554.png'),
                                                                  (164, 39, 'Copilot_20260511_155647.png'),
                                                                  (165, 45, 'Copilot_20260511_160129.png'),
                                                                  (166, 45, 'Copilot_20260511_160236.png'),
                                                                  (167, 47, '45334008700-a7.webp'),
                                                                  (168, 47, '45334008700-p1.webp'),
                                                                  (169, 42, '44162008400-a7.webp'),
                                                                  (170, 42, '44162008400-p1.webp'),
                                                                  (171, 41, '44164008250-a7.webp'),
                                                                  (172, 41, '44164008250-p1.webp'),
                                                                  (173, 49, '49113004808-a7.webp'),
                                                                  (174, 49, '49113004808-p1.webp'),
                                                                  (175, 50, '46370004104-a7.webp'),
                                                                  (176, 50, '46370004104-p1.webp'),
                                                                  (177, 51, '42360004711-a7.webp'),
                                                                  (178, 51, '42360004711-p1.webp'),
                                                                  (179, 52, '41359032250-a7.webp'),
                                                                  (180, 52, '41359032250-p1.webp'),
                                                                  (181, 53, '46317046800-a7.webp'),
                                                                  (182, 53, '46317046800-p1.webp'),
                                                                  (183, 54, '45377046802-a7.webp'),
                                                                  (184, 54, '45377046802-r1.webp');

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
    ) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `stock`) VALUES
                                                                         (1, 1, 'Size 5', 7),
                                                                         (2, 1, 'Size 6', 9),
                                                                         (3, 1, 'Size 7', 10),
                                                                         (4, 1, 'Size 8', 8),
                                                                         (5, 1, 'Size 9', 6),
                                                                         (6, 2, 'Size 5', 6),
                                                                         (7, 2, 'Size 6', 8),
                                                                         (8, 2, 'Size 7', 8),
                                                                         (9, 2, 'Size 8', 7),
                                                                         (10, 2, 'Size 9', 5),
                                                                         (11, 3, 'Size 5', 12),
                                                                         (12, 3, 'Size 6', 14),
                                                                         (13, 3, 'Size 7', 12),
                                                                         (14, 3, 'Size 8', 8),
                                                                         (15, 3, 'Size 9', 6),
                                                                         (16, 4, 'Size 5', 3),
                                                                         (17, 4, 'Size 6', 4),
                                                                         (18, 4, 'Size 7', 4),
                                                                         (19, 4, 'Size 8', 3),
                                                                         (20, 4, 'Size 9', 2),
                                                                         (21, 5, 'Size 5', 8),
                                                                         (22, 5, 'Size 6', 9),
                                                                         (23, 5, 'Size 7', 10),
                                                                         (24, 5, 'Size 8', 8),
                                                                         (25, 5, 'Size 9', 6),
                                                                         (26, 6, 'One Size', 18),
                                                                         (27, 7, 'One Size', 6),
                                                                         (28, 8, 'One Size', 15),
                                                                         (29, 9, 'One Size', 8),
                                                                         (30, 10, 'One Size', 10),
                                                                         (31, 11, 'One Size', 14),
                                                                         (32, 12, 'One Size', 22),
                                                                         (33, 13, 'One Size', 20),
                                                                         (34, 14, 'One Size', 16),
                                                                         (35, 15, 'One Size', 12),
                                                                         (36, 16, 'One Size', 15),
                                                                         (61, 34, 'One Size', 8),
                                                                         (62, 35, 'One Size', 6),
                                                                         (63, 36, 'One Size', 5),
                                                                         (64, 37, 'One Size', 6),
                                                                         (65, 38, 'One Size', 30),
                                                                         (66, 39, 'One Size', 18),
                                                                         (68, 41, 'One Size', 22),
                                                                         (69, 42, 'One Size', 20),
                                                                         (72, 45, 'One Size', 14),
                                                                         (74, 47, 'One Size', 18),
                                                                         (77, 49, 'One Size', 24),
                                                                         (78, 50, 'One Size', 32),
                                                                         (79, 51, 'One Size', 26),
                                                                         (80, 52, 'One Size', 16),
                                                                         (81, 53, 'One Size', 12),
                                                                         (82, 54, 'One Size', 14);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `week_start` date NOT NULL COMMENT 'Monday of the scheduled week',
    `day_of_week` tinyint(4) NOT NULL COMMENT '1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat, 7=Sun',
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    `created` datetime DEFAULT NULL,
    `modified` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_staff_week_day` (`user_id`,`week_start`,`day_of_week`)
    ) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `week_start`, `day_of_week`, `start_time`, `end_time`, `created`, `modified`) VALUES
                                                                                                                            (4, 10, '2026-05-11', 1, '09:00:00', '17:00:00', '2026-05-05 13:51:43', '2026-05-05 13:51:43'),
                                                                                                                            (9, 10, '2026-05-04', 1, '09:00:00', '17:00:00', '2026-05-07 11:32:53', '2026-05-07 11:32:53'),
                                                                                                                            (10, 13, '2026-05-04', 1, '09:00:00', '19:00:00', '2026-05-07 12:51:25', '2026-05-07 12:51:25'),
                                                                                                                            (11, 12, '2026-05-04', 2, '09:00:00', '17:00:00', '2026-05-07 12:51:40', '2026-05-07 12:51:40'),
                                                                                                                            (15, 11, '2026-05-04', 2, '09:00:00', '17:00:00', '2026-05-07 13:19:36', '2026-05-07 13:19:36'),
                                                                                                                            (16, 11, '2026-05-04', 3, '09:00:00', '17:00:00', '2026-05-07 13:19:36', '2026-05-07 13:19:36'),
                                                                                                                            (17, 11, '2026-05-04', 4, '09:00:00', '17:00:00', '2026-05-07 13:19:36', '2026-05-07 13:19:36'),
                                                                                                                            (18, 11, '2026-05-04', 5, '09:00:00', '17:00:00', '2026-05-07 13:19:36', '2026-05-07 13:19:36'),
                                                                                                                            (19, 13, '2026-05-11', 1, '09:00:00', '17:00:00', '2026-05-07 15:44:23', '2026-05-07 15:44:23'),
                                                                                                                            (20, 13, '2026-05-11', 2, '09:00:00', '17:00:00', '2026-05-07 15:44:23', '2026-05-07 15:44:23'),
                                                                                                                            (21, 13, '2026-05-11', 3, '09:00:00', '17:00:00', '2026-05-07 15:44:23', '2026-05-07 15:44:23'),
                                                                                                                            (22, 13, '2026-05-11', 4, '09:00:00', '17:00:00', '2026-05-07 15:44:23', '2026-05-07 15:44:23'),
                                                                                                                            (23, 13, '2026-05-11', 5, '09:00:00', '17:00:00', '2026-05-07 15:44:23', '2026-05-07 15:44:23'),
                                                                                                                            (24, 12, '2026-05-18', 1, '09:00:00', '17:00:00', '2026-05-07 15:47:11', '2026-05-07 15:47:11'),
                                                                                                                            (25, 12, '2026-05-18', 2, '09:00:00', '17:00:00', '2026-05-07 15:47:11', '2026-05-07 15:47:11'),
                                                                                                                            (26, 12, '2026-05-18', 3, '09:00:00', '17:00:00', '2026-05-07 15:47:11', '2026-05-07 15:47:11'),
                                                                                                                            (27, 12, '2026-05-18', 4, '09:00:00', '17:00:00', '2026-05-07 15:47:11', '2026-05-07 15:47:11'),
                                                                                                                            (28, 12, '2026-05-18', 5, '09:00:00', '17:00:00', '2026-05-07 15:47:11', '2026-05-07 15:47:11');

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
    ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
                                                                                                                                                           (6, 'admin@test.com', '$2y$12$3j848N59AYD3s84DOpqA7eI0Cu6aqLosTj9aGuR.8b4ysY2kaNMg6', 'Admin', 'Test', NULL, NULL, NULL, NULL, '2026-03-24 04:04:54', '2026-03-24 04:04:54', 'admin'),
                                                                                                                                                           (9, 'customer@gmail.com', '$2y$12$dpFy6UEE5lMm7GcYFM9KHewxxP6lHS1UUxqlARKrLqYpgupBvZAPq', 'Customer', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:19:40', '2026-04-16 13:19:40', 'customer'),
                                                                                                                                                           (10, 'staff@gmail.com', '$2y$12$7UeOSrqwNufTAgpnq0aFCuR0MqwCn1EUSN.GtTMrkePbB/kErewh6', 'Staff', 'Test', NULL, NULL, NULL, NULL, '2026-04-16 13:20:17', '2026-04-16 13:20:17', 'staff'),
                                                                                                                                                           (11, 'sen@staff.com', '$2y$12$Qym3rQqnHERvGNFi.hduz.RNTCUgJsi8jUYm4ChHYHa0ZellH9OzO', 'Sen', 'Staff', NULL, NULL, NULL, NULL, '2026-05-07 11:56:00', '2026-05-07 11:57:42', 'staff'),
                                                                                                                                                           (12, 'justin@staff.com', '$2y$12$Hiduc3Sk2MSGQ4gqibXbUuI2xbBld5lJOyOn6WCNEkU2Tr1lSQ0J6', 'Justin', 'Staff', NULL, NULL, NULL, NULL, '2026-05-07 11:56:28', '2026-05-07 11:57:48', 'staff'),
                                                                                                                                                           (13, 'katherine@staff.com', '$2y$12$2r.EIs49aZ3H4wEcE8bozOaG3JszX5cOUsUjF0h9v0pE3ZrxHZvvq', 'Katherine', 'Staff', NULL, NULL, NULL, NULL, '2026-05-07 11:57:14', '2026-05-07 11:57:37', 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
                                           `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `created` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_product` (`user_id`,`product_id`),
    KEY `user_id` (`user_id`),
    KEY `product_id` (`product_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created`) VALUES
                                                                       (1, 9, 16, '2026-05-07 23:14:01'),
                                                                       (2, 9, 15, '2026-05-07 23:14:04'),
                                                                       (3, 9, 14, '2026-05-07 23:14:05'),
                                                                       (4, 9, 1, '2026-05-07 23:14:56'),
                                                                       (10, 6, 14, '2026-05-08 02:42:43'),
                                                                       (12, 6, 12, '2026-05-08 02:42:45'),
                                                                       (14, 6, 3, '2026-05-08 02:46:32'),
                                                                       (15, 6, 45, '2026-05-08 02:50:35'),
                                                                       (16, 6, 15, '2026-05-08 02:55:02'),
                                                                       (20, 6, 16, '2026-05-12 22:24:09');

--
-- Constraints for dumped tables
--

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
-- Constraints for table `page_content`
--
ALTER TABLE `page_content`
    ADD CONSTRAINT `fk_pc_page` FOREIGN KEY (`page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
    ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

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

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
    ADD CONSTRAINT `fk_schedule_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
    ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
