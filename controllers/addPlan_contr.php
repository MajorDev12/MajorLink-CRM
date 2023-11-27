<?php
sleep(1);

$errors = array();

// Check if the form was submitted
if (isset($_POST['planName']) && isset($_POST['planVolume']) && isset($_POST['planPrice'])) {

    require_once  '../database/pdo.php';
    require_once  '../modals/addPlan_mod.php';
    require_once  '../modals/validate_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);


    // Retrieve the values from the form
    $planName = inputValidation($_POST['planName']);
    $planVolume = inputValidation($_POST['planVolume']);
    $planPrice = inputValidation($_POST['planPrice']);

    // Perform any additional processing or validation as needed

    if (empty($planName)) {
        $errors[] = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $planName)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
        }
    }

    if (empty($planVolume)) {
        $errors[] = 'Volume Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $planVolume)) {
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
        $successinsert = insertPlanData($planName, $planVolume, $planPrice, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }

    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $successinsert ? true : false,
        'newRecord' => [
            'planName' => $planName,
            'planVolume' => $planVolume,
            'planPrice' => $planPrice,
        ]
    ];

    echo json_encode($response);
} else {
    // If the form wasn't submitted via POST, handle accordingly
    echo 'Invalid request method';
}
