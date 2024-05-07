<?php

$baseUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

//base url
define('BASE_URL', $baseUrl);


// DATABASE DETAILS
define('DB_HOST', 'localhost');
define('DATABASE_NAME', 'majorlink');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '123456');

//   STRIPE DETAILS
define('STRIPE_API_KEY', 'sk_test_51OahpHCOzF40fi2hTL2NuZVCPFdfM6vUSjdLvf9oozxk77v1ejfeH7EMUnbNCWb32Fy3kMhfgxUy8dSPODZB7pOz00Z2Rhq2bw');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51OahpHCOzF40fi2hi1gx9KobB3Dp4gT2UZ54X7GxDnaeJXn6AltexdtJMxfvFgi8bpY435mSCrxVcS7aCjkwBQnZ00RFNMMqbA');
define('STRIPE_SUCCESS_URL', 'http://localhost/majorlink/user/success.php'); //Payment success URL 
define('STRIPE_CANCEL_URL', 'http://localhost/majorlink/user/cancel.php'); //Payment cancel URL 


//   PAYPAL DETAILS
define('CLIENT_ID', 'Ae4orbdIICDrUSBdOqXB0HbAwz41DvXZwYt9UXlCOska-hYHUEw2YkXEblL0N4VNgBmtAt9G8H7Gq1Mt');




// TWILLIO DETAILS
define('ACCOUNT_ID', 'ACc03f885d55864fe118986ae6fc061a3d');
define('AUTH_TOKEN', 'd807d84c6e0a0a6da6c73a321919d381');


// INFOBIP DETAILS
define('INFOBIP_BASE_URL', 'https://1v3r9d.api.infobip.com');
define('INFOBIP_API_KEY', 'c9a5fbb722cd3ccb6bfed037c0450e5c-b81eecc9-29d3-4c1e-8568-d4ccdf986f67');


// NEXMO DETAILS
define('VONAGE_API_KEY', '1a43d0b8');
define('VONAGE_API_SECRET', 'e4lqjZvZ2aYk31PB');


// PHPMAILER DETAILS
define('USERNAME', 'majordev12@gmail.com');
define('PASSWORD', 'jhdi bxqh tlfh bgwp');
define('PORT', 587);
define('MAILERHOST', 'smtp.gmail.com');



// EXCHANGE RATES DETAILS
define('ENDPOINT', 'https://open.er-api.com/v6/latest');
