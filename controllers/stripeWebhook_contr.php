<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require_once "../apis/stripe/vendor/autoload.php";

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient('sk_test_51OahpHCOzF40fi2hTL2NuZVCPFdfM6vUSjdLvf9oozxk77v1ejfeH7EMUnbNCWb32Fy3kMhfgxUy8dSPODZB7pOz00Z2Rhq2bw');

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_a9b279fdcb8382e60e90af9a024bb8f7cb08d699a67cb4ad57d7a0d73b1967a1';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        // ... handle other event types
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);
