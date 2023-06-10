<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>


<?php


if (isset($_GET['success']) && $_GET['success'] === 'true') {
    echoSuccessMsg("create_game_quiz_created");
}


if(isset($_POST["create_game"])) {

    $stmt = mysqli_prepare($connection, "INSERT INTO games (game_status, game_max_players, game_password, game_quiz_id, game_created_at, game_state, game_created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sisissi", $status, $max_players, $password, $quiz_id, $created_at, $game_state, $game_created_by);

    // Get player_id by player_username
    $player_id = getPlayerIdByUsername($_SESSION['player_username']);

    $status = $_POST['status'];
    $max_players = $_POST['max_players'];
    $password = generateGamePassword(6);
    $quiz_id = $_POST['quiz_id'];
    $created_at = date('Y-m-d H:i:s');
    $game_state = "waiting";
    $game_created_by = $player_id;

    if(empty($status) || empty($max_players) || empty($quiz_id)) {
        // Empty fields
        echoErrorMsg("create_game_fill_required_fields");
    } else {
        if(!mysqli_stmt_execute($stmt)) {
            // Error inserting error
            echoErrorMsg("error_inserting_record");
            echo mysqli_stmt_error($stmt);
        } else {
            // Game is successfully created
            $game_id = mysqli_insert_id($connection);

            // Add creator to the game
            if(addPlayerToGame($game_id, $player_id)){
                header("Location: game.php?code=$password");
            }
        }
    }
}


?>


<form role="form" action="create_game.php" method="post" class="form-group" autocomplete="off">
    
    <section class="create_game">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="create_game__title"><?php echo getTranslation("create_game_creating_game") ?></h2>
                </div>
                <div class="col-12 col-xl-3 offset-xl-3">
                    <div class="form-group">
                        <label for="status" style="width: 100%"><?php echo getTranslation("create_game_game_status") ?></label>
                        <select name="status" id="" class="selectpicker form-control">
                            <option value="private"><?php echo getTranslation("create_quiz_private") ?></option>
                            <option value="public"><?php echo getTranslation("create_quiz_public") ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="form-group">
                        <label for="max_players"><?php echo getTranslation("create_game_max_players") ?></label>
                        <input type="number" min="1" max="12" name="max_players" class="form-control" placeholder="5" value="5">
                    </div>
                </div>
                <div class="col-12 col-xl-6 offset-xl-3">
                    <div class="form-group last">
                        <label for="quiz_id"><?php echo getTranslation("create_game_choose_quiz") ?></label>
                        <select name="quiz_id" class="selectpicker form-control">
                            <?php
                                $player_id = getPlayerIdByUsername($_SESSION['player_username']);

                                $query = "SELECT quiz_id, quiz_title, quiz_status, quiz_created_by FROM quizzes WHERE quiz_status = 'public' OR quiz_created_by = $player_id ORDER BY quiz_created_at DESC";

                                $result = mysqli_query($connection, $query);

                                if ($result) {
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $quiz_id = $row['quiz_id'];
                                            $quiz_title = $row['quiz_title'];
                                            $quiz_status = $row['quiz_status'];
                                            
                                            echo "<option value='{$quiz_id}'>{$quiz_title}</option>";
                                        } 
                                    } else { 
                                        
                                        echo "<option value=''>No quizzes available</option>";   
                                        
                                        echo "<h2 class='create_quiz_link'><a href='create_quiz.php'>" + getTranslation("create_quiz_button") + "</a></h2>";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    confirmQuery($query);
                                }

                                mysqli_close($connection);
                            ?>
                        </select>
                            
                    </div>
                </div>
                <div class="col-12 text-center">
                    <input type="submit" name="create_game" class="btn cta" value="<?php echo getTranslation("create_game_create") ?>">
                </div>
            </div>
        </div>
    </section>

</form>

<?php include "includes/footer.php" ?>