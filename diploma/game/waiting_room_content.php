<?php  include "../includes/session_start.php"; ?>
<?php  include "../includes/db.php"; ?>
<?php include "../includes/functions.php" ?>

<section class="waiting_room">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>Waiting Room...</h2>
            </div>
            <div class="col-12 col-xl-2 offset-xl-5">
                <p>Code:</p>
                <input type="text" name="code" class="btn-1 form-control" value="<?php echo $_GET['game_password'] ?>" readonly onclick="this.select(); document.execCommand('copy');">
            </div>
            <div class="col-12">
                <div class="game_description">
                    <p>Quiz: <?php echo $_GET['quiz_title'] ?></p>
                    <p>Status: <span id="game-status"><?php echo $_GET['game_status'] ?></span></p>
                    <p>Max players: <?php echo $_GET['game_max_players'] ?></p>
                </div>
            </div>
            <?php
            $player_id = getPlayerIdByUsername($_SESSION['player_username']);
            if($_GET['game_created_by'] == $player_id) {

            ?>
            <div class="col-12 text-center">
                <form action="" method="post">
                    <input type="button" name="start_game" class="btn cta" value="Start Game" onclick="startGame();">
                </form>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>