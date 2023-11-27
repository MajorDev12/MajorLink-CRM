<?php


function insertPaymentData($clientId, $PlanID, $PlanAmount, $paymentStatus, $paymentDate, $InstallationFees, $connect)
{
    try {
        $query = "INSERT INTO Payments (ClientID, PlanID, PaymentAmount, PaymentStatus, PaymentDate, InstallationFees) VALUES (:clientId, :PlanID, :PlanAmount, :paymentStatus, :paymentDate, :InstallationFees)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':PlanID', $PlanID);
        $statement->bindParam(':PlanAmount', $PlanAmount);
        $statement->bindParam(':paymentStatus', $paymentStatus);
        $statement->bindParam(':paymentDate', $paymentDate);  // Fix the case here
        $statement->bindParam(':InstallationFees', $InstallationFees);  // Fix the case here
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function updatePlan($clientId, $PlanID, $expireDate, $connect)
{
    try {
        $query = "UPDATE clients SET PlanID = :PlanID, ExpireDate = :expireDate WHERE ClientID = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':PlanID', $PlanID);
        $statement->bindParam(':expireDate', $expireDate);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        false;
        return "Error: " . $e->getMessage();
    }
}
