<?php
$errors = array();
// Check if the request is a POST request
if (isset($_POST["ClientId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/updateBalance_mod.php';
    require_once  '../modals/checkAdvanceIsSet_mod.php';
    require_once  '../modals/addPayment_mod.php';
    require_once  '../modals/updateStatus_mod.php';


    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $clientId = $_POST["ClientId"];
    $PlanID = $_POST['PlanId'];
    $PlanAmount = $_POST['Amount'];
    $balance = $_POST['balance'];
    $paymentMethodID = $_POST['PaymentMethod'];
    $Paymentdate = $_POST['paymentDate'];
    $paymentStatus = $_POST['PaymentStatus'];
    $InstallationFees = $_POST['InstallationFees'];
    $paymentReference = $_POST['paymentReference'];

    $advancePaid = hasMadeAdvancePayment($clientId, $connect);

    if ($advancePaid) {
        // Code for clients who have made an advance payment
        // If the request method is not POST, return an error response
        $response = [
            'advancePaid' => true
        ];

        echo json_encode($response);
        return;
    }



    // Check if values are numeric
    if (!is_numeric($clientId) || !is_numeric($PlanID) || !is_numeric($PlanAmount) || !is_numeric($paymentMethodID)) {
        // Handle non-numeric values
        $errors[] = 'One or more values are Invalid.';
    }

    $dateObject = DateTime::createFromFormat('Y-m-d', $Paymentdate);
    // Convert PaymentDate to a DateTime object
    $paymentDateObject = $dateObject;

    // Add one month to the PaymentDate
    $expireDateObject = clone $paymentDateObject;
    $expireDateObject->add(new DateInterval('P1M')); // Adding 1 month to the payment date

    // Format the dates for insertion into the database
    $paymentDate = $paymentDateObject->format('Y-m-d');
    $expireDate = $expireDateObject->format('Y-m-d');

    // Check if paymentStatus is either "Paid" or "Pending"
    $activeStatus = 0;
    if ($paymentStatus === "Paid") {
        $activeStatus = true;
    }
    if ($paymentStatus === "Pending") {
        $activeStatus = false;
    }


    // If there are no errors, insert data
    if (empty($errors)) {
        // Check if balance is greater than 0
        if ($balance > 0) {
            // Update balance
            $balanceUpdated = updateBalance($clientId, $balance, $connect);
        }

        // Insert payment data, update plan, and change status
        $paymentSuccess = insertPaymentData($clientId, $PlanID, $PlanAmount, $paymentStatus, $paymentDate, $InstallationFees, $connect);
        $updatedPlan = updatePlan($clientId, $PlanID, $expireDate, $connect);
        $statusChanged = changeStatus($clientId, $activeStatus, $connect);


        $success = '<div class="alert alert-success">Client Added Successfuly</div>';
    } else {
        // If there are errors, construct an error message
        $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    }



    // Send a response back to the client
    $response = [
        'paymentSuccess' => $paymentSuccess ? true : false,
        'updatedPlan' => $updatedPlan ? true : false,
        'statusChanged' => $statusChanged ? true : false,
        'advancePaid' => false,
        'success' => $success
    ];


    echo json_encode($response);
} else {
    // If the request method is not POST, return an error response
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];

    echo json_encode($response);
}
