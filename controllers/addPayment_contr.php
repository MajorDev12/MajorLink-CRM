<?php
$errors = array();
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["clientId"])) {
        sleep(2);
        require_once  '../database/pdo.php';
        require_once  '../modals/addPayment_mod.php';
        require_once  '../modals/updateStatus_mod.php';
        require_once  '../modals/setup_mod.php';
        require_once  '../modals/addPlan_mod.php';
        require_once  '../modals/addInvoice_mod.php';
        require_once  '../modals/notification_mod.php';
        require_once  '../modals/sendSms_mod.php';
        require_once  '../modals/getTime_mod.php';
        require_once  '../modals/viewSingleUser_mod.php';

        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $settings = get_Settings($connect);
        $timezone = $settings[0]["TimeZone"];
        $symbol = $settings[0]["CurrencySymbol"];
        $code = $settings[0]["PhoneCode"];
        $time = getTime($timezone);

        $clientId = $_POST["clientId"];
        $PlanID = $_POST['selectedPlanID'];
        $PlanAmount = $_POST['selectedPlanAmount'];
        $paymentMethodID = $_POST['paymentMethodID'];
        $Paymentdate = $_POST['paymentDate'];
        $paymentStatus = $_POST['paymentStatus'];
        $InstallationFees = $_POST['InstallationFees'];
        $invoiceNumber = generateInvoiceNumber();
        $activeStatus = false;
        $last_paymentDate = $Paymentdate;

        $planData = getPlanDataByID($connect, $PlanID);
        $PlanVolume = $planData['Volume'];
        $paidMonths = 1;
        $invoiceProducts[] = [
            'product' => $planData['Name'],
            'volume' => $planData['Volume'],
            'qty' => $paidMonths,
            'price' => $planData['Price']
        ];

        $clientData = getClientDataById($connect, $clientId);
        $primaryNumber = $clientData["PrimaryNumber"];
        $to = $code . $primaryNumber;


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



        // If there are no errors, insert data
        if (empty($errors)) {

            if ($activeStatus) {
                $paymentSuccess = insertPaymentData($clientId, $PlanID, $invoiceNumber, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect);
                $updatedPlan = updatePlan($clientId, $PlanID, $expireDate, $last_paymentDate, $connect);
                $statusChanged = changeStatus($clientId, $activeStatus, $connect);
                $notified = sendSuccessMessage($clientId, $time, $connect);
                $messageSent = sendTextMessage($to, $PlanVolume, $expireDate);
                $success = '<div class="alert alert-success">Client Added Successfuly</div>';
            } else {
                $paymentSuccess = insertPaymentData($clientId, $PlanID, $invoiceNumber, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect);
                $success = '<div class="alert alert-success">Client Added Successfuly</div>';
            }

            createSaveInvoice($clientId, $invoiceNumber, $PlanAmount, $expireDate, $time, $paymentStatus, $paymentDate, $symbol, $connect);
            saveInvoiceProducts($connect, $invoiceNumber, $PlanAmount, $invoiceProducts);
        } else {
            // If there are errors, construct an error message
            $success = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
        }


        exit();

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
}



function createSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $time, $status, $paymentDate, $taxSymbol, $connect)
{
    $totalAmount = $paidAmount;
    $startDate = $time;
    $paymentDate = $paymentDate;
    $taxSymbol = $taxSymbol;
    $taxAmount = 0;
    $dueDate = $expireDate;
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
    $message = 'You have successfully changed your subscription plan ' . $planVolume . '. Your subscription will be renewed on ' . $expireDate . ' Thank you for choosing MajorLink';
    sendSMS($provider, $Clientnumber, $message);
}
