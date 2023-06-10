<?php  include "../includes/session_start.php"; ?>
<?php  include "../includes/db.php"; ?>
<?php include "../includes/functions.php" ?>

<section class="score_board">
    <div class="container">
        <div class="row">
            <?php
            $player_id = getPlayerIdByUsername($_SESSION['player_username']);
            if($_GET['game_created_by'] == $player_id) {

            ?>

                    
            <div class="col-12 text-center">
                <form action="" method="post">
                    <input type="button" name="stop_game" class="btn cta" value="Restart Game" onclick="stopGame();">
                </form>
            </div>

            <?php

            }
            ?>
            <div class="col-12">
                <h2>Score Board!</h2>
            </div>
            <div class="col-12 col-xl-6 offset-xl-3">
                <table id="resultsTable" class="table table-light table-hover text-center"></table>
            </div>
        </div>
    </div>
</section>




<script>

$(document).ready(function() {
    // Function to retrieve player results from the server
    function getPlayersResults() {
        $.ajax({
            url: 'game/get_players_results.php?game_id=<?php echo $_GET['game_id']; ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.length > 0) {
                    // Clear the table before adding new results
                    //$('#resultsTable').empty();

                    // Display player results
                    response.forEach(function(playerResult) {
                        var playerUsername = playerResult.playerUsername;
                        var score = playerResult.score;

                        // Add player results to the table
                        $('#resultsTable').append('<tr><td><span>' + playerUsername + '</span></td><td>' + score + '</td></tr>');
                    });
                } else {
                    console.log('No player results found');
                }
            },
            error: function() {
                console.log('AJAX request error');
            }
        });
    }    
    getPlayersResults();
});