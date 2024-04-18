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
require_once  '../modals/infobip_mod.php';
require_once  '../views/header.php';



$connect  = connectToDatabase($host, $dbname, $username, $password);
$payment_id = $statusMsg = '';
$status = 'error';
//get currencyCode
$settings = get_Settings($connect);
$initialCurrency = $settings[0]["CurrencyCode"];
$initialSymbol = $settings[0]["CurrencySymbol"];

//get client details
$clientID = $_SESSION['clientID'];
$clientData = getClientDataById($connect, $clientID);
$invoiceProducts = [];
$PaidPlanID = $_GET["p"];
$changing = $_GET["c"];
$changingNow = $_GET["cn"];
$paidMonths = intval($_SESSION["selectedMonths"]);
$planData = getPlanDataByID($connect, $PaidPlanID);
$invoiceNumber = "INV0000TEST";
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



$initialExpireDate = strtotime($clientData['ExpireDate']); // Convert string to Unix timestamp
$daysRemaining = calculateLeftDays($clientData["ExpireDate"]);

$selectedMonths = intval($_SESSION["selectedMonths"]);


if (!$changing) {
    $expireDate = calculateExpireDate($initialExpireDate, $selectedMonths);
} elseif ($changing && $changingNow) {
    $createdDate = date('Y-m-d H:i:s');
    $initialcreatedDate = strtotime($createdDate);
    $expireDate = calculateExpireDate($initialcreatedDate, $selectedMonths);
}

$firstDate = (string) $clientData['ExpireDate'];
$firstDate = new DateTime($clientData['ExpireDate']);
$firstDate = $firstDate->format('Y-m-d H:i:s');

// $firsDate = $clientData['ExpireDate'];
// $firsDate = new DateTime($firsDate);

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

// Function to calculate expire date
function calculateExpireDate($initialExpireDate, $selectedMonths)
{
    // Add the selected months to the timestamp
    $newExpireTimestamp = strtotime("+" . $selectedMonths . " months", $initialExpireDate);
    // Convert the new timestamp back to a date
    return date("Y-m-d", $newExpireTimestamp);
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
        require_once '../apis/stripe-php/init.php';

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
                        $apiEndpoint = 'https://open.er-api.com/v6/latest';

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



                    $settings = get_Settings($connect);
                    $timezone = $settings[0]["TimeZone"];
                    date_default_timezone_set($timezone);
                    $createdDate = date('Y-m-d H:i:s');

                    $PlanID = $clientData['PlanID'];

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
                        $paymentStatus = "Paid";
                        $expireDate = new DateTime($expireDate);

                        if ($daysRemaining > 0) {
                            $last_paymentDate = $clientData['LastPayment'];
                            // $expireDate->modify("+" . $daysRemaining . " days");
                        } else {
                            // Set last_paymentDate to paymentDate
                            $last_paymentDate = $createdDate;
                        }


                        $planAmount = $clientData['PlanPrice'];
                        $planVolume = $clientData['Plan'];
                        $Clientnumber = $clientData['PrimaryNumber'];
                        $paymentMethodID = 3;
                        $InstallationFees = 0;


                        $expireDate = $expireDate->format('Y-m-d H:i:s');

                        if ($PlanID !== $PaidPlanID) {
                            $changing = true;
                        } else {
                            $changing = false;
                        }

                        $paymentStatus = calculatePaymentStatus($connect, $ClientID, $paidAmount);

                        // Process payment and update client data
                        if (!$changing) {
                            // process payment
                            insertPaymentData($ClientID, $PlanID, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                            updatePlan($ClientID, $PlanID, $expireDate, $last_paymentDate, $connect);
                            changeStatus($ClientID, $activeStatus, $connect);
                            setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);

                            $invoiceNumber = generateInvoiceNumber();

                            // Call createAndSaveInvoice function with the generated invoice number
                            createAndSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $connect);
                            saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                            sendSuccessMessage($ClientID, $createdDate, $connect);
                            sendTextMessage($Clientnumber, $planVolume, $expireDate);
                        } else {

                            if ($changingNow) {
                                $initialcreatedDate = strtotime($createdDate);
                                $ExpireDate = calculateExpireDate($initialcreatedDate, $selectedMonths);


                                updateClientDataForImmediateChange($ClientID, $PaidPlanID, $createdDate, $ExpireDate, $connect);

                                $paymentStatus = calculatePaymentStatus($connect, $ClientID, $paidAmount);
                                insertPaymentData($ClientID, $PaidPlanID, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                                changeStatus($ClientID, $activeStatus, $connect);
                                setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);

                                $invoiceNumber = generateInvoiceNumber();
                                // Call createAndSaveInvoice function with the generated invoice number
                                createAndSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $ExpireDate, $connect);
                                saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                                sendSuccessMessage($ClientID, $createdDate, $connect);
                                sendSuccessPlanChangeMessage($ClientID, $createdDate, $connect);
                                sendTextMessage($Clientnumber, $planVolume, $expireDate);
                            } else {
                                schedulePlanChange($ClientID, $PaidPlanID, $firstDate, $connect);

                                $paymentStatus = calculatePaymentStatus($connect, $ClientID, $paidAmount);
                                insertPaymentData($ClientID, $PaidPlanID, $paidAmount, $paymentStatus, $createdDate, $paymentMethodID, $InstallationFees, $connect);
                                updatePlan($ClientID, $PlanID, $expireDate, $last_paymentDate, $connect);
                                changeStatus($ClientID, $activeStatus, $connect);
                                setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);


                                $invoiceNumber = generateInvoiceNumber();
                                // Call createAndSaveInvoice function with the generated invoice number
                                createAndSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $connect);
                                saveInvoiceProducts($connect, $invoiceNumber, $subtotal, $invoiceProducts);
                                sendSuccessMessage($ClientID, $createdDate, $connect);
                                sendSuccessPlanChangeLaterMessage($ClientID, $createdDate, $firstDate, $connect);
                                sendTextMessage($Clientnumber, $planVolume, $expireDate);
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










// Functions to perform specific tasks
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
    $message = 'You have successfully subscribed to ' . $planVolume . ' Your subscription will be renewed on ' . $expireDate . ' Thank you for choosing MajorLink';
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


function createAndSaveInvoice($ClientID, $invoiceNumber, $paidAmount, $expireDate, $connect)
{
    $totalAmount = $paidAmount;
    $startDate = $_SESSION['startDate'];
    $paymentDate = $_SESSION["paymentDate"];
    $taxSymbol = $_SESSION["currencySymbol"];
    $taxAmount = 0;
    $dueDate = $expireDate;
    $status = "Paid";
    addInvoice($connect, $ClientID, $invoiceNumber, $totalAmount, $paymentDate, $startDate, $dueDate, $status, $taxSymbol, $taxAmount);
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










<style>
    .invoice {
        width: 100%;
        height: 100%;
        margin-top: 5%;
        background-color: var(--light);
        padding: 20px;
    }

    .invoice .row {
        display: flex;
        justify-content: center;
        text-align: start;
    }

    .invoice .row div {
        margin-left: 10%;
    }

    .invoice .table thead th,
    .invoice .table tbody tr,
    .invoice .table tbody td {
        background-color: var(--light);
    }

    .invoice .table tbody .Subtotal,
    .invoice .table tbody .Tax,
    .invoice .table tbody .Total {
        font-weight: 700;
        color: var(--dark-grey);
    }

    .invoice .table tbody .Total {
        color: var(--dark);
    }

    .invoice .table tbody .totalPrice {
        font-weight: 700;
        font-size: 1.3em;
    }

    .space {
        border: none;
    }

    .status {
        background-color: var(--green);
        color: var(--light-green);
        padding: 2px 20px;
        border-radius: 10px;
    }
</style>




<?php if (isset($clientData)) : ?>

    <?php if (!empty($payment_id)) { ?>
        <div class="container invoice mt-5">
            <!-- <h1 class="display-4 text-success <?php echo $status; ?>"><?php echo $statusMsg; ?></h1> -->

            <div class="row">
                <div class="col-md-4">
                    <h3><?php echo $clientData['FirstName'] . ' ' . $clientData['LastName']; ?></h3>
                    <p>Payment Date: <?php echo $createdDate; ?></p>
                    <p>Expire Date: <?php echo $expireDate; ?></p>
                    <?php $paymentStatus = calculatePaymentStatus($connect, $ClientID, $paidAmount); ?>
                    <p>Status: <span class="status"> <?php echo $paymentStatus; ?></span></p>
                </div>
                <div class="col-md-4">
                    <h3>MajorLink.Co</h3>
                    <p>Nakuru City</p>
                    <p>Nakuru, Pipeline</p>
                    <p>987654321</p>
                </div>
            </div>


            <table class="table mt-5">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Volume</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Price</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <?php
                $planData = getPlanDataByID($connect, $PaidPlanID);
                ?>
                <tbody class="table-group-divider">
                    <tr>
                        <td><?= $planData['Name']; ?></td>
                        <td><?= $planData['Volume']; ?></td>
                        <td><?php echo $_SESSION["selectedMonths"]; ?></td>
                        <td><?php echo $planData['Price']; ?></td>
                        <td><?php
                            // Calculate the amount
                            $planAmount = $planData['Price'] * $selectedMonths;
                            echo $planAmount . ' ' . $initialSymbol;
                            ?></td>
                    </tr>

                    <tr>
                        <td colspan="3" class="border-none space"></td>
                        <td colspan="" class="text-start Subtotal">Subtotal</td>
                        <td class="">
                            <?= $planAmount; ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="border-none space"></td>
                        <td colspan="" class="text-start Tax">Tax(0%)</td>
                        <td class="">
                            <?php $tax = 0; ?>
                            <?= $tax; ?>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="border-none space"></td>
                        <td colspan="" class="text-start Total">Total</td>
                        <td class="text-primary totalPrice">
                            <?= number_format($planAmount, 0) . ' ' . $initialCurrency; ?>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-warning Print">Print</a>
            <a href="../controllers/generatepdf_contr.php" class="btn btn-success Download" target="_blank">Download pdf</a>
            <a href="index.php" class="btn btn-secondary">View Transactions</a>
            <a href="index.php" class="btn btn-primary">Done</a>
        </div>

    <?php } else { ?>
        <div class="container mt-5">
            <h1 class="display-4 error">Your Payment has failed!</h1>
            <p class="lead error"><?php echo $statusMsg; ?></p>
        </div>
    <?php } ?>

<?php else : ?>
    <div class="alert alert-warning" role="alert">
        No data found
        <?php header("Location: viewClient.php"); ?>
    </div>
<?php endif; ?>