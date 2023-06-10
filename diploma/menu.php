<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>

<?php

if (isset($_GET['access']) && $_GET['access'] === 'denied') {
    echoErrorMsg("no_access_game");
}

if (isset($_GET['quit_game']) && $_GET['quit_game'] === 'true') {
    echoSuccessMsg("quit_game_success");
}

if (isset($_GET['game_full']) && $_GET['game_full'] === 'true') {
    echoErrorMsg("join_game_failed");
}

if(isset($_POST['join_game'])) {

    $password = $_POST['code'];

    $player_id = getPlayerIdByUsername($_SESSION['player_username']);
    $game_id = getGameIdByPassword($password);

    if(addPlayerToGame($game_id, $player_id)){
        header("Location: game.php?code=$password");
    } else {
        header("Location: menu.php?game_full=true");
    }
}


// Check if user has game in progress or waiting state
checkUserForActiveGame();

?>


<section class="menu">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="menu__block">
                    <form action="menu.php" method="post">
                        <div class="active-game">
                            <input type="text" name="code" class="btn-1 form-control" placeholder="<?php echo getTranslation("enter_code") ?>" oninput="capitalizeText(this, 6)">
                            <input type="submit" name="join_game" class="btn btn-2 cta" value="<?php echo getTranslation("join_game_button") ?>">
                        </div>
                    </form>
                    <a href="create_game.php">
                        <input type="submit" name="create_game" class="btn btn-3 cta" value="<?php echo getTranslation("create_game_button") ?>">
                    </a>
                    <a href="create_quiz.php">
                        <input type="submit" name="create_quiz" class="btn btn-4 cta" value="<?php echo getTranslation("create_quiz_button") ?>">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php" ?>