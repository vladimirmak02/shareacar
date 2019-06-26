<?php
function cleanInput($a)
{
    return filter_var(htmlspecialchars(trim($a)), FILTER_SANITIZE_STRING);
}

function cleanEmail($a)
{
    $b = htmlspecialchars(filter_var($a, FILTER_SANITIZE_EMAIL));
    $b = filter_var($b, FILTER_VALIDATE_EMAIL);
    if ($b != "") {
        return $b;
    } else {
        return false;
    }
}
