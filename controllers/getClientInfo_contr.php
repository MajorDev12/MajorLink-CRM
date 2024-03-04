<?php


if (isset($_GET['clientId'])) {
    require_once  '../database/pdo.php';
    require_once  '../modals/viewSingleUser_mod.php';

    $clientId = $_GET['clientId'];
    $connect  = connectToDatabase($host, $dbname, $username, $password);
    $clientData = getClientDataById($connect, $clientId);

    // Return the client data as JSON
    header('Content-Type: application/json');
    echo json_encode($clientData);
} else {
    // Handle the case where clientId is not provided
    echo json_encode(['error' => 'Client ID not provided']);
}
