<?php

function echoErrorMsg($string) {
    $translation = getTranslation($string);
    echo "<div class='container'><div class='row'><div class='col-12 col-xl-4 offset-xl-4'>";
    echo "<h4 class='alert alert-danger'>$translation</h4>";
    echo "</div></div></div>";
}

function echoSuccessMsg($string) {
    $translation = getTranslation($string);
    echo "<div class='container'><div class='row'><div class='col-12 col-xl-4 offset-xl-4'>";
    echo "<h4 class='alert alert-success'>$translation</h4>";
    echo "</div></div></div>";
}

function confirmQuery($query) {
    global $connection;
    if(!$query) {
        echoErrorMsg("Query Failed!");
        die(mysqli_error($connection));
    }
}

function getPlayerIdByUsername($player_username) {
    global $connection;
    $getPlayerIdQuery = "SELECT player_id FROM players WHERE player_username = ?";
    $stmtPlayerId = mysqli_prepare($connection, $getPlayerIdQuery);
    mysqli_stmt_bind_param($stmtPlayerId, "s", $player_username);
    mysqli_stmt_execute($stmtPlayerId);
    mysqli_stmt_bind_result($stmtPlayerId, $player_id);
    mysqli_stmt_fetch($stmtPlayerId);
    mysqli_stmt_close($stmtPlayerId);
    return $player_id;
}

function getPlayerUsernameById($player_id) {
    global $connection;
    $getPlayerUsernameQuery = "SELECT player_username FROM players WHERE player_id = ?";
    $stmtPlayerUsername = mysqli_prepare($connection, $getPlayerUsernameQuery);
    mysqli_stmt_bind_param($stmtPlayerUsername, "i", $player_id);
    mysqli_stmt_execute($stmtPlayerUsername);
    mysqli_stmt_bind_result($stmtPlayerUsername, $player_username);
    mysqli_stmt_fetch($stmtPlayerUsername);
    mysqli_stmt_close($stmtPlayerUsername);
    return $player_username;
}

function getPlayerWinsByUsername($player_username){
    global $connection;
    $getPlayerIdQuery = "SELECT player_wins FROM players WHERE player_username = ?";
    $stmtPlayerId = mysqli_prepare($connection, $getPlayerIdQuery);
    mysqli_stmt_bind_param($stmtPlayerId, "s", $player_username);
    mysqli_stmt_execute($stmtPlayerId);
    mysqli_stmt_bind_result($stmtPlayerId, $player_wins);
    mysqli_stmt_fetch($stmtPlayerId);
    mysqli_stmt_close($stmtPlayerId);
    return $player_wins;
}

function getGameIdByPassword($password) {
    global $connection;
    $query = "SELECT * FROM games WHERE game_password = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row_count = mysqli_num_rows($result);
    
    if ($row_count > 0) {
        $game = mysqli_fetch_assoc($result);
        return $game['game_id'];
    }
}

function generateGamePassword($password_length) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';

    $characterCount = strlen($characters);
    for ($i = 0; $i < $password_length; $i++) {
        $password .= $characters[rand(0, $characterCount - 1)];
    }

    return $password;
}

function addPlayerToGame($player_game_id, $player_id){
    global $connection;

    // Check game for free player slots

    if(isGameFilled($player_game_id)) {
        return false;
    }

    // Add game creator to the games_players table to provide access to the game
    $stmt = mysqli_prepare($connection, "UPDATE players SET player_game_id = ? WHERE player_id = ?");

    mysqli_stmt_bind_param($stmt, "ii", $player_game_id, $player_id);
    
    mysqli_stmt_execute($stmt);

    if (!mysqli_stmt_affected_rows($stmt) > 0) {
        echoErrorMsg("Error: Failed to add player to the game.");
        echo mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
    } else {
        mysqli_stmt_close($stmt);
        return true;
    }
    return false;
}

function isGameFilled($game_id) {
    global $connection;
    $query = "SELECT COUNT(*) AS player_count FROM players WHERE player_game_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $game_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $player_count = $row['player_count'];

        $query2 = "SELECT game_max_players FROM games WHERE game_id = ?";
        $stmt2 = mysqli_prepare($connection, $query2);
        mysqli_stmt_bind_param($stmt2, "i", $game_id);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);

        if ($result2 && mysqli_num_rows($result2) > 0) {
            $row2 = mysqli_fetch_assoc($result2);
            $game_max_players = $row2['game_max_players'];

            if ($player_count >= $game_max_players) {
                return true; // Гра заповнена
            }
        }
    }

    return false; // Гра не заповнена
}

function checkUserForActiveGame() {
    global $connection;
    $player_username = $_SESSION['player_username'];

    $player_id = getPlayerIdByUsername($player_username);

    $query = "SELECT p.player_game_id, g.game_state, g.game_password 
              FROM players p
              JOIN games g ON p.player_game_id = g.game_id
              WHERE p.player_id = ?
              LIMIT 1";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $player_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $game_state = $row['game_state'];
        $game_password = $row['game_password'];

        if ($game_state == "waiting" || $game_state == "in progress" || $game_state == "score") {
            ?>
            <script>
                $(document).ready(function() {
                    var gameLink = 'game.php?code=<?php echo $game_password; ?>';
                    var buttonHTML = "<a href='" + gameLink + "'><input type='button' name='create_quiz' class='btn btn-3 cta' value='Join Active Game'></a>";
                    $('.active-game').html(buttonHTML);
                });
            </script>
            <?php
        }
    }

    mysqli_stmt_close($stmt);
}

function getAllPlayersScoreBoard() {
    global $connection;
    
    $query = "SELECT player_username, player_wins FROM players ORDER BY player_wins DESC LIMIT 10";
    $result = mysqli_query($connection, $query);
    
    $stats = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $player_username = $row['player_username'];
        $player_wins = $row['player_wins'];
        
        $stats[] = array(
            'player_username' => $player_username,
            'player_wins' => $player_wins
        );
    }
    
    mysqli_close($connection);
    
    return $stats;
}

function getTranslation($key) {
    global $connection;

    if(isset($_SESSION['player_language'])) {
        $player_language = $_SESSION['player_language'];
    } else {
        $player_language = "uk";
    }

    // Зчитування текстових рядків для відповідної мови
    $query = "SELECT translation_text FROM translations WHERE translation_key = ? AND translation_lang_code = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $key, $player_language);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $translationText);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $translationText;
}


function isPasswordSecure($password) {
    if (strlen($password) < 8) {
        return false;
    }

    if (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[^A-Za-z0-9]/", $password)) {
        return false;
    }

    $commonPasswords = array('password', '123456', 'qwerty', 'pass123', 'password123');
    if (in_array($password, $commonPasswords)) {
        return false;
    }

    return true;
}


function isUsernameValidAndAvailable($username) {
    global $connection;

    if (strlen($username) < 2 || strlen($username) > 30) {
        return false;
    }

    $query = "SELECT COUNT(*) FROM players WHERE player_username = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count > 0) {
        return false;
    }

    return true;
}

?>