<header>
    <div style="background-color: rgba(109,229,232,0.71); height: 3em; padding-top: 3px;">
        <ul class="nav nav-left">
        <li class="nav-item ">
            <a class="nav-link <?php if ($CURRENT_PAGE == "Index") { ?>active<?php } ?>" href="/index.php">Home</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link <?php if ($CURRENT_PAGE == "About") { ?>active<?php } ?>" href="/about.php">About Us</a>
        </li>
            <!--position: absolute; right: 0px;-->
        </ul>
        <ul class="nav nav-right">
            <li class="nav-item">

                <a class=" nav-link <?php if ($CURRENT_PAGE == "Login") { ?>active<?php } ?>" href="/login.php">Log
                    in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($CURRENT_PAGE == "Signup") { ?>active<?php } ?>" href="/signup.php">Sign
                    Up</a>
            </li>

            <!--<li class="nav-item">
            <a class="nav-link <?php /*if ($CURRENT_PAGE == "Contact") {*/?>active<?php /*}*/?>" href="contact.php">Contact</a>
        </li>-->
        </ul>
    </div>
</header>