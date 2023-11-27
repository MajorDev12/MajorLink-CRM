<?php
$errors = array();


if (isset($_POST["areaId"])) {

    //db info
    require_once  '../database/pdo.php';
    require_once  '../modals/addSubarea_mod.php';
    require_once  '../modals/getSubarea_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);

    //get input data
    $success = '';
    $areaId = $_POST['areaId'];

    // If there are no errors, insert data
    if (!empty($areaId)) {
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">areaid is empty</div>';
    }


    // Fetch all areas after insertion (including the newly added one)
    $subareas = getSubareasByAreaId($connect, $areaId);


    // Send the response as an associative array
    $output = array(
        'success' => $success,
        'subareas' => $subareas
    );
    echo json_encode($output);
}
