<?php

require_once "vendor/autoload.php";

use Omnipay\Omnipay;

define('CLIENT_ID', 'PAYPAL_CLIENT_ID_HERE');
define('CLIENT_SECRET', 'PAYPAL_CLIENT_SECRET_HERE');


define('PAYPAL_RETURN_URL', 'http://localhost/paypal/success.php');
define('PAYPAL_CANCEL_URL', 'http://localhost/paypal/cancel.php');
define('PAYPAL_CURRENCY', 'USD');

//connect with the db

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); //set false when go live
