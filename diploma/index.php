<?php  include "includes/header.php"; ?>
<?php ob_start(); ?>
<?php session_start(); ?>

<?php

if(isset($_POST["login"])) {


    $stmt = mysqli_prepare($connection, "SELECT player_password, player_role, player_language  FROM players WHERE player_username = ?");

    mysqli_stmt_bind_param($stmt, "s", $username);

    $username = $_POST["username"];
    $password = $_POST["password"];

    if(empty($username) || empty($password)) {
        // Empty fields
        echoErrorMsg("fill_required_fields");
    } else {
        if(!mysqli_stmt_execute($stmt)) {
            // Error inserting record
            echoErrorMsg("error_inserting_record");
            echo mysqli_stmt_error($stmt);
        } else {

            mysqli_stmt_bind_result($stmt, $db_player_password, $db_player_role, $db_player_language);
            mysqli_stmt_fetch($stmt);

            if(password_verify($password, $db_player_password)) {

                $_SESSION['player_username'] = $username;
                $_SESSION['player_role'] = $db_player_role;
                $_SESSION['player_language'] = $db_player_language;

                header("Location: menu.php");
            } else {
                echoErrorMsg("wrong_username_password");
            }
        }
    }

}

if (isset($_GET['access']) && $_GET['access'] === 'denied') {
    echoErrorMsg("no_access_message");
}

if (isset($_SESSION['player_username'])) {
    header("Location: menu.php");
}

?>

<section class="login">
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-4 offset-xl-4 text-center">
                <div class="login__logo">
                    <img src="images/logo.svg" alt="#">
                </div>
                <form role="form" action="index.php" method="post" class="form-group" autocomplete="off">
                    <div class="form-group">
                        <input type="text" name="username" class="username form-control" placeholder="<?php echo getTranslation("username") ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="password form-control" placeholder="<?php echo getTranslation("password") ?>">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" class="btn cta" value="<?php echo getTranslation("start_button") ?>">
                    </div>
                </form>
                <a href="register.php" class="login__link"><?php echo getTranslation("or_register") ?></a>
            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php" ?>