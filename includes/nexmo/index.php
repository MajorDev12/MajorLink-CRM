<?php

require_once 'vendor/autoload.php';

use Nexmo\Client;
use Nexmo\Client\Credentials\Basic;

// Your Nexmo API key and secret
$key = 'your-nexmo-key';
$secret = 'your-nexmo-secret';

// Create Nexmo client
$basic  = new Basic($key, $secret);
$client = new Client($basic);

$number  = '718317726'; // The recipient's phone number
$message = 'Hello, this is a test message from Nexmo!';

$response = $client->message()->send([
    'to'   => $number,
    'from' => 'Nexmo', // Sender ID (optional, not verified)
    'text' => $message,
]);

if ($response->getStatus() == 0) {
    echo 'Message sent successfully!';
} else {
    echo 'Error sending message: ' . $response->getErrorText();
}
