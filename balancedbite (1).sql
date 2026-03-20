-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 17, 2025 at 02:07 PM
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
-- Database: `balancedbite`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_querytbl`
--

CREATE TABLE `contact_querytbl` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `issue` varchar(255) DEFAULT NULL,
  `concern` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_querytbl`
--

INSERT INTO `contact_querytbl` (`id`, `name`, `email`, `issue`, `concern`, `status`) VALUES
(1, 'radha', 'radha@gmail.com', 'Support', 'login nahi ho raha', 'Done'),
(2, 'radha', 'radha@gmail.com', 'Others', 'login nhi ho raha', NULL),
(3, 'Dev', 'dev35@gmail.com', 'Feedback', 'such good website !!!', 'Done'),
(4, 'Riddhi', 'riddhisaspara2712@gmail.com', 'General Inquiry', 'Give me Diet plan Suggestion.?', 'Done'),
(5, 'rajal', 'rajal@gmail.com', 'General Inquiry', 'login problem', 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `diet_plan`
--

CREATE TABLE `diet_plan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meal_type` enum('Breakfast','Lunch','Dinner','Snack') NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plan`
--

INSERT INTO `diet_plan` (`id`, `user_id`, `meal_type`, `recipe_id`, `created_at`) VALUES
(33, 12, 'Dinner', 2, '2025-09-21 12:05:00'),
(35, 12, 'Dinner', 25, '2025-09-22 06:07:03'),
(36, 12, 'Lunch', 26, '2025-09-22 06:07:14'),
(37, 12, 'Breakfast', 25, '2025-09-22 06:07:31'),
(38, 12, 'Lunch', 22, '2025-10-03 06:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `discount_coupons`
--

CREATE TABLE `discount_coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_percent` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` enum('active','expired') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount_coupons`
--

INSERT INTO `discount_coupons` (`id`, `code`, `description`, `discount_percent`, `expiry_date`, `status`, `created_at`) VALUES
(1, 'WELCOME10', 'fbd', 10, '2025-12-31', 'active', '2025-11-07 13:50:40'),
(2, 'HEALTH5', NULL, 5, '2025-11-30', 'active', '2025-11-07 13:50:40'),
(3, 'FESTIVE20', NULL, 20, '2025-10-31', 'active', '2025-11-07 13:50:40'),
(4, 'NEWUSER15', NULL, 15, '2025-12-31', 'active', '2025-11-07 13:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'General', 'What is BalancedBite?', 'BalancedBite is a meal planning platform to help you eat healthy.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(2, 'Nutrition', 'How often should I eat?', 'It is recommended to eat 3 main meals and 2 snacks per day.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(3, 'Diet', 'Can I follow a vegan plan?', 'Yes, we provide personalized vegan meal plans.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(4, 'General', 'Is there a mobile app?', 'Currently we are web-based, mobile app is coming soon.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(5, 'Nutrition', 'Do you provide calorie information?', 'Yes, each meal includes calories and macros.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(6, 'Diet', 'Can I customize my plan?', 'Absolutely, you can select preferences and restrictions.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(7, 'General', 'How to contact support?', 'You can reach us via the Contact Us form or email.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(8, 'Nutrition', 'Are the plans dietitian approved?', 'Yes, all plans are reviewed by certified dietitians.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(9, 'Diet', 'Do you provide recipes?', 'Yes, each meal comes with a detailed recipe.', '2025-11-06 06:42:28', '2025-11-06 06:42:28'),
(10, 'General', 'Can I cancel anytime?', 'Yes, you can cancel your subscription anytime.', '2025-11-06 06:42:28', '2025-11-06 06:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `meal_plan`
--

CREATE TABLE `meal_plan` (
  `id` int(11) NOT NULL,
  `day_of_week` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') DEFAULT NULL,
  `meal_type` enum('Breakfast','Lunch','Dinner','Snack') DEFAULT NULL,
  `recipe_id` int(11) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `week_number` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_plan`
--

INSERT INTO `meal_plan` (`id`, `day_of_week`, `meal_type`, `recipe_id`, `month`, `week_number`, `image_url`) VALUES
(1, 'Monday', 'Breakfast', 40, NULL, NULL, NULL),
(2, 'Monday', 'Breakfast', 9, NULL, NULL, NULL),
(3, 'Monday', 'Lunch', 30, NULL, NULL, NULL),
(4, 'Monday', 'Lunch', 48, NULL, NULL, NULL),
(5, 'Monday', 'Dinner', 38, NULL, NULL, NULL),
(6, 'Monday', 'Dinner', 44, NULL, NULL, NULL),
(7, 'Monday', 'Snack', 36, NULL, NULL, NULL),
(8, 'Monday', 'Snack', 26, NULL, NULL, NULL),
(9, 'Tuesday', 'Breakfast', 32, NULL, NULL, NULL),
(10, 'Tuesday', 'Breakfast', 11, NULL, NULL, NULL),
(11, 'Tuesday', 'Lunch', 8, NULL, NULL, NULL),
(12, 'Tuesday', 'Lunch', 10, NULL, NULL, NULL),
(13, 'Tuesday', 'Dinner', 5, NULL, NULL, NULL),
(14, 'Tuesday', 'Dinner', 27, NULL, NULL, NULL),
(15, 'Tuesday', 'Snack', 45, NULL, NULL, NULL),
(16, 'Tuesday', 'Snack', 52, NULL, NULL, NULL),
(17, 'Wednesday', 'Breakfast', 7, NULL, NULL, NULL),
(18, 'Wednesday', 'Breakfast', 51, NULL, NULL, NULL),
(19, 'Wednesday', 'Lunch', 2, NULL, NULL, NULL),
(20, 'Wednesday', 'Lunch', 33, NULL, NULL, NULL),
(21, 'Wednesday', 'Dinner', 17, NULL, NULL, NULL),
(22, 'Wednesday', 'Dinner', 19, NULL, NULL, NULL),
(23, 'Wednesday', 'Snack', 41, NULL, NULL, NULL),
(24, 'Wednesday', 'Snack', 46, NULL, NULL, NULL),
(25, 'Thursday', 'Breakfast', 49, NULL, NULL, NULL),
(26, 'Thursday', 'Breakfast', 13, NULL, NULL, NULL),
(27, 'Thursday', 'Lunch', 24, NULL, NULL, NULL),
(28, 'Thursday', 'Lunch', 29, NULL, NULL, NULL),
(29, 'Thursday', 'Dinner', 50, NULL, NULL, NULL),
(30, 'Thursday', 'Dinner', 14, NULL, NULL, NULL),
(31, 'Thursday', 'Snack', 34, NULL, NULL, NULL),
(32, 'Thursday', 'Snack', 1, NULL, NULL, NULL),
(33, 'Friday', 'Breakfast', 47, NULL, NULL, NULL),
(34, 'Friday', 'Breakfast', 42, NULL, NULL, NULL),
(35, 'Friday', 'Lunch', 22, NULL, NULL, NULL),
(36, 'Friday', 'Lunch', 12, NULL, NULL, NULL),
(37, 'Friday', 'Dinner', 35, NULL, NULL, NULL),
(38, 'Friday', 'Dinner', 15, NULL, NULL, NULL),
(39, 'Friday', 'Snack', 28, NULL, NULL, NULL),
(40, 'Friday', 'Snack', 20, NULL, NULL, NULL),
(41, 'Saturday', 'Breakfast', 53, NULL, NULL, NULL),
(42, 'Saturday', 'Breakfast', 31, NULL, NULL, NULL),
(43, 'Saturday', 'Lunch', 39, NULL, NULL, NULL),
(44, 'Saturday', 'Lunch', 3, NULL, NULL, NULL),
(45, 'Saturday', 'Dinner', 4, NULL, NULL, NULL),
(46, 'Saturday', 'Dinner', 21, NULL, NULL, NULL),
(47, 'Saturday', 'Snack', 16, NULL, NULL, NULL),
(48, 'Saturday', 'Snack', 37, NULL, NULL, NULL),
(49, 'Sunday', 'Breakfast', 23, NULL, NULL, NULL),
(50, 'Sunday', 'Breakfast', 18, NULL, NULL, NULL),
(51, 'Sunday', 'Lunch', 43, NULL, NULL, NULL),
(52, 'Sunday', 'Lunch', 6, NULL, NULL, NULL),
(53, 'Sunday', 'Dinner', 25, NULL, NULL, NULL),
(54, 'Sunday', 'Dinner', 40, NULL, NULL, NULL),
(55, 'Sunday', 'Snack', 9, NULL, NULL, NULL),
(56, 'Sunday', 'Snack', 30, NULL, NULL, NULL),
(57, 'Friday', 'Breakfast', 4, '7', '1', 'images/meal_plan/1762410787_about-heading.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','success','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `amount`, `discount`, `final_amount`, `payment_status`, `payment_method`, `transaction_id`, `created_at`) VALUES
(12, 5, 999.00, 99.90, 899.10, 'success', 'paypal', 'TXN17625217846199', '2025-11-07 13:23:04'),
(13, 19, 999.00, 99.90, 899.10, 'success', 'googlepay', 'TXN17630996385336', '2025-11-14 05:53:58'),
(14, 22, 0.00, 0.00, 0.00, 'success', 'paypal', 'TXN17632973121562', '2025-11-16 12:48:32'),
(15, 23, 999.00, 99.90, 899.10, 'success', 'googlepay', 'TXN17632974775217', '2025-11-16 12:51:17'),
(16, 23, 999.00, 99.90, 899.10, 'success', 'googlepay', 'TXN17632975353233', '2025-11-16 12:52:15'),
(17, 23, 999.00, 99.90, 899.10, 'success', 'googlepay', 'TXN17632975475572', '2025-11-16 12:52:27'),
(18, 24, 4499.00, 899.80, 3599.20, 'success', 'googlepay', 'TXN17632990299014', '2025-11-16 13:17:09'),
(19, 26, 4499.00, 899.80, 3599.20, 'success', 'googlepay', 'TXN17633605432298', '2025-11-17 06:22:23'),
(20, 28, 2499.00, 374.85, 2124.15, 'success', 'googlepay', 'TXN17633608912311', '2025-11-17 06:28:11'),
(21, 31, 4499.00, 899.80, 3599.20, 'success', 'paypal', 'TXN17633646344027', '2025-11-17 07:30:34'),
(22, 37, 2499.00, 374.85, 2124.15, 'success', 'paypal', 'TXN17633803789957', '2025-11-17 11:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `carbs` int(11) DEFAULT NULL,
  `fats` int(11) DEFAULT NULL,
  `proteins` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `meal` varchar(20) DEFAULT NULL,
  `ingredients` text NOT NULL,
  `directions` text DEFAULT NULL,
  `foodcat` varchar(50) DEFAULT NULL,
  `prep_time` varchar(50) DEFAULT NULL,
  `cook_time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `calories`, `carbs`, `fats`, `proteins`, `image`, `meal`, `ingredients`, `directions`, `foodcat`, `prep_time`, `cook_time`) VALUES
(1, 'Berry Salad', 120, 28, 1, 2, 'berry_salad.jpg', 'Snack', 'Mixed berries (strawberries, blueberries, raspberries), mint leaves, lemon juice, honey', 'Step 1: Wash berries. Step 2: Mix with lemon juice and honey. Step 3: Garnish with mint.', 'Salad', '10 mins', '0 mins'),
(2, 'Veggie Sticks with Dip', 150, 20, 8, 3, 'veggie_sticks_dip.jpg', 'Snack', 'Carrots, cucumber, bell peppers, hummus or yogurt dip', 'Step 1: Cut veggies into sticks. Step 2: Serve with dip.', 'Snack', '5 mins', '0 mins'),
(3, 'Tomato Cucumber Salad', 100, 18, 3, 2, 'tomato_cucumber_salad.jpg', 'Lunch', 'Tomatoes, cucumber, onion, olive oil, lemon juice, salt, pepper', 'Step 1: Chop tomatoes, cucumber, onion. Step 2: Toss with olive oil, lemon juice, and seasoning.', 'Salad', '10 mins', '0 mins'),
(4, 'Roasted Carrot Soup', 180, 22, 6, 4, 'roasted_carrot_soup.jpg', 'Dinner', 'Carrots, onion, garlic, vegetable broth, olive oil, salt, pepper', 'Step 1: Roast carrots with olive oil. Step 2: Blend with broth and spices. Step 3: Simmer and serve warm.', 'Soup', '15 mins', '20 mins'),
(5, 'Mixed Green Salad', 90, 10, 4, 3, 'mixed_green_salad.jpg', 'Lunch', 'Lettuce, spinach, cucumber, olive oil, lemon juice', 'Step 1: Wash and chop greens. Step 2: Toss with dressing.', 'Salad', '8 mins', '0 mins'),
(6, 'Chickpea Salad', 220, 28, 7, 9, 'chickpea_salad.jpg', 'Lunch', 'Boiled chickpeas, cucumber, onion, tomato, olive oil, lemon juice', 'Step 1: Mix chickpeas with chopped veggies. Step 2: Season and serve.', 'Salad', '12 mins', '0 mins'),
(7, 'Spinach Smoothie', 160, 30, 3, 5, 'spinach_smoothie.jpg', 'Breakfast', 'Spinach, banana, apple, yogurt, honey, water', 'Step 1: Add all ingredients to blender. Step 2: Blend until smooth.', 'Smoothie', '5 mins', '2 mins'),
(8, 'Cucumber and Hummus Bites', 130, 15, 7, 4, 'cucumber_hummus_bites.jpg', 'Snack', 'Cucumber slices, hummus, paprika', 'Step 1: Slice cucumber. Step 2: Top with hummus and sprinkle paprika.', 'Snack', '5 mins', '0 mins'),
(9, 'Strawberry Spinach Salad', 140, 20, 6, 4, 'strawberry_spinach_salad.jpg', 'Lunch', 'Spinach, strawberries, walnuts, olive oil, balsamic vinegar', 'Step 1: Mix spinach and strawberries. Step 2: Top with walnuts and dressing.', 'Salad', '10 mins', '0 mins'),
(10, 'Stuffed Bell Peppers', 250, 32, 10, 8, 'stuffed_bell_peppers.jpg', 'Dinner', 'Bell peppers, quinoa/rice, onion, tomato, spices, cheese (optional)', 'Step 1: Cut peppers and remove seeds. Step 2: Stuff with cooked mixture. Step 3: Bake until tender.', 'Main Course', '20 mins', '25 mins'),
(11, 'Roasted Chickpeas', 180, 30, 5, 8, 'roasted_chickpeas.jpg', 'Snack', 'Boiled chickpeas, olive oil, paprika, salt, pepper', 'Step 1: Toss chickpeas with oil and spices. Step 2: Roast until crispy.', 'Snack', '5 mins', '20 mins'),
(12, 'Greek Yogurt and Honey', 160, 22, 4, 10, 'greek_yogurt_honey.jpg', 'Breakfast', 'Greek yogurt, honey, walnuts (optional)', 'Step 1: Add yogurt to bowl. Step 2: Drizzle honey and add toppings.', 'Dessert', '3 mins', '0 mins'),
(13, 'Stuffed Mushrooms', 200, 15, 9, 7, 'stuffed_mushrooms.jpg', 'Snack', 'Mushrooms, breadcrumbs, garlic, onion, olive oil, cheese (optional)', 'Step 1: Remove stems. Step 2: Stuff with mixture. Step 3: Bake until golden.', 'Appetizer', '10 mins', '15 mins'),
(14, 'Vegetable Soup', 120, 20, 3, 4, 'vegetable_soup.jpg', 'Dinner', 'Mixed vegetables (carrots, beans, peas, corn), onion, garlic, vegetable broth', 'Step 1: Boil veggies with broth. Step 2: Season and simmer.', 'Soup', '10 mins', '20 mins'),
(15, 'Spinach and Mushroom Quiche', 280, 20, 18, 12, 'spinach_mushroom_quiche.jpg', 'Breakfast', 'Spinach, mushrooms, eggs, milk, cheese, pastry crust', 'Step 1: Cook spinach and mushrooms. Step 2: Mix with eggs and cheese. Step 3: Bake in crust.', 'Main Course', '15 mins', '25 mins'),
(16, 'Roasted Beet Salad', 170, 22, 7, 5, 'roasted_beet_salad.jpg', 'Lunch', 'Beets, arugula, olive oil, lemon juice, feta cheese (optional)', 'Step 1: Roast beets until tender. Step 2: Slice and toss with greens and dressing.', 'Salad', '12 mins', '20 mins'),
(17, 'Sweet Potato Chips', 200, 28, 8, 3, 'sweet_potato_chips.jpg', 'Snack', 'Sweet potatoes, olive oil, salt, paprika', 'Step 1: Slice thinly. Step 2: Bake with oil and spices until crispy.', 'Snack', '8 mins', '20 mins'),
(18, 'Vegan Tacos', 300, 40, 12, 10, 'vegan_tacos.jpg', 'Dinner', 'Taco shells, beans, corn, onion, tomato, lettuce, avocado', 'Step 1: Prepare filling with beans and veggies. Step 2: Fill taco shells and serve.', 'Main Course', '15 mins', '10 mins'),
(19, 'Stuffed Zucchini Boats', 220, 18, 9, 7, 'stuffed_zucchini_boats.jpg', 'Dinner', 'Zucchini, quinoa/rice, onion, tomato, spices, cheese (optional)', 'Step 1: Cut zucchini lengthwise. Step 2: Scoop and stuff with mixture. Step 3: Bake until golden.', 'Main Course', '12 mins', '20 mins'),
(20, 'Brussels Sprouts Salad', 150, 16, 7, 5, 'brussels_sprouts_salad.jpg', 'Lunch', 'Shredded Brussels sprouts, olive oil, lemon juice, walnuts, parmesan', 'Step 1: Shred sprouts. Step 2: Toss with dressing and toppings.', 'Salad', '10 mins', '0 mins'),
(21, 'Avocado and Tomato Toast', 210, 24, 12, 6, 'avocado_tomato_toast.jpg', 'Breakfast', 'Whole grain bread, avocado, tomato, olive oil, salt, pepper', 'Step 1: Toast bread. Step 2: Mash avocado and spread. Step 3: Top with tomato slices and seasoning.', 'Breakfast', '5 mins', '5 mins'),
(22, 'Vegetarian Chili', 280, 35, 8, 12, 'vegetarian_chili.jpg', 'Dinner', 'Kidney beans, chickpeas, tomato, onion, garlic, bell peppers, chili spices', 'Step 1: Sauté onion and garlic. Step 2: Add beans, tomatoes, and spices. Step 3: Simmer until thick.', 'Main Course', '15 mins', '25 mins'),
(23, 'Pear and Walnut Salad', 160, 20, 9, 4, 'pear_walnut_salad.jpg', 'Lunch', 'Pears, walnuts, mixed greens, olive oil, balsamic vinegar', 'Step 1: Slice pears. Step 2: Toss with greens, walnuts, and dressing.', 'Salad', '10 mins', '0 mins'),
(24, 'Tofu Scramble', 220, 12, 14, 15, 'tofu_scramble.jpg', 'Breakfast', 'Tofu, turmeric, onion, tomato, spinach, olive oil, salt, pepper', 'Step 1: Crumble tofu. Step 2: Cook with veggies and spices until golden.', 'Breakfast', '8 mins', '10 mins'),
(25, 'Greek Salad', 180, 15, 10, 6, 'greek_salad.jpg', 'Lunch', 'Tomatoes, cucumber, onion, olives, feta cheese, olive oil, oregano', 'Step 1: Chop vegetables. Step 2: Add feta and olives. Step 3: Toss with olive oil and oregano.', 'Salad', '10 mins', '0 mins'),
(26, 'Vegetable Samosa', 250, 28, 12, 6, 'vegetable_samosa.jpg', 'Snack', 'Potatoes, peas, onion, spices, wheat flour, oil', 'Step 1: Prepare spiced potato-pea filling. Step 2: Fill dough cones. Step 3: Deep fry until golden.', 'Snack', '20 mins', '15 mins'),
(27, 'Roasted Red Pepper Hummus', 180, 20, 9, 7, 'roasted_red_pepper_hummus.jpg', 'Snack', 'Chickpeas, roasted red pepper, tahini, lemon juice, garlic, olive oil', 'Step 1: Blend all ingredients until smooth. Step 2: Chill and serve with veggies or pita.', 'Dip', '10 mins', '0 mins'),
(28, 'Mango Smoothie', 200, 40, 3, 5, 'mango_smoothie.jpg', 'Breakfast', 'Mango, yogurt, milk, honey', 'Step 1: Add all ingredients to blender. Step 2: Blend until smooth.', 'Smoothie', '5 mins', '2 mins'),
(29, 'Baked Falafel', 240, 26, 10, 9, 'baked_falafel.jpg', 'Dinner', 'Chickpeas, onion, garlic, parsley, cumin, olive oil', 'Step 1: Blend ingredients. Step 2: Form patties. Step 3: Bake until golden brown.', 'Main Course', '10 mins', '20 mins'),
(30, 'Quinoa Salad', 210, 28, 7, 8, 'quinoa_salad.jpg', 'Lunch', 'Cooked quinoa, cucumber, tomato, onion, olive oil, lemon juice', 'Step 1: Cook quinoa. Step 2: Mix with veggies and dressing. Step 3: Serve chilled.', 'Salad', '12 mins', '5 mins'),
(31, 'Vegetable Stir-Fry with Tofu', 260, 30, 12, 14, 'veg_stir_fry_tofu.jpg', 'Dinner', 'Tofu, broccoli, bell peppers, carrots, soy sauce, garlic, sesame oil', 'Step 1: Stir-fry veggies in sesame oil. Step 2: Add tofu and soy sauce. Step 3: Cook until tender-crisp.', 'Main Course', '15 mins', '12 mins'),
(32, 'Lentil Soup', 190, 28, 4, 10, 'lentil_soup.jpg', 'Dinner', 'Lentils, onion, garlic, tomato, carrots, vegetable broth, cumin', 'Step 1: Sauté onion and garlic. Step 2: Add lentils, broth, and veggies. Step 3: Simmer until soft.', 'Soup', '10 mins', '20 mins'),
(33, 'Greek Yogurt and Granola', 220, 30, 6, 10, 'greek_yogurt_granola.jpg', 'Breakfast', 'Greek yogurt, granola, honey, fresh fruits', 'Step 1: Add yogurt to bowl. Step 2: Top with granola and fruits. Step 3: Drizzle honey.', 'Breakfast', '5 mins', '0 mins'),
(34, 'Chickpea and Spinach Curry', 277, 32, 9, 11, 'chickpea_spinach_curry.jpg', 'Dinner', 'Chickpeas, spinach, onion, tomato, garlic, ginger, curry spices', 'Step 1: Cook onion, garlic, ginger with spices. Step 2: Add tomatoes and chickpeas. Step 3: Stir in spinach.', 'Main Course', '15 mins', '18 mins'),
(35, 'Tomato and Basil Pasta', 320, 55, 9, 12, 'tomato_basil_pasta.jpg', 'Lunch', 'Pasta, tomato, garlic, basil, olive oil, parmesan (optional)', 'Step 1: Cook pasta. Step 2: Make sauce with tomato, garlic, and basil. Step 3: Mix pasta with sauce.', 'Main Course', '12 mins', '15 mins'),
(36, 'Mushroom Stir-Fry', 200, 22, 8, 9, 'mushroom_stir_fry.jpg', 'Dinner', 'Mushrooms, onion, bell peppers, soy sauce, garlic, sesame oil', 'Step 1: Heat oil and sauté garlic. Step 2: Add mushrooms and veggies. Step 3: Stir-fry with soy sauce.', 'Main Course', '10 mins', '10 mins'),
(37, 'Chickpea Curry', 270, 34, 8, 10, 'chickpea_curry.jpg', 'Dinner', 'Chickpeas, onion, tomato, garlic, ginger, curry spices', 'Step 1: Sauté onion, garlic, ginger. Step 2: Add tomatoes and spices. Step 3: Add chickpeas and simmer.', 'Main Course', '12 mins', '15 mins'),
(38, 'Quinoa Salad', 210, 28, 7, 8, 'quinoa_salad.jpg', 'Lunch', 'Cooked quinoa, cucumber, tomato, onion, olive oil, lemon juice', 'Step 1: Cook quinoa. Step 2: Mix with veggies and dressing. Step 3: Serve chilled.', 'Salad', '12 mins', '5 mins'),
(39, 'Broccoli Soup', 160, 18, 6, 7, 'broccoli_soup.jpg', 'Dinner', 'Broccoli, onion, garlic, vegetable broth, olive oil, pepper', 'Step 1: Cook broccoli and onion in broth. Step 2: Blend until smooth. Step 3: Season and serve.', 'Soup', '10 mins', '15 mins'),
(40, 'Spinach and Feta Wrap', 250, 32, 10, 11, 'spinach_feta_wrap.jpg', 'Lunch', 'Whole wheat wrap, spinach, feta cheese, tomato, cucumber, hummus', 'Step 1: Spread hummus on wrap. Step 2: Add spinach, feta, and veggies. Step 3: Roll and serve.', 'Main Course', '8 mins', '5 mins'),
(41, 'Sweet Potato and Lentil Stew', 310, 48, 9, 14, 'sweet_potato_lentil_stew.jpg', 'Dinner', 'Sweet potatoes, lentils, onion, garlic, tomato, vegetable broth, spices', 'Step 1: Sauté onion and garlic. Step 2: Add sweet potato, lentils, broth, and spices. Step 3: Simmer until thick.', 'Main Course', '12 mins', '25 mins'),
(42, 'Greek Yogurt Parfait', 200, 32, 6, 9, 'greek_yogurt_parfait.jpg', 'Breakfast', 'Greek yogurt, granola, mixed berries, honey', 'Step 1: Layer yogurt, berries, and granola in a glass. Step 2: Repeat layers and drizzle honey.', 'Breakfast', '5 mins', '0 mins'),
(43, 'Stuffed Bell Peppers', 250, 32, 10, 8, 'stuffed_bell_peppers.jpg', 'Dinner', 'Bell peppers, rice/quinoa, onion, tomato, spices, cheese (optional)', 'Step 1: Cut peppers and remove seeds. Step 2: Stuff with filling. Step 3: Bake until golden.', 'Main Course', '15 mins', '25 mins'),
(44, 'Vegetable Stir-Fry', 220, 28, 9, 7, 'vegetable_stir_fry.jpg', 'Lunch', 'Mixed vegetables (broccoli, carrots, beans, bell peppers), soy sauce, garlic, olive oil', 'Step 1: Heat oil and sauté garlic. Step 2: Add vegetables and stir-fry. Step 3: Season and serve.', 'Main Course', '10 mins', '10 mins'),
(45, 'Vegetable Pasta Primavera', 3060, 48, 9, 11, 'veg_pasta_primavera.jpg', 'Lunch', 'Pasta, zucchini, bell peppers, broccoli, olive oil, garlic, parmesan (optional)', 'Step 1: Cook pasta. Step 2: Sauté veggies with olive oil and garlic. Step 3: Mix with pasta.', 'Main Course', '15 mins', '15 mins'),
(46, 'Chickpea and Vegetable Stew', 290, 36, 10, 13, 'chickpea_veg_stew.jpg', 'Dinner', 'Chickpeas, carrots, peas, onion, tomato, vegetable broth, spices', 'Step 1: Cook onion and garlic. Step 2: Add chickpeas, veggies, and broth. Step 3: Simmer until thick.', 'Main Course', '12 mins', '20 mins'),
(47, 'Sweet Potato and Black Bean Tacos', 320, 42, 11, 12, 'sweet_potato_black_bean_tacos.jpg', 'Dinner', 'Taco shells, sweet potatoes, black beans, onion, lettuce, avocado', 'Step 1: Roast sweet potato cubes. Step 2: Mix with black beans. Step 3: Fill taco shells and serve.', 'Main Course', '10 mins', '15 mins'),
(48, 'Quinoa and Roasted Vegetable Bowl', 280, 38, 11, 10, 'quinoa_roasted_veg_bowl.jpg', 'Lunch', 'Cooked quinoa, zucchini, carrots, bell peppers, olive oil, lemon juice', 'Step 1: Roast vegetables. Step 2: Mix with quinoa. Step 3: Drizzle lemon juice and serve.', 'Bowl', '15 mins', '20 mins'),
(49, 'Vegetable Stir-Fry with Peanut Sauce', 310, 35, 14, 12, 'veg_stir_fry_peanut_sauce.jpg', 'Dinner', 'Mixed vegetables, peanut butter, soy sauce, garlic, ginger, sesame oil', 'Step 1: Stir-fry vegetables. Step 2: Add peanut sauce mixture. Step 3: Toss and serve hot.', 'Main Course', '12 mins', '15 mins'),
(50, 'Lentil and Spinach Curry with Rice', 350, 52, 11, 15, 'lentil_spinach_curry_rice.jpg', 'Dinner', 'Lentils, spinach, onion, tomato, garlic, ginger, curry spices, rice', 'Step 1: Cook lentils with spices. Step 2: Stir in spinach. Step 3: Serve with steamed rice.', 'Main Course', '15 mins', '25 mins'),
(51, 'Quinoa and Veggie Salad', 230, 34, 8, 9, 'quinoa_veggie_salad.jpg', 'Lunch', 'Cooked quinoa, cucumber, tomato, bell peppers, olive oil, lemon juice', 'Step 1: Cook quinoa. Step 2: Mix with chopped veggies. Step 3: Toss with dressing.', 'Salad', '10 mins', '10 mins'),
(52, 'Lentil and Vegetable Stew', 300, 40, 9, 14, 'lentil_veg_stew.jpg', 'Dinner', 'Lentils, carrots, peas, onion, tomato, vegetable broth, spices', 'Step 1: Cook onion and garlic. Step 2: Add lentils, veggies, and broth. Step 3: Simmer until thick.', 'Main Course', '15 mins', '25 mins'),
(53, 'Greek Yogurt with Fruit and Nuts', 210, 26, 9, 12, 'greek_yogurt_fruit_nuts.jpg', 'Breakfast', 'Greek yogurt, mixed fruits, almonds, walnuts, honey', 'Step 1: Add yogurt to bowl. Step 2: Top with fruits and nuts. Step 3: Drizzle honey.', 'Breakfast', '5 mins', '0 mins');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `plan` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `email`, `plan`, `created_at`) VALUES
(1, 'kathiriyaforam6@gmail.com', 'basic', '2025-01-10 12:51:35'),
(2, 'kathiriyaforam2005@gamil.com', 'premium', '2025-01-10 12:53:24'),
(3, 'vidhi12@gmail.com', 'premium', '2025-10-11 05:28:06'),
(4, 'riddhisaspara2712@gmail.com', 'basic', '2025-10-11 06:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `phno` varchar(10) DEFAULT NULL,
  `profile_image` varchar(70) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_premium` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `phno`, `profile_image`, `created_at`, `is_premium`) VALUES
(11, 'riddhi', '', '$2y$10$63Z2Dm6SxNyjhDM/mV48Su6uALz45tJJaamvwXmt3b6NGBpaUlZGa', 'user', '7990417197', '', '2025-08-19 09:47:45', 0),
(12, 'vidhisha', '', '$2y$10$gjaFFIqAzi6QcX6SuV/kGO9w2Qg79Ru68gRycPWh.mTNaHeeX6MJ2', 'user', '8758165227', 'images/uploads/profile_12_1762933712.jpg', '2025-11-12 07:48:32', 0),
(13, 'mina', '', '$2y$10$X7i2YNF4woE.8y6NrTM.x.Ve.vYyU9xd8YhFLRgrE3LVY..0.HPra', 'user', '9979723064', '', '2025-08-19 09:47:45', 0),
(20, 'admin', 'admin@example.com', '$2y$10$WfTcvFtabrGqFxBgyA2DR.ukDkTtOPw/fBSSGgeexOWsgQrrPs.UO', 'admin', NULL, '', '2025-08-29 14:13:03', 0),
(23, 'Rajal', '', '$2y$10$4mr3kEvdflNMliaHco3CMu9gHrA.xV7KkMPaRh7rmeelTjJ.iAJ9i', 'user', '9876543211', '', '2025-09-01 13:30:26', 0),
(25, 'Dhruti', '', '$2y$10$Vo7jYYr/eKBU4Z10wJukhuDDOIwJ8/fcMktP1WX/By4D2NPFGNmDW', 'user', '7990417197', '', '2025-09-21 13:08:53', 0),
(26, 'keyur', '', '$2y$10$Gwf0xEv7dhG3zTIP1iuOqeld0O2HWZtt0y.WLBl8Uze0XVLsW3qHS', 'user', '9512511087', '', '2025-10-13 05:24:00', 0),
(27, 'ram', '', '$2y$10$QZYCpPuYN91m8gXp/lqbseIy/7bJHywvCpF9zLLwDlwkxURtOg.qy', 'user', '4578908767', '', '2025-11-06 11:52:00', 0),
(28, 'siya', '', '$2y$10$EsgTmyfB2NzUPoeDtgwzLOLPlMtSy0dVl3PY3taFCEeKga1cOEWWe', 'user', '3456780088', '', '2025-11-06 12:22:25', 0),
(29, 'dada', '', '$2y$10$mvsJF//p1H4jepQhG0U/Xuu1owYHub1JyLnUtmKuvvhU6lukp5v7G', 'user', '7990417197', '', '2025-11-06 12:30:51', 0),
(30, 'raju', '', '$2y$10$YWd.Pngg5BvSKvr8fb4cFO2P3Ck.89g2fDLMwrf3MxEGA/qjJWqdu', 'user', '6789834567', '', '2025-11-06 12:40:59', 0),
(31, 'chintu', '', '$2y$10$mkXtvc/vXTAoXQwplLI1dOYNbcYDEh/f0KuperdBDsXzD1YDVt3dy', 'user', '8758165227', '', '2025-11-06 13:53:27', 0),
(32, 'mansi', '', '$2y$10$cKo8yxy7PmRkPMRF7nnOkOVhLFvqmaOdKjgIoiDrv2kzwupEktW0G', 'user', '9512511087', '', '2025-11-06 14:09:01', 0),
(33, 'dev', '', '$2y$10$MZMd6wFdViP8BSIeuKbOX.eFcC6Y1OftlrXYV9N69QaoygFZ9hFNW', 'user', '9979723064', '', '2025-11-06 14:21:30', 0),
(34, 'devnsi', '', '$2y$10$rF7.76CsbdpKu5rvRN6bw.FCMCbMZTd7jgA4.cER2pgIp0ljZ2TF2', 'user', '9979723064', '', '2025-11-06 14:36:42', 0),
(35, 'hemali', '', '$2y$10$0HvLQaTc1Za/MXGPCCZ8XuMzt4xwqCpsNHE.5K9Z2enN2bpNXcAky', 'user', '7990417197', '', '2025-11-06 14:50:01', 0),
(36, 'Admin35', '', '$2y$10$Yj3F24v5O5meLHCbm4nKN.lpphIsf7Nk9MQh0ig8dpTBBoJMyOMsO', 'admin', NULL, '', '2025-11-07 05:44:34', 0),
(37, 'Nensi', '', '$2y$10$wKOqga9JCKhFOfb4BtdRC.t8WK.L0OkiWizeMZupl6IrgIa028/TC', 'user', '8141303811', '', '2025-11-14 05:24:40', 0),
(38, 'asmita', '', '$2y$10$K2FN65hQ/wqjpLwhbEguheZ0seawxj1gRFSOc3joQAkHYQoRkvrIK', 'user', '7990417197', '', '2025-11-14 05:29:33', 0),
(39, 'nayna', '', '$2y$10$gd3sTR30qHXM9AFSsGKgs.kI1GrUYtjaNAk19ivX1FefOneB8.FQu', 'user', '8758165227', '', '2025-11-14 05:34:40', 0),
(40, 'ttt', '', '$2y$10$qXKOrewAkupXJ2YhkW6/ju8H/SA95u6ZuKy5MDUa2iItdEToBixL2', 'user', '1234567890', '', '2025-11-14 05:36:32', 0),
(41, 'mirali', '', '$2y$10$9TdjI.7g42Uauz9VbNg9beK7dvQkkxH6qKNhC9Be.MRNj6Dpkt6PW', 'user', '1234567890', '', '2025-11-14 05:51:01', 0),
(42, 'Rudra', '', '$2y$10$RRnwFjbm1rsYMug8Fv3WfO3fKQObVkGqC1d908e0OCaVMM9zdLr0W', 'user', '9876543211', '', '2025-11-14 07:56:45', 0),
(43, 'AAA', '', '$2y$10$iouRU3wqlFfkDiKHAlK5ruGJxwX.C.fetHXEwZIrs7on0ZArVhDaS', 'user', '1212121212', '', '2025-11-16 12:39:02', 0),
(44, 'BBB', '', '$2y$10$aivmS5D8rgBCHvh9MBMm0ulbhqsdy3dbO92zRi4y5OMhuqNfD3bc2', 'user', '2345645678', '', '2025-11-16 12:46:36', 0),
(45, 'abc', '', '$2y$10$W3XNIeuSmGtQ9njg6y.3renYhY5BHeFOYsld0qf7sp9aXP7OXnYQW', 'user', '9998887770', '', '2025-11-16 12:49:54', 0),
(46, 'tanisha', '', '$2y$10$6UbCf/11UqA/oNDnMHFC4uDm1JUYUb1BfcwXB9xvYn1UA1kid8SF2', 'user', '7778887778', '', '2025-11-16 13:12:48', 0),
(47, 'tanu', '', '$2y$10$lZz.RNCULL6zCA2ctvdDmO.5nyOzFVrvDgCWOuhkM0/3Hmm1XmZdy', 'user', '5556665556', '', '2025-11-16 13:15:28', 0),
(48, 'josana', '', '$2y$10$b3oWd1IrJVg6yzfZwp9.iub.ykj1m57NooEklCVit2YqEFOLWDgJm', 'user', '1112223334', '', '2025-11-17 06:20:53', 0),
(49, 'Ravi', '', '$2y$10$Fph3aSVzfRQD8M.VmMlX6OzGz4AAg/rr.zdgEB2jDA0MsK3As9K4u', 'user', '3334444555', '', '2025-11-17 06:23:50', 0),
(50, 'Kinjal', '', '$2y$10$t5Rato9ou7M0PGVHKPxuF.fPLHQz3WuEsgdA4ocdtEThdrMg6dltq', 'user', '1111122222', '', '2025-11-17 06:26:32', 0),
(51, 'Nilam', '', '$2y$10$t5EXAzH1s8zsUCJyjkTNfOXBSE1Qdze2.dvK2PLw0oveHMTBnwwmW', 'user', '4444455555', '', '2025-11-17 07:16:56', 0),
(52, 'xyz', '', '$2y$10$COQUENQJHmYCIM40uEDJc.zgzXhS4svCKwNpsHZA/RDptLfcF667K', 'user', '7990417197', '', '2025-11-17 07:22:06', 0),
(53, 'Efg', '', '$2y$10$tO46JGMfo/v/gxaZi3GSQeNuQtpGigy/uGlQoxndmwWOPkvhhEN3C', 'user', '9558538597', '', '2025-11-17 07:28:41', 0),
(55, 'yesha', '', '$2y$10$Ax4m3Nkendsi3CKuwi2L1OPFyxEg7mG2knOe1wdPg6o8YXCNP.YCG', 'user', '7990417197', '', '2025-11-17 07:48:33', 0),
(56, 'vanita', '', '$2y$10$W3166MXgtn24DaG.WXRop.ag2YQCkuR236w93k766vLdZ8YT3IH82', 'user', '7990417197', '', '2025-11-17 07:52:27', 0),
(57, 'Darshu', '', '$2y$10$KcFAkDV9KAnpuPK2Y6OaP.QCc83.pogQA8IJ4H6CYr8HTebUMrBVK', 'user', '9979723064', '', '2025-11-17 07:57:38', 0),
(58, 'sonali', '', '$2y$10$lpvptmM1rSKskWwM3fOAeOLPv.kbBMEN.T9KxAdqzcMq7yUYS58PG', 'user', '8758165227', '', '2025-11-17 11:08:45', 0),
(59, 'Vishal', '', '$2y$10$.T.3cKNoVtASJRSjdJ674uZqzHa6ZWwYSiIHLnZ283csmBx7FmFM6', 'user', '9909909909', '', '2025-11-17 11:31:56', 0),
(60, 'Rutv', '', '$2y$10$ZF8ctovYLxRNNxINV5cPhuwRyX79cz.pL4vLS1zD29FH45Ht0SRIW', 'user', '7990417197', '', '2025-11-17 11:50:51', 0),
(61, 'vishu', '', '$2y$10$kCxfvLwzl.XGNP/yYaogZu3YBCTSNCMI8UAqFTemI/rT7w27u5QA.', 'user', '8758165227', '', '2025-11-17 11:57:54', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `username` varchar(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `activity_level` varchar(50) NOT NULL,
  `timeframe` int(11) NOT NULL,
  `options` varchar(50) NOT NULL,
  `calories` int(10) NOT NULL,
  `meals` int(10) NOT NULL,
  `goal` varchar(255) DEFAULT NULL,
  `bodyfat` int(11) DEFAULT NULL,
  `carbs` int(11) DEFAULT NULL,
  `fats` int(11) DEFAULT NULL,
  `proteins` int(11) DEFAULT NULL,
  `foodcat` varchar(50) DEFAULT NULL,
  `last_yoga_date` date DEFAULT NULL,
  `yoga_streak` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `username`, `uid`, `name`, `age`, `gender`, `height`, `weight`, `activity_level`, `timeframe`, `options`, `calories`, `meals`, `goal`, `bodyfat`, `carbs`, `fats`, `proteins`, `foodcat`, `last_yoga_date`, `yoga_streak`) VALUES
(1, 'root', NULL, 'sg', 2, 'male', 123, 23, '1', 3, 'lose weight', 1234, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 'user26', NULL, 'dsv', 3, 'female', 2, 3, '2', 3, 'lose weight', 1234, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, 'qwe', NULL, 'hwllo jsnak', 3, 'female', 2, 2, '1', 3, 'lose weight', 123, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 'riddhi', 11, '', 34, 'female', 5, 76, 'sedentary', 0, '', 2000, 0, 'maintain weight', 0, NULL, NULL, NULL, NULL, NULL, 0),
(5, 'vidhisha', 12, '', 26, 'female', 6, 67, 'active', 0, '', 1500, 0, 'build muscle', 0, 13, 11, 18, 'paleo', '2025-11-12', 7),
(6, 'admin1', 14, '', 23, 'male', 5, 60, 'sedentary', 0, '', 0, 0, 'build muscle', 0, NULL, NULL, NULL, NULL, NULL, 0),
(7, 'Rajal', 23, '', 34, 'female', 178, 56, 'moderately active', 0, '', 3000, 0, 'maintain weight', 0, NULL, NULL, NULL, 'mediterranean', NULL, 0),
(8, 'Dhruti', 25, '', 25, 'female', 178, 79, 'very active', 0, '', 2890, 0, 'lose fat', 0, 125, 164, 200, 'mediterranean', '2025-09-21', 1),
(9, 'keyur', 26, '', 25, 'male', 167, 79, 'very active', 0, '', 1800, 0, 'lose fat', 0, 229, 82, 66, NULL, '2025-10-13', 1),
(10, 'ram', 27, '', 34, 'male', 167, 90, 'very active', 0, '', 1500, 0, 'lose fat', 0, 162, 48, 116, NULL, NULL, 0),
(11, 'raju', 30, '', 30, 'male', 165, 67, 'very active', 0, '', 1800, 0, 'build muscle', 0, 139, 28, 81, NULL, '2025-11-06', 1),
(12, 'chintu', 31, '', 25, 'male', 165, 80, 'very active', 0, '', 1900, 0, 'lose fat', 0, 154, 33, 83, NULL, NULL, 0),
(13, 'mansi', 32, '', 34, 'female', 189, 89, 'active', 0, '', 2000, 0, 'lose fat', 0, 136, 11, 63, NULL, NULL, 0),
(14, 'dev', 33, '', 26, 'male', 159, 70, 'moderately active', 0, '', 1700, 0, 'lose fat', 0, 111, 21, 13, NULL, NULL, 0),
(15, 'devnsi', 34, '', 22, 'female', 160, 60, 'lightly active', 0, '', 1600, 0, 'maintain weight', 0, 121, 34, 10, NULL, NULL, 0),
(16, 'hemali', 35, '', 34, 'female', 178, 78, 'active', 0, '', 2100, 0, 'maintain weight', 0, 152, 61, 19, NULL, NULL, 0),
(17, 'asmita', 38, 'asmita', 23, 'female', 150, 67, 'moderately active', 0, '', 1800, 0, 'build muscle', 0, 105, 12, 4, 'anything', NULL, 0),
(18, 'ttt', 40, 'ttt', 35, 'male', 156, 78, 'active', 0, '', 1900, 0, 'maintain weight', 0, 113, 33, 11, 'keto', NULL, 0),
(19, 'mirali', 41, 'mirali', 21, 'female', 156, 70, 'very active', 0, '', 2000, 0, 'maintain weight', 0, 147, 41, 17, 'anything', '2025-11-14', 1),
(20, 'Rudra', 42, 'Rudra', 20, 'male', 154, 67, 'moderately active', 0, '', 2100, 0, 'build muscle', 0, 130, 32, 15, 'mediterranean', '2025-11-16', 3),
(21, 'AAA', 43, 'AAA', 18, 'male', 150, 67, 'moderately active', 0, '', 1500, 0, 'maintain weight', 0, 164, 39, 12, 'anything', NULL, 0),
(22, 'BBB', 44, 'BBB', 21, 'female', 165, 60, 'sedentary', 0, '', 1400, 0, 'build muscle', 0, 114, 16, 5, 'paleo', '2025-11-16', 1),
(23, 'abc', 45, 'abc', 15, 'female', 155, 56, 'sedentary', 0, '', 1200, 0, 'build muscle', 0, 166, 30, 12, 'paleo', '2025-11-16', 1),
(24, 'tanisha', 46, 'tanisha', 25, 'female', 154, 78, 'very active', 0, '', 2500, 0, 'build muscle', 0, 162, 43, 14, 'keto', NULL, 0),
(25, 'tanu', 47, 'tanu', 23, 'female', 153, 60, 'lightly active', 0, '', 1400, 0, 'lose fat', 0, 145, 40, 20, 'anything', NULL, 0),
(26, 'josana', 48, 'josana', 32, 'female', 153, 70, 'very active', 0, '', 2500, 0, 'build muscle', 0, 153, 34, 10, 'paleo', '2025-11-17', 1),
(27, 'Ravi', 49, 'Ravi', 31, 'male', 150, 76, 'active', 0, '', 2100, 0, 'lose fat', 0, 156, 43, 11, 'paleo', NULL, 0),
(28, 'Kinjal', 50, 'Kinjal', 35, 'female', 156, 80, 'moderately active', 0, '', 1500, 0, 'lose fat', 0, 205, 112, 29, 'mediterranean', NULL, 0),
(29, 'Nilam', 51, 'Nilam', 23, 'female', 156, 68, 'active', 0, '', 1670, 0, 'build muscle', 0, 151, 46, 24, 'anything', NULL, 0),
(30, 'xyz', 52, 'xyz', 45, 'male', 153, 67, 'sedentary', 0, '', 2000, 0, 'build muscle', 0, 134, 19, 3, 'mediterranean', NULL, 0),
(31, 'Efg', 53, 'Efg', 16, 'female', 153, 70, 'lightly active', 0, '', 2100, 0, 'lose fat', 0, 233, 84, 15, 'keto', NULL, 0),
(32, 'yesha', 54, 'yesha', 21, 'female', 150, 62, 'very active', 0, '', 2200, 0, 'build muscle', 0, 168, 74, 20, 'mediterranean', NULL, 0),
(33, 'vanita', 56, 'vanita', 20, 'male', 156, 45, 'sedentary', 0, '', 2000, 0, 'lose fat', 0, 115, 20, 9, 'keto', NULL, 0),
(34, 'Darshu', 57, 'Darshu', 20, 'male', 158, 72, 'very active', 0, '', 2600, 0, 'maintain weight', 0, 171, 95, 47, 'anything', NULL, 0),
(35, 'sonali', 58, 'sonali', 40, 'female', 150, 83, 'active', 0, '', 1400, 0, 'lose fat', 0, 131, 31, 12, 'paleo', NULL, 0),
(36, 'Vishal', 59, 'Vishal', 25, 'male', 164, 78, 'active', 0, '', 1200, 0, 'maintain weight', 0, 112, 29, 10, 'mediterranean', NULL, 0),
(37, 'Rutv', 60, 'Rutv', 22, 'male', 152, 64, 'sedentary', 0, '', 2000, 0, 'build muscle', 0, 167, 31, 7, 'paleo', '2025-11-17', 1),
(38, 'vishu', 61, 'vishu', 19, 'male', 143, 60, 'sedentary', 0, '', 2500, 0, 'lose fat', 0, 114, 45, 13, 'mediterranean', '2025-11-17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `yoga_poses`
--

CREATE TABLE `yoga_poses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `yoga_poses`
--

INSERT INTO `yoga_poses` (`id`, `name`, `image`, `description`, `difficulty`) VALUES
(1, 'Cobra Pose', 'yoga-cobra-pose.jpg', 'Improves spine flexibility and strengthens the back.', 'beginner'),
(2, 'Cow Pose', 'yoga-cow-pose.jpg', 'Improves posture and strengthens the spine.', 'beginner'),
(3, 'Downward Dog Pose', 'yoga-downward-dog.jpg', 'Stretches shoulders, hamstrings, calves, arches, and hands.', 'intermediate'),
(4, 'Lotus Pose', 'yoga-lotus-pose.jpg', 'Classic meditation pose that promotes relaxation.', 'intermediate'),
(5, 'Tree Pose', 'yoga-tree-pose.jpg', 'Improves balance and strengthens legs.', 'beginner'),
(6, 'Warrior II Pose', 'yoga-warrior2-pose.jpg', 'Strengthens legs and arms, improves stamina and concentration.', 'intermediate');

-- --------------------------------------------------------

--
-- Table structure for table `yoga_pose_details`
--

CREATE TABLE `yoga_pose_details` (
  `id` int(11) NOT NULL,
  `pose_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `how_to_perform` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `yoga_pose_details`
--

INSERT INTO `yoga_pose_details` (`id`, `pose_id`, `description`, `benefits`, `how_to_perform`) VALUES
(1, 1, 'Also called Bhujangasana, a reclining back-bending pose that strengthens the spine.', 'Improves posture, strengthens spine, reduces stress and fatigue.', 'Lie flat on stomach, place palms under shoulders, inhale and lift chest upward while keeping pelvis on the floor.'),
(2, 2, 'Also called Bitilasana, a gentle flow pose usually paired with Cat pose.', 'Stretches spine and neck, improves flexibility and posture.', 'Start on hands and knees, inhale while arching back and lifting head, exhale to release.'),
(3, 3, 'Also called Adho Mukha Svanasana, an essential inversion pose.', 'Strengthens arms and legs, stretches hamstrings and calves, improves blood flow to brain.', 'Start on hands and knees, lift hips towards ceiling, straighten legs and arms forming an inverted V shape.'),
(4, 4, 'Also called Padmasana, a classic seated meditation posture.', 'Calms the mind, improves posture, increases focus and concentration.', 'Sit with legs crossed, place each foot on the opposite thigh, keep spine straight and hands on knees.'),
(5, 5, 'Also called Vrikshasana, a balancing pose that resembles a tree.', 'Improves balance, strengthens thighs, calves, ankles, and enhances concentration.', 'Stand straight, place one foot on opposite thigh, bring hands together in namaste above head.'),
(6, 6, 'Also called Virabhadrasana II, a standing warrior pose.', 'Strengthens legs, arms, improves stamina, stretches chest and shoulders.', 'Stand with legs apart, extend arms parallel to floor, bend front knee, gaze forward.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_querytbl`
--
ALTER TABLE `contact_querytbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diet_plan`
--
ALTER TABLE `diet_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meal_plan`
--
ALTER TABLE `meal_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `yoga_poses`
--
ALTER TABLE `yoga_poses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yoga_pose_details`
--
ALTER TABLE `yoga_pose_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pose_id` (`pose_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_querytbl`
--
ALTER TABLE `contact_querytbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `diet_plan`
--
ALTER TABLE `diet_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `discount_coupons`
--
ALTER TABLE `discount_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `meal_plan`
--
ALTER TABLE `meal_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `yoga_poses`
--
ALTER TABLE `yoga_poses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `yoga_pose_details`
--
ALTER TABLE `yoga_pose_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diet_plan`
--
ALTER TABLE `diet_plan`
  ADD CONSTRAINT `diet_plan_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diet_plan_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meal_plan`
--
ALTER TABLE `meal_plan`
  ADD CONSTRAINT `meal_plan_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yoga_pose_details`
--
ALTER TABLE `yoga_pose_details`
  ADD CONSTRAINT `yoga_pose_details_ibfk_1` FOREIGN KEY (`pose_id`) REFERENCES `yoga_poses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
