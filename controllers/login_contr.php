<?php

// Include your database connection and any necessary functions
require_once '../database/pdo.php';
require_once '../modals/validate_mod.php';
require_once '../modals/login_mod.php';
require_once '../modals/setup_mod.php';
require_once '../modals/getTime_mod.php';
require_once '../modals/notification_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
// Get the posted data
$postData = json_decode(file_get_contents("php://input"));

if ($postData) {
    sleep(2);
    $email = inputValidation($postData->email);
    $password = inputValidation($postData->password);


    if (!empty($email) && !empty($password)) {

        $settings = get_Settings($connect);
        $CurrentTimezone = $settings[0]["TimeZone"];

        $hashedPassword = $password;
        // echo $hashedPassword;

        $isAdmin = isAdmin($email, $hashedPassword, $connect);

        // var_dump($isAdmin['PasswordHash']);
        // Check in the client table if not found in the admin table
        if (!$isAdmin) {
            $isClient = isClient($email, $hashedPassword, $connect);

            if ($isClient) {
                // Client login successful
                session_start();
                $_SESSION['clientID'] = $isClient['ClientID'];
                $_SESSION['FirstName'] = $isClient['FirstName'];
                $SenderName = 'system';
                $MessageType = 'ActivityLog';
                $MessageContent = 'Logged in successful ' . $isClient['FirstName'];
                $Status = 0;
                $timestamp = getTime($CurrentTimezone);
                $clientID = $isClient['ClientID'];
                insertMessage($connect, $SenderName, $clientID, $MessageType, $MessageContent, $timestamp, $Status);
                $response = [
                    'success' => true,
                    'role' => 'client',
                    'message' => 'welcome customer'
                ];
                echo json_encode($response);
                exit();
            }
        }
        if ($isAdmin) {
            // Admin login successful
            session_start();
            $_SESSION['adminID'] = $isAdmin['AdminID'];
            $_SESSION['Username'] = $isAdmin['Username'];
            // header("Location: ../views/setup.php");

            $response = [
                'success' => true,
                'role' => 'admin',
                'message' => 'welcome Admin'
            ];
            echo json_encode($response);
            exit();
        }


        $response = [
            'success' => true,
            'role' => 'undefined',
            'message' => 'Invalid email or password'
        ];
        echo json_encode($response);
        exit();
    }
} else {
    // If login fails, return an error response
    $response = [
        'success' => true,
        'role' => 'error',
        'message' => 'something went wrong'
    ];
    echo json_encode($response);
    exit();
}
