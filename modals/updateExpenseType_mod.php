<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



function updateProductData($expenseTypeID, $updatedexpenseType, $connect)
{
    try {
        $query = "UPDATE expensetypes SET ExpenseTypeName = :updatedexpenseType WHERE ExpenseTypeID   = :expenseTypeID";
        $statement = $connect->prepare($query);
        $statement->bindParam(':updatedexpenseType', $updatedexpenseType, PDO::PARAM_STR);
        $statement->bindParam(':expenseTypeID', $expenseTypeID, PDO::PARAM_INT);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
