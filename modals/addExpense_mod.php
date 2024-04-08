<?php

function addExpense($connect, $expenseDate, $expenseSelected, $amountSpent, $methodSelected, $expenseDescription, $PaymentRecieptFile)
{
    try {
        $query = "INSERT INTO expenses (ExpenseDate, ExpenseTypeID, ExpenseAmount, PaymentOptionID, Description, PaymentReceiptURL) VALUES (:expenseDate, :expenseSelected, :amountSpent, :methodSelected, :expenseDescription, :PaymentRecieptFile)";
        $statement = $connect->prepare($query);
        $statement->bindParam(':expenseDate', $expenseDate, PDO::PARAM_STR);
        $statement->bindParam(':expenseSelected', $expenseSelected, PDO::PARAM_INT);
        $statement->bindParam(':amountSpent', $amountSpent, PDO::PARAM_INT);
        $statement->bindParam(':methodSelected', $methodSelected, PDO::PARAM_INT);
        $statement->bindParam(':expenseDescription', $expenseDescription, PDO::PARAM_STR);
        $statement->bindParam(':PaymentRecieptFile', $PaymentRecieptFile);
        $statement->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
