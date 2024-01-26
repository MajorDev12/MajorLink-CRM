<?php
$errors = array();
// Check if the request is a POST request
if (isset($_POST["clientId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/addPayment_mod.php';
    require_once  '../modals/updateStatus_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);


    $clientId = $_POST["clientId"];
    $PlanID = $_POST['selectedPlanID'];
    $PlanAmount = $_POST['selectedPlanAmount'];
    $paymentMethodID = $_POST['paymentMethodID'];
    $Paymentdate = $_POST['paymentDate'];
    $paymentStatus = $_POST['paymentStatus'];
    $InstallationFees = $_POST['InstallationFees'];
    $paymentReference = $_POST['paymentReference'];



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
    if ($paymentStatus === "Paid") {
        $activeStatus = true;
    }
    if ($paymentStatus === "Pending") {
        $activeStatus = false;
    }


    if ($activeStatus) {
        $last_paymentDate = $paymentDate;
    } else {
        $last_paymentDate = null;
    }


    // If there are no errors, insert data
    if (empty($errors)) {

        $paymentSuccess = insertPaymentData($clientId, $PlanID, $PlanAmount, $paymentStatus, $paymentDate, $InstallationFees, $connect);
        $updatedPlan = updatePlan($clientId, $PlanID, $expireDate, $last_paymentDate, $connect);
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
