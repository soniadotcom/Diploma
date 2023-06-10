<?php ob_start(); ?>
<?php session_start(); ?>

<?php
if(!isset($_SESSION['player_role'])) {
    header("Location: index.php?access=denied");
}
?>