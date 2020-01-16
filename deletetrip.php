<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}

require_once "includes/inputValidation.php";

if (isset($_GET["trip"])) {
    $tripId = cleanInput($_GET["trip"]);
    $sql = "SELECT c.driverid FROM trips AS t INNER JOIN cars AS c ON c.carid = t.carid WHERE (tripid = ?)";

    if ($tripStmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($tripStmt, "s", $tripId);
    } else {
        echo "somehting went wrong1...";
    }
    if (mysqli_stmt_execute($tripStmt)) {
        mysqli_stmt_store_result($tripStmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($tripStmt) > 0) {
        //ADD ALL VARIABLES
        $driverId = NULL;
        if (mysqli_stmt_bind_result($tripStmt, $driverId)) {
            mysqli_stmt_fetch($tripStmt);
            if ($_SESSION["uid"] === $driverId) {
                $sql = "DELETE FROM trippassengers WHERE trip = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $tripId);
                } else {
                    echo "somehting went wrong1...";
                }
                if (mysqli_stmt_execute($stmt)) {
                    $sql = "DELETE FROM trips WHERE tripid = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $tripId);
                    } else {
                        echo "somehting went wrong3...";
                    }
                    if (mysqli_stmt_execute($stmt)) {
                        header("location: /trips.php");
                        exit;
                    }

                } else {
                    echo "Something went wrong. Please try again later.";
                }
            } else {
                header("location: /trips.php");
                exit;
            }

        }
    }
} else {
    header("location: /trips.php");
    exit;
}
