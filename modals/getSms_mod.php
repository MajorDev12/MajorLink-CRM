<?php

function insertSmsTemplate($connect, $category, $body, $status)
{
    try {
        $query = "INSERT INTO smstemplate (Category, Body, Status) VALUES (:category, :body, :status)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':category', $category);
        $statement->bindParam(':body', $body);
        $statement->bindParam(':status', $status);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}




function getSmsTemplate($connect)
{
    try {
        $emailQuery = "SELECT * FROM smstemplate";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->execute();

        $clientData = $emailStatement->fetchAll(PDO::FETCH_ASSOC);

        return $clientData;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function getSmsTemplateById($connect, $templateID)
{
    try {
        // Prepare SQL query with a WHERE clause to filter by templateID
        $emailQuery = "SELECT * FROM smstemplate WHERE TemplateID = :templateID";
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




function updateSmsTemplate($connect, $templateID, $body)
{
    try {
        // Prepare SQL query to update the email template
        $emailQuery = "UPDATE smstemplate SET Body = :body WHERE TemplateID = :templateID";
        $emailStatement = $connect->prepare($emailQuery);
        $emailStatement->bindParam(':templateID', $templateID, PDO::PARAM_INT);
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




function deleteSmsTemplate($connect, $templateID)
{
    try {
        // Prepare SQL query to delete the template by its ID
        $deleteQuery = "DELETE FROM smstemplate WHERE TemplateID = :templateID";
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
