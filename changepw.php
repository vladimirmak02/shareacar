<?

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (!isset($_SESSION["loggedin"]) OR $_SESSION["loggedin"] != true) {
    header("location: /login.php");
    exit;
}

require_once "includes/a_config.php";
require_once "includes/inputValidation.php";
$username = "";
$password = "";
$loginError = 0;
$userid = 0;

if (isset($_POST['submit'])) {
    $username = $_SESSION['username'];
    $password = cleanInput($_POST["oldpassword"]);
    $newpassword = cleanInput($_POST["newpassword"]);
    $newpasswordrepeat = cleanInput($_POST["newpasswordrepeat"]);

    $sql = "SELECT password FROM users WHERE (username = ?)";

    if ($newpassword == $newpasswordrepeat) {
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
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
            if (mysqli_stmt_bind_result($stmt, $password_out)) {
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $password_out)) {
                        $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

                        $sql = "UPDATE users SET password= ? WHERE username = ?";

                        if ($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss", $newpassword, $username);
                        } else {
                            echo "somehting went wrong1...";
                        }
                        if (mysqli_stmt_execute($stmt)) {

                            header("location: profile.php");
                            exit;

                        } else {
                            echo "Something went wrong. Please try again later.";
                        }

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
    } else {
        $passwordError = 1;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <? include("includes/head-tag-contents.php"); ?>
</head>
<body>

<div id="main">

    <? include("includes/navigation.php"); ?>
    <div class="container" style="padding: 5px">
        <form class="loginform" action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 50%;">
            <p class="invalid_text"><? if ($loginError == 1) {
                    echo "The password you entered isn't correct. Please try again, or <a href='/forgotpw.php'>click here if you forgot password?</a>";
                } ?> </p>
            <div class="form-group">
                <label for="inputPassword">Old Password</label>
                <input type="password" id="inputoldPassword" name="oldpassword" minlength="6" placeholder="New Password"
                       class="form-control"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword">New Password</label>
                <input type="password" id="inputnewPassword" name="newpassword" minlength="6" placeholder="New Password"
                       class="form-control"
                       required>
                <small id="passwordHelpInline" class="text-muted">
                    Must be at least 6 characters long.
                </small>
            </div>
            <div class="form-group">
                <label for="inputPassword2">Re-Type New Password</label>
                <input type="password" id="inputnewPassword2" name="newpasswordrepeat" minlength="6"
                       placeholder="Re-Type New Password"
                       class="form-control" required>
                <small id="passwordHelpInline" class="text-muted">
                    Type the same password as the field above.
                </small>
            </div>
            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Reset Password</button>
        </form>
    </div>

</div>

<? include("includes/footer.php"); ?>


</body>
</html>