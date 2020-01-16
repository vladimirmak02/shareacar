<?
require_once "includes/a_config.php";
require_once "includes/inputValidation.php";
$passwordError = 0;
$emailError = 0;
$emailTakenError = 0;
$usernameError = 0;
$firstname = "";
$lastname = "";
$email = "";
$username = "";
$password = "";
$passwordRepeat = "";
$userid = "";
if (isset($_POST['submit'])) {

    $firstname = cleanInput($_POST["newFirstname"]);
    $lastname = cleanInput($_POST["newLastname"]);
    $email = cleanEmail($_POST["newEmail"]);
    $username = cleanInput($_POST["newUsername"]);
    $password = cleanInput($_POST["newPassword"]);
    $passwordRepeat = cleanInput($_POST["newPassword2"]);

    echo $firstname . "-------";
    echo $lastname . "-------";
    echo $email . "-------";
    echo $username . "-------";
    echo $password . "-------";
    echo $passwordRepeat . "-------";


    if ($email == false) {
        $emailError = 1;
    }
    if ($password == $passwordRepeat) {
        //CHECK EMAIL
        $sql = "SELECT id FROM users WHERE (email = ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
        } else {
            echo "somehting went wrong1...";
        }
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

        } else {
            echo "Something went wrong. Please try again later.";
        }
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $emailTakenError = 1;
            mysqli_stmt_close($stmt);
        }

        //CHECK USERNAME
        $sql = "SELECT id FROM users WHERE (username = ?)";

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
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $usernameError = 1;
            mysqli_stmt_close($stmt);
        } else if ($emailError == 0 AND $emailTakenError == 0) {
            mysqli_stmt_close($stmt);
            $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $firstname, $lastname, $email, $username, $passwordHash);
            } else {
                echo "somehting went wrong...";
            }
            if (mysqli_stmt_execute($stmt)) {

                $sql = "SELECT id FROM users WHERE username = ?";

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
                    if (mysqli_stmt_bind_result($stmt, $userid)) {
                        if (mysqli_stmt_fetch($stmt)) {
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;

                            $_SESSION["uid"] = $userid;

                            $_SESSION["username"] = $username;

                            $_SESSION["firstname"] = $firstname;

                            // Redirect user to welcome page
                            header("location: profile.php");
                        } else {
                            echo "Fetch Failed";
                        }
                    } else {
                        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                } else {
                    echo "Error";
                }


            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
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
        <form class="loginform" action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 50%;">
            <div class="form-group">
                <p class="invalid_text"><? if ($passwordError == 1) {
                        echo "The passwords do not match. Please try again";
                    } ?> </p>
                <p class="invalid_text"> <?
                    if ($usernameError == 1) {
                        echo "This username is taken. Please try a different one.";
                    }
                    ?>
                </p>
                <p class="invalid_text"> <?
                    if ($emailTakenError == 1) {
                        echo "This email is taken. Please try a different one.";
                    }
                    ?>
                </p>
                <p class="invalid_text"> <?
                    if ($emailError == 1) {
                        echo "This email is invalid. Please enter a valid email.";
                    }
                    ?>
                </p>
                <label for="inputFirstname">First name</label>
                <input type="text" class="form-control" value="<? echo $firstname ?>" id="inputFirstname"
                       name="newFirstname"
                       placeholder="Enter your first name"
                       required>
            </div>
            <div class="form-group">
                <label for="inputLastname">Last name</label>
                <input type="text" class="form-control" value="<? echo $lastname; ?>" name="newLastname"
                       placeholder="Enter your last name"
                       required>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email address</label>
                <input type="email" class="form-control" value="<? echo $email; ?>" name="newEmail"
                       placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input type="text" class="form-control" name="newUsername" minlength="4"
                       value="<? echo $username; ?>" placeholder="Enter Username"
                       required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="newPassword" minlength="6" placeholder="Password" class="form-control"
                       required>
                <small id="passwordHelpInline" class="text-muted">
                    Must be at least 6 characters long.
                </small>
            </div>
            <div class="form-group">
                <label for="inputPassword2">Re-Type Password</label>

                <input type="password" name="newPassword2" minlength="6" placeholder="Re-Type Password"
                       class="form-control" required>
                <small id="passwordHelpInline" class="text-muted">
                    Type the same password as the field above.
                </small>

            </div>
            <input type='hidden' name='submit'/>
            <button class="btn btn-primary" id="submitBtn" type="submit">Signup</button>
        </form>
        <p>If you already have an account then <a href="/login.php">log in</a>.</p>

    </div>

</div>

<? include("includes/footer.php"); ?>

<script>$('#inputFirstname').select();</script>
</body>
</html>
