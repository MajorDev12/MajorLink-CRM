<?php


function  insertMessage($connect, $SenderName, $clientID, $MessageType, $MessageContent, $Timestamp, $Status)
{
    try {
        $query = "INSERT INTO messages (SenderName, RecipientID, MessageType, MessageContent, Timestamp, Status) VALUES (:SenderName, :clientID, :MessageType, :MessageContent, :Timestamp, :Status)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':SenderName', $SenderName);
        $statement->bindParam(':clientID', $clientID);
        $statement->bindParam(':MessageType', $MessageType);
        $statement->bindParam(':MessageContent', $MessageContent);
        $statement->bindParam(':Timestamp', $Timestamp);
        $statement->bindParam(':Status', $Status);
        $statement->bindParam(':MessageType', $MessageType);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getUnreadMessages($connect, $clientID)
{
    try {
        $query = "SELECT * FROM messages WHERE RecipientID = :clientID AND Status = 0";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientID', $clientID, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getMessagesByClientId($connect, $clientID)
{
    try {
        $query = "SELECT * FROM messages WHERE RecipientID = :clientID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientID', $clientID, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



function updateMessageStatus($connect, $clientID)
{
    try {
        $query = "UPDATE messages SET status = 1 WHERE clientID = :clientID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientID', $clientID, PDO::PARAM_INT);
        $statement->execute();
        // Optionally, you can return a success message or any other response here
        echo "Message status updated successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
