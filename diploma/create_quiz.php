<?php  include "includes/session_start.php"; ?>

<?php  include "includes/header.php"; ?>

<?php  include "includes/navigation.php"; ?>


<?php


if(isset($_POST["create_quiz"])) {


    $stmt = mysqli_prepare($connection, "INSERT INTO quizzes (quiz_title, quiz_description, quiz_status, quiz_created_at, quiz_created_by) VALUES (?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $status, $created_at, $created_by);

    // Get player_id by player_username
    $player_id = getPlayerIdByUsername($_SESSION['player_username']);

    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $created_at = date('Y-m-d H:i:s');
    $created_by = $player_id;

    if(empty($title) || empty($description) || empty($status)) {
        // Empty fields
        echoErrorMsg("fill_required_fields");
    } else {
        if(!mysqli_stmt_execute($stmt)) {
            // Error inserting error
            echoErrorMsg("error_inserting_record");
            echo mysqli_stmt_error($stmt);
        } else {
            $quiz_id = mysqli_insert_id($connection);

            header("Location: add_questions.php?quiz_id=$quiz_id");
        }
    }
}


?>


<form role="form" action="create_quiz.php" method="post" class="form-group" autocomplete="off">
    
    <section class="create_quiz">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="create_quiz__title"><?php echo getTranslation("create_quiz_creating_quiz") ?></h2>
                </div>
                <div class="col-12 text-center">
                    <div class="create_quiz__logo">
                        <img class="create_quiz__logo" src="images/logo.svg" alt="#">
                        <a href="create_quiz.php" class="quiz_avatar"><?php echo getTranslation("create_quiz_change_image") ?></a>
                        <!--<input value="change avatar" type="file" name="user_avatar">-->
                    </div>
                </div>
                <div class="col-12 col-xl-3 offset-xl-3">
                    <div class="form-group">
                        <label for="title"><?php echo getTranslation("create_quiz_title") ?></label>
                        <input type="text" name="title" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="form-group">
                        <label for="status" style="width: 100%"><?php echo getTranslation("create_quiz_status") ?></label>
                        <select name="status" id="" class="selectpicker form-control">
                            <option value="private"><?php echo getTranslation("create_quiz_private") ?></option>
                            <option value="public"><?php echo getTranslation("create_quiz_public") ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-xl-6 offset-xl-3">
                    <div class="form-group last">
                        <label for="description"><?php echo getTranslation("create_quiz_description") ?></label>
                        <input type="text" name="description" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="col-12 text-center">
                    <input type="submit" name="create_quiz" class="btn cta" value="<?php echo getTranslation("create_quiz_next") ?>">
                </div>
            </div>
        </div>
    </section>

</form>

<?php include "includes/footer.php" ?>