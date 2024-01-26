
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
    //get data
    $amount = $_POST['amount'];
    $payerID = $_POST['payerID'];
    $paymentID = $_POST['paymentID'];

    //if amount is > amountPaid

    // $response = [
    //     'success' => $paymentID
    // ];

    echo json_encode($response);
    exit();
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
