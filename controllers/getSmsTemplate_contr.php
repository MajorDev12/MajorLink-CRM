<?php
if (!isset($_GET["t"])) {
    echo json_encode(array("error" => "Template ID is missing"));
    exit();
}

require_once '../database/pdo.php';
require_once '../modals/getSms_mod.php';

// Connect to the database
$connect = connectToDatabase($host, $dbname, $username, $password);

// Get the template ID from the GET parameters
$templateID = $_GET["t"];

// Get the email template data
$emailTemplate = getSmsTemplateById($connect, $templateID);

// Check if the template exists
if ($emailTemplate) {
    // Return the email template data as JSON response
    echo json_encode($emailTemplate);
} else {
    // Return an error message if the template does not exist
    echo json_encode(array("error" => "Template not found"));
}
