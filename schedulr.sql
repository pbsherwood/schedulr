-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 15, 2017 at 04:09 PM
-- Server version: 5.5.51-38.2
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `schedulr`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedulr_guests`
--

CREATE TABLE IF NOT EXISTS `schedulr_guests` (
  `id` mediumint(8) unsigned NOT NULL,
  `items_id` mediumint(8) unsigned NOT NULL,
  `users_id` mediumint(8) unsigned NOT NULL,
  `created_by` mediumint(8) unsigned NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedulr_items`
--

CREATE TABLE IF NOT EXISTS `schedulr_items` (
  `schedulr_items_id` mediumint(8) unsigned NOT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `allDay` tinyint(1) NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `repeat_id` mediumint(8) unsigned NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` mediumint(9) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedulr_users`
--

CREATE TABLE IF NOT EXISTS `schedulr_users` (
  `schedulr_users_id` mediumint(9) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_roles` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `schedulr_users`
--

INSERT INTO `schedulr_users` (`email`, `password`, `first_name`, `last_name`, `user_roles`, `phone`) VALUES
('admin@test.com', '$2y$10$lUogO016kmSGAyb7VglM2eQB1vh1pq7Bah1GhjvlPbGVVrdcpwAjm', 'Admin', 'User', 'admin', '12345678900000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedulr_guests`
--
ALTER TABLE `schedulr_guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedulr_items`
--
ALTER TABLE `schedulr_items`
  ADD PRIMARY KEY (`schedulr_items_id`);

--
-- Indexes for table `schedulr_users`
--
ALTER TABLE `schedulr_users`
  ADD PRIMARY KEY (`schedulr_users_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedulr_guests`
--
ALTER TABLE `schedulr_guests`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `schedulr_items`
--
ALTER TABLE `schedulr_items`
  MODIFY `schedulr_items_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `schedulr_users`
--
ALTER TABLE `schedulr_users`
  MODIFY `schedulr_users_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
