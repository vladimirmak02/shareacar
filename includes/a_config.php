<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'shareacar';
$link = mysqli_connect($host, $user, $pass, $db);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

switch ($_SERVER["SCRIPT_NAME"]) {
    case "/about.php":
        $CURRENT_PAGE = "About";
        $PAGE_TITLE = "About Us";
        break;
    case "/login.php":
        $CURRENT_PAGE = "Login";
        $PAGE_TITLE = "Log in";
        break;
    case "/signup.php":
        $CURRENT_PAGE = "Signup";
        $PAGE_TITLE = "Sign up";
        break;
    case "/profile.php":
        $CURRENT_PAGE = "Profile";
        $PAGE_TITLE = "Profile";
        break;
    /*    case "/contact.php":
            $CURRENT_PAGE = "Contact";
            $PAGE_TITLE = "Contact Us";
            break;*/
    default:
        $CURRENT_PAGE = "Index";
        $PAGE_TITLE = "Welcome to my homepage!";
}
?>