CREATE DATABASE IF NOT EXISTS `canteen-system`;
USE `canteen-system`;

CREATE TABLE `admin_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `staff_id` varchar(50) NOT NULL,
  `role` enum('canteen_staff','manager') NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `food_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_picture` mediumblob DEFAULT NULL,
  `category_title` varchar(100) NOT NULL,
  `category_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `food_menu` (
  `food_id` int(11) NOT NULL AUTO_INCREMENT,
  `food_name` varchar(100) NOT NULL,
  `food_price` decimal(10,2) NOT NULL,
  `menu_food_category` varchar(100) NOT NULL,
  `food-description` varchar(200) DEFAULT NULL,
  `food_picture` mediumblob DEFAULT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;