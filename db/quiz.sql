-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2023 at 01:50 AM
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
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `answer_game_id` int(11) NOT NULL,
  `answer_question_id` int(11) NOT NULL,
  `answer_player_id` int(11) NOT NULL,
  `answer_selected` varchar(64) NOT NULL,
  `answer_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `answer_is_correct` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `answer_game_id`, `answer_question_id`, `answer_player_id`, `answer_selected`, `answer_time`, `answer_is_correct`) VALUES
(1987, 10, 30, 14, 'Риба-меч', '2023-06-05 18:22:07', 1),
(1988, 10, 30, 16, 'Лосось', '2023-06-05 18:22:09', 0),
(1989, 10, 30, 16, 'Риба-меч', '2023-06-05 18:22:13', 1),
(1990, 10, 31, 16, 'Китова акула', '2023-06-05 18:22:25', 1),
(1991, 10, 31, 14, 'Гігантська морська щука', '2023-06-05 18:22:25', 0),
(1992, 10, 31, 14, 'Китова акула', '2023-06-05 18:22:30', 1),
(1993, 10, 31, 14, 'Гололоб', '2023-06-05 18:22:31', 0),
(1994, 10, 32, 16, 'Піранья', '2023-06-05 18:22:45', 1),
(1995, 10, 32, 16, 'Акула', '2023-06-05 18:22:46', 0),
(1996, 10, 32, 14, 'Піранья', '2023-06-05 18:22:46', 1),
(1997, 10, 33, 16, 'Судак', '2023-06-05 18:23:02', 0),
(1998, 10, 33, 14, 'Сом', '2023-06-05 18:23:03', 0),
(1999, 10, 34, 16, 'Лосось', '2023-06-05 18:23:26', 1),
(2000, 10, 34, 14, 'Підкамінь', '2023-06-05 18:23:28', 0),
(2001, 10, 34, 14, 'Лосось', '2023-06-05 18:23:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_status` varchar(8) NOT NULL,
  `game_max_players` int(2) NOT NULL,
  `game_password` varchar(8) NOT NULL,
  `game_quiz_id` int(11) NOT NULL,
  `game_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `game_state` varchar(16) NOT NULL DEFAULT 'waiting',
  `game_created_by` int(11) NOT NULL,
  `game_question_number` int(3) NOT NULL DEFAULT 1,
  `game_question_start` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`game_id`, `game_status`, `game_max_players`, `game_password`, `game_quiz_id`, `game_created_at`, `game_state`, `game_created_by`, `game_question_number`, `game_question_start`) VALUES
(1, 'public', 3, '74KE', 5, '2023-06-02 00:12:43', 'waiting', 0, 0, '2023-06-04 17:26:59'),
(2, 'public', 7, 'LMBO8W', 2, '2023-06-03 11:25:38', 'in progress', 0, 1, '2023-06-03 21:03:20'),
(3, 'public', 2, 'UYQA51', 6, '2023-05-28 04:20:53', 'waiting', 0, 0, '2023-06-03 21:03:20'),
(4, 'public', 3, 'DRAUJD', 6, '2023-05-30 21:11:55', 'in progress', 0, 0, '2023-06-03 21:03:20'),
(5, 'public', 10, '6767GP', 1, '2023-06-03 11:27:15', 'waiting', 0, 0, '2023-06-03 21:03:20'),
(6, 'public', 9, 'MEWCW5', 7, '2023-06-03 12:49:28', 'score', 14, 0, '2023-06-04 17:24:37'),
(7, 'private', 5, 'DAT8ES', 8, '2023-05-28 17:37:57', 'waiting', 0, 0, '2023-06-03 21:03:20'),
(8, 'public', 9, 'DHWT2I', 9, '2023-05-29 22:32:32', 'waiting', 0, 0, '2023-06-03 21:03:20'),
(9, 'private', 4, 'RRQ8IV', 2, '2023-06-03 16:22:15', 'in progress', 14, 1, '2023-06-03 21:03:20'),
(10, 'public', 3, '8GR3CZ', 10, '2023-06-04 13:57:01', 'score', 14, 0, '2023-06-05 21:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `player_username` varchar(32) NOT NULL,
  `player_password` varchar(64) NOT NULL,
  `player_bio` varchar(255) DEFAULT NULL,
  `player_email` varchar(64) DEFAULT NULL,
  `player_image` text DEFAULT NULL,
  `player_language` varchar(3) NOT NULL DEFAULT 'uk',
  `player_role` varchar(16) NOT NULL DEFAULT 'user',
  `player_game_id` int(11) DEFAULT NULL,
  `player_wins` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `player_username`, `player_password`, `player_bio`, `player_email`, `player_image`, `player_language`, `player_role`, `player_game_id`, `player_wins`) VALUES
(14, 'user1', '$2y$10$AKjvcKaFlsbVMcItNoS6ouLHgdHiGYAfGwCTVZRSb3pV7mDx47Sqi', '', 'user1@gmail.com', NULL, 'uk', 'user', 0, 1),
(15, 'user2', '$2y$10$iI3h03RpqZLjDKdv82A0Qe6zDlhmPx594KlVtH9RAZthMzg/s4hIC', NULL, NULL, NULL, 'uk', 'user', 0, 0),
(16, 'user3', '$2y$10$J7FIhw8V5AaAm8NRuuFwEOuWDdCiNGq79idvynLqSCIU05Mx3WAXO', NULL, NULL, NULL, 'uk', 'user', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_content` text NOT NULL,
  `question_answer` varchar(64) NOT NULL,
  `question_option2` varchar(64) NOT NULL,
  `question_option3` varchar(64) NOT NULL,
  `question_option4` varchar(64) NOT NULL,
  `question_difficulty` varchar(16) NOT NULL,
  `question_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `question_created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_content`, `question_answer`, `question_option2`, `question_option3`, `question_option4`, `question_difficulty`, `question_created_at`, `question_created_by`) VALUES
(30, 'Яка риба вважається найшвидшою в світі?', 'Риба-меч', 'Тунець', 'Карась', 'Лосось', 'easy', '2023-06-04 13:55:35', 14),
(31, 'Яка найбільша риба на планеті?', 'Китова акула', 'Гололоб', 'Гігантська морська щука', 'Біла акула', 'medium', '2023-06-04 13:55:35', 14),
(32, 'Який вид риби має найбільші зуби?', 'Піранья', 'Сом', 'Акула', 'Баракуда', 'medium', '2023-06-04 13:55:35', 14),
(33, 'Якій рибі належить рекорд за найбільший зловлений екземпляр в історії?', 'Осетр', 'Судак', 'Сом', 'Карп', 'hard', '2023-06-04 13:55:35', 14),
(34, 'Який вид риб має найдовший міграційний маршрут?', 'Лосось', 'Тунець', 'Підкамінь', 'Американський лящ', 'hard', '2023-06-04 13:55:35', 14);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `quiz_title` varchar(64) NOT NULL,
  `quiz_description` varchar(255) NOT NULL,
  `quiz_status` varchar(8) NOT NULL,
  `quiz_image` text DEFAULT NULL,
  `quiz_created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quiz_created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `quiz_title`, `quiz_description`, `quiz_status`, `quiz_image`, `quiz_created_at`, `quiz_created_by`) VALUES
(10, 'Риби', 'П\'ять складних і цікавих запитань про рибу', 'public', NULL, '2023-06-04 13:43:44', 14);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes_questions`
--

CREATE TABLE `quizzes_questions` (
  `quiz_id` int(11) DEFAULT NULL,
  `question_id` int(11) NOT NULL,
  `question_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes_questions`
--

INSERT INTO `quizzes_questions` (`quiz_id`, `question_id`, `question_order`) VALUES
(10, 30, 1),
(10, 31, 2),
(10, 32, 3),
(10, 33, 4),
(10, 34, 5);

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `translation_id` int(11) NOT NULL,
  `translation_key` varchar(255) NOT NULL,
  `translation_lang_code` varchar(3) NOT NULL,
  `translation_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`translation_id`, `translation_key`, `translation_lang_code`, `translation_text`) VALUES
(1, 'fill_required_fields', 'en', 'Please fill in all required fields'),
(2, 'error_inserting_record', 'en', 'Error inserting record'),
(3, 'wrong_username_password', 'en', 'Wrong username or password'),
(4, 'no_access_message', 'en', 'You don\'t have access. Log in or <a class=\'error-link\' href=\'register.php\'>Register</a>.'),
(5, 'no_access_game', 'en', 'You don\'t have access to this game.'),
(6, 'quit_game_success', 'en', 'You have successfully quit the game.'),
(7, 'join_game_failed', 'en', 'Failed to join game. All seats are already taken.'),
(8, 'enter_code', 'en', 'Enter code'),
(9, 'join_game_button', 'en', 'Join Game'),
(10, 'create_game_button', 'en', 'Create Game'),
(11, 'create_quiz_button', 'en', 'Create Quiz'),
(12, 'error_selecting_record1', 'en', 'Error selecting record 1'),
(13, 'error_selecting_record2', 'en', 'Error selecting record 2'),
(14, 'profile_updated_success', 'en', 'Your profile has been successfully updated'),
(15, 'change_avatar', 'en', 'Change avatar'),
(16, 'username_label', 'en', 'username'),
(17, 'new_password_label', 'en', 'new password'),
(18, 'bio_label', 'en', 'bio'),
(19, 'email_label', 'en', 'email'),
(20, 'menu_label', 'en', 'Menu'),
(21, 'logout_label', 'en', 'Log out'),
(22, 'fill_required_fields', 'uk', 'Будь ласка, заповніть всі обов\'язкові поля'),
(23, 'error_inserting_record', 'uk', 'Помилка при додаванні запису'),
(24, 'wrong_username_password', 'uk', 'Неправильне ім\'я користувача або пароль'),
(25, 'no_access_message', 'uk', 'У вас немає доступу. Увійдіть або <a class=\'error-link\' href=\'register.php\'>зареєструйтесь</a>.'),
(26, 'no_access_game', 'uk', 'У вас немає доступу до цієї гри.'),
(27, 'quit_game_success', 'uk', 'Ви успішно покинули гру.'),
(28, 'join_game_failed', 'uk', 'Не вдалося приєднатися до гри. Усі місця вже зайняті.'),
(29, 'enter_code', 'uk', 'Введіть код'),
(30, 'join_game_button', 'uk', 'Приєднатися до гри'),
(31, 'create_game_button', 'uk', 'Створити гру'),
(32, 'create_quiz_button', 'uk', 'Створити вікторину'),
(33, 'error_selecting_record1', 'uk', 'Помилка при виборі запису 1'),
(34, 'error_selecting_record2', 'uk', 'Помилка при виборі запису 2'),
(35, 'profile_updated_success', 'uk', 'Ваш профіль успішно оновлено'),
(36, 'change_avatar', 'uk', 'Змінити аватар'),
(37, 'username_label', 'uk', 'Ім\'я користувача'),
(38, 'new_password_label', 'uk', 'Новий пароль'),
(39, 'bio_label', 'uk', 'Біографія'),
(40, 'email_label', 'uk', 'Електронна пошта'),
(41, 'menu_label', 'uk', 'Меню'),
(42, 'logout_label', 'uk', 'Вийти'),
(43, 'username', 'en', 'Username'),
(44, 'password', 'en', 'Password'),
(45, 'start_button', 'en', 'Start'),
(46, 'or_register', 'en', 'or register'),
(47, 'save_button', 'en', 'Save'),
(48, 'username', 'uk', 'Ім\'я користувача'),
(49, 'password', 'uk', 'Пароль'),
(50, 'start_button', 'uk', 'Почати'),
(51, 'or_register', 'uk', 'зареєструватися'),
(52, 'save_button', 'uk', 'Зберегти'),
(53, 'navigation_profile', 'en', 'Profile'),
(54, 'navigation_settings', 'en', 'Settings'),
(55, 'navigation_rating', 'en', 'Rating'),
(56, 'rating_players', 'en', 'Players'),
(57, 'rating_wins', 'en', 'Wins'),
(58, 'game_not_exist', 'en', 'This game does not exist. Go to the <a class=\'error-link\' href=\'menu.php\'>Menu</a> and try again.'),
(59, 'game_quit', 'en', 'Quit Game'),
(60, 'game_not_active', 'en', 'This game is not active'),
(62, 'create_quiz_creating_quiz', 'en', 'Creating Quiz'),
(63, 'create_quiz_change_image', 'en', 'change image'),
(64, 'create_quiz_title', 'en', 'title'),
(65, 'create_quiz_status', 'en', 'status'),
(66, 'create_quiz_private', 'en', 'private'),
(67, 'create_quiz_public', 'en', 'public'),
(68, 'create_quiz_description', 'en', 'description'),
(69, 'create_quiz_next', 'en', 'Next'),
(70, 'create_game_quiz_created', 'en', 'The quiz has been successfully created.<br> You can create new game now.'),
(71, 'create_game_fill_required_fields', 'en', 'Please fill in all required fields. If you can\'t choose any quiz, you have to <a class=\'error-link\' href=\'create_quiz.php\'>create</a> it first.'),
(72, 'create_game_creating_game', 'en', 'Creating Game'),
(73, 'create_game_game_status', 'en', 'Game status'),
(74, 'create_game_max_players', 'en', 'Max Players'),
(75, 'create_game_choose_quiz', 'en', 'Choose Quiz'),
(76, 'create_game_create', 'en', 'Create'),
(77, 'add_questions_error_query', 'en', 'Error preparing the query'),
(78, 'add_questions_record_not_found', 'en', 'Record with this quiz_id not found'),
(79, 'add_questions_adding_questions', 'en', 'Adding Questions'),
(80, 'add_questions_question', 'en', 'Question'),
(81, 'add_questions_delete_question', 'en', 'Delete question'),
(82, 'add_questions_options', 'en', 'Options'),
(83, 'add_questions_answer', 'en', 'Answer'),
(84, 'add_questions_option', 'en', 'Option'),
(85, 'add_questions_hard', 'en', 'Hard'),
(86, 'add_questions_medium', 'en', 'Medium'),
(87, 'add_questions_easy', 'en', 'Easy'),
(88, 'add_questions_add_question', 'en', 'Add question'),
(89, 'add_questions_create_quiz', 'en', 'Create Quiz'),
(90, 'navigation_profile', 'uk', 'Профіль'),
(91, 'navigation_settings', 'uk', 'Налаштування'),
(92, 'navigation_rating', 'uk', 'Рейтинг'),
(93, 'rating_players', 'uk', 'Гравці'),
(94, 'rating_wins', 'uk', 'Перемоги'),
(95, 'game_not_exist', 'uk', 'Гри не існує. Перейдіть до <a class=\'error-link\' href=\'menu.php\'>Меню</a> і спробуйте знову.'),
(96, 'game_quit', 'uk', 'Покинути гру'),
(97, 'game_not_active', 'uk', 'Ця гра не активна'),
(99, 'create_quiz_creating_quiz', 'uk', 'Створення вікторини'),
(100, 'create_quiz_change_image', 'uk', 'змінити зображення'),
(101, 'create_quiz_title', 'uk', 'назва'),
(102, 'create_quiz_status', 'uk', 'статус'),
(103, 'create_quiz_private', 'uk', 'приватна'),
(104, 'create_quiz_public', 'uk', 'публічна'),
(105, 'create_quiz_description', 'uk', 'опис'),
(106, 'create_quiz_next', 'uk', 'Далі'),
(107, 'create_game_quiz_created', 'uk', 'Вікторину успішно створено.<br> Тепер ви можете створити нову гру.'),
(108, 'create_game_fill_required_fields', 'uk', 'Будь ласка, заповніть всі обов\'язкові поля. Якщо ви не можете вибрати жодну вікторину, спочатку ви повинні <a class=\'error-link\' href=\'create_quiz.php\'>створити</a> її.'),
(109, 'create_game_creating_game', 'uk', 'Створення гри'),
(110, 'create_game_game_status', 'uk', 'Статус гри'),
(111, 'create_game_max_players', 'uk', 'Максимум гравців'),
(112, 'create_game_choose_quiz', 'uk', 'Оберіть вікторину'),
(113, 'create_game_create', 'uk', 'Створити'),
(114, 'add_questions_error_query', 'uk', 'Помилка підготовки запиту'),
(115, 'add_questions_adding_questions', 'uk', 'Додавання запитань'),
(116, 'add_questions_question', 'uk', 'Запитання'),
(117, 'add_questions_delete_question', 'uk', 'Видалити питання'),
(118, 'add_questions_options', 'uk', 'Варіанти'),
(119, 'add_questions_answer', 'uk', 'Відповідь'),
(120, 'add_questions_option', 'uk', 'Варіант'),
(121, 'add_questions_hard', 'uk', 'Важкий'),
(122, 'add_questions_medium', 'uk', 'Середній'),
(123, 'add_questions_easy', 'uk', 'Легкий'),
(124, 'add_questions_add_question', 'uk', 'Додати питання'),
(125, 'add_questions_create_quiz', 'uk', 'Створити вікторину'),
(126, 'error_weak_password', 'en', 'Your password is too weak.'),
(127, 'error_weak_password', 'uk', 'Ваш пароль занадто слабкий.'),
(128, 'register', 'uk', 'Зареєструватися'),
(129, 'register', 'en', 'Register'),
(130, 'error_username_not_unique', 'en', 'Your username must be unique.'),
(131, 'error_username_not_unique', 'uk', 'Ваше ім\'я користувача повинне бути унікальним');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD UNIQUE KEY `game_password` (`game_password`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD UNIQUE KEY `player_username` (`player_username`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`translation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2002;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `translation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
