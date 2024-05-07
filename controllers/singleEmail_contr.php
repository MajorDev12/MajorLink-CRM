<?php

require_once "../controllers/session_Config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["selectedCustomer"], $_POST["subject"], $_POST["message"])) {
        // Include necessary files
        require_once '../database/pdo.php';
        require_once '../modals/getEmail_mod.php';
        require "../includes/phpmailer/vendor/autoload.php";


        $selectedCustomer = $_POST["selectedCustomer"];
        $Subject = $_POST["subject"];
        $body = $_POST["message"];

        $connect = connectToDatabase($host, $dbname, $username, $password);

        try {
            // Query to retrieve clients with area IDs in the checkedValues array
            $query = "SELECT * FROM clients WHERE ClientID = :selectedCustomer";
            $statement = $connect->prepare($query);
            $statement->bindParam(':selectedCustomer', $selectedCustomer, PDO::PARAM_INT);
            $statement->execute();

            // Fetch the clients data
            $client = $statement->fetch(PDO::FETCH_ASSOC);


            if ($client) {
                // Get the client's email and other necessary details
                $to = $client['PrimaryEmail'];
                $from = "MajorLink"; // Assuming you have a FirstName field in your clients table
                $name = $client['FirstName'] . ' ' . $client['LastName']; // Assuming you have a FirstName field in your clients table

                $replacements = array(
                    'client_name' => $name,
                    'business_name' => 'MajorLink ISP', // Replace with your actual business name
                    'client_email' => $to
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $subject = replaceTemplateWords($Subject, $replacements);


                $sent = sendEmail($to, $from, $subject, $message);

                $output = array(
                    'success' => $sent ? true : false,
                    'message' => $sent ? 'Sent Successfully' : 'Something went wrong while sending'

                );
                echo json_encode($output);
                exit();
            } else {
                $output = array(
                    'success'  =>  false,
                    'message'  =>  'No Recipient found'
                );
                echo json_encode($output);
                exit();
            }
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Database error: " . $e->getMessage()));
        }
    } else {
        $output = array(
            'success'  =>  false,
            'message'  =>  'Something is missing'
        );
        echo json_encode($output);
        exit();
    }
}





function replaceTemplateWords($message, $replacements)
{
    // Iterate through each replacement key-value pair
    foreach ($replacements as $key => $value) {
        // Replace the template word (e.g., {{client_name}}) with its corresponding value
        $message = str_replace('{{' . $key . '}}', $value, $message);
    }

    // Return the message with replaced template words
    return $message;
}
