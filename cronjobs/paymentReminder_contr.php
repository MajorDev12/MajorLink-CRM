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
        // Calculate the expiration date as two days before today
        $expirationDate = date('Y-m-d', strtotime('-2 days'));

        // Adjust the query to select clients whose invoices are two days overdue
        $query = "SELECT clients.*, invoices.* 
          FROM clients 
          JOIN invoices ON clients.ClientID = invoices.ClientID 
          WHERE invoices.DueDate = :expirationDate";

        $statement = $connect->prepare($query);
        $statement->bindParam(':expirationDate', $expirationDate);
        $statement->execute();
        $clients = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($clients) {
            foreach ($clients as $client) {
                $to = $client['PrimaryEmail'];
                $name = $client['FirstName'];
                $clientId = $client['ClientID'];

                // Get invoice details for the current client
                $invoiceId = $client['InvoiceID'];
                $invoiceNumber = $client['InvoiceNumber'];
                $invoiceAmount = number_format($client['TotalAmount'], 2);
                $invoiceDate = date('j F Y', strtotime($client['paymentDate']));
                $invoiceDueDate = date('j F Y', strtotime($client['DueDate']));

                $templateID = 2;
                $emails = getEmailTemplateById($connect, $templateID);
                $Subject = $emails["Subject"];
                $body = $emails["Body"];

                // Replacements for template words
                $replacements = array(
                    'client_name' => $name,
                    'business_name' => 'MajorLink ISP',
                    'client_login_url' => 'https://example.com/login',
                    'client_email' => $to,
                    'invoice_id' => $invoiceNumber,
                    'invoice_date' => $invoiceDate,
                    'invoice_url' => 'http://localhost/majorlink/user/viewInvoice.php?i=' . $invoiceId . '&c=' . $clientId,
                    'invoice_amount' => $invoiceAmount,
                    'invoice_due_date' => $invoiceDueDate
                );

                // Replace template words in the message
                $message = replaceTemplateWords($body, $replacements);
                $subject = replaceTemplateWords($Subject, $replacements);

                // sendEmail($to, $name, $subject, $message);
                $sent = sendOverdueReminder($connect, $clientId, $invoiceId, $to, $name, $subject, $message);
            }
            if ($sent) {
                echo "reminders sent successfully";
            } else {
                echo "No clients need a reminder.";
            }
        } else {
            echo "No clients with overdue invoices for now.";
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




function sendOverdueReminder($connect, $clientId, $invoiceId, $to, $name, $subject, $message)
{
    // Check if a reminder has already been sent for the specific invoice
    $query = "SELECT * FROM sent_email_reminders WHERE ClientID = :clientId AND InvoiceID = :invoiceId";
    $statement = $connect->prepare($query);
    $statement->bindParam(':clientId', $clientId);
    $statement->bindParam(':invoiceId', $invoiceId);
    $statement->execute();
    $existingReminder = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$existingReminder) {
        // Send the reminder email
        sendEmail($to, $name, $subject, $message);

        // Insert a record into the database indicating that a reminder has been sent
        $insertQuery = "INSERT INTO sent_email_reminders (ClientID, InvoiceID, ReminderSentDate) VALUES (:clientId, :invoiceId, NOW())";
        $insertStatement = $connect->prepare($insertQuery);
        $insertStatement->bindParam(':clientId', $clientId);
        $insertStatement->bindParam(':invoiceId', $invoiceId);
        $insertStatement->execute();

        return true; // Reminder sent successfully
    } else {
        return false; // Reminder already sent
    }
}
