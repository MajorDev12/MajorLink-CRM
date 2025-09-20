<?php
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message']) && isset($_POST['templateid'])) {
        sleep(1);
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/getSms_mod.php';


        $connect  = connectToDatabase($host, $dbname, $username, $password);

        // Retrieve the values from the form
        $body = inputValidation($_POST['message']);
        $templateID = inputValidation($_POST['templateid']);

        $updated = updateSmsTemplate($connect, $templateID, $body);


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
}
