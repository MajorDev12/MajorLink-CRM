<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



function updateProductData($ProductId, $ProductName, $ProductNotes, $ProductPrice, $connect)
{
    try {
        $query = "UPDATE products SET ProductName = :ProductName, Description = :ProductNotes, Price = :ProductPrice WHERE ProductID  = :ProductId";
        $statement = $connect->prepare($query);
        $statement->bindParam(':ProductName', $ProductName, PDO::PARAM_STR);
        $statement->bindParam(':ProductNotes', $ProductNotes, PDO::PARAM_STR);
        $statement->bindParam(':ProductPrice', $ProductPrice, PDO::PARAM_INT);
        $statement->bindParam(':ProductId', $ProductId, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
