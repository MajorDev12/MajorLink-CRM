<?php

function changePassword($clientID, $PasswordHash, $connect)
{
    try {
        // Fetch advance payment data for the specific ClientId
        $query = "UPDATE clients SET PasswordHash = :PasswordHash WHERE ClientID = :clientID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':PasswordHash', $PasswordHash);
        $statement->bindParam(':clientID', $clientID);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
