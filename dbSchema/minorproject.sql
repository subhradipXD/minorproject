-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2023 at 11:25 PM
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
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `instid` varchar(255) NOT NULL,
  `activityid` varchar(255) NOT NULL,
  `activityname` varchar(255) NOT NULL,
  `scheme` varchar(255) NOT NULL,
  `reports` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `avgstu` varchar(255) NOT NULL,
  `addinfo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`instid`, `activityid`, `activityname`, `scheme`, `reports`, `images`, `avgstu`, `addinfo`) VALUES
('', '1186', 'NCC', 'none', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/album_2023-10-17_23-47-56.gif', '5', 'none'),
('', '7598', 'NCC', 'none', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/IMG_20230803_111115.jpg', '5', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `instid` varchar(255) NOT NULL,
  `adminid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phno` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `instid` varchar(255) NOT NULL,
  `eventid` varchar(255) NOT NULL,
  `eventname` varchar(255) NOT NULL,
  `eventyear` int(255) NOT NULL,
  `c_a_n_govt` varchar(255) NOT NULL,
  `c_a_n_govt_contact` varchar(255) NOT NULL,
  `c_a_ind` varchar(255) NOT NULL,
  `c_a_ind_contact` varchar(255) NOT NULL,
  `c_a_ngo` varchar(255) NOT NULL,
  `c_a_ngo_contact` varchar(255) NOT NULL,
  `reports` varchar(255) NOT NULL,
  `addinfo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`instid`, `eventid`, `eventname`, `eventyear`, `c_a_n_govt`, `c_a_n_govt_contact`, `c_a_ind`, `c_a_ind_contact`, `c_a_ngo`, `c_a_ngo_contact`, `reports`, `addinfo`) VALUES
('', '1607', 'NCC', 2023, 'hit', 'kolkata', 'tchno', 'kolkata', 'none', 'none', 'uploads/MCA_(2Years)_Jun-2021.pdf', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
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

INSERT INTO `faculty` (`fid`, `fname`, `fdept`, `femail`, `fphno`, `fpass`) VALUES
('BTech1', 'Adhiraj', 'BTechCS', 'adhiraj.mca@gmail.com', 123456789, 'CBz1I91p'),
('BTech2', 'Gourav', 'BTechCS', 'gourav.mca@gmail.com', 123456789, 'tzImpk4F'),
('BTech3', '', 'BTechCS', '.mca@gmail.com', 123456789, 'MC9u5p3Y'),
('EC1', 'Som', 'EC', 'som.mca@gmail.com', 123456789, 'x4WiNXzG'),
('EC2', 'Subhra', 'EC', 'subhra.mca@gmail.com', 123456789, 'x5NN8vAh'),
('EC3', 'Priya', 'EC', 'priya.mca@gmail.com', 123456789, 'i6X0tNNP'),
('MCA1', 'Arjun', 'MCA', 'arjun.mca@gmail.com', 123456789, '7K07mdfj'),
('MCA2', 'Soumik', 'MCA', 'soumik.mca@gmail.com', 123456789, 'KRiY7n0g'),
('MCA3', '', 'MCA', '.mca@gmail.com', 123456789, 'csrO584o'),
('MCA4', 'Subhradip', 'MCA', 'subhradipdas6969@gmail.com', 2147483647, 'grH9SkQO');

-- --------------------------------------------------------

--
-- Table structure for table `researchwork`
--

CREATE TABLE `researchwork` (
  `proname` varchar(255) NOT NULL,
  `picopi` varchar(255) NOT NULL,
  `fundagent` varchar(255) NOT NULL,
  `award` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `ecopy` varchar(255) NOT NULL,
  `fundstatement` varchar(255) NOT NULL,
  `activereport` varchar(255) NOT NULL,
  `factname` varchar(255) NOT NULL,
  `deptname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `researchwork`
--

INSERT INTO `researchwork` (`proname`, `picopi`, `fundagent`, `award`, `duration`, `ecopy`, `fundstatement`, `activereport`, `factname`, `deptname`) VALUES
('network', 'Subhradip Das', 'keu akjon', '2023', '23', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', '', ''),
('network', 'Subhradip Das', 'keu akjon', '2023', '23', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', 'uploads/Manual-for-Autonomous-Colleges-15-4-2021.pdf', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityid`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
