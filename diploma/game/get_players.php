<?php include "../includes/db.php" ?>

<?php
$game_id = $_GET['game_id'];

// Fetch the updated player information based on the game ID
$query = "SELECT player_id, player_username, player_bio, player_image, player_role FROM players WHERE player_game_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Generate the HTML markup for the player list
$playerListHTML = '';
while ($row = mysqli_fetch_assoc($result)) {
    $player_id = $row['player_id'];
    $player_username = $row['player_username'];
    $player_bio = $row['player_bio'];
    $player_image = $row['player_image'];
    $player_role = $row['player_role'];

    // Build the HTML markup for each player
    $playerListHTML .= '<div class="player">';
    $playerListHTML .= '<p class="player_username" title="' . $player_bio . '">' . $player_username . '</p>';
    $playerListHTML .= '<img class="player_avatar" src="images/logo.svg" alt="#">';
    $playerListHTML .= '</div>';
}

// Return the generated player list HTML
echo $playerListHTML;

mysqli_stmt_close($stmt);

?>