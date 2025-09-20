<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["clientId"])) {
        sleep(2);
        require_once '../database/pdo.php';
        require_once '../modals/addClient_mod.php';

        $clientId = $_POST["clientId"];

        $connect = connectToDatabase($host, $dbname, $username, $password);

        $deleted = deleteClient($clientId, $connect);

        $output = array(
            'success'  =>   $deleted ? true : false
        );
        echo json_encode($output);
    } else {
        $output = array(
            'success'  =>   'No User id given'
        );
        echo json_encode($output);
    }
}
