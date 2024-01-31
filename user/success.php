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

$connect  = connectToDatabase($host, $dbname, $username, $password);
$payment_id = $statusMsg = '';
$status = 'error';



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
        $transactionID = $transData['TransactionID'];
        $paidAmount = $transData['PaidAmount'];
        $paidCurrency = $transData['PaidCurrency'];
        $payment_status = $transData['Payment_status'];

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
            var_dump($paymentIntent);
            echo "<br />";
            echo "<br />";
            var_dump($customer_details);
            echo "<br />";
            echo "<br />";
            var_dump($checkout_session);
            exit();
            if (empty($api_error) && $paymentIntent) {
                // Check whether the payment was successful 
                if (!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
                    // Transaction details  
                    $transactionID = $paymentIntent->id;
                    $paidAmount = $paymentIntent->amount_received;
                    $paidAmount = ($paidAmount / 100);
                    $paidCurrency = $paymentIntent->currency;
                    $payment_status = $paymentIntent->status;

                    // Customer info 
                    $customer_name = $customer_email = '';
                    if (!empty($customer_details)) {
                        $customer_name = !empty($customer_details->name) ? $customer_details->name : '';
                        $customer_email = !empty($customer_details->email) ? $customer_details->email : '';
                    }
                    // Check if any transaction data is exists already with the same TXN ID 
                    $result = getStripeTransactionId($connect, $transactionID);

                    if (!empty($result)) {
                        $payment_id = $result['TransactionID'];
                    } else {
                        // Insert transaction data into the database 
                        setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $payment_id, $transactionID, $payment_status, $session_id);
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
?>

<?php if (!empty($transactionID)) { ?>
    <h1 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>

    <h4>Payment Information</h4>
    <p><b>Reference Number:</b> <?php echo $payment_id; ?></p>
    <p><b>Transaction ID:</b> <?php echo $transactionID; ?></p>
    <p><b>Paid Amount:</b> <?php echo $paidAmount . ' ' . $paidCurrency; ?></p>
    <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>

    <h4>Customer Information</h4>
    <p><b>Name:</b> <?php echo $customer_name; ?></p>
    <p><b>Email:</b> <?php echo $customer_email; ?></p>

    <h4>Product Information</h4>
    <p><b>Name:</b> <?php echo $productName; ?></p>
    <p><b>Price:</b> <?php echo $productPrice . ' ' . $currency; ?></p>
<?php } else { ?>
    <h1 class="error">Your Payment been failed!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
<?php } ?>