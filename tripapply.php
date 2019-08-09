<?php


session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}
require_once "includes/inputValidation.php";

if (!isset($_GET['trip'])) {
    header("location: /trips.php");
    exit;
}

$tripId = $_GET['trip'];
$sql = "SELECT approved FROM trippassengers WHERE (trip = ?) AND (passenger = ?)";

if ($passengerStmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($passengerStmt, "ss", $tripId, $_SESSION["uid"]);
} else {
    echo "somehting went wrong2...";
}
if (mysqli_stmt_execute($passengerStmt)) {
    mysqli_stmt_store_result($passengerStmt);


} else {
    echo "Something went wrong. Please try again later.";
}
if (mysqli_stmt_num_rows($passengerStmt) > 0) {
    header("location: /tripdetails.php?trip=" . $tripId);
    exit;
}

?>