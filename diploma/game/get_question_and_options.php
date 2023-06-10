<?php include "../includes/db.php" ?>

<?php
    // Отримання параметрів game_id і question_order з URL запиту
    $gameId = $_GET['game_id'];
    $next_question_index = $_GET['next_question_index'];

    // Підготовка та виконання запиту з використанням підготовлених виразів
    $stmt = $connection->prepare("SELECT q.question_id, q.question_content, q.question_answer, q.question_option2, q.question_option3, q.question_option4, q.question_difficulty, q.question_created_at, q.question_created_by, qq.question_order
            FROM quizzes_questions qq
            JOIN questions q ON qq.question_id = q.question_id
            WHERE qq.quiz_id = (SELECT game_quiz_id FROM games WHERE game_id = ?)
            AND qq.question_order = ?
            LIMIT 1");
    $stmt->bind_param("ii", $gameId, $next_question_index);
    $stmt->execute();
    $result = $stmt->get_result();

    // Перевірка результату запиту та формування відповіді у форматі JSON
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $question = array(
            'question_id' => $row['question_id'],
            'question_content' => $row['question_content'],
            'question_answer' => $row['question_answer'],
            'question_option2' => $row['question_option2'],
            'question_option3' => $row['question_option3'],
            'question_option4' => $row['question_option4'],
            'question_difficulty' => $row['question_difficulty'],
            'question_created_at' => $row['question_created_at'],
            'question_created_by' => $row['question_created_by'],
            'question_order' => $row['question_order']
        );

        echo json_encode($question);
    } else {
        echo json_encode(null);
    }

    $stmt->close();
?>
