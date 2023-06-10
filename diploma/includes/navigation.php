<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="nav">
        <div class="nav__left">
            <div class="to_profile">
                <a href="profile.php" title="<?php echo getTranslation("navigation_profile") ?>" class="hint">
                    <img src="images/logo1.svg" alt="#">
                </a>
            </div>
            <div class="to_menu">
                <a href="menu.php" title="<?php echo getTranslation("menu_label") ?>" class="hint">
                    <img src="images/home.svg" alt="#">
                </a>
            </div>
            <div class="to_settings">
                <a href="settings.php" title="<?php echo getTranslation("navigation_settings") ?>" class="hint">
                    <img src="images/settings.svg" alt="#">
                </a>
            </div>
        </div>
        <div class="nav__right">
            <a href="rating.php" title="<?php echo getTranslation("navigation_rating") ?>" class="hint">
                <div class="score">
                    <?php
                        if(isset($_SESSION['player_username'])) {
                            echo $_SESSION['player_username'];
                        }
                    ?>
                    &#x1F3C6; <?php 
                    if(isset($_SESSION['player_username'])) {
                        echo getPlayerWinsByUsername($_SESSION['player_username']);
                    } 
                    ?>
                </div>
            </a>
        </div>
    </div>
</nav>