<?php
function changeStatus($clientId, $activeStatus, $connect)
{
    try {
        $query = "UPDATE clients SET ActiveStatus = :activeStatus WHERE ClientID = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':activeStatus', $activeStatus);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
