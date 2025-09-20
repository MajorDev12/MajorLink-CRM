<?php
$errors = array();

// Check if the form was submitted
if (isset($_POST['planId']) && isset($_POST['updatedPlanName']) && isset($_POST['updatedPlanVolume']) && isset($_POST['updatedPlanPrice'])) {
    // sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updatePlan_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);


    // Retrieve the values from the form
    $planId = inputValidation($_POST['planId']);
    $planName = inputValidation($_POST['updatedPlanName']);
    $planVolume = inputValidation($_POST['updatedPlanVolume']);
    $planPrice = inputValidation($_POST['updatedPlanPrice']);
    // Perform any additional processing or validation as needed

    if (empty($planName)) {
        $errors[] = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $planName)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
        }
    }

    if (empty($planVolume)) {
        $errors[] = 'Volume Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $planVolume)) {
            $errors[] = 'Only Letters and White Space Allowed';
        }
    }

    if (empty($planPrice)) {
        $errors[] = 'price Cannot be empty';
    } else {
        if (!is_numeric($planPrice)) {
            $errors[] = 'Price Can Only be a Number';
        }
    }

    // If there are no errors, insert data
    if (empty($errors)) {
        updatePlanData($planId, $planName, $planVolume, $planPrice, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }

    $success = updatePlanData($planId, $planName, $planVolume, $planPrice, $connect);


    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $success ? true : false,
        'newRecord' => [
            'planName' => $planName,
            'planVolume' => $planVolume,
            'planPrice' => $planPrice,
        ],
    ];

    echo json_encode($response);
    exit;
} else {
    // If the form wasn't submitted via POST, handle accordingly
    echo 'Invalid request method';
}
