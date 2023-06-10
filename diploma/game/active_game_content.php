<?php  include "../includes/session_start.php"; ?>
<?php  include "../includes/db.php"; ?>
<?php include "../includes/functions.php" ?>

<section class="active_game">
    <div class="container">
        <div class="row align-items-center">
            <?php
            $player_id = getPlayerIdByUsername($_SESSION['player_username']);
            if($_GET['game_created_by'] == $player_id) {

            ?>

                    
            <div class="col-12 text-center">
                <form action="" method="post">
                    <input type="button" name="stop_game" class="btn cta" value="Stop Game" onclick="stopGame();">
                </form>
            </div>

            <?php

            }
            ?>
            <div class="col-12">
                <h2>Quiz: <?php echo $_GET['quiz_title'] ?></h2>
                <h2>Question: <?php echo $_GET['next_question_index'] ?></h2>
                <p>Time left: <span id="timer"></span></p>
            </div>
            <div class="col-12 col-xl-7 order-xl-2">
                <div class="question_block">
                    <h2 id="question"></h2>
                </div>
            </div>
            <div class="col-12 col-xl-5 order-xl-1">
                <div class="options">
                    <div class="option-buttons">
                        <div class="circle" onclick="selectOption(0)">A</div>
                        <div class="circle" onclick="selectOption(1)">B</div>
                        <div class="circle" onclick="selectOption(2)">C</div>
                        <div class="circle" onclick="selectOption(3)">D</div>
                        <input class="selectedOption" type="hidden" name="option" value="">
                    </div>
                    <div id="options">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
$(document).ready(function() {
    // Function to retrieve questions and answer options from the server
    function getQuestionAndOptions() {
        $.ajax({
            url: 'game/get_question_and_options.php?next_question_index=<?php echo $_GET['next_question_index']; ?>&game_id=<?php echo $_GET['game_id']; ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.question_content) {
                    //console.log(response.question_content);
                    // Оновити вміст запитання
                    $('#question').text(response.question_content);

                    // Вивести варіанти відповідей
                    var optionsContainer = $('#options');
                    optionsContainer.empty();
                    

                    var options = []; // Оголошуємо порожній масив варіантів

                    // Додати варіанти відповідей
                    for (var i = 2; i <= 4; i++) {
                        var option = response['question_option' + i];
                        if (option) {
                            options.push(option); // Додаємо варіант до масиву
                        }
                    }

                    options.sort(function() {
                        return 0.5 - Math.random(); // Випадкове змішування елементів масиву
                    });

                    // Додати перемішані варіанти в HTML-контейнер
                    for (var j = 0; j < options.length; j++) {
                        var optionHtml = '<div class="option">' + options[j] + '</div>';
                        optionsContainer.append(optionHtml);
                    }

                    // Додати правильну відповідь
                    var answerHtml = '<div class="option">' + response.question_answer + '</div>';
                    optionsContainer.append(answerHtml);
                }
            },
            error: function() {
                console.log('Error occurred while retrieving questions and answer options.');
            }
        });
    }
    

    function shuffleArray(array) {
        var shuffledArray = array.slice();
        var currentIndex = shuffledArray.length;
        var temporaryValue, randomIndex;

        while (currentIndex !== 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;
            temporaryValue = shuffledArray[currentIndex];
            shuffledArray[currentIndex] = shuffledArray[randomIndex];
            shuffledArray[randomIndex] = temporaryValue;
        }

        return shuffledArray;
    }


    // Call the getQuestionAndOptions() function on page load
    getQuestionAndOptions();
});


</script>