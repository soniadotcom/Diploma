<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>
<section class="rating_table">
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-4 offset-xl-4">
                <table id="resultsTable" class="table table-light table-hover text-center">
                    <thead>
                        <tr>
                            <th><h2><?php echo getTranslation("rating_players") ?></h2></th>
                            <th><h2><?php echo getTranslation("rating_wins") ?></h2></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $playerStats = getAllPlayersScoreBoard();

                        foreach ($playerStats as $stats) {
                            $player_username = $stats['player_username'];
                            $player_wins = $stats['player_wins'];
                            if($player_wins > 0) {
                                echo '<tr><td><span>', $player_username, '</span></td><td><span>', $player_wins, '</span></td></tr>';
                            }
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>





<?php include "includes/footer.php" ?>