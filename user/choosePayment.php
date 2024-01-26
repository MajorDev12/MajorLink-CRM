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
        echo "its true";
        if ($PaymentMethod["PaymentOptionName"] === 'paypal') {
            header('location: paypal.php');
            exit();
        }
        if ($PaymentMethod["PaymentOptionName"] === 'mpesa') {
            header('location: mpesa.php');
            exit();
        }
        if ($PaymentMethod["PaymentOptionName"] === 'razorpay') {
            header('location: razorpay.php');
            exit();
        }
        if ($PaymentMethod["PaymentOptionName"] === 'stripe') {
            header('location: stripe.php');
            exit();
        }
        header('location: paypal.php');
        exit();
    }
}
