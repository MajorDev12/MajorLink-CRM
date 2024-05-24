<?php
if (isset($_POST["expenseType"])) {
    sleep(1);
    require_once  '../database/pdo.php';
    require_once  '../modals/addExpenseType_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);

    $error = '';
    $expenseType = $_POST["expenseType"];

    if (empty($expenseType)) {
        $error = 'Name is Required';
    } else {
        if (!preg_match("/^[a-zA-Z0-9\s]+$/", $expenseType)) {
            $error = 'Only Letters and White Space Allowed in First name';
        }
    }

    if (empty($error)) {
        $successinsert =  insertexpenseTypeData($expenseType, $connect);
    }

    // For demonstration purposes, let's assume you want to return the same data as JSON
    $response = [
        'success' => $successinsert ? true : false
    ];

    echo json_encode($response);
}
