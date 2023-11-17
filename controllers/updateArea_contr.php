<?php

if (isset($_POST['updatedAreaName']) && isset($_POST['currentAreaId'])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateArea_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);

    $updatedAreaName = $_POST['updatedAreaName'];
    $areaId = $_POST['currentAreaId'];

    // Call your update function here and handle the update logic
    $updatedAreas = updateArea($updatedAreaName, $areaId, $connect);


    // Send the response as an associative array
    $output = array(
        'success' => $updatedAreas ? true : false
    );

    echo json_encode($output);
}
