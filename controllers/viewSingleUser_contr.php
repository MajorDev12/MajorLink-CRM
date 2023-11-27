<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    sleep(2);

    require_once  '../database/pdo.php';
    require_once  '../modals/viewSingleUser_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);

    // Retrieve the clientID from the query parameter
    $clientID = $_GET['id'];



    // Check if a valid clientID is provided
    if ($clientID) {
        // Use $clientID to fetch the client data and display it
        $clientData = getClientDataById($connect, $clientID);

        if ($clientData) {
            // Include the view file to display the client data
            $clientData;
        } else {
            // Redirect with an error message
            header("Location: ../views/viewClient.php?error=nodatafound");
            exit(); // Terminate script execution after redirection
        }

        // include 'viewSingleUser.php';
    } else {
        // Handle the case where no valid clientID is provided
        header("Location: ../views/viewClient.php?error=notdatafound");
    }
}
