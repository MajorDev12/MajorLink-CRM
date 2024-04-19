<?php
$error = array();

if (isset($_POST["selectedClientId"])) {
    sleep(2);
    require_once  '../database/pdo.php';
    require_once "../modals/advancePayment_mod.php";
    require_once  '../modals/addPayment_mod.php';
    require_once  '../modals/addInvoice_mod.php';
    require_once  '../modals/updateStatus_mod.php';
    require_once  '../modals/viewSingleUser_mod.php';
    require_once  '../modals/setup_mod.php';
    require_once  '../modals/getTime_mod.php';
    require_once  '../modals/addPlan_mod.php';
    require_once  '../modals/infobip_mod.php';
    require_once  '../modals/notification_mod.php';

    $connect  = connectToDatabase($host, $dbname, $username, $password);
    $settings = get_Settings($connect);
    $timezone = $settings[0]["TimeZone"];
    $symbol = $settings[0]["CurrencySymbol"];
    $time = getTime($timezone);
    $CreatedDate = $time;

    $clientId = $_POST["selectedClientId"];
    $paymentDate = $_POST['paymentDate'];
    $fromDate = $_POST['SetfromDate'];
    $amountPaid = $_POST['amountSet'];
    $toDate = $_POST['calculatedExpireDate'];
    $selectedPlan = $_POST['selectedPlan'];
    $PaymentMethod = $_POST['PaymentMethod'];
    $selectedMonths = $_POST['durationInMonths'];
    $newExpireDate = $toDate;
    $subtotal = intval($amountPaid) * intval($selectedMonths);
    $invoiceNumber = generateInvoiceNumber();

    $planData = getPlanDataByID($connect, $selectedPlan);
    $PlanVolume = $planData['Volume'];
    $invoiceProducts[] = [
        'product' => $planData['Name'],
        'volume' => $planData['Volume'],
        'qty' => $selectedMonths,
        'price' => $planData['Price']
    ];

    $clientData = getClientDataById($connect, $clientId);
    $currentPlan = $clientData["PlanID"];
    $Clientnumber = $clientData["PrimaryNumber"];

    $changing = (intval($selectedPlan) !== intval($currentPlan));
    $daysRemaining = calculateLeftDays($clientData["ExpireDate"]);



    if ($daysRemaining > 0) {
        $last_paymentDate = $clientData['LastPayment'];
        // $expireDate->modify("+" . $daysRemaining . " days");
    } else {
        // Set last_paymentDate to paymentDate
        $last_paymentDate = $CreatedDate;
    }


    // Check if the values are numeric
    if (!is_numeric($clientId) && !is_numeric($amountPaid) && !is_numeric($selectedPlan) && !is_numeric($paymentMethod)) {
        // All values are numeric
        $error[] =  "One or more values are not numeric.";
    }


    $activeStatus = 1;
    $InstallationFees = 0;
    $paymentStatus = calculatePaymentStatus($connect, $clientId, $amountPaid);

    // Initialize variables
    $paymentSuccess = false;
    $statusChanged = false;
    $planChangeScheduled = false;
    $paymentInserted = false;
    $planUpdated = false;
    $invoiceCreated = false;
    $invoiceProductSaved = false;
    $messageSent = false;
    $messageSent2 = false;
    $sentSms = false;

    // Check for errors before proceeding
    if (empty($errors)) {
        // Perform actions based on condition
        if ($changing) {
            $paymentSuccess = insertadvancePaymentData($clientId, $paymentDate, $fromDate, $toDate, $PaymentMethod, $selectedPlan, $amountPaid, $CreatedDate, $connect);
            $planChangeScheduled = schedulePlanChange($clientId, $selectedPlan, $fromDate, $connect);
            $paymentInserted = insertPaymentData($clientId, $selectedPlan, $invoiceNumber, $amountPaid, $paymentStatus, $CreatedDate, $PaymentMethod, $InstallationFees, $connect);
        } else {
            $paymentSuccess = insertadvancePaymentData($clientId, $paymentDate, $fromDate, $toDate, $PaymentMethod, $selectedPlan, $amountPaid, $CreatedDate, $connect);
            $paymentInserted = insertPaymentData($clientId, $selectedPlan, $invoiceNumber, $amountPaid, $paymentStatus, $CreatedDate, $PaymentMethod, $InstallationFees, $connect);
        }

        // Update plan and change status
        $planUpdated = updatePlan($clientId, $currentPlan, $newExpireDate, $last_paymentDate, $connect);
        $statusChanged =  changeStatus($clientId, $activeStatus, $connect);

        // Create invoice, save products, and send messages
        $invoiceCreated = createSaveInvoice($clientId, $invoiceNumber, $amountPaid, $newExpireDate, $fromDate, $paymentDate, $symbol, $connect);
        $invoiceProductSaved = saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
        $messageSent = sendSuccessMessage($clientId, $CreatedDate, $connect);
        $messageSent2 = sendSuccessPlanChangeLaterMessage($clientId, $CreatedDate, $fromDate, $connect);
        $sentSms = sendTextMessage($Clientnumber, $PlanVolume, $newExpireDate);
    }

    // Check if there are any errors
    if (empty($errors)) {
        $success = 'Payment Added Successfully';
    } else {
        $success = implode('<br>', $errors);
    }

    // Construct response based on actions and errors
    $response = [
        'paymentSuccess' => $paymentSuccess,
        'statusChanged' => $statusChanged,
        'planChangeScheduled' => $planChangeScheduled,
        'paymentInserted' => $paymentInserted,
        'planUpdated' => $planUpdated,
        'invoiceCreated' => $invoiceCreated,
        'invoiceProductSaved' => $invoiceProductSaved,
        'messageSent' => $messageSent,
        'messageSent2' => $messageSent2,
        'sentSms' => $sentSms,
        'success' => $success
    ];

    echo json_encode($response);
}





function schedulePlanChange($ClientID, $paidPlanID, $initialExpireDate, $connect)
{
    try {
        // Prepare and execute SQL query to insert schedule details into the database table
        $query = "INSERT INTO plan_change_schedule (ClientID, NewPlanID, ScheduledDate) VALUES (:ClientID, :NewPlanID, :initialExpireDate)";
        $stmt = $connect->prepare($query);
        $stmt->bindParam(':ClientID', $ClientID);
        $stmt->bindParam(':NewPlanID', $paidPlanID);
        $stmt->bindParam(':initialExpireDate', $initialExpireDate);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        // Error handling
        echo "Error scheduling plan change: " . $e->getMessage();
        return false;
    }
}



function calculatePaymentStatus($connect, $clientID, $paidAmount)
{
    try {
        $clientData = getClientDataById($connect, $clientID);
        // Prepare SQL query to fetch the plan price for a given client ID
        $planAmount = $clientData["PlanPrice"];

        // Check if a result was obtained
        if ($planAmount) {
            if ($paidAmount <= 0) {
                return "Pending";
            } elseif ($paidAmount < $planAmount) {
                return "Partially Paid";
            } elseif ($paidAmount >= $planAmount) {
                return "Paid";
            } else {
                return "Cancelled"; // Handle any other cases
            }
        } else {
            // Handle the case when no result is found
            return false;
        }
    } catch (PDOException $e) {
        // Error handling
        echo "Error fetching plan price: " . $e->getMessage();
        return false;
    }
}



function calculateLeftDays($expireDate)
{
    $expiredate = new DateTime($expireDate);
    $today = new DateTime();

    // Ensure daysRemaining is not negative
    if ($expiredate < $today) {
        return 0;
    } else {
        return max(0, $today->diff($expiredate)->days);
    }
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
    $invoiceAdded = addInvoice($connect, $ClientID, $invoiceNumber, $totalAmount, $paymentDate, $startDate, $dueDate, $status, $taxSymbol, $taxAmount);

    if ($invoiceAdded) {
        return true;
    } else {
        return false;
    }
}



function sendSuccessMessage($ClientID, $createdDate, $connect)
{
    $SenderName = 'system';
    $MessageType = 'Transaction-success';
    $MessageContent = 'Your Advance payment has been received successfully';
    $Status = 0;
    $inserted = insertMessage($connect, $SenderName, $ClientID, $MessageType, $MessageContent, $createdDate, $Status);
    if ($inserted) {
        return true;
    } else {
        return false;
    }
}



function sendSuccessPlanChangeMessage($ClientID, $createdDate, $connect)
{
    $SenderName = 'system';
    $MessageType = 'Change Plan-success';
    $MessageContent = 'You have successfully changed Your Subsciption Plan';
    $Status = 0;
    $inserted = insertMessage($connect, $SenderName, $ClientID, $MessageType, $MessageContent, $createdDate, $Status);
    if ($inserted) {
        return true;
    } else {
        return false;
    }
}


function sendSuccessPlanChangeLaterMessage($ClientID, $createdDate, $firstDate, $connect)
{
    $SenderName = 'system';
    $MessageType1 = 'Change Plan-success';
    $MessageType2 = 'Change Plan-notification';
    $MessageContent1 = 'You have successfully changed Your Subscription Plan';
    $MessageContent2 = 'Your Subscription Plan will take effect on ' . $firstDate;

    // Insert first message
    $inserted = insertMessage($connect, $SenderName, $ClientID, $MessageType1, $MessageContent1, $createdDate, 0);

    // Insert second message
    $inserted2 = insertMessage($connect, $SenderName, $ClientID, $MessageType2, $MessageContent2, $createdDate, 0);

    if ($inserted && $inserted2) {
        return true;
    } else {
        return false;
    }
}


function sendTextMessage($Clientnumber, $planVolume, $expireDate)
{
    $expireDate = new DateTime($expireDate);
    $expireDate = $expireDate->format('j F Y');

    $provider = 'Infobip';
    $message = 'You have successfully subscribed to ' . $planVolume . '. Your subscription will be renewed on ' . $expireDate . ' Thank you for choosing MajorLink';
    $sent = sendSMS($provider, $Clientnumber, $message);
    if ($sent) {
        return true;
    } else {
        return false;
    }
}
