<?php


if (isset($_POST["areaInput"])) {
    sleep(1);
    //db info
    require_once  '../database/pdo.php';
    require_once  '../modals/addArea_mod.php';
    require_once  '../modals/validate_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);
    //get input data
    $areaname = inputValidation($_POST['areaInput']);


    // if (emptyInput($areaname) !== false) {
    //     header("location: ../views/addArea.php?error=emptyInput");
    //     // $_SESSION['error'] = "emptyInput";
    //     exit();
    // }
    // if (isValidInput($areaname) !== false) {
    //     header("location: ../views/addArea.php?error=isInvalid");
    // $_SESSION['error'] = "only letters and numbers";
    //     exit();
    // }
    $insertData = insertArea($areaname, $connect);
    // $_SESSION['error'] = "success";
    // $session = $_SESSION['error'];


    // Send the response as an associative array
    $output = array(
        'success' => $insertData
    );

    echo json_encode($output);
}
