-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 09, 2022 at 05:31 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wallets_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(15,2) NOT NULL,
  `from_wallet_id` int(11) NOT NULL,
  `to_wallet_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `amount`, `from_wallet_id`, `to_wallet_id`, `from_user_id`, `created`, `modified`) VALUES
(12, '10.00', 2, 10, 3, '2022-09-09 11:00:34', '2022-09-09 11:00:34'),
(11, '10.00', 1, 10, 1, '2022-09-09 10:54:19', '2022-09-09 10:54:19'),
(10, '20.00', 1, 10, 1, '2022-09-09 10:52:05', '2022-09-09 10:52:05'),
(9, '50.00', 4, 1, 2, '2022-09-09 04:07:45', '2022-09-09 04:07:45'),
(8, '10.00', 2, 4, 3, '2022-09-09 01:29:52', '2022-09-09 01:29:52'),
(7, '10.00', 1, 2, 1, '2022-09-09 01:29:28', '2022-09-09 01:29:28'),
(13, '10.00', 2, 10, 3, '2022-09-09 11:00:59', '2022-09-09 11:00:59'),
(14, '15.00', 1, 2, 1, '2022-09-09 17:12:08', '2022-09-09 17:12:08'),
(15, '15.00', 10, 1, 3, '2022-09-09 17:12:35', '2022-09-09 17:12:35'),
(16, '15.00', 10, 1, 3, '2022-09-09 17:13:01', '2022-09-09 17:13:01'),
(17, '10.00', 10, 1, 3, '2022-09-09 17:14:03', '2022-09-09 17:14:03'),
(18, '10.00', 10, 1, 3, '2022-09-09 17:15:04', '2022-09-09 17:15:04'),
(19, '10.00', 1, 1, 1, '2022-09-09 17:15:28', '2022-09-09 17:15:28'),
(20, '1.00', 10, 1, 3, '2022-09-09 17:15:45', '2022-09-09 17:15:45'),
(21, '1.00', 10, 1, 3, '2022-09-09 17:16:03', '2022-09-09 17:16:03'),
(22, '1.00', 10, 1, 3, '2022-09-09 17:16:39', '2022-09-09 17:16:39'),
(23, '1.00', 10, 1, 3, '2022-09-09 17:18:12', '2022-09-09 17:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `min_balance` decimal(15,2) NOT NULL,
  `monthly_rate` decimal(5,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`, `min_balance`, `monthly_rate`, `created`, `modified`) VALUES
(1, 'Sipe', '50.00', '0.50', '2022-09-08 19:47:01', '2022-09-08 18:48:06'),
(2, 'Zapp', '80.00', '0.80', '2022-09-08 19:47:01', '2022-09-08 18:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`) VALUES
(1, 'Femi', 'femi@test.com'),
(2, 'Bola', 'bola@test.com'),
(3, 'James', 'james@test.com'),
(4, 'Peter', 'peter@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `balance` decimal(15,2) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `balance`, `type_id`, `user_id`, `created`, `modified`) VALUES
(10, '86.00', 2, 3, '2022-09-09 04:04:25', '2022-09-09 04:04:25'),
(1, '89.00', 1, 1, '2022-09-09 01:18:56', '2022-09-09 01:18:56'),
(2, '95.00', 2, 3, '2022-09-09 01:18:33', '2022-09-09 01:18:33'),
(4, '100.00', 1, 2, '2022-09-09 01:18:11', '2022-09-09 01:18:11');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
