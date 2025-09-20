<?php
// Check if the request contains the currentAreaId
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['modalSubAreaId'])) {
        sleep(1);
        // Include the necessary files
        require_once  '../database/pdo.php';
        require_once  '../modals/deleteSubArea_mod.php';
        // Get the currentAreaId from the request
        $SubAreaId = $_POST['modalSubAreaId'];

        $connect = connectToDatabase($host, $dbname, $username, $password);

        // Call your delete function to delete the area with the provided areaId
        $deleted = deleteSubArea($SubAreaId, $connect);

        // Send the response as an associative array
        $output = array(
            'success' => $deleted ? true : false
        );

        echo json_encode($output);
    }
}
