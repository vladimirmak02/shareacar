<?php


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
    $sql = "SELECT carid, starttime, country, startcity, startstreet, endcity, endstreet, monday, tuesday, wednesday, thursday, friday, saturday, sunday FROM trips WHERE (tripid = ?)";

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
        $carId = $starttime = $country = $startcity = $startstreet = $endcity = $endstreet = $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = NULL;
        if (mysqli_stmt_bind_result($tripStmt, $carId, $starttime, $country, $startcity, $startstreet, $endcity, $endstreet, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday)) {
            mysqli_stmt_fetch($tripStmt);

            $sql = "SELECT driverid, model, year, make, color, type, passengers, imagepath FROM cars WHERE (carid = ?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $carId);
            } else {
                echo "somehting went wrong1...";
            }
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

            } else {
                echo "Something went wrong. Please try again later.";
            }
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $driverId = "";
                $carMake = "";
                $carModel = "";
                $carYear = "";
                $carColor = "";
                $carType = "";
                $passengerNumber = "";
                $carImagePath = "";
                if (mysqli_stmt_bind_result($stmt, $driverId, $carModel, $carYear, $carMake, $carColor, $carType, $passengerNumber, $carImagePath)) {
                    mysqli_stmt_fetch($stmt);

                    $sql = "SELECT first_name, last_name, email FROM users WHERE (id= ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "s", $driverId);
                    } else {
                        echo "somehting went wrong1...";
                    }
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);

                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $first_name = $last_name = $email = "";
                        if (mysqli_stmt_bind_result($stmt, $first_name, $last_name, $email)) {
                            mysqli_stmt_fetch($stmt);


                        } else {
                            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                        }
                    }

                } else {
                    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
            }

            $sql = "SELECT passenger, time, city, street, approved FROM trippassengers WHERE (trip = ?)";

            if ($tripStmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($tripStmt, "s", $tripId);
            } else {
                echo "somehting went wrong2...";
            }
            if (mysqli_stmt_execute($tripStmt)) {
                mysqli_stmt_store_result($tripStmt);
                $numberOfPassengers = mysqli_stmt_num_rows($tripStmt);

            } else {
                echo "Something went wrong. Please try again later.";
            }
            if ($numberOfPassengers > 0) {
                $passengerId = $passengerTime = $passengerCity = $passengerStreet = "";
                $passengerApproved = 0;
                if (mysqli_stmt_bind_result($tripStmt, $passengerId, $passengerTime, $passengerCity, $passengerStreet, $passengerApproved)) {
                    mysqli_stmt_fetch($tripStmt);


                } else {
                    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
            }

        } else {
            echo "error";
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

        <div class="card card-body m-2">
            <div class="alert alert-primary card-title">

                <h5 class="mt-2">Trip
                    to: <? echo $endstreet . ", " . $endcity; ?></h5>

            </div>
            <table class="table table-borderless" style="width: 80%;">
                <tr>
                    <th scope="row">Car Make and Model</th>
                    <td><? echo $carMake . " " . $carModel; ?></td>
                </tr>
                <tr>
                    <th scope="row">Image of the Car</th>
                    <td>
                        <img style="width: 100%;" src="/<? echo $carImagePath ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Car Year</th>
                    <td><? echo $carYear; ?></td>
                </tr>
                <tr>
                    <th scope="row">Car Color</th>
                    <td><? echo $carColor; ?></td>
                </tr>
                <tr>
                    <th scope="row">Car Type</th>
                    <td><? echo $carType; ?></td>
                </tr>
                <tr>
                    <th scope="row">Passenger Seats Available</th>
                    <td><? echo $passengerNumber - $numberOfPassengers; ?></td>
                </tr>
                <tr>
                    <th scope="row">Driver Name</th>
                    <td><? echo $first_name . " " . $last_name; ?></td>
                </tr>
                <tr>
                    <th scope="row">Driver Email</th>
                    <td><? echo $email; ?></td>
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
                    <th scope="row">Start Time</th>
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

    </div>
</div>

<div id="map"
     style="height: 100%; width: 100%; margin-bottom: 5rem; margin-left: 0%; margin-right: 0%; margin-top: -5rem"></div>

<? include("includes/footer.php"); ?>

<script>
    function initMap() {
        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var mapOptions = {
            //Some options maybe?
        }
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        directionsDisplay.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        for (var i = 0; i < <? echo $numberOfPassengers?>; i++) {
            waypts.push({
                location: '<? echo $passengerStreet . ", " . $passengerCity?>',
                stopover: false
            });
        }

        directionsService.route({
            origin: '<? echo $startstreet . ", " . $startcity . ", " . $country?>',
            destination: '<? echo $endstreet . ", " . $endcity?>',
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        }, function (response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];
                /*var summaryPanel = document.getElementById('directions-panel');
                summaryPanel.innerHTML = '';
                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    var routeSegment = i + 1;
                    summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                        '</b><br>';
                    summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                    summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                    summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
                }*/
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJzLXoUHiXP_SZX6aKoocacsejOiNY7Xk&callback=initMap">
</script>


</body>
</html>
