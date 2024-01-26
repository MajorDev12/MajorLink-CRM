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



function setStripeTransaction($connect, $ClientID, $Customer_name, $Customer_email, $PaidAmount, $PaidCurrency, $TransactionID, $Payment_status, $Session_id)
{
    $sql = "INSERT INTO stripepayments (ClientID, Customer_name, Customer_email, PaidAmount, PaidCurrency, TransactionID, Payment_status, Session_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(1, $ClientID, PDO::PARAM_INT);
    $stmt->bindParam(2, $Customer_name, PDO::PARAM_STR);
    $stmt->bindParam(3, $Customer_email, PDO::PARAM_STR);
    // $stmt->bindParam(3, $productName, PDO::PARAM_STR);
    // $stmt->bindParam(4, $productID, PDO::PARAM_STR);
    // $stmt->bindParam(5, $productPrice, PDO::PARAM_STR);
    // $stmt->bindParam(6, $currency, PDO::PARAM_STR);
    $stmt->bindParam(4, $PaidAmount, PDO::PARAM_INT);
    $stmt->bindParam(5, $PaidCurrency, PDO::PARAM_STR);
    $stmt->bindParam(6, $TransactionID, PDO::PARAM_STR);
    $stmt->bindParam(7, $Payment_status, PDO::PARAM_STR);
    $stmt->bindParam(8, $Session_id, PDO::PARAM_STR);

    $insert = $stmt->execute();

    return $insert;
}
