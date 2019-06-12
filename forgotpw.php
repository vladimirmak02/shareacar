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
                <label for="inputPassword">New Password</label>
                <input type="password" id="inputPassword" minlength="8" placeholder="New Password" class="form-control"
                       required>
                <small id="passwordHelpInline" class="text-muted">
                    Must be at least 8 characters long.
                </small>
            </div>
            <div class="form-group">
                <label for="inputPassword2">Re-Type New Password</label>
                <input type="password" id="inputPassword2" minlength="8" placeholder="Re-Type New Password"
                       class="form-control" required>
                <small id="passwordHelpInline" class="text-muted">
                    Type the same password as the field above.
                </small>
            </div>
            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Reset Password</button>
        </form>
        <p>If you magically remembered your account details then <a href="/login.php">log in</a></p>
    </div>

</div>

<?php include("includes/footer.php"); ?>


</body>
</html>