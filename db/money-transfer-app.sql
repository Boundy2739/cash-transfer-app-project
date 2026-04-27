-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 27, 2026 at 03:05 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money-transfer-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `account_name` varchar(36) NOT NULL,
  `owner_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `id` (`account_id`),
  KEY `fk_accounts_owner` (`owner_id`(250)),
  KEY `fk_owner_id` (`owner_id`(250))
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `owner_id`, `balance`, `currency`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
('1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'Savings account', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 1766.76, 'EUR', 'active', 1, '2026-03-07 17:46:18', '2026-03-07 17:46:18'),
('aa7e7e3b-2b07-423b-83c5-580579b07211', 'Default account', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 185.89, 'EUR', 'active', 0, '2026-03-07 17:52:22', '2026-03-07 17:52:22'),
('1375eb91-74d8-4337-abf8-29bf3a93534a', 'Wilson\'s', 'e0debeeb-eedd-428c-b9a2-37afd5fb0a97', 136.28, 'EUR', 'active', 1, '2026-03-08 23:57:47', '2026-03-08 23:57:47'),
('c74d4018-1077-449f-8296-f55493c6886a', '', '37f7b61d-a3ce-4ff9-98f4-95a4094f6fb8', 1290.25, 'EUR', 'active', 1, '2026-03-31 10:46:30', '2026-03-31 10:46:30'),
('2cb08071-87a1-4b89-8066-7ba2dc063c4d', 'Rename test 100', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 2.50, 'EUR', 'active', 0, '2026-04-08 20:26:45', '2026-04-08 20:26:45'),
('d694fcb5-3ec1-4c1a-bca2-891c587e40e3', 'Test wallet', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 4.50, 'EUR', 'active', 0, '2026-04-23 21:37:28', '2026-04-23 21:37:28'),
('9ddd0356-3962-448c-8ca4-6a587d76811b', 'Walter\'s savings wallet', '46651eb1-a301-4baf-af63-047c85700e76', 10000.00, 'EUR', 'active', 1, '2026-04-24 18:34:09', '2026-04-24 18:34:09'),
('b00b06f4-960f-44ab-bb01-7c7efc6129fb', 'Groceries budget', '46651eb1-a301-4baf-af63-047c85700e76', 600.00, 'GBP', 'active', 0, '2026-04-24 18:37:11', '2026-04-24 18:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `rate_limit_ips`
--

DROP TABLE IF EXISTS `rate_limit_ips`;
CREATE TABLE IF NOT EXISTS `rate_limit_ips` (
  `ip_address` int UNSIGNED NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `last_attempt` datetime DEFAULT NULL,
  `lock_time` datetime DEFAULT NULL,
  PRIMARY KEY (`ip_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rate_limit_ips`
--

INSERT INTO `rate_limit_ips` (`ip_address`, `attempts`, `last_attempt`, `lock_time`) VALUES
(2130706433, 0, '2026-04-25 22:10:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `sender_wallet_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `receiver_wallet_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `amount` decimal(10,2) DEFAULT '0.00',
  `currency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fail_reason` varchar(36) DEFAULT NULL,
  `transaction_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `fk_sender_wallet_id` (`sender_wallet_id`),
  KEY `fk_receiver_wallet_id` (`receiver_wallet_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `sender_wallet_id`, `receiver_wallet_id`, `type`, `amount`, `currency`, `status`, `fail_reason`, `transaction_date`) VALUES
(18, '7bd3002b-712f-4553-bfe2-5307a5d028a5', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 'Deposit', 99.00, 'Euro', 'successful', NULL, '2026-03-09 16:06:02'),
(19, '7bd3002b-712f-4553-bfe2-5307a5d028a5', 'e0debeeb-eedd-428c-b9a2-37afd5fb0a97', 'Deposit', 500.00, 'Euro', 'successful', NULL, '2026-03-09 16:52:30'),
(16, '7bd3002b-712f-4553-bfe2-5307a5d028a5', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 'Deposit', 15.00, 'Euro', 'successful', NULL, '2026-03-08 23:40:46'),
(17, '7bd3002b-712f-4553-bfe2-5307a5d028a5', 'e0debeeb-eedd-428c-b9a2-37afd5fb0a97', 'Deposit', 257.00, 'Euro', 'successful', NULL, '2026-03-08 23:57:55'),
(21, 'e0debeeb-eedd-428c-b9a2-37afd5fb0a97', '7bd3002b-712f-4553-bfe2-5307a5d028a5', 'transfer', 58.39, 'EUR', 'successful', NULL, '2026-03-09 17:01:45'),
(22, NULL, 'e0debeeb-eedd-428c-b9a2-37afd5fb0a97', 'Deposit', 900.00, 'Euro', 'successful', NULL, '2026-03-10 11:52:25'),
(47, 'b00b06f4-960f-44ab-bb01-7c7efc6129fb', 'b00b06f4-960f-44ab-bb01-7c7efc6129fb', 'Deposit', 600.00, 'Euro', 'successful', NULL, '2026-04-24 18:37:16'),
(46, '9ddd0356-3962-448c-8ca4-6a587d76811b', '9ddd0356-3962-448c-8ca4-6a587d76811b', 'Deposit', 10000.00, 'Euro', 'successful', NULL, '2026-04-24 18:34:19'),
(45, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'Deposit', 800.00, 'Euro', 'successful', NULL, '2026-04-24 08:50:01'),
(44, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 22.22, 'EUR', 'successful', NULL, '2026-04-24 08:43:48'),
(43, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 119.83, 'EUR', 'successful', NULL, '2026-04-24 08:41:25'),
(42, 'c74d4018-1077-449f-8296-f55493c6886a', '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'transfer', 213.00, 'EUR', 'successful', NULL, '2026-04-23 18:17:38'),
(41, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 56.00, 'EUR', 'successful', NULL, '2026-04-23 18:05:16'),
(40, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 25.00, 'EUR', 'successful', NULL, '2026-04-23 00:12:52'),
(50, '9ddd0356-3962-448c-8ca4-6a587d76811b', NULL, 'transfer', 43.00, 'GBP', 'failed', 'Non existent recipient.', '2026-04-24 22:02:52'),
(51, '9ddd0356-3962-448c-8ca4-6a587d76811b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 99999999.99, 'GBP', 'failed', 'Not enough founds', '2026-04-24 22:03:01'),
(52, '9ddd0356-3962-448c-8ca4-6a587d76811b', 'b00b06f4-960f-44ab-bb01-7c7efc6129fb', 'transfer', 99999999.99, 'GBP', 'failed', 'Not enough funds!', '2026-04-24 22:19:37'),
(53, '9ddd0356-3962-448c-8ca4-6a587d76811b', NULL, 'transfer', NULL, 'GBP', 'failed', 'Non existent recipient.', '2026-04-24 23:30:41'),
(54, '9ddd0356-3962-448c-8ca4-6a587d76811b', NULL, 'transfer', NULL, 'GBP', 'failed', 'Recipient not found.', '2026-04-24 23:59:10'),
(55, '9ddd0356-3962-448c-8ca4-6a587d76811b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-24 23:59:58'),
(56, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:06:20'),
(57, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:08:28'),
(58, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:09:57'),
(59, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:11:33'),
(60, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:12:09'),
(61, 'aa7e7e3b-2b07-423b-83c5-580579b07211', NULL, 'transfer', 0.00, 'GBP', 'failed', 'Recipient not found.', '2026-04-25 00:12:28'),
(62, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'transfer', 99999999.99, 'GBP', 'failed', 'Not enough funds', '2026-04-25 08:35:31'),
(63, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'Deposit', 2.50, 'GBP', 'successful', NULL, '2026-04-27 15:01:45'),
(64, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', '2cb08071-87a1-4b89-8066-7ba2dc063c4d', 'transfer', 99999999.99, 'GBP', 'failed', 'Not enough funds!', '2026-04-27 15:08:17'),
(65, '1f3a6142-6a3d-482b-a5ea-198d70b3e32b', 'c74d4018-1077-449f-8296-f55493c6886a', 'payment', 2.20, 'EUR', 'successful', NULL, '2026-04-27 15:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(36) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(36) NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_street_name` varchar(255) NOT NULL,
  `address_house_number` int NOT NULL,
  `city` varchar(36) NOT NULL,
  `postcode` varchar(36) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email_idx` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `phone_2` (`phone`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `date_of_birth`, `email`, `password_hash`, `phone`, `address_street_name`, `address_house_number`, `city`, `postcode`, `created_at`) VALUES
('7bd3002b-712f-4553-bfe2-5307a5d028a5', 'Willy', '', 'Warner', 'abc212441898', '2026-03-19', 'abd@gmail.com', '$2y$10$E6plIo3BODrSyE3AvrZR1On5oSIht6jCrNdq0m/seMEVsBznrv7IK', '314714894210', 'Address somewhere', 21, 'Rome', 'IDK 123', '2026-03-07 13:14:08'),
('e0debeeb-eedd-428c-b9a2-37afd5fb0a97', 'Wilson', 'w', 'WWWWWWW', 'wilson2931', '2026-03-09', 'wilson@gmail.com', '$2y$10$cpbHq0D1xp/ePM2byYgyYuggbEZap/3fTFUr0u/.LC7CIT8RjCE0G', '144214', 'Address somewhere', 4, 'Dublin', 'IDK 900', '2026-03-08 23:56:58'),
('37f7b61d-a3ce-4ff9-98f4-95a4094f6fb8', 'Gru', 'Mr', 'Felonious', 'DespicableMe123', '2026-03-01', 'mrevil@gmail.com', '$2y$10$zOfrXSH6H1hotF9a5w50K.yt0XyLnNJJyRMj0XoDF9A5HfQ9rdzma', '12345678', 'Address somewhere', 87, 'Wordington', 'WDR 123', '2026-03-31 10:32:21'),
('1691561c-9e69-4f60-b077-d80149799454', 'Mario', '', 'Rossi', 'SigRossi1985', '1985-03-10', 'rossi@gmail.com', '$2y$10$DIZ0VRtQKxiR5qCZsszuUuRJS7C.vAm9LQzYghi6/7SuP.EpQpyfm', '0123456789', 'Somewhere', 93, 'London', 'IDK 809', '2026-04-08 11:01:39'),
('7d91ff83-cd30-40c7-a74c-43913696298b', 'Mike', '', 'Ehrmantraut', 'mikeEh478', '2004-02-21', 'ehrmantraut@email.com', '$2y$10$iMdjZob30G6aw5HQsH2kA.kOc7jRSouCbwPK0APDQC8YssRBQzrOW', '6787078657', 'Somewhere', 75, 'Birmingham', '', '2026-04-24 17:19:03'),
('46651eb1-a301-4baf-af63-047c85700e76', 'Walter', '', 'White', 'MrWhite9000', '2026-03-30', 'wwhite@email.com', '$2y$10$EYuNp5PlzptsYSszXw7d3eUywoZ8aZsqn8Z361iBSvJzQej8fuMie', '6907', 'Somewhere', 89, 'York', '', '2026-04-24 18:31:52'),
('681c0469-e64d-4840-9fb9-5ef09303e1fd', 'Nome', '', 'Cognome', 'Nome272818', '2026-04-14', 'nome@email.com', '$2y$10$EAR5PQrWykLdQQa63GyOK.GGpFqEqhRoqF5epWdNvJ9z2fwAmh7rK', '41414', 'Somewhere', 2, 'Ediburgh', '', '2026-04-25 19:11:53');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
