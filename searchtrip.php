<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}

require_once "includes/inputValidation.php";

if (isset($_POST['submit'])) {
//Perform search in database
    $foundTrip = 0;
    $country = cleanInput($_POST["country"]);
    $startCity = cleanInput($_POST["startcity"]);
    $endCity = cleanInput($_POST["endcity"]);

    if ($endCity === "a") {
        $sql = 'SELECT tripid, starttime, country, startcity, startstreet, endcity, endstreet, monday, tuesday, wednesday, thursday, friday, saturday, sunday FROM trips WHERE (startcity LIKE ?) AND (country = ?) ORDER BY starttime ASC';
        if ($tripStmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($tripStmt, "sss", $startCity, $country);
        } else {
            echo "somehting went wrong1...";
        }
    } else {
        $sql = 'SELECT tripid, starttime, country, startcity, startstreet, endcity, endstreet, monday, tuesday, wednesday, thursday, friday, saturday, sunday FROM trips WHERE (startcity LIKE ?) AND (country = ?) AND (endcity LIKE ?) ORDER BY starttime ASC';
        if ($tripStmt = mysqli_prepare($link, $sql)) {
            $tempStartCity = "%" . $startCity . "%";
            $tempEndCity = "%" . $endCity . "%";
            mysqli_stmt_bind_param($tripStmt, "sss", $tempStartCity, $country, $tempEndCity);
        } else {
            echo "somehting went wrong1...";
        }
    }
    if (mysqli_stmt_execute($tripStmt)) {
        mysqli_stmt_store_result($tripStmt);

    } else {
        echo "Something went wrong. Please try again later.";
    }
    if (mysqli_stmt_num_rows($tripStmt) > 0) {
        $tripId = $starttime = $country = $startcity = $startstreet = $endcity = $endstreet = $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = NULL;
        if (mysqli_stmt_bind_result($tripStmt, $tripId, $starttime, $country, $startcity, $startstreet, $endcity, $endstreet, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday)) {
            //fetch later
            $foundTrip = 1;
        }
    }
} else {

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

        <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mb-3">
            <div class="form-group row">
                <div class="col-2">
                    <label for="country" class="col-form-label">Country:</label>
                </div>
                <div class="col-4">
                    <select name="country" id="country" style class="form-control">
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
                </div>
                <div class="col-2">
                    <label for="startCity" class="col-form-label">Starting City</label>
                </div>
                <div class="col-4">
                    <input type="text" name="startcity" class="form-control" id="startCity"
                           placeholder="Enter the city where your trip is going to start" maxlength="50"
                           required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-2">
                    <label for="endCity" class="col-form-label">Destination City</label>
                </div>
                <div class="col-4">
                    <input type="text" name="endcity" class="form-control" id="endCity"
                           placeholder="End of the trip (you can leave this blank)" maxlength="50"
                    >
                </div>
                <div class="col-2">
                    <input type='hidden' name='submit'/>
                </div>
                <div class="col-4">
                    <button class="btn btn-success " id="searchBtn" type="submit">Search for trips</button>
                </div>
            </div>
        </form>

        <?
        if (isset($_POST['submit'])) {
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
            if ($foundTrip === 0) {
                ?>
                <div class="alert alert-warning m-3" role="alert">
                    <h4 class="alert-heading">No results :(</h4>
                    <p>Unfortunately, your search didn't yield any results, please try another set of search parameters
                        or
                        create your own trip <a href="createtrip.php" class="alert-link">here</a>!</p>
                </div>
                <?
            }
        } else {
            ?>
            <div class="alert alert-light m-3" role="alert">
                <h4 class="alert-heading">Please search for a trip</h4>
                <p>Please input your search parameters and click the search button!</p>
            </div>
            <?
        }

        ?>

    </div>
</div>

<? include("includes/footer.php"); ?>

<script>

</script>

</body>
</html>