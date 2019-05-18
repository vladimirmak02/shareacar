<header>
    <ul class="nav ">
        <li class="nav-item ">
            <a class="nav-link <?php if ($CURRENT_PAGE == "Index") { ?>active<?php } ?>" href="/index.php">Home</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link <?php if ($CURRENT_PAGE == "About") { ?>active<?php } ?>" href="/about.php">About Us</a>
        </li>

        <li class="nav-item" style="position: absolute; right: 0px;">
            <a class=" nav-link <?php if ($CURRENT_PAGE == "Login") { ?>active<?php } ?>" href="/login.php">Login</a>
        </li>
        <!--<li class="nav-item">
            <a class="nav-link <?php /*if ($CURRENT_PAGE == "Contact") {*/?>active<?php /*}*/?>" href="contact.php">Contact</a>
        </li>-->
    </ul>
</header>