<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["newMessage"])) {
        require_once '../database/pdo.php';
        require_once '../modals/getSms_mod.php';

        $message = $_POST["newMessage"];
        $status = "Custom";
        $category = "Custom";


        $connect = connectToDatabase($host, $dbname, $username, $password);

        // Call the function to insert the email template
        $inserted =  insertSmsTemplate($connect, $category, $message, $status);

        $output = array(
            'success'  =>   $inserted ? true : false
        );
        echo json_encode($output);
    }
}
