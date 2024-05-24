<?php
function insertEmailTemplate($connect, $category, $name, $subject, $body, $status)
{
    try {
        $query = "INSERT INTO emailtemplate (Category, Name, Subject, Body, Status) VALUES (:category, :name, :subject, :body, :status)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':category', $category);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':subject', $subject);
        $statement->bindParam(':body', $body);
        $statement->bindParam(':status', $status);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getEmailTemplate($connect)
{
    try {
        $emailQuery = "SELECT * FROM emailtemplate";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->execute();

        $clientData = $emailStatement->fetchAll(PDO::FETCH_ASSOC);

        return $clientData;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getEmailTemplateById($connect, $templateID)
{
    try {
        // Prepare SQL query with a WHERE clause to filter by templateID
        $emailQuery = "SELECT * FROM emailtemplate WHERE TemplateID = :templateID";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':templateID', $templateID, PDO::PARAM_INT);
        $emailStatement->execute();

        // Fetch the result
        $templateData = $emailStatement->fetch(PDO::FETCH_ASSOC);

        return $templateData;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function updateEmailTemplate($connect, $templateID, $subject, $body)
{
    try {
        // Prepare SQL query to update the email template
        $emailQuery = "UPDATE emailtemplate SET Subject = :subject, Body = :body WHERE TemplateID = :templateID";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':templateID', $templateID, PDO::PARAM_INT);
        $emailStatement->bindParam(':subject', $subject, PDO::PARAM_STR);
        $emailStatement->bindParam(':body', $body, PDO::PARAM_STR);

        // Execute the query
        $success = $emailStatement->execute();

        // Check if the query was successful
        if ($success) {
            // Return true if the update was successful
            return true;
        } else {
            // Return false if the update failed
            return false;
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


function sendEmail($to, $name, $subject, $message)
{
    require_once "config.php";
    $mail = new PHPMailer(true);

    try {
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = MAILERHOST;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = PORT;

        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;

        $mail->setFrom($to, $name);
        $mail->addAddress($to, $name);

        $mail->Subject = $subject;
        $mail->Body = $message;

        $isDelivered = $mail->send();

        // Check if the email was accepted for delivery
        if ($isDelivered) {
            // Email accepted for delivery
            return true;
        } else {
            // Email not accepted for delivery
            return false;
        }
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}



function deleteEmailTemplate($connect, $templateID)
{
    try {
        // Prepare SQL query to delete the template by its ID
        $deleteQuery = "DELETE FROM emailtemplate WHERE TemplateID = :templateID";
        $deleteStatement = $connect->prepare($deleteQuery);
        $deleteStatement->bindParam(':templateID', $templateID, PDO::PARAM_INT);

        // Execute the deletion query
        $deleteStatement->execute();

        // Check if any rows were affected (template deleted)
        if ($deleteStatement->rowCount() > 0) {
            return true; // Template deleted successfully
        } else {
            return false; // Template not found or deletion failed
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
