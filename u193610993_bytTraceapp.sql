-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 17, 2026 at 12:39 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u193610993_bytTraceapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `AuditLogs`
--

CREATE TABLE `AuditLogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` bigint(20) DEFAULT NULL,
  `meta_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `AuditLogs`
--

INSERT INTO `AuditLogs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `meta_json`, `ip_address`, `created_at`) VALUES
(1, 1, 'RequestLoginOtp', 'Users', 1, '{\"ip\":\"111.95.21.65\",\"wa_sent\":true}', '111.95.21.65', '2026-04-27 21:53:44'),
(2, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-27 21:54:02'),
(3, 1, 'RequestLoginOtp', 'Users', 1, '{\"ip\":\"114.8.214.141\",\"wa_sent\":true}', '114.8.214.141', '2026-04-27 21:55:36'),
(4, 1, 'Login', 'Users', 1, '{\"ip\":\"114.8.214.141\"}', '114.8.214.141', '2026-04-27 21:55:55'),
(5, 1, 'Logout', 'Users', 1, NULL, '114.8.214.141', '2026-04-27 21:57:44'),
(6, NULL, 'RequestLoginOtp', 'Users', 8, '{\"ip\":\"114.8.214.141\",\"wa_sent\":true}', '114.8.214.141', '2026-04-27 21:58:02'),
(7, NULL, 'Login', 'Users', 8, '{\"ip\":\"114.8.214.141\"}', '114.8.214.141', '2026-04-27 21:58:46'),
(8, NULL, 'SaveDraft', 'DailyReports', 1, NULL, '114.8.214.141', '2026-04-27 22:04:31'),
(9, NULL, 'SubmitReport', 'DailyReports', 1, NULL, '114.8.214.141', '2026-04-27 22:04:40'),
(10, 9, 'Register', 'Users', 9, '{\"username\":\"Rahman\"}', '140.213.68.46', '2026-04-28 00:32:55'),
(11, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"140.213.68.46\",\"wa_sent\":false}', '140.213.68.46', '2026-04-28 00:33:06'),
(12, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"2400:9800:bc0:adcb:7000:4045:614:c108\",\"wa_sent\":false}', '2400:9800:bc0:adcb:7000:4045:614:c108', '2026-04-28 00:36:00'),
(13, NULL, 'Logout', 'Users', 8, NULL, '114.8.214.141', '2026-04-28 01:25:14'),
(14, 1, 'RequestLoginOtp', 'Users', 1, '{\"ip\":\"114.8.214.141\",\"wa_sent\":false}', '114.8.214.141', '2026-04-28 01:25:20'),
(15, 1, 'RequestLoginOtp', 'Users', 1, '{\"ip\":\"114.8.214.141\",\"wa_sent\":true}', '114.8.214.141', '2026-04-28 01:36:12'),
(16, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"2400:9800:ba0:1015:5565:150f:a69d:a553\",\"wa_sent\":true}', '2400:9800:ba0:1015:5565:150f:a69d:a553', '2026-04-28 03:31:56'),
(17, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:ba0:1015:5565:150f:a69d:a553\"}', '2400:9800:ba0:1015:5565:150f:a69d:a553', '2026-04-28 03:32:24'),
(18, 1, 'RequestLoginOtp', 'Users', 1, '{\"ip\":\"114.8.214.141\",\"wa_sent\":true}', '114.8.214.141', '2026-04-28 03:39:25'),
(19, 1, 'Login', 'Users', 1, '{\"ip\":\"114.8.214.141\"}', '114.8.214.141', '2026-04-28 03:39:45'),
(20, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"2401:e320:a73:1808:f516:21c4:8898:1677\",\"wa_sent\":true}', '2401:e320:a73:1808:f516:21c4:8898:1677', '2026-04-28 05:16:19'),
(21, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"2401:e320:a73:1808:f516:21c4:8898:1677\",\"wa_sent\":true}', '2401:e320:a73:1808:f516:21c4:8898:1677', '2026-04-28 05:16:26'),
(22, 9, 'RequestLoginOtp', 'Users', 9, '{\"ip\":\"2401:e320:a73:1808:f516:21c4:8898:1677\",\"wa_sent\":true}', '2401:e320:a73:1808:f516:21c4:8898:1677', '2026-04-28 05:17:54'),
(23, 9, 'Login', 'Users', 9, '{\"ip\":\"2401:e320:a73:1808:f516:21c4:8898:1677\"}', '2401:e320:a73:1808:f516:21c4:8898:1677', '2026-04-28 05:18:51'),
(24, 1, 'Logout', 'Users', 1, NULL, '111.95.21.65', '2026-04-28 08:32:28'),
(25, NULL, 'Login', 'Users', 8, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 08:33:21'),
(26, NULL, 'Logout', 'Users', 8, NULL, '111.95.21.65', '2026-04-28 08:38:30'),
(27, NULL, 'Login', 'Users', 8, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 08:38:41'),
(28, 1, 'Logout', 'Users', 1, NULL, '111.95.21.65', '2026-04-28 08:59:45'),
(29, NULL, 'Login', 'Users', 8, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 09:01:07'),
(30, NULL, 'Logout', 'Users', 8, NULL, '111.95.21.65', '2026-04-28 09:06:17'),
(31, NULL, 'Login', 'Users', 8, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 09:08:03'),
(32, 9, 'Logout', 'Users', 9, NULL, '2401:e320:a73:1808:a00f:99c9:6b67:ffa5', '2026-04-28 09:08:08'),
(33, NULL, 'Logout', 'Users', 8, NULL, '111.95.21.65', '2026-04-28 09:09:49'),
(34, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 09:10:14'),
(35, 1, 'Logout', 'Users', 1, NULL, '111.95.21.65', '2026-04-28 09:11:37'),
(36, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-28 09:11:57'),
(37, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 11:57:32'),
(38, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97\"}', '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 12:07:43'),
(39, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 12:08:06'),
(40, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97\"}', '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 12:08:35'),
(41, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 12:15:04'),
(42, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97\"}', '2400:9800:bc0:d5a8:c8fe:cd31:45ed:fc97', '2026-04-28 12:15:08'),
(43, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:12:21'),
(44, 1, 'Login', 'Users', 1, '{\"ip\":\"140.213.66.153\"}', '140.213.66.153', '2026-04-28 13:13:31'),
(45, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:19:34'),
(46, NULL, 'Login', 'Users', 10, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:19:57'),
(47, NULL, 'Logout', 'Users', 10, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:20:27'),
(48, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:21:01'),
(49, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:23:05'),
(50, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:24:37'),
(51, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:24:46'),
(52, NULL, 'Login', 'Users', 10, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:25:39'),
(53, NULL, 'Logout', 'Users', 10, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:25:52'),
(54, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:28:39'),
(55, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:29:24'),
(56, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc0:33c5:42f:54dc:6e66:c49a\"}', '2400:9800:bc0:33c5:42f:54dc:6e66:c49a', '2026-04-28 13:29:56'),
(57, 1, 'DeleteUser', 'Users', 8, '{\"username\":\"Johannes\"}', '114.10.112.114', '2026-04-28 16:35:20'),
(58, 1, 'Logout', 'Users', 1, NULL, '114.10.112.114', '2026-04-28 16:36:03'),
(59, 12, 'Register', 'Users', 12, '{\"username\":\"Richy\"}', '114.10.112.114', '2026-04-28 16:36:30'),
(60, 12, 'Login', 'Users', 12, '{\"ip\":\"114.10.112.114\"}', '114.10.112.114', '2026-04-28 16:36:39'),
(61, 12, 'SaveDraft', 'DailyReports', 2, NULL, '114.10.112.114', '2026-04-28 16:42:00'),
(62, 12, 'SubmitReport', 'DailyReports', 2, NULL, '114.10.112.114', '2026-04-28 16:42:20'),
(63, 12, 'Logout', 'Users', 12, NULL, '114.10.112.114', '2026-04-28 16:43:40'),
(64, 12, 'Login', 'Users', 12, '{\"ip\":\"114.10.112.114\"}', '114.10.112.114', '2026-04-28 20:49:14'),
(65, 11, 'Logout', 'Users', 11, NULL, '202.65.238.184', '2026-04-29 00:32:38'),
(66, 9, 'Login', 'Users', 9, '{\"ip\":\"202.65.238.184\"}', '202.65.238.184', '2026-04-29 00:32:42'),
(67, 9, 'SaveDraft', 'DailyReports', 3, NULL, '2401:e320:a73:1808:3afe:26c1:89ba:2fed', '2026-04-29 00:35:17'),
(68, 9, 'SubmitReport', 'DailyReports', 3, NULL, '2401:e320:a73:1808:3afe:26c1:89ba:2fed', '2026-04-29 00:35:37'),
(69, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:39:38'),
(70, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc3:4e5f:aaf0:d6df:4016:75c\"}', '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:39:52'),
(71, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:41:45'),
(72, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc3:4e5f:aaf0:d6df:4016:75c\"}', '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:41:48'),
(73, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:42:36'),
(74, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc3:4e5f:aaf0:d6df:4016:75c\"}', '2400:9800:bc3:4e5f:aaf0:d6df:4016:75c', '2026-04-29 00:42:48'),
(75, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:dc00:4ab0:de34:9a57\"}', '2401:e320:a73:1808:dc00:4ab0:de34:9a57', '2026-04-29 01:06:11'),
(76, 12, 'SaveDraft', 'DailyReports', 4, NULL, '114.4.79.148', '2026-04-29 03:16:36'),
(77, 12, 'SubmitReport', 'DailyReports', 4, NULL, '114.4.79.148', '2026-04-29 03:16:40'),
(78, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc2:adc1:7e7c:2cb0:e3f7:3c42', '2026-04-29 03:33:18'),
(79, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc2:adc1:7e7c:2cb0:e3f7:3c42\"}', '2400:9800:bc2:adc1:7e7c:2cb0:e3f7:3c42', '2026-04-29 03:33:21'),
(80, 9, 'SaveDraft', 'DailyReports', 5, NULL, '2400:9800:bc2:adc1:7e7c:2cb0:e3f7:3c42', '2026-04-29 03:36:02'),
(81, 9, 'SubmitReport', 'DailyReports', 5, NULL, '2400:9800:bc2:adc1:7e7c:2cb0:e3f7:3c42', '2026-04-29 03:36:08'),
(82, 1, 'Logout', 'Users', 1, NULL, '2401:e320:a73:1808:a426:4023:49d8:e464', '2026-04-29 03:47:27'),
(83, 9, 'Login', 'Users', 9, '{\"ip\":\"2401:e320:a73:1808:a426:4023:49d8:e464\"}', '2401:e320:a73:1808:a426:4023:49d8:e464', '2026-04-29 03:47:33'),
(84, 9, 'Logout', 'Users', 9, NULL, '2401:e320:a73:1808:a426:4023:49d8:e464', '2026-04-29 03:47:53'),
(85, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:a426:4023:49d8:e464\"}', '2401:e320:a73:1808:a426:4023:49d8:e464', '2026-04-29 03:48:41'),
(86, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-29 03:48:49'),
(87, 1, 'Logout', 'Users', 1, NULL, '111.95.21.65', '2026-04-29 03:51:19'),
(88, 12, 'Login', 'Users', 12, '{\"ip\":\"111.95.21.65\"}', '111.95.21.65', '2026-04-29 03:51:29'),
(89, 1, 'Logout', 'Users', 1, NULL, '2401:e320:a73:1808:fdea:339f:8f86:90d5', '2026-04-29 03:57:46'),
(90, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:fdea:339f:8f86:90d5\"}', '2401:e320:a73:1808:fdea:339f:8f86:90d5', '2026-04-29 03:57:58'),
(91, 1, 'Login', 'Users', 1, '{\"ip\":\"103.177.176.250\"}', '103.177.176.250', '2026-04-29 04:07:23'),
(92, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc1:1c54:9e51:43e7:100a:1f09', '2026-04-29 06:59:25'),
(93, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc1:1c54:9e51:43e7:100a:1f09\"}', '2400:9800:bc1:1c54:9e51:43e7:100a:1f09', '2026-04-29 06:59:54'),
(94, 11, 'SaveDraft', 'DailyReports', 6, NULL, '2400:9800:bc1:1c54:9e51:43e7:100a:1f09', '2026-04-29 07:01:50'),
(95, 11, 'SubmitReport', 'DailyReports', 6, NULL, '2400:9800:bc1:1c54:9e51:43e7:100a:1f09', '2026-04-29 07:01:59'),
(96, 1, 'Logout', 'Users', 1, NULL, '202.125.100.85', '2026-04-29 07:14:51'),
(97, 1, 'Login', 'Users', 1, '{\"ip\":\"202.125.100.85\"}', '202.125.100.85', '2026-04-29 07:16:08'),
(98, 1, 'Logout', 'Users', 1, NULL, '202.125.100.85', '2026-04-29 07:34:48'),
(99, 11, 'Logout', 'Users', 11, NULL, '2400:9800:bc0:23de:a37d:569b:2379:b2d2', '2026-04-29 09:28:15'),
(100, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc0:23de:a37d:569b:2379:b2d2\"}', '2400:9800:bc0:23de:a37d:569b:2379:b2d2', '2026-04-29 09:28:19'),
(101, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc0:23de:a37d:569b:2379:b2d2', '2026-04-29 09:28:38'),
(102, 12, 'SaveDraft', 'DailyReports', 2, NULL, '111.95.21.65', '2026-04-29 11:32:52'),
(103, 12, 'SubmitReport', 'DailyReports', 2, NULL, '111.95.21.65', '2026-04-29 11:35:04'),
(104, 12, 'SaveDraft', 'DailyReports', 2, NULL, '111.95.21.65', '2026-04-29 11:35:16'),
(105, 12, 'SubmitReport', 'DailyReports', 2, NULL, '111.95.21.65', '2026-04-29 11:35:20'),
(106, 12, 'SaveDraft', 'DailyReports', 2, NULL, '111.95.21.65', '2026-04-29 11:36:11'),
(107, 12, 'SaveDraft', 'DailyReports', 4, NULL, '111.95.21.65', '2026-04-29 11:36:43'),
(108, 12, 'SubmitReport', 'DailyReports', 4, NULL, '111.95.21.65', '2026-04-29 11:36:47'),
(109, 12, 'SaveDraft', 'DailyReports', 7, NULL, '111.95.21.65', '2026-04-29 11:37:52'),
(110, 12, 'SaveDraft', 'DailyReports', 7, NULL, '111.95.21.65', '2026-04-29 11:46:19'),
(111, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc3:3e7b:595f:fd16:3ea8:b493\"}', '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:46:23'),
(112, 11, 'Logout', 'Users', 11, NULL, '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:48:09'),
(113, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc3:3e7b:595f:fd16:3ea8:b493\"}', '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:48:29'),
(114, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:48:49'),
(115, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc3:3e7b:595f:fd16:3ea8:b493\"}', '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:48:53'),
(116, 9, 'SaveDraft', 'DailyReports', 5, NULL, '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:50:06'),
(117, 9, 'SubmitReport', 'DailyReports', 5, NULL, '2400:9800:bc3:3e7b:595f:fd16:3ea8:b493', '2026-04-29 11:50:11'),
(118, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc2:e0:393a:6db:e605:4440', '2026-04-29 13:11:22'),
(119, 1, 'Login', 'Users', 1, '{\"ip\":\"2400:9800:bc2:e0:393a:6db:e605:4440\"}', '2400:9800:bc2:e0:393a:6db:e605:4440', '2026-04-29 13:12:14'),
(120, 1, 'Logout', 'Users', 1, NULL, '103.177.176.250', '2026-04-30 03:07:23'),
(121, 1, 'Login', 'Users', 1, '{\"ip\":\"2001:448a:50a0:1a58:dd47:e392:4d20:d8a\"}', '2001:448a:50a0:1a58:dd47:e392:4d20:d8a', '2026-04-30 03:08:57'),
(122, 1, 'Logout', 'Users', 1, NULL, '2400:9800:bc3:e97d:a69a:cef9:d581:de97', '2026-04-30 13:16:34'),
(123, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc3:e97d:a69a:cef9:d581:de97\"}', '2400:9800:bc3:e97d:a69a:cef9:d581:de97', '2026-04-30 13:16:37'),
(124, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc3:e97d:a69a:cef9:d581:de97', '2026-04-30 13:20:15'),
(125, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc3:e97d:a69a:cef9:d581:de97\"}', '2400:9800:bc3:e97d:a69a:cef9:d581:de97', '2026-04-30 13:20:31'),
(126, 11, 'Logout', 'Users', 11, NULL, '2400:9800:bc3:e97d:a69a:cef9:d581:de97', '2026-04-30 13:21:15'),
(127, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc1:456:28da:4397:c49d:f7c0\"}', '2400:9800:bc1:456:28da:4397:c49d:f7c0', '2026-05-01 07:46:49'),
(128, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc2:e52d:7001:bd27:6ecd:2363', '2026-05-01 13:14:39'),
(129, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc2:e52d:7001:bd27:6ecd:2363\"}', '2400:9800:bc2:e52d:7001:bd27:6ecd:2363', '2026-05-01 13:14:44'),
(130, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc4:f8a:9c56:de85:9546:ab48\"}', '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 00:34:17'),
(131, 12, 'SaveDraft', 'DailyReports', 8, NULL, '114.10.28.223', '2026-05-02 00:42:33'),
(132, 9, 'Logout', 'Users', 9, NULL, '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 00:53:40'),
(133, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc4:f8a:9c56:de85:9546:ab48\"}', '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 00:54:02'),
(134, 11, 'Logout', 'Users', 11, NULL, '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 00:54:25'),
(135, NULL, 'Login', 'Users', 10, '{\"ip\":\"2400:9800:bc4:f8a:9c56:de85:9546:ab48\"}', '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 00:55:15'),
(136, NULL, 'SaveDraft', 'DailyReports', 9, NULL, '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 01:03:30'),
(137, NULL, 'SubmitReport', 'DailyReports', 9, NULL, '2400:9800:bc4:f8a:9c56:de85:9546:ab48', '2026-05-02 01:03:43'),
(138, NULL, 'Logout', 'Users', 10, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:18:20'),
(139, 11, 'Login', 'Users', 11, '{\"ip\":\"2400:9800:bc2:b5e3:7e7e:9085:7503:ee01\"}', '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:18:26'),
(140, 11, 'Logout', 'Users', 11, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:18:40'),
(141, NULL, 'Login', 'Users', 10, '{\"ip\":\"2400:9800:bc2:b5e3:7e7e:9085:7503:ee01\"}', '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:19:12'),
(142, NULL, 'Logout', 'Users', 10, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:19:28'),
(143, 9, 'Login', 'Users', 9, '{\"ip\":\"2400:9800:bc2:b5e3:7e7e:9085:7503:ee01\"}', '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:19:31'),
(144, 9, 'SaveDraft', 'DailyReports', 10, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:21:01'),
(145, 9, 'SubmitReport', 'DailyReports', 10, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:21:14'),
(146, 9, 'SubmitReport', 'DailyReports', 10, NULL, '2400:9800:bc2:b5e3:7e7e:9085:7503:ee01', '2026-05-02 07:21:14'),
(147, 1, 'Login', 'Users', 1, '{\"ip\":\"118.99.81.209\"}', '118.99.81.209', '2026-05-04 11:27:03'),
(148, 1, 'Login', 'Users', 1, '{\"ip\":\"180.254.66.245\"}', '180.254.66.245', '2026-05-05 03:17:24'),
(149, 12, 'Logout', 'Users', 12, NULL, '111.95.21.80', '2026-05-05 03:24:27'),
(150, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.80\"}', '111.95.21.80', '2026-05-05 03:24:35'),
(151, 9, 'Logout', 'Users', 9, NULL, '116.254.97.32', '2026-05-06 06:21:46'),
(152, 9, 'Login', 'Users', 9, '{\"ip\":\"116.254.97.32\"}', '116.254.97.32', '2026-05-06 06:21:51'),
(153, 9, 'SaveDraft', 'DailyReports', 11, NULL, '2401:e320:a73:1808:acc4:25fe:78c8:7e4', '2026-05-06 06:24:39'),
(154, 9, 'SubmitReport', 'DailyReports', 11, NULL, '2401:e320:a73:1808:acc4:25fe:78c8:7e4', '2026-05-06 06:24:45'),
(160, 9, 'Logout', 'Users', 9, NULL, '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:02:38'),
(161, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:74de:650c:1aab:fdcd\"}', '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:02:45'),
(162, 1, 'Logout', 'Users', 1, NULL, '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:02:47'),
(163, NULL, 'Login', 'Users', 10, '{\"ip\":\"2401:e320:a73:1808:74de:650c:1aab:fdcd\"}', '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:02:52'),
(164, NULL, 'SaveDraft', 'DailyReports', 12, NULL, '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:05:35'),
(165, NULL, 'SubmitReport', 'DailyReports', 12, NULL, '2401:e320:a73:1808:74de:650c:1aab:fdcd', '2026-05-06 09:05:40'),
(166, NULL, 'Logout', 'Users', 10, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 05:52:24'),
(167, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 05:52:27'),
(168, 1, 'Logout', 'Users', 1, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:35:00'),
(169, NULL, 'Register', 'Users', 13, '{\"username\":\"AgusIrianto\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:35:37'),
(170, NULL, 'Login', 'Users', 13, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:35:40'),
(171, NULL, 'Logout', 'Users', 13, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:36:27'),
(172, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:36:41'),
(173, 1, 'DeleteUser', 'Users', 10, '{\"username\":\"Modar99\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:36:57'),
(174, 1, 'Logout', 'Users', 1, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:37:03'),
(175, NULL, 'Login', 'Users', 13, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 07:37:10'),
(176, NULL, 'Logout', 'Users', 13, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:04:55'),
(177, NULL, 'Register', 'Users', 14, '{\"username\":\"JoeliPurwanto\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:05:27'),
(178, NULL, 'Login', 'Users', 14, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:05:39'),
(179, NULL, 'Logout', 'Users', 14, NULL, '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:06:18'),
(180, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:a73:1808:8cc2:831d:afe3:3a63\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:06:40'),
(181, 1, 'DeleteUser', 'Users', 14, '{\"username\":\"JoeliPurwanto\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:06:47'),
(182, 1, 'DeleteUser', 'Users', 13, '{\"username\":\"AgusIrianto\"}', '2401:e320:a73:1808:8cc2:831d:afe3:3a63', '2026-05-07 09:06:50'),
(183, 1, 'Logout', 'Users', 1, NULL, '111.95.21.80', '2026-05-10 10:47:41'),
(184, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.80\"}', '111.95.21.80', '2026-05-10 10:48:05'),
(185, 1, 'Logout', 'Users', 1, NULL, '2401:e320:b7a:ba08:ad0f:33f0:5cec:1890', '2026-05-13 03:46:28'),
(186, 9, 'Login', 'Users', 9, '{\"ip\":\"2401:e320:b7a:ba08:ad0f:33f0:5cec:1890\"}', '2401:e320:b7a:ba08:ad0f:33f0:5cec:1890', '2026-05-13 03:46:53'),
(187, 9, 'Logout', 'Users', 9, NULL, '2401:e320:b7a:ba08:2139:f008:924f:eec6', '2026-05-13 10:15:27'),
(188, 11, 'Login', 'Users', 11, '{\"ip\":\"2401:e320:b7a:ba08:2139:f008:924f:eec6\"}', '2401:e320:b7a:ba08:2139:f008:924f:eec6', '2026-05-13 10:15:37'),
(189, 11, 'Logout', 'Users', 11, NULL, '2401:e320:b7a:ba08:2139:f008:924f:eec6', '2026-05-13 10:15:45'),
(190, 9, 'Login', 'Users', 9, '{\"ip\":\"2401:e320:b7a:ba08:2139:f008:924f:eec6\"}', '2401:e320:b7a:ba08:2139:f008:924f:eec6', '2026-05-13 10:17:10'),
(191, 1, 'Logout', 'Users', 1, NULL, '111.95.21.80', '2026-05-14 04:06:41'),
(192, 9, 'Logout', 'Users', 9, NULL, '2401:e320:ab1:7f08:df18:e18d:e576:fec0', '2026-05-15 05:15:13'),
(193, 1, 'Login', 'Users', 1, '{\"ip\":\"2401:e320:ab1:7f08:df18:e18d:e576:fec0\"}', '2401:e320:ab1:7f08:df18:e18d:e576:fec0', '2026-05-15 05:16:02'),
(194, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.80\"}', '111.95.21.80', '2026-05-15 08:36:31'),
(195, 1, 'Logout', 'Users', 1, NULL, '111.95.21.80', '2026-05-15 08:37:05'),
(196, 1, 'Login', 'Users', 1, '{\"ip\":\"111.95.21.80\"}', '111.95.21.80', '2026-05-15 08:37:44'),
(197, 1, 'Login', 'Users', 1, '{\"ip\":\"2404:c0:2540:1e4d:bdae:c790:3134:8dc2\"}', '2404:c0:2540:1e4d:bdae:c790:3134:8dc2', '2026-05-17 06:51:39'),
(198, 1, 'Login', 'Users', 1, '{\"ip\":\"36.77.234.181\"}', '36.77.234.181', '2026-05-17 06:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `DailyReports`
--

CREATE TABLE `DailyReports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_code` varchar(40) NOT NULL,
  `report_date` date NOT NULL,
  `worker_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `weather_code` enum('Cerah','Hujan','Mendung') NOT NULL,
  `realization_summary` text NOT NULL,
  `whatsapp_summary` longtext DEFAULT NULL,
  `status` enum('Draft','Submitted') NOT NULL DEFAULT 'Draft',
  `submitted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `edited_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `DailyReports`
--

INSERT INTO `DailyReports` (`id`, `report_code`, `report_date`, `worker_user_id`, `created_by_user_id`, `weather_code`, `realization_summary`, `whatsapp_summary`, `status`, `submitted_at`, `created_at`, `updated_at`, `deleted_at`, `edited_at`) VALUES
(2, 'RPT-20260428164200-D645D9', '2026-04-26', 12, 12, 'Hujan', 'Item pekerjaan 1 | Sat: m | Rencana: Rencana kedeoan | Realisasi: Belum direalisasikan | Deviasi: Standart | Rekanan: Tidak terdapat rekanan\nItem pekerjaan 2 | Sat: unit | Rencana: Rencananya begini | Realisasi: Realisasi nihil | Deviasi: Ganda | Rekanan: Rekiri adanya', 'Halo, Richy Johannes,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Lainnya - Jalan soekarno hatta\nCuaca: Hujan\nRealisasi Pekerjaan: 2 item pekerjaan\nPekerja dan Posisi: 69 orang dari 14 posisi\nAlat Berat: 60 unit dari 11 jenis alat\nAlat Ringan: 4 jenis alat kerja\nMaterial & Bahan: Semua tanpa menggunakan bahan dan material. Bebas aja\nKendala: Kendala terdapat\nLembur: Ya (07:55 - 04:35)\n\n*(Catatan: Laporan ini telah diedit/diperbarui pada 29 Apr 2026 11:35)*', 'Draft', '2026-04-28 16:42:20', '2026-04-28 16:42:00', '2026-04-29 11:36:11', NULL, '2026-04-29 11:35:20'),
(3, 'RPT-20260429003517-84F77A', '2026-04-28', 9, 9, 'Mendung', 'Bongkar mbg | Sat: M2 | Rencana: 100 | Realisasi: 200 | Deviasi: 100 | Rekanan: Maju mundur abadi', 'Halo, Rahman,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Area Swangi - Kono adoh pokoke\nCuaca: Mendung\nRealisasi Pekerjaan: 1 item pekerjaan\nPekerja dan Posisi: 12 orang dari 12 posisi\nAlat Berat: -\nAlat Ringan: 1 jenis alat kerja\nMaterial & Bahan: Kopi gula aren\nKendala: Banyak\nLembur: Ya (18:00 - -)', 'Submitted', '2026-04-29 00:35:37', '2026-04-29 00:35:17', '2026-04-29 00:35:37', NULL, NULL),
(4, 'RPT-20260429031636-94101A', '2026-04-28', 12, 12, 'Mendung', 'Item pekerjaan 1 | Sat: m | Rencana: Rencana kedeoan | Realisasi: Akn direalisasikan | Deviasi: Standart | Rekanan: Tidak terdapat rekanan', 'Halo, Richy Johannes,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Area Laut - Jalan jakarta Raya\nCuaca: Mendung\nRealisasi Pekerjaan: 1 item pekerjaan\nPekerja dan Posisi: 31 orang dari 11 posisi\nAlat Berat: 52 unit dari 8 jenis alat\nAlat Ringan: 1 jenis alat kerja\nMaterial & Bahan: Anyaman bambu\nKendala: Tanpa ada kendala\nLembur: Ya (06:00 - 03:16)\n\n*(Catatan: Laporan ini telah diedit/diperbarui pada 29 Apr 2026 11:36)*', 'Submitted', '2026-04-29 03:16:40', '2026-04-29 03:16:36', '2026-04-29 11:36:47', NULL, '2026-04-29 11:36:47'),
(5, 'RPT-20260429033602-64DC43', '2026-04-29', 9, 9, 'Mendung', 'Ndase ngelu | Sat: M | Rencana: 300 | Realisasi: 200 | Deviasi: 600 | Rekanan: Maju mundur sentosa', 'Halo, Rahman,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Area Lanal - Ngelu ndase\nCuaca: Mendung\nRealisasi Pekerjaan: 1 item pekerjaan\nPekerja dan Posisi: 3 orang dari 3 posisi\nAlat Berat: 2 unit dari 2 jenis alat\nAlat Ringan: 1 jenis alat kerja\nMaterial & Bahan: Sambel\nKendala: Tidak ada\nLembur: Tidak\n\n*(Catatan: Laporan ini telah diedit/diperbarui pada 29 Apr 2026 11:50)*', 'Submitted', '2026-04-29 03:36:08', '2026-04-29 03:36:02', '2026-04-29 11:50:11', NULL, '2026-04-29 11:50:11'),
(7, 'RPT-20260429113752-DDD307', '2026-04-29', 12, 12, 'Hujan', '', NULL, 'Draft', NULL, '2026-04-29 11:37:52', '2026-04-29 11:46:19', NULL, NULL),
(8, 'RPT-20260502004233-B5DD62', '2026-05-02', 12, 12, 'Hujan', '', NULL, 'Draft', NULL, '2026-05-02 00:42:33', '2026-05-02 00:42:33', NULL, NULL),
(10, 'RPT-20260502072101-B9A259', '2026-05-02', 9, 9, 'Hujan', 'Kkk | Sat: M | Rencana: 120 | Realisasi: 30 | Deviasi: 70 | Rekanan: Kshsksh', 'Halo, Rahman,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Area Laut - Ngaglik\nCuaca: Hujan\nRealisasi Pekerjaan: 1 item pekerjaan\nPekerja dan Posisi: 5 orang dari 2 posisi\nAlat Berat: 3 unit dari 1 jenis alat\nAlat Ringan: 1 jenis alat kerja\nMaterial & Bahan: Kkkkk\nKendala: Kkkkk\nLembur: Tidak\n\n*(Catatan: Laporan ini telah diedit/diperbarui pada 02 May 2026 07:21)*', 'Submitted', '2026-05-02 07:21:11', '2026-05-02 07:21:01', '2026-05-02 07:21:14', NULL, '2026-05-02 07:21:14'),
(11, 'RPT-20260506062439-B28065', '2026-05-06', 9, 9, 'Mendung', 'aaa | Sat: m | Rencana: 23 | Realisasi: 22 | Deviasi: 33 | Rekanan: sdfsdfsef', 'Halo, Rahman,\nBerikut ringkasan yang sudah Anda input:\n\nLokasi Pekerjaan: Area Lanal - aaaaa\nCuaca: Mendung\nRealisasi Pekerjaan: 1 item pekerjaan\nPekerja dan Posisi: 4 orang dari 2 posisi\nAlat Berat: 7 unit dari 2 jenis alat\nAlat Ringan: 1 jenis alat kerja\nMaterial & Bahan: sdfrg\nKendala: sfdfg\nLembur: Ya (18:00 - 20:50)', 'Submitted', '2026-05-06 06:24:45', '2026-05-06 06:24:39', '2026-05-06 06:24:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `HeavyEquipmentCategories`
--

CREATE TABLE `HeavyEquipmentCategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `HeavyEquipmentCategories`
--

INSERT INTO `HeavyEquipmentCategories` (`id`, `name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Dump Truck', 'dump-truck', 1, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(2, 'Excavator', 'excavator', 2, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(3, 'Bulldozer', 'bulldozer', 3, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(4, 'Loader', 'loader', 4, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(5, 'Vibroroller', 'vibroroller', 5, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(6, 'Hyab Crane', 'hyab-crane', 6, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(7, 'Crane', 'crane', 7, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(8, 'Boring Machine', 'boring-machine', 8, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `PasswordResets`
--

CREATE TABLE `PasswordResets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(160) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `RefreshTokens`
--

CREATE TABLE `RefreshTokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ReportHeavyEquipmentUsages`
--

CREATE TABLE `ReportHeavyEquipmentUsages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `heavy_equipment_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `equipment_label` varchar(120) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `volume` varchar(60) DEFAULT NULL,
  `unit` varchar(40) DEFAULT 'unit',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportHeavyEquipmentUsages`
--

INSERT INTO `ReportHeavyEquipmentUsages` (`id`, `daily_report_id`, `heavy_equipment_category_id`, `equipment_label`, `quantity`, `volume`, `unit`, `created_at`, `updated_at`) VALUES
(90, 2, 1, 'Dump Truck', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(91, 2, 2, 'Excavator', 6, '6', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(92, 2, 3, 'Bulldozer', 8, '8', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(93, 2, 4, 'Loader', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(94, 2, 5, 'Vibroroller', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(95, 2, 6, 'Hyab Crane', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(96, 2, 7, 'Crane', 4, '4', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(97, 2, 8, 'Boring Machine', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(98, 2, NULL, 'Alat tambahann 2', 6, '6', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(99, 2, NULL, 'Alat tambahan 3', 6, '6', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(100, 2, NULL, 'Alat berat tambahan 1', 5, '5', 'unit', '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(101, 4, 1, 'Dump Truck', 4, '4', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(102, 4, 2, 'Excavator', 8, '8', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(103, 4, 3, 'Bulldozer', 7, '7', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(104, 4, 4, 'Loader', 7, '7', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(105, 4, 5, 'Vibroroller', 5, '5', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(106, 4, 6, 'Hyab Crane', 7, '7', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(107, 4, 7, 'Crane', 7, '7', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(108, 4, 8, 'Boring Machine', 7, '7', 'unit', '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(109, 5, 5, 'Vibroroller', 1, '1', 'unit', '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(110, 5, 8, 'Boring Machine', 1, '1', 'unit', '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(128, 10, 7, 'Crane', 3, '3', 'unit', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(129, 11, 1, 'Dump Truck', 3, '3', 'unit', '2026-05-06 06:24:39', '2026-05-06 06:24:39'),
(130, 11, 6, 'Hyab Crane', 4, '4', 'unit', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportLightToolUsages`
--

CREATE TABLE `ReportLightToolUsages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `tool_label` varchar(160) NOT NULL,
  `volume` varchar(60) DEFAULT NULL,
  `unit` varchar(40) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportLightToolUsages`
--

INSERT INTO `ReportLightToolUsages` (`id`, `daily_report_id`, `tool_label`, `volume`, `unit`, `sort_order`, `created_at`, `updated_at`) VALUES
(8, 3, 'Cangkir', '3o', '3', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(25, 2, 'Akat ringab', '9', 'Unit', 1, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(26, 2, 'Alat ringan 3', '7', 'unit', 2, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(27, 2, 'Alat super ringan', '6', 'unit', 3, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(28, 2, 'Mainan buldozer', '7', 'unit', 4, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(29, 4, 'Kerja alat ringan', 'V', 'Unit', 1, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(30, 5, 'Sendok garpu', '80', 'Unit', 1, '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(37, 10, 'Kkk', '30', 'Pcs', 1, '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(38, 11, 'sss', '32', 'pcs', 1, '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportLocations`
--

CREATE TABLE `ReportLocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `current_location` varchar(255) NOT NULL,
  `area_code` varchar(50) NOT NULL,
  `area_label` varchar(120) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportLocations`
--

INSERT INTO `ReportLocations` (`id`, `daily_report_id`, `current_location`, `area_code`, `area_label`, `reason`, `created_at`, `updated_at`) VALUES
(2, 2, 'Jalan soekarno hatta', 'Lainnya', 'Lainnya', 'Tidak ada lokasi tambahab', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 'Kono adoh pokoke', 'AreaSwangi', 'Area Swangi', '', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 'Jalan jakarta Raya', 'AreaLaut', 'Area Laut', '', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, 'Ngelu ndase', 'AreaLanal', 'Area Lanal', '', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, 'Jccigigg', 'AreaSwangi', 'Area Swangi', '', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, 'Shhsshssh', 'AreaLanal', 'Area Lanal', '', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 'Ngaglik', 'AreaLaut', 'Area Laut', '', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 'aaaaa', 'AreaLanal', 'Area Lanal', '', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportMaterialSummaries`
--

CREATE TABLE `ReportMaterialSummaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `summary_text` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportMaterialSummaries`
--

INSERT INTO `ReportMaterialSummaries` (`id`, `daily_report_id`, `summary_text`, `created_at`, `updated_at`) VALUES
(2, 2, 'Semua tanpa menggunakan bahan dan material. Bebas aja', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 'Kopi gula aren', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 'Anyaman bambu', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, 'Sambel', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, 'Iggiggig', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, 'Haahahahahhahhah', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 'Kkkkk', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 'sdfrg', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportObstacleSummaries`
--

CREATE TABLE `ReportObstacleSummaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `obstacle_shape` varchar(255) NOT NULL,
  `obstacle_cause` varchar(255) NOT NULL,
  `obstacle_impact` varchar(255) NOT NULL,
  `additional_note` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportObstacleSummaries`
--

INSERT INTO `ReportObstacleSummaries` (`id`, `daily_report_id`, `obstacle_shape`, `obstacle_cause`, `obstacle_impact`, `additional_note`, `created_at`, `updated_at`) VALUES
(2, 2, 'Kendala terdapat', 'Penyebab belum diketahui', 'Dampaknya cukup signifikan', 'Tidak ada yang perlu dijelaskan lagi, semuanya sudah jelas', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 'Banyak', 'Gak masuk akal', 'Gak semangat', 'Apa ya', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 'Tanpa ada kendala', 'Tidak ada keendalaa', 'Gada dampak, lanjut ae', 'Keperluan', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, '', '', '', '', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, '', '', '', '', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, '', '', '', '', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 'Kkkkk', 'Hsjsgkakan', 'Habsksisb', 'Nansksbsj', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 'sfdfg', 'sdfgh', 'dfghy', 'dfghk', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportOvertimes`
--

CREATE TABLE `ReportOvertimes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `start_time` varchar(10) DEFAULT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `summary_text` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportOvertimes`
--

INSERT INTO `ReportOvertimes` (`id`, `daily_report_id`, `is_enabled`, `start_time`, `end_time`, `summary_text`, `created_at`, `updated_at`) VALUES
(2, 2, 1, '07:55', '04:35', 'Lembur wajib lembur, kejar target', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 1, '18:00', '', 'Oke', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 1, '06:00', '03:16', 'Lembur wajib lembur, kejar target', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, 0, NULL, NULL, '', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, 0, NULL, NULL, '', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, 0, NULL, NULL, '', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 0, NULL, NULL, '', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 1, '18:00', '20:50', 'dfghj', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportPhotos`
--

CREATE TABLE `ReportPhotos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportPhotos`
--

INSERT INTO `ReportPhotos` (`id`, `daily_report_id`, `file_name`, `file_path`, `mime_type`, `file_size`, `caption`, `sort_order`, `created_at`) VALUES
(7, 2, '1777394520_223fa6e7c8892f27fbef.jpg', 'Uploads/Reports/1777394520_223fa6e7c8892f27fbef.jpg', 'image/jpeg', 412069, NULL, 1, '2026-04-28 16:42:00'),
(8, 2, '1777394520_247401add1a691a1edcd.jpg', 'Uploads/Reports/1777394520_247401add1a691a1edcd.jpg', 'image/jpeg', 372350, NULL, 2, '2026-04-28 16:42:00'),
(9, 2, '1777394520_1bf2920c752ab80ca5ee.jpg', 'Uploads/Reports/1777394520_1bf2920c752ab80ca5ee.jpg', 'image/jpeg', 547432, NULL, 3, '2026-04-28 16:42:00'),
(10, 3, '1777422917_691f7a5237417b349a88.jpg', 'Uploads/Reports/1777422917_691f7a5237417b349a88.jpg', 'image/jpeg', 376812, NULL, 1, '2026-04-29 00:35:17'),
(11, 4, '1777432596_a9beebc3c2cbde7fb3b4.jpg', 'Uploads/Reports/1777432596_a9beebc3c2cbde7fb3b4.jpg', 'image/jpeg', 412069, NULL, 1, '2026-04-29 03:16:36'),
(12, 5, '1777433762_b2c2e22bd0ff737701ce.jpg', 'Uploads/Reports/1777433762_b2c2e22bd0ff737701ce.jpg', 'image/jpeg', 425390, NULL, 1, '2026-04-29 03:36:02'),
(18, 7, '1777462672_0f3c465f26df20ae4cc7.jpg', 'Uploads/Reports/1777462672_0f3c465f26df20ae4cc7.jpg', 'image/jpeg', 372350, NULL, 1, '2026-04-29 11:37:52'),
(19, 8, '1777682553_78e0cf867131f79fb18f.jpg', 'Uploads/Reports/1777682553_78e0cf867131f79fb18f.jpg', 'image/jpeg', 510552, NULL, 1, '2026-05-02 00:42:33'),
(31, 10, '1777706461_dbed048fc06bea207d99.jpg', 'Uploads/Reports/1777706461_dbed048fc06bea207d99.jpg', 'image/jpeg', 385790, NULL, 1, '2026-05-02 07:21:01'),
(32, 10, '1777706461_5214edec11fcf98e9cac.jpg', 'Uploads/Reports/1777706461_5214edec11fcf98e9cac.jpg', 'image/jpeg', 474470, NULL, 2, '2026-05-02 07:21:01'),
(33, 10, '1777706461_6073a5b1a5ee42953cce.jpg', 'Uploads/Reports/1777706461_6073a5b1a5ee42953cce.jpg', 'image/jpeg', 384659, NULL, 3, '2026-05-02 07:21:01'),
(34, 10, '1777706461_2be5eb068a6a4ccaaa7c.jpg', 'Uploads/Reports/1777706461_2be5eb068a6a4ccaaa7c.jpg', 'image/jpeg', 419266, NULL, 4, '2026-05-02 07:21:01'),
(35, 11, '1778048679_003b699969b847361309.png', 'Uploads/Reports/1778048679_003b699969b847361309.png', 'image/png', 1919, NULL, 1, '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportRealizationItems`
--

CREATE TABLE `ReportRealizationItems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `work_item` varchar(255) NOT NULL,
  `unit` varchar(40) DEFAULT NULL,
  `plan_text` varchar(255) DEFAULT NULL,
  `realization_text` varchar(255) DEFAULT NULL,
  `deviation_text` varchar(255) DEFAULT NULL,
  `partner` varchar(160) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportRealizationItems`
--

INSERT INTO `ReportRealizationItems` (`id`, `daily_report_id`, `work_item`, `unit`, `plan_text`, `realization_text`, `deviation_text`, `partner`, `sort_order`, `created_at`, `updated_at`) VALUES
(6, 3, 'Bongkar mbg', 'M2', '100', '200', '100', 'Maju mundur abadi', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(19, 2, 'Item pekerjaan 1', 'm', 'Rencana kedeoan', 'Belum direalisasikan', 'Standart', 'Tidak terdapat rekanan', 1, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(20, 2, 'Item pekerjaan 2', 'unit', 'Rencananya begini', 'Realisasi nihil', 'Ganda', 'Rekiri adanya', 2, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(21, 4, 'Item pekerjaan 1', 'm', 'Rencana kedeoan', 'Akn direalisasikan', 'Standart', 'Tidak terdapat rekanan', 1, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(22, 5, 'Ndase ngelu', 'M', '300', '200', '600', 'Maju mundur sentosa', 1, '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(29, 10, 'Kkk', 'M', '120', '30', '70', 'Kshsksh', 1, '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(30, 11, 'aaa', 'm', '23', '22', '33', 'sdfsdfsef', 1, '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportTomorrowPlans`
--

CREATE TABLE `ReportTomorrowPlans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `summary_text` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportTomorrowPlans`
--

INSERT INTO `ReportTomorrowPlans` (`id`, `daily_report_id`, `summary_text`, `created_at`, `updated_at`) VALUES
(2, 2, 'Besok rencananya libur kalau bolehvvb', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 'Apa aja lah', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 'Pekerjaan realidasivb', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, '', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, '', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, '', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 'Bskabdkabsn', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 'dfghjdfgth', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportToolSummaries`
--

CREATE TABLE `ReportToolSummaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `summary_text` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportToolSummaries`
--

INSERT INTO `ReportToolSummaries` (`id`, `daily_report_id`, `summary_text`, `created_at`, `updated_at`) VALUES
(2, 2, 'Akat ringab | Volume: 9 Unit\nAlat ringan 3 | Volume: 7 unit\nAlat super ringan | Volume: 6 unit\nMainan buldozer | Volume: 7 unit', '2026-04-28 16:42:00', '2026-04-29 11:36:11'),
(3, 3, 'Cangkir | Volume: 3o 3', '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(4, 4, 'Kerja alat ringan | Volume: V Unit', '2026-04-29 03:16:36', '2026-04-29 11:36:43'),
(5, 5, 'Sendok garpu | Volume: 80 Unit', '2026-04-29 03:36:02', '2026-04-29 11:50:06'),
(7, 7, '', '2026-04-29 11:37:52', '2026-04-29 11:46:19'),
(8, 8, '', '2026-05-02 00:42:33', '2026-05-02 00:42:33'),
(15, 10, 'Kkk | Volume: 30 Pcs', '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(16, 11, 'sss | Volume: 32 pcs', '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `ReportWorkerUpdates`
--

CREATE TABLE `ReportWorkerUpdates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_report_id` bigint(20) UNSIGNED NOT NULL,
  `worker_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_label` varchar(120) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ReportWorkerUpdates`
--

INSERT INTO `ReportWorkerUpdates` (`id`, `daily_report_id`, `worker_category_id`, `category_label`, `quantity`, `created_at`, `updated_at`) VALUES
(18, 3, 1, 'Pelaksana KSO', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(19, 3, 2, 'Pelaksana Subkon / Vendor', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(20, 3, 3, 'Gudang', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(21, 3, 4, 'Logistik', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(22, 3, 5, 'Peralatan', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(23, 3, 6, 'HSE', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(24, 3, 7, 'QA / QC', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(25, 3, 8, 'Survey', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(26, 3, 9, 'Mekanik & Elektrikal', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(27, 3, 10, 'Pekerja Subkon / Vendor', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(28, 3, 11, 'Pekerja Harian', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(29, 3, 12, 'Tukang', 1, '2026-04-29 00:35:17', '2026-04-29 00:35:17'),
(129, 2, 1, 'Pelaksana KSO', 2, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(130, 2, 2, 'Pelaksana Subkon / Vendor', 3, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(131, 2, 3, 'Gudang', 4, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(132, 2, 4, 'Logistik', 5, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(133, 2, 5, 'Peralatan', 1, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(134, 2, 6, 'HSE', 2, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(135, 2, 7, 'QA / QC', 6, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(136, 2, 8, 'Survey', 8, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(137, 2, 9, 'Mekanik & Elektrikal', 5, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(138, 2, 10, 'Pekerja Subkon / Vendor', 6, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(139, 2, 11, 'Pekerja Harian', 5, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(140, 2, 12, 'Tukang', 9, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(141, 2, NULL, 'Satpam', 7, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(142, 2, NULL, 'Posisi Baru', 6, '2026-04-29 11:36:11', '2026-04-29 11:36:11'),
(143, 4, 1, 'Pelaksana KSO', 1, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(144, 4, 2, 'Pelaksana Subkon / Vendor', 2, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(145, 4, 3, 'Gudang', 2, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(146, 4, 4, 'Logistik', 1, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(147, 4, 6, 'HSE', 2, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(148, 4, 7, 'QA / QC', 5, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(149, 4, 8, 'Survey', 3, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(150, 4, 9, 'Mekanik & Elektrikal', 2, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(151, 4, 10, 'Pekerja Subkon / Vendor', 4, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(152, 4, 11, 'Pekerja Harian', 4, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(153, 4, 12, 'Tukang', 5, '2026-04-29 11:36:43', '2026-04-29 11:36:43'),
(154, 7, NULL, 'Gh', 2, '2026-04-29 11:46:19', '2026-04-29 11:46:19'),
(155, 5, 1, 'Pelaksana KSO', 1, '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(156, 5, 2, 'Pelaksana Subkon / Vendor', 1, '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(157, 5, 8, 'Survey', 1, '2026-04-29 11:50:06', '2026-04-29 11:50:06'),
(177, 10, 5, 'Peralatan', 3, '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(178, 10, 8, 'Survey', 2, '2026-05-02 07:21:01', '2026-05-02 07:21:01'),
(179, 11, 1, 'Pelaksana KSO', 3, '2026-05-06 06:24:39', '2026-05-06 06:24:39'),
(180, 11, 6, 'HSE', 1, '2026-05-06 06:24:39', '2026-05-06 06:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 'Akses penuh monitoring, user management, dan pelaporan.', '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(2, 'Supervisor', 'Supervisor / PIC / Pelaksana', 'User lapangan yang mengisi laporan harian.', '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(3, 'Manager', 'Manager', 'Akses rekap dan trend kemajuan pekerjaan.', '2026-04-22 20:04:09', '2026-04-22 20:04:09');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `email` varchar(160) DEFAULT NULL,
  `username` varchar(60) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `role_id`, `full_name`, `email`, `username`, `phone`, `profile_photo_path`, `password_hash`, `status`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin Teknik', 'admin@gmail.com', 'admin', '08123456789', 'Uploads/Profile/1777141884_072ab76eb41fd606782b.jpeg', '$2y$10$L6Es53f14flvkb/7ZM9uq.xe.mLFXG7YiLoWrbbHiAVDd8BYaG7am', 'Active', '2026-05-17 06:53:30', '2026-04-22 20:04:09', '2026-05-17 06:53:30', NULL),
(9, 2, 'Rahman', NULL, 'Rahman', '6281944806884', NULL, '$2y$10$L6Es53f14flvkb/7ZM9uq.xe.mLFXG7YiLoWrbbHiAVDd8BYaG7am', 'Active', '2026-05-13 10:17:10', '2026-04-28 00:32:55', '2026-05-13 10:17:10', NULL),
(11, 3, 'Ndangak', NULL, 'Rruwetpol', '6281944806885', NULL, '$2y$10$43QVS9WP0umw7f/xPQG0NOvR13eixT3NCPU8rdTpTP64NUTLMIncO', 'Active', '2026-05-13 10:15:37', '2026-04-28 13:29:13', '2026-05-13 10:15:37', NULL),
(12, 2, 'Richy Johannes', NULL, 'Richy', '081573635143', NULL, '$2y$10$pwKqHpDbeDDiVpYCezG7kO3pFr/1tszbhEflkKktUSy0eG5ChFYV2', 'Active', '2026-04-29 03:51:29', '2026-04-28 16:36:30', '2026-04-29 03:51:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `UserSessions`
--

CREATE TABLE `UserSessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `UserSessions`
--

INSERT INTO `UserSessions` (`id`, `user_id`, `session_id`, `ip_address`, `user_agent`, `last_activity_at`, `created_at`, `updated_at`) VALUES
(24, 12, '2c1c367dee6d0868aad106c25ed519bc', '114.10.112.114', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-28 20:49:14', '2026-04-28 20:49:14', '2026-04-28 20:49:14'),
(44, 1, 'b0fa11d4a5bbb36371415e11ae507e48', '2001:448a:50a0:1a58:dd47:e392:4d20:d8a', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-30 03:08:57', '2026-04-30 03:08:57', '2026-04-30 03:08:57'),
(55, 1, 'bae8e30b8452da32807889991bbe5777', '118.99.81.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 11:27:03', '2026-05-04 11:27:03', '2026-05-04 11:27:03'),
(56, 1, '108ba67e29ef0b64f4c9e4ccf3f1ba24', '180.254.66.245', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.4 Mobile/15E148 Safari/604.1', '2026-05-05 03:17:24', '2026-05-05 03:17:24', '2026-05-05 03:17:24'),
(70, 9, 'ad183f33a34ce81f53555925ef3e24b4', '2401:e320:b7a:ba08:2139:f008:924f:eec6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-13 10:17:10', '2026-05-13 10:17:10', '2026-05-13 10:17:10'),
(71, 1, '0577bf62a33c74f431a0c26e2f143e04', '2401:e320:ab1:7f08:df18:e18d:e576:fec0', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-15 05:16:02', '2026-05-15 05:16:02', '2026-05-15 05:16:02'),
(73, 1, '3f55c8c5bf148b70bd2e49c4e21a1c4e', '111.95.21.80', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:150.0) Gecko/20100101 Firefox/150.0', '2026-05-15 08:37:44', '2026-05-15 08:37:44', '2026-05-15 08:37:44'),
(74, 1, '1975f2cce8374d02b85acad8d19ae76f', '2404:c0:2540:1e4d:bdae:c790:3134:8dc2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/148.0.7778.166 Mobile/15E148 Safari/604.1', '2026-05-17 06:51:39', '2026-05-17 06:51:39', '2026-05-17 06:51:39'),
(75, 1, '09fc4ec69ef0e772e926c7ddb4057ede', '36.77.234.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-17 06:53:30', '2026-05-17 06:53:30', '2026-05-17 06:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `WorkerCategories`
--

CREATE TABLE `WorkerCategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `WorkerCategories`
--

INSERT INTO `WorkerCategories` (`id`, `name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pelaksana KSO', 'pelaksana-kso', 1, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(2, 'Pelaksana Subkon / Vendor', 'pelaksana-subkon-vendor', 2, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(3, 'Gudang', 'gudang', 3, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(4, 'Logistik', 'logistik', 4, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(5, 'Peralatan', 'peralatan', 5, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(6, 'HSE', 'hse', 6, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(7, 'QA / QC', 'qa-qc', 7, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(8, 'Survey', 'survey', 8, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(9, 'Mekanik & Elektrikal', 'mekanik-elektrikal', 9, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(10, 'Pekerja Subkon / Vendor', 'pekerja-subkon-vendor', 10, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(11, 'Pekerja Harian', 'pekerja-harian', 11, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09'),
(12, 'Tukang', 'tukang', 12, 1, '2026-04-22 20:04:09', '2026-04-22 20:04:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AuditLogs`
--
ALTER TABLE `AuditLogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_logs_user` (`user_id`),
  ADD KEY `idx_audit_logs_action` (`action`);

--
-- Indexes for table `DailyReports`
--
ALTER TABLE `DailyReports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_daily_reports_code` (`report_code`),
  ADD UNIQUE KEY `uniq_daily_reports_date_user` (`report_date`,`worker_user_id`),
  ADD KEY `idx_daily_reports_status` (`status`),
  ADD KEY `idx_daily_reports_worker` (`worker_user_id`),
  ADD KEY `fk_daily_reports_created_user` (`created_by_user_id`);

--
-- Indexes for table `HeavyEquipmentCategories`
--
ALTER TABLE `HeavyEquipmentCategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_heavy_categories_slug` (`slug`);

--
-- Indexes for table `PasswordResets`
--
ALTER TABLE `PasswordResets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_password_resets_email` (`email`);

--
-- Indexes for table `RefreshTokens`
--
ALTER TABLE `RefreshTokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_refresh_tokens_hash` (`token_hash`),
  ADD KEY `idx_refresh_tokens_user` (`user_id`);

--
-- Indexes for table `ReportHeavyEquipmentUsages`
--
ALTER TABLE `ReportHeavyEquipmentUsages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_heavy_usage_report` (`daily_report_id`),
  ADD KEY `idx_report_heavy_usage_category` (`heavy_equipment_category_id`);

--
-- Indexes for table `ReportLightToolUsages`
--
ALTER TABLE `ReportLightToolUsages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_light_tool_report` (`daily_report_id`);

--
-- Indexes for table `ReportLocations`
--
ALTER TABLE `ReportLocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_locations_report` (`daily_report_id`);

--
-- Indexes for table `ReportMaterialSummaries`
--
ALTER TABLE `ReportMaterialSummaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_material_report` (`daily_report_id`);

--
-- Indexes for table `ReportObstacleSummaries`
--
ALTER TABLE `ReportObstacleSummaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_obstacle_report` (`daily_report_id`);

--
-- Indexes for table `ReportOvertimes`
--
ALTER TABLE `ReportOvertimes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_overtime_report` (`daily_report_id`);

--
-- Indexes for table `ReportPhotos`
--
ALTER TABLE `ReportPhotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_photos_report` (`daily_report_id`);

--
-- Indexes for table `ReportRealizationItems`
--
ALTER TABLE `ReportRealizationItems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_realization_report` (`daily_report_id`);

--
-- Indexes for table `ReportTomorrowPlans`
--
ALTER TABLE `ReportTomorrowPlans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_tomorrow_report` (`daily_report_id`);

--
-- Indexes for table `ReportToolSummaries`
--
ALTER TABLE `ReportToolSummaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_report_tool_report` (`daily_report_id`);

--
-- Indexes for table `ReportWorkerUpdates`
--
ALTER TABLE `ReportWorkerUpdates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_report_worker_updates_report` (`daily_report_id`),
  ADD KEY `idx_report_worker_updates_category` (`worker_category_id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_roles_code` (`code`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_users_username` (`username`),
  ADD UNIQUE KEY `uniq_users_phone` (`phone`),
  ADD UNIQUE KEY `uniq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role_id`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `UserSessions`
--
ALTER TABLE `UserSessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_sessions_session` (`session_id`),
  ADD KEY `idx_user_sessions_user` (`user_id`);

--
-- Indexes for table `WorkerCategories`
--
ALTER TABLE `WorkerCategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_worker_categories_slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `AuditLogs`
--
ALTER TABLE `AuditLogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `DailyReports`
--
ALTER TABLE `DailyReports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `HeavyEquipmentCategories`
--
ALTER TABLE `HeavyEquipmentCategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `PasswordResets`
--
ALTER TABLE `PasswordResets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `RefreshTokens`
--
ALTER TABLE `RefreshTokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ReportHeavyEquipmentUsages`
--
ALTER TABLE `ReportHeavyEquipmentUsages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `ReportLightToolUsages`
--
ALTER TABLE `ReportLightToolUsages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ReportLocations`
--
ALTER TABLE `ReportLocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportMaterialSummaries`
--
ALTER TABLE `ReportMaterialSummaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportObstacleSummaries`
--
ALTER TABLE `ReportObstacleSummaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportOvertimes`
--
ALTER TABLE `ReportOvertimes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportPhotos`
--
ALTER TABLE `ReportPhotos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ReportRealizationItems`
--
ALTER TABLE `ReportRealizationItems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `ReportTomorrowPlans`
--
ALTER TABLE `ReportTomorrowPlans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportToolSummaries`
--
ALTER TABLE `ReportToolSummaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `ReportWorkerUpdates`
--
ALTER TABLE `ReportWorkerUpdates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `UserSessions`
--
ALTER TABLE `UserSessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `WorkerCategories`
--
ALTER TABLE `WorkerCategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuditLogs`
--
ALTER TABLE `AuditLogs`
  ADD CONSTRAINT `fk_audit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `DailyReports`
--
ALTER TABLE `DailyReports`
  ADD CONSTRAINT `fk_daily_reports_created_user` FOREIGN KEY (`created_by_user_id`) REFERENCES `Users` (`id`),
  ADD CONSTRAINT `fk_daily_reports_worker_user` FOREIGN KEY (`worker_user_id`) REFERENCES `Users` (`id`);

--
-- Constraints for table `RefreshTokens`
--
ALTER TABLE `RefreshTokens`
  ADD CONSTRAINT `fk_refresh_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportHeavyEquipmentUsages`
--
ALTER TABLE `ReportHeavyEquipmentUsages`
  ADD CONSTRAINT `fk_report_heavy_usage_category` FOREIGN KEY (`heavy_equipment_category_id`) REFERENCES `HeavyEquipmentCategories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_report_heavy_usage_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportLightToolUsages`
--
ALTER TABLE `ReportLightToolUsages`
  ADD CONSTRAINT `fk_report_light_tool_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportLocations`
--
ALTER TABLE `ReportLocations`
  ADD CONSTRAINT `fk_report_locations_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportMaterialSummaries`
--
ALTER TABLE `ReportMaterialSummaries`
  ADD CONSTRAINT `fk_report_material_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportObstacleSummaries`
--
ALTER TABLE `ReportObstacleSummaries`
  ADD CONSTRAINT `fk_report_obstacle_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportOvertimes`
--
ALTER TABLE `ReportOvertimes`
  ADD CONSTRAINT `fk_report_overtime_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportPhotos`
--
ALTER TABLE `ReportPhotos`
  ADD CONSTRAINT `fk_report_photos_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportRealizationItems`
--
ALTER TABLE `ReportRealizationItems`
  ADD CONSTRAINT `fk_report_realization_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportTomorrowPlans`
--
ALTER TABLE `ReportTomorrowPlans`
  ADD CONSTRAINT `fk_report_tomorrow_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportToolSummaries`
--
ALTER TABLE `ReportToolSummaries`
  ADD CONSTRAINT `fk_report_tool_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ReportWorkerUpdates`
--
ALTER TABLE `ReportWorkerUpdates`
  ADD CONSTRAINT `fk_report_worker_updates_category` FOREIGN KEY (`worker_category_id`) REFERENCES `WorkerCategories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_report_worker_updates_report` FOREIGN KEY (`daily_report_id`) REFERENCES `DailyReports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `Roles` (`id`);

--
-- Constraints for table `UserSessions`
--
ALTER TABLE `UserSessions`
  ADD CONSTRAINT `fk_user_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
