<?php


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
