SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `veloura_jewels` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
USE `veloura_jewels`;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `categories_products`;
DROP TABLE IF EXISTS `contact_submissions`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `categories` (
                              `id` int(11) NOT NULL,
                              `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `categories_products` (
                                       `id` int(11) NOT NULL,
                                       `category_id` int(11) NOT NULL,
                                       `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `contact_submissions` (`id`, `first_name`, `last_name`, `email`, `message`, `captcha_passed`, `created`, `modified`) VALUES
                                                                                                                                     (1, 'Jialin', 'Wu', 'jialinwu.island@gmail.com', '1', 0, '2026-03-20 02:40:43', '2026-03-20 02:40:43'),
                                                                                                                                     (3, '11', '11', '11@1.com', '1wassadsad', 0, '2026-03-20 02:44:25', '2026-03-20 02:44:25'),
                                                                                                                                     (4, 'test', 'testing', 'test@gmail.com', 'this is after removing login thing', 0, '2026-03-22 01:11:19', '2026-03-22 01:11:19');

CREATE TABLE `products` (
                            `id` int(11) NOT NULL,
                            `name` varchar(64) NOT NULL,
                            `purchase_price` decimal(9,2) NOT NULL,
                            `sale_price` decimal(9,2) NOT NULL,
                            `supplier_email` varchar(320) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `product_images` (
                                  `id` int(11) NOT NULL,
                                  `product_id` int(11) NOT NULL,
                                  `filename` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `email` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `nonce` varchar(255) DEFAULT NULL,
                         `nonce_expiry` datetime DEFAULT NULL,
                         `created` datetime DEFAULT NULL,
                         `modified` datetime DEFAULT NULL,
                         `role` varchar(50) NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `users` (`id`, `email`, `password`, `nonce`, `nonce_expiry`, `created`, `modified`, `role`) VALUES
    (1, 'admin@test.com', '$2y$12$8/IZh.Uu7yYH3PftqfXz4u8Zz3TYZeczS1LS3qKQN3.ftYUxYQuhe', NULL, NULL, '2026-03-20 14:24:24', '2026-03-20 14:24:24', 'admin');

ALTER TABLE `categories` ADD PRIMARY KEY (`id`);
ALTER TABLE `categories_products`
    ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `product_id` (`product_id`);
ALTER TABLE `contact_submissions` ADD PRIMARY KEY (`id`);
ALTER TABLE `products` ADD PRIMARY KEY (`id`);
ALTER TABLE `product_images`
    ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `categories` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `categories_products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `contact_submissions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `product_images` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `categories_products`
    ADD CONSTRAINT `categories_products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `categories_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
ALTER TABLE `product_images`
    ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
