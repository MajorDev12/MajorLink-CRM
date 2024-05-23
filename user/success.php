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
// Include configuration file  
require_once  '../modals/config.php';

// Include database connection file  
require_once  '../database/pdo.php';

require_once '../modals/getStripeTransaction_mod.php';
require_once  '../modals/setup_mod.php';
require_once  '../modals/updateStatus_mod.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/addPayment_mod.php';
require_once  '../modals/notification_mod.php';
require_once  '../modals/addInvoice_mod.php';
require_once  '../modals/addPlan_mod.php';
require_once  '../modals/sendSms_mod.php';
require_once  '../modals/getTime_mod.php';
require_once  '../views/header.php';



$connect  = connectToDatabase($host, $dbname, $username, $password);
$settings = get_Settings($connect);
$timezone = $settings[0]["TimeZone"];
$phoneCode = $settings[0]["PhoneCode"];
date_default_timezone_set($timezone);
$createdDate = getTime($timezone);
$tax = 0;
$taxSymbol = $settings[0]["CurrencySymbol"];

$payment_id = $statusMsg = '';
$status = 'error';
//get currencyCode
$settings = get_Settings($connect);
$initialCurrency = $settings[0]["CurrencyCode"];
$initialSymbol = $settings[0]["CurrencySymbol"];

//get client details
$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
$invoiceNumber = generateInvoiceNumber();
$invoiceProducts = [];
$PaidPlanID = $_GET["p"];
$PlanID = $clientData['PlanID'];
$changing = ($_GET["c"] === '1') ? true : false;
$changingNow = ($_GET["cn"] === '1') ? true : false;
$paidMonths = intval($_SESSION["selectedMonths"]);

if (isset($_POST['planID']) && !empty($_POST['planID'])) {
    $paidplanid = $_POST['planID'];
} else {
    $paidplanid = $_GET["p"];
}
$planData = getPlanDataByID($connect, $paidplanid);


$subtotal = $paidMonths * $clientData['PlanPrice'];
// get plan data
if (isset($_GET["p"])) {
    $invoiceProducts[] = [
        'product' => $planData['Name'],
        'volume' => $planData['Volume'],
        'qty' => $paidMonths,
        'price' => $planData['Price']
    ];
}



$initialExpireDate = strtotime($clientData['ExpireDate']);
$daysRemaining = calculateLeftDays($clientData["ExpireDate"]);

$selectedMonths = intval($_SESSION["selectedMonths"]);


if (!$changing) {
    $expireDate = calculateExpireDate($initialExpireDate, $selectedMonths, $timezone);
} elseif ($changing && $changingNow) {
    $initialcreatedDate = strtotime($createdDate);
    $expireDate = calculateExpireDate($initialcreatedDate, $selectedMonths, $timezone);
} elseif ($changing && !$changingNow) {
    // Calculate the new expire date based on the modified expire date
    $expireDate = calculateExpireDate($initialExpireDate, $selectedMonths, $timezone);
}





$initialDate = $clientData["ExpireDate"];

if ($changingNow) {
    $startDate = $createdDate;
} else {
    $startDate = date('Y-m-d', strtotime($initialDate . ' +1 day'));
    $expireDate = date('Y-m-d', strtotime($expireDate . ' +1 day'));
}



// Function to calculate left days
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







// Check whether stripe checkout session is not empty 
if (!empty($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    $ClientID = $_SESSION['clientID'];

    // Fetch transaction data from the database if already exists 
    $result = getStripeSessionId($connect, $session_id);

    if (!empty($result)) {
        // Transaction details
        $transData = $result[0]; // Assuming you want the first row if there are multiple rows
        $payment_id = $transData['PaymentID'];
        $paidAmount = $transData['PaidAmount'];
        $paidCurrency = $transData['PaidCurrency'];
        $payment_status = $transData['Payment_status'];
        $createdDate = $transData['CreatedDate'];

        $customer_name = $transData['Customer_name'];
        $customer_email = $transData['Customer_email'];

        $status = 'success';
        $statusMsg = 'Your Payment has been Successful!';
    } else {
        // Include the Stripe PHP library 
        require_once '../includes/stripe-php/init.php';

        // Set API key 
        $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

        // Fetch the Checkout Session to display the JSON result on the success page 
        try {
            $checkout_session = $stripe->checkout->sessions->retrieve($session_id);
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $checkout_session) {
            // Get customer details 
            $customer_details = $checkout_session->customer_details;

            // Retrieve the details of a PaymentIntent 
            try {
                $paymentIntent = $stripe->paymentIntents->retrieve($checkout_session->payment_intent);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $paymentIntent) {
                // Check whether the payment was successful 
                if (!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
                    // Transaction details  
                    $payment_id = $paymentIntent->id;
                    $paidAmount = $paymentIntent->amount_received;
                    $paidAmount = ($paidAmount / 100);
                    $paidCurrency = $paymentIntent->currency;
                    $payment_status = 'Paid';

                    if ($paidCurrency != $initialCurrency) {
                        // API endpoint for ExchangeRatesAPI
                        $apiEndpoint = ENDPOINT;

                        // API request to get the latest exchange rates
                        $apiUrl = "{$apiEndpoint}?base={$paidCurrency}&symbols={$initialCurrency}";
                        $apiResponse = file_get_contents($apiUrl);

                        // Decode the API response
                        $exchangeRates = json_decode($apiResponse, true);

                        // Check if the exchange rates were successfully retrieved
                        if ($exchangeRates && isset($exchangeRates['rates'][$initialCurrency])) {
                            // Convert paidAmount to the initialCurrency and round off
                            $exchangeRate = $exchangeRates['rates'][$initialCurrency];
                            $paidAmount = round($paidAmount * $exchangeRate);
                        } else {
                            // Handle the case where exchange rates couldn't be retrieved
                            // You might want to log an error or handle it based on your application's needs
                            // For example, you could set an error flag or display a message to the user
                            // $errorFlag = true;
                        }
                    }



                    $paymentStatus = calculatePaymentStatus($connect, $paidplanid, $paidAmount, $paidMonths);


                    // Customer info 
                    $customer_name = $customer_email = '';
                    if (!empty($customer_details)) {
                        $customer_name = !empty($customer_details->name) ? $customer_details->name : '';
                        $customer_email = !empty($customer_details->email) ? $customer_details->email : '';
                    }
                    // Check if any transaction data exists with the same TXN ID 
                    $result = getStripeTransactionId($connect, $payment_id);

                    if (empty($result)) {
                        $activeStatus = 1;
                        $expireDate = new DateTime($expireDate);

                        if ($daysRemaining > 0) {
                            $last_paymentDate = $clientData['LastPayment'];
                            // $expireDate->modify("+" . $daysRemaining . " days");
                        } else {
                            // Set last_paymentDate to paymentDate
                            $last_paymentDate = $createdDate;
                        }


                        $planAmount = $planData['Price'];
                        $planVolume = $planData['Volume'];
                        $to = $clientData['PrimaryNumber'];
                        $Clientnumber = $phoneCode . $to;
                        $paymentMethodID = 3;
                        $InstallationFees = 0;


                        $expireDate = $expireDate->format('Y-m-d H:i:s');

                        if ($PlanID !== $PaidPlanID) {
                            $changing = true;
                        } else {
                            $changing = false;
                        }



                        // Process payment and update client data
                        if (!$changing) {
                            // process payment
                            insertPaymentData($ClientID, $PlanID, $invoiceNumber, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                            updatePlan($ClientID, $PlanID, $expireDate, $last_paymentDate, $connect);
                            changeStatus($ClientID, $activeStatus, $connect);
                            setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);



                            // Call createAndSaveInvoice function with the generated invoice number
                            addInvoice($connect, $ClientID, $invoiceNumber, $paidAmount, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                            saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                            sendSuccessMessage($ClientID, $createdDate, $connect);
                            sendTextMessage($Clientnumber, $planVolume, $expireDate);
                        } else {

                            if ($changingNow) {

                                updateClientDataForImmediateChange($ClientID, $PaidPlanID, $createdDate, $expireDate, $connect);
                                insertPaymentData($ClientID, $PaidPlanID, $invoiceNumber, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                                changeStatus($ClientID, $activeStatus, $connect);
                                setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);


                                // Call createAndSaveInvoice function with the generated invoice number
                                addInvoice($connect, $ClientID, $invoiceNumber, $paidAmount, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                                saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                                sendSuccessMessage($ClientID, $createdDate, $connect);
                                sendSuccessPlanChangeMessage($ClientID, $createdDate, $connect);
                                sendTextMessage($Clientnumber, $planVolume, $expireDate);
                            } else {
                                $scheduled = schedulePlanChange($ClientID, $PaidPlanID, $startDate, $connect);

                                $paid = insertPaymentData($ClientID, $PaidPlanID, $invoiceNumber, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                                $updated = updatePlan($ClientID, $PlanID, $expireDate, $last_paymentDate, $connect);
                                $status = changeStatus($ClientID, $activeStatus, $connect);
                                $stripe = setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);



                                // Call createAndSaveInvoice function with the generated invoice number
                                $invoiceadded = addInvoice($connect, $ClientID, $invoiceNumber, $paidAmount, $createdDate, $startDate, $expireDate, $paymentStatus, $taxSymbol, $tax);
                                $products = saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                                $notified = sendSuccessMessage($ClientID, $createdDate, $connect);
                                $notifiedLater = sendSuccessPlanChangeLaterMessage($ClientID, $createdDate, $startDate, $connect);
                                $textSent = sendTextMessage($Clientnumber, $planVolume, $expireDate);
                            }
                        }
                    }

                    $status = 'success';
                    $statusMsg = 'Your Payment has been Successful!';
                } else {
                    $statusMsg = "Transaction has been failed!";
                }
            } else {
                $statusMsg = "Unable to fetch the transaction details! $api_error";
            }
        } else {
            $statusMsg = "Invalid Transaction! $api_error";
        }
    }
}






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




// Functions to perform specific tasks
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



?>

<?php

// unset($_SESSION['selectedMonths']);
// unset($_SESSION['startDate']);
// unset($_SESSION['paymentDate']);
// unset($_SESSION['currencySymbol']);

var_dump($_SESSION['currencySymbol']);


?>
<?php header('Location: paymentSuccess.php'); ?>