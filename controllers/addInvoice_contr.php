<?php


if (isset($_POST["selectedClientId"])) {
    require_once  '../database/pdo.php';
    require_once  '../modals/validate_mod.php';
    require_once  '../modals/addInvoice_mod.php';
    require_once  '../modals/setup_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);

    // get data
    $clientid = inputValidation($_POST["selectedClientId"]);
    $invoiceNumber = inputValidation($_POST["invoiceNumber"]);
    $paymentDate = inputValidation($_POST["paymentDate"]);
    $startDate = inputValidation($_POST["startDate"]);
    $expireDate = inputValidation($_POST["expireDate"]);
    $tax = inputValidation($_POST["tax"]);
    $taxSymbol = inputValidation($_POST["taxSymbol"]);
    $subtotal = inputValidation($_POST["subtotalAmount"]);
    $totalPrice = inputValidation($_POST["totalPrice"]);
    $invoiceProducts = json_decode($_POST["invoiceProducts"], true);
    $status = inputValidation($_POST["status"]);

    if (empty($invoiceNumber)) {
        $invoiceNumber = generateInvoiceNumber();
    }

    if (empty($tax)) {
        $tax = 0;
    }



    $addInvoice = addInvoice($connect, $clientid, $invoiceNumber, $totalPrice, $paymentDate, $startDate, $expireDate, $status, $taxSymbol, $tax);
    $saveProducts =  saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);


    $invoiceid =  getInvoiceIDByNumber($connect, $invoiceNumber);


    $response = [
        'addInvoice' => $addInvoice ? true : false,
        'saveProducts' => $saveProducts ? true : false,
        'invoiceid' => $invoiceid,
        'clientid' => $clientid
    ];

    echo json_encode($response);
}
