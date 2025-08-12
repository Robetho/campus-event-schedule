-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2025 at 12:44 AM
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
-- Database: `campus_event_scheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_academic_session`
--

CREATE TABLE `tbl_academic_session` (
  `id` int(100) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `semester` int(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_academic_session`
--

INSERT INTO `tbl_academic_session` (`id`, `academic_year`, `semester`, `status`) VALUES
(1, '2025/2026', 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assigned_course_to_programme`
--

CREATE TABLE `tbl_assigned_course_to_programme` (
  `id` int(100) NOT NULL,
  `programme_id` int(255) NOT NULL,
  `programme_name` text NOT NULL,
  `programme_capacity` int(255) DEFAULT NULL,
  `course_id` int(255) NOT NULL,
  `course_name` text NOT NULL,
  `course_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `tbl_courses`
--

CREATE TABLE `tbl_courses` (
  `c_id` int(100) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `tbl_departments`
--

CREATE TABLE `tbl_departments` (
  `id` int(100) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL,
  `department` text DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `time_slot` varchar(20) DEFAULT NULL,
  `room_name` text DEFAULT NULL,
  `academic_session_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `tbl_programmes`
--

CREATE TABLE `tbl_programmes` (
  `prog_id` int(100) NOT NULL,
  `programme_name` varchar(255) NOT NULL,
  `programme_short_name` varchar(50) NOT NULL,
  `programme_capacity` int(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `tbl_programme_timetable`
--

CREATE TABLE `tbl_programme_timetable` (
  `id` int(255) NOT NULL,
  `programme_id` int(255) NOT NULL,
  `programme_name` varchar(255) NOT NULL,
  `course_id` int(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `room_id` int(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `semester` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `tbl_rooms`
--

CREATE TABLE `tbl_rooms` (
  `id` int(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_capacity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `tbl_staff_allocated_event`
--

CREATE TABLE `tbl_staff_allocated_event` (
  `id` int(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `event_startdate` date NOT NULL,
  `event_enddate` date NOT NULL,
  `event_start_time` time NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(100) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_name` text DEFAULT NULL,
  `role_as` enum('Admin','Teacher','Student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `email`, `gender`, `department_id`, `department_name`, `role_as`) VALUES
(1, 'Maseke', 'Admin', 'Maseke', 'cesp@admin25', '$2y$10$PTc5pAEG3HqojKW9m7Xtlu6YfeTn/5CjB3WUG7Q.Jkp02yEPmhami', 'maseke@gmail.com', 'Male', NULL, NULL, 'Admin');
--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_academic_session`
--
ALTER TABLE `tbl_academic_session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_academic_session` (`academic_year`,`semester`);

--
-- Indexes for table `tbl_assigned_course_to_programme`
--
ALTER TABLE `tbl_assigned_course_to_programme`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_assigned_course` (`programme_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `tbl_courses`
--
ALTER TABLE `tbl_courses`
  ADD PRIMARY KEY (`c_id`),
  ADD UNIQUE KEY `course_name` (`course_name`),
  ADD UNIQUE KEY `course_code` (`course_code`);

--
-- Indexes for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_programmes`
--
ALTER TABLE `tbl_programmes`
  ADD PRIMARY KEY (`prog_id`),
  ADD UNIQUE KEY `programme_name` (`programme_name`),
  ADD UNIQUE KEY `programme_short_name` (`programme_short_name`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tbl_programme_timetable`
--
ALTER TABLE `tbl_programme_timetable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_timetable_slot` (`programme_id`,`day`,`time_slot`,`room_id`,`academic_year`,`semester`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_name` (`room_name`);

--
-- Indexes for table `tbl_staff_allocated_event`
--
ALTER TABLE `tbl_staff_allocated_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_academic_session`
--
ALTER TABLE `tbl_academic_session`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_assigned_course_to_programme`
--
ALTER TABLE `tbl_assigned_course_to_programme`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_courses`
--
ALTER TABLE `tbl_courses`
  MODIFY `c_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_departments`
--
ALTER TABLE `tbl_departments`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_programmes`
--
ALTER TABLE `tbl_programmes`
  MODIFY `prog_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_programme_timetable`
--
ALTER TABLE `tbl_programme_timetable`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_staff_allocated_event`
--
ALTER TABLE `tbl_staff_allocated_event`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_assigned_course_to_programme`
--
ALTER TABLE `tbl_assigned_course_to_programme`
  ADD CONSTRAINT `tbl_assigned_course_to_programme_ibfk_1` FOREIGN KEY (`programme_id`) REFERENCES `tbl_programmes` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_assigned_course_to_programme_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `tbl_courses` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_programmes`
--
ALTER TABLE `tbl_programmes`
  ADD CONSTRAINT `tbl_programmes_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbl_departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_programme_timetable`
--
ALTER TABLE `tbl_programme_timetable`
  ADD CONSTRAINT `tbl_programme_timetable_ibfk_1` FOREIGN KEY (`programme_id`) REFERENCES `tbl_programmes` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_programme_timetable_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `tbl_courses` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_programme_timetable_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `tbl_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_staff_allocated_event`
--
ALTER TABLE `tbl_staff_allocated_event`
  ADD CONSTRAINT `tbl_staff_allocated_event_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbl_departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_staff_allocated_event_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `tbl_rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbl_departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
