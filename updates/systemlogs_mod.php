<?php

function insertLogData($pdo, $userID, $timestamp, $eventType, $eventDescription)
{
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO systemlogs (UserID, Timestamp, EventType, EventDescription) VALUES (:userID, :timestamp, :eventType, :eventDescription)");

        // Bind parameters
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->bindParam(':eventType', $eventType, PDO::PARAM_STR);
        $stmt->bindParam(':eventDescription', $eventDescription, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Return true if insertion is successful
        return true;
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}
