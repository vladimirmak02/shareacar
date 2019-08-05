<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}


$showOwnTrips = 1;
$showOtherTrips = 1;
$selectSearch = 0;
$hasCar = 0;
$nOfCars = 0;
if (isset($_POST['submit'])) {
    switch ($_POST["selectSearch"]) {
        case "all":
            $selectSearch = 1;
            $showOwnTrips = 1;
            $showOtherTrips = 1;
            break;
        case "driver":
            $selectSearch = 2;
            $showOwnTrips = 1;
            $showOtherTrips = 0;
            break;
        case "passenger":
            $selectSearch = 3;
            $showOwnTrips = 0;
            $showOtherTrips = 1;
            break;
    }
}

if ($showOwnTrips === 1) {
    $sql = "SELECT carid, model, make FROM cars WHERE (driverid = ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['uid']);
    } else {
        echo "somehting went wrong1...";
    }
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $carId = "";
        $carMake = "";
        $carModel = "";
        if (mysqli_stmt_bind_result($stmt, $carId, $carModel, $carMake)) {
            //fetch later
            $nOfCars = mysqli_stmt_num_rows($stmt);
            $hasCar = 1;


        } else {
            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
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
        <div class="row mb-4">
            <div class="col-7">
                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-inline">
                    <div class="form-group">
                        <label for="selectSearch" class="mr-3">Show me </label>
                        <div class="input-group">
                            <select class="form-control " id="selectSearch" name="selectSearch">
                                <option <? if ($selectSearch === 1) {
                                    echo "selected";
                                } ?> value="all">All my trips
                                </option>
                                <option <? if ($selectSearch === 2) {
                                    echo "selected";
                                } ?> value="driver">Just those where im driving
                                </option>
                                <option <? if ($selectSearch === 3) {
                                    echo "selected";
                                } ?> value="passenger">Just those where im a passenger
                                </option>
                            </select>
                            <input type='hidden' name='submit'/>
                            <button class="btn btn-success " id="searchBtn" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-5">
                <button class="nav-right btn btn-primary mx-3 <? if ($hasCar === 0) {
                    echo "disappear";
                } ?>" type="button" id="addNewTripBtn"><a href="createtrip.php" style="color: white">Add your own
                        trip</a></button>
                <button class="nav-right btn btn-primary mx-3" type="button" id="searchTripBtn"><a href="searchtrip.php"
                                                                                                   style="color: white">Search
                        for a trip</a></button>
            </div>
        </div>


        <? if ($showOwnTrips === 1) {
            if ($hasCar === 0) { ?>
                <div class="alert alert-warning m-3" role="alert">
                    <h4 class="alert-heading">You do not have a car!</h4>
                    <p>Since you have no cars registered, we cannot show you any trips that you created yourself.
                        Please consider registering your own car in order to create your own trips, <a
                                href="createcar.php" class="alert-link">click
                            here</a> to do so!</p>
                </div>
            <? } elseif ($nOfCars >= 1) {
                for ($i = 1; $i <= $nOfCars; $i++) {
                    if (mysqli_stmt_fetch($stmt)) {
                        $sql = "SELECT tripid, starttime, country, startcity, startstreet, endcity, endstreet, monday, tuesday, wednesday, thursday, friday, saturday, sunday FROM trips WHERE (carid = ?)";
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
                            $tripId = $starttime = $country = $startcity = $startstreet = $endcity = $endstreet = $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = NULL;
                            if (mysqli_stmt_bind_result($tripStmt, $tripId, $starttime, $country, $startcity, $startstreet, $endcity, $endstreet, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday)) {
                                for ($k = 1; $k <= mysqli_stmt_num_rows($tripStmt); $k++) {
                                    if (mysqli_stmt_fetch($tripStmt)) { ?>
                                        <div class="card card-body m-2">
                                            <div class="row alert alert-primary card-title">
                                                <div class="col-10">
                                                    <h5 class="mt-2">Trip
                                                        to: <? echo $endstreet . ", " . $endcity; ?></h5>
                                                </div>
                                                <div class="col-2">
                                                    <button class="nav-right btn btn-primary" type="button"
                                                            id="searchTripBtn"><a
                                                                href="tripdetails.php/?trip=<? echo $tripId; ?>"
                                                                target="_blank"
                                                                style="color: white">Details</a></button>
                                                </div>
                                            </div>
                                            <table class="table table-borderless" style="width: 80%;">
                                                <tr>
                                                    <th scope="row">Car</th>
                                                    <td><? echo $carMake . " " . $carModel; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Country</th>
                                                    <td><? echo $country; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Starting Point Address</th>
                                                    <td><? echo $startstreet . ", " . $startcity; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Final Stop Address</th>
                                                    <td><? echo $endstreet . ", " . $endcity; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Time</th>
                                                    <td><? echo $starttime; ?></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Days</th>
                                                    <td>
                                                        <span <? if ($monday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   monday,   </span>
                                                        <span <? if ($tuesday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   tuesday,   </span>
                                                        <span <? if ($wednesday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   wednesday,   </span>
                                                        <span <? if ($thursday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   thursday,   </span>
                                                        <span <? if ($friday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   friday,   </span>
                                                        <span <? if ($saturday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   saturday,   </span>
                                                        <span <? if ($sunday === 0) {
                                                            echo 'class="disappear"';
                                                        } ?>>   sunday,   </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <?
                                    } else {
                                        echo "Fetch failed, please try again.";
                                    }
                                }
                            } else {
                                echo "Binding output parameters failed: (" . $tripStmt->errno . ") " . $tripStmt->error;
                            }
                        }
                    } else {
                        echo "Fetch error";
                    }
                }
            }
        }
        if ($showOtherTrips === 1) { ?>


        <? }
        ?>


    </div>
</div>

<? include("includes/footer.php"); ?>

<script>

</script>

</body>
</html>