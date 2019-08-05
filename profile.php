<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}

if (isset($_POST['deleteCar'])) {
    $sql = "DELETE FROM cars WHERE (imagepath = ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_POST["deleteCar"]);
    } else {
        echo "somehting went wrong1...";
    }
    if (mysqli_stmt_execute($stmt)) {
        unlink($_POST["deleteCar"]) or die("Couldn't delete file");

    } else {
        echo "Something went wrong. Please try again later.";
    }
}

$firstname = "";
$lastname = "";
$email = "";
$username = "";

$sql = "SELECT first_name, last_name, username, email FROM users WHERE (username = ?)";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
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
    echo "nothing in database";
}


$hasCar = 1;
$nOfCars = 0;
$sql = "SELECT carid, model, year, make, color, type, passengers, imagepath FROM cars WHERE (driverid = ?)";
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
    $carYear = "";
    $carModel = "";
    $carColor = "";
    $carType = "";
    $passengerNumber = "";
    $carImagePath = "";
    if (mysqli_stmt_bind_result($stmt, $carId, $carModel, $carYear, $carMake, $carColor, $carType, $passengerNumber, $carImagePath)) {
        //fetch later
        $nOfCars = mysqli_stmt_num_rows($stmt);
    } else {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
} else {
    $hasCar = 0;
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
        <table class="table table-borderless" style="width: 50%;">
            <tr>
                <th scope="row" width="60%">First name</th>
                <td><? echo $firstname; ?></td>
            </tr>
            <tr>
                <th scope="row">Last name</th>
                <td><? echo $lastname; ?></td>
            </tr>
            <tr>
                <th scope="row">Username</th>
                <td><? echo $username; ?></td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td><? echo $email; ?></td>
            </tr>
            <tr>
                <th scope="row">Password</th>
                <td><a href="changepw.php">Change Password</a></td>
            </tr>
        </table>
        <? if ($hasCar === 0) { ?>
            <span>You haven't added a car yet!   </span>
            <a href="createcar.php">Create your car</a>
        <? } elseif ($hasCar > 0) {
            for ($i = 1; $i <= $nOfCars; $i++) {
                if (mysqli_stmt_fetch($stmt)) { ?>
                    <div class="card card-body">
                        <h5 class="card-title alert alert-primary">Car: <? echo $carMake . " " . $carModel; ?></h5>
                        <table class="table table-borderless" style="width: 50%;">
                            <img style="width: 70%;" src="/<? echo $carImagePath ?>">
                            <tr>
                                <th scope="row" width="60%">Number Plate (ID)</th>
                                <td><? echo $carId; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Make</th>
                                <td><? echo $carMake; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Year</th>
                                <td><? echo $carYear; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Model</th>
                                <td><? echo $carModel; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Color</th>
                                <td><? echo $carColor; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Type</th>
                                <td><? echo $carType; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Number of Passengers</th>
                                <td><? echo $passengerNumber; ?></td>
                            </tr>
                        </table>
                        <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type='hidden' name='deleteCar' value="<? echo $carImagePath; ?>"/>
                            <button class="btn btn-danger" id="deleteBtn<? echo $i; ?>" type="submit">Delete
                                your <? echo $carMake . " " . $carModel; ?></button>
                        </form>
                    </div>
                    <br>
                <? } else {
                    echo "Fetch Failed";
                }
            }
            ?> <a href="createcar.php">Add another car</a> <?
        } ?>
    </div>
</div>

<? include("includes/footer.php"); ?>

</body>
</html>