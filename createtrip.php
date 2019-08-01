<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require_once "includes/inputValidation.php";

$hasCar = 0;
$nOfCars = 0;
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
} else {
    header("location: trips.php");
    exit;
}

$tripExistsError = 0;

if (isset($_POST['submit'])) {

    $carId = cleanInput($_POST["car"]);
    $startTime = cleanInput($_POST["time"]);
    $country = cleanInput($_POST["country"]);
    $startCity = cleanInput($_POST["startcity"]);
    $endCity = cleanInput($_POST["endcity"]);
    $startStreet = cleanInput($_POST["startstreet"]);
    $endStreet = cleanInput($_POST["endstreet"]);
    if (isset($_POST["monday"])) {
        $monday = 1;
    } else {
        $monday = 0;
    }
    if (isset($_POST["tuesday"])) {
        $tuesday = 1;
    } else {
        $tuesday = 0;
    }
    if (isset($_POST["wednesday"])) {
        $wednesday = 1;
    } else {
        $wednesday = 0;
    }
    if (isset($_POST["thursday"])) {
        $thursday = 1;
    } else {
        $thursday = 0;
    }
    if (isset($_POST["friday"])) {
        $friday = 1;
    } else {
        $friday = 0;
    }
    if (isset($_POST["saturday"])) {
        $saturday = 1;
    } else {
        $saturday = 0;
    }
    if (isset($_POST["sunday"])) {
        $sunday = 1;
    } else {
        $sunday = 0;
    }

    $sql = "SELECT * FROM trips WHERE carid = ? AND startcity = ? AND endcity = ? AND startstreet = ? AND endstreet = ? AND starttime < ? AND starttime > ?";

    if ($chackStmt = mysqli_prepare($link, $sql)) {
        $tmpTime1 = strval(strtotime($startTime) + 60 * 60);
        $tmpTime2 = strval(strtotime($startTime) - 60 * 60);
        echo $tmpTime1;
        mysqli_stmt_bind_param($chackStmt, "sssssss", $carId, $startCity, $endCity, $startStreet, $endStreet, $tmpTime1, $tmpTime2);
    } else {
        echo "somehting went wrong...";
    }
    if (mysqli_stmt_execute($chackStmt)) {
        mysqli_stmt_store_result($chackStmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($chackStmt) === 0) {

        $sql = "INSERT INTO trips (carid, starttime, country, startcity, startstreet, endcity, endstreet, monday, tuesday, wednesday, thursday, friday, saturday, sunday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($chackStmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($chackStmt, "ssssssssssssss", $carId, $startTime, $country, $startCity, $startStreet, $endCity, $endStreet, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);
        } else {
            echo "somehting went wrong...";
        }
        if (mysqli_stmt_execute($chackStmt)) {

            header("location: trips.php");
            exit;

        } else {
            echo "Something went wrong. Please try again later.";
        }
    } else {
        $tripExistsError = 1;
    }

    mysqli_stmt_close($chackStmt);

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
        <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <div style="width: 60%">

                    <p class="invalid_text"> <?
                        if ($tripExistsError === 1) {
                            echo "This trip, or a very very similar one exists already, please try again!";
                        }
                        ?>
                    </p>

                    <label for="carSelect">Select the car you want to create a trip for</label>
                    <br>
                    <select name="car" id="carSelect" class="form-control">
                        <? for ($i = 0; $i < $nOfCars; $i++) {
                            mysqli_stmt_fetch($stmt); ?>
                            <option value="<? echo $carId; ?>" selected><? echo $carMake . " " . $carModel; ?></option>
                        <? } ?>
                    </select>

                    <label for="tripTime">Select the start time of your trip</label>
                    <input type="time" id="tripTime" name="time" class="form-control" required>

                    <label for="country">Select your country</label>
                    <select name="country" class="form-control">
                        <option value="Armenia">Armenia</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                        <option value="Canada">Canada</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="Germany">Germany</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Great Britain">Great Britain</option>
                        <option value="Greece">Greece</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Italy">Italy</option>
                        <option value="Korea North">Korea North</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Malta">Malta</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Netherlands">Netherlands (Holland, Europe)</option>
                        <option value="Norway">Norway</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Montenegro">Republic of Montenegro</option>
                        <option value="Romania">Romania</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Spain">Spain</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States of America">United States of America</option>
                        <option value="Vatican City State">Vatican City State</option>
                        <option value="Vietnam">Vietnam</option>
                    </select>

                    <label for="startCity">Starting City</label>
                    <input type="text" name="startcity" class="form-control" id="startCity"
                           placeholder="Enter the city where your trip is going to start" maxlength="50"
                           required>
                    <label for="startStreet">Select Starting Street and House Number (Your address)</label>
                    <input type="text" name="startstreet" class="form-control" id="startStreet"
                           placeholder="Enter the street and house number where your trip is going to start"
                           maxlength="50"
                           required>
                    <label for="endCity">Final City</label>
                    <input type="text" name="endcity" class="form-control" id="endCity"
                           placeholder="Enter the city where your trip is going to end" maxlength="50"
                           required>
                    <label for="endStreet">Select Final Street and House Number (Work address)</label>
                    <input type="text" name="endstreet" class="form-control" id="endStreet"
                           placeholder="Enter the street and house number where your trip is going to end"
                           maxlength="50"
                           required>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Monday" name="monday" value="1">
                    <label class="form-check-label" for="Monday">Monday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Tuesday" name="tuesday" value="1">
                    <label class="form-check-label" for="Tuesday">Tuesday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Wednesday" name="wednesday" value="1">
                    <label class="form-check-label" for="Wednesday">Wednesday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Thursday" name="thursday" value="1">
                    <label class="form-check-label" for="Thursday">Thursday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Friday" name="friday" value="1">
                    <label class="form-check-label" for="Friday">Friday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Saturday" name="saturday" value="1">
                    <label class="form-check-label" for="Saturday">Saturday</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="Sunday" name="sunday" value="1">
                    <label class="form-check-label" for="Sunday">Sunday</label>
                </div>
                <br>
                <input type='hidden' name='submit'/>
                <button class="btn btn-primary" id="submitBtn" type="submit">Add a new route</button>

            </div>
        </form>
    </div>
</div>

<? include("includes/footer.php"); ?>

<script>

</script>

</body>
</html>
