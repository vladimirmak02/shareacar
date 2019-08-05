<header>
    <?
    if (!isset($_SESSION)) {
        session_start();
    }
    ?>
    <div style="background-image: linear-gradient(0deg, #00D8DB, #0050B3); height: 3em;padding-top: 3px; padding-left: 1em;">

        <ul class="nav nav-left">
            <a class="navbar-brand" href="/index.php"><img src="/res/logosmall.png"
                                                           style="height: 2em; margin-top: -0.2em;"></a>
            <li class="nav-item ">
                <a class="nav-link <? if ($CURRENT_PAGE == "Shareacar") { ?>active<? } ?>" href="/index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <? if ($CURRENT_PAGE == "Trips") { ?>active<? }
                if (!isset($_SESSION["loggedin"]) OR !$_SESSION["loggedin"]) { ?> disappear <? } ?>"
                   href="/trips.php">My trips</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <? if ($CURRENT_PAGE == "Profile") { ?>active<? }
                if (!isset($_SESSION["loggedin"]) OR !$_SESSION["loggedin"]) { ?> disappear <? } ?>"
                   href="/profile.php">Profile</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link <? if ($CURRENT_PAGE == "About") { ?>active<? } ?>" href="/about.php">About
                    Us</a>
            </li>

            <!--position: absolute; right: 0px;-->
        </ul>
        <ul class="nav nav-right">
            <li class="nav-item">

                <a class="nav-link <? if ($CURRENT_PAGE == "Login") { ?>active<? }
                if (isset($_SESSION["loggedin"])) {
                    if ($_SESSION["loggedin"] === true) { ?> disappear <? }
                } ?>"
                   href="/login.php">Log in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <? if ($CURRENT_PAGE == "Signup") { ?>active<? }
                if (isset($_SESSION["loggedin"])) {
                    if ($_SESSION["loggedin"] === true) { ?> disappear <? }
                } ?>" href="/signup.php">Sign
                    Up</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <? if (!isset($_SESSION["loggedin"]) OR !$_SESSION["loggedin"]) { ?> disappear <? } ?>"
                   href="/signout.php">Sign Out</a>
            </li>

            <!--<li class="nav-item">
            <a class="nav-link <? /*if ($CURRENT_PAGE == "Contact") {*/ ?>active<? /*}*/ ?>" href="contact.php">Contact</a>
        </li>-->
        </ul>
    </div>
</header>
