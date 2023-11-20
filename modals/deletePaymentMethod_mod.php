<?php

function deletePaymentMethodId($PaymentMethodID, $connect)
{
    try {
        $query = "DELETE FROM paymentoptions WHERE PaymentOptionID   = :PaymentMethodID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':PaymentMethodID', $PaymentMethodID, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        // Handle any exceptions or errors that occur during the query execution
        // For example, log the error, display an error message, or redirect to an error page
        echo "Error: " . $e->getMessage();
        return false;
    }
}
