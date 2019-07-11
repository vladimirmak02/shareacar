<header>
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    ?>
    <div style="background-image: linear-gradient(0deg, #00D8DB, #0050B3); height: 3em;padding-top: 3px; padding-left: 1em;">

        <ul class="nav nav-left">
            <a class="navbar-brand" href="/index.php"><img src="/res/logosmall.png"
                                                           style="height: 2em; margin-top: -0.2em;"></a>
            <li class="nav-item ">
                <a class="nav-link <?php if ($CURRENT_PAGE == "Index") { ?>active<?php } ?>" href="/index.php">Home</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link <?php if ($CURRENT_PAGE == "About") { ?>active<?php } ?>" href="/about.php">About
                    Us</a>
            </li>
            <!--position: absolute; right: 0px;-->
        </ul>
        <ul class="nav nav-right">
            <li class="nav-item">

                <a class="nav-link <?php if ($CURRENT_PAGE == "Login") { ?>active<?php }
                if ($_SESSION["loggedin"] === true) { ?> disappear <?php } ?>"
                   href="/login.php">Log in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($CURRENT_PAGE == "Signup") { ?>active<?php }
                if ($_SESSION["loggedin"] === true) { ?> disappear <?php } ?>" href="/signup.php">Sign
                    Up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if (!$_SESSION["loggedin"]) { ?> disappear <?php } ?>"
                   href="/signout.php">Sign Out</a>
            </li>

            <!--<li class="nav-item">
            <a class="nav-link <?php /*if ($CURRENT_PAGE == "Contact") {*/ ?>active<?php /*}*/ ?>" href="contact.php">Contact</a>
        </li>-->
        </ul>
    </div>
</header>
