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
        <form class="loginform" style="width: 50%;">
            <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" class="form-control" id="inputEmail" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input type="text" class="form-control" id="inputUsername" minlength="4" placeholder="Enter Username"
                       required>

            </div>

        </form>
        <p>If you already have an account then <a href="/login.php">log in</a>.</p>
    </div>

</div>

<?php include("includes/footer.php"); ?>

</body>
</html>