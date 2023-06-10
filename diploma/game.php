<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>


<?php


if(isset($_GET['code'])) {

    $game_password = $_GET['code'];

    $query = "SELECT g.*, q.* FROM games g
    JOIN quizzes q ON g.game_quiz_id = q.quiz_id
    WHERE g.game_password = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $game_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row_count = mysqli_num_rows($result);
    
    if ($row_count > 0) {
        $game = mysqli_fetch_assoc($result);
    
        // Отримання даних з таблиці games
        $game_id = $game['game_id'];
        $game_status = $game['game_status'];
        $game_max_players = $game['game_max_players'];
        $game_password = $game['game_password'];
        $game_quiz_id = $game['game_quiz_id'];
        $game_created_at = $game['game_created_at'];
        $game_created_by = $game['game_created_by'];
        $game_state = $game['game_state'];
        $next_question_index = $game['game_question_number'];

        // Отримання даних з таблиці quizzes
        $quiz_id = $game['quiz_id'];
        $quiz_title = $game['quiz_title'];



        $game_id = getGameIdByPassword($game_password);

        // Отримання списку гравців
        $query = "SELECT player_id, player_username, player_bio, player_image, player_role FROM players WHERE player_game_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $game_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $players = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $players[] = $row;
        }
    
        // Перевірка наявності гравця з $_SESSION['player_username'] в масиві $players
        $isPlayerExist = false;
        foreach ($players as $player) {
            if ($player['player_username'] == $_SESSION['player_username']) {
                $isPlayerExist = true;
                break;
            }
        }
    
        if (!$isPlayerExist) {
            header("Location: menu.php?access=denied");
        }
    } else {
        echoErrorMsg("game_not_exist");
    }
    
    mysqli_stmt_close($stmt);
}

?>

<section class="game">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div id="game-state-container" style="color: white">
                </div>
            </div>
            <div class="col-12 text-center">
                <div id="game-content-container">
                </div>
            </div>
            <div class="col-12 text-center">
                <div id="player-list-container">
                </div>
            </div>
            <div class="col-12 text-center">
                <form action="" method="post">
                    <input type="submit" name="quit_game" class="btn btn-2 cta" value="<?php echo getTranslation("game_quit") ?>">
                </form>
            </div>
        </div>
    </div>
</section>


<?php

$player_id = getPlayerIdByUsername($_SESSION['player_username']);


if(isset($_POST['quit_game'])) {
    if(addPlayerToGame(0, $player_id)){
        header("Location: menu.php?quit_game=true");
    } else {
        
    }
}

?>



<script>

var prev_game_question_number = 0;
var game_question_number = 0;
var lastQuestionIndex = null;
var scoreBoardIsShown = false;

$(document).ready(function() {
    // Оновлення стану гри на основі отриманого значення з AJAX-запиту
    function updateGameState(gameState, game_question_number) {
        if(game_question_number == 0) {
            prev_game_question_number = 0;
        }
        switch (gameState) {
            case 'waiting':
                // Отримуємо вміст сторінки з вмістом waiting_room_content.php за допомогою AJAX
                $.ajax({
                    url: 'game/waiting_room_content.php',
                    method: 'GET',
                    dataType: 'html',
                    data: {
                        game_password: '<?php echo $game_password; ?>',
                        quiz_title: '<?php echo $quiz_title; ?>',
                        game_status: '<?php echo $game_status; ?>',
                        game_max_players: '<?php echo $game_max_players; ?>',
                        game_created_by: '<?php echo $game_created_by; ?>'
                    },
                    success: function(response) {
                        // Оновлюємо тільки вміст відповідного контейнера
                        $('#game-content-container').html(response);
                    },
                    error: function() {
                        console.log('Error occurred while updating game content.');
                    }
                });
                break;
            case 'in progress':
                if(remainingTime >= 0) {
                    updateRemainingTimeOnPage(remainingTime)
                } else {
                    if (game_question_number + 1 > lastQuestionIndex) {
                        showScoreBoard();
                    } else {
                        game_question_number++;
                        changeGameQuestion(game_question_number);
                    }
                }

                if(game_question_number == 0 || game_question_number == prev_game_question_number) {
                    break;
                }

                prev_game_question_number = game_question_number;

                $.ajax({
                    url: 'game/active_game_content.php',
                    method: 'GET',
                    dataType: 'html',
                    data: {
                        game_id: '<?php echo $game_id; ?>',
                        game_password: '<?php echo $game_password; ?>',
                        quiz_title: '<?php echo $quiz_title; ?>',
                        game_status: '<?php echo $game_status; ?>',
                        game_max_players: '<?php echo $game_max_players; ?>',
                        next_question_index: game_question_number,
                        game_created_by: '<?php echo $game_created_by; ?>'
                    },
                    success: function(response) {
                        // Оновлюємо тільки вміст відповідного контейнера
                        $('#game-content-container').html(response);
                    },
                    error: function() {
                        console.log('Error occurred while updating game content.');
                    }
                });
                break;
            case 'score':

                if(scoreBoardIsShown) {
                    break;
                }

                scoreBoardIsShown = true;

                // Отримуємо вміст сторінки з вмістом score_board_content.php за допомогою AJAX
                $.ajax({
                    url: 'game/score_board_content.php',
                    method: 'GET',
                    dataType: 'html',
                    data: {
                        game_id: '<?php echo $game_id; ?>',
                        game_password: '<?php echo $game_password; ?>',
                        quiz_title: '<?php echo $quiz_title; ?>',
                        game_status: '<?php echo $game_status; ?>',
                        game_max_players: '<?php echo $game_max_players; ?>',
                        next_question_index: game_question_number,
                        game_created_by: '<?php echo $game_created_by; ?>'
                    },
                    success: function(response) {
                        // Оновлюємо тільки вміст відповідного контейнера
                        $('#game-content-container').html(response);
                    },
                    error: function() {
                        console.log('Error occurred while updating game content.');
                    }
                });
                break;
            default:
                // Оновлюємо вміст відповідного контейнера з повідомленням про неактивну гру
                $('#game-content-container').html('<h2>This game is not active</h2>');
        }
    }

    // Функція для оновлення стану гри за допомогою AJAX
    function updateGameStateAjax() {
        $.ajax({
            url: 'game/get_game_state.php?game_id=<?php echo $game_id; ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Оновлення стану гри на основі відповіді з сервера
                var game_state = response.game_state;
                game_question_number = response.game_question_number;
                remainingTime = response.remainingTime;
                updateGameState(game_state, game_question_number);
            },
            error: function() {
                console.log('Помилка при оновленні стану гри.');
            }
        });
    }

    // Виклик функції updateGameStateAjax() кожні 3 секунди (змініть на потрібний вам інтервал)
    setInterval(updateGameStateAjax, 500);




    // Функція для отримання індексу останнього запитання вікторини за допомогою AJAX та обіцянки
    function getLastQuestionIndex() {
    return new Promise(function(resolve, reject) {
        $.ajax({
        url: 'game/get_last_question_index.php?game_id=<?php echo $game_id; ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            resolve(response.last_question_index);
        },
        error: function() {
            console.log('Помилка при отриманні індексу останнього запитання у вікторині.');
            reject(new Error('Помилка при отриманні індексу останнього запитання у вікторині.'));
        }
        });
    });
    }


    // Функція для отримання індексу останнього запитання вікторини за допомогою AJAX та обіцянки
    function getLastQuestionIndex() {
    return new Promise(function(resolve, reject) {
        $.ajax({
        url: 'game/get_last_question_index.php?game_id=<?php echo $game_id; ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            resolve(response.last_question_index);
        },
        error: function() {
            console.log('Помилка при отриманні індексу останнього запитання у вікторині.');
            reject(new Error('Помилка при отриманні індексу останнього запитання у вікторині.'));
        }
        });
    });
    }

    // Асинхронна функція для отримання значення lastQuestionIndex
    async function retrieveLastQuestionIndex() {
        try {
            lastQuestionIndex = await getLastQuestionIndex();
        } catch (error) {
            console.log(error);
            // Обробка помилки, якщо виникла.
        }
    }


    retrieveLastQuestionIndex();
});

</script>



<script>
    function selectOption(answer) {
        var options = document.getElementsByClassName('options')[0];
        var circles = options.getElementsByClassName('circle');

        var clickedCircle = circles[answer]; // Отримуємо елемент, на якому відбувся клік

        for (var i = 0; i < circles.length; i++) {
            circles[i].classList.remove('selected');
        }

        clickedCircle.classList.add('selected');

        var selectedOption = options.getElementsByClassName('selectedOption')[0];

        selectedOption.value = options.getElementsByClassName('option')[answer].innerHTML;

        sendAnswer(selectedOption.value);
    }

/*
    function getSelectedOption() {
        var options = document.getElementsByClassName('options')[0];
        var selectedOption = options.getElementsByClassName('selectedOption')[0].value;
        return selectedOption;
    }
*/

    var remainingTime = 0;


    function updateRemainingTimeOnPage(remainingTime) {
        var timerElement = document.getElementById("timer");
        if (timerElement) {
            if(remainingTime < 0) {
                //var selectedOption = getSelectedOption();
                //sendAnswer(selectedOption);
            } else {
                timerElement.innerHTML = remainingTime;
            }
        }
    }
    

    function sendAnswer(answer) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "game/submit_answer.php?cache=" + Date.now(), true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
            } else {

            }
        };
        var encodedAnswer = encodeURIComponent(answer);
        var data = "gameId=" + encodeURIComponent(<?php echo $game_id; ?>) + "&playerId=" + encodeURIComponent(<?php echo $player_id; ?>) + "&nextQuestionIndex=" + encodeURIComponent(game_question_number) + "&answer=" + encodedAnswer;

        xhr.send(data);
    }
</script>





<script>
function updatePlayerList() {
    $.ajax({
        url: 'game/get_players.php?game_id=<?php echo $game_id; ?>',
        method: 'GET',
        dataType: 'html',
        cache: false,
        success: function(response) {
            if ($('#player-list-container').html() !== response) {
                $('#player-list-container').html(response);
            }
        },
        error: function() {
            console.log('Error occurred while updating player list.');
        }
    });
}

updatePlayerList();

setInterval(updatePlayerList, 1000);

</script>




   
<script>

function startGame() {
    $.ajax({
        url: 'game/update_game_state.php',
        method: 'POST',
        data: { game_id: <?php echo $game_id; ?>, new_state: 'in progress', next_question_index: '1' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.href = 'game.php?code=<?php echo $game_password; ?>';

            } else {
                console.log('Помилка оновлення стану гри: ' + response.message);
            }
        },
        error: function() {
            console.log('Помилка AJAX-запиту');
        }
    });
}



function stopGame() {
    $.ajax({
        url: 'game/update_game_state.php',
        method: 'POST',
        data: { game_id: <?php echo $game_id; ?>, new_state: 'waiting', next_question_index: '0' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                prev_game_question_number = 0;
            } else {
                console.log('Помилка оновлення стану гри: ' + response.message);
            }
        },
        error: function() {
            console.log('Помилка AJAX-запиту');
        }
    });
}


function showScoreBoard() {
    $.ajax({
        url: 'game/update_game_state.php',
        method: 'POST',
        data: { game_id: <?php echo $game_id; ?>, new_state: 'score', next_question_index: '0' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.href = 'game.php?code=<?php echo $game_password; ?>';
            } else {
                console.log('Помилка оновлення стану гри: ' + response.message);
            }
        },
        error: function(response) {
            console.log(response, 'Помилка AJAX-запиту. showScoreBoard()');
        }
    });
}


function changeGameQuestion(nextQuestion) {
    $.ajax({
        url: 'game/update_game_state.php',
        method: 'POST',
        data: { game_id: <?php echo $game_id; ?>, new_state: 'in progress', next_question_index: nextQuestion },
        dataType: 'json',
        success: function(response) {
            if (response.success) {

            } else {
                console.log('Помилка оновлення стану гри: ' + response.message);
            }
        },
        error: function() {
            console.log('Помилка AJAX-запиту');
        }
    });
}

</script>

<?php include "includes/footer.php" ?>