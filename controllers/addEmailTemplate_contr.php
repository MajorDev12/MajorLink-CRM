<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["newname"]) && isset($_POST["newsubject"]) && isset($_POST["newMessage"])) {
        require_once '../database/pdo.php';
        require_once '../modals/getEmail_mod.php';

        $name = $_POST["newname"];
        $subject = $_POST["newsubject"];
        $message = $_POST["newMessage"];
        $status = "custom";
        $category = "custom";


        $connect = connectToDatabase($host, $dbname, $username, $password);

        // Call the function to insert the email template
        $inserted = insertEmailTemplate($connect, $name, $subject, $message, $status);

        $output = array(
            'success'  =>   $inserted ? true : false
        );
        echo json_encode($output);
    }
}
