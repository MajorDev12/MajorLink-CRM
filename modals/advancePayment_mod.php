<?php

function insertadvancePaymentData($clientId, $paymentDate, $fromDate, $toDate, $PaymentMethod, $selectedPlan, $amountPaid, $CreatedDate, $connect)
{
    try {
        $query = "INSERT INTO advancepayments (ClientID, PaymentDate, FromDate, ToDate, PaymentOptionID, PlanID, AmountPaid, CreatedDate) VALUES (:clientId, :paymentDate, :fromDate, :toDate, :PaymentMethod, :selectedPlan, :amountPaid, :CreatedDate)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':paymentDate', $paymentDate);
        $statement->bindParam(':fromDate', $fromDate);
        $statement->bindParam(':toDate', $toDate);
        $statement->bindParam(':PaymentMethod', $PaymentMethod);
        $statement->bindParam(':selectedPlan', $selectedPlan);
        $statement->bindParam(':amountPaid', $amountPaid);
        $statement->bindParam(':CreatedDate', $CreatedDate);
        $statement->execute();
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
