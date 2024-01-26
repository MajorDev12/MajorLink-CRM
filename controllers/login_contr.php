<?php

// Include your database connection and any necessary functions
require_once '../database/pdo.php';
require_once '../modals/validate_mod.php';
require_once '../modals/login_mod.php';

$connect = connectToDatabase($host, $dbname, $username, $password);
// Get the posted data
$postData = json_decode(file_get_contents("php://input"));

if ($postData) {
    sleep(2);
    $email = inputValidation($postData->email);
    $password = inputValidation($postData->password);


    if (!empty($email) && !empty($password)) {

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

        $hashedPassword = '123456';

        $options = [
            'cost' => 12
        ];
        // hash the password
        $PasswordHash = password_hash($hashedPassword, PASSWORD_BCRYPT, $options);

        $isTest = isTest($email, $PasswordHash, $connect);

        if ($isTest) {
            session_start();
            $_SESSION['testID'] = $isTest['ClientID'];
            $_SESSION['FirstName'] = $isTest['FirstName'];


            $response = [
                'success' => true,
                'role' => 'test_client',
                'message' => 'welcome client'
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
