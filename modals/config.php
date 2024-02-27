<?php

$baseUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

//base url
define('BASE_URL', $baseUrl);

//   STRIPE DETAILS
define('STRIPE_API_KEY', 'sk_test_51OahpHCOzF40fi2hTL2NuZVCPFdfM6vUSjdLvf9oozxk77v1ejfeH7EMUnbNCWb32Fy3kMhfgxUy8dSPODZB7pOz00Z2Rhq2bw');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51OahpHCOzF40fi2hi1gx9KobB3Dp4gT2UZ54X7GxDnaeJXn6AltexdtJMxfvFgi8bpY435mSCrxVcS7aCjkwBQnZ00RFNMMqbA');
define('STRIPE_SUCCESS_URL', 'http://localhost/majorlink/user/success.php'); //Payment success URL 
define('STRIPE_CANCEL_URL', 'http://localhost/majorlink/user/cancel.php'); //Payment cancel URL 



// COMPANY DETAILS
define('COMPANY NAME', 'MajorLink');
define('COMPANY_WEBSITE', 'www.majorlink.com');
define('COMPANY EMAIL', 'majorlink@gmail.com');
define('COMPANY NUMBER', '(254) 718 317 726');
