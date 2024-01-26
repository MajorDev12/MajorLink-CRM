<?php
$error = array();

if (isset($_POST["selectedClientId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once "../modals/advancePayment_mod.php";
    require_once  '../modals/updateStatus_mod.php';
    require_once  '../modals/updateBalance_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $clientId = $_POST["selectedClientId"];
    $paymentDate = $_POST['paymentDate'];
    $fromDate = $_POST['SetfromDate'];
    $amountToAdd = $_POST['balance'];
    $amountPaid = $_POST['amountSet'];
    $toDate = $_POST['calculatedExpireDate'];
    $selectedPlan = $_POST['selectedPlan'];
    $PaymentMethod = $_POST['PaymentMethod'];
    $newExpireDate = $toDate;



    // Check if the values are numeric
    if (!is_numeric($clientId) && !is_numeric($balance) && !is_numeric($amountPaid) && !is_numeric($selectedPlan) && !is_numeric($paymentMethod)) {
        // All values are numeric
        $error[] =  "One or more values are not numeric.";
    }

    $CreatedDate = date('Y-m-d');
    $activeStatus = 1;

    // If there are no errors, insert data
    if (empty($errors)) {

        // Insert payment data, update plan, and change status
        $paymentSuccess = insertadvancePaymentData($clientId, $paymentDate, $fromDate, $toDate, $PaymentMethod, $selectedPlan, $amountPaid, $CreatedDate, $connect);
        $statusChanged = changeStatus($clientId, $activeStatus, $newExpireDate, $connect);
        $balanceUpdated = updateBalance($clientId, $amountToAdd, $connect);

        $success = '<div class="alert alert-success">Client Added Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }



    // Send a response back to the client
    $response = [
        'paymentSuccess' => $paymentSuccess ? true : false,
        'statusChanged' => $statusChanged ? true : false,
        'balanceUpdated' => $balanceUpdated ? true : false,
        'success' => $success
    ];


    echo json_encode($response);
}
