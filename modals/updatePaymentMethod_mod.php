<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



function updatePaymentMethodData($PaymentMethodID, $updatedPaymentMethod, $connect)
{
    try {
        $query = "UPDATE paymentoptions SET PaymentOptionName = :updatedPaymentMethod WHERE PaymentOptionID = :PaymentMethodID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':updatedPaymentMethod', $updatedPaymentMethod, PDO::PARAM_STR);
        $statement->bindParam(':PaymentMethodID', $PaymentMethodID, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
