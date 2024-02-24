<?php

function getTime($CurrentTimezone)
{
    $targetTimezone = $CurrentTimezone;

    // Set the default timezone
    date_default_timezone_set($targetTimezone);

    // Get the current date and time in the specified timezone
    $currentDateTime = date('Y-m-d H:i:s');

    // Display the formatted date and time
    return $currentDateTime;
}
