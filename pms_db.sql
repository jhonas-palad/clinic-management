-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2022 at 07:19 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE DATABASE pms_db;
USE pms_db;

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`) VALUES
(1, 'Amoxicillin'),
(4, 'Antibiotic'),
(5, 'Antihistamine'),
(6, 'Atorvastatin'),
(3, 'Losartan'),
(2, 'Mefenamic'),
(7, 'Oxymetazoline');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_details`
--

CREATE TABLE `medicine_details` (
  `id` int(11) NOT NULL,
  -- `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(60) NOT NULL,
  `total_capsules` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medicine_details`
--

INSERT INTO `medicine_details` (`id`, `medicine_name`, `total_capsules`) VALUES
(1,'Amoxicillin', 500),
(2,'Antibiotic', 300),
(3,'Antihistamine', 200),
(4,'Atorvastatin', 250),
(5,'Losartan', 800),
(6,'Mefenamic', 1000),
(7,'Oxymetazoline', 250);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `past_medical_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allergy` ENUM('yes', 'no') DEFAULT 'no',
  `asthma` ENUM('yes', 'no') DEFAULT 'no',
  `anemia` ENUM('yes', 'no') DEFAULT 'no',
  `behavioral_problem` ENUM('yes', 'no') DEFAULT 'no',
  `hearing_problem` ENUM('yes', 'no') DEFAULT 'no',
  `speech_problem` ENUM('yes', 'no') DEFAULT 'no',
  `visual_problem` ENUM('yes', 'no') DEFAULT 'no',
  `recurrent_indigestion` ENUM('yes', 'no') DEFAULT 'no',
  `joundloe` ENUM('yes', 'no') DEFAULT 'no',
  `eating_disorder` ENUM('yes', 'no') DEFAULT 'no',
  `chicken_pox` ENUM('yes', 'no') DEFAULT 'no',
  `dengue_fever` ENUM('yes', 'no') DEFAULT 'no',
  `typhoid_fever` ENUM('yes', 'no') DEFAULT 'no',
  `mumps` ENUM('yes', 'no') DEFAULT 'no',
  `pnemonia` ENUM('yes', 'no') DEFAULT 'no',
  `primary_complex` ENUM('yes', 'no') DEFAULT 'no',
  `ear_discharge` ENUM('yes', 'no') DEFAULT 'no',
  `tonsilitis` ENUM('yes', 'no') DEFAULT 'no',
  `paratism` ENUM('yes', 'no') DEFAULT 'no',
  `insomia` ENUM('yes', 'no') DEFAULT 'no',
  `heart_disease` ENUM('yes', 'no') DEFAULT 'no',
  `kidney_disease` ENUM('yes', 'no') DEFAULT 'no',
  `convultion_epillepsy` ENUM('yes', 'no') DEFAULT 'no',
  `diabetes` ENUM('yes', 'no') DEFAULT 'no',
  `fainting` ENUM('yes', 'no') DEFAULT 'no',
  `fractures` ENUM('yes', 'no') DEFAULT 'no',
  `hospitalization` ENUM('yes', 'no') DEFAULT 'no',
  `operation` ENUM('yes', 'no') DEFAULT 'no',
  `scoliosis` ENUM('yes', 'no') DEFAULT 'no',
  PRIMARY KEY(`id`) 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `family_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cancer` ENUM('yes', 'no') DEFAULT 'no',
  `tuberculosis` ENUM('yes', 'no') DEFAULT 'no',
  `high_blood` ENUM('yes', 'no') DEFAULT 'no',
  `diabetes` ENUM('yes', 'no') DEFAULT 'no',
  `kidney_problem` ENUM('yes', 'no') DEFAULT 'no',
  `seizure_disorder` ENUM('yes', 'no') DEFAULT 'no',
  `asthma` ENUM('yes', 'no') DEFAULT 'no',
  `tendency_to_bleed` ENUM('yes', 'no') DEFAULT 'no',
  `mental_trouble` ENUM('yes', 'no') DEFAULT 'no',
  `stroke` ENUM('yes', 'no') DEFAULT 'no',
  `obesity` ENUM('yes', 'no') DEFAULT 'no',
  PRIMARY KEY(`id`) 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `family_history_relation`(
  `id` INT NOT NULL,
  `cancer` VARCHAR(20) NOT NULL,
  `tuberculosis` VARCHAR(20) NOT NULL,
  `high_blood` VARCHAR(20) NOT NULL,
  `diabetes` VARCHAR(20) NOT NULL,
  `kidney_problem` VARCHAR(20) NOT NULL,
  `seizure_disorder` VARCHAR(20) NOT NULL,
  `asthma` VARCHAR(20) NOT NULL,
  `tendency_to_bleed` VARCHAR(20) NOT NULL,
  `mental_trouble` VARCHAR(20) NOT NULL,
  `stroke` VARCHAR(20) NOT NULL,
  `obesity` VARCHAR(20) NOT NULL,
  UNIQUE KEY `family_history_relation_unique` (`id`),
  CONSTRAINT `fk_family_history_relation` FOREIGN KEY (`id`) REFERENCES `family_history` (`id`) ON DELETE CASCADE 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `immunization`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dpt_opv_i` date DEFAULT NULL,
  `dpt_opv_ii` date DEFAULT NULL,
  `dpt_opv_iii` date DEFAULT NULL,
  `dpt_opv_booster_i` date DEFAULT NULL,
  `dpt_opv_booster_ii` date DEFAULT NULL,
  `hib_i` date DEFAULT NULL,
  `hib_ii` date DEFAULT NULL,
  `hib_iii` date DEFAULT NULL,
  `anti_measios` date DEFAULT NULL,
  `anti_hepit_b_i` date DEFAULT NULL,
  `anti_hepit_b_ii` date DEFAULT NULL,
  `anti_hepit_b_iii` date DEFAULT NULL,
  `mmr` date DEFAULT NULL,
  `anti_chicken_pox` date DEFAULT NULL,
  `anti_hepepititis_a_i` date DEFAULT NULL,
  `anti_hepepititis_a_ii` date DEFAULT NULL,
  `anti_hepepititis_a_iii` date DEFAULT NULL,
  `anti_typhoid_fever` date DEFAULT NULL,
  `others` date DEFAULT NULL,
  PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `immunization` (`dpt_opv_i`,`dpt_opv_ii`,`dpt_opv_iii`,`dpt_opv_booster_i`,`dpt_opv_booster_ii`,`hib_i`,`hib_ii`,`hib_iii`,`anti_measios`,`anti_hepit_b_i`,`anti_hepit_b_ii`,`anti_hepit_b_iii`,`mmr`,`anti_chicken_pox`,`anti_hepepititis_a_i`,`anti_hepepititis_a_ii`,`anti_hepepititis_a_iii`,`anti_typhoid_fever`,`others`)
VALUES ('1999-06-23', '1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23','1999-06-23');

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(60) NOT NULL,
  `address` varchar(100) NOT NULL,
  `course` varchar(17) NOT NULL,
  `date_of_birth` date NOT NULL,
  `todays_time` varchar(255) NOT NULL,
  `phone_number` varchar(12) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `complaint` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `patients` (`id`, `patient_name`, `address`, `course`, `date_of_birth`, `todays_time`, `phone_number`, `gender`, `complaint`) VALUES
(1, 'Mark Cooper', 'Sample Address 101 - Updated', '123654789', '1999-06-23', '04:13', '091235649879', 'Male', 'Nothing');

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

CREATE TABLE IF NOT EXISTS `health_record`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `past_medical_history_id` int(11) NOT NULL,
  `family_history_id` int(11) NOT NULL,
  `immunization_id` int(11) NOT NULL,
  PRIMARY KEY(`id`),
  CONSTRAINT `fk_health_record_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_health_record_past_medical_history` FOREIGN KEY (`past_medical_history_id`) REFERENCES `past_medical_history` (`id`) ON DELETE CASCADE, 
  CONSTRAINT `fk_health_record_family_history` FOREIGN KEY (`family_history_id`) REFERENCES `family_history` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_health_record_immunization` FOREIGN KEY (`immunization_id`) REFERENCES `immunization` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE UNIQUE INDEX `idx_patient_past_family_history`
ON health_record(patient_id,past_medical_history_id,family_history_id);


INSERT INTO `past_medical_history` (`allergy`,`asthma`,`anemia`,`behavioral_problem`,`hearing_problem`,`speech_problem`,`visual_problem`,`recurrent_indigestion`,`joundloe`,`eating_disorder`,`chicken_pox`,`dengue_fever`,`typhoid_fever`,`mumps`,`pnemonia`,`primary_complex`,`ear_discharge`,`tonsilitis`,`paratism`,`insomia`,`heart_disease`,`kidney_disease`,`convultion_epillepsy`,`diabetes`,`fainting`,`fractures`,`hospitalization`,`operation`,`scoliosis`)
VALUES ('yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'yes', 'no');

INSERT INTO `family_history` (`cancer`,`tuberculosis`,`high_blood`,`diabetes`,`kidney_problem`,`seizure_disorder`,`asthma`,`tendency_to_bleed`,`mental_trouble`,`stroke`,`obesity`)
VALUES ('no', 'no', 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no', 'yes');

INSERT INTO `family_history_relation`
VALUES (1,'Mother', 'Mother', 'Mother', 'Mother', 'Father', 'Father', 'Father', 'Father', 'Father', 'Father', 'Father');

INSERT INTO `health_record` (`patient_id`, `past_medical_history_id`, `family_history_id`, `immunization_id`)
VALUES (1, 1, 1, 1);
-- --------------------------------------------------------

--
-- Table structure for table `quantity`
--

CREATE TABLE `patient_medication_history` (
  `id` int(11) NOT NULL,
  `patient_visit_id` int(11) NOT NULL,
  `medicine_detail_id` int(11) NOT NULL,
  `quantity` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient_medication_history`
--

INSERT INTO `patient_medication_history` (`id`, `patient_visit_id`, `medicine_detail_id`, `quantity`) VALUES
(1, 1, 3, 1),
(2, 1, 4, 1),
(3, 2, 5, 1),
(4, 2, 6, 1);


-- CREATE TABLE `patient_medication_history` (
--   `id` int(11) AUTO_INCREMENT NOT NULL,
--   `patient_visits_history_id` int(11) NOT NULL,
--   `medication_detail_id` int(11) NOT NULL,
--   `quantity` int DEFAULT 1,
--   PRIMARY KEY(`id`),
--   CONSTRAINT `fk_patient_visits_history_id` FOREIGN KEY (`patient_visits_history_id`) REFERENCES `patient_visits_history` (`id`),
--   CONSTRAINT `fk_medication_detail_id` FOREIGN KEY (`medication_detail_id`) REFERENCES `medicine_details` (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `patient_visits`
--

CREATE TABLE `patient_visits` (
  `id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `next_visit_date` date DEFAULT NULL,
  `bp` varchar(23) NOT NULL,
  `weight` varchar(12) NOT NULL,
  `disease` varchar(30) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient_visits`
--

INSERT INTO `patient_visits` (`id`, `visit_date`, `next_visit_date`, `bp`, `weight`, `disease`, `patient_id`) VALUES
(1, '2022-06-28', '2022-06-30', '120/80', '65 kg.', 'Wounded Arm', 1),
(2, '2022-06-30', '2022-07-02', '120/80', '65 kg.', 'Rhinovirus', 1);

-- --------------------------------------------------------

--
-- Table structure for table `template_forms`
--

CREATE TABLE `template_forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `template_forms`
--

INSERT INTO `template_forms` (`id`, `title`, `file_name`) VALUES
(1, 'A', 'templates/annabelle639b3c2370e75.jpg'),
(2, '123123', 'templates/DataTables example - HTML5 export buttons639b3e780366c.csv'),
(3, '321', 'templates/1w639b3f568d829.jfif'),
(4, '123123', 'templates/DataTables example - HTML5 export buttons (1)639b40e71a2e8.xlsx');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `profile_picture` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `display_name`, `user_name`, `password`, `profile_picture`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', '1656551981avatar.png '),
(2, 'John Doe', 'jdoe', '9c86d448e84d4ba23eb089e0b5160207', '1656551999avatar_.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicine_name` (`medicine_name`);

--
-- Indexes for table `medicine_details`
--
ALTER TABLE `medicine_details`
  ADD PRIMARY KEY (`id`);
  -- ADD KEY `fk_medicine_details_medicine_id` (`medicine_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);



--
-- Indexes for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient_medication_history_patients_visits_id` (`patient_visit_id`),
  ADD KEY `fk_patient_medication_history_medicine_details_id` (`medicine_detail_id`);

--
-- Indexes for table `patient_visits`
--
ALTER TABLE `patient_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patients_visit_patient_id` (`patient_id`);

--
-- Indexes for table `template_forms`
--
ALTER TABLE `template_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `medicine_details`
--
ALTER TABLE `medicine_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;



--
-- AUTO_INCREMENT for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patient_visits`
--
ALTER TABLE `patient_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `template_forms`
--
ALTER TABLE `template_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicine_details`
--
-- ALTER TABLE `medicine_details`
  -- ADD CONSTRAINT `fk_medicine_details_medicine_id` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `patient_medication_history`
--
ALTER TABLE `patient_medication_history`
  ADD CONSTRAINT `fk_patient_medication_history_medicine_details_id` FOREIGN KEY (`medicine_detail_id`) REFERENCES `medicine_details` (`id`),
  ADD CONSTRAINT `fk_patient_medication_history_patients_visits_id` FOREIGN KEY (`patient_visit_id`) REFERENCES `patient_visits` (`id`);

--
-- Constraints for table `patient_visits`
--
ALTER TABLE `patient_visits`
  ADD CONSTRAINT `fk_patients_visit_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--