-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:4306
-- :4306
-- Generation Time: Apr 20, 2025 at 04:19 PM
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
-- Database: `agrimarketsolutions`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `username`, `full_name`, `email`, `phone`, `address`, `customer_id`) VALUES
(3, 'hanisha', 'Hanisha Paramasivam', 'hanisha@gmail.com', '0163037020', 'No.22, Jalan Damai 1, Taman Damai, 42700 Banting, Selangor', 1),
(8, 'hani', 'Hani', 'testemail@example.com', '1234567890', '123 Street Name, City, Country', 8);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `order_details` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_datetime` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `customer_name`, `order_details`, `total_amount`, `payment_method`, `order_datetime`, `status`) VALUES
(31, 3, 'Hanisha Paramasivam', 'Chili Seeds (Local) x1, Spinach Seeds x2', 14.00, 'Bank Transfer', '2025-04-20 21:14:02', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `tracking_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT NULL,
  `packaging` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `quantity`, `image_path`, `vendor_id`, `created_at`, `product_image`, `category`, `stock_quantity`, `packaging`, `image_url`) VALUES
(10, 'Red Tomatoes', 'Fresh and juicy tomatoes.', 3.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'red_tomatoes.jpg'),
(11, 'Japanese Cucumbers', 'Crisp and refreshing.', 4.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'japanese_cucumber.jpg'),
(12, 'Sweet Corn', 'Sweet and tender corn.', 5.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'sweet_corn.jpg'),
(13, 'Fresh Spinach', 'Organic leafy greens.', 3.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'fresh_spinach.jpg'),
(14, 'Baby Carrots', 'Crunchy and sweet.', 4.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'baby_carrots.jpg'),
(15, 'Long Beans', 'Locally grown long beans.', 4.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'long_beans.jpg'),
(16, 'Bell Peppers (Mixed)', 'Red, green and yellow peppers.', 6.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'bell_peppers.jpg'),
(17, 'Eggplant (Brinjal)', 'Glossy and fresh brinjals.', 3.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'eggplant.jpg'),
(18, 'Bitter Gourd', 'Traditional healthy vegetable.', 4.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'bitter_gourd.jpg'),
(19, 'Lettuce (Butterhead)', 'Soft buttery leaves.', 3.80, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'lettuce.jpg'),
(20, 'Musang King Durian', 'Premium creamy durian.', 60.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 50, NULL, 'musang_king.jpg'),
(22, 'Thai Mangoes', 'Sweet imported mangoes.', 10.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'thai_mangoes.jpg'),
(23, 'Papaya (Red Lady)', 'Juicy red-flesh papaya.', 4.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'papaya.jpg'),
(24, 'Pineapple (MD2)', 'Sweet MD2 pineapples.', 5.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'pineapple.jpg'),
(25, 'Watermelon (Seedless)', 'Refreshing and seedless.', 7.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'watermelon.jpg'),
(26, 'Bananas (Berangan)', 'Ripe and sweet berangan.', 3.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'bananas.jpg'),
(27, 'Guava (White Flesh)', 'Crispy and refreshing.', 3.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'guava.jpg'),
(28, 'Dragon Fruit (Red)', 'Sweet red dragon fruit.', 6.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'dragon_fruit.jpg'),
(29, 'Passionfruit', 'Tropical and tangy fruit.', 5.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'passionfruit.jpg'),
(30, 'Lemongrass', 'Aromatic herb for cooking.', 2.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'lemongrass.jpg'),
(31, 'Thai Basil', 'Sweet and fragrant leaves.', 2.50, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'thai_basil.jpg'),
(32, 'Mint Leaves', 'Fresh and cooling.', 2.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'mint_leaves.jpg'),
(33, 'Curry Leaves', 'Essential for Malaysian dishes.', 1.80, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'curry_leaves.jpg'),
(34, 'Pandan Leaves', 'Natural fragrance for cooking.', 2.20, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'pandan_leaves.jpg'),
(35, 'Turmeric Root', 'Golden anti-inflammatory root.', 3.00, 10, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'turmeric_root.jpg'),
(36, 'Ginger (Old)', 'Strong flavor, old ginger.', 4.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'ginger.jpg'),
(37, 'Bird’s Eye Chili', 'Hot and spicy chili.', 2.50, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'bird_eye_chili.jpg'),
(38, 'Coriander Leaves', 'Fresh herb for garnishing.', 2.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'coriander_leaves.jpg'),
(39, 'Organic Compost (5kg)', 'Natural compost for soil health.', 10.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'organic_compost.jpg'),
(40, 'Chicken Manure (10kg)', 'Rich in nutrients.', 12.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'chicken_manure.jpg'),
(41, 'NPK Fertilizer', 'Balanced fertilizer mix.', 15.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'npk_fertilizer.jpg'),
(42, 'Vermicompost (Worm Castings)', 'Eco-friendly worm compost.', 14.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'vermicompost.jpg'),
(43, 'Biochar Soil Enhancer', 'Improves soil structure.', 18.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'biochar_soil_enhancer.jpg'),
(44, 'Seaweed Extract (Liquid)', 'Boosts plant immunity.', 20.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'seaweed_extract.jpeg'),
(45, 'Humic Acid Booster', 'Improves nutrient uptake.', 17.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'humic_acid_booster.jpg'),
(46, 'Fish Amino Acid Fertilizer', 'Organic growth stimulant.', 16.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'miscellaneous', 100, NULL, 'fish_amino_acid_fertilizer.jpg'),
(47, 'Cucumber Seeds (Hybrid)', 'Fast-growing hybrid cucumber.', 8.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'cucumber_seeds.jpg'),
(48, 'Sweet Corn Seeds', 'High-yield corn seeds.', 7.50, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'sweet_corn.jpg'),
(49, 'Durian D24', 'D24 durian from Malaysia.', 25.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'durian_sampling_d24.jpg'),
(50, 'Tomato Seeds (Roma)', 'Ideal for sauces.', 6.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'tomato_seeds.jpg'),
(51, 'Chili Seeds (Local)', 'Spicy local chili.', 5.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'chili_seeds.jpg'),
(52, 'Lettuce Seeds (Iceberg)', 'Crisp head lettuce.', 6.50, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'lettuce_seeds.jpg'),
(53, 'Papaya Sapling (Solo)', 'Solo papaya tree.', 20.00, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'edible_forestry', 100, NULL, 'papaya_sapling.jpg'),
(54, 'Long Bean Seeds', 'Grow your own long beans.', 5.50, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'long_bean_seeds.jpg'),
(55, 'Spinach Seeds', 'Green leafy veggie seeds.', 4.50, 0, NULL, 0, '2025-04-18 07:05:57', NULL, 'crops', 100, NULL, 'spinach_seeds.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_comparison`
--

CREATE TABLE `product_comparison` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff','vendor','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `address`, `password_hash`, `role`, `created_at`) VALUES
(3, 'hanisha', 'hanisha1310@gmail.com', '0163037020', 'No.22, Jalan Damai 1, Taman Damai, 42700 Banting,Selangor.', '$2y$10$917oPkinOFa49CaKZVBqNe/JySnI7flXtS9EVnekIemWNUgT2JTOq', 'customer', '2025-04-19 09:58:02'),
(4, 'customer_name', 'customer_email@example.com', '1234567890', 'Customer address', 'hashed_password', 'customer', '2025-04-20 06:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `subscription_tier` enum('basic','premium','enterprise') NOT NULL DEFAULT 'basic',
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `user_id`, `vendor_name`, `email`, `business_name`, `description`, `subscription_tier`, `contact`) VALUES
(5, 1, 'Ali', 'Ali123@gmail.com', 'Ali&Co', 'Selling Watermelon', 'basic', '0111234567'),
(12, 2, 'Abu', 'Abu123@gmail.com', 'Abu&Co', 'Selling Berries', 'basic', '01122233344');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`customer_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id_2` (`customer_id`),
  ADD UNIQUE KEY `customer_id_3` (`customer_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_ibfk_1` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`tracking_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_comparison`
--
ALTER TABLE `product_comparison`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `product_comparison`
--
ALTER TABLE `product_comparison`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
