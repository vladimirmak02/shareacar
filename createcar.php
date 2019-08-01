<?
session_start();

require_once "includes/a_config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) AND $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "includes/inputValidation.php";

$carId = "";
$driverId = $_SESSION["uid"];
$carMake = "";
$carYear = "";
$carModel = "";
$carColor = "";
$carType = "";
$passengerNumber = 0;
$carIdError = 0;

if (isset($_POST['submit'])) {
    $carId = cleanInput($_POST["carid"]);
    $carMake = cleanInput($_POST["make"]);
    $carYear = cleanInput($_POST["year"]);
    $carModel = cleanInput($_POST["model"]);
    $carColor = cleanInput($_POST["color"]);
    $carType = cleanInput($_POST["type"]);
    $passengerNumber = cleanInput($_POST["passengernumber"]);


    //CHECK CARID
    $sql = "SELECT driverid FROM cars WHERE (carid = ?)";

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
        $carIdError = 1;
        mysqli_stmt_close($stmt);
    }
    if ($carIdError === 0) {
        $carImageFile = $_FILES['file'];
        $carImageName = $carImageFile['name'];
        $carImageTmpname = $carImageFile['tmp_name'];
        $carImageError = $carImageFile['error'];
        $fileExttmp = explode('.', $carImageName);
        $fileExt = strtolower(end($fileExttmp));
        $carImageNewName = $carId . "user" . $driverId . $_SESSION['username'] . "." . $fileExt;
        $fileDestination = 'res/useruploads/' . $carImageNewName;
        move_uploaded_file($carImageTmpname, $fileDestination);

        $sql = "INSERT INTO cars (carid, driverid, model, year, make, color, type, passengers, imagepath) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sisisssis", $carId, $driverId, $carModel, $carYear, $carMake, $carColor, $carType, $passengerNumber, $fileDestination);
        } else {
            echo "somehting went wrong...";
        }
        if (mysqli_stmt_execute($stmt)) {
            header("location: profile.php");
            exit;
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
        <form action="<? echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
              style="width: 60%;" enctype="multipart/form-data">
            <p class="invalid_text"> <?
                if ($carIdError == 1) {
                    echo "A car with such a plate number already exists, please try again!";
                }
                ?>
            </p>
            <div class="form-group">
                <div class="custom-file hoverHand">
                    <input type="file" class="custom-file-input hoverHand" name="file" id="carimage"
                           accept="image/png, image/jpeg" required>
                    <label class="custom-file-label hoverHand" for="carimage">Choose an image of your car, preferrably
                        with the <b>Number Plate visible</b></label>
                </div>

                <label for="carid">Number Plate</label>
                <input type="text" name="carid" class="form-control" id="carid" minlength="5" maxlength="20"
                       placeholder="Enter your number plate"
                       required>
                <label for="make">Car make / manufacturer</label>
                <input type="text" name="make" class="form-control" id="make" maxlength="30"
                       placeholder="Enter your car make"
                       required>
                <label for="year">Year</label>
                <input type="number" name="year" class="form-control" id="year" minlength="4"
                       min="1950" max="2020" placeholder="1950"
                       required>
                <label for="model">Car model</label>
                <input type="text" name="model" class="form-control" id="model"
                       placeholder="Enter your car model" maxlength="30"
                       required>
                <label for="color">Color</label>
                <input type="text" name="color" class="form-control" id="color"
                       placeholder="Enter your car color" maxlength="30"
                       required>
                <label for="type">Body Type</label>
                <br>
                <select name="type" id="type" class="form-control">
                    <option value="" selected>Choose your car type...</option>
                    <option value="Compact">Compact</option>
                    <option value="Convertible">Convertible</option>
                    <option value="Coupe">Coupe</option>
                    <option value="Offroad">Off-road/Pick-up</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Hatchback">Station Wagon / Hatchback</option>
                    <option value="Transporter">Transporter</option>
                    <option value="Van">Van</option>
                    <option value="Other">Other</option>
                </select>
                <p id="selecterror" class="invalid_text" style="font-size: 0.8em">Please select one of the options</p>
                <label for="passengernumber">Number of possible passengers</label>
                <input type="number" name="passengernumber" class="form-control" id="passengernumber"
                       min="1" max="10" placeholder="1"
                       required>
                <br>
                <input type='hidden' name='submit'/>
                <button class="btn btn-primary" id="submitBtn" type="submit" disabled="true">Add a new car</button>
            </div>
        </form>
    </div>

</div>

<? include("includes/footer.php"); ?>

<script> $("form #type").change(function () {
        if ($("form #type").val() != "") {
            $("#submitBtn").prop("disabled", false);
            $("#selecterror").html("");
        } else {
            $("#submitBtn").prop("disabled", true);
            $("#selecterror").html("Please select one of the options");
        }
    });
    $(document).on('change', ':file', function () {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', label);
    });
    $(':file').on('fileselect', function (event, label) {

        $(".custom-file-label").html("File Chosen: " + label);

    });

</script>

</body>
</html>

