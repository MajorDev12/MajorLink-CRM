<?php
$errors = array();

// Check if the form was submitted
if (isset($_POST['ProductId']) && isset($_POST['updatedProductName']) && isset($_POST['updatedProductPrice']) && isset($_POST['updatedProductNotes'])) {
    // sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateProduct_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);


    // Retrieve the values from the form
    $ProductId = inputValidation($_POST['ProductId']);
    $ProductName = inputValidation($_POST['updatedProductName']);
    $ProductPrice = inputValidation($_POST['updatedProductPrice']);
    $ProductNotes = inputValidation($_POST['updatedProductNotes']);
    // Perform any additional processing or validation as needed

    if (empty($ProductName)) {
        $errors[] = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $ProductName)) {
            $errors[] = 'Only Letters and White Space Allowed in First name';
        }
    }

    // if (empty($ProductNotes)) {
    //     $errors[] = 'Notes is Required';
    // } else {
    //     if (!preg_match("/^[a-zA-Z-' ]*$/", $ProductNotes)) {
    //         $errors[] = 'Only Letters and White Space Allowed';
    //     }
    // }

    if (empty($ProductPrice)) {
        $errors[] = 'price Cannot be empty';
    } else {
        if (!is_numeric($ProductPrice)) {
            $errors[] = 'Price Can Only be a Number';
        }
    }

    // If there are no errors, insert data
    if (empty($errors)) {
        updateProductData($ProductId, $ProductName, $ProductNotes, $ProductPrice, $connect);
        $success = '<div class="alert alert-success">Data Saved Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }

    $success = updateProductData($ProductId, $ProductName, $ProductNotes, $ProductPrice, $connect);


    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $success ? true : false
    ];

    echo json_encode($response);
    exit;
} else {
    // If the form wasn't submitted via POST, handle accordingly
    echo 'Invalid request method';
}
