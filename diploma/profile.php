<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>


<?php

if(isset($_SESSION['player_username'])) {
    $stmt = mysqli_prepare($connection, "SELECT player_bio, player_email, player_image FROM players WHERE player_username = ?");

    mysqli_stmt_bind_param($stmt, "s", $prev_username);

    $prev_username = $_SESSION["player_username"];

    if(!mysqli_stmt_execute($stmt)) {
        // Error selecting record
        echoErrorMsg("error_selecting_record1");
        echo mysqli_stmt_error($stmt);
    } else {
        mysqli_stmt_bind_result($stmt, $prev_bio, $prev_email, $prev_image);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

if(isset($_POST["edit_profile"])) {

    // if no new password is entered, leaves the old one
    $stmt = mysqli_prepare($connection, "UPDATE players SET player_bio = ?, player_email = ?, player_username = ?, player_password = CASE WHEN ? <> '' THEN ? ELSE player_password END WHERE player_username = ?");

    mysqli_stmt_bind_param($stmt, "ssssss", $bio, $email, $username, $password, $password, $prev_username);

    $bio = $_POST["bio"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $prev_username = $_SESSION["player_username"];

    if(!empty($password)) {
        if(isPasswordSecure($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
        } else {
            echoErrorMsg("error_weak_password");
        }
    }

    if(empty($username)) {
        // Empty fields
        echoErrorMsg("fill_required_fields");
    } else {
        if(isUsernameValidAndAvailable($username)) {
            if(!mysqli_stmt_execute($stmt)) {
                // Error selecting record
                echoErrorMsg("error_selecting_record2");
                echo mysqli_stmt_error($stmt);
            } else {
                // Profile updated
                $_SESSION['player_username'] = $username;
    
                header("Location: profile.php?success=true");
                exit;
            }
        } else {
            echoErrorMsg("error_username_not_unique");
        }
    }
}

if (isset($_GET['success']) && $_GET['success'] === 'true') {
    echoSuccessMsg("profile_updated_success");
}

?>


<form role="form" id="profileForm" action="profile.php" method="post" class="form-group" autocomplete="off">
    
    <section class="profile">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="profile__logo">
                        <img class="profile__logo" src="images/logo.svg" alt="#">
                        <a href="profile.php" class="user_avatar"><?php echo getTranslation("change_avatar") ?></a>
                        <!--<input value="change avatar" type="file" name="user_avatar">-->
                    </div>
                </div>
                <div class="col-12 col-xl-4 offset-xl-2">
                    <div class="form-group">
                        <label for="username"><?php echo getTranslation("username_label") ?></label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $prev_username ?>">
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="form-group">
                        <label for="password"><?php echo getTranslation("new_password_label") ?></label>
                        <input type="password" name="password" class="form-control" value="">
                    </div>
                </div>
                <div class="col-12 col-xl-4 offset-xl-2">
                    <div class="form-group">
                        <label for="bio"><?php echo getTranslation("bio_label") ?></label>
                        <input type="text" name="bio" id="bio" class="last-xl form-control" value="<?php echo $prev_bio ?>">
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="form-group">
                        <label for="email"><?php echo getTranslation("email_label") ?></label>
                        <input type="email" name="email" id="email" class="last form-control" value="<?php echo $prev_email ?>">
                    </div>
                </div>
                <div class="col-12 text-center">
                    <div class="form-group btn-group">
                        <input type="submit" name="edit_profile" class="btn cta" value="<?php echo getTranslation("save_button") ?>">
                        <a href="menu.php">
                            <input class="btn cta cta-2" value="<?php echo getTranslation("menu_label") ?>">
                        </a>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <a href="logout.php" class="login__link"><?php echo getTranslation("logout_label") ?></a>
                </div>
            </div>
        </div>
    </section>

</form>



<?php include "includes/footer.php" ?>

