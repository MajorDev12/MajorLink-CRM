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
    $amount = inputValidation($request->PlanAmount);
    $currency = inputValidation($request->currency);
    $PlanID = inputValidation($request->PlanID);
    $startDate = inputValidation($request->startDate);


    if (empty($amount)) {
        $response = array(
            'error' => 'Amount Cannot Be Empty'
        );
        echo json_encode($response);
        exit();
    }

    //get stripe currencies
    //check if currency is  compatible
    //if compatible:  pass it like that
    //else: use converter api






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
                        'unit_amount' => $amount . '00',
                        'currency' => 'kes',
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
