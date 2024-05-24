<?php
// Check if the form was submitted
if (isset($_POST['subjectInput']) && isset($_POST['bodyInput'])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/validate_mod.php';
    require_once  '../modals/getEmail_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);

    // Retrieve the values from the form
    $body = inputValidation($_POST['bodyInput']);
    $subject = inputValidation($_POST['subjectInput']);
    $templateID = inputValidation($_POST['templateid']);

    $updated = updateEmailTemplate($connect, $templateID, $subject, $body);


    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $updated ? true : false
    ];

    echo json_encode($response);
    exit;
} else {
    // If the form wasn't submitted via POST, handle accordingly
    echo 'Invalid request method';
}
