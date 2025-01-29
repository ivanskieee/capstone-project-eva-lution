-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 06:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
(17, '2025-2026', 1, 0, 2, NULL, NULL),
(18, '2027-2028', 1, 1, 1, '2025-01-28', '2026-01-28');

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
(1, 89, 'beia@siomaipenge.com', '', '2025-01-26 15:36:46'),
(2, 90, 'mary@lucido.com', '', '2025-01-26 15:36:46'),
(4, 98, 'beia@siomaipenge.com', '', '2025-01-26 15:36:46'),
(5, 99, 'beia@siomaipenge.com', '', '2025-01-26 15:36:46'),
(6, 100, 'brilataivan86@gmail.com', 'Academic period closed', '2025-01-26 15:44:13'),
(7, 101, 'brilataivan86@gmail.com', 'Academic period closed', '2025-01-26 15:50:05'),
(8, 102, 'brilataivan86@gmail.com', 'Academic period closed', '2025-01-29 00:12:55');

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
(1, '0', '0', 17, 5, '2025-01-26 15:44:13'),
(2, 'archive', 'student_list', 17, 5, '2025-01-26 15:50:05'),
(3, 'archive', 'student_list', 17, 5, '2025-01-29 00:12:55');

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

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`class_id`, `course`, `level`, `section`) VALUES
(5, 'CCS', '3', 'B'),
(6, 'BSED', '4', 'C'),
(8, 'CCS', '3', 'BSIT');

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

--
-- Dumping data for table `college_faculty_list`
--

INSERT INTO `college_faculty_list` (`faculty_id`, `school_id`, `firstname`, `lastname`, `subject`, `department`, `email`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(62, '0001', 'jorge', 'resurreccion', 'cc101', 'ccs', 'jorge@res.com', '$2y$10$i8Xsn.HQBClMwYWZzppgFu2HiJtqYLEOZOysezHFgvzkbPWQTKiDm', '', 18, 0, '2025-01-15 14:06:44'),
(63, '0002', 'joann', 'ganda', 'cc101, cc102', 'ccs', 'joann@ganda.com', '$2y$10$xPY1Is8BtL8RJ4SXsx.qmuarDtKcEZLuZF0CXqiAz9r/OQ2ebEHvG', '', 18, 0, '2025-01-15 19:22:04'),
(64, '0003', 'kyle', 'ronyo', 'cc108', 'educ', 'kyle@ron.com', '$2y$10$Vz0LYRqq5/A3OY256zX5EuFuncP2X18EIRulwbXfIvk6LvTLn09b6', '', 18, 0, '2025-01-15 21:59:20'),
(65, '0007', 'nizzle', 'brinas', 'cc108', 'educ', 'niz@brin.com', '$2y$10$acW2mAdoT5Sv9N85iqHiWes841PnNah80H5j04RxY5WDZP3.OF4sK', '', 18, 0, '2025-01-25 13:46:15');

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
(162, 'Evaluation Questions', 0);

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

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `faculty_id`, `student_id`, `head_id`, `evaluator_id`, `question_id`, `rate`, `academic_id`, `comment`) VALUES
(353, 62, 88, 0, 0, 224, 4, 17, ''),
(354, 62, 88, 0, 0, 225, 3, 17, ''),
(355, 62, 88, 0, 0, 226, 4, 17, ''),
(356, 62, 88, 0, 0, 227, 3, 17, ''),
(357, 62, 88, 0, 0, 228, 4, 17, ''),
(358, 62, 88, 0, 0, 230, NULL, 17, 'good job well done!'),
(359, 63, 88, 0, 0, 224, 2, 17, ''),
(360, 63, 88, 0, 0, 225, 2, 17, ''),
(361, 63, 88, 0, 0, 226, 2, 17, ''),
(362, 63, 88, 0, 0, 227, 2, 17, ''),
(363, 63, 88, 0, 0, 228, 2, 17, ''),
(364, 63, 88, 0, 0, 230, NULL, 17, 'better luck\r\n'),
(365, 62, 98, 0, 0, 224, 4, 18, ''),
(366, 62, 98, 0, 0, 225, 3, 18, ''),
(367, 62, 98, 0, 0, 226, 3, 18, ''),
(368, 62, 98, 0, 0, 227, 4, 18, ''),
(369, 62, 98, 0, 0, 228, 3, 18, ''),
(370, 62, 98, 0, 0, 230, NULL, 18, 'siomai good job!'),
(371, 63, 98, 0, 0, 224, 4, 18, ''),
(372, 63, 98, 0, 0, 225, 3, 18, ''),
(373, 63, 98, 0, 0, 226, 4, 18, ''),
(374, 63, 98, 0, 0, 227, 4, 18, ''),
(375, 63, 98, 0, 0, 228, 4, 18, ''),
(376, 63, 98, 0, 0, 230, NULL, 18, 'good job siomai na'),
(377, 62, 99, 0, 0, 224, 4, 17, ''),
(378, 62, 99, 0, 0, 225, 3, 17, ''),
(379, 62, 99, 0, 0, 226, 4, 17, ''),
(380, 62, 99, 0, 0, 227, 3, 17, ''),
(381, 62, 99, 0, 0, 228, 3, 17, ''),
(382, 62, 99, 0, 0, 230, NULL, 17, 'galing mo siomai'),
(383, 63, 99, 0, 0, 224, 4, 17, ''),
(384, 63, 99, 0, 0, 225, 3, 17, ''),
(385, 63, 99, 0, 0, 226, 3, 17, ''),
(386, 63, 99, 0, 0, 227, 3, 17, ''),
(387, 63, 99, 0, 0, 228, 3, 17, ''),
(388, 63, 99, 0, 0, 230, NULL, 17, 'lakas siomai'),
(389, 62, 100, 0, 0, 224, 3, 17, ''),
(390, 62, 100, 0, 0, 225, 3, 17, ''),
(391, 62, 100, 0, 0, 226, 4, 17, ''),
(392, 62, 100, 0, 0, 227, 4, 17, ''),
(393, 62, 100, 0, 0, 228, 4, 17, ''),
(394, 62, 100, 0, 0, 230, NULL, 17, 'good good'),
(395, 63, 100, 0, 0, 224, 4, 17, ''),
(396, 63, 100, 0, 0, 225, 4, 17, ''),
(397, 63, 100, 0, 0, 226, 4, 17, ''),
(398, 63, 100, 0, 0, 227, 4, 17, ''),
(399, 63, 100, 0, 0, 228, 4, 17, ''),
(400, 63, 100, 0, 0, 230, NULL, 17, 'verygood!!'),
(401, 62, 101, 0, 0, 224, 2, 17, ''),
(402, 62, 101, 0, 0, 225, 2, 17, ''),
(403, 62, 101, 0, 0, 226, 2, 17, ''),
(404, 62, 101, 0, 0, 227, 1, 17, ''),
(405, 62, 101, 0, 0, 228, 1, 17, ''),
(406, 62, 101, 0, 0, 230, NULL, 17, 'bawi lods'),
(407, 63, 101, 0, 0, 224, 4, 17, ''),
(408, 63, 101, 0, 0, 225, 4, 17, ''),
(409, 63, 101, 0, 0, 226, 3, 17, ''),
(410, 63, 101, 0, 0, 227, 3, 17, ''),
(411, 63, 101, 0, 0, 228, 3, 17, ''),
(412, 63, 101, 0, 0, 230, NULL, 17, 'galing\r\n'),
(461, 62, 102, 0, 0, 224, 4, 17, ''),
(462, 62, 102, 0, 0, 225, 4, 17, ''),
(463, 62, 102, 0, 0, 226, 3, 17, ''),
(464, 62, 102, 0, 0, 227, 3, 17, ''),
(465, 62, 102, 0, 0, 228, 3, 17, ''),
(466, 62, 102, 0, 0, 230, NULL, 17, 'asdasasddaa'),
(467, 63, 102, 0, 0, 224, 2, 17, ''),
(468, 63, 102, 0, 0, 225, 2, 17, ''),
(469, 63, 102, 0, 0, 226, 2, 17, ''),
(470, 63, 102, 0, 0, 227, 2, 17, ''),
(471, 63, 102, 0, 0, 228, 2, 17, ''),
(472, 63, 102, 0, 0, 230, NULL, 17, 'asdasdadasd'),
(473, 62, 103, 0, 0, 224, 1, 18, ''),
(474, 62, 103, 0, 0, 225, 1, 18, ''),
(475, 62, 103, 0, 0, 226, 1, 18, ''),
(476, 62, 103, 0, 0, 227, 1, 18, ''),
(477, 62, 103, 0, 0, 228, 1, 18, ''),
(478, 62, 103, 0, 0, 230, NULL, 18, 'bawi ka'),
(479, 63, 103, 0, 0, 224, 4, 18, ''),
(480, 63, 103, 0, 0, 225, 4, 18, ''),
(481, 63, 103, 0, 0, 226, 4, 18, ''),
(482, 63, 103, 0, 0, 227, 4, 18, ''),
(483, 63, 103, 0, 0, 228, 4, 18, ''),
(484, 63, 103, 0, 0, 230, NULL, 18, 'good very job');

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

--
-- Dumping data for table `evaluation_answers_dean_faculty`
--

INSERT INTO `evaluation_answers_dean_faculty` (`evaluation_id`, `faculty_id`, `head_id`, `evaluator_id`, `question_id`, `rate`, `academic_id`, `comment`) VALUES
(1, 64, 0, 19, 5, 4, 17, ''),
(2, 65, 0, 19, 5, 3, 17, ''),
(5, 62, 0, 18, 5, 2, 17, ''),
(6, 63, 0, 18, 5, 4, 17, ''),
(7, 62, 0, 18, 5, 1, 18, ''),
(8, 63, 0, 18, 5, 2, 18, ''),
(9, 64, 0, 19, 5, 1, 18, ''),
(10, 65, 0, 19, 5, 3, 18, '');

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

--
-- Dumping data for table `evaluation_answers_faculty_dean`
--

INSERT INTO `evaluation_answers_faculty_dean` (`evaluation_id`, `faculty_id`, `head_id`, `evaluator_id`, `question_id`, `rate`, `academic_id`, `comment`) VALUES
(1, 19, 0, 64, 5, 4, 17, ''),
(2, 18, 0, 62, 5, 4, 17, ''),
(3, 18, 0, 63, 5, 4, 17, ''),
(4, 18, 0, 62, 5, 4, 18, ''),
(5, 19, 0, 65, 5, 1, 18, '');

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

--
-- Dumping data for table `evaluation_answers_faculty_faculty`
--

INSERT INTO `evaluation_answers_faculty_faculty` (`evaluation_id`, `faculty_id`, `head_id`, `evaluator_id`, `question_id`, `rate`, `academic_id`, `comment`) VALUES
(4, 65, 0, 64, 11, 4, 17, ''),
(5, 65, 0, 64, 12, 4, 17, ''),
(10, 63, 0, 62, 11, 4, 17, ''),
(11, 63, 0, 62, 12, 4, 17, ''),
(12, 63, 0, 62, 11, 1, 18, ''),
(13, 63, 0, 62, 12, 1, 18, ''),
(14, 64, 0, 65, 11, 4, 18, ''),
(15, 64, 0, 65, 12, 3, 18, '');

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

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(1, 1, 2, 3, 1, 1, 1, '2024-10-01 05:23:49');

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

--
-- Dumping data for table `head_faculty_list`
--

INSERT INTO `head_faculty_list` (`head_id`, `school_id`, `firstname`, `lastname`, `email`, `department`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(18, '0001', 'jayson', 'guia', 'jay@jay.com', 'ccs', '$2y$10$jI8wOFskjwH.CBsOLsukg.mZT6jEGeQKCBS5Kr/2.8v9xNGGvlJaS', '', 18, 0, '2025-01-15 21:58:10'),
(19, '0002', 'nizzle', 'brinas', 'nizzle@brinas.com', 'educ', '$2y$10$tZrBNHrqyGfzj0HrvoIg0.oe.r7z9YDZvPxNUo4B8d9C2KW8AISda', '', 18, 0, '2025-01-15 23:10:32');

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
(269, '', 'd94b839978d68a7590b7c900ad3ba601', '2025-01-29 09:50:23', '2025-01-28 08:50:23'),
(270, '', '1dac52f0c81ce6d616f2b09e478f0ac0', '2025-01-29 17:19:00', '2025-01-28 16:19:00');

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
(5, 17, 'The faculty executes each lessons clearly.', 162, 'mcq');

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
(5, 17, 'The faculty executes each lessons clearly.', 162, 'mcq');

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
(11, 17, 'asdsadas', 162, 'mcq'),
(12, 17, 'asdasdasasd', 162, 'mcq');

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
(224, 17, 'The faculty executes each lessons clearly.', 0, 162, 'mcq'),
(225, 17, 'The faculty encourages students to engage with the class discussions.', 0, 162, 'mcq'),
(226, 17, 'The faculty is approachable and easy to talk to.', 0, 162, 'mcq'),
(227, 17, 'The faculty is consistent in providing scores and feedbacks on quizzes and exams.', 0, 162, 'mcq'),
(228, 17, 'Overall, I am satisfied with the faculty’s performance.', 0, 162, 'mcq'),
(230, 17, 'can you comment for your prof?', 0, 162, 'text');

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

--
-- Dumping data for table `self_faculty_eval`
--

INSERT INTO `self_faculty_eval` (`faculty_id`, `academic_id`, `skills`, `performance`, `average_score`, `feedback`, `comments`, `id`) VALUES
(65, 17, 4, 4, 4, 'Excellent performance!', 'goods', 9),
(65, 18, 1, 1, 1, 'Needs significant improvement.', 'asdasdasdsadas', 10);

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

--
-- Dumping data for table `self_head_eval`
--

INSERT INTO `self_head_eval` (`faculty_id`, `academic_id`, `skills`, `performance`, `average_score`, `feedback`, `comments`, `id`) VALUES
(19, 0, 4, 4, 4, 'Excellent performance!', 'good', 6),
(18, 17, 4, 4, 4, 'Excellent performance!', 'good job\r\n', 7),
(18, 17, 1, 1, 1, 'Needs significant improvement.', 'asdasadasd', 8);

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

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`student_id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `subject`, `section`, `avatar`, `academic_id`, `date_created`, `account_status`) VALUES
(103, '1001', 'van', 'bri', 'brilataivan86@gmail.com', '$2y$10$6MeZAW9FMLa71CfFH2V5JO62sU2QFgYLq4zSlXj6ACn1MDzSxYKmC', 'cc101', 'bsit', '', 18, '2025-01-29 00:19:47', 1);

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

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`subject_id`, `code`, `subject`, `description`) VALUES
(2, 'CC101', 'MATHEMATICS', 'ASDSADASSADASAS'),
(45, 'ES101', 'entrepreneur', 'sdafasdfasd');

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
(5, 'test', 'admin', 'test@admin.com', '$2y$10$J6OgDIT6/U/FNMAqBki6YeWUEX7V3dzmUUaZ8zniF151cuQ.HhIHq', 'admin.jpg', 18, '2024-09-27 13:13:20');

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
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`criteria_id`);

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
  MODIFY `academic_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `archives_student_list`
--
ALTER TABLE `archives_student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `faculty_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `criteria_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

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
  MODIFY `head_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `question_dean_faculty`
--
ALTER TABLE `question_dean_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `question_faculty_dean`
--
ALTER TABLE `question_faculty_dean`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `question_faculty_faculty`
--
ALTER TABLE `question_faculty_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `question_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

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
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `subject_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
