<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require __DIR__ . "/vendor/autoload.php";

$number = "0718317726";
$message = "Your days are numbered";

$base_url = "1v3r9d.api.infobip.com";
$api_key = "c9a5fbb722cd3ccb6bfed037c0450e5c-b81eecc9-29d3-4c1e-8568-d4ccdf986f67";

$configuration = new Configuration(host: $base_url, apiKey: $api_key);

$api = new SmsApi(config: $configuration);

$destination = new SmsDestination(to: $number);

$message = new SmsTextualMessage(
    destinations: [$destination],
    text: $message,
    from: "254"
);

$request = new SmsAdvancedTextualRequest(messages: [$message]);

try {
    $api->sendSmsMessage($request);
    echo "Message sent successfully!";
} catch (\Exception $e) {
    echo "Error sending message: " . $e->getMessage();
}
