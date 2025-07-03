-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 09:26 AM
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
-- Database: `book_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `name`) VALUES
(1, 'Jena Brown'),
(2, 'Catherine Saunders'),
(3, 'Stan Lee'),
(4, 'Madeleine Rou'),
(5, ' James Robinson '),
(6, 'Bram Stoker'),
(7, 'Alma Katsu'),
(8, 'Truman Capote'),
(9, 'Wilkie Collins'),
(10, 'Gillian Flynn'),
(11, 'Walter Isaacson '),
(12, 'Barrack Obama'),
(13, 'Lekhnath Paudya'),
(14, 'Laxmi Prasad Devkota');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `author_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `publication_year` year(4) NOT NULL,
  `publisher` varchar(50) NOT NULL,
  `copies_available` int(10) UNSIGNED NOT NULL,
  `total_copies` int(5) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `isbn`, `price`, `author_id`, `category_id`, `publication_year`, `publisher`, `copies_available`, `total_copies`, `image`) VALUES
(1, 'The Luminous Dead', 'abcd100', 250, 1, 1, '2021', 'Aashmita Publication', 20, 20, 'The Luminous Dead.jpg'),
(2, 'The Amazing Spiderman', 'abcd1001', 225, 2, 1, '2006', 'Marvel', 22, 22, 'The amazing spiderman.jpg'),
(3, 'Spiderman', 'abcd1002', 275, 3, 1, '2024', 'Marvel', 22, 22, 'spiderman.jpg'),
(4, 'Loki', 'abcd1003', 280, 4, 1, '2024', 'Marvel', 25, 30, 'loki.jpg'),
(5, 'Scarlet Witch', 'abcd1004', 300, 5, 1, '2016', 'Marvel', 14, 15, 'Scarlet Witch.jpg'),
(6, 'Stan Lee', 'abcd1005', 240, 3, 1, '2016', 'Yale University', 16, 18, 'stan lee.avif'),
(7, 'Dracula', 'abcd1006', 280, 6, 2, '1980', 'E-Kitap Projesi', 9, 10, 'Dracula.webp'),
(8, 'The Hunger', 'abcd1007', 325, 7, 2, '2010', 'Goodread', 20, 20, 'The Hunger.webp'),
(9, 'In Cold Blood', 'abcd1008', 300, 8, 3, '1966', 'Vintage', 15, 15, 'In Cold Blood.jpg'),
(10, 'The Woman in White', 'abcd1009', 315, 9, 3, '1992', 'Penguin Classics ', 25, 25, 'The Woman in White.jpg'),
(11, 'Dark Places', 'abcd10010', 250, 10, 3, '2009', 'Shaye Areheart Books', 0, 10, 'Dark Places.jpg'),
(12, 'Steve Jobs', 'abcd10011', 400, 11, 4, '2011', 'Simon & Schuster', 18, 18, 'Steve Jobs.jpg'),
(13, 'Einstein', 'abcd10012', 360, 11, 4, '2007', 'Simon & Schuster', 15, 15, 'Einstein.jpg'),
(14, 'Elon Musk', 'abcd10014', 350, 11, 4, '2021', 'Simon & Schuster', 29, 30, 'Elon Musk.jpg'),
(15, 'A Promised Land', 'abcd10015', 355, 12, 4, '2020', 'Random House ', 0, 20, 'A Promise Land.jpg'),
(16, 'Tarun Tapasi', 'abcd10016', 285, 13, 5, '2009', 'Sajha Prakashan', 20, 20, 'Tarun Tapasi.jpg'),
(17, 'बुद्धिविनोद [Buddhibinod]', 'abcd10017', 300, 13, 5, '2009', 'Manjari Publication', 10, 10, 'Buddhibinod.jpg'),
(18, 'नेपाली शाकुन्तल [Nepali Sakuntal]', 'abcd10019', 399, 14, 5, '1991', ' साझा प्रकाशन', 15, 15, 'नेपाली शाकुन्तल.jpg'),
(19, 'मुनामदन [Muna Madan]', 'abcd10020', 410, 14, 5, '1936', 'Adarsh Books', 20, 20, 'मुनामदन [Muna Madan].jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(10) NOT NULL,
  `genre` varchar(150) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `genre`, `description`) VALUES
(1, 'Sci-Fi', 'As stated by Captain James T. Kirk, space is the final frontier. And it makes for some excellent sci'),
(2, 'Horror', 'Horror is a genre of speculative fiction that is intended to disturb, frighten, or scare an audience'),
(3, 'Crime', 'Crime fiction provides unique psychological impacts on readers and enables them to become mediated w'),
(4, 'Biography', 'Like autobiographies, biographies provide readers with a person’s life story; but they’re written in'),
(5, 'Poetry', 'This genre tells a story, often with characters, plot, and setting. Examples include epic poems, bal');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` float NOT NULL,
  `shipping_address` varchar(20) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `shipping_address`, `date`, `payment_status`, `payment_method`, `status`) VALUES
(1, 1, 240, 'Kathmandu,Jorpati', '2025-05-09 12:54:37.999903', 'pending', NULL, 'cancelled'),
(2, 1, 240, 'Kathmandu,Sitapaila', '2025-05-09 12:55:09.851777', 'pending', NULL, 'pending'),
(3, 2, 350, 'Boudha', '2025-05-10 11:45:50.285728', 'pending', NULL, 'pending'),
(4, 1, 355, 'Sankhu', '2025-05-10 13:11:27.266040', 'pending', NULL, 'processing'),
(5, 1, 280, 'Baneshwor', '2025-05-10 13:13:04.211478', 'pending', NULL, 'processing'),
(6, 2, 355, 'xcvbnm', '2025-05-12 18:43:10.706632', 'pending', NULL, 'pending'),
(7, 1, 280, 'Jorpati', '2025-05-12 18:46:24.151333', 'pending', NULL, 'pending'),
(8, 2, 300, 'Jorpati', '2025-07-01 15:29:19.469526', 'pending', NULL, 'pending'),
(9, 2, 7455, 'sj', '2025-07-02 09:19:41.189900', 'pending', NULL, 'delivered'),
(10, 2, 530, 'as', '2025-07-02 10:11:46.708231', 'pending', NULL, 'processing'),
(11, 2, 530, 'Jorpati', '2025-07-02 10:22:39.996656', 'pending', NULL, 'pending'),
(12, 2, 530, 'Subedigaun', '2025-07-02 10:37:31.182844', 'pending', NULL, 'processing'),
(13, 2, 530, 'ds', '2025-07-02 10:42:05.222460', 'completed', 'eSewa', 'shipped'),
(14, 2, 250, 'moi', '2025-07-02 10:42:39.694609', 'completed', 'eSewa', 'cancelled'),
(15, 2, 250, 'Subedigaun', '2025-07-02 11:07:32.384974', 'completed', 'eSewa', 'pending'),
(16, 2, 1000, 'wdfg', '2025-07-02 20:42:39.378363', 'completed', 'eSewa', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` float(10,2) NOT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `book_id`, `quantity`, `price`, `date`) VALUES
(1, 1, 6, 1, 240.00, '2025-05-09 12:54:38.003416'),
(2, 2, 6, 1, 240.00, '2025-05-09 12:55:09.854132'),
(3, 3, 14, 1, 350.00, '2025-05-10 11:45:50.300841'),
(4, 4, 15, 1, 355.00, '2025-05-10 13:11:27.317043'),
(5, 5, 7, 1, 280.00, '2025-05-10 13:13:04.216046'),
(6, 6, 15, 1, 355.00, '2025-05-12 18:43:10.738416'),
(7, 7, 4, 1, 280.00, '2025-05-12 18:46:24.157726'),
(8, 8, 5, 1, 300.00, '2025-07-01 15:29:19.503135'),
(9, 9, 15, 21, 355.00, '2025-07-02 09:19:41.195557'),
(10, 10, 11, 1, 250.00, '2025-07-02 10:11:46.749267'),
(11, 10, 4, 1, 280.00, '2025-07-02 10:11:46.771134'),
(12, 11, 11, 1, 250.00, '2025-07-02 10:22:40.000146'),
(13, 11, 4, 1, 280.00, '2025-07-02 10:22:40.003976'),
(14, 12, 11, 1, 250.00, '2025-07-02 10:37:31.188076'),
(15, 12, 4, 1, 280.00, '2025-07-02 10:37:31.192170'),
(16, 13, 11, 1, 250.00, '2025-07-02 10:42:05.224573'),
(17, 13, 4, 1, 280.00, '2025-07-02 10:42:05.228507'),
(18, 14, 11, 1, 250.00, '2025-07-02 10:42:39.699873'),
(19, 15, 11, 1, 250.00, '2025-07-02 11:07:32.395848'),
(20, 16, 11, 4, 250.00, '2025-07-02 20:42:39.383324');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(5) NOT NULL,
  `user_id` int(10) NOT NULL,
  `book_id` int(20) NOT NULL,
  `ratings` int(6) NOT NULL,
  `reviews` varchar(50) NOT NULL,
  `rated_on` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `user_id`, `book_id`, `ratings`, `reviews`, `rated_on`) VALUES
(1, 1, 13, 5, 'Great', '2025-05-09 15:34:42.000000'),
(2, 3, 13, 3, 'wow', '2025-05-09 15:39:15.000000'),
(3, 2, 14, 3, 'not bad', '2025-05-10 06:01:27.000000');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_status` varchar(20) NOT NULL DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `order_id`, `payment_method`, `amount`, `transaction_status`, `transaction_date`, `reference_id`) VALUES
(1, 13, 'eSewa', 530.00, 'completed', '2025-07-02 04:57:09', 'MOCK-6864bc254e2a4'),
(2, 14, 'eSewa', 250.00, 'completed', '2025-07-02 04:57:43', 'MOCK-6864bc4755fc7'),
(3, 15, 'eSewa', 250.00, 'completed', '2025-07-02 05:22:35', 'MOCK-6864c21b0652f'),
(4, 16, 'eSewa', 1000.00, 'completed', '2025-07-02 14:57:42', 'MOCK-686548e62323d');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'abc', 'abc@gmail.com', 'abc'),
(2, 'Bishal', 'ghartimagarbishal87@gmail.com', 'abc'),
(3, 'xyz', 'xyz@gmail.com', 'xyz'),
(9, 'jenish', 'jnishxrestha@gmail.com', '$2y$10$.UMHDwzL.fQM9qYqyJH4eOLPJo.Ob44FvgQHAxRtK065PPiW9DYz6'),
(10, 'user', 'user@gmail.com', '$2y$10$4gfTq8vx12pUY8FiNUmJ8u3Eig4AQcealwJHArn5R0BPwTwcyVkre'),
(11, 'Bishal', 'bishal@gmail.com', '$2y$10$Nv6Wvh0CYNhjO3U3ignvWO.RptQ1XdIgLePmDR3mDGDaqCfKld8/C');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`author_id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
