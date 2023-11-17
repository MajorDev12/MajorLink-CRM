<?php
// Check if the request contains the currentAreaId
if (isset($_POST['currentAreaId'])) {
    sleep(1);
    // Include the necessary files
    require_once  '../database/pdo.php';
    require_once  '../modals/deleteArea_mod.php';
    // Get the currentAreaId from the request
    $areaId = $_POST['currentAreaId'];

    $connect = connectToDatabase($host, $dbname, $username, $password);

    // Call your delete function to delete the area with the provided areaId
    $deleted = deleteArea($areaId, $connect);

    // Send the response as an associative array
    $output = array(
        'success' => $deleted ? true : false
    );

    echo json_encode($output);
}
