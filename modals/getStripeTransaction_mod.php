<?php

function getStripeSessionId($connect, $session_id)
{
    $sql = "SELECT * FROM stripepayments WHERE Session_id = :session_id";
    $statement = $connect->prepare($sql);
    $statement->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the result
    return $result;
}


function getStripeTransactionId($connect, $transactionID)
{
    $sql = "SELECT TransactionID FROM stripepayments WHERE TransactionID = :TransactionID";
    $statement = $connect->prepare($sql);
    $statement->bindParam(":TransactionID", $transactionID);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}



function setStripeTransaction($connect, $ClientID, $Customer_name, $Customer_email, $PaidAmount, $PaidCurrency, $payment_id, $TransactionID, $Payment_status, $Session_id)
{
    $sql = "INSERT INTO stripepayments (ClientID, Customer_name, Customer_email, PaidAmount, PaidCurrency, PaymentID, TransactionID, Payment_status, Session_id) 
            VALUES (:ClientID, :Customer_name, :Customer_email, :PaidAmount, :PaidCurrency, :payment_id, :TransactionID, :Payment_status, :Session_id)";

    $stmt = $connect->prepare($sql);

    $stmt->bindParam(':ClientID', $ClientID, PDO::PARAM_INT);
    $stmt->bindParam(':Customer_name', $Customer_name, PDO::PARAM_STR);
    $stmt->bindParam(':Customer_email', $Customer_email, PDO::PARAM_STR);
    $stmt->bindParam(':PaidAmount', $PaidAmount, PDO::PARAM_INT);
    $stmt->bindParam(':PaidCurrency', $PaidCurrency, PDO::PARAM_STR);
    $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_STR);
    $stmt->bindParam(':TransactionID', $TransactionID, PDO::PARAM_STR);
    $stmt->bindParam(':Payment_status', $Payment_status, PDO::PARAM_STR);
    $stmt->bindParam(':Session_id', $Session_id, PDO::PARAM_STR);

    $insert = $stmt->execute();

    return $insert;
}


    // $stmt->bindParam(3, $productName, PDO::PARAM_STR);
    // $stmt->bindParam(4, $productID, PDO::PARAM_STR);
    // $stmt->bindParam(5, $productPrice, PDO::PARAM_STR);
    // $stmt->bindParam(6, $currency, PDO::PARAM_STR);