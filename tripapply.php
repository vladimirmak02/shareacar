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
// TODO: check if the driver is applying or if max number of passengers reached
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
} elseif (mysqli_stmt_num_rows($passengerStmt) === 0) {

    if (isset($_POST['submit'])) {
        $startTime = cleanInput($_POST["time"]);
        $startCity = cleanInput($_POST["startcity"]);
        $endCity = cleanInput($_POST["endcity"]);
        $startStreet = cleanInput($_POST["startstreet"]);
        $endStreet = cleanInput($_POST["endstreet"]);
        $approved = 0;
        $sql = "INSERT INTO trippassengers (trip, passenger, time, startcity, startstreet, endcity, endstreet, approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($passengerStmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($passengerStmt, "ssssssss", $tripId, $_SESSION["uid"], $startTime, $startCity, $startStreet, $endCity, $endStreet, $approved);
        } else {
            echo "somehting went wrong...";
        }
        if (mysqli_stmt_execute($passengerStmt)) {

            header("location: tripdetails.php?trip=" . $tripId);
            exit;

        } else {
            echo "Something went wrong. Please try again later.";
        }

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
    <br>
    <div class="container" style="padding: 5px">

        <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?trip=" . $tripId; ?>" method="post">
            <div class="form-group">
                <div style="width: 60%">
                    <label for="tripTime">Select the start time of your trip</label>
                    <input type="time" id="tripTime" name="time" class="form-control" required>

                    <label for="startCity">Origin City</label>
                    <input type="text" name="startcity" class="form-control" id="startCity"
                           placeholder="Enter the city where your trip is going to start" maxlength="50"
                           required>
                    <label for="startStreet">Starting Street and House Number (Your address)</label>
                    <input type="text" name="startstreet" class="form-control" id="startStreet"
                           placeholder="Enter the street and house number where your trip is going to start"
                           maxlength="50"
                           required>

                    <label for="endCity">Destination City</label>
                    <input type="text" name="endcity" class="form-control" id="endCity"
                           placeholder="Enter the city where your trip is going to end" maxlength="50"
                           required>
                    <label for="endStreet">Ending Street and House Number (Work address)</label>
                    <input type="text" name="endstreet" class="form-control" id="endStreet"
                           placeholder="Enter the street and house number where your trip is going to end"
                           maxlength="50"
                           required>

                    <br>
                    <input type='hidden' name='submit'/>
                    <button class="btn btn-primary" id="submitBtn" type="submit">Apply as a passenger</button>
                </div>
            </div>
        </form>

    </div>
</div>
<? include("includes/footer.php"); ?>
</body>
</html>
