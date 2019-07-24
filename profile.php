<?php
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$firstname = "";
$lastname = "";
$email = "";
$username = "";

$sql = "SELECT first_name, last_name, username, email FROM users WHERE (username = ?)";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
} else {
    echo "somehting went wrong1...";
}
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_store_result($stmt);

} else {
    echo "Something went wrong. Please try again later.";
}
if (mysqli_stmt_num_rows($stmt) == 1) {
    if (mysqli_stmt_bind_result($stmt, $firstname, $lastname, $username, $email)) {
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
        } else {
            echo "Fetch Failed";
        }
    } else {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
} else {
    echo "nothing in database";
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
    <br>
    <div class="container" style="padding: 5%">
        <table class="table table-borderless" style="width: 70%;">

            <tr>
                <th scope="row">First name</th>
                <td><?php echo $firstname; ?></td>
            </tr>
            <tr>
                <th scope="row">Last name</th>
                <td><?php echo $lastname; ?></td>
            </tr>
            <tr>
                <th scope="row">Username</th>
                <td><?php echo $username; ?></td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td><?php echo $email; ?></td>
            </tr>
            <tr>
                <th scope="row">Password</th>
                <td><a href="changepw.php">Change Password</a></td>
            </tr>
        </table>

    </div>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>

