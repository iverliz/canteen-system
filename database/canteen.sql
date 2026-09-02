<<<<<<< HEAD
UPDATE orders SET status = 'preparing' WHERE id = ?; 


CREATE TABLE IF NOT EXISTS `users` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `username` varchar(50) NOT NULL,
   `student_id` varchar(50) NOT NULL,
   `password` varchar(255) NOT NULL,
   `role` varchar(20) NOT NULL DEFAULT 'student',
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `failed_attempts` int(11) NOT NULL DEFAULT 0,
   `locked_until` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `unique_student_id` (`student_id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `orders` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `user_id` int(11) NOT NULL,
   `total` decimal(10,2) NOT NULL,
   `status` enum('pending','preparing','ready','completed','cancelled') NOT NULL DEFAULT 'pending',
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),
   CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

 CREATE TABLE `order_items` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `order_id` int(11) NOT NULL,
   `food_name` varchar(100) NOT NULL,
   `price` decimal(10,2) NOT NULL,
   `quantity` int(11) NOT NULL,
   PRIMARY KEY (`id`),
   KEY `order_id` (`order_id`),
   CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

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
>>>>>>> 40f6623a477cc18e9e21d780255eabf34c3210f2
