<?php

if (isset($_POST['updatedNameInput']) && isset($_POST['modalSubAreaId'])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateSubarea_mod.php';
    $connect = connectToDatabase($host, $dbname, $username, $password);

    $SubAreaId = $_POST['modalSubAreaId'];
    $updatedName = $_POST['updatedNameInput'];

    // Call your update function here and handle the update logic
    $updateSubAreas = updateSubArea($updatedName, $SubAreaId, $connect);


    // Send the response as an associative array
    $output = array(
        'success' => $updateSubAreas ? true : false
    );

    echo json_encode($output);
}
