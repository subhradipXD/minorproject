-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2023 at 07:39 PM
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
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `instid` varchar(255) NOT NULL,
  `deptid` varchar(255) NOT NULL,
  `deptname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('Tech3373', 'BTech2', 'BTechCS', 'NCC0505', 'NCC', 2023, 'fdsfsdv', 'cavac', 'eafceac', 'cadevad', 'advdv', 'daveav', 'bgfhnn', '4', 'uploads/album_2023-10-17_23-47-56.gif', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'vvds');

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
('Tech3373', 'Tech3373Subh3106', 'BTech1', 'Adhiraj', 'BTechCS', 'adhiraj.mca@gmail.com', 123456789, 'Li6kW98j'),
('Tech3373', 'Tech3373Subh3106', 'BTech2', 'Gourav', 'BTechCS', 'gourav.mca@gmail.com', 123456789, 'X3mSXG6q'),
('Tech3373', 'Tech3373Subh3106', 'BTech3', '', 'BTechCS', '.mca@gmail.com', 123456789, 'thPQQS36'),
('hitk4825', 'hitk4825Subh4066', 'BTech4', 'Anirban', 'BTechCS', 'anirban.mca@gmail.com', 123456789, '6oAuUgw4'),
('hitk4825', 'hitk4825Subh4066', 'BTech5', 'Gourav', 'BTechCS', 'gourav.mca@gmail.com', 123456789, 'R77Ym0YU'),
('hitk4825', 'hitk4825Subh4066', 'BTech6', '', 'BTechCS', '.mca@gmail.com', 123456789, 'xoL9CNB1'),
('Tech3373', 'Tech3373Subh3106', 'EC1', 'Som', 'EC', 'som.mca@gmail.com', 123456789, 'UBd6xRBH'),
('Tech3373', 'Tech3373Subh3106', 'EC2', 'Subhra', 'EC', 'subhra.mca@gmail.com', 123456789, 'xN69bLK2'),
('Tech3373', 'Tech3373Subh3106', 'EC3', 'Priya', 'EC', 'priya.mca@gmail.com', 123456789, '7B85auGE'),
('hitk4825', 'hitk4825Subh4066', 'EC4', 'Atrayee', 'EC', 'atrayee.mca@gmail.com', 123456789, 'lLyE2gEu'),
('hitk4825', 'hitk4825Subh4066', 'EC5', 'Subhradip', 'EC', 'subhradip.mca@gmail.com', 123456789, 'bQCCM7SA'),
('hitk4825', 'hitk4825Subh4066', 'EC6', 'Pranati', 'EC', 'pranati.mca@gmail.com', 123456789, '0Oi3W3he'),
('Tech3373', 'Tech3373Subh3106', 'MCA1', 'Arjun', 'MCA', 'arjun.mca@gmail.com', 123456789, 'X7SOfnzm'),
('Tech3373', 'Tech3373Subh3106', 'MCA2', 'Soumik', 'MCA', 'soumik.mca@gmail.com', 123456789, 'dqfZmKQ4'),
('Tech3373', 'Tech3373Subh3106', 'MCA3', '', 'MCA', '.mca@gmail.com', 123456789, '73D0sojF'),
('hitk4825', 'hitk4825Subh4066', 'MCA6', 'Pritam', 'MCA', 'pritam.mca@gmail.com', 123456789, 'Y8O3wjus'),
('hitk4825', 'hitk4825Subh4066', 'MCA7', '', 'MCA', '.mca@gmail.com', 123456789, 'RE0zUdqI');

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
  `activereport` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`instid`,`adminid`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptid`,`instid`),
  ADD KEY `instid` (`instid`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `researchwork`
--
ALTER TABLE `researchwork`
  ADD PRIMARY KEY (`proid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`instid`) REFERENCES `admin` (`instid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
