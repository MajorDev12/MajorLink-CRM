<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["details"]) && isset($_POST["paymentData"])) {

        // Include database connection file  
        require_once  '../database/pdo.php';

        require_once '../modals/getPaypalTransaction_mod.php';
        require_once  '../modals/setup_mod.php';
        require_once  '../modals/updateStatus_mod.php';
        require_once  '../modals/viewSingleUser_mod.php';
        require_once  '../modals/addPayment_mod.php';
        require_once  '../modals/notification_mod.php';
        require_once  '../modals/addInvoice_mod.php';
        require_once  '../modals/addPlan_mod.php';
        require_once  '../modals/sendSms_mod.php';
        require_once  '../modals/getTime_mod.php';
        require_once  '../modals/config.php';



        $connect  = connectToDatabase($host, $dbname, $username, $password);
        $settings = get_Settings($connect);
        $timezone = $settings[0]["TimeZone"];
        $phoneCode = $settings[0]["PhoneCode"];
        date_default_timezone_set($timezone);
        $createdDate = getTime($timezone);


        //get data
        $details = $_POST['details'];
        $order = $_POST['paymentData'];
        $PlanID = isset($_POST['planID']) ? $_POST['planID'] : null;
        $PaidPlanID = $_POST['paidPlanID'];
        $paidMonths = intval($_POST['paidMonths']);
        $changing = ($_POST['changing'] === 'true') ? true : false;
        $changingNow = ($_POST['changingNow'] === 'true') ? true : false;
        $amount = $_POST['amount'];
        $ClientID = $_SESSION['clientID'];
        $paymentMethodID = 2;
        $InstallationFees = 0;
        $activeStatus = 1;
        $tax = 0;
        $taxSymbol = $settings[0]["CurrencySymbol"];
        $subtotal = $amount * $paidMonths;
        $invoiceProducts = [];

        $clientData = getClientDataById($connect, $ClientID);
        $invoiceNumber = generateInvoiceNumber();
        if (isset($_POST['planID']) && !empty($_POST['planID'])) {
            $paidplanid = $_POST['planID'];
        } else {
            $paidplanid = $_POST['paidPlanID'];
        }
        $planData = getPlanDataByID($connect, $paidplanid);
        $paymentStatus = calculatePaymentStatus($connect, $paidplanid, $amount, $paidMonths);
        $daysRemaining = calculateLeftDays($clientData["ExpireDate"]);
        $currentPlan = $clientData["PlanID"];
        $planVolume = $planData['Volume'];
        $to = $clientData['PrimaryNumber'];
        $Clientnumber = $phoneCode . $to;
        // var_dump($changing);
        // exit();





        if (isset($planData)) {
            $invoiceProducts[] = [
                'product' => $planData['Name'],
                'volume' => $planData['Volume'],
                'qty' => $paidMonths,
                'price' => $planData['Price']
            ];
        }






        // payment details
        $customer_name = $details['payer']['name']['given_name'] . ' ' . $details['payer']['name']['surname'];
        $customer_email = $details['payer']['email_address'];
        $paidAmount = $details['purchase_units'][0]['amount']['value'];
        $paidCurrency = $details['purchase_units'][0]['amount']['currency_code'];
        $payment_status = $details['status'];
        // order details
        $order_id = $order['orderID'];
        $payment_id = $order['paymentID'];

        // calculate expireDate
        $initialExpireDate = strtotime($clientData['ExpireDate']);

        if (!$changing) {
            $expireDate = calculateExpireDate($initialExpireDate, $paidMonths, $timezone);
        } elseif ($changing && $changingNow) {
            $initialcreatedDate = strtotime($createdDate);
            $expireDate = calculateExpireDate($initialcreatedDate, $paidMonths, $timezone);
        } elseif ($changing && !$changingNow) {
            $expireDate = calculateExpireDate($initialExpireDate, $paidMonths, $timezone);
        }


        $initialDate = $clientData["ExpireDate"];

        if ($changingNow) {
            $startDate = $createdDate;
        } else {
            $startDate = date('Y-m-d', strtotime($initialDate . ' +1 day'));
            $expireDate = date('Y-m-d', strtotime($expireDate . ' +1 day'));
        }


        // calculate last_payment
        if ($daysRemaining > 0) {
            $last_paymentDate = $clientData['LastPayment'];
        } else {
            // Set last_paymentDate to paymentDate
            $last_paymentDate = $createdDate;
        }



        if ($payment_status === "COMPLETED") {

            // Process payment and update client data
            if (!$changing) {

                // process payment
                $paymentInserted = insertPaymentData($ClientID, $PlanID, $invoiceNumber, $amount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                $planUpdated = updatePlan($ClientID, $PlanID, $expireDate, $last_paymentDate, $connect);
                $statusChanged = changeStatus($ClientID, $activeStatus, $connect);
                $transactionSet = setPaypalTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $order_id);


                // Call createAndSaveInvoice function with the generated invoice number
                $invoiceAdded = addInvoice($connect, $ClientID, $invoiceNumber, $subtotal, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                $productsAdded = saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                $notified = sendSuccessMessage($ClientID, $createdDate, $connect);
                $smsSent = sendTextMessage($Clientnumber, $planVolume, $expireDate);
            } else {

                if ($changingNow) {
                    $immediateChanged = updateClientDataForImmediateChange($ClientID, $PaidPlanID, $createdDate, $expireDate, $connect);
                    $paymentInserted = insertPaymentData($ClientID, $PlanID, $invoiceNumber, $amount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                    $statusChanged = changeStatus($ClientID, $activeStatus, $connect);
                    $transactionSet = setPaypalTransaction($connect, $ClientID, $customer_name, $customer_email, $amount, $paidCurrency, $createdDate, $payment_id, $payment_status, $order_id);

                    // Call createAndSaveInvoice function with the generated invoice number
                    $invoiceAdded = addInvoice($connect, $ClientID, $invoiceNumber, $subtotal, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                    $productsAdded = saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                    $notified = sendSuccessMessage($ClientID, $createdDate, $connect);
                    $changedNow = sendSuccessPlanChangeMessage($ClientID, $createdDate, $connect);
                    $smsSent = sendTextMessage($Clientnumber, $planVolume, $expireDate);
                } else {
                    $scheduled = schedulePlanChange($ClientID, $PaidPlanID, $initialExpireDate, $connect);

                    $paymentInserted = insertPaymentData($ClientID, $PaidPlanID, $invoiceNumber, $amount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                    $planUpdated = updatePlan($ClientID, $currentPlan, $expireDate, $last_paymentDate, $connect);
                    $statusChanged = changeStatus($ClientID, $activeStatus, $connect);
                    $transactionSet = setPaypalTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $order_id);


                    // Call createAndSaveInvoice function with the generated invoice number
                    $invoiceAdded = addInvoice($connect, $ClientID, $invoiceNumber, $subtotal, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                    $productsAdded = saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                    $notified = sendSuccessMessage($ClientID, $createdDate, $connect);
                    $changedLater = sendSuccessPlanChangeLaterMessage($ClientID, $createdDate, $startDate, $connect);
                    $smsSent = sendTextMessage($Clientnumber, $planVolume, $expireDate);
                }
            }




            $response = [
                'paymentInserted' => $paymentInserted ? true : false,
                'planUpdated' => $planUpdated ? true : false,
                'statusChanged' => $statusChanged ? true : false,
                'transactionSet' => $transactionSet ? true : false,
                'invoiceAdded' => $invoiceAdded ? true : false,
                'productsAdded' => $productsAdded ? true : false,
                'notified' => $notified ? true : false,
                'smsSent' => $smsSent ? true : false
            ];

            echo json_encode($response);
            exit();
        } else {
            $response = [
                'success' => false
            ];

            echo json_encode($response);
            exit();
        }
    } else {
        $response = [
            'success' => false
        ];

        echo json_encode($response);
        exit();
    }
}


function calculatePaymentStatus($connect, $paidplanid, $paidAmount, $selectedMonths)
{
    try {
        $planData = getPlanDataByID($connect, $paidplanid);
        $planAmount = $planData['Price'];

        $paidAmount = $paidAmount / $selectedMonths;

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



// Function to calculate expire date
function calculateExpireDate($initialExpireDate, $selectedMonths, $timezone)
{
    // Check if $initialExpireDate is a string
    if (is_string($initialExpireDate)) {
        // Convert the initial expire date string to a Unix timestamp
        $initialExpireDate = strtotime($initialExpireDate);
    }

    // Convert the Unix timestamp to a date string
    $initialExpireDateString = date('Y-m-d', $initialExpireDate);

    // Set the timezone
    date_default_timezone_set($timezone);

    // Convert the initial expire date to a DateTime object
    $initialExpireDateTime = new DateTime($initialExpireDateString);

    // Add the selected months to the initial expire date
    $initialExpireDateTime->modify("+$selectedMonths months");

    // Return the new expire date
    return $initialExpireDateTime->format('Y-m-d');
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


function sendSuccessPlanChangeMessage($ClientID, $createdDate, $connect)
{
    $SenderName = 'system';
    $MessageType = 'Change Plan-success';
    $MessageContent = 'You have successfully changed Your Subsciption Plan';
    $Status = 0;
    insertMessage($connect, $SenderName, $ClientID, $MessageType, $MessageContent, $createdDate, $Status);
}


function sendSuccessPlanChangeLaterMessage($ClientID, $createdDate, $firstDate, $connect)
{
    $SenderName = 'system';
    $MessageType1 = 'Change Plan-success';
    $MessageType2 = 'Change Plan-notification';
    $MessageContent1 = 'You have successfully changed Your Subscription Plan';
    $MessageContent2 = 'Your Subscription Plan will take effect on ' . $firstDate;

    // Insert first message
    insertMessage($connect, $SenderName, $ClientID, $MessageType1, $MessageContent1, $createdDate, 0);

    // Insert second message
    insertMessage($connect, $SenderName, $ClientID, $MessageType2, $MessageContent2, $createdDate, 0);
}


function updateClientDataForImmediateChange($ClientID, $paidPlanID, $paymentDate, $expireDate, $connect)

{
    try {
        // Prepare SQL query to update client data
        $query = "UPDATE clients 
                                          SET PlanID = :paidPlanID, 
                                              LastPayment = :paymentDate, 
                                              ExpireDate = :expireDate
                                          WHERE ClientID = :ClientID";
        $stmt = $connect->prepare($query);

        // Bind parameters
        $stmt->bindParam(':ClientID', $ClientID);
        $stmt->bindParam(':paidPlanID', $paidPlanID);
        $stmt->bindParam(':paymentDate', $paymentDate);
        $stmt->bindParam(':expireDate', $expireDate);

        // Execute query
        $stmt->execute();

        // Success message or logging
        return true;
    } catch (PDOException $e) {
        // Error handling
        echo "Error updating client data: " . $e->getMessage();
        return false;
    }
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


















































// set API Endpoint, access key, required parameters
// $endpoint = 'convert';
// $access_key = 'API_KEY';

// $from = 'USD';
// $to = 'USD';
// $amount = 10;

// // initialize CURL:
// $ch = curl_init('https://api.exchangeratesapi.io/v1/' . $endpoint . '?access_key=' . $access_key . '&from=' . $from . '&to=' . $to . '&amount=' . $amount . '');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // get the JSON data:
// $json = curl_exec($ch);
// curl_close($ch);

// // Decode JSON response:
// $conversionResult = json_decode($json, true);

// // access the conversion result
// echo $conversionResult['result'];





















































// sleep(2);
// require '../paypal/rest-api/vendor/autoload.php'; // Include the PayPal PHP SDK

// use PayPal\Api\Amount;
// use PayPal\Api\Payer;
// use PayPal\Api\Payment;
// use PayPal\Api\RedirectUrls;
// use PayPal\Api\Transaction;


// // Your PayPal credentials
// $clientId = 'Ae4orbdIICDrUSBdOqXB0HbAwz41DvXZwYt9UXlCOska-hYHUEw2YkXEblL0N4VNgBmtAt9G8H7Gq1Mt';
// $clientSecret = 'EJ_VF8yUNoB_CA5h9EF8as_S4DHY8QFGwZErM41cFhw1TqFtkke2F68ZFQdq8oLdLjmctfvvM8NfEeVO';

// // Set up PayPal API context
// $apiContext = new \PayPal\Rest\ApiContext(
//     new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
// );



// $amountPaid = $_POST['amount'];



// // Create a new payment
// $payer = new Payer();
// $payer->setPaymentMethod('paypal');

// $amount = new Amount();
// $amount->setTotal($amountPaid); // Set the total amount
// $amount->setCurrency('USD'); // Set the currency code

// $transaction = new Transaction();
// $transaction->setAmount($amount);


// $baseUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// $baseUrl .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

// $redirectUrls = new RedirectUrls();
// $redirectUrls->setReturnUrl($baseUrl . '/paypal/rest-api/success.php')
//     ->setCancelUrl($baseUrl . '/paypal/rest-api/Error.php');


// $payment = new Payment();
// $payment->setIntent('sale')
//     ->setPayer($payer)
//     ->setTransactions([$transaction])
//     ->setRedirectUrls($redirectUrls);

// // Create payment and get approval URL
// try {
//     $payment->create($apiContext);

//     // Get PayPal approval URL
//     $approvalUrl = $payment->getApprovalLink();

//     // Return the approval URL to your JavaScript code
//     echo json_encode(['success' => true, 'message' => 'we did it']);
//     exit();
// } catch (\PayPal\Exception\PayPalConnectionException $e) {
//     // Log the error
//     echo json_encode([
//         'success' => false,
//         'message' => 'Error creating payment' . $e->getData()
//     ]);
//     exit();
// } catch (\Exception $e) {
//     // Log the error
//     echo json_encode([
//         'success' => false,
//         'message' => 'Unexpected error'
//     ]);
//     exit();
// } -->
