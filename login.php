<?php

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;
}

require_once "includes/a_config.php";
require_once "includes/inputValidation.php";
$username = "";
$password = "";
$loginError = 0;
$userid = 0;
$firstname = "";

if (isset($_POST['submit'])) {
    $username = cleanInput($_POST["username"]);
    $password = cleanInput($_POST["password"]);

    $sql = "SELECT id, password, first_name, username FROM users WHERE (email = ?) OR (username = ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    } else {
        echo "somehting went wrong1...";
    }
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($stmt) == 1) {
        $password_out = NULL;
        if (mysqli_stmt_bind_result($stmt, $userid, $password_out, $firstname, $username)) {
            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $password_out)) {
                    session_start();

                    // Store data in session variables
                    $_SESSION["loggedin"] = true;

                    $_SESSION["uid"] = $userid;

                    $_SESSION["username"] = $username;

                    $_SESSION["firstname"] = $firstname;

                    // Redirect user to welcome page
                    header("location: profile.php");
                    exit;
                } else {
                    $loginError = 1;
                }
            } else {
                echo "Fetch Failed";
            }
        } else {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

    } else {
        $loginError = 1;
    }
    mysqli_stmt_close($stmt);
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

    <div class="container" style="padding: 5px">
        <form class="loginform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 50%;">
            <?php if (isset($_GET['newpwd']) AND $_GET['newpwd'] === "updated") { ?>
                <div class="alert alert-success" role="alert">
                    <p>You successfully reset your password, now go ahead and log in!</p>
                </div>
            <?php } ?>
            <p class="invalid_text"><?php if ($loginError == 1) {
                    echo "The login details do not match. Please try again";
                } ?> </p>

            <div class="form-group">
                <label for="inputUsername">Username or Email</label>
                <input type="text" name="username" class="form-control" id="inputUsername" minlength="4"
                       placeholder="Enter Username or Email"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" id="inputPassword" name="password" minlength="6" placeholder="Password"
                       class="form-control"
                       required>
            </div>

            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Login</button>
        </form>
        <p><a href="/forgotpw.php">Forgot password?</a></p>
    </div>


</div>

<?php include("includes/footer.php"); ?>

<script>$('#inputUsername').select();</script>
</body>
</html>