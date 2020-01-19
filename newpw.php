<?

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;
}

require_once "includes/a_config.php";
require_once "includes/inputValidation.php";

if (isset($_GET["selector"]) AND isset($_GET["validator"])) {
    $selector = $_GET["selector"];
    $validator = $_GET["validator"];
}

$password = "";
$passwordRepeat = "";
$passwordError = 0;
$email = "";
$resetselector = "";
$resettoken = "";
$requestExpired = 0;

if (isset($_POST['submit'])) {
    $password = cleanInput($_POST["password"]);
    $passwordRepeat = cleanInput($_POST["passwordrepeat"]);

    if ($password === $passwordRepeat) {

        $currentDate = date("U");
        $sql = "SELECT email, token FROM passwordreset WHERE selector = ? AND expires >= ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        } else {
            echo "somehting went wrong1...";
        }
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

        } else {
            echo "Something went wrong. Please try again later.";
        }
        if (mysqli_stmt_num_rows($stmt) == 1) {
            if (mysqli_stmt_bind_result($stmt, $email, $resettoken)) {
                if (mysqli_stmt_fetch($stmt)) {

                    $tokenBin = hex2bin($validator);
                    $tokenCheck = password_verify($tokenBin, $resettoken);

                    if ($tokenCheck) {
                        //Update the password
                        $sql = "UPDATE users SET password= ? WHERE email = ?";

                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $password = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "ss", $password, $email);
                        } else {
                            echo "somehting went wrong1...";
                        }
                        if (mysqli_stmt_execute($stmt)) {

                            $sql = "DELETE FROM passwordreset WHERE email = ?";

                            if ($stmt = mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "s", $email);
                            } else {
                                echo "somehting went wrong1...";
                            }
                            if (mysqli_stmt_execute($stmt)) {

                                header("location: login.php?newpwd=updated");
                                exit;


                            } else {
                                echo "Something went wrong. Please try again later.";
                            }


                        } else {
                            echo "Something went wrong. Please try again later.";
                        }


                    } else {
                        echo "Please resubmit your request.";
                    }

                } else {
                    echo "Fetch Failed";
                }
            } else {
                echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }

        } else {
            $requestExpired = 1;
        }
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
        <? if (empty($selector) OR empty($validator)) {
            echo "We could not validate your request";
            echo '<br> <a href="index.php">Go back home</a>';
        } elseif ($requestExpired == 1) {

            echo '<p class="invalid_text">Unfortunately, your reset password request has expired, please submit a new request to receive a new email.</p>';

        } else {
            if (ctype_xdigit($selector) AND ctype_xdigit($validator)) {

                ?>
                <p class="invalid_text"><? if ($passwordError == 1) {
                        echo "The passwords do not match. Please try again";
                    } ?> </p>
                <form class="loginform"
                      action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?selector=" . $selector . "&validator=" . $validator; ?>"
                      method="post"
                      style="width: 50%;">
                    <div class="form-group">
                        <label for="inputPassword">New Password</label>
                        <input type="password" name="password" id="inputPassword" minlength="6"
                               placeholder="New Password"
                               class="form-control"
                               required>
                        <small id="passwordHelpInline" class="text-muted">
                            Must be at least 6 characters long.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword2">Re-Type New Password</label>
                        <input type="password" name="passwordrepeat" id="inputPassword2" minlength="6"
                               placeholder="Re-Type New Password"
                               class="form-control" required>
                        <small id="passwordHelpInline" class="text-muted">
                            Type the same password as the field above.
                        </small>
                    </div>
                    <input type='hidden' name='submit'/>
                    <button class="btn btn-primary" id="submitBtn" type="submit">Reset my password</button>
                </form>
            <? }
        } ?>
    </div>

</div>

<? include("includes/footer.php"); ?>

<script>$('#inputPassword').select();</script>

</body>
</html>