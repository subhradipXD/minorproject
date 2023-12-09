-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2023 at 02:03 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `minorproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `instid` varchar(255) NOT NULL,
  `instname` varchar(255) NOT NULL,
  `instlogo` varchar(255) NOT NULL,
  `instadd` varchar(255) NOT NULL,
  `instemail` varchar(255) NOT NULL,
  `instphno` varchar(255) NOT NULL,
  `adminid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phno` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `adminimage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`instid`, `instname`, `instlogo`, `instadd`, `instemail`, `instphno`, `adminid`, `name`, `email`, `phno`, `pass`, `adminimage`) VALUES
('hitk4825', 'hitk', 'uploads/Snapchat-1059982910.jpg', 'none', 'hitk@gmail.com', '+919635760319', 'hitk4825Subh4066', 'Subhradip Das', 'subhradipdas6969@gmail.com', '+919635760319', 'Joydas', 'uploads/Snapchat-1059982910.jpg'),
('Tech3373', 'Techno', 'uploads/album_2023-10-17_23-47-56.gif', 'none', 'techno@gmail.com', '+919635760319', 'Tech3373Subh3106', 'Subhradip Das', 'subhradipdas6969@gmail.com', '+919635760319', 'Joydas', 'uploads/Snapchat-1059982910.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `instid` varchar(255) NOT NULL,
  `fid` varchar(255) NOT NULL,
  `deptid` varchar(255) NOT NULL,
  `eventid` varchar(255) NOT NULL,
  `eventname` varchar(255) NOT NULL,
  `eventyear` int(255) NOT NULL,
  `scheme` varchar(255) NOT NULL,
  `c_a_n_govt` varchar(255) NOT NULL,
  `c_a_n_govt_contact` varchar(255) NOT NULL,
  `c_a_ind` varchar(255) NOT NULL,
  `c_a_ind_contact` varchar(255) NOT NULL,
  `c_a_ngo` varchar(255) NOT NULL,
  `c_a_ngo_contact` varchar(255) NOT NULL,
  `avgstu` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `reports` varchar(255) NOT NULL,
  `addinfo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`instid`, `fid`, `deptid`, `eventid`, `eventname`, `eventyear`, `scheme`, `c_a_n_govt`, `c_a_n_govt_contact`, `c_a_ind`, `c_a_ind_contact`, `c_a_ngo`, `c_a_ngo_contact`, `avgstu`, `images`, `reports`, `addinfo`) VALUES
('Tech3373', 'EC3', 'MCA', 'NCC0465', 'NCC', 2023, 'NCC Sports Event Org By Techno College Under AK Sir', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/IMG_20230803_111115.jpg', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'NCC Sports Event Org By Techno College Under AK Sir'),
('Tech3373', 'BTech1', 'BTechCS', 'NCC1060', 'NCC', 2023, 'ncc game ncc nccnccncc', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/album_2023-10-17_23-47-56.gif', 'uploads/MCA_(2Years)_Jun-2021.pdf', 'NCC nccnew newncc '),
('Tech3373', 'EC3', 'MCA', 'NCC2676', 'NCC', 2023, 'NCC Sports Event Org By Techno College Under AK Sir', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/IMG_20230803_111115.jpg', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'NCC Sports Event Org By Techno College Under AK Sir'),
('Tech3373', 'BTech2', 'BTechCS', 'NCC3792', 'NCC', 2023, 'vjhvhjbjknjbubjkjkh798t674e5tyfvghv jhn', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/album_2023-10-17_23-47-56.gif', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', ' jkn98h8uj09jpomip,'),
('Tech3373', 'EC3', 'MCA', 'NCC6576', 'NCC', 2023, 'NCC Sports Event Org By Techno College Under AK Sir', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/IMG_20230803_111115.jpg', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'NCC Sports Event Org By Techno College Under AK Sir');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `instid` varchar(255) NOT NULL,
  `adminid` varchar(255) NOT NULL,
  `fid` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `fdept` varchar(255) NOT NULL,
  `femail` varchar(255) NOT NULL,
  `fphno` int(255) NOT NULL,
  `fpass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`instid`, `adminid`, `fid`, `fname`, `fdept`, `femail`, `fphno`, `fpass`) VALUES
('Tech3373', 'Tech3373Subh3106', 'BTech1', 'Adhiraj', 'BTechCS', 'adhiraj.mca@gmail.com', 123456789, 'dIAmO5ra'),
('Tech3373', 'Tech3373Subh3106', 'BTech2', 'Gourav', 'BTechCS', 'gourav.mca@gmail.com', 123456789, 'yQO8WKFu'),
('hitk4825', 'hitk4825Subh4066', 'BTech4', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, '6oAuUgw4'),
('hitk4825', 'hitk4825Subh4066', 'BTech5', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'R77Ym0YU'),
('hitk4825', 'hitk4825Subh4066', 'BTech6', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'xoL9CNB1'),
('hitk4825', 'hitk4825Subh4066', 'EC4', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'lLyE2gEu'),
('hitk4825', 'hitk4825Subh4066', 'EC5', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'bQCCM7SA'),
('hitk4825', 'hitk4825Subh4066', 'EC6', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, '0Oi3W3he'),
('hitk4825', 'hitk4825Subh4066', 'MCA6', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'Y8O3wjus'),
('hitk4825', 'hitk4825Subh4066', 'MCA7', 'Adhiraj', 'EC', 'adhiraj.mca@gmail.com', 2147483647, 'RE0zUdqI');

-- --------------------------------------------------------

--
-- Table structure for table `researchwork`
--

CREATE TABLE `researchwork` (
  `proid` varchar(255) NOT NULL,
  `instid` varchar(255) NOT NULL,
  `deptname` varchar(255) NOT NULL,
  `fid` varchar(255) NOT NULL,
  `proname` varchar(255) NOT NULL,
  `picopi` varchar(255) NOT NULL,
  `fundagent` varchar(255) NOT NULL,
  `award` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `ecopy` varchar(255) NOT NULL,
  `fundstatement` varchar(255) NOT NULL,
  `activereport` varchar(255) NOT NULL,
  `addinfo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `researchwork`
--

INSERT INTO `researchwork` (`proid`, `instid`, `deptname`, `fid`, `proname`, `picopi`, `fundagent`, `award`, `duration`, `ecopy`, `fundstatement`, `activereport`, `addinfo`) VALUES
('ML3018', 'Tech3373', 'MCA', 'EC2', 'ML', 'SG Sir ', 'Keu Na', '2021', '23', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'We Complete machine learning project under sg sir'),
('Web Dev2371', 'Tech3373', 'MCA', 'EC2', 'Web Dev', 'Ak Sir', 'Keu Na', '2021', '23', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'web development project under AK Sir it was nice experience ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`instid`,`adminid`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventid`),
  ADD KEY `instid` (`instid`),
  ADD KEY `fid` (`fid`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `instid` (`instid`);

--
-- Indexes for table `researchwork`
--
ALTER TABLE `researchwork`
  ADD PRIMARY KEY (`proid`),
  ADD KEY `instid` (`instid`),
  ADD KEY `fid` (`fid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`instid`) REFERENCES `admin` (`instid`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`instid`) REFERENCES `admin` (`instid`);

--
-- Constraints for table `researchwork`
--
ALTER TABLE `researchwork`
  ADD CONSTRAINT `researchwork_ibfk_1` FOREIGN KEY (`instid`) REFERENCES `admin` (`instid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
