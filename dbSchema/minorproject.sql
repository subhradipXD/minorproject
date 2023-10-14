CREATE DATABASE if not exists minorproject;
use minorproject;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2023 at 07:21 AM
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
('BTech1', 'Adhiraj', 'BTechCS', 'adhiraj.mca@gmail.com', 123456789, ''),
('BTech2', 'Gourav', 'BTechCS', 'gourav.mca@gmail.com', 123456789, ''),
('BTech3', '', 'BTechCS', '.mca@gmail.com', 123456789, ''),
('EC1', 'Som', 'EC', 'som.mca@gmail.com', 123456789, ''),
('EC2', 'Subhra', 'EC', 'subhra.mca@gmail.com', 123456789, ''),
('EC3', 'Priya', 'EC', 'priya.mca@gmail.com', 123456789, ''),
('MCA1', 'Arjun', 'MCA', 'arjun.mca@gmail.com', 123456789, ''),
('MCA2', 'Soumik', 'MCA', 'soumik.mca@gmail.com', 123456789, ''),
('MCA3', '', 'MCA', '.mca@gmail.com', 123456789, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`fid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
