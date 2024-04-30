-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-db
-- Generation Time: Apr 30, 2024 at 01:40 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` decimal(4,0) DEFAULT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isbn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `title`, `author`, `publication_year`, `publisher`, `genre`, `isbn`) VALUES
(20, 'Pride and Prejudice', 'Jane Austen', 1813, 'T. Egerton, Whitehall', 'Novel', '9780141439518'),
(21, 'The Catcher in the Rye', 'J.D. Salinger', 1951, 'Little, Brown and Company', 'Novel', '9780316769488'),
(22, 'The Hobbit', 'J.R.R. Tolkien', 1937, 'Allen & Unwin', 'Fantasy', '9780547928227'),
(23, 'Moby-Dick', 'Herman Melville', 1851, 'Harper & Brothers', 'Adventure', '9780143124672'),
(24, 'Brave New World', 'Aldous Huxley', 1932, 'Chatto & Windus', 'Science Fiction', '9780060850524'),
(25, 'Ashes', 'Unknown Author', 2023, 'Unknown Publisher', 'Unknown Genre', '978-1-60309-517-4'),
(26, 'The Delicacy', 'Unknown Author', 2023, 'Unknown Publisher', 'Unknown Genre', '978-1-60309-492-4'),
(30, 'Tomorrow, and Tomorrow, and Tomorrow: A novel', 'Gabrielle Zevin', 2023, 'Unknown Publisher', 'Unknown Genre', '978-0-593-32120-1'),
(32, 'Catch a Crayfish, Count the Stars: Fun Projects, Skills, and Adventures for Outdoor Kids', 'Steven Rinella, Max Temescu (Illustrator)', 2023, 'Unknown Publisher', 'Unknown Genre', '978-0-593-44897-7'),
(33, 'Dork Diaries 15: Tales from a Not-So-Posh Paris Adventure', 'Unknown Author', 2023, 'Unknown Publisher', 'Unknown Genre', '978-1-53448-048-3'),
(34, 'The Heaven & Earth Grocery Store: A Novel', 'James McBride', 2023, 'Unknown Publisher', 'Unknown Genre', '978-0-593-42294-6'),
(36, 'To Kill a Mockingbird', 'Harper Lee', 1960, 'J. B. Lippincott & Co.', 'Novel', '978-0-446-31078-9'),
(37, '1984', 'George Orwell', 1949, 'Secker & Warburg', 'Dystopian Fiction', '978-0-451-52493-5'),
(38, 'The Great Gatsby', 'F. Scott Fitzgerald', 1925, 'Scribner', 'Novel', '978-0-7432-7356-5'),
(45, 'The Old Man and the Sea', 'Ernest Hemingway', 1952, 'Scribner', 'Novel', '978-0-684-80122-3');

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `loan_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C5D30D0316A2B381` (`book_id`),
  ADD KEY `IDX_C5D30D03A76ED395` (`user_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `FK_C5D30D0316A2B381` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`),
  ADD CONSTRAINT `FK_C5D30D03A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
