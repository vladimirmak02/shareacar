<?

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] === true) {
    header("location: profile.php");
    exit;
}

require_once "includes/a_config.php";
require_once "includes/inputValidation.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/vendor/autoload.php";
$email = "";
$emailError = 0;
$sendSuccessful = 0;

if (isset($_POST['submit'])) {
    $email = cleanEmail($_POST["email"]);

    $sql = "SELECT id, first_name FROM users WHERE (email = ?) OR (username = ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $email);
    } else {
        echo "somehting went wrong1...";
    }
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($stmt) == 1) {

        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);

        $url = "localhost/newpw.php?selector=" . $selector . "&validator=" . bin2hex($token);

        $expires = date("U") + 1800;

        $sql = "DELETE FROM passwordreset WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
        } else {
            echo "somehting went wrong1...";
        }


        $sql = "INSERT INTO passwordreset (email, selector, token, expires) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $selector, $hashedToken, $expires);
            mysqli_stmt_execute($stmt);
        } else {
            echo "somehting went wrong1...";
        }


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML(true);
        $mail->Username = 'shareacarinfo@gmail.com';
        $mail->Password = 'share247';
        $mail->SetFrom('no-reply@shareacar.com', 'Admin');
        $mail->Subject = 'Share A Car Password Reset';
        $mail->Body = '<p>Hello,</p>' .
            '<p>To continue resetting your password, please follow the link bellow:</p> <br> <a href="' . $url . '">' . $url . '</a>';
        $mail->addAddress($email);

        $mail->Send();

        $sendSuccessful = 1;

    } else {
        $emailError = 1;
    }
    mysqli_stmt_close($stmt);
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
        <form class="loginform <? if ($sendSuccessful) { ?> disappear <? } ?> "
              action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 50%;">
            <p class="invalid_text"><? if ($emailError == 1) {
                    echo 'This email does not exist, please try again, or <a href="signup.php">Sign Up</a>';
                } ?> </p>
            <div class="form-group">
                <label for="inputUsername">Email</label>
                <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Enter your Email"
                       required <? if ($emailError == 1) { ?> value="<? echo $email; ?>" <? } ?>>
                <br>
                <p>An Email will be sent to you with instructions on how to reset your password.</p>

                <input type='hidden' name='submit'/>
                <button class="btn btn-primary" id="submitBtn" type="submit">Send Email</button>
            </div>
        </form>

        <div class="alert alert-success <? if (!$sendSuccessful) { ?> disappear <? } ?>" role="alert">
            <h4 class="alert-heading">Email Sent!</h4>
            <p>An email containing instructions on how to reset your password has been sent to the email you provided,
                please check your email!</p>
        </div>
    </div>

</div>

<? include("includes/footer.php"); ?>

<script>$('#inputEmail').select();</script>

</body>
</html>