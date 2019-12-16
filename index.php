<?
include("includes/a_config.php");
session_start();
?>
<!DOCTYPE html>
<!--RESOURCES:
https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
https://www.tutorialrepublic.com/php-tutorial/php-form-handling.php
https://getbootstrap.com/docs/4.3/components/forms/
https://www.w3schools.com/php/php_filter_advanced.asp
YOUTUBE TUTORIAL ON SIGNUP & LOGIN
-->
<html>
<head>
    <? include("includes/head-tag-contents.php"); ?>
</head>
<body>

<div id="main"
     style='background-image: url("/res/home_background.jpg"); background-position: bottom; background-attachment: fixed; background-repeat: no-repeat; background-size: cover;'>

    <? include("includes/navigation.php"); ?>
    <div class="container" )>
        <br>
        <div style="height: 4rem; background-color: rgba(255, 255, 255, 0.9); border-radius: 1rem">
            <p style="color: black; font-size: 1.5rem; text-align: center; padding: 0.5rem">
                Welcome<? if (isset($_SESSION['firstname'])) {
                echo " " . $_SESSION['firstname'];
            } ?>!</p>
        </div>


    </div>
</div>

<? include("includes/footer.php"); ?>

</body>
</html>