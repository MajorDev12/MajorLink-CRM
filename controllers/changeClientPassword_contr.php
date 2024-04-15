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
sleep(1);
require_once  '../database/pdo.php';
require_once "../modals/validate_mod.php";
require_once "../modals/changeClientPassword_mod.php";

$connect  = connectToDatabase($host, $dbname, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["clientID"]) || isset($_POST["clientID"])) {
    $password = inputValidation($_POST["confirmNewPassword"]);
    $clientID = inputValidation($_POST["clientID"]);

    // echo $password;
    // echo $clientID;

    $PasswordHash = password_hash($password, PASSWORD_BCRYPT);

    $changePassword = changePassword($clientID, $PasswordHash, $connect);

    // Send a response back to the client
    $response = [
        'success' => $changePassword ? true : false
    ];

    echo json_encode($response);
    exit();
}
