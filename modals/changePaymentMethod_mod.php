<?php

function update_preferedPaymentMethod($connect, $clientID, $selectedPaymentId)
{
    try {
        // Fetch advance payment data for the specific ClientId
        $query = "UPDATE clients SET PreferedPaymentMethod = :selectedPaymentId WHERE ClientID = :clientID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':selectedPaymentId', $selectedPaymentId);
        $statement->bindParam(':clientID', $clientID);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
