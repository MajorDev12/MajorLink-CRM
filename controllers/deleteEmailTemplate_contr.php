<?php

require_once "../controllers/session_Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["templateId"])) {
        require_once '../database/pdo.php';
        require_once '../modals/getEmail_mod.php';

        $templateID = $_POST["templateId"];

        $connect = connectToDatabase($host, $dbname, $username, $password);

        // Call the function to insert the email template
        $deleted = deleteEmailTemplate($connect, $templateID);

        $output = array(
            'success'  =>   $deleted ? true : false
        );
        echo json_encode($output);
    }
}
