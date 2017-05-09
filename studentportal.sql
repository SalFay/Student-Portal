-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2017 at 11:00 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `admit_id` int(11) NOT NULL,
  `admit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admission`
--

INSERT INTO `admission` (`admit_id`, `admit_type`, `user_id`, `department_id`, `semester_id`, `course_id`) VALUES
(1, 'teacher', 2, 1, 1, 1),
(2, 'teacher', 3, 2, 1, 2),
(3, 'student', 5, 1, 1, 0),
(4, 'student', 6, 1, 1, 0),
(5, 'student', 7, 1, 1, 0),
(6, 'student', 8, 2, 1, 0),
(7, 'student', 9, 2, 1, 0),
(8, 'student', 5, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `announcement_body` text COLLATE utf8_unicode_ci NOT NULL,
  `announcement_course` int(11) NOT NULL,
  `announcement_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `announcement_title`, `announcement_body`, `announcement_course`, `announcement_date`) VALUES
(1, 'sdagsgsggreg', 'ergwege', 1, '2015-08-19');

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `asgn_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `asgn_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `asgn_body` text COLLATE utf8_unicode_ci NOT NULL,
  `asgn_date` date NOT NULL,
  `asgn_expiry` date NOT NULL,
  `asgn_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`asgn_id`, `teacher_id`, `course_id`, `asgn_title`, `asgn_body`, `asgn_date`, `asgn_expiry`, `asgn_status`) VALUES
(2, 4, 1, 'mh iugvkhvkhvk', 'dgsdfgdfg', '2015-08-17', '2015-08-26', 1),
(3, 4, 1, 'mh iugvkhvkhvk', 'dgsdfgdfg', '2015-08-17', '2015-08-26', 1),
(4, 4, 1, 'mh iugvkhvkhvk', 'dgsdfgdfg', '2015-08-17', '2015-08-26', 1),
(5, 4, 1, 'mh iugvkhvkhvk', 'dgsdfgdfg', '2015-08-17', '2015-08-26', 1),
(6, 4, 1, 'mh iugvkhvkhvk', 'dgsdfgdfg', '2015-08-17', '2015-08-26', 1),
(7, 3, 1, 'gsdfgsdfg', 'sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9sudfgiasudbf9', '2015-08-19', '2015-08-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_files`
--

CREATE TABLE `assignment_files` (
  `af_id` int(11) NOT NULL,
  `asgn_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `af_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `af_comments` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `af_marks` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `assignment_files`
--

INSERT INTO `assignment_files` (`af_id`, `asgn_id`, `student_id`, `af_file`, `af_comments`, `af_marks`) VALUES
(1, 7, 2, '', '', 0),
(2, 7, 5, 'class.database.php', '', 0),
(3, 7, 6, '', '', 0),
(4, 7, 7, '', '', 0),
(5, 7, 5, '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cgpa`
--

CREATE TABLE `cgpa` (
  `cgpa_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `cgpa_marks` float NOT NULL,
  `exam_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cgpa`
--

INSERT INTO `cgpa` (`cgpa_id`, `student_id`, `cgpa_marks`, `exam_id`) VALUES
(1, 5, 0.22, 11);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `message` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `user` int(11) DEFAULT NULL,
  `message_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `message`, `user`, `message_on`) VALUES
(1, 'hi', 5, '2017-05-08 12:26:24'),
(2, 'hi', 5, '2017-05-08 12:26:25'),
(3, 'hi', 5, '2017-05-08 12:26:40'),
(4, 'hi', 5, '2017-05-08 12:27:16'),
(5, 'hi', 5, '2017-05-08 12:27:19'),
(6, 'How are you', 5, '2017-05-08 12:35:17'),
(7, 'I am fine', 5, '2017-05-08 12:37:17'),
(8, 'I am fine', 5, '2017-05-09 10:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `course_body` text COLLATE utf8_unicode_ci NOT NULL,
  `course_credit` int(11) NOT NULL,
  `course_hours` int(11) NOT NULL,
  `course_semester` int(11) NOT NULL,
  `course_department` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_body`, `course_credit`, `course_hours`, `course_semester`, `course_department`) VALUES
(1, 'Islamic Calendar', '<p>page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2page.php?id=2</p>', 454, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `department_semester` int(2) NOT NULL,
  `department_contact` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `department_semester`, `department_contact`) VALUES
(1, 'Computer Science', 8, '8968769'),
(2, 'Software Engineering', 8, '03131234567');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exam_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `exam_department` int(11) NOT NULL,
  `exam_semester` int(11) NOT NULL,
  `exam_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `exam_name`, `exam_type`, `exam_department`, `exam_semester`, `exam_date`) VALUES
(10, 'Spring 2015 Mid Term', 'mid', 1, 1, '2015-08-04'),
(11, 'Spring 2015 FinalTerm', 'final', 1, 1, '2015-08-28');

-- --------------------------------------------------------

--
-- Table structure for table `exam_result`
--

CREATE TABLE `exam_result` (
  `exr_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `result_total` float NOT NULL,
  `result_marks` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `exam_result`
--

INSERT INTO `exam_result` (`exr_id`, `exam_id`, `student_id`, `course_id`, `result_total`, `result_marks`) VALUES
(44, 10, 5, 1, 100, 98),
(45, 10, 5, 3, 100, 60),
(46, 10, 5, 4, 100, 85),
(47, 10, 5, 5, 100, 70),
(48, 10, 6, 1, 0, 0),
(49, 10, 6, 3, 0, 0),
(50, 10, 6, 4, 0, 0),
(51, 10, 6, 5, 0, 0),
(52, 10, 7, 1, 0, 0),
(53, 10, 7, 3, 0, 0),
(54, 10, 7, 4, 0, 0),
(55, 10, 7, 5, 0, 0),
(56, 11, 5, 1, 100, 56),
(57, 11, 5, 3, 100, 78),
(58, 11, 5, 4, 100, 91),
(59, 11, 5, 5, 100, 85),
(60, 11, 6, 1, 0, 0),
(61, 11, 6, 3, 0, 0),
(62, 11, 6, 4, 0, 0),
(63, 11, 6, 5, 0, 0),
(64, 11, 7, 1, 0, 0),
(65, 11, 7, 3, 0, 0),
(66, 11, 7, 4, 0, 0),
(67, 11, 7, 5, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gpa`
--

CREATE TABLE `gpa` (
  `gpa_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `gpa_marks` float NOT NULL,
  `gpa_credit_hours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gpa`
--

INSERT INTO `gpa` (`gpa_id`, `student_id`, `exam_id`, `gpa_marks`, `gpa_credit_hours`) VALUES
(33, 5, 10, 3.4, 15),
(34, 6, 10, 0, 15),
(35, 7, 10, 0, 15),
(36, 5, 11, 3.3, 15),
(37, 6, 11, 0, 15),
(38, 7, 11, 0, 15);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL,
  `menu_label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `menu_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_label`, `menu_link`, `menu_title`, `menu_order`) VALUES
(1, 'home', 'index.php', '', 1),
(2, 'About us', 'http://localhost/studentportal/page.php?id=1', 'About us', 1),
(3, 'Sample Page', 'page.php?id=2', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `page_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `page_body` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `page_title`, `page_body`) VALUES
(1, 'About us', '<p><strong>MaxTech Computer institute</strong> is committed to student&rsquo;s satisfaction in the 17 years<strong>.</strong> <strong>MTCI</strong>&rsquo;s aim is to provide Computer education in a very friendly Environment and through the use of modern software tools to improve the level of skills among the students. Whether you are new to the computer industry or to improve your skills, we have the right course for you. <strong>MTCI</strong> have well qualified, experienced certified instructors train the participants with easy to use step by step training material in the most optimized period of time.</p>\r\n<p>The computer labs of <strong>MTCI</strong> are well equipped with latest computer technology and all training courses are designed to develop in depth understanding of computer technology.</p>\r\n<p><strong>MTCI</strong> believes that computer sciences is a practical field and thus evaluates the students on the basis practical performance.</p>'),
(2, 'Sample Page', '<p>There is a movement that is working on teaching basic personal finances to high school student before they graduate. Many students graduate without learning how to manage the basics or without <a href="http://moneyfor20s.about.com/od/moneyinyour20sbasics/tp/20-Financial-Skills-You-Should-Master-in-Your-Twenties.htm" data-component="link" data-source="inlineLink" data-type="internalLink" data-ordinal="1">solid financial skills</a>. The sad truth is that many parents do not talk about to them either because they either are embarrassed</p>');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL,
  `test_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `test_body` text COLLATE utf8_unicode_ci NOT NULL,
  `test_teacher` int(11) NOT NULL,
  `test_department` int(11) NOT NULL,
  `test_semester` int(11) NOT NULL,
  `test_course` int(11) NOT NULL,
  `test_date` date NOT NULL,
  `test_marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `test_name`, `test_body`, `test_teacher`, `test_department`, `test_semester`, `test_course`, `test_date`, `test_marks`) VALUES
(1, 'First Quiz', '', 2, 1, 1, 1, '2015-07-29', 10);

-- --------------------------------------------------------

--
-- Table structure for table `test_result`
--

CREATE TABLE `test_result` (
  `tr_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_marks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `test_result`
--

INSERT INTO `test_result` (`tr_id`, `test_id`, `student_id`, `student_marks`) VALUES
(1, 1, 5, 8),
(2, 1, 6, 6),
(3, 1, 7, 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_login` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_level` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_dob` date NOT NULL,
  `user_qualification` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_contact` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_gender` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_email`, `user_name`, `user_level`, `user_dob`, `user_qualification`, `user_address`, `user_contact`, `user_gender`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'pakxpertz@gmail.com', 'Fayaz Khan Yousufzai', 'admin', '0000-00-00', '', '', '', NULL),
(2, 'salmansherin', '1a1dc91c907325c69271ddf0c944bc72', 'salmansherin@gmail.com', 'Salman Sherin', 'teacher', '1985-06-22', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(3, 'navid', '1a1dc91c907325c69271ddf0c944bc72', 'navid@gmail.com', 'Navid Ali Khan', 'teacher', '1996-09-26', 'PHD', 'Nawakali Mingora Swat', '0313098754', 'male'),
(4, 'pakxpert', '1a1dc91c907325c69271ddf0c944bc72', 'pakxpertz@gmail.com', 'Fayaz Khan', 'teacher', '1992-10-28', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(5, 'aizazali', '1a1dc91c907325c69271ddf0c944bc72', 'aizaz@gmail.com', 'Aizaz Khan', 'student', '1993-09-20', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(6, 'iftikhar', '1a1dc91c907325c69271ddf0c944bc72', 'iftikhar@gmail.com', 'Iftikhar Ali', 'student', '1999-04-19', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(7, 'nadeem', '1a1dc91c907325c69271ddf0c944bc72', 'nadeem@gmail.com', 'Nadeem Khan', 'student', '1985-09-15', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(8, 'waqas', '1a1dc91c907325c69271ddf0c944bc72', 'waqas@gmail.com', 'Waqas Khan', 'student', '2003-12-19', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male'),
(9, 'noman', '1a1dc91c907325c69271ddf0c944bc72', 'noman@gmail.com', 'Noman Khan', 'student', '1988-02-15', 'PHD', 'Nawakali Mingora Swat', '03131234567', 'male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`admit_id`),
  ADD KEY `admit_id` (`admit_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`asgn_id`);

--
-- Indexes for table `assignment_files`
--
ALTER TABLE `assignment_files`
  ADD PRIMARY KEY (`af_id`);

--
-- Indexes for table `cgpa`
--
ALTER TABLE `cgpa`
  ADD PRIMARY KEY (`cgpa_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `exam_result`
--
ALTER TABLE `exam_result`
  ADD PRIMARY KEY (`exr_id`);

--
-- Indexes for table `gpa`
--
ALTER TABLE `gpa`
  ADD PRIMARY KEY (`gpa_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `test_result`
--
ALTER TABLE `test_result`
  ADD PRIMARY KEY (`tr_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_login` (`user_login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission`
--
ALTER TABLE `admission`
  MODIFY `admit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `asgn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `assignment_files`
--
ALTER TABLE `assignment_files`
  MODIFY `af_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cgpa`
--
ALTER TABLE `cgpa`
  MODIFY `cgpa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `exam_result`
--
ALTER TABLE `exam_result`
  MODIFY `exr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `gpa`
--
ALTER TABLE `gpa`
  MODIFY `gpa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `test_result`
--
ALTER TABLE `test_result`
  MODIFY `tr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
