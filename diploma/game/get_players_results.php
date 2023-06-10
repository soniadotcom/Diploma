<?php include "../includes/db.php"; ?>
<?php include "../includes/functions.php"; ?>

<?php 
// Отримання game_id з AJAX-запиту
$game_id = $_GET['game_id'];

// Запит для отримання результатів гравців
$query = "SELECT answer_player_id, SUM(answer_is_correct) AS score
          FROM answers
          WHERE answer_game_id = ? 
            AND (answer_player_id, answer_question_id, answer_time) IN (
              SELECT answer_player_id, answer_question_id, MAX(answer_time)
              FROM answers AS a
              WHERE a.answer_game_id = answers.answer_game_id
              GROUP BY a.answer_player_id, a.answer_question_id
            )
          GROUP BY answer_player_id
          ORDER BY score DESC";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Масив для зберігання результатів гравців
$playersResults = array();

// Отримання результатів гравців з результатів запиту
while ($row = mysqli_fetch_assoc($result)) {
    $player_id = $row['answer_player_id'];
    $score = $row['score'];

    //$playerUsername = getPlayerUsernameById($player_id);
    $playerUsername = getPlayerUsernameById($player_id);

    // Збереження результатів гравця в масиві
    $playersResults[] = array(
        'playerUsername' => $playerUsername,
        'score' => $score
    );
}

// Повернення результатів гравців у форматі JSON
echo json_encode($playersResults);

mysqli_stmt_close($stmt);
?>
