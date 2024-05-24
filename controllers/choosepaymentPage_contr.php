<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['clientID']) || !isset($_SESSION['FirstName'])) {
    // Redirect to the login page
    header("location: ../views/login.php");
    exit();
}
?>

<?php
require_once  '../database/pdo.php';
require_once  '../modals/viewSingleUser_mod.php';
require_once  '../modals/getPaymentMethods_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
$clientID = $_SESSION['clientID'];

$clientData = getClientDataById($connect, $clientID);

$preferedmethodid = $clientData["PreferedPaymentMethod"];

$PaymentMethods = getPaymentMethods($connect);

foreach ($PaymentMethods as $PaymentMethod) {
    if ($preferedmethodid === $PaymentMethod["PaymentOptionID"]) {

        switch ($PaymentMethod["PaymentOptionName"]) {
            case 'paypal':
                header('location: ../user/paypal.php');
                exit();
            case 'mpesa':
                header('location: ../user/mpesa.php');
                exit();
            case 'razorpay':
                header('location: ../user/razorpay.php');
                exit();
            case 'stripe':
                header('location: ../user/stripe.php');
                exit();
            default:
                header('location: ../user/paypal.php');
                exit();
        }
    }
}
