<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["clientID"]) && isset($_POST["productID"])) {
        require_once  '../database/pdo.php';
        require_once  '../modals/validate_mod.php';
        require_once  '../modals/addSale_mod.php';
        require_once  '../modals/getTime_mod.php';
        require_once  '../modals/setup_mod.php';

        $connect = connectToDatabase($host, $dbname, $username, $password);
        $adminID = $_SESSION["adminID"];
        $settings = get_Settings($connect);

        // Get and sanitize input data
        $saleDate = inputValidation($_POST["saleDate"]);
        $clientID = inputValidation($_POST["clientID"]);
        $productID = inputValidation($_POST["productID"]);
        $quantity = inputValidation($_POST["quantity"]);
        $unitPrice = inputValidation($_POST["unitPrice"]);
        $total = inputValidation($_POST["total"]);
        $paymentMethodID = inputValidation($_POST["paymentMethodID"]);
        $PaymentStatus = inputValidation($_POST["PaymentStatus"]);
        $tax = inputValidation($_POST["taxAmount"]);
        $taxType = inputValidation($_POST["taxType"]);



        // Check for empty fields
        $requiredFields = [$saleDate, $clientID, $productID, $quantity, $unitPrice, $total, $paymentMethodID, $PaymentStatus];

        foreach ($requiredFields as $field) {
            if (empty($field)) {
                $response = [
                    'success' => false,
                    'message' => 'Fill in all fields with asterisk (*)'
                ];

                echo json_encode($response);
                exit();
            }
        }

        // Check for invalid quantity and tax
        if ($quantity <= 0 || $tax < 0) {
            $response = [
                'success' => false,
                'message' => 'Quantity and tax cannot be less than or equal to 0'
            ];

            echo json_encode($response);
            exit();
        }
        // generate unique invoice number
        $invoiceNumber = 'INV' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // Get current time
        $updatedDate = getTime($settings[0]["TimeZone"]);

        // Add sale to database
        $sale = addSale($connect, $saleDate, $adminID, $clientID, $productID, $invoiceNumber, $quantity, $unitPrice, $total, $paymentMethodID, $tax, $taxType, $PaymentStatus, $updatedDate);
        // Prepare and send response
        $response = [
            'success' => $sale ? true : false,
            'message' => $sale ? 'Sale added successfully' : 'Something went wrong'
        ];

        echo json_encode($response);
        exit();
    }
}
