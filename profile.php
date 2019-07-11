<?php
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$firstname = "";
$lastname = "";
$email = "";
$username = "";

$sql = "SELECT first_name, last_name, username, email FROM users WHERE (id = ?)";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['id']);
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
    echo "ERROR BLYAT";
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
    <p>First name: <?php echo $firstname; ?></p>
    <p>Last name: <?php echo $lastname; ?></p>
    <p>Username: <?php echo $username; ?></p>
    <p>Email: <?php echo $email; ?></p>

    <button class="btn">Change Password</button>

</div>

<?php include("includes/footer.php"); ?>

</body>
</html>

