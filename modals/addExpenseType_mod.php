<?php
function inputValidation($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function insertexpenseTypeData($expenseType, $connect)
{
    try {
        $query = "INSERT INTO expensetypes (ExpenseTypeName) VALUES (:expenseType)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':expenseType', $expenseType, PDO::PARAM_STR);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


function getExpenseTypeData($connect)
{
    try {
        $query = "SELECT ExpenseTypeID , ExpenseTypeName FROM expensetypes";
        $statement = $connect->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
