-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 04:03 AM
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
(20, '2024-2025', 2, 0, 2, NULL, NULL),
(21, '2025-2026', 1, 0, 2, NULL, NULL),
(22, '2026-2027', 1, 1, 1, '2025-02-06', '2026-02-06'),
(23, '2026-2027', 2, 0, 0, NULL, NULL);

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
(10, 105, 'gabrieldorado09@gmail.com', 'Academic period closed', '2025-01-30 17:30:24'),
(11, 106, '15equips@gmail.com', 'Academic period closed', '2025-01-30 17:37:56'),
(12, 107, '15equips@gmail.com', 'Academic period closed', '2025-02-06 21:39:16'),
(13, 108, 'brilataivan86@gmail.com', 'Academic period closed', '2025-02-06 21:39:16'),
(14, 118, 'sadfdsafdsaf@ga.com', 'Academic period closed', '2025-02-06 21:39:16'),
(15, 119, 'aybanbrilata05@gmail.com', 'Academic period closed', '2025-02-06 21:39:16');

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
(9, 'archive', 'student_list', 21, 49, '2025-02-06 21:39:16');

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

--
-- Dumping data for table `college_faculty_list`
--

INSERT INTO `college_faculty_list` (`faculty_id`, `school_id`, `firstname`, `lastname`, `subject`, `department`, `email`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(67, '1001', 'joann', 'lopez', 'cc101, cc102', 'ccs', 'joann@lopez.com', '$2y$10$PpQSWK0gDAlZbU1H0mOEaeBXFPBDRMvEh5uCHTFAPc8WNQjmQo7p2', '', 22, 0, '2025-01-29 21:46:39'),
(68, '1002', 'apol', 'capuno', 'cc103', 'ccs', 'apol@capuno.com', '$2y$10$tcjA1L9dnHt1.MoOM7XxiO2.6O0nfLUGCkOIWZuiSejM3OUmG42YC', '', 22, 0, '2025-01-29 21:48:01'),
(69, '1003', 'solejaena', 'herrera', 'cc104', 'ccs', 'solejaena@herrera.com', '$2y$10$Ty.6YNHI0JMNvzAClxsZ/el1gkkIa12YRbr8.F3W3wEh0DmNFxMOO', '', 22, 0, '2025-01-29 21:51:09'),
(70, '1004', 'aaron', 'lagaya', 'cc105', 'ccs', 'aaron@lagaya.com', '$2y$10$f593oA6tlVDsbFL3t6Tyj.D2Gs7bbwQ44KIqb1X5dCJxbPOE4ykta', '', 22, 0, '2025-01-29 21:51:54'),
(72, '12233', 'Juan', 'Dela Cruz', 'gec7', 'cas', '10equips@gmail.com', '$2y$10$VPPIyU2S06aYZR4VKxAVme2grPZbZIMfb08zFhJi/TOckGrDDGvcm', '', 22, 0, '2025-01-30 17:33:33'),
(74, '0006', 'klein', 'ligs', 'ES104, IT PROF EL5', 'ccs', 'klein@darelleligs.com', '$2y$10$KghmfL9S0AlorjNP7XwG.uBj3KmIDD98ulV3UMIP6MSIqdDvUIqhu', '', 22, 0, '2025-02-01 12:42:14'),
(75, '23232', 'gab', 'darelle', 'ES104, IT PROF EL5', 'ccs', 'gab@darelle.com', '$2y$10$y7iUlRxoCK5zS.GPGxa.c.ebyMvCiIsqL.9xIg1ZsCoLHUL4t0iuG', '', 22, 0, '2025-02-02 02:39:00');

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
(485, 67, 107, 0, 0, 233, 4, 21, ''),
(486, 67, 107, 0, 0, 234, 1, 21, ''),
(487, 67, 107, 0, 0, 235, 2, 21, ''),
(488, 67, 107, 0, 0, 236, 3, 21, ''),
(489, 67, 107, 0, 0, 237, 4, 21, ''),
(490, 67, 107, 0, 0, 238, NULL, 21, 'the best!!!'),
(491, 67, 107, 0, 0, 239, 4, 21, ''),
(492, 72, 107, 0, 0, 233, 3, 21, ''),
(493, 72, 107, 0, 0, 234, 2, 21, ''),
(494, 72, 107, 0, 0, 235, 3, 21, ''),
(495, 72, 107, 0, 0, 236, 4, 21, ''),
(496, 72, 107, 0, 0, 237, 2, 21, ''),
(497, 72, 107, 0, 0, 238, NULL, 21, 'ang bakal na crus'),
(498, 72, 107, 0, 0, 239, 2, 21, ''),
(499, 67, 108, 0, 0, 233, 3, 21, ''),
(500, 67, 108, 0, 0, 234, 2, 21, ''),
(501, 67, 108, 0, 0, 235, 3, 21, ''),
(502, 67, 108, 0, 0, 236, 3, 21, ''),
(503, 67, 108, 0, 0, 237, 2, 21, ''),
(504, 67, 108, 0, 0, 238, NULL, 21, 'high'),
(505, 67, 108, 0, 0, 239, 2, 21, ''),
(506, 74, 110, 0, 0, 233, 1, 21, ''),
(507, 74, 110, 0, 0, 234, 1, 21, ''),
(508, 74, 110, 0, 0, 235, 1, 21, ''),
(509, 74, 110, 0, 0, 236, 1, 21, ''),
(510, 74, 110, 0, 0, 237, 1, 21, ''),
(511, 74, 110, 0, 0, 238, NULL, 21, 'ay ang tanong?'),
(512, 74, 110, 0, 0, 239, 4, 21, ''),
(513, 74, 119, 0, 0, 233, 4, 21, ''),
(514, 74, 119, 0, 0, 234, 4, 21, ''),
(515, 74, 119, 0, 0, 235, 4, 21, ''),
(516, 74, 119, 0, 0, 236, 3, 21, ''),
(517, 74, 119, 0, 0, 237, 3, 21, ''),
(518, 74, 119, 0, 0, 239, 3, 21, ''),
(519, 74, 119, 0, 0, 240, NULL, 21, 'goods brother!'),
(520, 74, 118, 0, 0, 233, 1, 21, ''),
(521, 74, 118, 0, 0, 234, 1, 21, ''),
(522, 74, 118, 0, 0, 235, 1, 21, ''),
(523, 74, 118, 0, 0, 236, 1, 21, ''),
(524, 74, 118, 0, 0, 237, 1, 21, ''),
(525, 74, 118, 0, 0, 239, 1, 21, ''),
(526, 74, 118, 0, 0, 240, NULL, 21, 'bad job'),
(527, 74, 120, 0, 0, 233, 4, 22, ''),
(528, 74, 120, 0, 0, 234, 4, 22, ''),
(529, 74, 120, 0, 0, 235, 3, 22, ''),
(530, 74, 120, 0, 0, 236, 3, 22, ''),
(531, 74, 120, 0, 0, 237, 3, 22, ''),
(532, 74, 120, 0, 0, 239, 3, 22, ''),
(533, 74, 120, 0, 0, 240, NULL, 22, 'asdasasdasasdasasdasdasas'),
(534, 75, 120, 0, 0, 233, 3, 22, ''),
(535, 75, 120, 0, 0, 234, 4, 22, ''),
(536, 75, 120, 0, 0, 235, 4, 22, ''),
(537, 75, 120, 0, 0, 236, 3, 22, ''),
(538, 75, 120, 0, 0, 237, 3, 22, ''),
(539, 75, 120, 0, 0, 239, 3, 22, ''),
(540, 75, 120, 0, 0, 240, NULL, 22, 'asdasdasdaasdasdas'),
(541, 74, 122, 0, 0, 234, 4, 22, ''),
(542, 74, 122, 0, 0, 235, 3, 22, ''),
(543, 74, 122, 0, 0, 236, 3, 22, ''),
(544, 74, 122, 0, 0, 237, 3, 22, ''),
(545, 74, 122, 0, 0, 241, 4, 22, ''),
(546, 74, 122, 0, 0, 242, 4, 22, ''),
(547, 74, 122, 0, 0, 243, 4, 22, ''),
(548, 74, 122, 0, 0, 244, 4, 22, ''),
(549, 74, 122, 0, 0, 245, 4, 22, ''),
(550, 74, 122, 0, 0, 246, 4, 22, ''),
(551, 74, 122, 0, 0, 239, 3, 22, ''),
(552, 74, 122, 0, 0, 247, 3, 22, ''),
(553, 74, 122, 0, 0, 248, 3, 22, ''),
(554, 74, 122, 0, 0, 249, 3, 22, ''),
(555, 74, 122, 0, 0, 250, 2, 22, ''),
(556, 74, 122, 0, 0, 251, 2, 22, ''),
(557, 74, 122, 0, 0, 252, 2, 22, ''),
(558, 74, 122, 0, 0, 253, 2, 22, ''),
(559, 74, 122, 0, 0, 254, 3, 22, ''),
(560, 74, 122, 0, 0, 255, 3, 22, ''),
(561, 74, 122, 0, 0, 256, 3, 22, ''),
(562, 74, 122, 0, 0, 257, 3, 22, ''),
(563, 74, 122, 0, 0, 258, 3, 22, ''),
(564, 74, 122, 0, 0, 259, 3, 22, ''),
(565, 74, 122, 0, 0, 260, 3, 22, ''),
(566, 74, 122, 0, 0, 261, 2, 22, ''),
(567, 74, 122, 0, 0, 262, 2, 22, ''),
(568, 74, 122, 0, 0, 263, 2, 22, ''),
(569, 74, 122, 0, 0, 264, 2, 22, ''),
(570, 74, 122, 0, 0, 265, 1, 22, ''),
(571, 74, 122, 0, 0, 240, NULL, 22, 'asdadas'),
(572, 75, 122, 0, 0, 234, 4, 22, ''),
(573, 75, 122, 0, 0, 235, 3, 22, ''),
(574, 75, 122, 0, 0, 236, 3, 22, ''),
(575, 75, 122, 0, 0, 237, 2, 22, ''),
(576, 75, 122, 0, 0, 241, 2, 22, ''),
(577, 75, 122, 0, 0, 242, 2, 22, ''),
(578, 75, 122, 0, 0, 243, 3, 22, ''),
(579, 75, 122, 0, 0, 244, 3, 22, ''),
(580, 75, 122, 0, 0, 245, 3, 22, ''),
(581, 75, 122, 0, 0, 246, 3, 22, ''),
(582, 75, 122, 0, 0, 239, 2, 22, ''),
(583, 75, 122, 0, 0, 247, 2, 22, ''),
(584, 75, 122, 0, 0, 248, 2, 22, ''),
(585, 75, 122, 0, 0, 249, 2, 22, ''),
(586, 75, 122, 0, 0, 250, 3, 22, ''),
(587, 75, 122, 0, 0, 251, 3, 22, ''),
(588, 75, 122, 0, 0, 252, 2, 22, ''),
(589, 75, 122, 0, 0, 253, 2, 22, ''),
(590, 75, 122, 0, 0, 254, 3, 22, ''),
(591, 75, 122, 0, 0, 255, 2, 22, ''),
(592, 75, 122, 0, 0, 256, 3, 22, ''),
(593, 75, 122, 0, 0, 257, 3, 22, ''),
(594, 75, 122, 0, 0, 258, 3, 22, ''),
(595, 75, 122, 0, 0, 259, 3, 22, ''),
(596, 75, 122, 0, 0, 260, 3, 22, ''),
(597, 75, 122, 0, 0, 261, 2, 22, ''),
(598, 75, 122, 0, 0, 262, 2, 22, ''),
(599, 75, 122, 0, 0, 263, 2, 22, ''),
(600, 75, 122, 0, 0, 264, 3, 22, ''),
(601, 75, 122, 0, 0, 265, 3, 22, ''),
(602, 75, 122, 0, 0, 240, NULL, 22, 'asdsasadsadsa');

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

--
-- Dumping data for table `head_faculty_list`
--

INSERT INTO `head_faculty_list` (`head_id`, `school_id`, `firstname`, `lastname`, `email`, `department`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(20, '1001', 'jorge', 'resurreccion', 'jorge@resurreccion.com', 'ccs', '$2y$10$U7UtO60TT/Z4.Y0KUKhMTe9C6KmruMhXC9l40kgkoGyZ2r42DM0Yy', '', 22, 0, '2025-01-29 21:44:10');

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
(308, '', 'e98dd6ca9afff9bc5f1a49cedf737d40', '2025-02-12 01:45:21', '2025-02-11 00:45:21');

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

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`student_id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `subject`, `section`, `avatar`, `academic_id`, `date_created`, `account_status`) VALUES
(120, '2312321', 'aaa', 'aaaa', 'aaa@aa.com', '$2y$10$Ws1zvVbSOkW1ugSJ7HJAoeE691/7vUKKNrJTXHBfvQStl6R/OaYg6', 'ES104', 'A', '', 22, '2025-02-06 21:42:06', 1),
(121, '2147483647', 'asdfadsf', 'asdfasdf', 'sadfdsafdsaf@ga.com', '$2y$10$8mcd/33kFmdWlrWhlsVu/Oud9RZB.9C5zcr11ADL72xHMxK1Poft2', 'IT PROF EL5', 'A', '', 22, '2025-02-06 21:42:11', 1),
(122, '10002', 'beia', 'bianca', 'bancabeia@gmail.com', '$2y$10$fIzh1GUA52T9qtcgzmXlCuF3cX7p2X02COw6K.E6PWhVdyt1XEjXm', 'ES104', 'A', '', 22, '2025-02-10 11:48:00', 1),
(123, '10003', 'kevin', 'balocos', 'kevinbalocos03@gmail.com', '$2y$10$W9.UYNQ5vzFreTW018HJ3OWUCr8SQfj8H/qwcW7FqwMnDXvhyQQva', 'ES104', 'A', '', 22, '2025-02-10 11:48:08', 1),
(126, '23423', 'Ivan', 'Brilata', 'brilataivan86@gmail.com', '$2y$10$fQGKVrA2CuAdEcH/kdJfkuMd6QWl43eatdmOQdGNugaYKeFAp4aai', 'ES104', 'A', '', 22, '2025-02-10 22:19:35', 1),
(127, '232131312', 'klein', 'darelle', 'kleindarelle@gmail.com', '$2y$10$gDYL6dd/RQF3LdR36jDYUOV3XM4l28RofLJ/J6BOMansswXa6.ngW', 'ES104', 'A', '', 22, '2025-02-11 08:47:25', 1);

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
(49, 'SPC', 'admin', 'spc@admin.com', '$2y$10$wmwcFX8dInqYbb.1clkxNOKDGFYBsvWpKPasHVfSepAy5/Li7pwi.', '', 22, '2025-01-29 21:02:56');

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
  MODIFY `academic_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `archives_student_list`
--
ALTER TABLE `archives_student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `faculty_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `criteria_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=603;

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
  MODIFY `head_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

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
  MODIFY `question_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

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
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `subject_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
