<?php

$errors = array();


if (isset($_POST["areaId"]) && isset($_POST["subArea"])) {
    sleep(1);

    //db info
    require_once  '../database/pdo.php';
    require_once  '../modals/addSubarea_mod.php';
    require_once  '../modals/getSubarea_mod.php';
    $host = "localhost";
    $dbname = "majorlink";
    $username = "root";
    $password = "123456";
    $connect  = connectToDatabase($host, $dbname, $username, $password);

    //get input data
    $success = '';
    $subArea = $_POST['subArea'];
    $areaId = $_POST['areaId'];

    if (empty($subArea)) {
        $errors[] = 'Cannot be empty';
    } else {
        if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $subArea)) {
            $errors[] = 'Only Letters, Numbers, and White Space Allowed';
        }
    }
    // If there are no errors, insert data
    if (empty($errors)) {
        insertSubarea($subArea, $areaId, $connect);
        $success = '<div class="text-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert-danger">' . implode('<br>', $errors) . '</div>';
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
