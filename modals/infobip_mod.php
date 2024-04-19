<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use Twilio\Rest\Client;




function sendSMS($provider, $number, $message)
{
    $number = "254" . $number;
    if ($provider === "Infobip") {
        require "../includes/infobip/vendor/autoload.php";



        $base_url = "https://1v3r9d.api.infobip.com";
        $api_key = "c9a5fbb722cd3ccb6bfed037c0450e5c-b81eecc9-29d3-4c1e-8568-d4ccdf986f67";

        $configuration = new Configuration(host: $base_url, apiKey: $api_key);

        $api = new SmsApi(config: $configuration);

        $destination = new SmsDestination(to: $number);

        $message = new SmsTextualMessage(
            destinations: [$destination],
            text: $message,
            from: "MajorLink"
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        $response = $api->sendSmsMessage($request);

        return $response;
    } else if ($provider === "Twillio") {
        require "../includes/twillio/vendor/autoload.php";

        $account_id = "ACc03f885d55864fe118986ae6fc061a3d";
        $auth_token = "d807d84c6e0a0a6da6c73a321919d381";

        $client = new Client($account_id, $auth_token);

        $client->messages->create(
            $number,
            [
                "from" => $number,
                "body" => $message
            ]
        );
    } else {
        return "No such Api";
    }
}
