<?php
require_once "includes/a_config.php";
$passwordError = 0;
if (isset($_POST['submit'])) {
    $firstname = $lastname = $email = $username = $password = $passwordRepeat = "";

    $firstname = $_POST["newFirstname"];
    $lastname = $_POST["newLastname"];
    $email = $_POST["newEmail"];
    $username = $_POST["newUsername"];
    $password = $_POST["newPassword"];
    $passwordRepeat = $_POST["newPassword2"];


    if ($password == $passwordRepeat) {
        $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";

        if ($signupStmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($signupStmt, "sssss", $firstname, $lastname, $email, $username, $password);
        } else {
            echo "somehting went wrong...";
        }
        if (mysqli_stmt_execute($signupStmt)) {
            header("location: index.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($signupStmt);

    } else {
        $passwordError = 1;
    }

}

?>
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
                <label for="inputFirstname">First name</label>
                <input type="text" class="form-control" name="newFirstname" minlength="4"
                       placeholder="Enter your first name"
                       required>
            </div>
            <div class="form-group">
                <label for="inputLastname">Last name</label>
                <input type="text" class="form-control" name="newLastname" minlength="4"
                       placeholder="Enter your last name"
                       required>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" class="form-control" name="newEmail" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input type="text" class="form-control" name="newUsername" minlength="4" placeholder="Enter Username"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="newPassword" minlength="8" placeholder="Password" class="form-control"
                       required>
                <small id="passwordHelpInline" class="text-muted">
                    Must be at least 8 characters long.
                </small>
            </div>
            <div class="form-group">
                <label for="inputPassword2">Re-Type Password</label>

                <input type="password" name="newPassword2" minlength="8" placeholder="Re-Type Password"
                       class="form-control" required>
                <small id="passwordHelpInline" class="text-muted">
                    Type the same password as the field above.
                </small>
            </div>
            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Signup</button>
        </form>
        <p>If you already have an account then <a href="/login.php">log in</a>.</p>
        <span id="errorMessage" class="invalid-feedback"><?php if ($passwordError == 1) {
                echo "The passwords do not match.";
            } ?> Please try again</span>
    </div>

</div>

<?php include("includes/footer.php"); ?>


</body>
</html>