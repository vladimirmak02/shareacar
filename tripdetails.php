<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}
require_once "includes/inputValidation.php";

require_once ".apikey.php";

$userIsDriver = 0;
$passengerApproved = NULL;
$userIsPassenger = NULL;


if (isset($_GET["trip"])) {
    $tripId = cleanInput($_GET["trip"]);
    $sql = "SELECT  t.carid, t.starttime, t.country, t.startcity, t.startstreet, t.endcity, t.endstreet, t.monday, t.tuesday, t.wednesday, t.thursday, t.friday, t.saturday, t.sunday, c.driverid, c.model, c.year, c.make, c.color, c.type, c.passengers, c.imagepath, u.first_name, u.last_name, u.email FROM trips AS t INNER JOIN cars AS c ON c.carid = t.carid INNER JOIN users AS u ON u.id = c.driverid WHERE (tripid = ?)";

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
        $carId = $starttime = $country = $startcity = $startstreet = $endcity = $endstreet = $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = $driverId = $carMake = $carModel = $carYear = $carColor = $carType = $passengerNumber = $carImagePath = $first_name = $last_name = $email = NULL;
        if (mysqli_stmt_bind_result($tripStmt, $carId, $starttime, $country, $startcity, $startstreet, $endcity, $endstreet, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday, $driverId, $carModel, $carYear, $carMake, $carColor, $carType, $passengerNumber, $carImagePath, $first_name, $last_name, $email)) {
            mysqli_stmt_fetch($tripStmt);
            if ($_SESSION["uid"] === $driverId) {
                $userIsDriver = 1;
            }

        }

        $sql = "SELECT u.first_name, u.last_name, u.email, t.time, t.startcity, t.startstreet, t.endcity, t.endstreet FROM trippassengers AS t 
INNER JOIN users AS u ON u.id = t.passenger
WHERE (t.trip = ?) AND (t.approved = 1) ORDER BY t.time";

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
            $passengerFirstName = $passengerLastName = $passengerEmail = $passengerTime = $passengerStartCity = $passengerStartStreet = $passengerEndCity = $passengerEndStreet = "";
            if (mysqli_stmt_bind_result($tripStmt, $passengerFirstName, $passengerLastName, $passengerEmail, $passengerTime, $passengerStartCity, $passengerStartStreet, $passengerEndCity, $passengerEndStreet)) {


            } else {
                echo "Binding output parameters failed: (" . $tripStmt->errno . ") " . $tripStmt->error;
            }
        }

        if ($userIsDriver === 0) {
            $userIsPassenger = 0;
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
                $userIsPassenger = 1;
                if (mysqli_stmt_bind_result($passengerStmt, $passengerApproved)) {
                    mysqli_stmt_fetch($passengerStmt);


                } else {
                    echo "Binding output parameters failed: (" . $passengerStmt->errno . ") " . $passengerStmt->error;
                }
            }
        } elseif ($userIsDriver === 1) {

            if (isset($_POST["status"])) {
                $passengerId = $_POST["passenger"];
                $passengerApproved = $_POST["status"];

                $sql = "UPDATE trippassengers SET approved = ? WHERE (passenger = ?)";

                if ($passengerStmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($passengerStmt, "is", $passengerApproved, $passengerId);
                } else {
                    echo "somehting went wrong2...";
                }
                if (mysqli_stmt_execute($passengerStmt)) {

                } else {
                    echo "Something went wrong. Please try again later.";
                }

            }

            $sql = "SELECT passenger, time, startcity, startstreet, endcity, endstreet FROM trippassengers WHERE (trip = ?) AND (approved = 0)";
            $unapprovedPassengers = 0;
            if ($passengerStmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($passengerStmt, "s", $tripId);
            } else {
                echo "somehting went wrong2...";
            }
            if (mysqli_stmt_execute($passengerStmt)) {
                mysqli_stmt_store_result($passengerStmt);

            } else {
                echo "Something went wrong. Please try again later.";
            }
            if (mysqli_stmt_num_rows($passengerStmt) > 0) {
                $unapprovedPassengers = mysqli_stmt_num_rows($passengerStmt);
                $passengerId = $passengerTime = $passengerStartCity = $passengerStartStreet = $passengerEndCity = $passengerEndStreet = "";
                if (mysqli_stmt_bind_result($passengerStmt, $passengerId, $passengerTime, $passengerStartCity, $passengerStartStreet, $passengerEndCity, $passengerEndStreet)) {


                } else {
                    echo "Binding output parameters failed: (" . $passengerStmt->errno . ") " . $passengerStmt->error;
                }
            }
        }

    } else {
        echo "error";
    }

}
?>


<!DOCTYPE html>
<html>
<!--
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

                            if ($_SESSION["uid"] === $driverId) {
                                $userIsDriver = 1;
                            }

                        } else {
                            echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                        }
                    }

                } else {
                    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
            }

            $sql = "SELECT passenger, time, startcity, startstreet, endcity, endstreet FROM trippassengers WHERE (trip = ?) AND (approved = 1)";

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
                $passengerId = $passengerTime = $passengerStartCity = $passengerStartStreet = $passengerEndCity = $passengerEndStreet = "";
                if (mysqli_stmt_bind_result($tripStmt, $passengerId, $passengerTime, $passengerStartCity, $passengerStartStreet, $passengerEndCity, $passengerEndStreet)) {


                } else {
                    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
            }

            if ($userIsDriver === 0) {
                $userIsPassenger = 0;
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
                    $userIsPassenger = 1;
                    if (mysqli_stmt_bind_result($passengerStmt, $passengerApproved)) {
                        mysqli_stmt_fetch($passengerStmt);


                    } else {
                        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                }
            } elseif ($userIsDriver === 1) {

                if (isset($_POST["status"])) {
                    $passengerId = $_POST["passenger"];
                    $passengerApproved = $_POST["status"];

                    $sql = "UPDATE trippassengers SET approved = ? WHERE (passenger = ?)";

                    if ($passengerStmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($passengerStmt, "is", $passengerApproved, $passengerId);
                    } else {
                        echo "somehting went wrong2...";
                    }
                    if (mysqli_stmt_execute($passengerStmt)) {

                    } else {
                        echo "Something went wrong. Please try again later.";
                    }

                }

                $sql = "SELECT passenger, time, startcity, startstreet, endcity, endstreet FROM trippassengers WHERE (trip = ?) AND (approved = 0)";
                $unapprovedPassengers = 0;
                if ($passengerStmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($passengerStmt, "s", $tripId);
                } else {
                    echo "somehting went wrong2...";
                }
                if (mysqli_stmt_execute($passengerStmt)) {
                    mysqli_stmt_store_result($passengerStmt);

                } else {
                    echo "Something went wrong. Please try again later.";
                }
                if (mysqli_stmt_num_rows($passengerStmt) > 0) {
                    $unapprovedPassengers = mysqli_stmt_num_rows($passengerStmt);
                    $passengerId = $passengerTime = $passengerStartCity = $passengerStartStreet = $passengerEndCity = $passengerEndStreet = "";
                    if (mysqli_stmt_bind_result($passengerStmt, $passengerId, $passengerTime, $passengerStartCity, $passengerStartStreet, $passengerEndCity, $passengerEndStreet)) {


                    } else {
                        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                    }
                }
            }

        } else {
            echo "error";
        }
    }

}
-->
<head>
    <? include("includes/head-tag-contents.php"); ?>
</head>
<body>

<div id="main">
    <? include("includes/navigation.php"); ?>
    <br>
    <div class="container" style="padding: 5px">

        <? if ($passengerApproved === 0) { ?>
            <div class="alert alert-warning m-3" role="alert">
                <h4 class="alert-heading">The driver has not approved your application <i>yet!</i></h4>
                <p>Please wait until the driver either accepts, or declines your application to this trip.</p>
            </div>
        <? } elseif ($passengerApproved === -1) { ?>
            <div class="alert alert-danger m-3" role="alert">
                <h4 class="alert-heading">The driver declined your application!</h4>
                <p>Unfortunately, your application to this trip was <i>not accepted</i> by the driver, please apply to
                    another trip, or create your own!</p>
                <!--TODO: ADD LINKS-->
            </div>
        <? } elseif ($passengerApproved === 1) { ?>
            <div class="alert alert-success m-3" role="alert">
                <h4 class="alert-heading">You are a passenger in this trip!</h4>
                <p>Your application to this trip was <i>accepted</i> by the driver, your stop was added to the map!</p>
                <!--TODO: ADD LINKS-->
            </div>
        <? } ?>

        <? if ($userIsDriver === 1 and $unapprovedPassengers > 0) {
            ?>
            <h4>Unapproved Passengers</h4>
            <? for ($i = 0; $i < $unapprovedPassengers; $i++) {
                mysqli_stmt_fetch($passengerStmt); ?>
                <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?trip=" . $tripId; ?>"
                      method="post">
                    <div class="form-group">
                        <label>Passenger
                            from <? echo $passengerStartStreet . ", " . $passengerStartCity . ". To " . $passengerEndStreet . ", " . $passengerEndCity . ". At " . $passengerTime; ?></label>
                        <input type="hidden" name="passenger" value="<? echo $passengerId; ?>">
                        <div class="form-inline">
                            <button class="btn btn-success" id="submitBtn" name="status" value="1"
                                    type="submit">Accept this passenger
                            </button>
                            <button class="btn btn-danger" id="submitBtn" name="status" value="-1"
                                    type="submit">Reject this passenger
                            </button>
                        </div>
                    </div>
                </form>
                <?
            }
        } ?>

        <div class="card card-body m-2">

            <div class="row alert alert-primary card-title">
                <div class="col-10">
                    <h5 class="mt-2">Trip
                        to: <? echo $endstreet . ", " . $endcity; ?></h5>
                </div>
                <div class="col-2">
                    <button class="nav-right btn btn-success <? if ($userIsPassenger === 1 OR $userIsDriver === 1) {
                        echo "disappear";
                    } ?>" type="button"
                            id="applyBtn"><a
                                href="/tripapply.php/?trip=<? echo $tripId; ?>"
                                target="_blank"
                                style="color: white">Apply to this trip</a></button>
                </div>
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

            <div id="tripCosts"></div>

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
        };
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        directionsDisplay.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    }

    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var waypts = [];
        var passengers = [];
        <? for ($i = 0; $i < $numberOfPassengers; $i++) { mysqli_stmt_fetch($tripStmt); ?>
        passengers.push({
            first_name: '<? echo $passengerFirstName?>',
            last_name: '<? echo $passengerLastName?>',
            email: '<? echo $passengerEmail?>',
            time: '<? echo $passengerTime?>'
        });
        waypts.push({
            location: '<? echo $passengerStartStreet . ", " . $passengerStartCity?>',
            stopover: true
        });
        waypts.push({
            location: '<? echo $passengerEndStreet . ", " . $passengerEndCity?>',
            stopover: true
        });
        <?}?>

        directionsService.route({
            origin: '<? echo $startstreet . ", " . $startcity . ", " . $country?>',
            destination: '<? echo $endstreet . ", " . $endcity?>',
            waypoints: waypts,
            optimizeWaypoints: false,
            travelMode: 'DRIVING'
        }, function (response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];
                for (var i = 0; i < route.legs.length; i++) {
                    $("#tripCosts").append('<b>Route Segment: ' + (i + 1).toString() + ', Passenger: ' + passengers[i].first_name + ' ' + passengers[i].last_name + ' <br>Email: ' + passengers[i].email + '</b><br>' + 'Time: ' + passengers[i].time + '<br>');
                    $("#tripCosts").append(route.legs[i].start_address + ' to <br>');
                    $("#tripCosts").append(route.legs[i].end_address + '<br>');
                    $("#tripCosts").append('Distance : ' + Math.round(route.legs[i].distance.value / 1000) + 'km, Cost : ' + Math.round(route.legs[i].distance.value * 0.002) + '$<br><br>');
                }

            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }

    //TODO: Delete trip and all passengers
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<? echo $mapsKEY ?>&callback=initMap">
</script>


</body>
</html>
