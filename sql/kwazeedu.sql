-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 09:05 AM
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
-- Database: `kwazeedu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  `otp` varchar(50) NOT NULL,
  `otp_time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `first_name`, `last_name`, `email`, `password`, `user_role`, `otp`, `otp_time`) VALUES
(1, 'iammanager', 'Wilson', 'liew', 'manager@gmail.com', 'manager99', 'manager', '@@@@', '2024-06-12 17:34:02.647447'),
(2, 'JK', 'Jacky', 'Kum', 'dekkaioppai@gmail.com', 'w8eudosu3', 'admin', '@@@@', '2024-06-07 15:06:07.422939'),
(3, 'AK', 'Annie', 'Kiu', 'yummy@yum.cum', 'e98wfy298', 'admin', '@@@@', '2024-06-07 15:06:53.458396'),
(4, 'nekopara', 'Vanilla', 'Chocola', 'AT@gmail.com', '1234567890', 'admin', '@@@@', '2024-06-07 15:07:12.847560'),
(5, 'Chew', 'Bubu', 'Chocola', 'CAS@gmail.com', '12345678', 'admin', '@@@@', '2024-06-07 15:07:27.898893'),
(6, 'Spange', 'bob', 'Wuff', 'SB@gmail.com', '1234563245678', 'admin', '@@@@', '2024-06-07 15:07:51.010891'),
(7, 'Forest', 'Pogkkai', 'Kum', 'ljbroccoli@gmail.com', '1234567890-', 'admin', '@@@@', '2024-06-07 15:08:04.935829');

-- --------------------------------------------------------

--
-- Table structure for table `email_verify`
--

CREATE TABLE `email_verify` (
  `id` int(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `otp` varchar(50) NOT NULL,
  `otp_time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_pic`
--

CREATE TABLE `game_pic` (
  `pic_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(50) NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_pic`
--

INSERT INTO `game_pic` (`pic_id`, `name`, `category`, `price`, `image`, `date_created`) VALUES
(1, 'Cat', 'Capybara', 20, 'capybaraCat.jpeg', '2024-06-05 07:55:08.810669'),
(2, 'Pig', 'Capybara', 20, 'capybaraPig.jpeg', '2024-06-04 18:44:03.281725'),
(3, 'Rabbit', 'Capybara', 20, 'capybaraRabbit.jpeg', '2024-06-04 18:44:03.281725'),
(4, 'Snow White', 'Maltese', 30, 'p_SnowWhi.jpeg', '2024-06-07 09:16:01.000000'),
(5, 'Snow Choco', 'Maltese', 30, 'p_SnowCho.jpeg', '2024-06-07 15:16:34.872662'),
(6, 'Pizza White', 'Maltese', 20, 'p_PizzaWhi.jpeg', '2024-06-07 09:17:05.000000'),
(7, 'Pizza Choco', 'Maltese', 12, 'p_PizzaCho.jpeg', '2024-06-07 09:17:22.000000'),
(8, 'Teacher', 'Capybara', 12, 'p_CapybaraTeacher.jpeg', '2024-06-07 15:19:19.473212'),
(9, 'Love', 'Pochacco', 1, 'p_Love.jpeg', '2024-06-07 09:17:56.000000'),
(10, 'Cat', 'Kuromi', 40, 'p_KuromiCat.jpeg', '2024-06-07 09:18:09.000000'),
(11, 'Slobber', 'Capybara', 22, 'p_CapybaraSlobber.jpeg', '2024-06-07 15:19:23.899760'),
(12, 'Camera', 'Capybara', 2, 'p_CapybaraCamera.jpeg', '2024-06-07 15:19:25.944781'),
(13, 'Eat', 'Pochacco', 4, 'p_Spoon.jpeg', '2024-06-12 16:22:34.000000'),
(14, 'Shopping', 'Maltese', 10, 'p_Shopping.jpeg', '2024-06-12 16:22:55.000000'),
(15, 'Chef', 'Capybara', 12, 'p_CapybaraChef.jpeg', '2024-06-12 16:23:24.000000'),
(16, 'UFO', 'Maltese', 40, 'p_UFO.jpeg', '2024-06-12 16:23:49.000000'),
(17, 'Jupiter Choco', 'Maltese', 20, 'p_JupiterCho.jpeg', '2024-06-12 16:24:35.000000'),
(18, 'Jupiter White', 'Maltese', 20, 'p_JupiterWhi.jpeg', '2024-06-12 16:24:53.000000'),
(19, 'Sleeping', 'Pochacco', 5, 'p_PochaccoSleep.jpeg', '2024-06-12 16:26:30.000000'),
(20, 'Ice Cream', 'Pochacco', 10, 'p_PochaccoIceCream.jpeg', '2024-06-12 16:26:47.000000'),
(21, 'Dancing', 'Pochacco', 0, 'p_PochaccoDance.jpeg', '2024-06-12 16:26:58.000000'),
(22, 'Rose', 'Pochacco', 5, 'p_PochaccoRose.jpeg', '2024-06-12 16:27:09.000000'),
(23, 'Oreo White', 'Maltese', 5, 'p_OreoWhi.jpeg', '2024-06-12 16:27:32.000000'),
(24, 'Oreo Choco', 'Maltese', 5, 'p_OreoCho.jpeg', '2024-06-12 16:27:47.000000'),
(25, 'Exercise White', 'Maltese', 0, 'p_ExerciseWhi.jpeg', '2024-06-12 16:28:09.000000'),
(26, 'Exercise Choco', 'Maltese', 0, 'p_ExerciseCho.jpeg', '2024-06-12 16:28:24.000000'),
(27, 'Eating Drumstick', 'Capybara', 6, 'p_CapybaraChicken.jpeg', '2024-06-12 16:29:15.000000'),
(28, 'Eating Ice Cream', 'Capybara', 5, 'p_CapybaraWatermelon.jpeg', '2024-06-12 16:29:50.000000'),
(29, 'Eating Watermelon', 'Capybara', 10, 'p_CapybaraWatermelon2.jpeg', '2024-06-12 16:30:12.000000'),
(30, 'Sitting', 'Kuromi', 6, 'p_KuromiSitting.jpeg', '2024-06-12 16:30:39.000000'),
(31, 'Hooray', 'SpongeBob', 5, 'p_SpongeYahoo.jpeg', '2024-06-12 16:47:11.000000'),
(32, 'Sleeping', 'SpongeBob', 3, 'p_SpongeSleep.jpeg', '2024-06-12 16:47:30.000000'),
(33, 'Side Eyes', 'SpongeBob', 0, 'p_SpongeSideeyes.jpeg', '2024-06-12 16:47:46.000000'),
(34, 'Pitiful', 'SpongeBob', 20, 'p_SpongePitiful.jpeg', '2024-06-12 16:48:14.000000'),
(35, 'Peace', 'SpongeBob', 5, 'p_SpongePeace.jpeg', '2024-06-12 16:48:26.000000'),
(36, 'Excited', 'SpongeBob', 10, 'p_SpongeExcited.jpeg', '2024-06-12 16:48:42.000000'),
(37, 'Chill', 'SpongeBob', 6, 'p_SpongeChill.jpeg', '2024-06-12 16:48:57.000000'),
(38, 'Cash', 'SpongeBob', 20, 'p_SpongeCash.jpeg', '2024-06-12 16:49:07.000000');

-- --------------------------------------------------------

--
-- Table structure for table `learn_mat`
--

CREATE TABLE `learn_mat` (
  `mat_id` int(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `last_updated` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learn_mat`
--

INSERT INTO `learn_mat` (`mat_id`, `title`, `category`, `level`, `date_created`, `last_updated`, `admin_id`) VALUES
(1, 'The Adventure Begins', 'Storyboard Reading', '12 years old and above', '2024-06-12 19:29:07.582654', '2024-06-12 14:07:07.000000', 1),
(2, 'The Secret Garden', 'Paragraph Reading', '16 years old and above', '2024-06-12 20:22:31.402349', '2024-06-12 14:22:52.000000', 1),
(3, 'The Lost Puppy', 'Paragraph Reading', '12 years old and above', '2024-06-12 20:23:53.964639', '2024-06-12 14:24:22.000000', 1),
(4, 'Animal', 'Simple Flash Card', '12 years old and above', '2024-06-12 20:53:40.708772', '2024-06-12 16:02:30.000000', 1),
(5, 'Colour', 'Simple Flash Card', '12 years old and above', '2024-06-12 22:02:49.241105', '2024-06-12 16:04:38.000000', 1),
(6, 'Feeling', 'Advanced Flash Card', '21 years old and above', '2024-06-12 22:05:00.633902', '2024-06-12 16:13:21.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mat_content`
--

CREATE TABLE `mat_content` (
  `content_id` int(100) NOT NULL,
  `mat_id` int(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mat_content`
--

INSERT INTO `mat_content` (`content_id`, `mat_id`, `content`, `image`) VALUES
(1, 1, 'This is Alex and Sam. They love adventures.', 'The Adventure Begins 1.png'),
(2, 1, 'One day, they found an old map. It showed a hidden treasure!', 'The Adventure Begins 2.png'),
(3, 1, 'Alex and Sam packed their bags and set off on their journey.', 'The Adventure Begins 3.png'),
(4, 1, 'They walked through a forest and saw many animals. It was exciting!', 'The Adventure Begins 4.png'),
(5, 1, 'They came across a wide river. How would they cross it?', 'The Adventure Begins 5.png'),
(6, 1, 'Alex had an idea. They built a raft to cross the river.', 'The Adventure Begins 6.png'),
(7, 1, 'The map led them to a cave. The treasure must be inside!', 'The Adventure Begins 7.png'),
(8, 1, 'Inside the cave, they found a chest full of sparkling gems. They did it!', 'The Adventure Begins 8.png'),
(9, 1, 'Alex and Sam returned home as heroes. They would never forget this adventure.', 'The Adventure Begins 9.png'),
(10, 2, 'Emily loved spending time in her grandmother\'s garden. The garden was big and full of colorful flowers. Every time she visited; she found a new flower to admire. One day, while exploring, Emily noticed something different. A small, hidden door behind a bush caught her eye.', 'noimage'),
(11, 2, 'Emily felt curious and decided to open the door. It creaked as she pushed it. Behind the door was a secret garden! This garden was even more beautiful than her grandmother\'s. It had tall trees, sparkling streams, and butterflies flying everywhere.', 'noimage'),
(12, 2, 'Emily walked deeper into the garden. She saw a wooden bench and decided to sit down. Suddenly, she heard a soft voice. \"Welcome to the secret garden, Emily,\" the voice said. Emily looked around but saw no one. She felt excited and a little scared.', 'noimage'),
(13, 2, 'The voice continued, \"This garden has been waiting for you. It is a place of magic and wonder. Take care of it, and it will share its secrets with you.\" Emily promised to take good care of the garden. She couldn\'t wait to tell her grandmother about her discovery.', 'noimage'),
(14, 2, 'Every day after school, Emily visited the secret garden. She watered the plants, picked up fallen leaves, and made sure everything was perfect. The garden felt alive and happy. Emily knew she had found a special place, and she felt lucky to be its guardian.', 'noimage'),
(15, 3, 'Jake was walking home from school one rainy afternoon. As he walked, he heard a soft whimpering sound. Curious, Jake followed the sound and found a small, wet puppy hiding under a bush. The puppy looked scared and alone.', 'noimage'),
(16, 3, 'Jake gently picked up the puppy and wrapped it in his jacket. \"It\'s okay, little guy,\" he whispered. The puppy seemed to relax a bit in Jake\'s warm arms. Jake decided to take the puppy home and help it find its owner.\r\n\r\n', 'noimage'),
(17, 3, 'When Jake got home, he dried the puppy with a towel and gave it some food. The puppy wagged its tail happily. Jake\'s mom agreed they could keep the puppy until they found its owner. They named it Max.', 'noimage'),
(18, 3, 'Jake made posters with a picture of Max and put them up around the neighborhood. He also posted about the lost puppy online. Days passed, and no one came forward to claim Max. Jake was secretly happy because he had grown attached to the puppy.', 'noimage'),
(19, 3, 'One evening, a woman knocked on Jake\'s door. She had seen the posters and came to see if Max was her lost puppy, Buddy. Max\'s tail wagged furiously when he saw the woman. It was clear they were reunited. The woman thanked Jake and his mom, but she also saw how much Jake loved Max. She decided that Jake could visit Max anytime he wanted.', 'noimage'),
(32, 4, 'Cat', 'Animal 01.png'),
(33, 4, 'Dog', 'Animal 02.png'),
(34, 4, 'Penguin', 'Animal 03.png'),
(35, 4, 'Cow', 'Animal 04.png'),
(36, 4, 'Giraffe', 'Animal 05.png'),
(37, 4, 'Lion', 'Animal 06.png'),
(38, 4, 'Tiger', 'Animal 07.png'),
(39, 4, 'Fox', 'Animal 08.png'),
(40, 4, 'Bird', 'Animal 09.png'),
(41, 4, 'Eagle', 'Animal 10.png'),
(42, 4, 'Dolphin', 'Animal 11.png'),
(43, 4, 'Shark', 'Animal 12.png'),
(53, 5, 'Red', 'Color 01.png'),
(54, 5, 'Blue', 'Color 02.png'),
(55, 5, 'Green', 'Color 03.png'),
(56, 5, 'Yellow', 'Color 04.png'),
(57, 5, 'Pink', 'Color 05.png'),
(58, 5, 'Grey', 'Color 06.png'),
(59, 5, 'Black', 'Color 07.png'),
(60, 5, 'Orange', 'Color 08.png'),
(61, 5, 'Purple', 'Color 09.png'),
(62, 6, '{\"title\":\"Happy\",\"desc\":\"Feeling joy and contentment\"}', 'Feeling 01.png'),
(63, 6, '{\"title\":\"Sad\",\"desc\":\"Feeling sorrow and unhappiness\"}', 'Feeling 02.png'),
(64, 6, '{\"title\":\"Curious\",\"desc\":\"Feeling a strong desire to learn or know\"}', 'Feeling 03.png'),
(65, 6, '{\"title\":\"Fear\",\"desc\":\"Feeling anxious and afraid\"}', 'Feeling 04.png'),
(66, 6, '{\"title\":\"Guilt \",\"desc\":\"Feel remorse for what you have done wrong\"}', 'Feeling 05.png'),
(67, 6, '{\"title\":\"Disgust \",\"desc\":\"Feeling revulsion and disgust\"}', 'Feeling 06.png'),
(68, 6, '{\"title\":\"Envy\",\"desc\":\"Desire for another\'s property or quality\"}', 'Feeling 07.png'),
(69, 6, '{\"title\":\"Angry\",\"desc\":\"Feeling strong displeasure and hostility\"}', 'Feeling 08.png'),
(70, 6, '{\"title\":\"Surprise\",\"desc\":\"Feeling astonishment and shock\"}', 'Feeling 09.png'),
(71, 6, '{\"title\":\"Joy\",\"desc\":\"Feeling great pleasure and happiness\"}', 'Feeling 10.png');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `last_updated` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `title`, `category`, `level`, `type`, `date_created`, `last_updated`, `admin_id`) VALUES
(1, 'Natural : Plants', 'True or False', 'All ages', 'rank', '2024-06-12 17:42:50.832161', '2024-06-12 11:44:44.000000', 1),
(2, 'Natural : Animals', 'True or False', 'All ages', 'rank', '2024-06-12 22:58:20.456670', '2024-06-12 16:59:52.000000', 1),
(3, 'Feelings', 'Multiple Choice Question', 'All ages', 'rank', '2024-06-12 23:01:43.109914', '2024-06-12 17:04:23.000000', 1),
(4, 'Mathematics : Basic Calculation', 'Multiple Choice Question', 'All ages', 'rank', '2024-06-12 23:06:12.574303', '2024-06-12 17:07:26.000000', 1),
(5, 'Mathematics', 'Multiple Choice Question', '12 years old and above', 'prac', '2024-06-12 23:08:40.484239', '2024-06-12 17:12:14.000000', 1),
(6, 'Mathematics', 'Multiple Choice Question', '21 years old and above', 'prac', '2024-06-12 23:12:59.015499', '2024-06-12 17:23:35.000000', 1),
(7, 'History', 'True or False', '21 years old and above', 'prac', '2024-06-12 23:27:08.288696', '2024-06-12 17:28:25.000000', 1),
(8, 'Human', 'True or False', '12 years old and above', 'prac', '2024-06-12 23:28:59.972391', '2024-06-12 17:29:51.000000', 1),
(9, 'Grammar : Prepositions', 'Fill in the Blank', '12 years old and above', 'prac', '2024-06-12 23:30:42.772558', '2024-06-12 17:36:05.000000', 1),
(10, 'Grammar : Pronouns', 'Fill in the Blank', '12 years old and above', 'prac', '2024-06-12 23:37:57.612561', '2024-06-12 17:44:46.000000', 1),
(11, 'Grammar : Present Simple', 'Fill in the Blank', '16 years old and above', 'prac', '2024-06-12 23:40:12.389878', '2024-06-12 17:42:09.000000', 1),
(12, 'Geography : Capital Cities', 'Fill in the Blank', 'All ages', 'rank', '2024-06-12 23:47:56.413026', '2024-06-12 17:50:11.000000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_question`
--

CREATE TABLE `quiz_question` (
  `ques_id` int(100) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `quiz_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_question`
--

INSERT INTO `quiz_question` (`ques_id`, `question`, `answer`, `quiz_id`) VALUES
(11, 'All plants need sunlight to grow.', 'true', 1),
(12, 'Cacti can store water in their stems.', 'true', 1),
(13, 'Bamboo is a type of grass.', 'true', 1),
(14, 'Roses are the only flowers that have thorns.', 'false', 1),
(15, 'Some plants can grow without soil.', 'true', 1),
(16, 'Photosynthesis is the process by which plants make their food using sunlight.', 'true', 1),
(17, 'All plants have flowers.', 'false', 1),
(18, 'Venus flytraps are carnivorous plants.', 'true', 1),
(19, 'A tree\'s rings can tell its age.', 'true', 1),
(20, 'Algae are plants that live in water.', 'true', 1),
(22, 'A group of lions is called a pride.', 'true', 2),
(23, 'All birds can fly.', 'false', 2),
(24, 'Elephants are the largest land animals.', 'true', 2),
(25, 'Sharks are mammals.', 'false', 2),
(26, ' baby kangaroo is called a joey.', 'true', 2),
(27, 'Bats are blind.', 'false', 2),
(28, 'Dolphins sleep with one eye open.', 'true', 2),
(29, 'Camels store water in their humps.', 'false', 2),
(30, 'A group of owls is called a parliament.', 'true', 2),
(31, 'Koalas eat eucalyptus leaves.', 'true', 2),
(32, '{\"ques\":\"Which feeling might you experience if you win a competition?\",\"A\":\"Sadness\",\"B\":\"Anger\",\"C\":\"Joy\",\"D\":\"Fear\"}', 'C', 3),
(33, '{\"ques\":\"What feeling is often associated with receiving a surprise gift?\",\"A\":\"Boredom\",\"B\":\"Excitement\",\"C\":\"Disgust\",\"D\":\"Confusion\"}', 'B', 3),
(34, '{\"ques\":\"If someone is being bullied, they might feel:\",\"A\":\"Happy\",\"B\":\"Anxious\",\"C\":\"Calm\",\"D\":\"Proud\"}', 'B', 3),
(35, '{\"ques\":\"Which feeling is typically experienced during a scary movie?\",\"A\":\"Fear\",\"B\":\"Love\",\"C\":\"Anger\",\"D\":\"Boredom\"}', 'A', 3),
(36, '{\"ques\":\"What feeling might you have if you fail a test?\",\"A\":\"Happiness\",\"B\":\"Surprise\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 3),
(37, '{\"ques\":\"When you are very thirsty, you might feel:\",\"A\":\"Frustration\",\"B\":\"Hunger\",\"C\":\"Pride\",\"D\":\"Joy\"}', 'A', 3),
(38, '{\"ques\":\"How might you feel if you did something kind for a friend?\",\"A\":\"Guilty\",\"B\":\"Angry\",\"C\":\"Proud\",\"D\":\"Scared\"}', 'C', 3),
(39, '{\"ques\":\"Which feeling is often associated with losing a favorite item?\",\"A\":\"Joy\",\"B\":\"Fear\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 3),
(40, '{\"ques\":\"If you are about to perform on stage, you might feel:\",\"A\":\"Nervous\",\"B\":\"Bored\",\"C\":\"Disgusted\",\"D\":\"Peaceful\"}', 'A', 3),
(41, '{\"ques\":\"Which feeling might you have after hearing a funny joke?\",\"A\":\"Anger\",\"B\":\"Disappointment\",\"C\":\"Laughter\",\"D\":\"Frustration\"}', 'C', 3),
(42, '{\"ques\":\"What is 7 + 5?\",\"A\":\"10\",\"B\":\"12\",\"C\":\"14\",\"D\":\"15\"}', 'B', 4),
(43, '{\"ques\":\"What is the product of 8 and 3?\",\"A\":\"21\",\"B\":\"23\",\"C\":\"24\",\"D\":\"26\"}', 'C', 4),
(44, '{\"ques\":\"What is 15 divided by 3?\",\"A\":\"4\",\"B\":\"5\",\"C\":\"6\",\"D\":\"7\"}', 'B', 4),
(45, '{\"ques\":\"What is the value of \\r\\n9\\r\\n\\u00d7\\r\\n9\\r\\n9\\u00d79?\",\"A\":\"72\",\"B\":\"81\",\"C\":\"90\",\"D\":\"99\"}', 'B', 4),
(46, '{\"ques\":\"What is 25% of 200?\",\"A\":\"25\",\"B\":\"50\",\"C\":\"75\",\"D\":\"100\"}', 'B', 4),
(47, '{\"ques\":\"What is the sum of 6 and 8?\",\"A\":\"12\",\"B\":\"13\",\"C\":\"14\",\"D\":\"15\"}', 'C', 5),
(48, '{\"ques\":\"What is the product of 7 and 4?\",\"A\":\"21\",\"B\":\"24\",\"C\":\"27\",\"D\":\"28\"}', 'D', 5),
(49, '{\"ques\":\"What is 45 divided by 9?\",\"A\":\"4\",\"B\":\"5\",\"C\":\"6\",\"D\":\"7\"}', 'B', 5),
(50, '{\"ques\":\"If you subtract 15 from 20, what is the result?\",\"A\":\"3\",\"B\":\"4\",\"C\":\"5\",\"D\":\"6\"}', 'C', 5),
(51, '{\"ques\":\"What is the value of 3\\u00b2 (3 squared)?\",\"A\":\"6\",\"B\":\"9\",\"C\":\"12\",\"D\":\"15\"}', 'B', 5),
(52, '{\"ques\":\"What is 50% of 80?\",\"A\":\"20\",\"B\":\"30\",\"C\":\"40\",\"D\":\"50\"}', 'C', 5),
(53, '{\"ques\":\"Which number is a prime number?\",\"A\":\"12\",\"B\":\"15\",\"C\":\"17\",\"D\":\"20\"}', 'C', 5),
(54, '{\"ques\":\"What is the area of a rectangle with length 5 and width 3?\",\"A\":\"8\",\"B\":\"15\",\"C\":\"20\",\"D\":\"25\"}', 'B', 5),
(55, '{\"ques\":\"If you have a triangle with angles of 90\\u00b0, 45\\u00b0, and 45\\u00b0, what type of triangle is it?\",\"A\":\"Equilateral\",\"B\":\"Isosceles\",\"C\":\"Scalene\",\"D\":\"Right-angled\"}', 'D', 5),
(56, '{\"ques\":\"What is the value of \\u221a64 (square root of 64)?\",\"A\":\"6\",\"B\":\"7\",\"C\":\"8\",\"D\":\"9\"}', 'C', 5),
(57, '{\"ques\":\"What is the derivative of x\\u00b2 ?\",\"A\":\"1\",\"B\":\"2x\",\"C\":\"x\",\"D\":\"x\\u00b2\"}', 'B', 6),
(58, '{\"ques\":\"What is the value of \\ud835\\udf0b (pi) to two decimal places?\\r\\n\",\"A\":\"3.12\",\"B\":\"3.13\",\"C\":\"3.14\",\"D\":\"3.15\"}', 'C', 6),
(59, '{\"ques\":\"Which of the following is an example of a quadratic equation?\",\"A\":\"x + 2 = 0\",\"B\":\"x\\u00b2 \\u2212 4x + 4 = 0\",\"C\":\"x\\u00b3+ 3 = 0\",\"D\":\"2x \\u2212 5 = 0\"}', 'B', 6),
(60, '{\"ques\":\"What is the integral of 3x\\u00b2 ?\",\"A\":\"x\\u00b3 + C\",\"B\":\"3x + C\",\"C\":\"x\\u00b3 \",\"D\":\"9x + C\"}', 'A', 6),
(61, '{\"ques\":\"What is the sum of the interior angles of a triangle?\",\"A\":\"90\\u00b0\",\"B\":\"180\\u00b0\",\"C\":\"270\\u00b0\",\"D\":\"360\\u00b0\"}', 'B', 6),
(62, '{\"ques\":\"Which number is not a prime number?\",\"A\":\"29\",\"B\":\"31\",\"C\":\"33\",\"D\":\"37\"}', 'C', 6),
(63, '{\"ques\":\"What is the probability of rolling a 6 on a fair six-sided die?\",\"A\":\"\\u2159\\u200b\",\"B\":\"\\u2155\",\"C\":\"\\u00bc\",\"D\":\"\\u2153\"}', 'A', 6),
(64, '{\"ques\":\"What is the solution to the equation 2x+5=15 ?\",\"A\":\"3\",\"B\":\"4\",\"C\":\"5\",\"D\":\"6\"}', 'C', 6),
(65, '{\"ques\":\"If f(x) = 2x + 3, what is f(4) ?\",\"A\":\"8\",\"B\":\"10\",\"C\":\"11\",\"D\":\"12\"}', 'C', 6),
(66, '{\"ques\":\"What is the value of log10 1000?\",\"A\":\"1\",\"B\":\"2\",\"C\":\"3\",\"D\":\"4\"}', 'C', 6),
(67, 'The Great Wall of China was built to protect against invasions from the Mongols.', 'true', 7),
(68, 'The United States declared independence from Great Britain in 1776.', 'true', 7),
(69, 'Cleopatra was not the last pharaoh of ancient Egypt.', 'false', 7),
(70, 'The Roman Empire fell in the year 476 AD.', 'true', 7),
(71, 'The French Revolution began in 1788.', 'false', 7),
(72, 'The Magna Carta was signed in 1492.', 'false', 7),
(73, 'Leonardo da Vinci painted the ceiling of the Sistine Chapel.', 'false', 7),
(74, 'The first successful airplane flight was made by the Wright brothers in 1903.', 'true', 7),
(75, 'The Berlin Wall was built to separate East and West Germany.', 'true', 7),
(76, 'Mahatma Gandhi led India to independence from British rule through non-violent resistance.', 'true', 7),
(77, 'The human body has 206 bones.', 'true', 8),
(78, 'The heart is located on the left side of the chest.', 'false', 8),
(79, 'Humans have five senses: sight, hearing, taste, touch, and smell.', 'true', 8),
(80, 'The human brain is the largest organ in the body.', 'false', 8),
(81, 'Blood is red because of the presence of iron.', 'true', 8),
(82, 'The human body is made up of about 60% water.', 'true', 8),
(83, 'Adults have more bones than babies.', 'false', 8),
(84, 'The lungs are responsible for pumping blood throughout the body.', 'false', 8),
(95, 'The book is __ the table.', 'on', 9),
(96, 'She walked __ the park.', 'through', 9),
(97, 'The keys are __ the drawer.', 'in', 9),
(98, 'He is hiding __ the door.', 'behind', 9),
(99, 'The dog jumped __ the fence.', 'over', 9),
(100, 'The picture is hanging __ the wall.', 'on', 9),
(101, 'We sat __ the tree for shade.', 'under', 9),
(102, 'The cat ran __ the street.', 'across', 9),
(103, 'They went __ the movies last night.', 'to', 9),
(104, 'The train travels __ the two cities.', 'between', 9),
(125, 'She __ (read) a book every evening.', 'reads', 11),
(126, 'They __ (play) soccer on weekends.', 'play', 11),
(127, 'He __ (work) at a bank.', 'works', 11),
(128, 'The sun __ (rise) in the east.', 'rises', 11),
(129, 'We __ (study) English at school.', 'study', 11),
(130, 'She __ (wake) up early every day.', 'wakes', 11),
(131, 'I __ (like) to eat pizza.', 'like', 11),
(132, 'The train __ (leave) at 7 AM.', 'leaves', 11),
(133, 'He __ (drive) to work.', 'drives', 11),
(134, 'They __ (enjoy) going to the movies.', 'enjoy', 11),
(135, '__ is my male best friend.', 'He', 10),
(136, 'I gave the book to __ (female).', 'her', 10),
(137, '__ went to the park together.', 'They', 10),
(138, 'The teacher told __ a story.', 'us', 10),
(139, '__ (female) is going to the store.', 'She', 10),
(140, 'This is __ (me) favorite movie.', 'my', 10),
(141, 'The dog wagged __ tail.', 'its', 10),
(142, 'Can __ help me with this?', 'you', 10),
(143, '__ are planning a trip next week.', 'We', 10),
(144, 'I saw __ (more) at the concert last night.', 'them', 10),
(145, 'The capital of France is __.', 'Paris', 12),
(146, 'The capital of Japan is __.', 'Tokyo', 12),
(147, 'The capital of Australia is __.', 'Canberra', 12),
(148, 'The capital of Canada is __.', 'Ottawa', 12),
(149, 'The capital of China is __.', 'Beijing', 12),
(150, 'The capital of Italy is __.', 'Rome', 12),
(151, 'The capital of Brazil is __.', 'Brasilia', 12),
(152, 'The capital of India is __.', 'New Delhi', 12),
(153, 'The capital of England is __.', 'London', 12),
(154, 'The capital of South Korea is __.', 'Seoul', 12),
(155, 'The capital of Thailand is __.', 'Bangkok', 12),
(156, 'The capital of Malaysia is __.', 'Kuala Lumpur', 12),
(157, 'The capital of Turkey is __.', 'Ankara', 12);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `user_pfp` int(50) NOT NULL,
  `user_role` varchar(10) NOT NULL,
  `otp` varchar(50) NOT NULL,
  `otp_time` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `user_coins` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`, `country`, `user_pfp`, `user_role`, `otp`, `otp_time`, `user_coins`) VALUES
(1, 'Babu', 'Bababubu', 'gugugaga', 'iambaby69@gmail.com', 'eD3\'Nt$(WnJ', 'Japan', 2, 'user', '@@@@', '2024-06-12 23:55:48.134553', 0),
(2, 'Jacky', 'Lord', 'khoo', 'WL@gmail.com', '6699hsjakd', 'China', 0, 'user', '@@@@', '2024-06-12 23:56:24.242215', 0),
(3, 'Ooi\n', 'PY\n', 'opyyy', 'waifulpy@gmail.com', 'fukfok6969', 'Malaysia', 33, 'user', '@@@@', '2024-06-12 22:50:53.473562', 0),
(4, 'opy', 'opy', 'opysubeast', 'ooipeiying732@gmail.com', 'opykioppai69', 'Malaysia', 25, 'user', 'MSBEVS', '2024-06-13 06:58:20.067722', 7),
(5, 'Tatto\n', 'Kwan\n', 'joykwan', 'opy7654321@gmail.com', '0bd941a4d65821d5bcb23.1415926535897932384', 'Macao', 36, 'user', 'LerOf0', '2024-06-13 07:02:47.678215', 15),
(6, 'Koon', 'Hazel', 'hazenut', 'cirnomatonline2@gmail.com', '12345678', 'Japan', 3, 'user', '@@@@', '2024-06-12 23:56:45.475325', 20),
(7, 'Bubu', 'Baba', 'abababa', 'signasguest732@gmail.com', '12345678', 'Malaysia', 32, 'user', '@@@@', '2024-06-13 07:04:44.344588', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_pic`
--

CREATE TABLE `user_pic` (
  `userpic_id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `pic_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_pic`
--

INSERT INTO `user_pic` (`userpic_id`, `user_id`, `pic_id`) VALUES
(1, 3, 33),
(2, 4, 25),
(3, 5, 36),
(4, 7, 32);

-- --------------------------------------------------------

--
-- Table structure for table `user_result`
--

CREATE TABLE `user_result` (
  `result_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `quiz_id` int(100) NOT NULL,
  `quiz_type` varchar(10) NOT NULL,
  `quiz_category` varchar(50) NOT NULL,
  `score` varchar(500) NOT NULL,
  `coins_earned` int(100) NOT NULL,
  `timestamp` int(100) NOT NULL,
  `quiz_title` varchar(50) NOT NULL,
  `date_completed` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_result`
--

INSERT INTO `user_result` (`result_id`, `user_id`, `quiz_id`, `quiz_type`, `quiz_category`, `score`, `coins_earned`, `timestamp`, `quiz_title`, `date_completed`) VALUES
(1, 4, 3, 'rank', 'Multiple Choice Question', '{\"correct\":4,\"incorrect\":6,\"total\":700}', 7, 34, 'Feelings', '2024-06-13 00:57:38.000000'),
(2, 5, 12, 'rank', 'Fill in the Blank', '{\"correct\":6,\"incorrect\":7,\"total\":900}', 9, 46, 'Geography : Capital Cities', '2024-06-13 01:01:06.000000'),
(3, 5, 1, 'rank', 'True or False', '{\"correct\":7,\"incorrect\":3,\"total\":1000}', 10, 10, 'Natural : Plants', '2024-06-13 01:01:45.000000'),
(4, 5, 3, 'rank', 'Multiple Choice Question', '{\"correct\":3,\"incorrect\":7,\"total\":600}', 6, 16, 'Feelings', '2024-06-13 01:02:20.000000'),
(5, 7, 3, 'rank', 'Multiple Choice Question', '{\"correct\":1,\"incorrect\":9,\"total\":400}', 4, 8, 'Feelings', '2024-06-13 01:04:20.000000');

-- --------------------------------------------------------

--
-- Table structure for table `user_result_ques`
--

CREATE TABLE `user_result_ques` (
  `history_id` int(100) NOT NULL,
  `result_id` int(100) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `correct_answer` varchar(100) NOT NULL,
  `user_answer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_result_ques`
--

INSERT INTO `user_result_ques` (`history_id`, `result_id`, `question`, `correct_answer`, `user_answer`) VALUES
(1, 1, '{\"ques\":\"Which feeling might you experience if you win a competition?\",\"A\":\"Sadness\",\"B\":\"Anger\",\"C\":\"Joy\",\"D\":\"Fear\"}', 'C', 'C'),
(2, 1, '{\"ques\":\"What feeling is often associated with receiving a surprise gift?\",\"A\":\"Boredom\",\"B\":\"Excitement\",\"C\":\"Disgust\",\"D\":\"Confusion\"}', 'B', 'B'),
(3, 1, '{\"ques\":\"If someone is being bullied, they might feel:\",\"A\":\"Happy\",\"B\":\"Anxious\",\"C\":\"Calm\",\"D\":\"Proud\"}', 'B', 'B'),
(4, 1, '{\"ques\":\"Which feeling is typically experienced during a scary movie?\",\"A\":\"Fear\",\"B\":\"Love\",\"C\":\"Anger\",\"D\":\"Boredom\"}', 'A', 'C'),
(5, 1, '{\"ques\":\"What feeling might you have if you fail a test?\",\"A\":\"Happiness\",\"B\":\"Surprise\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'B'),
(6, 1, '{\"ques\":\"When you are very thirsty, you might feel:\",\"A\":\"Frustration\",\"B\":\"Hunger\",\"C\":\"Pride\",\"D\":\"Joy\"}', 'A', 'C'),
(7, 1, '{\"ques\":\"How might you feel if you did something kind for a friend?\",\"A\":\"Guilty\",\"B\":\"Angry\",\"C\":\"Proud\",\"D\":\"Scared\"}', 'C', 'C'),
(8, 1, '{\"ques\":\"Which feeling is often associated with losing a favorite item?\",\"A\":\"Joy\",\"B\":\"Fear\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'B'),
(9, 1, '{\"ques\":\"If you are about to perform on stage, you might feel:\",\"A\":\"Nervous\",\"B\":\"Bored\",\"C\":\"Disgusted\",\"D\":\"Peaceful\"}', 'A', 'C'),
(10, 1, '{\"ques\":\"Which feeling might you have after hearing a funny joke?\",\"A\":\"Anger\",\"B\":\"Disappointment\",\"C\":\"Laughter\",\"D\":\"Frustration\"}', 'C', 'D'),
(11, 2, 'The capital of France is __.', 'Paris', ''),
(12, 2, 'The capital of Japan is __.', 'Tokyo', 'Tokyo'),
(13, 2, 'The capital of Australia is __.', 'Canberra', ''),
(14, 2, 'The capital of Canada is __.', 'Ottawa', ''),
(15, 2, 'The capital of China is __.', 'Beijing', 'Bei Jing'),
(16, 2, 'The capital of Italy is __.', 'Rome', 'Rome'),
(17, 2, 'The capital of Brazil is __.', 'Brasilia', ''),
(18, 2, 'The capital of India is __.', 'New Delhi', 'New Delhi'),
(19, 2, 'The capital of England is __.', 'London', ''),
(20, 2, 'The capital of South Korea is __.', 'Seoul', 'Seoul'),
(21, 2, 'The capital of Thailand is __.', 'Bangkok', 'Bangkok'),
(22, 2, 'The capital of Malaysia is __.', 'Kuala Lumpur', 'Kuala Lumpur'),
(23, 2, 'The capital of Turkey is __.', 'Ankara', ''),
(24, 3, 'All plants need sunlight to grow.', 'true', 'true'),
(25, 3, 'Cacti can store water in their stems.', 'true', 'true'),
(26, 3, 'Bamboo is a type of grass.', 'true', 'true'),
(27, 3, 'Roses are the only flowers that have thorns.', 'false', 'false'),
(28, 3, 'Some plants can grow without soil.', 'true', 'false'),
(29, 3, 'Photosynthesis is the process by which plants make their food using sunlight.', 'true', 'true'),
(30, 3, 'All plants have flowers.', 'false', 'false'),
(31, 3, 'Venus flytraps are carnivorous plants.', 'true', 'false'),
(32, 3, 'A tree\'s rings can tell its age.', 'true', 'false'),
(33, 3, 'Algae are plants that live in water.', 'true', 'true'),
(34, 4, '{\"ques\":\"Which feeling might you experience if you win a competition?\",\"A\":\"Sadness\",\"B\":\"Anger\",\"C\":\"Joy\",\"D\":\"Fear\"}', 'C', 'C'),
(35, 4, '{\"ques\":\"What feeling is often associated with receiving a surprise gift?\",\"A\":\"Boredom\",\"B\":\"Excitement\",\"C\":\"Disgust\",\"D\":\"Confusion\"}', 'B', 'C'),
(36, 4, '{\"ques\":\"If someone is being bullied, they might feel:\",\"A\":\"Happy\",\"B\":\"Anxious\",\"C\":\"Calm\",\"D\":\"Proud\"}', 'B', 'A'),
(37, 4, '{\"ques\":\"Which feeling is typically experienced during a scary movie?\",\"A\":\"Fear\",\"B\":\"Love\",\"C\":\"Anger\",\"D\":\"Boredom\"}', 'A', 'C'),
(38, 4, '{\"ques\":\"What feeling might you have if you fail a test?\",\"A\":\"Happiness\",\"B\":\"Surprise\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'C'),
(39, 4, '{\"ques\":\"When you are very thirsty, you might feel:\",\"A\":\"Frustration\",\"B\":\"Hunger\",\"C\":\"Pride\",\"D\":\"Joy\"}', 'A', 'B'),
(40, 4, '{\"ques\":\"How might you feel if you did something kind for a friend?\",\"A\":\"Guilty\",\"B\":\"Angry\",\"C\":\"Proud\",\"D\":\"Scared\"}', 'C', 'B'),
(41, 4, '{\"ques\":\"Which feeling is often associated with losing a favorite item?\",\"A\":\"Joy\",\"B\":\"Fear\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'B'),
(42, 4, '{\"ques\":\"If you are about to perform on stage, you might feel:\",\"A\":\"Nervous\",\"B\":\"Bored\",\"C\":\"Disgusted\",\"D\":\"Peaceful\"}', 'A', 'D'),
(43, 4, '{\"ques\":\"Which feeling might you have after hearing a funny joke?\",\"A\":\"Anger\",\"B\":\"Disappointment\",\"C\":\"Laughter\",\"D\":\"Frustration\"}', 'C', 'C'),
(44, 5, '{\"ques\":\"Which feeling might you experience if you win a competition?\",\"A\":\"Sadness\",\"B\":\"Anger\",\"C\":\"Joy\",\"D\":\"Fear\"}', 'C', 'D'),
(45, 5, '{\"ques\":\"What feeling is often associated with receiving a surprise gift?\",\"A\":\"Boredom\",\"B\":\"Excitement\",\"C\":\"Disgust\",\"D\":\"Confusion\"}', 'B', 'D'),
(46, 5, '{\"ques\":\"If someone is being bullied, they might feel:\",\"A\":\"Happy\",\"B\":\"Anxious\",\"C\":\"Calm\",\"D\":\"Proud\"}', 'B', 'D'),
(47, 5, '{\"ques\":\"Which feeling is typically experienced during a scary movie?\",\"A\":\"Fear\",\"B\":\"Love\",\"C\":\"Anger\",\"D\":\"Boredom\"}', 'A', 'D'),
(48, 5, '{\"ques\":\"What feeling might you have if you fail a test?\",\"A\":\"Happiness\",\"B\":\"Surprise\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'C'),
(49, 5, '{\"ques\":\"When you are very thirsty, you might feel:\",\"A\":\"Frustration\",\"B\":\"Hunger\",\"C\":\"Pride\",\"D\":\"Joy\"}', 'A', 'C'),
(50, 5, '{\"ques\":\"How might you feel if you did something kind for a friend?\",\"A\":\"Guilty\",\"B\":\"Angry\",\"C\":\"Proud\",\"D\":\"Scared\"}', 'C', 'D'),
(51, 5, '{\"ques\":\"Which feeling is often associated with losing a favorite item?\",\"A\":\"Joy\",\"B\":\"Fear\",\"C\":\"Sadness\",\"D\":\"Excitement\"}', 'C', 'D'),
(52, 5, '{\"ques\":\"If you are about to perform on stage, you might feel:\",\"A\":\"Nervous\",\"B\":\"Bored\",\"C\":\"Disgusted\",\"D\":\"Peaceful\"}', 'A', 'B'),
(53, 5, '{\"ques\":\"Which feeling might you have after hearing a funny joke?\",\"A\":\"Anger\",\"B\":\"Disappointment\",\"C\":\"Laughter\",\"D\":\"Frustration\"}', 'C', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `email_verify`
--
ALTER TABLE `email_verify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_pic`
--
ALTER TABLE `game_pic`
  ADD PRIMARY KEY (`pic_id`);

--
-- Indexes for table `learn_mat`
--
ALTER TABLE `learn_mat`
  ADD PRIMARY KEY (`mat_id`);

--
-- Indexes for table `mat_content`
--
ALTER TABLE `mat_content`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD PRIMARY KEY (`ques_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_pic` (`user_pfp`);

--
-- Indexes for table `user_pic`
--
ALTER TABLE `user_pic`
  ADD PRIMARY KEY (`userpic_id`),
  ADD KEY `pic` (`pic_id`),
  ADD KEY `user` (`user_id`);

--
-- Indexes for table `user_result`
--
ALTER TABLE `user_result`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `user_result` (`user_id`);

--
-- Indexes for table `user_result_ques`
--
ALTER TABLE `user_result_ques`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `result_history` (`result_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `email_verify`
--
ALTER TABLE `email_verify`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game_pic`
--
ALTER TABLE `game_pic`
  MODIFY `pic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `learn_mat`
--
ALTER TABLE `learn_mat`
  MODIFY `mat_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mat_content`
--
ALTER TABLE `mat_content`
  MODIFY `content_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `quiz_question`
--
ALTER TABLE `quiz_question`
  MODIFY `ques_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_pic`
--
ALTER TABLE `user_pic`
  MODIFY `userpic_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_result`
--
ALTER TABLE `user_result`
  MODIFY `result_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_result_ques`
--
ALTER TABLE `user_result_ques`
  MODIFY `history_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_pic`
--
ALTER TABLE `user_pic`
  ADD CONSTRAINT `pic` FOREIGN KEY (`pic_id`) REFERENCES `game_pic` (`pic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_result`
--
ALTER TABLE `user_result`
  ADD CONSTRAINT `user_result` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_result_ques`
--
ALTER TABLE `user_result_ques`
  ADD CONSTRAINT `result_history` FOREIGN KEY (`result_id`) REFERENCES `user_result` (`result_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
