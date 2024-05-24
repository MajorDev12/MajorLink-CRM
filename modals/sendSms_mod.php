<?php
require_once "../modals/config.php";

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

use Twilio\Rest\Client;


function sendSMS($provider, $number, $textMessage)
{

    if ($provider === "Infobip") {
        require "../includes/infobip/vendor/autoload.php";



        $base_url = INFOBIP_BASE_URL;
        $api_key = INFOBIP_API_KEY;

        $configuration = new Configuration(host: $base_url, apiKey: $api_key);

        $api = new SmsApi(config: $configuration);

        $destination = new SmsDestination(to: $number);

        $message = new SmsTextualMessage(
            destinations: [$destination],
            text: $textMessage,
            from: "MajorLink"
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        $response = $api->sendSmsMessage($request);

        return $response;
    } else if ($provider === "Nexmo") {
        require "../includes/nexmo/vendor/autoload.php";


        $basic  = new \Vonage\Client\Credentials\Basic(VONAGE_API_KEY, VONAGE_API_SECRET);
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($number, 'MajorLink', $textMessage)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            return true;
        } else {
            // echo "The message failed with status: " . $message->getStatus() . "\n";
            return false;
        }
    } else {
        return "No such Api";
    }
}
