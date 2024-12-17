-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 05:27 AM
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
(9, '2024-2025', 1, 1, 1, '2024-12-15', '2025-12-15'),
(11, '2024-2025', 2, 0, 0, NULL, NULL);

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

INSERT INTO `college_faculty_list` (`faculty_id`, `school_id`, `firstname`, `lastname`, `subject`, `email`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(53, '19992', 'test', 'faculty', 'cc101', 'test@faculty.com', '$2y$10$dOD8/9WxkFbLS3raK4gKBurDAbWYOXtAC6K0UjoaCVQqX8cCP17kW', '', 9, 1, '2024-11-22 15:30:32'),
(54, '1994', 'test', 'faculty2', 'cc106', 'test@faculty2.com', '$2y$10$TSSlyFtTHgkdmfpRxVGc1.N.U7HUzBuZLqpMQzZ.CSZb7V7Bx.aYu', '', 11, 0, '2024-11-22 15:31:25'),
(55, '0911232', 'facs', 'faculty3', 'cc101, cc102, cc103, cc104', 'facs1@z.com', '$2y$10$7KStehfqFNjhII2JVrM.FOemgp29.pIvytlDTllnLpVDZrIbkOxl.', '', 9, 1, '2024-11-24 22:59:27'),
(56, '343433', 'miss', 'me', 'cc101', 'missin@me.com', '$2y$10$qVK3vOX7KFq3i9f.74z95e7jn6UFeBQnSBnj0YxOWwZMXSH2kEawO', '', 11, 0, '2024-12-08 13:51:49');

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
(148, 'Evaluation Questions', 0);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) DEFAULT NULL,
  `academic_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `faculty_id`, `student_id`, `question_id`, `rate`, `academic_id`, `comment`) VALUES
(71, 53, 68, 185, 4, 9, ''),
(72, 53, 68, 186, 4, 9, ''),
(73, 53, 68, 187, 4, 9, ''),
(74, 53, 68, 188, 3, 9, ''),
(75, 53, 68, 189, 3, 9, ''),
(76, 55, 68, 185, 4, 9, ''),
(77, 55, 68, 186, 4, 9, ''),
(78, 55, 68, 187, 4, 9, ''),
(79, 55, 68, 188, 3, 9, ''),
(80, 55, 68, 189, 3, 9, ''),
(81, 54, 68, 185, 4, 9, ''),
(82, 54, 68, 186, 4, 9, ''),
(83, 54, 68, 187, 3, 9, ''),
(84, 54, 68, 188, 3, 9, ''),
(85, 54, 68, 189, 3, 9, ''),
(86, 53, 69, 185, 4, 9, ''),
(87, 53, 69, 186, 4, 9, ''),
(88, 53, 69, 187, 4, 9, ''),
(89, 53, 69, 188, 4, 9, ''),
(90, 53, 69, 189, 4, 9, ''),
(91, 55, 69, 185, 4, 9, ''),
(92, 55, 69, 186, 4, 9, ''),
(93, 55, 69, 187, 3, 9, ''),
(94, 55, 69, 188, 3, 9, ''),
(95, 55, 69, 189, 3, 9, ''),
(96, 55, 71, 185, 4, 9, ''),
(97, 55, 71, 186, 3, 9, ''),
(98, 55, 71, 187, 4, 9, ''),
(99, 55, 71, 188, 4, 9, ''),
(100, 55, 71, 189, 4, 9, ''),
(101, 53, 72, 185, 3, 9, ''),
(102, 53, 72, 186, 3, 9, ''),
(103, 53, 72, 187, 3, 9, ''),
(104, 53, 72, 188, 3, 9, ''),
(105, 53, 72, 189, 3, 9, ''),
(106, 55, 72, 185, 3, 9, ''),
(107, 55, 72, 186, 4, 9, ''),
(108, 55, 72, 187, 4, 9, ''),
(109, 55, 72, 188, 3, 9, ''),
(110, 55, 72, 189, 4, 9, ''),
(111, 53, 80, 185, 4, 9, ''),
(112, 53, 80, 186, 4, 9, ''),
(113, 53, 80, 187, 3, 9, ''),
(114, 53, 80, 188, 4, 9, ''),
(115, 53, 80, 189, 3, 9, ''),
(116, 55, 80, 185, 4, 9, ''),
(117, 55, 80, 186, 4, 9, ''),
(118, 55, 80, 187, 4, 9, ''),
(119, 55, 80, 188, 4, 9, ''),
(120, 55, 80, 189, 4, 9, ''),
(121, 53, 78, 185, 4, 9, ''),
(122, 53, 78, 186, 4, 9, ''),
(123, 53, 78, 187, 3, 9, ''),
(124, 53, 78, 188, 3, 9, ''),
(125, 53, 78, 189, 3, 9, ''),
(126, 53, 79, 185, 4, 9, ''),
(127, 53, 79, 186, 4, 9, ''),
(128, 53, 79, 187, 3, 9, ''),
(129, 53, 79, 188, 3, 9, ''),
(130, 53, 79, 189, 4, 9, ''),
(131, 53, 77, 185, 4, 9, ''),
(132, 53, 77, 186, 3, 9, ''),
(133, 53, 77, 187, 3, 9, ''),
(134, 53, 77, 188, 4, 9, ''),
(135, 53, 77, 189, 4, 9, ''),
(136, 55, 77, 185, 4, 9, ''),
(137, 55, 77, 186, 3, 9, ''),
(138, 55, 77, 187, 3, 9, ''),
(139, 55, 77, 188, 4, 9, ''),
(140, 55, 77, 189, 4, 9, ''),
(141, 55, 79, 185, 4, 9, ''),
(142, 55, 79, 186, 4, 9, ''),
(143, 55, 79, 187, 3, 9, ''),
(144, 55, 79, 188, 4, 9, ''),
(145, 55, 79, 189, 4, 9, ''),
(167, 55, 78, 185, 4, 9, ''),
(168, 55, 78, 186, 4, 9, ''),
(169, 55, 78, 187, 4, 9, ''),
(170, 55, 78, 188, 4, 9, ''),
(171, 55, 78, 189, 4, 9, ''),
(172, 53, 81, 185, 4, 9, ''),
(173, 53, 81, 186, 4, 9, ''),
(174, 53, 81, 187, 4, 9, ''),
(175, 53, 81, 188, 4, 9, ''),
(176, 53, 81, 189, 4, 9, ''),
(177, 55, 81, 185, 4, 9, ''),
(178, 55, 81, 186, 4, 9, ''),
(179, 55, 81, 187, 4, 9, ''),
(180, 55, 81, 188, 4, 9, ''),
(181, 55, 81, 189, 4, 9, ''),
(182, 54, 81, 185, 4, 9, ''),
(183, 54, 81, 186, 4, 9, ''),
(184, 54, 81, 187, 4, 9, ''),
(185, 54, 81, 188, 4, 9, ''),
(186, 54, 81, 189, 4, 9, ''),
(187, 56, 81, 185, 4, 9, ''),
(188, 56, 81, 186, 4, 9, ''),
(189, 56, 81, 187, 4, 9, ''),
(190, 56, 81, 188, 3, 9, ''),
(191, 56, 81, 189, 3, 9, ''),
(192, 56, 77, 185, 4, 9, ''),
(193, 56, 77, 186, 4, 9, ''),
(194, 56, 77, 187, 4, 9, ''),
(195, 56, 77, 188, 4, 9, ''),
(196, 56, 77, 189, 4, 9, ''),
(212, 56, 78, 185, 4, 9, ''),
(213, 56, 78, 186, 4, 9, ''),
(214, 56, 78, 187, 4, 9, ''),
(215, 56, 78, 188, 4, 9, ''),
(216, 56, 78, 189, 4, 9, ''),
(217, 56, 78, 205, NULL, 9, 'asdasdasdasdasdsasas');

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
  `password` text NOT NULL,
  `avatar` text NOT NULL,
  `academic_id` int(11) NOT NULL,
  `account_status` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `head_faculty_list`
--

INSERT INTO `head_faculty_list` (`head_id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `academic_id`, `account_status`, `date_created`) VALUES
(1, '4534534534543', 'head', 'test', 'head@test.com', '$2y$10$6owGd1p1eCC7nqgvQ/yJq.0OSKsxpgKHxA/yjbW2vyw866x8.syvC', '', 9, 1, '2024-10-26 13:19:54'),
(8, '909901', 'test', 'head2', 'brilataivan86@gmail.com', '$2y$10$7A1B3hrEyEetk9h8e9.whO.fAkIVaAYeJFLRIt9BLDVs8.QI/mslS', '', 9, 0, '2024-12-16 01:02:01');

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
(230, '', '39bdf8f4259691b1c3f7eb548e1aa2b8', '2024-12-06 18:13:17', '2024-12-05 17:13:17'),
(231, '', '49a2c6c10b0718520e3d33ce48e9fe00', '2024-12-09 17:16:09', '2024-12-08 16:16:09'),
(232, '', 'bdc8f9a0d915f727a79df80270c27b4a', '2024-12-09 17:17:32', '2024-12-08 16:17:32'),
(233, '', 'f312d6619bca6489d14f979e03518395', '2024-12-09 17:18:59', '2024-12-08 16:18:59'),
(234, '', 'e855c76d8b7da5bd33a1d603009e09eb', '2024-12-09 17:25:16', '2024-12-08 16:25:16'),
(235, '', '582952bb68d926bd1ccd275e4b966791', '2024-12-09 17:28:59', '2024-12-08 16:28:59'),
(236, '', '5e6d4c4a5d27c01425e9f90163c0a4de', '2024-12-09 17:29:50', '2024-12-08 16:29:50'),
(237, '', '5eff2e38496603f06ecc9dca117f8bfb', '2024-12-09 17:31:30', '2024-12-08 16:31:30'),
(238, '', '7ebf8ff825db413d6874200469b78472', '2024-12-09 17:37:31', '2024-12-08 16:37:31'),
(239, '', 'e14fd749870149850bd94c9aa37ea5ec', '2024-12-09 17:38:47', '2024-12-08 16:38:47'),
(240, '', '067ccd6a56f6c9cd2c31744bd3816cb1', '2024-12-09 17:39:24', '2024-12-08 16:39:24'),
(241, '', 'ef5cf3e91e3fc64f517cc38b6580ff79', '2024-12-09 17:41:35', '2024-12-08 16:41:35'),
(242, '', '876553a5b7524427368f2824d1a3b66a', '2024-12-09 17:44:24', '2024-12-08 16:44:24'),
(243, '', '42d679b8f735eab996d27255aff72327', '2024-12-09 17:45:48', '2024-12-08 16:45:48'),
(244, '', '95cd250bd7473a9430f3800b39ad4ede', '2024-12-09 17:59:24', '2024-12-08 16:59:24'),
(245, '', '9f6c9da39a45f4f5bc4816d9e68091dd', '2024-12-16 19:11:26', '2024-12-15 18:11:26');

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
(185, 9, 'The faculty executes each lessons clearly.', 0, 148, 'mcq'),
(186, 9, 'The faculty encourages students to engage with the class discussions.', 0, 148, 'mcq'),
(187, 9, 'The faculty is approachable and easy to talk to.', 0, 148, 'mcq'),
(188, 9, 'The faculty is consistent in providing scores and feedbacks on quizzes and exams.', 0, 148, 'mcq'),
(189, 9, 'Overall, I am satisfied with the faculty’s performance.', 0, 148, 'mcq'),
(205, 9, 'can you comment for your prof?', 0, 148, 'text');

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

--
-- Dumping data for table `secondary_faculty_list`
--

INSERT INTO `secondary_faculty_list` (`secondary_id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, '0909', 'test', 'secondary', 'tests@secondary.com', '$2y$10$ajekoRRJ.9uDYvqdd1O1ZeK2vdHev4YAiHX.6gqggtfCWCMzgN1Wa', '', '2024-10-28 23:48:23');

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
(68, '565656565', 'van', 'van1', 'van@van', '$2y$10$nknk/Xk.BqUIWOxrDhHrXeCV152VrccCBuHc0wI8a52Mdj0/13F8K', 'cc101,cc106', 'bsit', '', 11, '2024-11-22 15:32:29', 0),
(69, '2147483647', 'asd', 'asdad', 'asd@yahoo.com', '$2y$10$d.ZpHB0zcKxUUi412inPqu7dAGe13bDhRCdBVt2Op08AMMYKRyiaK', 'cc101', 'bsed', '', 11, '2024-11-22 15:33:01', 0),
(71, '2147483647', 'asd', 'asd', 'sc@sr.com', '$2y$10$tHU282ku0qrG34w4z7oMk.yuZMwDyZTolMdlHOXs7S1HrpapIFx6e', 'cc103', 'bsit', '', 11, '2024-11-24 23:46:08', 0),
(72, '56755465', 'asd', 'asd', 'yan@yan1.com', '$2y$10$QHj2LBr44MnaWjzvK3JnlujW/8qaxQGYTytSJpePOW8oKKt0cpr/2', 'cc101,cc104', 'bsit', '', 11, '2024-11-26 19:37:50', 0),
(77, '123', 'half', 'way', 'halfway@1.com', '$2y$10$irnxLNRcHkk/pVbV2YTEMuusqJqNhmzsUN5lLb/9pk.vmZfnefQ/i', 'cc101', 'bsit', '', 9, '2024-12-04 21:26:48', 1),
(78, '1212', 'hg', 'sd', 'hg@hs1.com', '$2y$10$EYmEhBX4BE4LeiUlW1A0sOlxkOgIlSYKIP6spEou0uBCrXeAWf0ZG', 'cc101', 'bsit', '', 9, '2024-12-05 13:45:19', 1),
(79, '90909090', 'beta', 'ta', 'beta@1.com', '$2y$10$Ho1f9ubPpps57M1GF3LExuFzAglUB4hzKW51UG1gcOqw.oULksXIe', 'cc101', 'bsit', '', 9, '2024-12-05 21:52:52', 1),
(80, '121212', 'asd', 'asd', 'beta2@1.com', '$2y$10$F/ysq0Ja6r/tPN31raTBUOEQJY8toXp9GStQOjPt0WpP9AyF6mQte', 'CC101', 'bsit', '', 11, '2024-12-05 22:00:06', 0),
(81, '9119', 'gab', 'beia', 'gab@beia.com', '$2y$10$/YWB7uox43kdvXB.8Smykez65oHUCJchff2A/NhTX4AjUAlcFiXnm', 'cc101,cc104,cc106', 'bsit', '', 9, '2024-12-06 01:14:28', 1);

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
(5, 'test', 'admin', 'test@admin.com', '$2y$10$J6OgDIT6/U/FNMAqBki6YeWUEX7V3dzmUUaZ8zniF151cuQ.HhIHq', 'admin.jpg', 9, '2024-09-27 13:13:20'),
(45, 'test', 'admin1', 'test@admin1.com', '$2y$10$ubK8Ajn.S6Zm0uPf/U7jUuVb1BejhRfVjELm.44Ubz./hiAtlS0ma', '', 9, '2024-12-03 23:53:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`academic_id`);

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
  MODIFY `academic_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `faculty_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `criteria_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `head_faculty_list`
--
ALTER TABLE `head_faculty_list`
  MODIFY `head_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `question_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `secondary_faculty_list`
--
ALTER TABLE `secondary_faculty_list`
  MODIFY `secondary_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `subject_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
