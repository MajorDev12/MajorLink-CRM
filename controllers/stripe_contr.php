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

    require_once  '../database/pdo.php';
    require_once "../modals/validate_mod.php";
    require_once "../modals/config.php";

    $connect  = connectToDatabase($host, $dbname, $username, $password);
    $input = file_get_contents('php://input');
    $request = json_decode($input);


    //we'll use this for advance payment 
    $paymentDate = inputValidation($request->paymentDate);
    $PlanName = inputValidation($request->PlanName);
    $PlanAmount = inputValidation($request->PlanAmount);
    $currencySymbol = inputValidation($request->currency);
    $currencyCode = inputValidation($request->currencyCode);
    $PlanID = inputValidation($request->PlanID);
    $startDate = inputValidation($request->startDate);
    $selectedMonths = inputValidation($request->selectedMonths);

    // Set the value in the session
    $_SESSION["selectedMonths"] = $selectedMonths;

    if (empty($PlanAmount)) {
        $response = array(
            'error' => 'Amount Cannot Be Empty'
        );
        echo json_encode($response);
        exit();
    }

    //get stripe currencies
    $stripeCurrencies = json_decode(file_get_contents("../assets/stripeCurrencies.json"), true);

    // Convert to lowercase
    $currency = trim(strtolower($currencyCode));


    // Check if it's not in the list of Stripe currencies
    if (!in_array($currency, $stripeCurrencies, false)) {
        // API endpoint for ExchangeRatesAPI
        $apiEndpoint = 'https://open.er-api.com/v6/latest';

        // API request to get the latest exchange rates
        $apiUrl = "{$apiEndpoint}?base={$currency}&symbols=USD";
        $apiResponse = file_get_contents($apiUrl);


        if ($apiResponse !== false) {
            $exchangeRates = json_decode($apiResponse, true);

            // Check if the response is valid
            if (isset($exchangeRates['rates']['USD'])) {
                // Convert the amount to USD
                $amountInUSD = $PlanAmount * $exchangeRates['rates']['USD'];

                // Set the currency to USD
                $currency = 'usd';
            } else {
                // Handle invalid response
                // Log an error or set a default conversion
                $amountInUSD = $PlanAmount;
            }
        } else {
            // Handle API request error
            // Log an error or set a default conversion
            $amountInUSD = $PlanAmount;
        }
    } else {
        // If the currency is in the list, keep the amount and currency as is
        $amountInUSD = $PlanAmount;
    }


    // Ensure $amountInUSD is a decimal with two decimal places
    $amount = round($amountInUSD, 2);

    // Convert $amountInUSD to cents
    $stripeAmount = (int) round($amountInUSD * 100);


    // Include the Stripe PHP library 
    require_once '../apis/stripe-php/init.php';

    // Set API key 
    $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);




    if (!empty($request->createCheckoutSession)) {
        // Convert product price to cent 
        // $stripeAmount = round($request->PlanAmount * 100, 2);

        // Create new Checkout Session for the order 
        try {
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => [[
                    'price_data' => [
                        'product_data' => [
                            'name' => $PlanName,
                        ],
                        'unit_amount' => $stripeAmount,
                        'currency' => strtolower($currency),
                    ],
                    'quantity' => 1
                ]],
                'mode' => 'payment',
                'success_url' => STRIPE_SUCCESS_URL . '?session_id={CHECKOUT_SESSION_ID}&' . $request->PlanID,
                'cancel_url' => STRIPE_CANCEL_URL,
            ]);
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $checkout_session) {
            $response = array(
                'status' => 1,
                'message' => 'Checkout Session created successfully!',
                'sessionId' => $checkout_session->id
            );
        } else {
            $response = array(
                'status' => 0,
                'error' => array(
                    'message' => 'Checkout Session creation failed! ' . $api_error
                )
            );
        }
    }

    // Return response 
    echo json_encode($response);
}
