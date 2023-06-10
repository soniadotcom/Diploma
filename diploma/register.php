<?php  include "includes/header.php"; ?>
<?php ob_start(); ?>
<?php session_start(); ?>

<?php



if(isset($_POST["register"])) {


    $stmt = mysqli_prepare($connection, "INSERT INTO players (player_username, player_password) VALUES (?, ?)");

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    $username = $_POST["username"];
    $password = $_POST["password"];

    if(empty($username) || empty($password) || $password = "") {
        // Empty fields
        echoErrorMsg("fill_required_fields");
    } else {
        if(isUsernameValidAndAvailable($username)) {
            if(isPasswordSecure($password)) {
                $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
                if(!mysqli_stmt_execute($stmt)) {
                    echoErrorMsg("error_inserting_record");
                    echo mysqli_stmt_error($stmt);
                } else {
        
                    $_SESSION['player_username'] = $username;
                    $_SESSION['player_role'] = "user";
        
                    header("Location: profile.php");
                }
            } else {
                echoErrorMsg("error_weak_password");
            }
        } else {
            echoErrorMsg("error_username_not_unique");
        }
    }

}


?>



<section class="register">
    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-4 offset-xl-4 text-center">
                <div class="register__logo">
                    <img src="images/logo.svg" alt="#">
                </div>
                <form role="form" action="register.php" method="post" class="form-group" autocomplete="off">
                    <div class="form-group">
                        <input type="text" name="username" class="username form-control" placeholder="<?php echo getTranslation("username") ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="password form-control" placeholder="<?php echo getTranslation("password") ?>">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="register" class="btn cta" value="<?php echo getTranslation("register") ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php" ?>