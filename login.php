<?php include("includes/a_config.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <?php include("includes/head-tag-contents.php"); ?>
</head>
<body>

<div id="main">

    <?php include("includes/navigation.php"); ?>

    <div class="container" style="padding: 5%">
        <form class="loginform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 50%;">
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input type="text" class="form-control" id="inputUsername" minlength="4" placeholder="Enter Username"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" id="inputPassword" minlength="8" placeholder="Password" class="form-control"
                       required>
                <!--                <small id="passwordHelpInline" class="text-muted">-->
                <!--                    -->
                <!--                </small>-->
            </div>

            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Signup</button>
        </form>
        <p><a href="/forgotpw.php">Forgot password?</a>.</p>
    </div>


</div>

<?php include("includes/footer.php"); ?>

</body>
</html>