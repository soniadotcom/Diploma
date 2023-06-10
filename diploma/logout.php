<?php session_start(); ?>

<?php

    $_SESSION['player_username'] = null;
    $_SESSION['player_role'] = null;

    header("Location: index.php");
            
?>