<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function insertPaymentMethodData($PaymentMethod, $connect)
{
    try {
        $query = "INSERT INTO paymentoptions (PaymentOptionName) VALUES (:PaymentMethod)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':PaymentMethod', $PaymentMethod, PDO::PARAM_STR);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getPaymentMethodData($connect)
{
    try {
        $query = "SELECT PaymentOptionID  , PaymentOptionName FROM paymentoptions";
        $statement = $connect->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
