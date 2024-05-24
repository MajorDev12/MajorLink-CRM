<?php
require_once "session_Config.php";
require_once  '../database/pdo.php';
require_once "../modals/validate_mod.php";
require_once "../modals/setup_mod.php";

$connect  = connectToDatabase($host, $dbname, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currencyName = inputValidation($_POST["selectedCurrency"]);
    $currencySymbol = inputValidation($_POST["currencySymbol"]);
    $currencyCode = inputValidation($_POST["currencyCode"]);
    $settingId = 1;

    $updatedCurrency = update_currency($connect, $currencyName, $currencySymbol, $currencyCode, $settingId);

    $response = [
        'success' => $updatedCurrency
    ];

    echo json_encode($response);
    exit();
}
