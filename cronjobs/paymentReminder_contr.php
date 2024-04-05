<?php
// Connect to the database
// Include necessary files
require_once  '../database/pdo.php';
require_once  '../modals/getEmail_mod.php';
require "../includes/phpmailer/vendor/autoload.php";
$connect  = connectToDatabase($host, $dbname, $username, $password);

sendPaymentReminders($connect);


function sendPaymentReminders($connect)
{
    try {
        $expirationDate = date('Y-m-d', strtotime('+2 days'));

        $query = "SELECT * FROM clients WHERE ExpireDate = :expirationDate";
        $statement = $connect->prepare($query);
        $statement->bindParam(':expirationDate', $expirationDate);
        $statement->execute();
        $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($clients) {
            foreach ($clients as $client) {
                $to = $client['PrimaryEmail'];
                $name = $client['FirstName'];
                $from = "MajorLink";

                $templateID = 9;
                $emails = getEmailTemplateById($connect, $templateID);
                $Subject = $emails["Subject"];
                $body = $emails["Body"];

                // Replacements for template words
                $replacements = array(
                    'client_name' => $name,
                    'business_name' => 'MajorLink ISP', // Replace with your actual business name
                    'client_login_url' => 'https://example.com/login', // Replace with the client login URL
                    'client_email' => $to
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $subject = replaceTemplateWords($Subject, $replacements);


                sendEmail($to, $from, $subject, $message);
            }
        } else {
            echo "no data";
        }

        // echo "Payment reminders sent successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
