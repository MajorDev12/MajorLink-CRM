<?php
require_once "session_Config.php";
require_once  '../database/pdo.php';
require_once "../modals/validate_mod.php";
require_once "../modals/setup_mod.php";
require_once "../modals/changePaymentMethod_mod.php";

$connect  = connectToDatabase($host, $dbname, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sleep(2);

    $clientID = inputValidation($_POST["clientID"]);
    $selectedPaymentId = inputValidation($_POST["selectedPaymentId"]);

    $updatedCurrency = update_preferedPaymentMethod($connect, $clientID, $selectedPaymentId);

    $response = [
        'success' => $updatedCurrency
    ];

    echo json_encode($response);
    exit();
}
