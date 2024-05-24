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


function getGreeting()
{
    $currentHour = date('H');

    // Define greeting messages based on the time of the day
    if ($currentHour >= 5 && $currentHour < 12) {
        $greeting = "Good Morning";
    } elseif ($currentHour >= 12 && $currentHour < 17) {
        $greeting = "Afternoon";
    } elseif ($currentHour >= 17 && $currentHour < 20) {
        $greeting = "Good Evening";
    } else {
        $greeting = "Good Night";
    }

    return $greeting;
}
