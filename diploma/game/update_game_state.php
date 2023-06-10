<?php
include '../includes/db.php';

// Перевірка, чи отримано POST-дані
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримання даних з POST-запиту
    $game_id = $_POST['game_id'];
    $new_state = $_POST['new_state'];
    $next_question_index = $_POST['next_question_index'];


    switch($new_state) {
        case 'score':
            // Запит для отримання переможця
            $query =   "SELECT answer_player_id, SUM(answer_is_correct) AS score
            FROM (
                SELECT a.answer_player_id, a.answer_is_correct,
                    ROW_NUMBER() OVER (PARTITION BY a.answer_player_id, a.answer_question_id ORDER BY a.answer_time DESC) AS rn
                FROM answers AS a
                WHERE a.answer_game_id = ?
            ) AS subquery
            WHERE rn = 1
            GROUP BY answer_player_id
            HAVING score = (
                SELECT MAX(score)
                FROM (
                    SELECT SUM(answer_is_correct) AS score
                    FROM (
                        SELECT a.answer_player_id, a.answer_is_correct,
                            ROW_NUMBER() OVER (PARTITION BY a.answer_player_id, a.answer_question_id ORDER BY a.answer_time DESC) AS rn
                        FROM answers AS a
                        WHERE a.answer_game_id = ?
                    ) AS subquery
                    WHERE rn = 1
                    GROUP BY answer_player_id
                ) AS scores
            )
            ORDER BY score DESC";

            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "ii", $game_id, $game_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $winners = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $winners[] = $row['answer_player_id'];
            }

            mysqli_stmt_close($stmt);

            // Оновлення кількості перемог для кожного переможця
            foreach ($winners as $winner_id) {
                $query2 = "UPDATE players SET player_wins = player_wins + 1 WHERE player_id = ?";
                $stmt2 = mysqli_prepare($connection, $query2);
                mysqli_stmt_bind_param($stmt2, 'i', $winner_id);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
            }
            break;
        case 'waiting':
            // Оновлення кількості перемог переможця
            $query = "DELETE FROM answers WHERE answer_game_id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, 'i', $game_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;
    }


    // Оновлення стану гри в базі даних
    $query = "UPDATE games SET game_state = ?, game_question_number = ? WHERE game_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'sii', $new_state, $next_question_index, $game_id);
    
    // Виконання запиту
    if (mysqli_stmt_execute($stmt)) {
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'message' => 'Помилка при оновленні стану гри');
        echo json_encode($response);
    }

    mysqli_stmt_close($stmt);
}

?>
