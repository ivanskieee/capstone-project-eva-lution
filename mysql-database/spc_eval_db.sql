-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2025 at 10:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spc_eval_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `academic_id` int(30) NOT NULL,
  `year` text DEFAULT NULL,
  `semester` int(30) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL,
  `status` int(1) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`academic_id`, `year`, `semester`, `is_default`, `status`, `start_date`, `end_date`) VALUES
(20, '2024-2025', 2, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `archives_student_list`
--

CREATE TABLE `archives_student_list` (
  `id` int(11) NOT NULL,
  `student_id` int(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `archive_reason` varchar(255) NOT NULL,
  `archive_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archives_student_list`
--

INSERT INTO `archives_student_list` (`id`, `student_id`, `email`, `archive_reason`, `archive_date`) VALUES
(50, 174, 'erer@1.com', 'Academic period closed', '2025-03-08 17:24:14'),
(51, 175, 'fdgrtt@trewr.com', 'Academic period closed', '2025-03-08 17:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `action_type`, `table_name`, `academic_id`, `user_id`, `date`) VALUES
(5, 'archive', 'student_list', 20, 49, '2025-01-30 14:37:50'),
(6, 'archive', 'student_list', 20, 49, '2025-01-30 16:31:54'),
(7, 'archive', 'student_list', 20, 49, '2025-01-30 17:30:24'),
(8, 'archive', 'student_list', 20, 49, '2025-01-30 17:37:56'),
(9, 'archive', 'student_list', 21, 49, '2025-02-06 21:39:16'),
(10, 'archive', 'student_list', 22, 55, '2025-02-17 09:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `class_id` int(30) NOT NULL,
  `course` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college_faculty_list`
--

CREATE TABLE `college_faculty_list` (
  `faculty_id` int(30) NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `academic_id` int(11) NOT NULL,
  `account_status` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`) VALUES
(3, 'ba_comm', 'BA Communication'),
(4, 'abel', 'BA English Language'),
(5, 'ab_polsci', 'BA Political Science'),
(6, 'bs_math', 'BS Mathematics'),
(7, 'bsba_hrm', 'BSBA - Human Resource Management'),
(8, 'bsba_mm', 'BSBA - Marketing Management'),
(9, 'bsba_ft', 'BSBA - Financial Technology'),
(10, 'bs_entrep', 'BS Entrepreneurship'),
(11, 'bs_pubad', 'BS Public Administration'),
(12, 'bsrem', 'BS Real Estate Management'),
(13, 'beed', 'BEEd - Generalist'),
(14, 'bsed_eng', 'BSEd - English'),
(15, 'bsed_filipino', 'BSEd - Filipino'),
(16, 'bsed_science', 'BSEd - Science'),
(17, 'bsed_math', 'BSEd - Mathematics'),
(18, 'bsed_ss', 'BSEd - Social Studies'),
(19, 'bsed_ve', 'BSEd - Values Education'),
(20, 'btle_he', 'BTLE - Home Economics'),
(21, 'btle_ict', 'BTLE - Information & Communication Technology'),
(22, 'bped', 'BPED - Physical Education'),
(23, 'bsned', 'BSNED - Special Needs Education'),
(24, 'bs_psych', 'BS Psychology'),
(25, 'bsa', 'BS Accountancy'),
(26, 'beced', 'BECEd - Early Childhood Education'),
(27, 'bsn', 'BS Nursing'),
(28, 'bscs', 'BS Computer Science'),
(29, 'bsit', 'BS Information Technology'),
(30, 'bspt', 'BS Physical Therapy'),
(31, 'bsrt', 'BS Radiologic Technology'),
(32, 'bshm', 'BS Hospitality Management'),
(33, 'aradtech', 'Associate in Radiologic Technology'),
(34, 'act', 'Associate in Computer Technology'),
(35, 'ctp_mapeh', 'Certificate in Teaching Program - MAPEH'),
(36, 'ctp_pe', 'Certificate in Teaching Program - PE');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `criteria_id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `faculty_order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`criteria_id`, `criteria`, `faculty_order_by`) VALUES
(164, 'A. Personal Traits', 0),
(169, 'B. Professional Traits', 0),
(170, 'Comments', 0);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_code` varchar(50) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_code`, `department_name`) VALUES
(1, 'ccs', 'College of Computer Studies'),
(2, 'educ', 'College of Education'),
(3, 'cas', 'College of Arts & Sciences'),
(4, 'cba', 'College of Business Administration'),
(5, 'con', 'College of Nursing');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `head_id` int(30) NOT NULL,
  `evaluator_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) DEFAULT NULL,
  `academic_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers_dean_faculty`
--

CREATE TABLE `evaluation_answers_dean_faculty` (
  `evaluation_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `head_id` int(30) NOT NULL,
  `evaluator_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) DEFAULT NULL,
  `academic_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers_faculty_dean`
--

CREATE TABLE `evaluation_answers_faculty_dean` (
  `evaluation_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `head_id` int(30) NOT NULL,
  `evaluator_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) DEFAULT NULL,
  `academic_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers_faculty_faculty`
--

CREATE TABLE `evaluation_answers_faculty_faculty` (
  `evaluation_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `head_id` int(30) NOT NULL,
  `evaluator_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) DEFAULT NULL,
  `academic_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `date_taken` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `head_faculty_list`
--

CREATE TABLE `head_faculty_list` (
  `head_id` int(30) NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `department` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `academic_id` int(11) NOT NULL,
  `account_status` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(293, '', 'd46ba11d5d37ea9d6ae50e45b987aa58', '2025-02-02 05:39:26', '2025-02-01 04:39:26'),
(294, '', 'd4a0d534f9cc4f862af73ca25578b5f4', '2025-02-02 05:47:45', '2025-02-01 04:47:45'),
(295, '', '98c2bea6f39d7c7579e6ab6dc2956e87', '2025-02-02 17:41:21', '2025-02-01 16:41:21'),
(296, '', '73f49898cb253377f3fe2f1a3c70b512', '2025-02-02 18:12:33', '2025-02-01 17:12:33'),
(297, '', '24d8a2c5f648226eaf95d1d01d994d7a', '2025-02-02 18:26:30', '2025-02-01 17:26:30'),
(298, '', '194dc1b4a9ac7c84bec8702984ea223f', '2025-02-02 18:46:07', '2025-02-01 17:46:07'),
(299, '', '35688396575f4da2fd9bb18b8a64bffc', '2025-02-02 18:58:14', '2025-02-01 17:58:14'),
(300, '', '7551197a2db7a2ea3b5ea44f4ba4bbdf', '2025-02-02 19:01:09', '2025-02-01 18:01:09'),
(301, '', 'e76f50688da438c2d833fefa12a054f9', '2025-02-02 19:04:11', '2025-02-01 18:04:11'),
(302, '', '25f86c49b611712f3db94344abf538ce', '2025-02-02 19:32:25', '2025-02-01 18:32:25'),
(303, '', '791a8ec240e3a4506cfc3762c65a19a8', '2025-02-07 14:40:36', '2025-02-06 13:40:36'),
(304, '', '231f9aa9ba4ce3fa1db3181893f5b75b', '2025-02-11 04:42:49', '2025-02-10 03:42:49'),
(305, '', 'd8969b2746a67e71fb46c1da078de4a3', '2025-02-11 15:09:41', '2025-02-10 14:09:41'),
(306, '', '17365d5c7f3173dba393934683fda604', '2025-02-11 15:15:11', '2025-02-10 14:15:11'),
(307, '', '752379962de5c31dd187d1d496c0168c', '2025-02-11 15:18:43', '2025-02-10 14:18:43'),
(308, '', 'e98dd6ca9afff9bc5f1a49cedf737d40', '2025-02-12 01:45:21', '2025-02-11 00:45:21'),
(309, '', '049df4ef33f675e965d5f5178a8acf8a', '2025-02-12 06:09:42', '2025-02-11 05:09:42'),
(310, '', '2e7415be0c3899ab7e8798d7b1eef02e', '2025-02-12 06:53:27', '2025-02-11 05:53:27'),
(311, '', '0c19b51096883d982e1fa46f5e1be37f', '2025-02-12 07:14:34', '2025-02-11 06:14:34'),
(312, '', '846d2ab27892d32ad565739e876aad9b', '2025-02-12 07:19:51', '2025-02-11 06:19:51'),
(313, '', '28ecd623c9b9f228efb00ab6240958c8', '2025-02-12 07:28:19', '2025-02-11 06:28:19'),
(314, '', 'ae69d2a8106b8b2ebb64f89b96d9b010', '2025-02-12 07:34:31', '2025-02-11 06:34:31'),
(315, '', '6670255220ecd68ed839401302e8555b', '2025-02-16 12:07:06', '2025-02-15 11:07:06'),
(316, '', 'db46582a178cb50d5ffe015ede2aa753', '2025-02-17 05:10:51', '2025-02-16 04:10:51'),
(317, '', 'bea4b3da400b34c1a33f439e89ee9550', '2025-02-17 10:48:32', '2025-02-16 09:48:32'),
(318, '', 'e13e78c4c217a6a9d4e096f9a3940bbb', '2025-02-17 10:49:09', '2025-02-16 09:49:09'),
(319, '', 'bb0db55d393aed086559004b42ca2eb9', '2025-02-17 10:50:11', '2025-02-16 09:50:11'),
(320, '', '92006ca0f63b417e32612488d2dcff76', '2025-02-17 10:51:54', '2025-02-16 09:51:54'),
(321, '', '13bce0f7f6e96f1db77587b905ea9106', '2025-02-17 10:53:45', '2025-02-16 09:53:45'),
(322, '', 'c598093bb6fc63b980ed1a08b5b24079', '2025-02-17 10:54:17', '2025-02-16 09:54:17'),
(323, '', 'd317b48ab0586609cd1a86b891f1acfd', '2025-02-17 10:57:21', '2025-02-16 09:57:21'),
(324, '', 'dd03a95f517ecfb09bf5fc2843d86c9d', '2025-02-17 10:58:27', '2025-02-16 09:58:27'),
(325, '', 'f0b0013256ccd25ddddd3992d093ae77', '2025-02-17 10:59:07', '2025-02-16 09:59:07'),
(326, '', '734310fdf1469bfddbb2c5aa16e90106', '2025-02-17 11:06:58', '2025-02-16 10:06:58'),
(327, '', '12643644f952250204edc59de972a0c4', '2025-02-17 11:20:45', '2025-02-16 10:20:45'),
(328, '', '884942294cd4ccaa534ae65a35dbe195', '2025-02-17 11:25:04', '2025-02-16 10:25:04'),
(329, '', '0ac0fd25a80e2e03493d89e79f3e6115', '2025-02-17 14:11:25', '2025-02-16 13:11:25'),
(330, '', '6ca47aa10d1143e74f2cf1c10d25329d', '2025-02-17 14:58:12', '2025-02-16 13:58:12'),
(331, '', '118179199bbb59e8a770cf2fa9db56ab', '2025-02-17 15:10:23', '2025-02-16 14:10:23'),
(332, '', 'e9e01b39daa2fdc8f9532b7957892a6d', '2025-02-18 01:26:07', '2025-02-17 00:26:07'),
(333, '', '2cb804878363b3c2c4098ec51179cdf4', '2025-02-18 08:59:34', '2025-02-17 07:59:34'),
(334, '', 'dec21246de31bf981169e9bd2ec17ff2', '2025-02-18 12:39:27', '2025-02-17 11:39:27'),
(335, '', 'cdb21adde496436b98db298d7bacd4f3', '2025-02-18 12:41:25', '2025-02-17 11:41:25'),
(336, '', '7dc5a54d6a44d562b7a2550733a1923a', '2025-02-18 12:42:13', '2025-02-17 11:42:13'),
(337, '', '76a398f18af1294493da81acadf180c3', '2025-02-18 12:42:53', '2025-02-17 11:42:53'),
(338, '', 'c6d078ed65875e54b577571065852be4', '2025-02-18 12:43:19', '2025-02-17 11:43:19'),
(339, '', '81b6c1d58159405fae187d86b6a054d3', '2025-02-18 12:44:47', '2025-02-17 11:44:47'),
(340, '', '0d12ec19754790fae5da4b3401a2e7e5', '2025-02-18 12:46:11', '2025-02-17 11:46:11'),
(341, '', 'fd3e8ffb4b17367cb8de4f589b38ff1a', '2025-02-18 12:46:35', '2025-02-17 11:46:35'),
(342, '', 'd143cbdd75a0d8abf428fc02a7cfd18c', '2025-02-18 12:47:36', '2025-02-17 11:47:36'),
(343, '', '05716b100662ceae2c9fec8a244be1d6', '2025-02-18 14:02:00', '2025-02-17 13:02:00'),
(344, '', 'f9edb019f74e2eee0c6befa22de5225c', '2025-02-18 14:10:56', '2025-02-17 13:10:56'),
(345, '', 'bc51c07ef709e4f711fc268736a8b117', '2025-02-18 15:37:09', '2025-02-17 14:37:09'),
(346, '', '2c0defbf3d88070ee4d70c925cf39e7a', '2025-02-20 14:30:09', '2025-02-19 13:30:09'),
(347, '', '7573048678f2dada902aa5619acdbc69', '2025-02-20 14:30:57', '2025-02-19 13:30:57'),
(348, '', '83589187380c16a7a5fd4cf9bdc05bc2', '2025-02-20 14:38:30', '2025-02-19 13:38:30'),
(349, '', '5257242483f1723cf5f9d8677e76ae06', '2025-02-25 02:49:12', '2025-02-24 01:49:12'),
(350, '', 'f3829f5ca39cb75e66883a6753ad0f30', '2025-02-25 02:54:14', '2025-02-24 01:54:14'),
(351, '', '76eb52121119e56bd434ef0d6422edd8', '2025-02-25 03:00:51', '2025-02-24 02:00:51'),
(352, '', '5e9aa4e65ae94a3cca9b79a510c01d5f', '2025-02-25 03:05:48', '2025-02-24 02:05:48'),
(353, '', '582a2336aabbbbc1ecfdc40b3a19c702', '2025-02-25 05:01:51', '2025-02-24 04:01:51'),
(354, '', '444e24be3c1b2d109bfacfe06a78d60b', '2025-02-25 05:14:31', '2025-02-24 04:14:31'),
(355, '', '64d475e4eb9db2a94963309a12ec8ec7', '2025-02-25 13:38:11', '2025-02-24 12:38:11'),
(356, '', '094d3f79b5666c0ace15faa23917c388', '2025-02-25 14:15:03', '2025-02-24 13:15:03'),
(357, '', '8c9664f4a80c307c8e14d0adea04c0f9', '2025-02-27 01:52:47', '2025-02-26 00:52:47'),
(358, '', '05b1cb0da8c1f9adf06757f1e872da5a', '2025-02-27 02:02:15', '2025-02-26 01:02:15'),
(359, '', 'f10105aaf2194d1632897d5af0ce09a9', '2025-02-27 02:05:32', '2025-02-26 01:05:32'),
(360, '', '7f17edd76f97615152686c5d1ec929ce', '2025-02-27 02:13:18', '2025-02-26 01:13:18'),
(361, '', '39b4f4e3ed63553ead898e8e559a3c97', '2025-02-27 02:17:48', '2025-02-26 01:17:48'),
(362, 'aybanbrilata05@gmail.com', '37db3e7040726fe95cb7d0e8ad5f5a98af64202723411f67ff4ce3af7d15226437f4a3423545cd39a1152085f7a9f1f19de1', '2025-02-27 02:27:55', '2025-02-26 01:27:55'),
(363, '', '9d0591de9aa020de1a7603fe96a2a461', '2025-03-03 12:09:30', '2025-03-02 11:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `pending_students`
--

CREATE TABLE `pending_students` (
  `school_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `course` varchar(255) NOT NULL,
  `section` varchar(150) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_dean_faculty`
--

CREATE TABLE `question_dean_faculty` (
  `question_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `question_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_dean_faculty`
--

INSERT INTO `question_dean_faculty` (`question_id`, `academic_id`, `question`, `criteria_id`, `question_type`) VALUES
(6, 20, 'This faculty demonstrates strong subject knowledge.', 164, 'mcq'),
(7, 20, 'This faculty maintains and engaging and organized classroom environment.', 164, 'mcq'),
(8, 20, 'This faculty member is found by student and peers to be approachable and easy to talk to', 164, 'mcq'),
(9, 20, 'This faculty meets school expectations and policies in a responsible manner.', 164, 'mcq'),
(10, 20, 'I would recommend this faculty for further professional growth or leadership opportunities.', 164, 'mcq'),
(11, 20, 'The faculty executes each lessons clearly.', 169, 'mcq'),
(12, 20, 'Please write your feedbacks', 170, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `question_faculty_dean`
--

CREATE TABLE `question_faculty_dean` (
  `question_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `question_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_faculty_dean`
--

INSERT INTO `question_faculty_dean` (`question_id`, `academic_id`, `question`, `criteria_id`, `question_type`) VALUES
(6, 20, 'This head collaborates well with peers and contributes to the department.', 164, 'mcq'),
(7, 20, 'The head upholds a high ethical standards and professionalism in the workplace.', 164, 'mcq'),
(8, 20, 'The head contributes to school-wide initiatives, as well as activities inside their respective department organizations.', 164, 'mcq'),
(9, 20, 'The head maintains a positive and productive learning environment.', 164, 'mcq'),
(10, 20, 'Overall, I am satisfied with the head’s contribution to the school.', 164, 'mcq');

-- --------------------------------------------------------

--
-- Table structure for table `question_faculty_faculty`
--

CREATE TABLE `question_faculty_faculty` (
  `question_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `question_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_faculty_faculty`
--

INSERT INTO `question_faculty_faculty` (`question_id`, `academic_id`, `question`, `criteria_id`, `question_type`) VALUES
(13, 20, 'This faculty collaborates effectively with other peers in the department.', 164, 'mcq'),
(14, 20, 'This faculty proves expertise in their subject area.', 164, 'mcq'),
(15, 20, 'This faculty is open to new ideas and procedures.', 164, 'mcq'),
(16, 20, 'Overall, I would recommend this faculty for leadership roles.', 164, 'mcq'),
(18, 20, 'The faculty is approachable and easy to talk to?', 169, 'mcq'),
(19, 20, 'Please write your feedbacks', 170, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `question_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `faculty_order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL,
  `question_type` enum('mcq','text') DEFAULT 'mcq'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`question_id`, `academic_id`, `question`, `faculty_order_by`, `criteria_id`, `question_type`) VALUES
(234, 20, 'shows love for God in words and actions.', 0, 164, 'mcq'),
(235, 20, 'uses decent language and free of distracting mannerisms.', 0, 164, 'mcq'),
(236, 20, 'strives to act as a model worthy of emulation.', 0, 164, 'mcq'),
(237, 20, 'demonstrates self-confidence.', 0, 164, 'mcq'),
(239, 20, 'provides course outline at the start of the semester', 0, 169, 'mcq'),
(240, 20, 'Write your comments to the faculty member', 0, 170, 'text'),
(241, 20, 'manifests integrity and fairness in all his/her dealings with learners.', 0, 164, 'mcq'),
(242, 20, 'always appears well groomed and properly dressed.', 0, 164, 'mcq'),
(243, 20, 'is friendly and approachable.', 0, 164, 'mcq'),
(244, 20, 'is firm but kind.', 0, 164, 'mcq'),
(245, 20, 'is patient and self-controlled.', 0, 164, 'mcq'),
(246, 20, 'maintains composure and emotional maturity.', 0, 164, 'mcq'),
(247, 20, 'announces the requirements of the course at the start of the semester.', 0, 169, 'mcq'),
(248, 20, 'relates subject matter to real life situations.', 0, 169, 'mcq'),
(249, 20, 'applies varied teaching methodologies, techniques, approaches.', 0, 169, 'mcq'),
(250, 20, 'scores, records and returns test paper/other outputs promptly.', 0, 169, 'mcq'),
(251, 20, 'is available for consultation during class hours.', 0, 169, 'mcq'),
(252, 20, 'provides modules that complement the topics covered in our synchronous classes', 0, 169, 'mcq'),
(253, 20, 'provides carefully crafted modules suited to my level of understanding', 0, 169, 'mcq'),
(254, 20, 'provides appropriate application/examples', 0, 169, 'mcq'),
(255, 20, 'issues prelim and mid-term grades two weeks after schedules term exams', 0, 169, 'mcq'),
(256, 20, 'provides additional learning resources and instructional materials', 0, 169, 'mcq'),
(257, 20, 'conducts a synchronous class at least once a week', 0, 169, 'mcq'),
(258, 20, 'prays before and after class, systematically checks attendance.', 0, 169, 'mcq'),
(259, 20, 'has broad knowledge of the subject matter.', 0, 169, 'mcq'),
(260, 20, 'speaks clearly and in a well-modulated voice with correct grammar and pronunciation.', 0, 169, 'mcq'),
(261, 20, 'allows the students to actively participate in the teaching-learning process.', 0, 169, 'mcq'),
(262, 20, 'summarizes the lesson and relates it to the previous or next day’s lesson', 0, 169, 'mcq'),
(263, 20, 'has a fine sense of humor which makes learning easy, exciting and enjoyable.', 0, 169, 'mcq'),
(264, 20, 'comes and dismisses the online class on time.', 0, 169, 'mcq'),
(265, 20, 'maintains order in the online class.', 0, 169, 'mcq');

-- --------------------------------------------------------

--
-- Table structure for table `secondary_faculty_list`
--

CREATE TABLE `secondary_faculty_list` (
  `secondary_id` int(30) NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `self_faculty_eval`
--

CREATE TABLE `self_faculty_eval` (
  `faculty_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `skills` int(11) NOT NULL,
  `performance` int(11) NOT NULL,
  `average_score` int(11) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `self_head_eval`
--

CREATE TABLE `self_head_eval` (
  `faculty_id` int(11) NOT NULL,
  `academic_id` int(11) NOT NULL,
  `skills` int(11) NOT NULL,
  `performance` int(11) NOT NULL,
  `average_score` int(11) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `student_id` int(30) NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `section` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
  `academic_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `account_status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(50) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `department_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_code`, `subject_name`, `department_code`) VALUES
(3, 'GEC1', 'Purposive Communication', 'CCS'),
(4, 'GEC2', 'Understanding the Self', 'CCS'),
(5, 'GEC3', 'Reading in Philippine History', 'CCS'),
(6, 'GEC4', 'Mathematics in the Modern World', 'CCS'),
(7, 'CC101', 'Introduction to Computing', 'CCS'),
(8, 'CC102', 'Fundamentals of Programming', 'CCS'),
(9, 'PATH-FIT', 'Movement Competency Training', 'CCS'),
(10, 'NSTP', 'ROTC11/CWTS11', 'CCS'),
(11, 'GEC5', 'Art Appreciation', 'CCS'),
(12, 'GEC6', 'The Contemporary World', 'CCS'),
(13, 'GEC7', 'Science, Technology & Society', 'CCS'),
(14, 'GEC8', 'Ethics', 'CCS'),
(15, 'CC103', 'Intermediate Programming', 'CCS'),
(16, 'HCI101', 'Introduction to Human-Computer', 'CCS'),
(17, 'DS101', 'Discrete Structures 1', 'CCS'),
(18, 'PATH-FIT2', 'Exercise-Based Fitness Activities', 'CCS'),
(19, 'NSTP2', 'ROTC12/CWTS12', 'CCS'),
(20, 'CC104', 'Data Structure and Algorithms', 'CCS'),
(21, 'AC101', 'Advance Calculus', 'CCS'),
(22, 'OOP101', 'Object-Oriented Programming', 'CCS'),
(23, 'CC105', 'Information Management', 'CCS'),
(24, 'CS ELEC1', 'CS Elective 1', 'CCS'),
(25, 'FILI1', 'Kontekstwalidong Komunikasyon sa Filipino', 'CCS'),
(26, 'LITT1', 'Sosyedad at Literatura/Panitikang Panlipunan', 'CCS'),
(27, 'PATH-FIT3', 'Popular Dance and Other Dance Forms', 'CCS'),
(28, 'RIZAL', 'Life and Works of Rizal', 'CCS'),
(29, 'FILI2', 'Filipino sa Iba\'t-Ibang Disiplina', 'CCS'),
(30, 'PL101', 'Programming Languages', 'CCS'),
(31, 'AL101', 'Algorithms and Complexity', 'CCS'),
(32, 'CC106', 'Applications Development and Emerging Technologies', 'CCS'),
(33, 'CS PROF EL1', 'CS Professional Elective 1', 'CCS'),
(34, 'DS102', 'Discrete Structure 2', 'CCS'),
(35, 'PATH-FIT4', 'Team Sport (Volleyball)', 'CCS'),
(36, 'AL102', 'Automata Theory and Formal Languages', 'CCS'),
(37, 'AR101', 'Architecture and Organization', 'CCS'),
(38, 'SP101', 'Social Issues and Professional Practices', 'CCS'),
(39, 'CS ELEC2', 'CS Elective 2', 'CCS'),
(40, 'OS101', 'Operating System', 'CCS'),
(41, 'NC101', 'Network and Communications', 'CCS'),
(42, 'SE101', 'Software Engineering', 'CCS'),
(43, 'CS ELEC3', 'CS Elective 3', 'CCS'),
(44, 'CS PROF EL2', 'CS Professional Elective 2', 'CCS'),
(45, 'TH101', 'Thesis Writing 1', 'CCS'),
(46, 'CS PROF EL3', 'CS Professional Elective 3', 'CCS'),
(47, 'THS102', 'Thesis Writing 2', 'CCS'),
(48, 'CS PROF EL4', 'CS Professional Elective 4', 'CCS'),
(49, 'CS PROF EL5', 'CS Professional Elective 5', 'CCS'),
(50, 'PRAC101', 'Practicum (250-486 Hours)', 'CCS'),
(51, 'IAS101', 'Information Assurance & Security 1', 'CCS'),
(52, 'CS PROF EL6', 'CS Professional Elective 6', 'CCS'),
(53, 'IT ELEC1', 'IT Elective 1', 'CCS'),
(54, 'IT ELEC2', 'IT Elective 2', 'CCS'),
(55, 'IT PROF EL1', 'IT Professional Elective 1', 'CCS'),
(56, 'MS101', 'Quantitative Methods (Including Modeling and Simulation)', 'CCS'),
(57, 'NET101', 'Networking 1', 'CCS'),
(58, 'IPT101', 'Integrative Programming & Technologies', 'CCS'),
(59, 'IM101', 'Advance Database System', 'CCS'),
(60, 'NE102', 'Networking 2', 'CCS'),
(61, 'SIA101', 'Systems Integration and Architecture 1', 'CCS'),
(62, 'IT PROF EL2', 'IT Professional Elective 2', 'CCS'),
(63, 'IAS102', 'Information Assurance and Security 2', 'CCS'),
(64, 'CAP101', 'Capstone Project and Research 1', 'CCS'),
(65, 'IT PROF EL3', 'IT Professional Elective 3', 'CCS'),
(66, 'SAM101', 'System Administration and Maintenance', 'CCS'),
(67, 'IT ELEC3', 'IT Elective 3', 'CCS'),
(68, 'CAP102', 'Capstone Project and Research 2', 'CCS'),
(69, 'IT PROF EL4', 'IT Professional Elective 4', 'CCS'),
(70, 'ES104', 'Elective 4', 'CCS'),
(71, 'IT PROF EL5', 'IT Professional Elective 5', 'CCS'),
(72, 'IT PROF EL6', 'IT Professional Elective 6', 'CCS'),
(73, 'SOCSI ELECT1', 'General Psychology', 'CCS'),
(74, 'ES101', 'Elective 1', 'CCS'),
(75, 'ES102', 'Elective 2', 'CCS'),
(76, 'FE101', 'Free Elective 1', 'CCS'),
(77, 'ED101', 'Foundations of Education', 'EDUC'),
(78, 'ED102', 'Educational Psychology', 'EDUC'),
(79, 'ED103', 'Curriculum Development', 'EDUC'),
(80, 'ED104', 'Teaching Strategies', 'EDUC'),
(81, 'CAS101', 'Introduction to Humanities', 'CAS'),
(82, 'CAS102', 'Philippine Literature', 'CAS'),
(83, 'CAS103', 'World History', 'CAS'),
(84, 'CAS104', 'Ethics and Logic', 'CAS'),
(85, 'BA101', 'Principles of Marketing', 'CBA'),
(86, 'BA102', 'Financial Management', 'CBA'),
(87, 'BA103', 'Business Law', 'CBA'),
(88, 'BA104', 'Entrepreneurship', 'CBA'),
(89, 'CC114', 'MAKABAGOMAKALUMA', 'ccs'),
(90, 'CC913', 'MABILIS NGA', 'ccs'),
(91, 'ASDLKAS23', 'ASDASDASDASDASDSAAS', 'ccs'),
(92, 'ASDASDAAADSDSADAS', 'ASDASDASDASDAS', 'ccs'),
(93, 'ASDASDASDAS', 'ADASDASDASDASASDASD', 'ccs'),
(94, 'ASHDASHDSAJ', 'ASDHNASJKDHSAJDHSAJDSA', 'ccs'),
(95, 'ASHGDJKSAHJHJASD', 'ASDASDASDSADASDAS', 'ccs');

-- --------------------------------------------------------

--
-- Table structure for table `subjects_course`
--

CREATE TABLE `subjects_course` (
  `id` int(11) NOT NULL,
  `subject_code` varchar(255) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects_course`
--

INSERT INTO `subjects_course` (`id`, `subject_code`, `subject_name`, `course_code`) VALUES
(4, 'GEC1', 'Purposive Communication', 'act'),
(5, 'GEC2', 'Understanding the Self', 'act'),
(6, 'GEC3', 'Reading in Philippine History', 'act'),
(7, 'GEC4', 'Mathematics in the Modern World', 'act'),
(8, 'CC101', 'Introduction to Computing', 'act'),
(9, 'CC102', 'Fundamentals of Programming', 'act'),
(10, 'SOCSI ELECT1', 'General Psychology', 'act'),
(11, 'PATH-FIT', 'Movement Competency Training', 'act'),
(12, 'NSTP', 'ROTC11/CWTS11', 'act'),
(13, 'GEC5', 'Art Appreciation', 'act'),
(14, 'GEC6', 'The Contemporary World', 'act'),
(15, 'GEC7', 'Science, Technology & Society', 'act'),
(16, 'GEC8', 'Ethics', 'act'),
(17, 'CC103', 'Intermediate Programming', 'act'),
(18, 'HCI101', 'Introduction to Human-Computer', 'act'),
(19, 'PATH-FIT2', 'Exercise-Based Fitness Activities', 'act'),
(20, 'NSTP 2', 'ROTC12/CWTS12', 'act'),
(21, 'CC104', 'Data Structure and Algorithms', 'act'),
(22, 'ES101', 'Elective 1', 'act'),
(23, 'ES102', 'Elective 2', 'act'),
(24, 'CC105', 'Information Management', 'act'),
(25, 'LITT1', 'Sosyedad at Literatura/Panitikang Panlipunan', 'act'),
(26, 'PATH-FIT3', 'Popular Dance and Other Dance Forms', 'act'),
(27, 'RIZAL', 'Life and Works of Rizal', 'act'),
(28, 'MS101', 'Quantitative Methods (Including Modeling and Simulation)', 'act'),
(29, 'NET101', 'Networking 1', 'act'),
(30, 'FE101', 'Free Elective 1', 'act'),
(31, 'IPT101', 'Integrative Programming & Technologies', 'act'),
(32, 'PATH-FIT4', 'Team Sport (Volleyball)', 'act'),
(33, 'GEC1', 'Purposive Communication', 'bsit'),
(34, 'GEC2', 'Understanding The Self', 'bsit'),
(35, 'GEC3', 'Reading in Philippine History', 'bsit'),
(36, 'GEC4', 'Mathematics in the Modern World', 'bsit'),
(37, 'CC101', 'Introduction to Computing', 'bsit'),
(38, 'CC102', 'Fundamentals of Programming', 'bsit'),
(39, 'PATH-FIT', 'Movement Competency Training', 'bsit'),
(40, 'NSTP', 'ROTC11/CWTS11', 'bsit'),
(41, 'GEC5', 'Art Appreciation', 'bsit'),
(42, 'GEC6', 'The Contemporary World', 'bsit'),
(43, 'GEC7', 'Science, Technology & Society', 'bsit'),
(44, 'GEC8', 'Ethics', 'bsit'),
(45, 'CC103', 'Intermediate Programming', 'bsit'),
(46, 'HCI101', 'Introduction to Human Computer', 'bsit'),
(47, 'DS101', 'Discrete Structures 1', 'bsit'),
(48, 'PATH-FIT2', 'Exercise-Based Fitness Activities', 'bsit'),
(49, 'NSTP2', 'ROTC12/CWTS12', 'bsit'),
(50, 'CC104', 'Data Structure and Algorithms', 'bsit'),
(51, 'IT ELEC1', 'IT Elective 1', 'bsit'),
(52, 'IT ELEC2', 'IT Elective 2', 'bsit'),
(53, 'CC105', 'Information Management', 'bsit'),
(54, 'CS ELEC1', 'CS Elective 1', 'bsit'),
(55, 'FILI1', 'Kontekstuwalisadong Komunikasyon sa Filipino', 'bsit'),
(56, 'LITT1', 'Sosyedad at Literatura/Panitikang Panlipunan', 'bsit'),
(57, 'PATH-FIT3', 'Popular Dance and Other Dance Forms', 'bsit'),
(58, 'RIZAL', 'Life and Works of Rizal', 'bsit'),
(59, 'FILI2', 'Filipino sa Ibat-Ibang Disiplina', 'bsit'),
(60, 'MS101', 'Quantitative Methods (Including Modeling and Simulation)', 'bsit'),
(61, 'NET101', 'Networking 1', 'bsit'),
(62, 'IT PROF EL1', 'IT Professional Elective 1', 'bsit'),
(63, 'IPT101', 'Integrative Prog. & Technologies', 'bsit'),
(64, 'PATH-FIT4', 'Team Sport (Volleyball)', 'bsit'),
(65, 'IM101', 'Advance Database System', 'bsit'),
(66, 'NE102', 'Networking 2', 'bsit'),
(67, 'SIA101', 'Systems Integration and Architecture 1', 'bsit'),
(68, 'SP101', 'Social and Professional Practices', 'bsit'),
(69, 'IT PROF EL2', 'IT Professional Elective 2', 'bsit'),
(70, 'IAS101', 'Information Assurance & Security 1', 'bsit'),
(71, 'CC106', 'Application Development and Emerging Technologies', 'bsit'),
(72, 'IT PROF EL3', 'IT Professional Elective 3', 'bsit'),
(73, 'SAM101', 'System Administration and Maintenance', 'bsit'),
(74, 'IT ELEC3', 'IT Elective 3', 'bsit'),
(75, 'IAS102', 'Information Assurance and Security 2', 'bsit'),
(76, 'CAP101', 'Capstone Project and Research 1', 'bsit'),
(77, 'IT PROF EL4', 'IT Professional Elective 4', 'bsit'),
(78, 'ES104', 'Elective 4', 'bsit'),
(79, 'CAP102', 'Capstone Project and Research 2', 'bsit'),
(80, 'IT PROF EL5', 'IT Professional Elective 5', 'bsit'),
(81, 'IT PROF EL6', 'IT Professional Elective 6', 'bsit'),
(82, 'PRAC101', 'Practicum (486 Hours)', 'bsit'),
(83, 'hrm101', 'Human Resource Fundamentals', 'bsba_hrm'),
(84, 'mm101', 'Marketing Principles', 'bsba_mm'),
(85, 'ft101', 'Financial Tech Innovations', 'bsba_ft'),
(86, 'ed102', 'asdasdas', 'beed'),
(87, 'math101', 'Mathematical Analysis', 'bs_math'),
(88, 'math102', 'Statistics and Probability', 'bs_math'),
(89, 'GEC1', 'Purposive Communication', 'bscs'),
(90, 'GEC2', 'Understanding the Self', 'bscs'),
(91, 'GEC3', 'Reading in Philippine History', 'bscs'),
(92, 'GEC4', 'Mathematics in the Modern World', 'bscs'),
(93, 'CC101', 'Introduction to Computing', 'bscs'),
(94, 'CC102', 'Fundamentals of Programming', 'bscs'),
(95, 'PATH-FIT', 'Movement Competency Training', 'bscs'),
(96, 'NSTP', 'ROTC11/CWTS11', 'bscs'),
(97, 'GEC5', 'Art Appreciation', 'bscs'),
(98, 'GEC6', 'The Contemporary World', 'bscs'),
(99, 'GEC7', 'Science, Technology & Society', 'bscs'),
(100, 'GEC8', 'Ethics', 'bscs'),
(101, 'CC103', 'Intermediate Programming', 'bscs'),
(102, 'HCI101', 'Introduction to Human Computer', 'bscs'),
(103, 'DS101', 'Discrete Structures 1', 'bscs'),
(104, 'PATH-FIT2', 'Exercise-Based Fitness Activities', 'bscs'),
(105, 'NSTP2', 'ROTC12/CWTS12', 'bscs');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `subject_id` int(15) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(15) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `academic_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `academic_id`, `date_created`) VALUES
(49, 'SPC', 'admin', 'spc@admin.com', '$2y$10$wmwcFX8dInqYbb.1clkxNOKDGFYBsvWpKPasHVfSepAy5/Li7pwi.', '', 20, '2025-01-29 21:02:56'),
(55, 'SPC', 'cos-admin', 'spc@coadmin.com', '$2y$10$isYMw2Rl2vcovgTWzs1O5.Ln0xnJotkAWuVV/mvxnjOI0IZd39zcS', '', 20, '2025-02-16 13:34:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`academic_id`);

--
-- Indexes for table `archives_student_list`
--
ALTER TABLE `archives_student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `college_faculty_list`
--
ALTER TABLE `college_faculty_list`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`criteria_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `evaluation_answers_dean_faculty`
--
ALTER TABLE `evaluation_answers_dean_faculty`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `evaluation_answers_faculty_dean`
--
ALTER TABLE `evaluation_answers_faculty_dean`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `evaluation_answers_faculty_faculty`
--
ALTER TABLE `evaluation_answers_faculty_faculty`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `head_faculty_list`
--
ALTER TABLE `head_faculty_list`
  ADD PRIMARY KEY (`head_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_students`
--
ALTER TABLE `pending_students`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `question_dean_faculty`
--
ALTER TABLE `question_dean_faculty`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_faculty_dean`
--
ALTER TABLE `question_faculty_dean`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_faculty_faculty`
--
ALTER TABLE `question_faculty_faculty`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `secondary_faculty_list`
--
ALTER TABLE `secondary_faculty_list`
  ADD PRIMARY KEY (`secondary_id`);

--
-- Indexes for table `self_faculty_eval`
--
ALTER TABLE `self_faculty_eval`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `self_head_eval`
--
ALTER TABLE `self_head_eval`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects_course`
--
ALTER TABLE `subjects_course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `academic_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `archives_student_list`
--
ALTER TABLE `archives_student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `class_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `college_faculty_list`
--
ALTER TABLE `college_faculty_list`
  MODIFY `faculty_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `criteria_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1641;

--
-- AUTO_INCREMENT for table `evaluation_answers_dean_faculty`
--
ALTER TABLE `evaluation_answers_dean_faculty`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `evaluation_answers_faculty_dean`
--
ALTER TABLE `evaluation_answers_faculty_dean`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `evaluation_answers_faculty_faculty`
--
ALTER TABLE `evaluation_answers_faculty_faculty`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `head_faculty_list`
--
ALTER TABLE `head_faculty_list`
  MODIFY `head_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=364;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `question_dean_faculty`
--
ALTER TABLE `question_dean_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `question_faculty_dean`
--
ALTER TABLE `question_faculty_dean`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `question_faculty_faculty`
--
ALTER TABLE `question_faculty_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `question_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT for table `secondary_faculty_list`
--
ALTER TABLE `secondary_faculty_list`
  MODIFY `secondary_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `self_faculty_eval`
--
ALTER TABLE `self_faculty_eval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `self_head_eval`
--
ALTER TABLE `self_head_eval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `subjects_course`
--
ALTER TABLE `subjects_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `subject_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
