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

<div id="main">

    <? include("includes/navigation.php"); ?>
    <div class="container">

        <p>Welcome<? if (isset($_SESSION['firstname'])) {
                echo " " . $_SESSION['firstname'];
            } ?>!</p>
        <? print_r($_SESSION); ?>
    </div>
</div>

<? include("includes/footer.php"); ?>

</body>
</html>