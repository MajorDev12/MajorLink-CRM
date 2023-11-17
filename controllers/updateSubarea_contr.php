<?php

if (isset($_POST['updatedName']) && isset($_POST['currentAreaId'])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateArea_mod.php';

    $connect = connectToDatabase($host, $dbname, $username, $password);

    $updatedAreaName = $_POST['updatedName'];
    $areaId = $_POST['currentAreaId'];

    // Call your update function here and handle the update logic
    $updatedAreas = updateArea($updatedAreaName, $areaId, $connect);

    // Fetch all areas after deletion
    $areas = getData($connect);

    // Send the response as an associative array
    $output = array(
        'success' => $updatedAreas ? true : false,
        'areas' => $areas
    );

    echo json_encode($output);
}
