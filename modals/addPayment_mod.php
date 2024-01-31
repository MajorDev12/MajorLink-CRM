<?php


function insertPaymentData($clientId, $PlanID, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect)
{
    try {
        $query = "INSERT INTO Payments (ClientID, PlanID, PaymentAmount, PaymentStatus, PaymentDate, PaymentOptionID, InstallationFees) VALUES (:clientId, :PlanID, :PlanAmount, :paymentStatus, :paymentDate, :paymentMethodID, :InstallationFees)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':PlanID', $PlanID);
        $statement->bindParam(':PlanAmount', $PlanAmount);
        $statement->bindParam(':paymentStatus', $paymentStatus);
        $statement->bindParam(':paymentDate', $paymentDate);
        $statement->bindParam(':paymentMethodID', $paymentMethodID);
        $statement->bindParam(':InstallationFees', $InstallationFees);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function updatePlan($clientId, $PlanID, $expireDate, $last_paymentDate, $connect)
{
    try {
        $query = "UPDATE clients SET PlanID = :PlanID, ExpireDate = :expireDate, LastPayment = :last_paymentDate WHERE ClientID = :clientId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':PlanID', $PlanID);
        $statement->bindParam(':expireDate', $expireDate);
        $statement->bindParam(':last_paymentDate', $last_paymentDate);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        false;
        return "Error: " . $e->getMessage();
    }
}
