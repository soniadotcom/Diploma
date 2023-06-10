<?php
include "../includes/db.php";

// Getting input data from AJAX request
$gameId = urldecode($_POST['gameId']);
$playerId = urldecode($_POST['playerId']);
$nextQuestionIndex = urldecode($_POST['nextQuestionIndex']);
$playerAnswer = urldecode($_POST['answer']);

// Initialize the statements array
$statements = [];

// Retrieve the correct answer from the 'questions' table
$query = "SELECT qq.question_id, qq.quiz_id, qq.question_order, q.question_answer
FROM games g
JOIN quizzes_questions qq ON g.game_quiz_id = qq.quiz_id
JOIN questions q ON qq.question_id = q.question_id
WHERE g.game_id = ?
  AND qq.question_order = ?";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ii", $gameId, $nextQuestionIndex);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the question and correct answer exist
if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $question_id = $row['question_id'];
  $correctAnswer = $row['question_answer'];

  // Perform answer validation and correctness check
  $isCorrect = ($playerAnswer === $correctAnswer);

  // Save the player's answer in the 'answers' table
  $insertQuery = "INSERT INTO answers (answer_game_id, answer_question_id, answer_player_id,  answer_selected, answer_is_correct) VALUES (?, ?, ?, ?, ?)";
  $insertStmt = mysqli_prepare($connection, $insertQuery);
  mysqli_stmt_bind_param($insertStmt, "iiisi", $gameId, $question_id, $playerId, $playerAnswer, $isCorrect);

  // Add the statement to the batch
  $statements[] = $insertStmt;
} else {
  // Handle the case when the question or correct answer is not found
  $isCorrect = false;
}

// Close the statement
mysqli_stmt_close($stmt);

// Execute all statements in the batch
foreach ($statements as $stmt) {
  mysqli_stmt_execute($stmt);
}

// Close the database connection
mysqli_close($connection);

// Prepare the response to send back to the client
$response = array(
  'isCorrect' => $isCorrect
);
echo json_encode($response);
?>
