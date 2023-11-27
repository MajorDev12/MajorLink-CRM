<?php

function getPaymentMethods($connect)
{

    $sql = "SELECT PaymentOptionID, PaymentOptionName FROM paymentoptions";

    $statement = $connect->prepare($sql);

    $statement->execute();

    // Fetch all rows as an associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Return the fetched data
    return $result;
}
