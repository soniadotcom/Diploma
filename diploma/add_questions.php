<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>


<?php


if(isset($_GET['quiz_id'])) {
    // Перевірка чи користувач може додавати запитання в цю вікторину
    $quiz_id = $_GET['quiz_id'];

    $query = "SELECT quiz_created_by FROM quizzes WHERE quiz_id = ?";
    $stmt = mysqli_prepare($connection, $query);

    if (!$stmt) {
        echoErrorMsg("add_questions_error_query");
        echo mysqli_error($connection);
    }

    mysqli_stmt_bind_param($stmt, "i", $quiz_id);

    if (!mysqli_stmt_execute($stmt)) {
        echoErrorMsg("error_inserting_record");
        echo mysqli_stmt_error($stmt);
    }

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if($row['quiz_created_by'] !== getPlayerIdByUsername($_SESSION['player_username'])) {
            header("Location: menu.php");
        }
    } else {
        echoErrorMsg("Record with quiz_id = $quiz_id not found");
    }

    mysqli_stmt_close($stmt);
}


if(isset($_POST["add_questions"])) {

    $questions = $_POST["question"];
    $answers = $_POST["answer"];
    $options2 = $_POST["option2"];
    $options3 = $_POST["option3"];
    $options4 = $_POST["option4"];
    $difficulties = $_POST["difficulty"];
    
    // Додавання запитань у таблицю
    $query2 = "INSERT INTO questions (question_content, question_answer, question_option2, question_option3, question_option4, question_difficulty, question_created_at, 	question_created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Підготовка запиту
    $stmt2 = mysqli_prepare($connection, $query2);

    if (!$stmt2) {
        echoErrorMsg("add_questions_error_query");
        echo mysqli_error($connection);
    }

    // Зв'язування параметрів зі змінними
    mysqli_stmt_bind_param($stmt2, "ssssssss", $question, $answer, $option2, $option3, $option4, $difficulty, $created_at, $created_by);

    // Get player_id by player_username
    $player_id = getPlayerIdByUsername($_SESSION['player_username']);



    // Додавання у id запитань та id вікторин у таблицю
    $query3 = "INSERT INTO quizzes_questions (quiz_id, question_id, question_order) VALUES (?, ?, ?)";

    // Підготовка запиту
    $stmt3 = mysqli_prepare($connection, $query3);

    if (!$stmt3) {
        echoErrorMsg("add_questions_error_query");
        echo mysqli_error($connection);
    }

    // Зв'язування параметрів зі змінними
    mysqli_stmt_bind_param($stmt3, "iii", $quiz_id, $question_id, $question_order);



    // Ітеруємося по масиву даних та виконуємо вставку
    for ($i = 0; $i < count($questions); $i++) {
        $question = $questions[$i];
        $answer = $answers[$i];
        $option2 = $options2[$i];
        $option3 = $options3[$i];
        $option4 = $options4[$i];
        $difficulty = $difficulties[$i];
        $created_at = date('Y-m-d H:i:s');
        $created_by = $player_id;

        // Виконання запиту
        if (!mysqli_stmt_execute($stmt2)) {
            echoErrorMsg("error_inserting_record");
            echo mysqli_stmt_error($stmt2);
        }

        $quiz_id = $_GET['quiz_id'];
        $question_id = mysqli_insert_id($connection);
        $question_order = $i+1;


        // Виконання запиту
        if (!mysqli_stmt_execute($stmt3)) {
            echoErrorMsg("error_inserting_record");
            echo mysqli_stmt_error($stmt3);
        }

    }

    // Закриття підготовленого запиту
    mysqli_stmt_close($stmt2);
    mysqli_stmt_close($stmt3);

    header("Location: create_game.php?success=true");
}



?>

<form role="form" action="" method="post" class="form-group" autocomplete="off">
    
<section class="add_questions">
    <div class="container" id="question_block_parent">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="add_questions__title"><?php echo getTranslation("") ?></h2>
            </div>
        </div>
        <div class="row" id="question_block" class="question_block question_block_1">
            <div class="col-12 col-xl-6 offset-xl-3">
                <div class="form-group question">
                    <label for="question"><?php echo getTranslation("add_questions_question") ?> #1</label>
                    <input type="submit" name="delete_question" class="btn cta cta__delete d-xl-none" size="10" value="x" title="<?php echo getTranslation("add_questions_delete_question") ?>" class="hint">
                </div>
                <textarea type="text" name="question[]" class="form-control question_input" placeholder="" rows="3" cols="50"></textarea>
            </div>
            <div class="col-12 col-xl-1 d-none d-xl-flex">
                <input type="submit" name="delete_question" class="btn cta cta__delete" value="x" title="<?php echo getTranslation("add_questions_delete_question") ?>" class="hint">
            </div>
            <div class="col-12 col-xl-6 offset-xl-3">
                <div class="form-group">
                    <label for="description"><?php echo getTranslation("add_questions_options") ?></label>
                    <div class="row">
                        <div class="col-12 col-xl-6">
                            <input type="text" name="answer[]" class="form-control" placeholder="<?php echo getTranslation("add_questions_answer") ?>">
                        </div>
                        <div class="col-12 col-xl-6">
                            <input type="text" name="option2[]" class="form-control" placeholder="<?php echo getTranslation("add_questions_option") ?> 2">
                        </div>
                        <div class="col-12 col-xl-6">
                            <input type="text" name="option3[]" class="form-control last-xl" placeholder="<?php echo getTranslation("add_questions_option") ?> 3">
                        </div>
                        <div class="col-12 col-xl-6">
                            <input type="text" name="option4[]" class="form-control last" placeholder="<?php echo getTranslation("add_questions_option") ?> 4">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-1">
                <div class="choose_difficulty">
                    <div class="circle" id="hard" onclick="selectDifficulty('hard')" title="<?php echo getTranslation("add_questions_hard") ?>" class="hint"></div>
                    <div class="circle" id="medium" onclick="selectDifficulty('medium')" title="<?php echo getTranslation("add_questions_medium") ?>" class="hint"></div>
                    <div class="circle selected" id="easy" onclick="selectDifficulty('easy')" title="<?php echo getTranslation("add_questions_easy") ?>" class="hint"></div>
                    <input class="selectedDifficulty" type="hidden" name="difficulty[]" value="easy">
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <input id="cloneButton" class="btn cta cta__add" value="+" title="<?php echo getTranslation("add_questions_add_question") ?>" class="hint" readonly>
            </div>
            <div class="col-12 text-center">
                <input type="submit" name="add_questions" class="btn cta" value="<?php echo getTranslation("add_questions_create_quiz") ?>">
            </div>
        </div>
    </div>
</section>

</form>


<script>

    function selectDifficulty(difficulty) {
        var clickedCircle = event.target; // Отримуємо елемент, на якому відбувся клік

        var questionBlock = clickedCircle.closest('.question_block'); // Знаходимо батьківський елемент з класом .question_block
        var circles = questionBlock.getElementsByClassName('circle'); // Отримуємо всі елементи .circle в межах блоку

        for (var i = 0; i < circles.length; i++) {
            circles[i].classList.remove('selected');
        }

        clickedCircle.classList.add('selected');

        var selectedDifficulty = questionBlock.getElementsByClassName('selectedDifficulty')[0];
        selectedDifficulty.value = difficulty;
    }
    

</script>


<script>
  var parentElement = document.getElementById('question_block_parent');
  var originalElement = document.getElementById('question_block');
  var cloneButton = document.getElementById('cloneButton');
  var counter = 2; // Лічильник для номерації елементів

  
  originalElement.classList.add('question_block');
  originalElement.classList.add('question_block_1');

  cloneButton.addEventListener('click', function() {
    // Клонування оригінального елемента
    var clonedElement = originalElement.cloneNode(true);

    // Додавання класу .question_block_N до клонованого елемента
    clonedElement.classList.remove('question_block_1');
    clonedElement.classList.add('question_block_' + counter);
    clonedElement.removeAttribute('id');

    // Отримання всіх елементів з класом .form-control у клонованому елементі
    var formControlElements = clonedElement.querySelectorAll('.form-control');

    // Видалення значення value у кожному елементі з класом .form-control
    formControlElements.forEach(function(element) {
      element.value = '';
    });

    // Додавання клонованого елемента після оригінального елемента
    parentElement.appendChild(clonedElement);

    // Збільшення лічильника для наступного клонованого елемента
    counter++;
  });
</script>


<?php include "includes/footer.php" ?>