-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2024 at 02:44 PM
-- Server version: 10.6.16-MariaDB-cll-lve-log
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlbeiztw_mymufti_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data_id` varchar(191) NOT NULL DEFAULT '',
  `message` varchar(191) NOT NULL DEFAULT '',
  `type` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `data_id`, `message`, `type`, `created_at`, `updated_at`) VALUES
(1, '29', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-20 18:41:41', '2024-02-20 18:41:41'),
(40, '36', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-23 21:38:14', '2024-02-23 21:38:14'),
(3, '2', 'A new appointment booked by Numan', 'booked appointment', '2024-02-20 23:34:15', '2024-02-20 23:34:15'),
(39, '35', 'User  added scholar’s details.', 'request', '2024-02-23 18:06:13', '2024-02-23 18:06:13'),
(38, '35', 'User  added scholar’s details.', 'request', '2024-02-23 18:03:03', '2024-02-23 18:03:03'),
(16, '11', 'Mufti Qazi added a new event.', 'event added', '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(8, '31', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-21 00:22:59', '2024-02-21 00:22:59'),
(9, '20', 'A new appointment booked by Mlbench', 'booked appointment', '2024-02-21 00:29:18', '2024-02-21 00:29:18'),
(10, '25', 'Sani posted a new question.', 'posted question', '2024-02-21 09:47:00', '2024-02-21 09:47:00'),
(19, '32', 'User Qazi Ammar Arshad added scholar’s details.', 'request', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(37, '35', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-23 17:55:39', '2024-02-23 17:55:39'),
(36, '34', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-23 17:42:00', '2024-02-23 17:42:00'),
(18, '32', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-21 18:55:07', '2024-02-21 18:55:07'),
(20, '2', 'Numan added a new event.', 'event added', '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(22, '25', 'Sani added a new event.', 'event added', '2024-02-22 10:04:23', '2024-02-22 10:04:23'),
(35, '19', 'User Taimoor Arif added scholar’s details.', 'request', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(24, '19', 'Taimoor Arif added a new event.', 'event added', '2024-02-22 12:45:12', '2024-02-22 12:45:12'),
(34, '22', 'Mufti tahir added a new event.', 'event added', '2024-02-23 16:49:24', '2024-02-23 16:49:24'),
(33, '22', 'User Mufti tahir added scholar’s details.', 'request', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(27, '33', 'A new user has registered on the platform. Review their profile.', 'register', '2024-02-22 16:18:24', '2024-02-22 16:18:24'),
(28, '33', 'User Hadi added scholar’s details.', 'request', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(29, '32', 'Hafi added a new event.', 'event added', '2024-02-22 17:10:25', '2024-02-22 17:10:25'),
(30, '9', 'Rehman added a new event.', 'event added', '2024-02-23 11:07:47', '2024-02-23 11:07:47'),
(32, '22', 'User Tahir added scholar’s details.', 'request', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(41, '36', ' added a new event.', 'event added', '2024-02-23 21:47:24', '2024-02-23 21:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL DEFAULT '',
  `email` varchar(191) NOT NULL DEFAULT '',
  `password` varchar(191) NOT NULL DEFAULT '',
  `email_code` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `email_code`, `created_at`, `updated_at`) VALUES
(1, 'Hassan Abbasi', 'admin@gmail.com', '123456', 4596, '2024-02-14 22:34:38', '2024-02-14 17:43:36'),
(2, 'MLB Tester', 'tahirbaloch2634@gmail.com', '654321', 9780, '2024-02-14 22:44:17', '2024-02-15 10:34:25');

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `degree_title` varchar(191) NOT NULL DEFAULT '',
  `institute_name` varchar(191) NOT NULL DEFAULT '',
  `degree_image` varchar(191) NOT NULL DEFAULT '',
  `degree_startDate` varchar(191) NOT NULL DEFAULT '',
  `degree_endDate` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`id`, `user_id`, `degree_title`, `institute_name`, `degree_image`, `degree_startDate`, `degree_endDate`, `created_at`, `updated_at`) VALUES
(4, 11, 'MS CSSSSS', 'UMT', 'degree_images/TDbo3sDkoV29iUv.png', '2019-02-15', '2021-02-15', '2024-02-15 16:30:20', '2024-02-20 15:43:21'),
(5, 11, 'MS CS', 'UMTT', 'degree_images/mMMLVbyrQBDkyX1.png', '2019-02-15', '2021-02-15', '2024-02-15 16:32:26', '2024-02-15 16:32:26'),
(6, 11, 'MS CS', 'UMTTTT', 'degree_images/wQuIrDPUQTh2802.png', '2019-02-15', '2021-02-15', '2024-02-15 16:33:08', '2024-02-15 16:33:08'),
(7, 11, 'MS CSS', 'UMTT', 'degree_images/u6cR9iaYZYEg2BU.png', '2019-02-15', '2021-02-15', '2024-02-15 16:37:28', '2024-02-15 16:37:28'),
(8, 11, 'MS  once again', 'UMT', 'degree_images/ty7VtuFhftVTJAL.png', '2019-02-15', '2021-02-15', '2024-02-15 16:40:05', '2024-02-15 16:55:20'),
(13, 15, 'MS ISL', 'UMT', 'degree_images/VVdM7TqWRPRUNBS.png', '2019-02-16', '2020-02-16', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(10, 11, 'bscs', 'iub', 'degree_images/rZPPqbvEb826iX9.png', '2021-02-16', '2021-02-16', '2024-02-16 11:30:38', '2024-02-16 11:30:38'),
(11, 11, 'BSCSCSC', 'Iub', 'degree_images/YUgESQYP2D5OGob.png', '2023-02-16', '2023-02-16', '2024-02-16 11:32:04', '2024-02-16 11:44:57'),
(12, 11, 'Ali degree', 'Umt', 'degree_images/tDpcKVSZGbRIhT2.png', '2020-02-16', '2024-02-16', '2024-02-16 15:21:39', '2024-02-16 15:21:39'),
(14, 18, 'Islamic studies', 'Lahore Garrison University', 'degree_images/6u4Gmi9BWaN8J75.png', '2018-02-19', '2023-02-19', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(15, 17, 'bscs', 'BScs', 'degree_images/sksij2GLdEnkTuZ.png', '2023-08-22', '2024-02-20', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(16, 15, 'bscs', 'bscs', 'degree_images/fnJwuzxbE0cY0ri.png', '2023-11-13', '2024-02-20', '2024-02-20 15:22:48', '2024-02-20 15:22:48'),
(17, 11, 'Islamic studies', 'lahore Garrison University', 'degree_images/a75ORbryp47RE1u.png', '2019-02-20', '2024-02-20', '2024-02-20 15:47:21', '2024-02-20 15:47:21'),
(18, 25, 'bscs', 'bscs', 'degree_images/d4HY5CKbEmb6LJt.png', '2023-11-05', '2024-02-20', '2024-02-20 18:25:02', '2024-02-20 18:25:02'),
(26, 22, 'My title', 'Institute', 'degree_images/C7UtkhywzEI4G41.png', '2021-01-23', '2023-02-23', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(24, 33, 'bscs', 'bscs', 'degree_images/QBQWVwtfBNKqZn9.png', '2023-11-06', '2024-02-13', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(23, 32, 'Bscs', 'Bscs', 'degree_images/q0B0NSBoEInFUfe.png', '2015-02-21', '2018-02-21', '2024-02-21 18:56:21', '2024-02-23 17:54:44'),
(27, 22, 'Hahsgsvs', 'Gagags', 'degree_images/GYVxveMX14sx6PA.png', '2020-02-23', '2022-02-23', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(28, 19, 'Fringing', 'Lksndjkdgn', 'degree_images/Zsj895FAxTWphXN.png', '2020-02-23', '2022-02-23', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(30, 35, 'Bscs', 'Bscs', 'degree_images/FXK5x8y7QmczobL.png', '2023-02-23', '2024-01-17', '2024-02-23 18:06:13', '2024-02-23 18:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(191) NOT NULL DEFAULT '',
  `event_title` varchar(191) NOT NULL DEFAULT '',
  `event_category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`event_category`)),
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration` varchar(191) NOT NULL DEFAULT '',
  `location` varchar(191) NOT NULL DEFAULT '',
  `latitude` double NOT NULL DEFAULT 0,
  `longitude` double NOT NULL DEFAULT 0,
  `about` longtext NOT NULL DEFAULT '',
  `event_status` int(11) NOT NULL DEFAULT 2 COMMENT '0 for rejected,1 for approved,2 for pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_id`, `image`, `event_title`, `event_category`, `date`, `duration`, `location`, `latitude`, `longitude`, `about`, `event_status`, `created_at`, `updated_at`) VALUES
(5, 2, 'event_images/q2QQqHwnJqA1AnX.png', 'Mehfil Milad', '[\"CSS\",\"html\",\"PHP\"]', '2000-02-23 13:34:23', '1', 'Model Town', 2.5, 7.5, 'This is a mehfil event', 2, '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(4, 11, 'event_images/TaivAaApZ9eE2gs.png', 'Mehfil', '[\"Hadith\",\"Salah\",\"Dhikir\"]', '2024-02-28 23:18:00', '3', 'ML Bench', 31.4644795, 74.2566424, 'This is a dummy event', 1, '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(9, 19, 'event_images/5DQ9VLUUOyEijtu.png', 'My Event', '[\"Duas\",\"Dhikir\",\"Salah\",\"Dawah\",\"Hadith\",\"Quran\",\"Comparative religion\"]', '2024-02-29 17:44:00', '3', 'ML Bench', 31.4644795, 74.2566424, 'This is a dummy event.', 1, '2024-02-22 12:45:12', '2024-02-22 12:45:50'),
(13, 36, 'event_images/S31i8hMQGmTQwNk.png', 'Miftah', '[\"Family law\",\"Quran\"]', '2024-02-26 16:39:00', '4', '2975 Senora Dr', 42.5804317, -83.0899661, 'Hello', 2, '2024-02-23 21:47:24', '2024-02-23 21:47:24'),
(7, 25, 'event_images/Z842AUMZWWbFbJT.png', 'Mehfil Milad', '[\"CSS\",\"html\",\"PHP\"]', '2024-02-26 13:34:23', '2', 'Model Town', 2.5, 7.5, 'This is a mehfil event', 0, '2024-02-22 10:04:23', '2024-02-22 10:17:12'),
(12, 22, 'event_images/5DCHlOl6LjCgNVq.png', 'Khatam', '[\"Food\",\"Salah\",\"Duas\",\"Raising kids\",\"Dhikir\",\"Finance\"]', '2024-02-23 22:20:44', '3', 'Lahore Garrison Golf & Country Club', 31.5402459, 74.3953998, 'Allot to learn', 1, '2024-02-23 16:49:24', '2024-02-23 17:20:50'),
(10, 32, 'event_images/2bqRVWPXEB3PHG4.png', 'Urs', '[\"Dawah\",\"Duas\",\"Raising kids\",\"Salah\",\"Dhikir\"]', '2024-02-24 22:05:00', '3', 'Lahore Garrison Golf & Country Club', 31.5402459, 74.3953998, 'There you get good knowlage', 2, '2024-02-22 17:10:25', '2024-02-22 17:10:25'),
(11, 9, 'event_images/Wj3mX0RyVN7EoG3.png', 'Mehfil Milad', '[\"CSS\",\"html\",\"PHP\"]', '2000-02-23 13:34:23', '1', 'Model Town', 2.5, 7.5, 'This is a mehfil event', 2, '2024-02-23 11:07:47', '2024-02-23 11:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `event_questions`
--

CREATE TABLE `event_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) NOT NULL DEFAULT '',
  `question` longtext NOT NULL DEFAULT '',
  `answer` longtext NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_questions`
--

INSERT INTO `event_questions` (`id`, `event_id`, `user_id`, `category`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(9, 4, 22, 'Hadith', 'This is a dummy question', '', '2024-02-23 16:58:59', '2024-02-23 16:58:59'),
(8, 4, 22, 'Salah', 'If the Nimaz left then what we can do', '', '2024-02-23 16:56:50', '2024-02-23 16:56:50'),
(4, 4, 19, 'Dhikir', 'Can you tell me bouth this event?', '', '2024-02-22 16:46:17', '2024-02-22 16:46:17'),
(5, 4, 19, 'Salah', 'Adding a dummy question here', 'Answering your dummy question', '2024-02-22 17:08:24', '2024-02-22 17:29:17'),
(6, 4, 19, 'Salah', 'Adding another dummy qustion', '', '2024-02-22 17:30:24', '2024-02-22 17:30:24'),
(7, 9, 11, 'Salah', 'Can we offer two salahs at one time?', 'Ye, you can', '2024-02-22 17:44:17', '2024-02-22 17:44:35'),
(10, 4, 22, 'Dhikir', 'How are you', '', '2024-02-23 17:01:44', '2024-02-23 17:01:44'),
(11, 4, 22, 'Hadith', 'How are you', '', '2024-02-23 17:02:09', '2024-02-23 17:02:09'),
(12, 12, 24, 'food', 'What are you doing', 'Nothing', '2024-02-23 17:23:00', '2024-02-23 17:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `event_scholars`
--

CREATE TABLE `event_scholars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL DEFAULT '',
  `fiqa` varchar(191) NOT NULL DEFAULT '',
  `image` varchar(191) NOT NULL DEFAULT '',
  `category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`category`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_scholars`
--

INSERT INTO `event_scholars` (`id`, `event_id`, `user_id`, `name`, `fiqa`, `image`, `category`, `created_at`, `updated_at`) VALUES
(17, 5, 0, 'Zaheer', 'Mālikī', 'event_scholar/p4C5hCtRzWyyEYJ.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(16, 5, 0, 'Safdar', 'Mālikī', 'event_scholar/yQ51lB8sLWl21LX.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(15, 5, 2, 'Numan', '', 'users_profile/2xexTy5QXMzecE2.png', '[]', '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(14, 4, 0, 'Usman', 'Mālikī', '', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(13, 4, 0, 'Zaheer', 'Mālikī', 'event_scholar/DgvR8f0zFFxnH30.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(12, 4, 0, 'Mufti Tayyab', 'Ḥanafī', 'event_scholar/P6ueJVHdVoQv90k.png', '[\"Dawah\",\"Dhikir\",\"Quran\"]', '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(11, 4, 11, 'Mufti Qazi', 'Ḥanafī', 'users_profile/51afC3FVYJt8vnZ.png', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 18:04:43', '2024-02-21 18:04:43'),
(43, 9, 25, 'Sani', 'Ḥanafī', '', '[\"Duas\",\"Relationship\",\"Marriage\",\"Home Finance\",\"Finance\"]', '2024-02-22 15:17:53', '2024-02-22 15:17:53'),
(42, 9, 11, 'Mufti Qazi', 'Ḥanafī', 'users_profile/51afC3FVYJt8vnZ.png', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-22 15:17:53', '2024-02-22 15:17:53'),
(18, 5, 0, 'Usman', 'Mālikī', '', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 09:54:10', '2024-02-22 09:54:10'),
(62, 13, 0, 'Mufti nayem', 'Shāfiʿī', 'event_scholar/VFPcqYzMgCKZhfX.png', '[\"Duas\",\"Hadith\"]', '2024-02-23 21:47:24', '2024-02-23 21:47:24'),
(23, 7, 2, 'Numan', '', 'users_profile/2xexTy5QXMzecE2.png', '[]', '2024-02-22 10:04:23', '2024-02-22 10:04:23'),
(24, 7, 0, 'Safdar', 'Mālikī', 'event_scholar/87TPx1p8iybV4R6.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 10:04:23', '2024-02-22 10:04:23'),
(25, 7, 0, 'Zaheer', 'Mālikī', 'event_scholar/qW7cQyMCdEy9cDG.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 10:04:23', '2024-02-22 10:04:23'),
(26, 7, 0, 'Usman', 'Mālikī', '', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-22 10:04:23', '2024-02-22 10:04:23'),
(60, 12, 33, 'Rahman', 'Ḥanbalī', '', '[\"Home Finance\",\"Relationship\",\"Duas\"]', '2024-02-23 16:49:24', '2024-02-23 16:49:24'),
(59, 12, 15, 'Ali Syed', 'Shāfiʿī', '', '[\"Salah\",\"Finance\",\"Family law\"]', '2024-02-23 16:49:24', '2024-02-23 16:49:24'),
(58, 12, 11, 'Mufti Qazi', 'Ḥanafī', 'users_profile/51afC3FVYJt8vnZ.png', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-23 16:49:24', '2024-02-23 16:49:24'),
(41, 9, 0, 'New Mufti', 'Ḥanafī', 'event_scholar/2UiGehpw7pUOmKX.png', '[\"Hadith\",\"Dawah\",\"Food\",\"Comparative religion\"]', '2024-02-22 15:17:42', '2024-02-22 15:17:42'),
(49, 10, 15, 'Ali Syed', 'Shāfiʿī', '', '[\"Salah\",\"Finance\",\"Family law\"]', '2024-02-22 17:11:42', '2024-02-22 17:11:42'),
(50, 10, 25, 'Sani', 'Ḥanafī', '', '[\"Duas\",\"Relationship\",\"Marriage\",\"Home Finance\",\"Finance\"]', '2024-02-22 17:11:42', '2024-02-22 17:11:42'),
(48, 10, 11, 'Mufti Qazi', 'Ḥanafī', 'users_profile/51afC3FVYJt8vnZ.png', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-22 17:11:42', '2024-02-22 17:11:42'),
(47, 10, 0, 'Tariq Jamil', 'Ḥanbalī', 'event_scholar/3vz64HxVeOZOt5D.png', '[\"Dhikir\",\"Duas\",\"Comparative religion\",\"Hadith\",\"Dawah\",\"Salah\",\"Raising kids\"]', '2024-02-22 17:10:25', '2024-02-22 17:10:25'),
(51, 10, 32, 'Hafi', 'Mālikī', '', '[\"Dhikir\",\"Raising kids\",\"Finance\",\"Family law\"]', '2024-02-22 17:11:42', '2024-02-22 17:11:42'),
(52, 10, 33, 'Rahman', 'Ḥanbalī', '', '[\"Home Finance\",\"Relationship\",\"Duas\"]', '2024-02-22 17:11:42', '2024-02-22 17:11:42'),
(53, 11, 1, 'Numan', '', '', '[]', '2024-02-23 11:07:47', '2024-02-23 11:07:47'),
(54, 11, 3, 'Numan', '', '', '[]', '2024-02-23 11:07:47', '2024-02-23 11:07:47'),
(55, 11, 0, 'Safdar', 'Mālikī', 'event_scholar/EPiLtLrH69Dm9K6.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-23 11:07:47', '2024-02-23 11:07:47'),
(56, 11, 0, 'Zaheer', 'Mālikī', 'event_scholar/7fh0G3DhztIx5bC.png', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-23 11:07:47', '2024-02-23 11:07:47'),
(57, 11, 0, 'Usman', 'Mālikī', '', '[\"CSS\",\"PHP\",\"html\"]', '2024-02-23 11:07:47', '2024-02-23 11:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `experience_startDate` varchar(191) NOT NULL DEFAULT '',
  `experience_endDate` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `user_id`, `experience_startDate`, `experience_endDate`, `created_at`, `updated_at`) VALUES
(3, 11, '2021-02-15', '2024-02-15', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(5, 15, '2021-02-16', '2024-02-16', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(6, 18, '2024-02-19', '2024-02-19', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(7, 17, '2024-02-20', '2024-02-20', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(8, 25, '2024-02-20', '2024-02-20', '2024-02-20 18:25:02', '2024-02-20 18:25:02'),
(12, 32, '2024-02-05', '2024-02-20', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(13, 33, '2024-02-22', '2024-02-22', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(15, 22, '2023-02-23', '2024-02-23', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(16, 22, '2022-02-23', '2024-02-23', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(17, 19, '2022-02-23', '2024-02-23', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(19, 35, '2024-02-13', '2024-02-23', '2024-02-23 18:06:13', '2024-02-23 18:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_feed_backs`
--

CREATE TABLE `help_feed_backs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL DEFAULT '',
  `description` longtext NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `help_feed_backs`
--

INSERT INTO `help_feed_backs` (`id`, `user_id`, `email`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'numan.codes@gmail.com', 'Hi i am here', '2024-02-14 16:30:09', '2024-02-14 16:30:09'),
(3, 18, 'abdul@gmail.com', '.', '2024-02-19 11:37:28', '2024-02-19 11:37:28');

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `interest` varchar(191) NOT NULL DEFAULT '',
  `fiqa` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `user_id`, `interest`, `fiqa`, `created_at`, `updated_at`) VALUES
(4, 11, 'Family law', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(5, 11, 'Finance', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(6, 11, 'Marriage', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(7, 11, 'Dhikir', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(8, 11, 'Duas', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(20, 15, 'Salah', 'Shāfiʿī', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(19, 15, 'Finance', 'Shāfiʿī', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(18, 15, 'Family law', 'Shāfiʿī', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(21, 18, 'Finance', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(22, 18, 'Family Law', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(23, 18, 'Home Finance', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(24, 18, 'Marriage', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(25, 18, 'Dhikir', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(26, 18, 'Relationship', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(27, 17, 'Duas', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(28, 17, 'Raising Kids', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(29, 17, 'Parents', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(30, 17, 'Salah', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(31, 17, 'Comparative religion', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(32, 17, 'Dawah', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(33, 17, 'Quran', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(34, 17, 'Hadith', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(35, 25, 'Duas', 'Ḥanafī', '2024-02-20 18:25:02', '2024-02-20 18:25:02'),
(36, 25, 'Relationship', 'Ḥanafī', '2024-02-20 18:25:02', '2024-02-20 18:25:02'),
(37, 25, 'Marriage', 'Ḥanafī', '2024-02-20 18:25:02', '2024-02-20 18:25:02'),
(38, 25, 'Home Finance', 'Ḥanafī', '2024-02-20 18:25:03', '2024-02-20 18:25:03'),
(39, 25, 'Finance', 'Ḥanafī', '2024-02-20 18:25:03', '2024-02-20 18:25:03'),
(56, 32, 'Dhikir', 'Mālikī', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(57, 32, 'Raising kids', 'Mālikī', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(55, 32, 'Finance', 'Mālikī', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(54, 32, 'Family law', 'Mālikī', '2024-02-21 18:56:21', '2024-02-21 18:56:21'),
(60, 33, 'Home Finance', 'Ḥanbalī', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(59, 33, 'Relationship', 'Ḥanbalī', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(58, 33, 'Duas', 'Ḥanbalī', '2024-02-22 16:20:49', '2024-02-22 16:20:49'),
(69, 22, 'Raising kids', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(68, 22, 'Family law', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(67, 22, 'Finance', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(66, 22, 'Marriage', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(70, 22, 'Duas', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(71, 22, 'Dhikir', 'Ḥanbalī', '2024-02-23 15:53:53', '2024-02-23 15:53:53'),
(72, 22, 'Finance', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(73, 22, 'Marriage', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(74, 22, 'Family law', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(75, 22, 'Salah', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(76, 22, 'Raising kids', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(77, 22, 'Food', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(78, 22, 'Hadith', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(79, 19, 'Family law', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(80, 19, 'Marriage', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(81, 19, 'Finance', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(82, 19, 'Duas', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(83, 19, 'Salah', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(84, 19, 'Dawah', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(91, 35, 'Marriage', 'Ḥanbalī', '2024-02-23 18:06:13', '2024-02-23 18:06:13'),
(90, 35, 'Finance', 'Ḥanbalī', '2024-02-23 18:06:13', '2024-02-23 18:06:13'),
(89, 35, 'Duas', 'Ḥanbalī', '2024-02-23 18:06:13', '2024-02-23 18:06:13'),
(92, 35, 'Dhikir', 'Ḥanbalī', '2024-02-23 18:06:13', '2024-02-23 18:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_02_13_061339_create_help_feed_backs_table', 2),
(7, '2024_02_13_105234_create_muftis_table', 3),
(8, '2024_02_13_110543_create_degrees_table', 3),
(9, '2024_02_13_110736_create_experiences_table', 3),
(10, '2024_02_13_111603_create_interests_table', 3),
(11, '2024_02_14_073048_create_questions_table', 3),
(12, '2024_02_14_073640_create_admins_table', 3),
(13, '2024_02_14_075150_create_question_votes_table', 3),
(14, '2024_02_14_090654_create_question_comments_table', 3),
(15, '2024_02_14_102010_create_scholar_replies_table', 3),
(16, '2024_02_15_044532_create_private_questions_table', 4),
(17, '2024_02_15_100541_create_user_queries_table', 5),
(18, '2024_02_15_100551_create_user_all_queries_table', 5),
(19, '2024_02_16_050950_create_mufti_appointments_table', 6),
(20, '2024_02_17_190322_create_events_table', 7),
(21, '2024_02_17_191703_create_event_scholars_table', 7),
(22, '2024_02_19_075006_create_event_questions_table', 7),
(23, '2024_02_19_093530_create_save_events_table', 7),
(24, '2024_02_19_114437_add_column_to_table', 7),
(25, '2024_02_20_054538_add_column_to_table', 8),
(26, '2024_02_20_064421_create_notifications_table', 9),
(27, '2024_02_20_070417_add_column_to_table', 9),
(28, '2024_02_20_114558_create_activities_table', 10),
(29, '2024_02_20_132831_add_column_to_table', 10),
(30, '2024_02_21_073218_add_column_to_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `muftis`
--

CREATE TABLE `muftis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL DEFAULT '',
  `phone_number` varchar(191) NOT NULL DEFAULT '',
  `fiqa` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `muftis`
--

INSERT INTO `muftis` (`id`, `user_id`, `name`, `phone_number`, `fiqa`, `created_at`, `updated_at`) VALUES
(3, 11, 'Ali Syed', '03209418112', 'Ḥanafī', '2024-02-15 16:30:20', '2024-02-15 16:30:20'),
(5, 15, 'Ali Syed 111', '03209418112', 'Shāfiʿī', '2024-02-16 18:26:55', '2024-02-16 18:26:55'),
(6, 18, 'Abdul Rehman', '+923034241566', 'Shāfiʿī', '2024-02-19 12:36:22', '2024-02-19 12:36:22'),
(7, 17, 'Mlbtester Tester', '+103366666660', 'Shāfiʿī', '2024-02-20 15:10:11', '2024-02-20 15:10:11'),
(16, 22, 'Mufti tahir', '030454845484', 'Ḥanbalī', '2024-02-23 16:02:14', '2024-02-23 16:02:14'),
(17, 19, 'Taimoor', '030303030', 'Ḥanafī', '2024-02-23 16:50:44', '2024-02-23 16:50:44'),
(19, 35, 'Tahir', '0348464664', 'Ḥanbalī', '2024-02-23 18:06:13', '2024-02-23 18:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `mufti_appointments`
--

CREATE TABLE `mufti_appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mufti_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) NOT NULL DEFAULT '',
  `description` longtext NOT NULL DEFAULT '',
  `date` varchar(191) NOT NULL DEFAULT '',
  `duration` varchar(191) NOT NULL DEFAULT '',
  `consultation_fee` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_id` longtext NOT NULL DEFAULT '',
  `payment_method` varchar(191) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mufti_appointments`
--

INSERT INTO `mufti_appointments` (`id`, `user_id`, `mufti_id`, `category`, `description`, `date`, `duration`, `consultation_fee`, `created_at`, `updated_at`, `payment_id`, `payment_method`) VALUES
(1, 15, 11, 'Family law', 'Need to Talk about something', '2024-03-27 14:47:00', '45', '75', '2024-02-19 14:47:36', '2024-02-19 14:47:36', '', 'Stripe'),
(2, 1, 15, 'CSS', 'hi this is description', '2024-08-17 15:47:00', '35', '200', '2024-02-19 15:47:57', '2024-02-19 15:47:57', '', 'Stripe'),
(3, 11, 15, 'CSS', 'hi this is description', '2024-08-17 15:47:00', '35', '200', '2024-02-20 18:15:56', '2024-02-20 18:15:56', '', 'Stripe'),
(5, 2, 11, 'CSS', 'hi this is description', '2024-08-17 15:47:00', '35', '200', '2024-02-20 23:34:15', '2024-02-20 23:34:15', '', 'Stripe'),
(9, 20, 11, 'Family law', '.', '2024-02-21 00:28:00', '30', '50', '2024-02-21 00:29:18', '2024-02-21 00:29:18', '', 'Stripe');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL DEFAULT '',
  `body` longtext NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `created_at`, `updated_at`) VALUES
(1, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:15:47', '2024-02-20 17:15:47'),
(2, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:17:45', '2024-02-20 17:17:45'),
(3, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:18:23', '2024-02-20 17:18:23'),
(4, 15, 'Asked Question', 'User Ali Syed ALi wants to ask a question for you.', '2024-02-20 17:18:56', '2024-02-20 17:18:56'),
(5, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:19:24', '2024-02-20 17:19:24'),
(6, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:27:26', '2024-02-20 17:27:26'),
(7, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:27:27', '2024-02-20 17:27:27'),
(23, 28, 'Question Request Update', 'Your request for private question to Mufti Ali Syed has been accepted.', '2024-02-20 18:24:53', '2024-02-20 18:24:53'),
(9, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:34:07', '2024-02-20 17:34:07'),
(10, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:34:07', '2024-02-20 17:34:07'),
(22, 28, 'Question Request Sent', 'Your request for private question to Mufti Ali Syed has been sent.', '2024-02-20 18:22:27', '2024-02-20 18:22:27'),
(12, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:37:14', '2024-02-20 17:37:14'),
(13, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 17:37:14', '2024-02-20 17:37:14'),
(21, 11, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 18:22:27', '2024-02-20 18:22:27'),
(20, 15, 'Asked Question', 'User Muhammad Numan wants to ask a question for you.', '2024-02-20 18:22:26', '2024-02-20 18:22:26'),
(17, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-20 18:10:21', '2024-02-20 18:10:21'),
(18, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-20 18:10:21', '2024-02-20 18:10:21'),
(19, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-20 18:10:22', '2024-02-20 18:10:22'),
(24, 28, 'Question Request Update', 'Your request for private question to Mufti Ali Syed has been rejected.', '2024-02-20 18:25:04', '2024-02-20 18:25:04'),
(25, 15, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-20 21:58:39', '2024-02-20 21:58:39'),
(26, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-20 21:58:39', '2024-02-20 21:58:39'),
(72, 11, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:14:26', '2024-02-23 17:14:26'),
(28, 15, 'Asked Question', 'User Mlbench wants to ask a question for you.', '2024-02-21 00:31:03', '2024-02-21 00:31:03'),
(29, 25, 'Asked Question', 'User Mlbench wants to ask a question for you.', '2024-02-21 00:31:03', '2024-02-21 00:31:03'),
(30, 11, 'Asked Question', 'User Mlbench wants to ask a question for you.', '2024-02-21 00:31:04', '2024-02-21 00:31:04'),
(31, 15, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 09:48:42', '2024-02-21 09:48:42'),
(32, 25, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 09:48:42', '2024-02-21 09:48:42'),
(33, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 09:48:43', '2024-02-21 09:48:43'),
(34, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 09:59:51', '2024-02-21 09:59:51'),
(35, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 09:59:51', '2024-02-21 09:59:51'),
(36, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-21 09:59:51', '2024-02-21 09:59:51'),
(37, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:01:35', '2024-02-21 10:01:35'),
(38, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:01:35', '2024-02-21 10:01:35'),
(39, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-21 10:01:36', '2024-02-21 10:01:36'),
(40, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:11:33', '2024-02-21 10:11:33'),
(41, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:11:33', '2024-02-21 10:11:33'),
(42, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-21 10:11:34', '2024-02-21 10:11:34'),
(43, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:13:08', '2024-02-21 10:13:08'),
(44, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:13:09', '2024-02-21 10:13:09'),
(45, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-21 10:13:09', '2024-02-21 10:13:09'),
(46, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:17:22', '2024-02-21 10:17:22'),
(47, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:17:22', '2024-02-21 10:17:22'),
(48, 25, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-21 10:17:22', '2024-02-21 10:17:22'),
(49, 15, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:19:25', '2024-02-21 10:19:25'),
(50, 25, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:19:26', '2024-02-21 10:19:26'),
(51, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:19:26', '2024-02-21 10:19:26'),
(52, 15, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:22:17', '2024-02-21 10:22:17'),
(53, 25, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:22:18', '2024-02-21 10:22:18'),
(54, 11, 'Asked Question', 'User Sani wants to ask a question for you.', '2024-02-21 10:22:18', '2024-02-21 10:22:18'),
(55, 15, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:23:59', '2024-02-21 10:23:59'),
(56, 25, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:23:59', '2024-02-21 10:23:59'),
(57, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-21 10:23:59', '2024-02-21 10:23:59'),
(61, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 11:40:30', '2024-02-22 11:40:30'),
(59, 11, 'Asked Question', 'User Numan wants to ask a question for you.', '2024-02-21 15:33:01', '2024-02-21 15:33:01'),
(60, 5, 'Question Request Sent', 'Your request for private question to Mufti Shair Ali has been sent.', '2024-02-21 15:33:01', '2024-02-21 15:33:01'),
(62, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 11:40:30', '2024-02-22 11:40:30'),
(71, 11, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:14:25', '2024-02-23 17:14:25'),
(64, 15, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 16:15:23', '2024-02-22 16:15:23'),
(65, 25, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 16:15:23', '2024-02-22 16:15:23'),
(66, 32, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 16:15:23', '2024-02-22 16:15:23'),
(67, 11, 'Asked Question', 'User Tahir wants to ask a question for you.', '2024-02-22 16:15:24', '2024-02-22 16:15:24'),
(68, 25, 'Asked Question', 'User Rahman wants to ask a question for you.', '2024-02-22 16:26:47', '2024-02-22 16:26:47'),
(69, 11, 'Asked Question', 'User Rahman wants to ask a question for you.', '2024-02-22 16:26:47', '2024-02-22 16:26:47'),
(70, 33, 'Question Request Sent', 'Your request for private question to Mufti Sani has been sent.', '2024-02-22 16:26:48', '2024-02-22 16:26:48'),
(73, 22, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-23 17:14:26', '2024-02-23 17:14:26'),
(74, 15, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:15:01', '2024-02-23 17:15:01'),
(75, 25, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:15:01', '2024-02-23 17:15:01'),
(76, 32, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:15:02', '2024-02-23 17:15:02'),
(77, 33, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:15:02', '2024-02-23 17:15:02'),
(78, 11, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:15:03', '2024-02-23 17:15:03'),
(79, 11, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:47:58', '2024-02-23 17:47:58'),
(80, 11, 'Asked Question', 'User Mufti tahir wants to ask a question for you.', '2024-02-23 17:47:59', '2024-02-23 17:47:59'),
(81, 22, 'Question Request Sent', 'Your request for private question to Mufti Mufti Qazi has been sent.', '2024-02-23 17:48:00', '2024-02-23 17:48:00'),
(82, 22, 'Question Request Update', 'Your request for private question to Mufti Mufti Qazi has been accepted.', '2024-02-23 17:49:06', '2024-02-23 17:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_questions`
--

CREATE TABLE `private_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mufti_id` bigint(20) UNSIGNED NOT NULL,
  `question` longtext NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 for pending,1 for accepted,2 for rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `question_category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`question_category`)),
  `question` longtext NOT NULL DEFAULT '',
  `time_limit` varchar(191) NOT NULL DEFAULT '',
  `voting_option` int(11) NOT NULL DEFAULT 0,
  `user_info` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `question_category`, `question`, `time_limit`, `voting_option`, `user_info`, `created_at`, `updated_at`) VALUES
(14, 2, '[\"web\",\"html\",\"CSS\"]', 'Hi How are you', '08-07-09 03:50:45', 0, 0, '2024-02-17 17:49:11', '2024-02-17 17:49:11'),
(13, 2, '[\"web\",\"html\",\"CSS\"]', 'What is the significance of the Hijra in Islamic history?', '2026-02-16 15:31:45', 0, 0, '2024-02-16 15:33:38', '2024-02-16 15:33:38'),
(12, 2, '[\"web\",\"html\",\"CSS\"]', 'What are the Five Pillars of Islam, and can you briefly explain each one?', '2026-02-16 15:31:45', 0, 0, '2024-02-16 15:33:01', '2024-02-16 15:33:01'),
(11, 11, '[\"Comparative religion\",\"Hadith\"]', 'Is the current time valid?', '2026-02-16 15:31:45', 1, 0, '2024-02-16 15:31:47', '2024-02-16 15:31:47'),
(15, 18, '[\"Parents\",\"Duas\",\"Comparative religion\"]', 'how to pray Namaz', '2026-02-19 16:43:43', 1, 1, '2024-02-19 16:43:43', '2024-02-19 16:43:43'),
(16, 20, '[\"Family Law\",\"Finance\",\"Home Finance\",\"Marriage\",\"Relationship\",\"Dhikir\",\"Duas\",\"Raising Kids\",\"Competitive religion\"]', 'How are You', '2026-02-20 15:33:48', 1, 1, '2024-02-20 15:33:51', '2024-02-20 15:33:51'),
(17, 20, '[\"Salah\"]', 'what is the number count of namaz during traveling?', '2026-02-20 17:40:57', 1, 0, '2024-02-20 17:41:00', '2024-02-20 17:41:00'),
(18, 25, '[\"Family Law\",\"Duas\",\"Raising Kids\"]', 'Total nimaz', '2026-02-21 09:46:58', 2, 0, '2024-02-21 09:47:00', '2024-02-21 09:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `question_comments`
--

CREATE TABLE `question_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` longtext NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_comments`
--

INSERT INTO `question_comments` (`id`, `question_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(2, 11, 2, 'This is nice question', '2024-02-16 15:50:38', '2024-02-16 15:50:38'),
(3, 13, 11, 'Sample reply', '2024-02-16 15:54:39', '2024-02-16 15:54:39'),
(4, 13, 11, 'This is the sample answer.', '2024-02-16 16:51:37', '2024-02-16 16:51:37'),
(5, 13, 2, 'This is nice question', '2024-02-19 15:03:34', '2024-02-19 15:03:34'),
(6, 13, 18, 'hey', '2024-02-19 17:33:03', '2024-02-19 17:33:03'),
(7, 14, 1, 'This is nice question', '2024-02-20 11:12:17', '2024-02-20 11:12:17'),
(8, 15, 11, 'first you should do ablution', '2024-02-20 11:32:34', '2024-02-20 11:32:34'),
(9, 15, 11, '.', '2024-02-20 11:36:01', '2024-02-20 11:36:01'),
(10, 12, 11, '.', '2024-02-20 11:37:33', '2024-02-20 11:37:33'),
(11, 13, 11, '.', '2024-02-20 11:44:56', '2024-02-20 11:44:56'),
(12, 12, 11, '.', '2024-02-20 11:45:44', '2024-02-20 11:45:44'),
(13, 13, 11, '.', '2024-02-20 11:49:43', '2024-02-20 11:49:43'),
(14, 13, 11, '.', '2024-02-20 11:51:08', '2024-02-20 11:51:08'),
(15, 12, 11, '.', '2024-02-20 11:54:57', '2024-02-20 11:54:57'),
(16, 12, 11, 'good question', '2024-02-20 11:59:27', '2024-02-20 11:59:27'),
(17, 15, 14, 'You needs to do ablution before the namaz', '2024-02-20 17:32:37', '2024-02-20 17:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `question_votes`
--

CREATE TABLE `question_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vote` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_votes`
--

INSERT INTO `question_votes` (`id`, `question_id`, `user_id`, `vote`, `created_at`, `updated_at`) VALUES
(8, 11, 2, 1, '2024-02-16 16:24:21', '2024-02-16 16:24:21'),
(12, 11, 11, 1, '2024-02-16 17:56:14', '2024-02-16 17:56:23'),
(10, 13, 11, 1, '2024-02-16 17:04:26', '2024-02-20 16:53:09'),
(9, 12, 14, 1, '2024-02-16 16:40:54', '2024-02-16 16:40:56'),
(13, 12, 11, 2, '2024-02-16 17:56:18', '2024-02-20 16:53:17'),
(14, 13, 15, 1, '2024-02-16 19:09:33', '2024-02-16 19:09:33'),
(15, 13, 18, 1, '2024-02-19 16:08:45', '2024-02-19 16:42:37'),
(16, 12, 18, 1, '2024-02-19 16:42:41', '2024-02-19 16:42:41'),
(17, 11, 18, 2, '2024-02-19 16:42:44', '2024-02-19 16:42:44'),
(18, 15, 18, 1, '2024-02-19 16:44:35', '2024-02-19 16:44:37'),
(19, 13, 1, 1, '2024-02-20 11:08:22', '2024-02-20 11:08:22'),
(20, 13, 20, 2, '2024-02-20 16:31:17', '2024-02-21 00:27:40'),
(21, 12, 20, 1, '2024-02-20 16:31:24', '2024-02-20 16:31:26'),
(22, 15, 20, 1, '2024-02-20 16:31:35', '2024-02-21 00:27:26'),
(23, 16, 20, 2, '2024-02-20 16:31:46', '2024-02-21 00:27:16'),
(24, 16, 11, 1, '2024-02-20 16:42:45', '2024-02-20 16:42:56'),
(25, 16, 14, 1, '2024-02-20 17:31:29', '2024-02-20 17:31:31'),
(26, 17, 28, 2, '2024-02-20 19:28:20', '2024-02-20 19:28:25'),
(27, 13, 28, 1, '2024-02-20 19:29:08', '2024-02-20 19:29:11'),
(28, 13, 31, 1, '2024-02-21 00:23:44', '2024-02-21 00:24:04'),
(29, 12, 31, 1, '2024-02-21 00:23:56', '2024-02-21 00:23:56'),
(30, 17, 20, 1, '2024-02-21 00:27:08', '2024-02-21 00:27:08');

-- --------------------------------------------------------

--
-- Table structure for table `save_events`
--

CREATE TABLE `save_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `save_events`
--

INSERT INTO `save_events` (`id`, `event_id`, `user_id`, `created_at`, `updated_at`) VALUES
(9, 9, 22, '2024-02-23 17:19:23', '2024-02-23 17:19:23'),
(8, 9, 11, '2024-02-23 10:58:51', '2024-02-23 10:58:51'),
(7, 4, 19, '2024-02-22 19:37:39', '2024-02-22 19:37:39');

-- --------------------------------------------------------

--
-- Table structure for table `scholar_replies`
--

CREATE TABLE `scholar_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reply` longtext NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scholar_replies`
--

INSERT INTO `scholar_replies` (`id`, `question_id`, `user_id`, `reply`, `created_at`, `updated_at`) VALUES
(2, 13, 11, 'I am replying as a scholar.', '2024-02-16 16:52:13', '2024-02-16 16:52:13'),
(4, 13, 2, 'This is good question', '2024-02-19 16:48:13', '2024-02-19 16:48:13'),
(5, 11, 11, 'No', '2024-02-20 11:59:56', '2024-02-20 11:59:56'),
(6, 12, 2, 'This is good question', '2024-02-20 12:03:28', '2024-02-20 12:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL DEFAULT '',
  `email` varchar(191) NOT NULL DEFAULT '',
  `password` varchar(191) NOT NULL DEFAULT '',
  `image` varchar(191) NOT NULL DEFAULT '',
  `phone_number` varchar(191) NOT NULL DEFAULT '',
  `fiqa` varchar(191) NOT NULL DEFAULT '',
  `mufti_status` int(11) NOT NULL DEFAULT 0 COMMENT '1 for pending,2 for accepted,3 for rejected',
  `user_type` varchar(191) NOT NULL DEFAULT 'user',
  `device_id` longtext NOT NULL DEFAULT '',
  `a_code` longtext NOT NULL DEFAULT '',
  `g_code` longtext NOT NULL DEFAULT '',
  `email_code` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `phone_number`, `fiqa`, `mufti_status`, `user_type`, `device_id`, `a_code`, `g_code`, `email_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Numan', 'numan@gmail.com', '$2y$12$UrTDihB.9rLRuFMmqP7KteUVntkx7RZmIGwqgBtD1uVazkb12ifem', '', '', '', 0, 'user', '13', '', '', 0, '2024-02-12 17:25:26', '2024-02-20 17:58:26', NULL),
(2, 'Numan', 'numan.codes@gmail.com', '$2y$12$UvLkUiK90tQSsf7IXfiwMOKic6.ckP4b1cQdwvTYNBGqwmaoyPspm', 'users_profile/2xexTy5QXMzecE2.png', '', '', 0, 'user', '', '', '', 0, '2024-02-12 20:22:16', '2024-02-14 15:39:07', NULL),
(3, 'Numan', 'numang@gmail.com', '', '', '', '', 0, 'user', '12344353', '', '9384sjjl', 0, '2024-02-13 11:19:55', '2024-02-14 17:56:56', NULL),
(5, 'Numan', 'numangg@gmail.com', '', '', '', '', 0, 'user', '12344353', '9384sjjl', '', 0, '2024-02-13 14:46:59', '2024-02-13 14:46:59', NULL),
(11, 'Mufti Qazi', 'alisyedali000@gmail.com', '$2y$12$n.uaPLRmca0SXMUFMTdPc.tx14hAxrjLSVTKIxACdh6.rytQ.eWC6', 'users_profile/51afC3FVYJt8vnZ.png', '0890488202', 'Ḥanafī', 2, 'scholar', 'doiriDRaQdm-xwN1o_2crv:APA91bFsmV_a9-H5OecvsukkgD3mu_F39pItK9YFxlLDy2JC9u4ZdPP1prKlNtcdHwWcqcYJ4YEE0cCV_i9GmMlrKNsdujuAX7iED0Wv_UDYuQV27NAt_uQM8VnJGVsBqOriEzJLj_dN', '', '', 0, '2024-02-15 16:29:24', '2024-02-23 17:48:52', NULL),
(15, 'Ali Syed', 'Alisyedali111@gmail.com', '$2y$12$hiU.Jujh7L4fml/bE1zXyOSKN44s.sGFBRW0EyJvTTs7vMdHqbVtW', '', '0904343535', 'Shāfiʿī', 2, 'scholar', '', '', '', 0, '2024-02-16 18:25:33', '2024-02-21 09:31:07', NULL),
(7, 'Numan', 'numan123@gmail.com', '$2y$12$z5ntlfwIbZbokjnxQxWf0u7KPdJKp6LdOH.LboGzveM5ajywhm37W', '', '', '', 0, 'user', '', '', '', 0, '2024-02-15 10:48:22', '2024-02-15 10:48:22', NULL),
(8, 'Shair', 'shair@gmail.com', '$2y$12$j7LfLnICykYy4C.W/mvlBOux4xLbKTymp9neNaoKblggEgCJZQgKS', '', '', '', 0, 'user', '', '', '', 0, '2024-02-15 10:50:34', '2024-02-15 10:50:34', NULL),
(9, 'Rehman', 'rehman@gmail.com', '$2y$12$K4zYsTvfQJW1KThjq31JP.CoeNc.CmjEFxyfB2d5ybQqru35Yoj1S', '', '', '', 0, 'user', '', '', '', 0, '2024-02-15 10:51:01', '2024-02-15 10:51:01', NULL),
(10, 'Rehan', 'rehan@gmail.com', '$2y$12$R7gOQMZB6keUY19CHp604.3rUxqDQHtiT2wdkzsB1.iNvCe7/YKFe', '', '', '', 0, 'user', '', '', '', 0, '2024-02-15 10:51:18', '2024-02-15 10:51:18', NULL),
(12, 'Shair Ali', 'qaziammar.g@gmail.com', '', 'users_profile/nsVbu4R7SNnWzg2.png', '0333365844', 'Mālikī', 2, 'scholar', 'cPZjFS1Hi0ZOpkIx-fiP3f:APA91bFLQP28L7q5-HwVs5qztBMeMwbj5lSW8hZzROSPQ0x-VH4x8i2M2XicSrVu1hJkv3wkRsLKshtI2OnuWR8wFDru9rJKxypJIUNSh9HW8K2MEqS1qo60TER9tPxcgaLjrA35g9v-', '', 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImVkODA2ZjE4NDJiNTg4MDU0YjE4YjY2OWRkMWEwOWE0ZjM2N2FmYzQiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDk4MjIzMzQ5MzQ0NzY3ODA0MjUiLCJlbWFpbCI6InFhemlhbW1hci5nQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiNWxUQng3NFVWTk45dDlJcC13Q2hKQSIsIm5vbmNlIjoicHEySk1TVFRIRWVQWU5fc2dSYVZLeDVyZkwzbmpHUFRudkUtWnE2eE4xMCIsIm5hbWUiOiJRYXppIEFtbWFyIEFyc2hhZCIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NMd2x5OEZiYm03dzNJajVsYjNEamMzUU0yVkJxR2FtSXA4SkduX0l3bmtod0k9czk2LWMiLCJnaXZlbl9uYW1lIjoiUWF6aSIsImZhbWlseV9uYW1lIjoiQW1tYXIgQXJzaGFkIiwibG9jYWxlIjoiZW4iLCJpYXQiOjE3MDc5OTk5NzgsImV4cCI6MTcwODAwMzU3OH0.DFx0MUV7iC14OUXiyPrpudnRkyFHxF6ubyGkoOIJ_mRWuvBBxWdWFpIL751delNaHv8jdGlwU62KkPMtKTfjL54yoUjgoWlSNofO8pbITI4VeaRFXbH3bBEUMKRNB4dxraCh0ehLGMvUH2OvPfyVQ4M0GWo6geOIM0NT7B0Zf2w56Bwq03utAIoIdvTOgT3Jdxan0Mtw46ebyT5cJro9jkUKXwigkscEQgh6s9MCA8EkGQPArdHjpnjyzMMAn0qnu0IRVAElU5efFYO5ob-3WoR2q9IzuVfVUiF_5jINlvK3NbC-WJ6H2eYpGmDcAcN8SwyWwGSpbK3bP4timFRGjQ', 0, '2024-02-15 17:26:20', '2024-02-21 18:52:35', '2024-02-21 18:52:35'),
(13, 'Sani', 'Sani@gmail.com', '$2y$12$ZKn8gGK0EbUiQrMPP1sV6eZwe8Zo72apEektN20waOk74GD8/SQp6', '', '', '', 0, 'user', '', '', '', 0, '2024-02-16 10:33:01', '2024-02-16 10:33:01', NULL),
(14, 'Tahir', 'Tahir@gmail.com', '$2y$12$BH3V6pTqQjgf7ZFAP5raTOjRQfCduulDdidbCsGDWYI9GYJJgG4Fq', 'users_profile/6j2ul6SZW1LRb8S.png', '', '', 0, 'user', '', '', '', 0, '2024-02-16 15:40:20', '2024-02-21 09:27:49', NULL),
(16, 'Ali', 'Sanig@gmail.com', '$2y$12$R50/M7Yb0ZPW3EZ7NDGEH.KWsqeG5JhmuSvnNOay4m1klHFwdB3h6', '', '', '', 0, 'user', '', '', '', 0, '2024-02-17 22:42:10', '2024-02-17 22:42:10', NULL),
(20, 'Mlbench', 'mlbench12@gmail.com', '', '', '', '', 0, 'user', '', '', '117607073455573954697', 0, '2024-02-20 15:14:21', '2024-02-23 17:48:34', NULL),
(18, 'Rehman Abdul', 'abdulrehman4241566@gmail.com', '$2y$12$ArXDXRs1rsdKO2xpssOuZeaBPjni..9YBitH2g3Xji.atYs09DSmC', '', '', '', 1, 'user', 'dUcwn1s2Tzidi46aSFZ0pB:APA91bEevHIzskAAJ8yQGnYLul-gXBdwZ9L4bThpEzefqvRa_kGpSyRdgeRrmziPeb_Q7Za1MsaGloGgZaKIFoN-TtSb6ehDHIJdllTLc1IHRraFrGt-zim5L-q9-Og5icaGP_Y5_lpB', '', '', 0, '2024-02-19 11:02:48', '2024-02-22 18:06:35', NULL),
(19, 'Taimoor Arif', 'tamoorarif20@gmail.com', '', 'users_profile/bPwKygAc8GGlzCZ.png', '', '', 1, 'user', 'eCRbZxVM8EhWnQZFFKhahy:APA91bFpUNuSDH75EyVxOJdYF0sQwJmUqgHkhXEfMMw6aYOntoKfA2coaHlJWJcSPHDdgVlQGsUE0-msdMri-h6K1oq3E8rziE45Ll8KOv3eVaYfZn0goZweirc9bV-Rnhj0Z1hEdy-s', '', 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjU1YzE4OGE4MzU0NmZjMTg4ZTUxNTc2YmE3MjgzNmUwNjAwZThiNzMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTI4OTA4MTIzMjc5MDQxNTYyOTciLCJlbWFpbCI6InRhbW9vcmFyaWYyMEBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXRfaGFzaCI6IlZsZHFXZXlNZkVzNi1PYWJQWG5PNkEiLCJub25jZSI6IjhLdXZsSlJzMU5ra2xyV3dXUnlJY2k2elBFWDVkZ2RIeS1NeXRsN1J6QlUiLCJuYW1lIjoiVGFpbW9vciBBcmlmIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0xlVlRidGt6MUtiLTFUZkFhWlNXTDFQX1Z3Ty1Bd0hSSWV6U2FWLVpLMj1zOTYtYyIsImdpdmVuX25hbWUiOiJUYWltb29yIiwiZmFtaWx5X25hbWUiOiJBcmlmIiwibG9jYWxlIjoiZW4iLCJpYXQiOjE3MDg0MTM3NjMsImV4cCI6MTcwODQxNzM2M30.UvgcSYlN5ha_XLUCWgafdJI8kI6vtblvKZzg77gKozgd3-2CyoiizTwPxV_H41ibGEGYpl3oaZl6HKftJXQBnAmRdzUAUiMt8nEYrbje1SR-PlOhxAWBQkFCTQKKvQsz7waI2R4f7X3VBi7RHDNQiTtRJgU--4bX7-4uuf-r3n7NdB-Taa77ngi0U-UHtRNLIIACrEAgvWcsRuHcKmMkse2nB3yR4EVqo8DMr5Vn5zem8x25O0YOzRAuVh-RNkmCxnLTvSrX9HrG_4ATe_fMIq5Tj0O6Ae3wwPYxkLFX8tlBGj72fHTXKCvOmeCzflXSmlHYyZsAfB6VUKNoOq1CjA', 0, '2024-02-20 12:22:44', '2024-02-23 16:50:44', NULL),
(33, 'Rahman', 'hari@gmail.com', '$2y$12$6gGRGftSzvtJHRyXDIk0CuszC0r38WrtXHYGn00RZJFnk.PPVagBG', '', '+923454545123', 'Ḥanbalī', 2, 'scholar', '', '', '', 0, '2024-02-22 16:18:24', '2024-02-22 16:36:53', NULL),
(22, 'Mufti tahir', 'sani123@gmail.com', '$2y$12$XN6.uxdwK4rTOTKpkMEf4uGMj.DlsqamfeliS86wjKnc1FQFTzBG6', 'users_profile/Rapc42Qaw01axz7.png', '030303030303', 'Ḥanbalī', 0, 'scholar', 'cPZjFS1Hi0ZOpkIx-fiP3f:APA91bFLQP28L7q5-HwVs5qztBMeMwbj5lSW8hZzROSPQ0x-VH4x8i2M2XicSrVu1hJkv3wkRsLKshtI2OnuWR8wFDru9rJKxypJIUNSh9HW8K2MEqS1qo60TER9tPxcgaLjrA35g9v-', '', '', 0, '2024-02-20 17:59:27', '2024-02-23 17:46:55', NULL),
(23, 'Ali', 'sani12@gmail.com', '$2y$12$FxLzfaWFoqm/ilVn2LzpLOL7HgST5MoqddegkpLO067Co0hRTjhXi', '', '0333255654', 'Shāfiʿī', 2, 'scholar', 'cPZjFS1Hi0ZOpkIx-fiP3f:APA91bFLQP28L7q5-HwVs5qztBMeMwbj5lSW8hZzROSPQ0x-VH4x8i2M2XicSrVu1hJkv3wkRsLKshtI2OnuWR8wFDru9rJKxypJIUNSh9HW8K2MEqS1qo60TER9tPxcgaLjrA35g9v-', '', '', 0, '2024-02-20 18:00:23', '2024-02-23 15:48:47', '2024-02-23 15:48:47'),
(24, 'Tahir', 'sani2@gmail.com', '$2y$12$.XidjcS/9Y0IhyUdunhvuOYlxdajIJ5eW8HfrcWtGlwOcS4gR5nBG', '', '', '', 0, 'user', 'doiriDRaQdm-xwN1o_2crv:APA91bFsmV_a9-H5OecvsukkgD3mu_F39pItK9YFxlLDy2JC9u4ZdPP1prKlNtcdHwWcqcYJ4YEE0cCV_i9GmMlrKNsdujuAX7iED0Wv_UDYuQV27NAt_uQM8VnJGVsBqOriEzJLj_dN', '', '', 0, '2024-02-20 18:00:57', '2024-02-20 18:00:57', NULL),
(25, 'Sani', 'sani21@gmail.com', '$2y$12$dTRmuwnYBgDYUs.ba/zjYOcqhI9qwUW.FdLmu.AvjrdQwGT36CUMK', '', '+923333375164', 'Ḥanafī', 2, 'scholar', '', '', '', 0, '2024-02-20 18:01:23', '2024-02-21 15:05:45', NULL),
(27, 'ali', 'ali12@gmail.com', '$2y$12$FIU20DAfu/d6/QsJBcWQYOTiC3h9gspL/.mjLEqVi/DqPBbnpE04e', '', '', '', 0, 'user', 'dUcwn1s2Tzidi46aSFZ0pB:APA91bEevHIzskAAJ8yQGnYLul-gXBdwZ9L4bThpEzefqvRa_kGpSyRdgeRrmziPeb_Q7Za1MsaGloGgZaKIFoN-TtSb6ehDHIJdllTLc1IHRraFrGt-zim5L-q9-Og5icaGP_Y5_lpB', '', '', 0, '2024-02-20 18:19:09', '2024-02-20 18:19:09', NULL),
(29, 'Hamad', 'ali.codes@gmail.com', '$2y$12$bn4dnBE6luXV.cfarNBWyuMx3M9v5PbqgegwcifxPWHbhmmUEL7CS', '', '', '', 0, 'user', '11', '', '', 0, '2024-02-20 18:41:41', '2024-02-20 18:41:41', NULL),
(28, 'Muhammad Numan', '5125.2019.gct@gmail.com', '', '', '', '', 0, 'user', 'c-W1iFNXSsyGfAMMteW9zi:APA91bEJ00lABX3zm9aA9SBzTRoAXe8pvSLkVy-7PDCzupaNoE8ba4m98dnF8tB9MNIslNJIewg9c343COgbDmyDT3G8AUO07omKwBqi_oFYyW_MXZIEG5hcegntOR9EwT8q059c4006', '', '103536130319291032501', 0, '2024-02-20 18:20:28', '2024-02-20 19:30:45', NULL),
(31, 'abdullah', 'abdul@gmail.com', '$2y$12$PFnpzGC2pS7.5idHSfEJWOd5NQGT0GET/610kemx5FsqiXyd1NHeW', '', '', '', 0, 'user', '', '', '', 0, '2024-02-21 00:22:59', '2024-02-21 00:25:23', NULL),
(32, 'Hafi', 'qaziammar.g@gmail.com', '', '', '033333333', 'Mālikī', 2, 'scholar', 'cPZjFS1Hi0ZOpkIx-fiP3f:APA91bFLQP28L7q5-HwVs5qztBMeMwbj5lSW8hZzROSPQ0x-VH4x8i2M2XicSrVu1hJkv3wkRsLKshtI2OnuWR8wFDru9rJKxypJIUNSh9HW8K2MEqS1qo60TER9tPxcgaLjrA35g9v-', '', 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjU1YzE4OGE4MzU0NmZjMTg4ZTUxNTc2YmE3MjgzNmUwNjAwZThiNzMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI2NzAyMDk4NjAxNTAtZWM1amEwcmZhcm9sdjduZHRsdTIzaDcwcW92OG9rbXEuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDk4MjIzMzQ5MzQ0NzY3ODA0MjUiLCJlbWFpbCI6InFhemlhbW1hci5nQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJhdF9oYXNoIjoiaGFDNENqM2xiM05STGs0UUdJQi00ZyIsIm5vbmNlIjoidlBxSUhsY2FUYWNxRjloX3hoNFRJSkdheDh4X000QVBreDNBWnBsNG94TSIsIm5hbWUiOiJRYXppIEFtbWFyIEFyc2hhZCIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NMd2x5OEZiYm03dzNJajVsYjNEamMzUU0yVkJxR2FtSXA4SkduX0l3bmtod0k9czk2LWMiLCJnaXZlbl9uYW1lIjoiUWF6aSIsImZhbWlseV9uYW1lIjoiQW1tYXIgQXJzaGFkIiwibG9jYWxlIjoiZW4iLCJpYXQiOjE3MDg1MjM3MDYsImV4cCI6MTcwODUyNzMwNn0.UiGYb9dXDgg1jMxOSvMypcFxXc4x9_8Xq2EuzlHZv6qC9nyLaS6U0KZwwHFThClM-lPHKIdu-bblNUcL3JB5cq6C8VhOmUzOrDNodyDu3Dp_0bBSOS63uNCvJFG204VFUMLTfxMTPxZ06uSEW0ZxnFTkqUdh9wcuYtVa4dB7WCpe8yzp2UejujKkg8eVxMztTmlG2b4W98l7Wz8QZ1euy-qbS4B3hjqmhzBXi9kq6nwvjBuhCko6IYNiMoA3O7D9Vj1CZPMSQ6IUXe7yTmKwU7tnGFRV8CFOj_hDSvoe9a54V9PttdV6v6CY911mM_HM44XXHCd4Qzd-cjznsVk3mg', 0, '2024-02-21 18:55:07', '2024-02-21 18:57:03', NULL),
(35, '', '', '', '', '', '', 1, 'user', 'cPZjFS1Hi0ZOpkIx-fiP3f:APA91bFLQP28L7q5-HwVs5qztBMeMwbj5lSW8hZzROSPQ0x-VH4x8i2M2XicSrVu1hJkv3wkRsLKshtI2OnuWR8wFDru9rJKxypJIUNSh9HW8K2MEqS1qo60TER9tPxcgaLjrA35g9v-', '000482.ea191cbff187444f84b2605a99effb74.1053', '', 0, '2024-02-23 17:55:39', '2024-02-23 18:06:13', NULL),
(34, '', '', '', '', '', '', 0, 'user', 'eCRbZxVM8EhWnQZFFKhahy:APA91bFpUNuSDH75EyVxOJdYF0sQwJmUqgHkhXEfMMw6aYOntoKfA2coaHlJWJcSPHDdgVlQGsUE0-msdMri-h6K1oq3E8rziE45Ll8KOv3eVaYfZn0goZweirc9bV-Rnhj0Z1hEdy-s', '000062.ee3831417f294142855693fae6b563a6.0802', '', 0, '2024-02-23 17:42:00', '2024-02-23 17:42:00', NULL),
(36, '', '', '', '', '', '', 0, 'user', 'ezw7pHpjlkrVhOGq1D7jTf:APA91bFDYKwwrRZdoHD7-v9Y9q6HXHGA85Acl5fiKQ_Mm33MoJ8U7eB7ZcA2Gi5LEPjUQNr1xt7DTjGvdnlCDl_yzQzVY4z-1kZEPTNPaDDy7QoGOJcxg_E3ro3-FnmGrmlxmiyCS9o_', '001085.65f3b95e751f405ba98c06f694d3b918.0701', '', 0, '2024-02-23 21:38:14', '2024-02-23 21:38:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_all_queries`
--

CREATE TABLE `user_all_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `query_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mufti_id` bigint(20) UNSIGNED NOT NULL,
  `question` longtext NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 for pending,1 for accepted,2 for rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_all_queries`
--

INSERT INTO `user_all_queries` (`id`, `query_id`, `user_id`, `mufti_id`, `question`, `status`, `created_at`, `updated_at`) VALUES
(10, 6, 2, 15, 'Hi How are you', 2, '2024-02-16 18:41:11', '2024-02-19 10:42:49'),
(9, 6, 2, 11, 'Hi How are you', 1, '2024-02-16 18:41:11', '2024-02-20 14:47:38'),
(8, 5, 2, 15, 'Hi Mufti G', 1, '2024-02-16 18:39:20', '2024-02-19 12:53:13'),
(7, 5, 2, 11, 'Hi Mufti G', 0, '2024-02-16 18:39:20', '2024-02-20 15:06:21'),
(11, 7, 1, 15, 'Hi How are you', 1, '2024-02-19 12:12:57', '2024-02-19 14:04:01'),
(12, 7, 1, 7, 'Hi How are you', 0, '2024-02-19 12:12:57', '2024-02-19 12:12:57'),
(13, 8, 15, 11, 'Is your queries visible now?', 0, '2024-02-19 17:31:32', '2024-02-19 17:31:32'),
(14, 8, 15, 3, 'Is your queries visible now?', 0, '2024-02-19 17:31:32', '2024-02-19 17:31:32'),
(15, 9, 8, 11, 'Hi How are you', 0, '2024-02-20 10:11:49', '2024-02-20 10:11:49'),
(16, 9, 8, 3, 'Hi How are you', 0, '2024-02-20 10:11:49', '2024-02-20 10:11:49'),
(17, 10, 11, 15, '...', 0, '2024-02-20 10:12:37', '2024-02-20 10:12:37'),
(18, 10, 11, 3, '...', 0, '2024-02-20 10:12:37', '2024-02-20 10:12:37'),
(19, 11, 11, 15, '.', 0, '2024-02-20 10:18:14', '2024-02-20 10:18:14'),
(20, 11, 11, 3, '.', 0, '2024-02-20 10:18:14', '2024-02-20 10:18:14'),
(21, 12, 11, 15, '.', 0, '2024-02-20 10:25:10', '2024-02-20 10:25:10'),
(22, 12, 11, 3, '.', 0, '2024-02-20 10:25:10', '2024-02-20 10:25:10'),
(23, 13, 11, 15, '.', 0, '2024-02-20 10:26:00', '2024-02-20 10:26:00'),
(24, 13, 11, 3, '.', 0, '2024-02-20 10:26:00', '2024-02-20 10:26:00'),
(25, 14, 11, 15, 'hello', 0, '2024-02-20 10:27:43', '2024-02-20 10:27:43'),
(26, 14, 11, 3, 'hello', 0, '2024-02-20 10:27:43', '2024-02-20 10:27:43'),
(27, 15, 11, 15, 'hello', 0, '2024-02-20 10:27:56', '2024-02-20 10:27:56'),
(28, 15, 11, 3, 'hello', 0, '2024-02-20 10:27:56', '2024-02-20 10:27:56'),
(29, 16, 2, 3, 'Hi Mufti G', 0, '2024-02-20 10:31:06', '2024-02-20 10:31:06'),
(30, 17, 11, 3, '.', 0, '2024-02-20 10:32:28', '2024-02-20 10:32:28'),
(40, 27, 28, 11, 'Islam', 0, '2024-02-20 18:22:27', '2024-02-20 18:22:27'),
(39, 27, 28, 15, 'Islam', 2, '2024-02-20 18:22:27', '2024-02-20 18:25:04'),
(37, 26, 25, 11, 'Proper way of wasu', 0, '2024-02-20 18:10:22', '2024-02-20 18:10:22'),
(38, 26, 25, 11, 'Proper way of wasu', 0, '2024-02-20 18:10:22', '2024-02-20 18:10:22'),
(69, 44, 22, 11, 'What are you doing', 1, '2024-02-23 17:15:03', '2024-02-23 17:49:06'),
(43, 29, 20, 11, 'how to pray Namaz e tasvi?', 0, '2024-02-21 00:31:04', '2024-02-21 00:31:04'),
(44, 30, 25, 11, 'How are you', 0, '2024-02-21 09:48:43', '2024-02-21 09:48:43'),
(45, 31, 25, 11, 'How are you', 0, '2024-02-21 09:59:51', '2024-02-21 09:59:51'),
(46, 31, 25, 11, 'How are you', 0, '2024-02-21 09:59:51', '2024-02-21 09:59:51'),
(47, 32, 25, 11, 'Can I Ask some questions', 0, '2024-02-21 10:01:36', '2024-02-21 10:01:36'),
(48, 32, 25, 11, 'Can I Ask some questions', 0, '2024-02-21 10:01:36', '2024-02-21 10:01:36'),
(49, 33, 25, 11, 'Can I Ask', 0, '2024-02-21 10:11:34', '2024-02-21 10:11:34'),
(50, 33, 25, 11, 'Can I Ask', 0, '2024-02-21 10:11:34', '2024-02-21 10:11:34'),
(51, 34, 25, 11, 'Can I', 0, '2024-02-21 10:13:09', '2024-02-21 10:13:09'),
(52, 34, 25, 11, 'Can I', 0, '2024-02-21 10:13:09', '2024-02-21 10:13:09'),
(53, 35, 25, 11, 'Can', 0, '2024-02-21 10:17:22', '2024-02-21 10:17:22'),
(54, 35, 25, 11, 'Can', 0, '2024-02-21 10:17:22', '2024-02-21 10:17:22'),
(55, 36, 24, 11, 'Hi Mufti shb', 0, '2024-02-21 10:19:26', '2024-02-21 10:19:26'),
(56, 36, 24, 25, 'Hi Mufti shb', 0, '2024-02-21 10:19:26', '2024-02-21 10:19:26'),
(57, 37, 25, 11, 'Islam', 0, '2024-02-21 10:22:18', '2024-02-21 10:22:18'),
(58, 37, 25, 25, 'Islam', 0, '2024-02-21 10:22:18', '2024-02-21 10:22:18'),
(59, 38, 22, 11, 'Question', 0, '2024-02-21 10:23:59', '2024-02-21 10:23:59'),
(68, 43, 22, 11, 'How are you Sir', 0, '2024-02-23 17:14:26', '2024-02-23 17:14:26'),
(61, 39, 5, 11, 'Hi How are you', 0, '2024-02-21 15:33:01', '2024-02-21 15:33:01'),
(67, 43, 22, 11, 'How are you Sir', 0, '2024-02-23 17:14:26', '2024-02-23 17:14:26'),
(65, 42, 33, 25, 'hello', 0, '2024-02-22 16:26:48', '2024-02-22 16:26:48'),
(66, 42, 33, 11, 'hello', 0, '2024-02-22 16:26:48', '2024-02-22 16:26:48'),
(70, 45, 22, 11, 'Hello Sir', 0, '2024-02-23 17:48:00', '2024-02-23 17:48:00'),
(71, 45, 22, 11, 'Hello Sir', 0, '2024-02-23 17:48:00', '2024-02-23 17:48:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `question` longtext NOT NULL DEFAULT '',
  `fiqa` varchar(191) NOT NULL DEFAULT '',
  `category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`category`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_queries`
--

INSERT INTO `user_queries` (`id`, `user_id`, `question`, `fiqa`, `category`, `created_at`, `updated_at`) VALUES
(6, 2, 'Hi How are you', 'General', '[]', '2024-02-16 18:41:11', '2024-02-16 18:41:11'),
(5, 2, 'Hi Mufti G', 'Hanfi', '[]', '2024-02-16 18:39:20', '2024-02-16 18:39:20'),
(7, 1, 'Hi How are you', 'Hanfi', '[]', '2024-02-19 12:12:57', '2024-02-19 12:12:57'),
(8, 15, 'Is your queries visible now?', '', '[]', '2024-02-19 17:31:32', '2024-02-19 17:31:32'),
(9, 8, 'Hi How are you', '', '[]', '2024-02-20 10:11:49', '2024-02-20 10:11:49'),
(10, 11, '...', '', '[]', '2024-02-20 10:12:37', '2024-02-20 10:12:37'),
(11, 11, '.', '', '[]', '2024-02-20 10:18:14', '2024-02-20 10:18:14'),
(12, 11, '.', '', '[]', '2024-02-20 10:25:10', '2024-02-20 10:25:10'),
(13, 11, '.', '', '[]', '2024-02-20 10:26:00', '2024-02-20 10:26:00'),
(14, 11, 'hello', '', '[]', '2024-02-20 10:27:43', '2024-02-20 10:27:43'),
(15, 11, 'hello', '', '[]', '2024-02-20 10:27:56', '2024-02-20 10:27:56'),
(16, 2, 'Hi Mufti G', '', '[]', '2024-02-20 10:31:06', '2024-02-20 10:31:06'),
(17, 11, '.', '', '[]', '2024-02-20 10:32:28', '2024-02-20 10:32:28'),
(27, 28, 'Islam', 'General', '[\"Salah\",\"Finance\",\"Family law\"]', '2024-02-20 18:22:26', '2024-02-20 18:22:26'),
(21, 11, '.', 'General', '[\"Salah\",\"Finance\",\"Family law\"]', '2024-02-20 17:18:56', '2024-02-20 17:18:56'),
(25, 21, 'Hi How are you', 'General', '[\"Salah\",\"Finance\",\"Family law\"]', '2024-02-20 17:37:13', '2024-02-20 17:37:13'),
(26, 25, 'Proper way of wasu', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-20 18:10:21', '2024-02-20 18:10:21'),
(29, 20, 'how to pray Namaz e tasvi?', 'Shāfiʿī', '[\"Duas\"]', '2024-02-21 00:31:03', '2024-02-21 00:31:03'),
(30, 25, 'How are you', 'Shāfiʿī', '[\"Relationship\"]', '2024-02-21 09:48:42', '2024-02-21 09:48:42'),
(31, 25, 'How are you', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 09:59:50', '2024-02-21 09:59:50'),
(32, 25, 'Can I Ask some questions', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 10:01:34', '2024-02-21 10:01:34'),
(33, 25, 'Can I Ask', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 10:11:32', '2024-02-21 10:11:32'),
(34, 25, 'Can I', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 10:13:08', '2024-02-21 10:13:08'),
(35, 25, 'Can', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-21 10:17:21', '2024-02-21 10:17:21'),
(36, 24, 'Hi Mufti shb', 'Ḥanafī', '[\"Duas\"]', '2024-02-21 10:19:26', '2024-02-21 10:19:26'),
(37, 25, 'Islam', 'Ḥanafī', '[\"Duas\"]', '2024-02-21 10:22:18', '2024-02-21 10:22:18'),
(38, 22, 'Question', 'Mālikī', '[\"Comparative religion\"]', '2024-02-21 10:23:59', '2024-02-21 10:23:59'),
(39, 5, 'Hi How are you', 'General', '[\"Duas\",\"Raising kids\",\"Dhikir\",\"Salah\",\"Dawah\",\"Food\"]', '2024-02-21 15:33:00', '2024-02-21 15:33:00'),
(44, 22, 'What are you doing', 'Mālikī', '[\"Marriage\"]', '2024-02-23 17:15:02', '2024-02-23 17:15:02'),
(43, 22, 'How are you Sir', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-23 17:14:25', '2024-02-23 17:14:25'),
(42, 33, 'hello', 'General', '[\"Duas\",\"Relationship\",\"Marriage\",\"Home Finance\",\"Finance\"]', '2024-02-22 16:26:47', '2024-02-22 16:26:47'),
(45, 22, 'Hello Sir', 'General', '[\"Family law\",\"Finance\",\"Marriage\",\"Dhikir\",\"Duas\"]', '2024-02-23 17:47:58', '2024-02-23 17:47:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `degrees_user_id_foreign` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_user_id_foreign` (`user_id`);

--
-- Indexes for table `event_questions`
--
ALTER TABLE `event_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_questions_event_id_foreign` (`event_id`),
  ADD KEY `event_questions_user_id_foreign` (`user_id`);

--
-- Indexes for table `event_scholars`
--
ALTER TABLE `event_scholars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_scholars_event_id_foreign` (`event_id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `experiences_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `help_feed_backs`
--
ALTER TABLE `help_feed_backs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `help_feed_backs_user_id_foreign` (`user_id`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interests_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muftis`
--
ALTER TABLE `muftis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `muftis_user_id_foreign` (`user_id`);

--
-- Indexes for table `mufti_appointments`
--
ALTER TABLE `mufti_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mufti_appointments_user_id_foreign` (`user_id`),
  ADD KEY `mufti_appointments_mufti_id_foreign` (`mufti_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `private_questions`
--
ALTER TABLE `private_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `private_questions_user_id_foreign` (`user_id`),
  ADD KEY `private_questions_mufti_id_foreign` (`mufti_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_user_id_foreign` (`user_id`);

--
-- Indexes for table `question_comments`
--
ALTER TABLE `question_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_comments_question_id_foreign` (`question_id`),
  ADD KEY `question_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `question_votes`
--
ALTER TABLE `question_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_votes_question_id_foreign` (`question_id`),
  ADD KEY `question_votes_user_id_foreign` (`user_id`);

--
-- Indexes for table `save_events`
--
ALTER TABLE `save_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `save_events_event_id_foreign` (`event_id`),
  ADD KEY `save_events_user_id_foreign` (`user_id`);

--
-- Indexes for table `scholar_replies`
--
ALTER TABLE `scholar_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scholar_replies_question_id_foreign` (`question_id`),
  ADD KEY `scholar_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_all_queries`
--
ALTER TABLE `user_all_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_all_queries_query_id_foreign` (`query_id`),
  ADD KEY `user_all_queries_user_id_foreign` (`user_id`),
  ADD KEY `user_all_queries_mufti_id_foreign` (`mufti_id`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_queries_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `degrees`
--
ALTER TABLE `degrees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `event_questions`
--
ALTER TABLE `event_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `event_scholars`
--
ALTER TABLE `event_scholars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_feed_backs`
--
ALTER TABLE `help_feed_backs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `muftis`
--
ALTER TABLE `muftis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mufti_appointments`
--
ALTER TABLE `mufti_appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_questions`
--
ALTER TABLE `private_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `question_comments`
--
ALTER TABLE `question_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `question_votes`
--
ALTER TABLE `question_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `save_events`
--
ALTER TABLE `save_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `scholar_replies`
--
ALTER TABLE `scholar_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user_all_queries`
--
ALTER TABLE `user_all_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
