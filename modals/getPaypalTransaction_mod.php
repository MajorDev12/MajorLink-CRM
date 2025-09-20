<?php

function getPaypalOrderId($connect, $order_id)
{
    $sql = "SELECT * FROM paypalpayments WHERE Order_id = :order_id";
    $statement = $connect->prepare($sql);
    $statement->bindParam(':order_id', $order_id, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the result
    return $result;
}


function getPaypalTransactionId($connect, $payment_id)
{
    $sql = "SELECT PaymentID FROM paypalpayments WHERE PaymentID = :payment_id";
    $statement = $connect->prepare($sql);
    $statement->bindParam(":payment_id", $payment_id);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}



function setPaypalTransaction($connect, $ClientID, $Customer_name, $Customer_email, $PaidAmount, $PaidCurrency, $createdDate, $payment_id, $Payment_status, $order_id)
{
    $sql = "INSERT INTO paypalpayments (ClientID, Customer_name, Customer_email, PaidAmount, PaidCurrency, CreatedDate, PaymentID, Payment_status, Order_id) 
            VALUES (:ClientID, :Customer_name, :Customer_email, :PaidAmount, :PaidCurrency, :createdDate, :payment_id, :Payment_status, :order_id)";

    $stmt = $connect->prepare($sql);

    $stmt->bindParam(':ClientID', $ClientID, PDO::PARAM_INT);
    $stmt->bindParam(':Customer_name', $Customer_name, PDO::PARAM_STR);
    $stmt->bindParam(':Customer_email', $Customer_email, PDO::PARAM_STR);
    $stmt->bindParam(':PaidAmount', $PaidAmount, PDO::PARAM_INT);
    $stmt->bindParam(':PaidCurrency', $PaidCurrency, PDO::PARAM_STR);
    $stmt->bindParam(':createdDate', $createdDate);
    $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_STR);
    $stmt->bindParam(':Payment_status', $Payment_status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);

    $insert = $stmt->execute();

    return $insert;
}
