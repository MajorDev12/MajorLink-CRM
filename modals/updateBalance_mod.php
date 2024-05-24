<?php



function setAccount($clientId, $balance, $connect)
{
    try {
        $query = "INSERT INTO clientaccounts (ClientID, Balance) VALUES (:clientId, :balance)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':balance', $balance);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        // Log or report the error, don't expose it to the UI
        error_log("Error setting up account: " . $e->getMessage(), 0);
        return false;
    }
}



function updateBalance($clientId, $amountToAdd, $connect)
{
    try {
        // Fetch the current balance
        $currentBalance = getCurrentBalance($clientId, $connect);

        // Calculate the new balance by adding the amount to the current balance
        $newBalance = $currentBalance + $amountToAdd;

        // Update the balance in the database
        $query = "UPDATE clientaccounts SET Balance = :newBalance WHERE ClientID = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':newBalance', $newBalance);
        $statement->execute();

        return true;
    } catch (PDOException $e) {
        // Log or report the error, don't expose it to the UI
        error_log("Error updating balance: " . $e->getMessage(), 0);
        return false;
    }
}



function getCurrentBalance($clientId, $connect)
{
    $query = "SELECT Balance FROM clientaccounts WHERE ClientID = :clientId";
    $statement = $connect->prepare($query);
    $statement->bindParam(':clientId', $clientId);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return ($result !== false) ? (int)$result['Balance'] : 0;
}
