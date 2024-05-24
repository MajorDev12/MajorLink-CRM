// Send an SMS using Twilio's REST API and PHP
<?php
// Required if your environment does not handle autoloading
require __DIR__ . '/vendor/autoload.php';

// Your Account SID and Auth Token from console.twilio.com
$sid = "ACc03f885d55864fe118986ae6fc061a3d";
$token = "d807d84c6e0a0a6da6c73a321919d381";
$client = new Twilio\Rest\Client($sid, $token);

// Use the Client to make requests to the Twilio REST API
$client->messages->create(
    // The number you'd like to send the message to
    '+254759936978',
    [
        // A Twilio phone number you purchased at https://console.twilio.com
        'from' => '+12057794009',
        // The body of the text message you'd like to send
        'body' => "Congratulations!!!!"
    ]
);


// verify code
//LRGLDJWXE4Q2X5R6NMWTMZV3