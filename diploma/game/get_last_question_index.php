<?php
include '../includes/db.php';

$game_id = $_GET['game_id'];

// Отримання актуального стану гри з бази даних
$query = "SELECT game_quiz_id FROM games WHERE game_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$game_quiz_id = $row['game_quiz_id'];



$query = "SELECT MAX(question_order) AS last_question_index FROM quizzes_questions WHERE quiz_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$last_question_index = $row['last_question_index'];


$response = [
  'last_question_index' => $last_question_index,
];


// Закриття підготовленого запиту та з'єднання з базою даних
mysqli_stmt_close($stmt);
mysqli_close($connection);

// Виведення стану гри у форматі JSON
echo json_encode($response);

?>






