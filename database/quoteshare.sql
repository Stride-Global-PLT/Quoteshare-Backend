-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2021 at 11:11 PM
-- Server version: 5.6.49-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quoteshare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `picture` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL,
  `banner_categoryid` int(11) DEFAULT NULL,
  `banner_categoryname` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `is_feature` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE `feeds` (
  `feed_id` int(11) NOT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quote` varchar(1200) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT '0',
  `booker_id` int(11) DEFAULT '0',
  `tagger_id` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_comments`
--

CREATE TABLE `feed_comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `feed_id` int(11) NOT NULL DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_likes`
--

CREATE TABLE `feed_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feed_id` int(11) DEFAULT NULL,
  `is_liked` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `follow_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `follower_id` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL DEFAULT '0',
  `receiver` int(11) DEFAULT '0',
  `feed_id` int(11) DEFAULT '0',
  `message` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quotes_category`
--

CREATE TABLE `quotes_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `report_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `report_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resharingcount`
--

CREATE TABLE `resharingcount` (
  `resharing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `feed_id` int(11) NOT NULL DEFAULT '0',
  `resharing_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `social_id` varchar(255) DEFAULT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1normal,2author,3book,4tag',
  `full_name` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `login_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'normal 1, google,facebook 2',
  `device_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for ios,2 for android',
  `device_token` varchar(255) DEFAULT NULL,
  `session_key` varchar(255) DEFAULT NULL,
  `verification_code` int(11) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `is_verified` tinyint(4) NOT NULL DEFAULT '0',
  `picture` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `verfication_code`
--

CREATE TABLE `verfication_code` (
  `verification_id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `feeds`
--
ALTER TABLE `feeds`
  ADD PRIMARY KEY (`feed_id`);

--
-- Indexes for table `feed_comments`
--
ALTER TABLE `feed_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `feed_likes`
--
ALTER TABLE `feed_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follow_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `quotes_category`
--
ALTER TABLE `quotes_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `resharingcount`
--
ALTER TABLE `resharingcount`
  ADD PRIMARY KEY (`resharing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `verfication_code`
--
ALTER TABLE `verfication_code`
  ADD PRIMARY KEY (`verification_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeds`
--
ALTER TABLE `feeds`
  MODIFY `feed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feed_comments`
--
ALTER TABLE `feed_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feed_likes`
--
ALTER TABLE `feed_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes_category`
--
ALTER TABLE `quotes_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resharingcount`
--
ALTER TABLE `resharingcount`
  MODIFY `resharing_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verfication_code`
--
ALTER TABLE `verfication_code`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
