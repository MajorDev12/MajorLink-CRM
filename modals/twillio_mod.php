<?php



require __DIR__ . "/vendor/autoload.php";

$number = $_POST["number"];
$message = $_POST["message"];


$account_id = "your account SID";
$auth_token = "your auth token";

$client = new Client($account_id, $auth_token);

$twilio_number = "+ your outgoing Twilio phone number";

$client->messages->create(
    $number,
    [
        "from" => $twilio_number,
        "body" => $message
    ]
);
