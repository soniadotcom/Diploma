<?php
include '../includes/db.php';

$game_id = $_GET['game_id'];

// Отримання актуального стану гри з бази даних
$query = "SELECT game_state, game_question_number, CONVERT_TZ(game_question_start, @@session.time_zone, '+02:00') AS game_question_start FROM games WHERE game_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$game_state = $row['game_state'];
$game_question_number = $row['game_question_number'];
$game_question_start = $row['game_question_start'];

// Знайти час, що залишився на відповідь на запитання
$currentTime = time();
$startTime = strtotime($game_question_start);
$endTime = $startTime + 16;
$remainingTime = $endTime - $currentTime;

// Формування відповіді у форматі JSON з отриманим залишеним часом
$response = [
  'game_state' => $game_state,
  'game_question_number' => $game_question_number,
  'remainingTime' => $remainingTime,
  'start_time' => $startTime
];


// Закриття підготовленого запиту та з'єднання з базою даних
mysqli_stmt_close($stmt);
mysqli_close($connection);

// Виведення стану гри у форматі JSON
echo json_encode($response);

?>






