-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 08:08 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `criteria_id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `faculty_order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(71, 53, 68, 0, 0, 185, 4, 9, ''),
(72, 53, 68, 0, 0, 186, 4, 9, ''),
(73, 53, 68, 0, 0, 187, 4, 9, ''),
(74, 53, 68, 0, 0, 188, 3, 9, ''),
(75, 53, 68, 0, 0, 189, 3, 9, ''),
(76, 55, 68, 0, 0, 185, 4, 9, ''),
(77, 55, 68, 0, 0, 186, 4, 9, ''),
(78, 55, 68, 0, 0, 187, 4, 9, ''),
(79, 55, 68, 0, 0, 188, 3, 9, ''),
(80, 55, 68, 0, 0, 189, 3, 9, ''),
(81, 54, 68, 0, 0, 185, 4, 9, ''),
(82, 54, 68, 0, 0, 186, 4, 9, ''),
(83, 54, 68, 0, 0, 187, 3, 9, ''),
(84, 54, 68, 0, 0, 188, 3, 9, ''),
(85, 54, 68, 0, 0, 189, 3, 9, ''),
(86, 53, 69, 0, 0, 185, 4, 9, ''),
(87, 53, 69, 0, 0, 186, 4, 9, ''),
(88, 53, 69, 0, 0, 187, 4, 9, ''),
(89, 53, 69, 0, 0, 188, 4, 9, ''),
(90, 53, 69, 0, 0, 189, 4, 9, ''),
(91, 55, 69, 0, 0, 185, 4, 9, ''),
(92, 55, 69, 0, 0, 186, 4, 9, ''),
(93, 55, 69, 0, 0, 187, 3, 9, ''),
(94, 55, 69, 0, 0, 188, 3, 9, ''),
(95, 55, 69, 0, 0, 189, 3, 9, ''),
(96, 55, 71, 0, 0, 185, 4, 9, ''),
(97, 55, 71, 0, 0, 186, 3, 9, ''),
(98, 55, 71, 0, 0, 187, 4, 9, ''),
(99, 55, 71, 0, 0, 188, 4, 9, ''),
(100, 55, 71, 0, 0, 189, 4, 9, ''),
(101, 53, 72, 0, 0, 185, 3, 9, ''),
(102, 53, 72, 0, 0, 186, 3, 9, ''),
(103, 53, 72, 0, 0, 187, 3, 9, ''),
(104, 53, 72, 0, 0, 188, 3, 9, ''),
(105, 53, 72, 0, 0, 189, 3, 9, ''),
(106, 55, 72, 0, 0, 185, 3, 9, ''),
(107, 55, 72, 0, 0, 186, 4, 9, ''),
(108, 55, 72, 0, 0, 187, 4, 9, ''),
(109, 55, 72, 0, 0, 188, 3, 9, ''),
(110, 55, 72, 0, 0, 189, 4, 9, ''),
(111, 53, 80, 0, 0, 185, 4, 9, ''),
(112, 53, 80, 0, 0, 186, 4, 9, ''),
(113, 53, 80, 0, 0, 187, 3, 9, ''),
(114, 53, 80, 0, 0, 188, 4, 9, ''),
(115, 53, 80, 0, 0, 189, 3, 9, ''),
(116, 55, 80, 0, 0, 185, 4, 9, ''),
(117, 55, 80, 0, 0, 186, 4, 9, ''),
(118, 55, 80, 0, 0, 187, 4, 9, ''),
(119, 55, 80, 0, 0, 188, 4, 9, ''),
(120, 55, 80, 0, 0, 189, 4, 9, ''),
(121, 53, 78, 0, 0, 185, 4, 9, ''),
(122, 53, 78, 0, 0, 186, 4, 9, ''),
(123, 53, 78, 0, 0, 187, 3, 9, ''),
(124, 53, 78, 0, 0, 188, 3, 9, ''),
(125, 53, 78, 0, 0, 189, 3, 9, ''),
(126, 53, 79, 0, 0, 185, 4, 9, ''),
(127, 53, 79, 0, 0, 186, 4, 9, ''),
(128, 53, 79, 0, 0, 187, 3, 9, ''),
(129, 53, 79, 0, 0, 188, 3, 9, ''),
(130, 53, 79, 0, 0, 189, 4, 9, ''),
(131, 53, 77, 0, 0, 185, 4, 9, ''),
(132, 53, 77, 0, 0, 186, 3, 9, ''),
(133, 53, 77, 0, 0, 187, 3, 9, ''),
(134, 53, 77, 0, 0, 188, 4, 9, ''),
(135, 53, 77, 0, 0, 189, 4, 9, ''),
(136, 55, 77, 0, 0, 185, 4, 9, ''),
(137, 55, 77, 0, 0, 186, 3, 9, ''),
(138, 55, 77, 0, 0, 187, 3, 9, ''),
(139, 55, 77, 0, 0, 188, 4, 9, ''),
(140, 55, 77, 0, 0, 189, 4, 9, ''),
(141, 55, 79, 0, 0, 185, 4, 9, ''),
(142, 55, 79, 0, 0, 186, 4, 9, ''),
(143, 55, 79, 0, 0, 187, 3, 9, ''),
(144, 55, 79, 0, 0, 188, 4, 9, ''),
(145, 55, 79, 0, 0, 189, 4, 9, ''),
(167, 55, 78, 0, 0, 185, 4, 9, ''),
(168, 55, 78, 0, 0, 186, 4, 9, ''),
(169, 55, 78, 0, 0, 187, 4, 9, ''),
(170, 55, 78, 0, 0, 188, 4, 9, ''),
(171, 55, 78, 0, 0, 189, 4, 9, ''),
(172, 53, 81, 0, 0, 185, 4, 9, ''),
(173, 53, 81, 0, 0, 186, 4, 9, ''),
(174, 53, 81, 0, 0, 187, 4, 9, ''),
(175, 53, 81, 0, 0, 188, 4, 9, ''),
(176, 53, 81, 0, 0, 189, 4, 9, ''),
(177, 55, 81, 0, 0, 185, 4, 9, ''),
(178, 55, 81, 0, 0, 186, 4, 9, ''),
(179, 55, 81, 0, 0, 187, 4, 9, ''),
(180, 55, 81, 0, 0, 188, 4, 9, ''),
(181, 55, 81, 0, 0, 189, 4, 9, ''),
(182, 54, 81, 0, 0, 185, 4, 9, ''),
(183, 54, 81, 0, 0, 186, 4, 9, ''),
(184, 54, 81, 0, 0, 187, 4, 9, ''),
(185, 54, 81, 0, 0, 188, 4, 9, ''),
(186, 54, 81, 0, 0, 189, 4, 9, ''),
(187, 56, 81, 0, 0, 185, 4, 9, ''),
(188, 56, 81, 0, 0, 186, 4, 9, ''),
(189, 56, 81, 0, 0, 187, 4, 9, ''),
(190, 56, 81, 0, 0, 188, 3, 9, ''),
(191, 56, 81, 0, 0, 189, 3, 9, ''),
(192, 56, 77, 0, 0, 185, 4, 9, ''),
(193, 56, 77, 0, 0, 186, 4, 9, ''),
(194, 56, 77, 0, 0, 187, 4, 9, ''),
(195, 56, 77, 0, 0, 188, 4, 9, ''),
(196, 56, 77, 0, 0, 189, 4, 9, ''),
(212, 56, 78, 0, 0, 185, 4, 9, ''),
(213, 56, 78, 0, 0, 186, 4, 9, ''),
(214, 56, 78, 0, 0, 187, 4, 9, ''),
(215, 56, 78, 0, 0, 188, 4, 9, ''),
(216, 56, 78, 0, 0, 189, 4, 9, ''),
(218, 56, 68, 0, 0, 185, 1, 11, ''),
(219, 56, 68, 0, 0, 186, 2, 11, ''),
(220, 56, 68, 0, 0, 187, 3, 11, ''),
(221, 56, 68, 0, 0, 188, 4, 11, ''),
(222, 56, 68, 0, 0, 189, 4, 11, ''),
(223, 56, 68, 0, 0, 205, NULL, 11, 'good work'),
(224, 56, 69, 0, 0, 185, 1, 11, ''),
(225, 56, 69, 0, 0, 186, 2, 11, ''),
(226, 56, 69, 0, 0, 187, 2, 11, ''),
(227, 56, 69, 0, 0, 188, 2, 11, ''),
(228, 56, 69, 0, 0, 189, 2, 11, ''),
(229, 56, 69, 0, 0, 205, NULL, 11, 'gege'),
(230, 56, 80, 0, 0, 185, 1, 11, ''),
(231, 56, 80, 0, 0, 186, 1, 11, ''),
(232, 56, 80, 0, 0, 187, 1, 11, ''),
(233, 56, 80, 0, 0, 188, 1, 11, ''),
(234, 56, 80, 0, 0, 189, 1, 11, ''),
(235, 56, 80, 0, 0, 205, NULL, 11, 'mamaya nako uuwi'),
(236, 53, 85, 0, 0, 185, 3, 11, ''),
(237, 53, 85, 0, 0, 186, 2, 11, ''),
(238, 53, 85, 0, 0, 187, 3, 11, ''),
(239, 53, 85, 0, 0, 188, 4, 11, ''),
(240, 53, 85, 0, 0, 189, 4, 11, ''),
(241, 53, 85, 0, 0, 205, NULL, 11, 'good work'),
(242, 55, 85, 0, 0, 185, 2, 11, ''),
(243, 55, 85, 0, 0, 186, 2, 11, ''),
(244, 55, 85, 0, 0, 187, 2, 11, ''),
(245, 55, 85, 0, 0, 188, 2, 11, ''),
(246, 55, 85, 0, 0, 189, 2, 11, ''),
(247, 55, 85, 0, 0, 205, NULL, 11, 'oks'),
(248, 56, 85, 0, 0, 185, 4, 11, ''),
(249, 56, 85, 0, 0, 186, 3, 11, ''),
(250, 56, 85, 0, 0, 187, 4, 11, ''),
(251, 56, 85, 0, 0, 188, 4, 11, ''),
(252, 56, 85, 0, 0, 189, 4, 11, ''),
(253, 56, 85, 0, 0, 205, NULL, 11, 'asdasdas'),
(254, 54, 85, 0, 0, 185, 1, 11, ''),
(255, 54, 85, 0, 0, 186, 1, 11, ''),
(256, 54, 85, 0, 0, 187, 1, 11, ''),
(257, 54, 85, 0, 0, 188, 1, 11, ''),
(258, 54, 85, 0, 0, 189, 1, 11, ''),
(259, 54, 85, 0, 0, 205, NULL, 11, 'asdasdsa'),
(266, 54, 0, 0, 53, 6, 4, 11, ''),
(267, 54, 0, 0, 53, 7, NULL, 11, 'asdasdas'),
(280, 55, 0, 0, 1, 3, 2, 11, ''),
(281, 55, 0, 0, 1, 4, NULL, 11, 'asdasdasas'),
(282, 56, 0, 0, 1, 3, 2, 11, ''),
(283, 56, 0, 0, 1, 4, NULL, 11, 'asdsa'),
(284, 57, 0, 0, 1, 3, 2, 11, ''),
(285, 57, 0, 0, 1, 4, NULL, 11, 'asdsadsa'),
(286, 8, 0, 0, 53, 3, 3, 11, ''),
(287, 8, 0, 0, 53, 4, NULL, 11, 'gege'),
(290, 57, 68, 0, 0, 185, 3, 11, ''),
(291, 57, 68, 0, 0, 186, 2, 11, ''),
(292, 57, 68, 0, 0, 187, 3, 11, ''),
(293, 57, 68, 0, 0, 188, 4, 11, ''),
(294, 57, 68, 0, 0, 189, 3, 11, ''),
(295, 57, 68, 0, 0, 205, NULL, 11, 'sadadasdasdasdas'),
(296, 53, 86, 0, 0, 185, 1, 11, ''),
(297, 53, 86, 0, 0, 186, 2, 11, ''),
(298, 53, 86, 0, 0, 187, 3, 11, ''),
(299, 53, 86, 0, 0, 188, 4, 11, ''),
(300, 53, 86, 0, 0, 189, 4, 11, ''),
(301, 53, 86, 0, 0, 205, NULL, 11, 'ang galing mo');

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
(247, '', 'da717bea63b3ab150a963480f33d4a7d', '2025-01-05 14:48:02', '2025-01-04 13:48:02'),
(248, '', '7ad3430feb3968aaefea954e4f479b0f', '2025-01-11 17:48:22', '2025-01-10 16:48:22'),
(249, '', 'caf9a2c9d7f25313a30e82cac0964bea', '2025-01-12 15:33:10', '2025-01-11 14:33:10'),
(250, '', '290fc63e354e2ad072ebb5440e9e1941', '2025-01-14 07:43:52', '2025-01-13 06:43:52');

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
(7, 9, 'Comment on your co teacher?', 148, 'text');

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
-- Table structure for table `self_faculty_eval`
--

CREATE TABLE `self_faculty_eval` (
  `faculty_id` int(11) NOT NULL,
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
(5, 'test', 'admin', 'test@admin.com', '$2y$10$J6OgDIT6/U/FNMAqBki6YeWUEX7V3dzmUUaZ8zniF151cuQ.HhIHq', 'admin.jpg', 11, '2024-09-27 13:13:20');

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
  MODIFY `faculty_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `criteria_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `evaluation_answers`
--
ALTER TABLE `evaluation_answers`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `head_faculty_list`
--
ALTER TABLE `head_faculty_list`
  MODIFY `head_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `pending_students`
--
ALTER TABLE `pending_students`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `question_dean_faculty`
--
ALTER TABLE `question_dean_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `question_faculty_dean`
--
ALTER TABLE `question_faculty_dean`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `question_faculty_faculty`
--
ALTER TABLE `question_faculty_faculty`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `question_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `secondary_faculty_list`
--
ALTER TABLE `secondary_faculty_list`
  MODIFY `secondary_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `self_faculty_eval`
--
ALTER TABLE `self_faculty_eval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `self_head_eval`
--
ALTER TABLE `self_head_eval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

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
