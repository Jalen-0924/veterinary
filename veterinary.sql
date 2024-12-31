-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 04:48 AM
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
-- Database: `veterinary`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `services_id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `timeslot` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `reminder_sent` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `doctor_id`, `doctor_name`, `patient_id`, `pet_id`, `services_id`, `date`, `timeslot`, `status`, `invoice_id`, `reminder_sent`, `created_at`) VALUES
(7, 0, '', 21, 135, 47, '2024-12-13', '114', 'Pending', 0, 0, '2024-12-11 21:15:14'),
(9, 0, '', 29, 136, 47, '2024-12-13', '116', 'Confirm', 12, 1, '2024-12-12 23:31:27'),
(16, 0, '', 30, 137, 47, '2024-12-14', '124', 'Confirm', 0, 1, '2024-12-13 19:25:58');

-- --------------------------------------------------------

--
-- Table structure for table `deworm_history`
--

CREATE TABLE `deworm_history` (
  `id` int(11) NOT NULL,
  `deworm_date` varchar(255) NOT NULL,
  `r_date` varchar(255) NOT NULL,
  `weight` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deworm_history`
--

INSERT INTO `deworm_history` (`id`, `deworm_date`, `r_date`, `weight`, `product`, `patient_id`) VALUES
(1, '2024-10-24', '2024-10-24', '', 'Worm Shield', 35);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `ser_name` text NOT NULL,
  `ser_price` text NOT NULL,
  `ser_desc` text NOT NULL,
  `med_name` text NOT NULL,
  `med_qty` text NOT NULL,
  `med_price` text NOT NULL,
  `med_desc` text NOT NULL,
  `total` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `invo_note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `patient_id`, `ser_name`, `ser_price`, `ser_desc`, `med_name`, `med_qty`, `med_price`, `med_desc`, `total`, `date`, `invo_note`) VALUES
(1, 15, '[\"Check Up\\/Consultation\"]', '[\"500\"]', 'null', 'null', 'null', 'null', 'null', '500', '2024-11-02', ''),
(2, 15, '[\"Check Up\\/Consultation\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-11-02', NULL),
(3, 15, '[\"Wellness - Vaccination\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-11-02', NULL),
(4, 15, '[\"Wellness - Vaccination\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-11-02', NULL),
(5, 15, '[\"Wellness - Vaccination\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-11-02', NULL),
(6, 15, '[\"50\"]', '[\"800\"]', 'null', 'null', 'null', 'null', 'null', '800', '2024-11-02', ''),
(7, 15, '[\"Wellness - Parasitic Control\"]', '[\"800\"]', 'null', '[\"100\",\"102\"]', '[\"1\",\"1\"]', '[\"80\",\"114\"]', 'null', '994', '2024-11-02', 'Test'),
(8, 15, '[\"Check Up\\/Consultation\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-11-02', NULL),
(9, 15, '[\"50\"]', '[\"800\"]', '', '', '', '', '', '800', '2024-11-02', NULL),
(10, 15, '[\"Wellness - Deworming\"]', '[\"450\"]', '', '', '', '', '', '450', '2024-11-02', NULL),
(11, 29, '[\"47\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-12-13', NULL),
(12, 29, '[\"47\"]', '[\"500\"]', '', '', '', '', '', '500', '2024-12-13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `diagnosis` varchar(255) NOT NULL,
  `treatment` varchar(255) NOT NULL,
  `results` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`id`, `date`, `diagnosis`, `treatment`, `results`, `patient_id`, `pet_id`) VALUES
(74, '2024-10-24', 'Virus', 'Anti-Virus', 'Cleared', 35, 0),
(75, '2024-10-24', 'Initial Visit', 'General Check-up', 'N/A', 8, 0),
(76, '2024-10-24', 'Initial Visit', 'General Check-up', 'N/A', 8, 0),
(77, '2024-10-24', 'Initial Visit', 'General Check-up', 'N/A', 8, 0),
(78, '', 'Parasite', 'Ivermectin', 'Cleared', 38, 0),
(79, '', 'Initial Visit', 'General Check-up', 'N/A', 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `expiration` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `name`, `category`, `expiration`, `quantity`, `price`) VALUES
(100, 'Dog Food', 'Food and Beverages', '2024-10-31', '9', '80'),
(101, 'Collar', 'Pet Supplies', '', '20', '13'),
(102, 'Goat\'s Milk', 'Food and Beverages', '2024-11-09', '34', '114'),
(103, 'Chain', 'Pet Supplies', '', '10', '35'),
(104, 'Flea Powder', 'Pet Supplies', '', '19', '45'),
(105, 'Pet Soap', 'Pet Supplies', '', '25', '15');

-- --------------------------------------------------------

--
-- Table structure for table `patient_reports`
--

CREATE TABLE `patient_reports` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `medical_history_id` int(11) NOT NULL,
  `services` text NOT NULL,
  `medication` text NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patient_reports`
--

INSERT INTO `patient_reports` (`id`, `doctor_id`, `appointment_id`, `patient_id`, `pet_id`, `medical_history_id`, `services`, `medication`, `status`) VALUES
(28, 49, 90, 47, 42, 0, '[\"1\",\"10\",\"6\"]', '[\"7\",\"8\"]', 'Complete'),
(29, 45, 89, 44, 32, 0, '[\"11\"]', '[\"5\"]', 'Complete'),
(30, 45, 91, 47, 33, 0, '[\"3\"]', '[\"4\",\"5\"]', 'Complete'),
(31, 49, 92, 44, 38, 0, '[\"3\"]', '[\"4\",\"7\"]', 'Complete'),
(32, 45, 93, 51, 37, 0, '[\"6\",\"1\"]', '[\"10\"]', 'Complete'),
(33, 49, 94, 48, 35, 0, '[\"3\"]', '[\"8\",\"10\"]', 'Complete'),
(35, 64, 96, 65, 44, 0, '[\"3\",\"10\"]', '[\"7\",\"8\"]', 'Complete'),
(36, 67, 99, 66, 45, 0, '[\"3\"]', '[\"5\"]', 'Complete'),
(37, 2, 100, 66, 45, 0, 'null', 'null', 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `species` varchar(255) NOT NULL,
  `breed` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `weight` varchar(255) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `colorm` varchar(255) NOT NULL,
  `rstatus` varchar(255) NOT NULL,
  `mchip` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `owner_id`, `name`, `species`, `breed`, `sex`, `weight`, `birthdate`, `colorm`, `rstatus`, `mchip`, `status`) VALUES
(131, 8, 'Kaikai', 'Canine', 'Pitbull-Aspin Mix', 'Female', '', '', 'Gray', 'Intact', '975723950463', 'Confirm'),
(132, 8, 'sario', 'Canine', 'Aspin', 'Female', '11', '2022-02-07', 'white', 'Intact', '', 'Confirm'),
(133, 13, 'Brownie', 'Canine', 'Askal', 'Female', '', '', 'Black', 'Intact', '', 'Confirm'),
(134, 15, 'Bubbles', 'Canine', 'Aspin', 'Male', '', '2023-01-02', 'White', 'Intact', '', 'Pending'),
(135, 21, 'klijne', 'Feline', 'Maine Coon', 'Female', '4', '2024-12-19', 'color', 'Neutered/Spayed', '', 'Pending'),
(136, 29, 'Klinee', 'wsew', 'Persian', 'Male', '4', '2024-12-04', 'color', 'Neutered/Spayed', '1', 'Pending'),
(137, 30, 'test', 'Feline', 'Sphynx', 'Female', '90', '2024-12-12', 'test', 'Neutered/Spayed', '1', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `pet_confinement`
--

CREATE TABLE `pet_confinement` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text NOT NULL,
  `treatment` text NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `patient_id`, `pet_id`) VALUES
(39, 8, 131),
(41, 21, 135),
(42, 29, 136),
(43, 30, 137);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `month` varchar(11) NOT NULL,
  `year` varchar(255) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `status`) VALUES
(47, 'Check Up/Consultation', '500', 1),
(48, 'Wellness - Vaccination', '500', 1),
(49, 'Wellness - Deworming', '450', 1),
(50, 'Wellness - Parasitic Control', '800', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_doctor_slots`
--

CREATE TABLE `tbl_doctor_slots` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `start_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `number_of_slots` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeslot`
--

CREATE TABLE `timeslot` (
  `id` int(11) NOT NULL,
  `slot` text NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `dr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `timeslot`
--

INSERT INTO `timeslot` (`id`, `slot`, `start_date`, `end_date`, `dr_id`) VALUES
(119, '08:30-09:00', '2024-12-13 08:30', '2024-12-13 09:00', 2),
(120, '09:00-09:30', '2024-12-13 09:00', '2024-12-13 09:30', 2),
(121, '09:30-10:00', '2024-12-13 09:30', '2024-12-13 10:00', 2),
(122, '10:00-10:30', '2024-12-13 10:00', '2024-12-13 10:30', 2),
(123, '10:30-11:00', '2024-12-13 10:30', '2024-12-13 11:00', 2),
(124, '08:30-09:00', '2024-12-14 08:30', '2024-12-14 09:00', 2),
(125, '09:00-09:30', '2024-12-14 09:00', '2024-12-14 09:30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `user_type` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `google_id`, `facebook_id`, `password`, `status`, `user_type`, `phone`, `city`, `zipcode`, `address`, `token`, `profile_pic`, `otp`, `is_verified`) VALUES
(2, 'Administrator', '.', 'pawsomefuriends.business@gmail.com', '', '', '$2y$10$krXxoWNg9tW/BAoHlR7//eBFPsf/mQ3tipJriQzpInbKkSrZs38XG', 1, 'admin', '0000', 'Jasaan', '9000', 'Solana, Jasaan, Misamis Oriental               								                								                								                ', '60c291130509c389829a91954a95e9bd', '1710405635908.jpg', NULL, 0),
(4, 'Jalen', 'Loq', 'doctor@gmail.com', '', '', '$2y$10$1FlYWnaRCCX9.r/g1cpz5.qteK0/v36Vb1/fjEPgJsXcfdGurvv/K', 1, 'doctor', '09757239504', 'Cagayan De Oro', '9000', '', NULL, NULL, NULL, 0),
(8, 'Ellen Joy', 'Loquire', 'lenjoyloquire09@gmail.com', '', '', '$2y$10$E1d6NOdbyZh6eflgQBwEwOOjPZOTm7Hs6PXE90aUd4rkwxfj5LhoG\n', 1, 'patient', '09757239504', 'Cagayan De Oro', '9000', 'Bugo, Cagayan De Oro City, Misamis Oriental, Philippines', NULL, NULL, NULL, 0),
(13, 'Nelle', 'Lokers', 'llenjoyloquire924@gmail.com', '', '', '$2y$10$aGvKt8b1/tsghaAGk7dzyOAu8q6wVIJ7Tmh/WH9TfTgKEN9GPAXyy', 1, 'patient', '09757239504', '', '', '', NULL, NULL, NULL, 0),
(15, 'Patient', 'Patient', 'janalfredruiz@gmail.com', '', '', '$2y$10$24gGdhjfnFx3p1ONDVxk5OB9lV7tmshfyCyES.Bol1vJevYz4W7PG', 1, 'patient', '09913269101', '', '', '', NULL, NULL, NULL, 0),
(23, 'Pabualan,', 'John Rey', 'pabualan.johnrey00@gmail.com', '', '', '$2y$10$MoGQadCPp8krApsB6iAyu.cBG3q/InWFxjtxYLxeYPzxx1F1qWt1S', 1, 'patient', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(28, 'Garcia,', 'Kline Vladimier', 'klineygarcia@gmail.com', '', '', '$2y$10$gnxhE34uatp1wRgJUHTq3eBbSdI7Jr1Zgihkvd9.eYVG9JYovTeWm', 1, 'patient', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(30, 'Sherwin', 'Aleonar', 'sherwin.aleonar2023@gmail.com', '', '', '$2y$10$JWr67CD2LwsEhh/NHYSv.uBUBcUaCa6WK9HwAt/MJToVAMbPAFr06', 1, 'patient', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_history`
--

CREATE TABLE `vaccine_history` (
  `id` int(11) NOT NULL,
  `vaccine_date` varchar(255) NOT NULL,
  `vaccine` varchar(255) NOT NULL,
  `weight` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_history`
--

INSERT INTO `vaccine_history` (`id`, `vaccine_date`, `vaccine`, `weight`, `patient_id`) VALUES
(23, '2024-08-31', 'Prazinate', '1.1 Kg', 35);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deworm_history`
--
ALTER TABLE `deworm_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_reports`
--
ALTER TABLE `patient_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_confinement`
--
ALTER TABLE `pet_confinement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_doctor_slots`
--
ALTER TABLE `tbl_doctor_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslot`
--
ALTER TABLE `timeslot`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vaccine_history`
--
ALTER TABLE `vaccine_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `deworm_history`
--
ALTER TABLE `deworm_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `patient_reports`
--
ALTER TABLE `patient_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `pet_confinement`
--
ALTER TABLE `pet_confinement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_doctor_slots`
--
ALTER TABLE `tbl_doctor_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeslot`
--
ALTER TABLE `timeslot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `vaccine_history`
--
ALTER TABLE `vaccine_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
