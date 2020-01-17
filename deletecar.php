<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}

require_once "includes/inputValidation.php";

if (isset($_GET["car"])) {

    $carId = cleanInput($_GET["car"]);
    $sql = "SELECT c.driverid, t.tripid, c.imagepath FROM cars AS c LEFT JOIN trips AS t ON t.carid = c.carid WHERE (c.carid = ?)";

    if ($tripStmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($tripStmt, "s", $carId);
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

        $driverId = $tripId = $carImagePath = NULL;
        if (mysqli_stmt_bind_result($tripStmt, $driverId, $tripId, $carImagePath)) {
            for ($i = 0; $i < mysqli_stmt_num_rows($tripStmt); $i++) {
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
                            echo "executed query number " . $i;
                        }


                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                } else {
                    header("location: /profile.php");
                    exit;
                }
            }
            $sql = "DELETE FROM cars WHERE carid = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $carId);
            } else {
                echo "somehting went wrong3...";
            }

            if (mysqli_stmt_execute($stmt)) {
                echo "deleted car";
                unlink($carImagePath) or die("Couldn't delete file");
                header("location: /profile.php");
                exit;
            }
        }

    }
} else {
    header("location: /profile.php");
    exit;
}
