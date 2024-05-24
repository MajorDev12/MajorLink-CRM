<?php
$errors = array();

// Check if the form was submitted
if (isset($_POST['expenseTypeID']) && isset($_POST['updatedexpenseType'])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateExpenseType_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);


    // Retrieve the values from the form
    $expenseTypeID = inputValidation($_POST['expenseTypeID']);
    $updatedexpenseType = inputValidation($_POST['updatedexpenseType']);

    if (empty($updatedexpenseType)) {
        $errors[] = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $updatedexpenseType)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
        }
    }


    // If there are no errors, insert data
    if (empty($errors)) {
        $updateSuccess = updateProductData($expenseTypeID, $updatedexpenseType, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }



    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $updateSuccess ? true : false
    ];

    echo json_encode($response);
    exit;
} else {
    // If the form wasn't submitted via POST, handle accordingly
    echo 'Invalid request method';
}
