<?php
if (isset($_POST["PaymentMethodInput"])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/addPaymentMethod_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);

    $error = '';
    $PaymentMethod = $_POST["PaymentMethodInput"];

    if (empty($PaymentMethod)) {
        $error = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $PaymentMethod)) {
            $error = 'Only Letters and White Space Allowed in First name';
        }
    }

    if (empty($error)) {
        $successinsert =  insertPaymentMethodData($PaymentMethod, $connect);
    }

    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $successinsert ? true : false
    ];

    echo json_encode($response);
}
