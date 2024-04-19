<?php
$errors = array();

// Check if the request is a POST request
if (isset($_POST["ClientId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once  '../modals/viewSingleUser_mod.php';
    require_once  '../modals/checkAdvanceIsSet_mod.php';
    require_once  '../modals/addPayment_mod.php';
    require_once  '../modals/updateStatus_mod.php';
    require_once  '../modals/getTime_mod.php';
    require_once  '../modals/setup_mod.php';
    require_once  '../modals/addInvoice_mod.php';
    require_once  '../modals/addPlan_mod.php';
    require_once  '../modals/infobip_mod.php';
    require_once  '../modals/notification_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);
    $settings = get_Settings($connect);
    $timezone = $settings[0]["TimeZone"];
    $symbol = $settings[0]["CurrencySymbol"];
    $time = getTime($timezone);

    $clientId = $_POST["ClientId"];
    $PlanID = $_POST['PlanId'];
    $PlanAmount = $_POST['Amount'];
    $paymentMethodID = $_POST['PaymentMethod'];
    $Paymentdate = $_POST['paymentDate'];
    $paymentStatus = $_POST['PaymentStatus'];
    $InstallationFees = $_POST['InstallationFees'];

    $clientData = getClientDataById($connect, $clientId);
    $Clientnumber = $clientData["PrimaryNumber"];

    $invoiceNumber = generateInvoiceNumber();

    $planData = getPlanDataByID($connect, $PlanID);
    $PlanVolume = $planData['Volume'];
    $paidMonths = 1;
    $invoiceProducts[] = [
        'product' => $planData['Name'],
        'volume' => $planData['Volume'],
        'qty' => $paidMonths,
        'price' => $planData['Price']
    ];



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
    $last_paymentDate = $paymentDateObject->format('Y-m-d');
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
        // Insert payment data, update plan, and change status
        if ($activeStatus) {
            $paymentSuccess = insertPaymentData($clientId, $PlanID, $invoiceNumber, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect);
            $updatedPlan = updatePlan($clientId, $PlanID, $expireDate, $last_paymentDate, $connect);
            $statusChanged = changeStatus($clientId, $activeStatus, $connect);
            $success = 'Payment Added Successfuly';

            createSaveInvoice($clientId, $invoiceNumber, $PlanAmount, $expireDate, $time, $paymentDate, $symbol, $connect);
            saveInvoiceProducts($connect, $invoiceNumber, $PlanAmount, $invoiceProducts);
            sendSuccessMessage($clientId, $time, $connect);
            sendTextMessage($Clientnumber, $PlanVolume, $expireDate);
        } else {
            $paymentSuccess = insertPaymentData($clientId, $PlanID, $invoiceNumber, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect);
            $updatedPlan = updatePlan($clientId, $PlanID, $expireDate, $last_paymentDate, $connect);
            $statusChanged = changeStatus($clientId, $activeStatus, $connect);
            $success = 'Payment Added Successfuly';
        }
    } else {
        // If there are errors, construct an error message
        $success = implode('<br>', $errors);
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
    exit();
} else {
    // If the request method is not POST, return an error response
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];

    echo json_encode($response);
}









function createSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $time, $paymentDate, $taxSymbol, $connect)
{
    $totalAmount = $paidAmount;
    $startDate = $time;
    $paymentDate = $paymentDate;
    $taxSymbol = $taxSymbol;
    $taxAmount = 0;
    $dueDate = $expireDate;
    $status = "Paid";
    addInvoice($connect, $ClientID, $invoiceNumber, $totalAmount, $paymentDate, $startDate, $dueDate, $status, $taxSymbol, $taxAmount);
}


function sendSuccessMessage($ClientID, $createdDate, $connect)
{
    $SenderName = 'system';
    $MessageType = 'Transaction-success';
    $MessageContent = 'Your payment has been received successfully';
    $Status = 0;
    insertMessage($connect, $SenderName, $ClientID, $MessageType, $MessageContent, $createdDate, $Status);
}

function sendTextMessage($Clientnumber, $planVolume, $expireDate)
{
    $expireDate = new DateTime($expireDate);
    $expireDate = $expireDate->format('j F Y');

    $provider = 'Infobip';
    $message = 'You have successfully subscribed to ' . $planVolume . '. Your subscription will be renewed on ' . $expireDate . ' Thank you for choosing MajorLink';
    sendSMS($provider, $Clientnumber, $message);
}
