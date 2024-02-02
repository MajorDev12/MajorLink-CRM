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





                    //handle the advance payment








                    $settings = get_Settings($connect);
                    $timezone = $settings[0]["TimeZone"];
                    date_default_timezone_set($timezone);
                    $createdDate = date('Y-m-d H:i:s');

                    // Customer info 
                    $customer_name = $customer_email = '';
                    if (!empty($customer_details)) {
                        $customer_name = !empty($customer_details->name) ? $customer_details->name : '';
                        $customer_email = !empty($customer_details->email) ? $customer_details->email : '';
                    }
                    // Check if any transaction data exists with the same TXN ID 
                    $result = getStripeTransactionId($connect, $payment_id);

                    if (empty($result)) {
                        // Insert transaction data into the database 
                        setStripeTransaction($connect, $ClientID, $customer_name, $customer_email, $paidAmount, $paidCurrency, $createdDate, $payment_id, $payment_status, $session_id);
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

<?php if (!empty($payment_id)) { ?>
    <h1 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>

    <h2>Payment Information</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Created Date</th>
            <td><?php echo $createdDate; ?></td>
        </tr>
        <tr>
            <th>Reference Number</th>
            <td><?php echo $payment_id; ?></td>
        </tr>
        <tr>
            <th>Paid Amount</th>
            <td><?php echo $paidAmount . ' ' . $paidCurrency; ?></td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td><?php echo $payment_status; ?></td>
        </tr>
    </table>

    <h2>Customer Information</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <td><?php echo $customer_name; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $customer_email; ?></td>
        </tr>
    </table>

    <h2>Product Information</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <td><?php echo $productName; ?></td>
        </tr>
        <tr>
            <th>Price</th>
            <td><?php echo $productPrice . ' ' . $currency; ?></td>
        </tr>
    </table>

    <a href="index.php">Back</a>

<?php } else { ?>
    <h1 class="error">Your Payment has failed!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
<?php } ?>