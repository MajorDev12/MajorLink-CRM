<?php


function insertPaymentData($clientId, $PlanID, $invoiceNumber, $PlanAmount, $paymentStatus, $paymentDate, $paymentMethodID, $InstallationFees, $connect)
{
    try {
        $query = "INSERT INTO Payments (ClientID, PlanID, InvoiceNumber, PaymentAmount, PaymentStatus, PaymentDate, PaymentOptionID, InstallationFees) VALUES (:clientId, :PlanID, :invoiceNumber, :PlanAmount, :paymentStatus, :paymentDate, :paymentMethodID, :InstallationFees)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':clientId', $clientId);
        $statement->bindParam(':PlanID', $PlanID);
        $statement->bindParam(':invoiceNumber', $invoiceNumber);
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


function getPayments($connect, $clientId)
{
    try {
        $sql = "SELECT 
            payments.PaymentAmount,
            payments.PaymentStatus,
            payments.InvoiceNumber,
            payments.PaymentDate,
            paymentoptions.PaymentOptionName,
            plans.Volume 
        FROM payments
        LEFT JOIN paymentoptions ON payments.PaymentOptionID = paymentoptions.PaymentOptionID
        LEFT JOIN plans ON payments.PlanID = plans.PlanID 
        WHERE payments.ClientID = :clientId";

        $statement = $connect->prepare($sql);
        $statement->bindParam(":clientId", $clientId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}





function getAllPayments($connect)
{
    try {
        $sql = "(SELECT 
                    'Plan' AS type,
                    payments.PaymentAmount,
                    payments.PaymentStatus,
                    payments.InvoiceNumber,
                    payments.PaymentDate,
                    paymentoptions.PaymentOptionName
                FROM payments
                LEFT JOIN paymentoptions ON payments.PaymentOptionID = paymentoptions.PaymentOptionID
                LEFT JOIN plans ON payments.PlanID = plans.PlanID)
                UNION
                (SELECT 
                    'Product' AS type,
                    sales.Total,
                    sales.PaymentStatus,
                    sales.InvoiceNumber,
                    sales.SaleDate AS PaymentDate,
                    paymentoptions.PaymentOptionName
                FROM sales
                LEFT JOIN paymentoptions ON sales.PaymentOptionID = paymentoptions.PaymentOptionID)
                ORDER BY PaymentDate DESC";

        $statement = $connect->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        return array("error" => "Error: " . $e->getMessage()); // Return an array with an error message
    }
}
