<?php
require_once "session_Config.php";
require_once  '../database/pdo.php';
require_once "../modals/validate_mod.php";
require_once "../modals/setup_mod.php";

$connect  = connectToDatabase($host, $dbname, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $countryName = inputValidation($_POST["country"]);
    $countryTimezone = inputValidation($_POST["timezone"]);
    $currencyName = inputValidation($_POST["currency"]);
    $currencySymbol = inputValidation($_POST["symbol"]);
    $phoneCode = inputValidation($_POST["phoneCode"]);

    $settingId = 1;


    //check if country is set
    if (empty($countryName)) {
        $countryName = null;
    }

    //check if timezone is set & valid
    if (empty($countryTimezone)) {
        $countryTimezone = null;
    }
    //check if currencyName is set
    if (empty($currencyName)) {
        $currencyName = "United States Dollar";
    }
    //check if symbol is set
    if (empty($currencySymbol)) {
        $currencySymbol = "$";
    }
    //check if phonecode is set
    if (empty($phoneCode)) {
        $phoneCode = null;
    }


    $storedSetup = set_setup($countryName, $countryTimezone, $currencyName, $currencySymbol, $phoneCode, $settingId, $connect);


    $response = [
        'success' => $storedSetup
    ];

    echo json_encode($response);
    exit();
}
